-- MySQL dump 10.2
--
-- Host: localhost    Database: blog
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
INSERT INTO ezcontentbrowserecent VALUES (35,111,99,1067006746,'foo bar corp'),(94,10,2,1069411387,'Blog'),(65,149,135,1068126974,'lkj ssssstick'),(64,206,135,1068123651,'lkj ssssstick'),(92,14,154,1068796296,'Computers');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1068804191),(2,0,'Article','article','<title>',14,14,1024392098,1066907423),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1068123024),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069677671),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(25,0,'Poll','poll','<name>',14,14,1068716373,1068717758),(24,0,'Link','link','<name>',14,14,1068716182,1068716360),(23,0,'Log','log','<title>',14,14,1068710873,1068718967),(26,0,'Comment','comment','<name>',14,14,1068716787,1068716858),(14,1,'Setup link','setup_link','<title>',14,14,1066383719,1069334255),(25,1,'Poll','poll','<name>',14,14,1068716373,1069411484),(26,1,'Comment','comment','<name>',14,14,1068716787,1069411500);
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
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(214,0,1,'list_title','List title','ezstring',0,1,4,0,0,0,0,0,0,0,0,'Recent items','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(198,0,4,'location','Location','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(199,0,4,'signature','Signature','eztext',1,0,6,2,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(203,0,23,'log','Log','ezxmltext',1,0,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(207,0,25,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(208,0,25,'option','Option','ezoption',0,1,2,0,0,0,0,0,0,0,0,'','','','','',1,1),(205,0,24,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1),(206,0,24,'url','URL','ezurl',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(204,0,24,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(202,0,23,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(212,0,26,'comment','Comment','eztext',1,0,4,10,0,0,0,0,0,0,0,'','','','','',0,1),(200,0,4,'user_image','User image','ezimage',0,0,7,1,0,0,0,0,0,0,0,'','','','','',0,1),(197,0,4,'title','Title','ezstring',1,0,4,25,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(213,0,23,'enable_comments','Enable comments','ezboolean',1,0,3,0,0,1,0,0,0,0,0,'','','','','',0,1),(211,0,26,'url','URL','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(210,0,26,'email','E-mail','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(209,0,26,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(215,0,1,'category_list_title','Category list title','ezstring',0,1,5,0,0,0,0,0,0,0,0,'All Categories','','','','',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1),(216,0,1,'archive_title','Archive Title','ezstring',0,1,3,0,0,0,0,0,0,0,0,'Item Archive','','','','',0,1),(119,0,1,'description','Description','ezxmltext',1,0,2,5,0,0,0,0,0,0,0,'','','','','',0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','','',0,1),(153,1,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,1,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(154,1,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(152,1,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(207,1,25,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(208,1,25,'option','Option','ezoption',0,1,2,0,0,0,0,0,0,0,0,'','','','','',1,1),(212,1,26,'comment','Comment','eztext',1,0,4,10,0,0,0,0,0,0,0,'','','','','',0,1),(209,1,26,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(210,1,26,'email','E-mail','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(211,1,26,'url','URL','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(160,0,15,'css','CSS','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'css','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3;4;5','override;user;admin;demo;blog_admin;blog_user',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(21,1,1,'Content'),(20,0,1,'Content'),(21,0,1,'Content'),(25,1,1,'Content'),(23,0,1,'Content'),(26,0,1,'Content'),(24,0,1,'Content'),(25,0,1,'Content'),(14,1,4,'Setup'),(26,1,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Blog',6,0,1033917596,1068710485,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',5,0,1033920830,1068468219,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',9,0,1066384365,1068729357,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',10,0,1066388816,1068729376,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,13,1,'Blogs',5,0,1066398020,1068804689,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Blog',68,0,1066643397,1069686910,1,''),(214,14,13,23,'Today I got my new car!',1,0,1068711140,1068711140,1,''),(215,14,13,23,'Special things happened today',1,0,1068713677,1068713677,1,''),(212,14,13,1,'Personal',2,0,1068711069,1068717667,1,''),(161,14,1,10,'About me',2,0,1068047603,1068710511,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,2,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(213,14,13,1,'Computers',2,0,1068711091,1068717696,1,''),(165,149,1,21,'New Forum topic',1,0,0,0,2,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(107,14,2,4,'John Doe',2,0,1066916865,1066916941,1,''),(220,14,13,26,'båbåb',1,0,1068716967,1068716967,1,''),(111,14,2,4,'vid la',1,0,1066917523,1066917523,1,''),(115,14,11,14,'Cache',4,0,1066991725,1068729308,1,''),(116,14,11,14,'URL translator',3,0,1066992054,1068729395,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(218,14,1,25,'New Poll',1,0,0,0,0,''),(219,14,13,26,'Bård Farstad',1,0,1068716920,1068716920,1,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(216,14,1,25,'New Poll',1,0,0,0,0,''),(149,14,2,4,'wenyue yu',8,0,1068041016,1068130543,1,''),(217,14,1,25,'New Poll',1,0,0,0,0,''),(168,149,0,21,'New Forum topic',1,0,0,0,2,''),(169,149,0,21,'New Forum topic',1,0,0,0,2,''),(171,149,1,21,'New Forum topic',1,0,0,0,2,''),(172,149,0,21,'New Forum topic',1,0,0,0,2,''),(173,149,0,21,'New Forum topic',1,0,0,0,2,''),(174,149,0,21,'New Forum topic',1,0,0,0,2,''),(175,149,0,21,'New Forum topic',1,0,0,0,2,''),(176,149,0,21,'New Forum topic',1,0,0,0,2,''),(177,149,0,21,'New Forum topic',1,0,0,0,2,''),(178,149,0,21,'New Forum topic',1,0,0,0,2,''),(179,149,0,21,'New Forum topic',1,0,0,0,2,''),(180,149,0,21,'New Forum topic',1,0,0,0,2,''),(181,149,0,21,'New Forum topic',1,0,0,0,2,''),(182,149,0,21,'New Forum topic',1,0,0,0,2,''),(183,149,0,21,'New Forum topic',1,0,0,0,2,''),(184,149,0,21,'New Forum topic',1,0,0,0,2,''),(185,149,0,21,'New Forum topic',1,0,0,0,2,''),(186,149,0,21,'New Forum topic',1,0,0,0,2,''),(187,14,1,4,'New User',1,0,0,0,0,''),(191,149,1,21,'New Forum topic',1,0,0,0,2,''),(189,14,1,4,'New User',1,0,0,0,0,''),(192,149,0,21,'New Forum topic',1,0,0,0,2,''),(193,149,0,21,'New Forum topic',1,0,0,0,2,''),(194,149,0,21,'New Forum topic',1,0,0,0,2,''),(200,149,1,21,'New Forum topic',1,0,0,0,2,''),(201,149,1,22,'New Forum reply',1,0,0,0,2,''),(206,14,2,4,'Bård Farstad',1,0,1068123599,1068123599,1,''),(221,14,1,25,'New Poll',1,0,0,0,0,''),(222,14,1,25,'New Poll',1,0,0,0,0,''),(224,14,1,25,'New Poll',1,0,0,0,0,''),(225,14,1,25,'New Poll',1,0,0,0,0,''),(226,14,13,23,'For Posterity\'s Sake',1,0,1068717935,1068717935,1,''),(227,14,1,25,'Which color do you like?',2,0,1068718128,1068719696,1,''),(228,14,12,1,'Links',4,0,1068718629,1068804675,1,''),(229,14,12,1,'Software',1,0,1068718672,1068718672,1,''),(230,14,12,1,'Movie',1,0,1068718712,1068718712,1,''),(231,14,12,1,'News',1,0,1068718746,1068718746,1,''),(232,14,12,24,'VG',1,0,1068718861,1068718861,1,''),(233,14,13,26,'bård',1,0,1068718705,1068718705,1,''),(234,14,12,24,'Sina',1,0,1068718957,1068718957,1,''),(235,14,13,26,'kjh',1,0,1068718760,1068718760,1,''),(236,14,12,24,'Soft house',1,0,1068719251,1068719251,1,''),(237,14,13,23,'New big discovery today',1,0,1068719051,1068719051,1,''),(238,14,13,23,'No comments on this one',1,0,1068719129,1068719129,1,''),(239,14,13,26,'Bård',1,0,1068719374,1068719374,1,''),(240,14,1,1,'Polls',3,0,1068719643,1069172879,1,''),(241,14,1,25,'Which one is the best of matrix movies?',1,0,1068720802,1068720802,1,''),(242,14,13,26,'ghghj',1,0,1068720915,1068720915,1,''),(243,14,13,1,'Entertainment',1,0,1068727871,1068727871,1,''),(244,14,13,23,'A Pirate\'s Life',1,0,1068727918,1068727918,1,''),(245,14,13,26,'kjlh',1,0,1068730476,1068730476,1,''),(246,14,13,26,'kjhkjh',1,0,1068737197,1068737197,1,''),(247,14,13,23,'I overslept today',1,0,1068796296,1068796296,1,''),(248,10,13,26,'ete',1,0,1069409151,1069409151,1,''),(249,10,13,26,'New Comment',1,0,0,0,0,''),(250,10,0,26,'New Comment',1,0,0,0,0,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Blog',0,0,0,0,'blog','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring'),(29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring'),(30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',1,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',1,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',1,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',1,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',1,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',1,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',1,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',1,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(810,'eng-GB',1,235,211,'kljh',0,0,0,0,'kljh','ezstring'),(811,'eng-GB',1,235,212,'< >\n\n:)\n\nhttp://ez.no',0,0,0,0,'','eztext'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(809,'eng-GB',1,235,210,'kjlh',0,0,0,0,'kjlh','ezstring'),(676,'eng-GB',1,200,190,'',0,0,0,0,'','ezboolean'),(677,'eng-GB',1,200,194,'',0,0,0,0,'','ezsubtreesubscription'),(678,'eng-GB',1,201,191,'Re:test',0,0,0,0,'re:test','ezstring'),(679,'eng-GB',1,201,193,'fdsf',0,0,0,0,'','eztext'),(817,'eng-GB',1,226,213,'',1,0,0,1,'','ezboolean'),(816,'eng-GB',1,215,213,'',1,0,0,1,'','ezboolean'),(815,'eng-GB',1,214,213,'',1,0,0,1,'','ezboolean'),(814,'eng-GB',1,236,206,'hzinfo',4,0,0,0,'','ezurl'),(813,'eng-GB',1,236,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Download software here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(812,'eng-GB',1,236,204,'Soft house',0,0,0,0,'soft house','ezstring'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(558,'eng-GB',1,171,189,'',0,0,0,0,'','eztext'),(553,'eng-GB',1,169,190,'',0,0,0,0,'','ezboolean'),(554,'eng-GB',1,169,194,'',0,0,0,0,'','ezsubtreesubscription'),(557,'eng-GB',1,171,188,'',0,0,0,0,'','ezstring'),(552,'eng-GB',1,169,189,'sfsvggs\nsfsf',0,0,0,0,'','eztext'),(547,'eng-GB',1,168,188,'',0,0,0,0,'','ezstring'),(548,'eng-GB',1,168,189,'',0,0,0,0,'','eztext'),(549,'eng-GB',1,168,190,'',0,0,0,0,'','ezboolean'),(550,'eng-GB',1,168,194,'',0,0,0,0,'','ezsubtreesubscription'),(551,'eng-GB',1,169,188,'test',0,0,0,0,'test','ezstring'),(767,'eng-GB',1,215,202,'Special things happened today',0,0,0,0,'special things happened today','ezstring'),(768,'eng-GB',1,215,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <link id=\"1\">sd oifgu sdoiguosdiu gfosdfg d</link>dfg sdfg sdfg sdfg sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n  <paragraph>sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n  <paragraph>\n    <link id=\"1\">sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg</link> sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.sdfg sdfg sdfhsdjkghsdigfu iosdf g.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(765,'eng-GB',1,214,202,'Today I got my new car!',0,0,0,0,'today i got my new car!','ezstring'),(766,'eng-GB',1,214,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf </paragraph>\n  <paragraph>\n    <strong>sdgfklj sdfklgj sdlkfg </strong>lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf </paragraph>\n  <paragraph>\n    <strong>sdgfklj sdfklgj sdlkfg lsdgf sdgf </strong>sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf sdgfklj sdfklgj sdlkfg lsdgf sdgf </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(762,'eng-GB',1,212,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(763,'eng-GB',1,213,4,'Computers',0,0,0,0,'computers','ezstring'),(764,'eng-GB',1,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(761,'eng-GB',1,212,4,'Personal',0,0,0,0,'personal','ezstring'),(671,'eng-GB',66,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(535,'eng-GB',1,165,188,'',0,0,0,0,'','ezstring'),(536,'eng-GB',1,165,189,'',0,0,0,0,'','eztext'),(537,'eng-GB',1,165,190,'',0,0,0,0,'','ezboolean'),(538,'eng-GB',1,165,194,'',0,0,0,0,'','ezsubtreesubscription'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',61,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"60\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(740,'eng-GB',1,206,8,'Bård',0,0,0,0,'bård','ezstring'),(741,'eng-GB',1,206,9,'Farstad',0,0,0,0,'farstad','ezstring'),(742,'eng-GB',1,206,12,'',0,0,0,0,'','ezuser'),(743,'eng-GB',1,206,197,'music guru',0,0,0,0,'music guru','ezstring'),(744,'eng-GB',1,206,198,'Oslo/Norway',0,0,0,0,'oslo/norway','ezstring'),(745,'eng-GB',1,206,199,'sig..',0,0,0,0,'','eztext'),(746,'eng-GB',1,206,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"brd_farstad.jpg\"\n         suffix=\"jpg\"\n         basename=\"brd_farstad\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad.jpg\"\n         original_filename=\"dscn9284.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"2cv\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"brd_farstad_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"brd_farstad_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"225\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"brd_farstad_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/brd_farstad/746-1-eng-GB/brd_farstad_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(639,'eng-GB',1,192,189,'',0,0,0,0,'','eztext'),(640,'eng-GB',1,192,190,'',0,0,0,0,'','ezboolean'),(483,'eng-GB',2,149,12,'',0,0,0,0,'','ezuser'),(634,'eng-GB',1,191,188,'',0,0,0,0,'','ezstring'),(635,'eng-GB',1,191,189,'',0,0,0,0,'','eztext'),(636,'eng-GB',1,191,190,'',0,0,0,0,'','ezboolean'),(637,'eng-GB',1,191,194,'',0,0,0,0,'','ezsubtreesubscription'),(638,'eng-GB',1,192,188,'',0,0,0,0,'','ezstring'),(609,'eng-GB',1,184,188,'',0,0,0,0,'','ezstring'),(610,'eng-GB',1,184,189,'',0,0,0,0,'','eztext'),(611,'eng-GB',1,184,190,'',0,0,0,0,'','ezboolean'),(612,'eng-GB',1,184,194,'',0,0,0,0,'','ezsubtreesubscription'),(613,'eng-GB',1,185,188,'',0,0,0,0,'','ezstring'),(614,'eng-GB',1,185,189,'',0,0,0,0,'','eztext'),(615,'eng-GB',1,185,190,'',0,0,0,0,'','ezboolean'),(616,'eng-GB',1,185,194,'',0,0,0,0,'','ezsubtreesubscription'),(617,'eng-GB',1,186,188,'',0,0,0,0,'','ezstring'),(618,'eng-GB',1,186,189,'',0,0,0,0,'','eztext'),(619,'eng-GB',1,186,190,'',0,0,0,0,'','ezboolean'),(620,'eng-GB',1,186,194,'',0,0,0,0,'','ezsubtreesubscription'),(482,'eng-GB',2,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',2,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(603,'eng-GB',1,182,190,'',0,0,0,0,'','ezboolean'),(604,'eng-GB',1,182,194,'',0,0,0,0,'','ezsubtreesubscription'),(605,'eng-GB',1,183,188,'',0,0,0,0,'','ezstring'),(606,'eng-GB',1,183,189,'',0,0,0,0,'','eztext'),(607,'eng-GB',1,183,190,'',0,0,0,0,'','ezboolean'),(608,'eng-GB',1,183,194,'',0,0,0,0,'','ezsubtreesubscription'),(602,'eng-GB',1,182,189,'',0,0,0,0,'','eztext'),(730,'eng-GB',2,14,200,'',0,0,0,0,'','ezimage'),(731,'eng-GB',3,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"administrator_user.\"\n         suffix=\"\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-3-eng-GB/administrator_user.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(732,'eng-GB',1,107,200,'',0,0,0,0,'','ezimage'),(733,'eng-GB',2,107,200,'',0,0,0,0,'','ezimage'),(734,'eng-GB',1,111,200,'',0,0,0,0,'','ezimage'),(735,'eng-GB',1,149,200,'',0,0,0,0,'','ezimage'),(736,'eng-GB',2,149,200,'',0,0,0,0,'','ezimage'),(737,'eng-GB',3,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(729,'eng-GB',1,14,200,'',0,0,0,0,'','ezimage'),(728,'eng-GB',1,10,200,'',0,0,0,0,'','ezimage'),(675,'eng-GB',1,200,189,'sefsefsf\nsf\nsf',0,0,0,0,'','eztext'),(601,'eng-GB',1,182,188,'',0,0,0,0,'','ezstring'),(716,'eng-GB',1,10,199,'',0,0,0,0,'','eztext'),(717,'eng-GB',1,14,199,'',0,0,0,0,'','eztext'),(718,'eng-GB',2,14,199,'',0,0,0,0,'','eztext'),(719,'eng-GB',3,14,199,'developer... ;)',0,0,0,0,'','eztext'),(720,'eng-GB',1,107,199,'',0,0,0,0,'','eztext'),(721,'eng-GB',2,107,199,'',0,0,0,0,'','eztext'),(722,'eng-GB',1,111,199,'',0,0,0,0,'','eztext'),(723,'eng-GB',1,149,199,'',0,0,0,0,'','eztext'),(724,'eng-GB',2,149,199,'',0,0,0,0,'','eztext'),(725,'eng-GB',3,149,199,'',0,0,0,0,'','eztext'),(692,'eng-GB',1,10,197,'',0,0,0,0,'','ezstring'),(693,'eng-GB',1,14,197,'',0,0,0,0,'','ezstring'),(694,'eng-GB',2,14,197,'',0,0,0,0,'','ezstring'),(695,'eng-GB',3,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(696,'eng-GB',1,107,197,'',0,0,0,0,'','ezstring'),(697,'eng-GB',2,107,197,'',0,0,0,0,'','ezstring'),(698,'eng-GB',1,111,197,'',0,0,0,0,'','ezstring'),(699,'eng-GB',1,149,197,'',0,0,0,0,'','ezstring'),(700,'eng-GB',2,149,197,'',0,0,0,0,'','ezstring'),(701,'eng-GB',3,149,197,'',0,0,0,0,'','ezstring'),(704,'eng-GB',1,10,198,'',0,0,0,0,'','ezstring'),(705,'eng-GB',1,14,198,'',0,0,0,0,'','ezstring'),(706,'eng-GB',2,14,198,'',0,0,0,0,'','ezstring'),(707,'eng-GB',3,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(708,'eng-GB',1,107,198,'',0,0,0,0,'','ezstring'),(709,'eng-GB',2,107,198,'',0,0,0,0,'','ezstring'),(710,'eng-GB',1,111,198,'',0,0,0,0,'','ezstring'),(711,'eng-GB',1,149,198,'',0,0,0,0,'','ezstring'),(712,'eng-GB',2,149,198,'',0,0,0,0,'','ezstring'),(713,'eng-GB',3,149,198,'',0,0,0,0,'','ezstring'),(820,'eng-GB',1,237,213,'',1,0,0,1,'','ezboolean'),(819,'eng-GB',1,237,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>I discovered the Internet, it&apos;s big - about 20meters. dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </line>\n    <line>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </line>\n  </paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg v</paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </paragraph>\n  <paragraph>dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg dsfg sdfg sdfg </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(818,'eng-GB',1,237,202,'New big discovery today',0,0,0,0,'new big discovery today','ezstring'),(808,'eng-GB',1,235,209,'kjh',0,0,0,0,'kjh','ezstring'),(302,'eng-GB',1,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',1,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',1,107,12,'',0,0,0,0,'','ezuser'),(302,'eng-GB',2,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',2,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',2,107,12,'',0,0,0,0,'','ezuser'),(315,'eng-GB',1,111,12,'',0,0,0,0,'','ezuser'),(313,'eng-GB',1,111,8,'vid',0,0,0,0,'vid','ezstring'),(314,'eng-GB',1,111,9,'la',0,0,0,0,'la','ezstring'),(1,'eng-GB',2,1,4,'Corporate',0,0,0,0,'corporate','ezstring'),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(154,'eng-GB',67,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',67,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',67,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(772,'eng-GB',1,219,209,'Bård Farstad',0,0,0,0,'bård farstad','ezstring'),(773,'eng-GB',1,219,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(774,'eng-GB',1,219,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(775,'eng-GB',1,219,212,'I\'ve seen more speacial things.. dsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf\n\ndsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gfdsfgljk sdfg jsdklgj sdlfgj skldg sd gf',0,0,0,0,'','eztext'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',61,56,157,'Blog',0,0,0,0,'','ezinisetting'),(151,'eng-GB',62,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',67,56,157,'Blog',0,0,0,0,'','ezinisetting'),(151,'eng-GB',67,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',67,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"66\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069686266\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(125,'eng-GB',2,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',2,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(153,'eng-GB',62,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',62,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',62,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',62,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(600,'eng-GB',1,181,194,'',0,0,0,0,'','ezsubtreesubscription'),(591,'eng-GB',1,179,190,'',0,0,0,0,'','ezboolean'),(592,'eng-GB',1,179,194,'',0,0,0,0,'','ezsubtreesubscription'),(593,'eng-GB',1,180,188,'',0,0,0,0,'','ezstring'),(594,'eng-GB',1,180,189,'',0,0,0,0,'','eztext'),(595,'eng-GB',1,180,190,'',0,0,0,0,'','ezboolean'),(596,'eng-GB',1,180,194,'',0,0,0,0,'','ezsubtreesubscription'),(597,'eng-GB',1,181,188,'',0,0,0,0,'','ezstring'),(598,'eng-GB',1,181,189,'',0,0,0,0,'','eztext'),(599,'eng-GB',1,181,190,'',0,0,0,0,'','ezboolean'),(573,'eng-GB',1,175,188,'',0,0,0,0,'','ezstring'),(574,'eng-GB',1,175,189,'',0,0,0,0,'','eztext'),(575,'eng-GB',1,175,190,'',0,0,0,0,'','ezboolean'),(576,'eng-GB',1,175,194,'',0,0,0,0,'','ezsubtreesubscription'),(577,'eng-GB',1,176,188,'',0,0,0,0,'','ezstring'),(578,'eng-GB',1,176,189,'',0,0,0,0,'','eztext'),(579,'eng-GB',1,176,190,'',0,0,0,0,'','ezboolean'),(580,'eng-GB',1,176,194,'',0,0,0,0,'','ezsubtreesubscription'),(581,'eng-GB',1,177,188,'',0,0,0,0,'','ezstring'),(582,'eng-GB',1,177,189,'',0,0,0,0,'','eztext'),(583,'eng-GB',1,177,190,'',0,0,0,0,'','ezboolean'),(584,'eng-GB',1,177,194,'',0,0,0,0,'','ezsubtreesubscription'),(585,'eng-GB',1,178,188,'',0,0,0,0,'','ezstring'),(586,'eng-GB',1,178,189,'',0,0,0,0,'','eztext'),(587,'eng-GB',1,178,190,'',0,0,0,0,'','ezboolean'),(588,'eng-GB',1,178,194,'',0,0,0,0,'','ezsubtreesubscription'),(589,'eng-GB',1,179,188,'',0,0,0,0,'','ezstring'),(590,'eng-GB',1,179,189,'',0,0,0,0,'','eztext'),(561,'eng-GB',1,172,188,'',0,0,0,0,'','ezstring'),(562,'eng-GB',1,172,189,'',0,0,0,0,'','eztext'),(563,'eng-GB',1,172,190,'',0,0,0,0,'','ezboolean'),(564,'eng-GB',1,172,194,'',0,0,0,0,'','ezsubtreesubscription'),(565,'eng-GB',1,173,188,'',0,0,0,0,'','ezstring'),(566,'eng-GB',1,173,189,'',0,0,0,0,'','eztext'),(567,'eng-GB',1,173,190,'',0,0,0,0,'','ezboolean'),(568,'eng-GB',1,173,194,'',0,0,0,0,'','ezsubtreesubscription'),(569,'eng-GB',1,174,188,'',0,0,0,0,'','ezstring'),(570,'eng-GB',1,174,189,'',0,0,0,0,'','eztext'),(571,'eng-GB',1,174,190,'',0,0,0,0,'','ezboolean'),(572,'eng-GB',1,174,194,'',0,0,0,0,'','ezsubtreesubscription'),(560,'eng-GB',1,171,194,'',0,0,0,0,'','ezsubtreesubscription'),(559,'eng-GB',1,171,190,'',0,0,0,0,'','ezboolean'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(153,'eng-GB',67,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(150,'eng-GB',68,56,157,'Blog',0,0,0,0,'','ezinisetting'),(150,'eng-GB',63,56,157,'Blog test',0,0,0,0,'','ezinisetting'),(151,'eng-GB',63,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(437,'eng-GB',59,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',63,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"62\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',66,56,157,'Blog',0,0,0,0,'','ezinisetting'),(153,'eng-GB',63,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',63,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',63,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',63,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(824,'eng-GB',1,239,209,'Bård',0,0,0,0,'bård','ezstring'),(823,'eng-GB',1,238,213,'',0,0,0,0,'','ezboolean'),(822,'eng-GB',1,238,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n  <paragraph>dsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gfdsgf iosdufg iosdufgo idsfg sdgf sd gf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(821,'eng-GB',1,238,202,'No comments on this one',0,0,0,0,'no comments on this one','ezstring'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',66,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(482,'eng-GB',3,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',3,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(483,'eng-GB',3,149,12,'',0,0,0,0,'','ezuser'),(154,'eng-GB',59,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(151,'eng-GB',68,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(437,'eng-GB',61,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(674,'eng-GB',1,200,188,'test',0,0,0,0,'test','ezstring'),(641,'eng-GB',1,192,194,'',0,0,0,0,'','ezsubtreesubscription'),(642,'eng-GB',1,193,188,'',0,0,0,0,'','ezstring'),(643,'eng-GB',1,193,189,'',0,0,0,0,'','eztext'),(644,'eng-GB',1,193,190,'',0,0,0,0,'','ezboolean'),(645,'eng-GB',1,193,194,'',0,0,0,0,'','ezsubtreesubscription'),(646,'eng-GB',1,194,188,'',0,0,0,0,'','ezstring'),(647,'eng-GB',1,194,189,'',0,0,0,0,'','eztext'),(648,'eng-GB',1,194,190,'',0,0,0,0,'','ezboolean'),(649,'eng-GB',1,194,194,'',0,0,0,0,'','ezsubtreesubscription'),(154,'eng-GB',61,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(671,'eng-GB',59,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',68,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"67\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',68,56,160,'blog_red',0,0,0,0,'blog_red','ezpackage'),(154,'eng-GB',68,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',68,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(151,'eng-GB',61,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(153,'eng-GB',66,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',66,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',66,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(152,'eng-GB',66,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"65\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069678621\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(850,'eng-GB',1,247,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdgj sdlgjsdkfjlgh sdfg sdfg</line>\n    <line>sdfg</line>\n    <line>sdg</line>\n    <line>sdf</line>\n    <line>gds</line>\n  </paragraph>\n  <paragraph>\n    <line>sdfgsdfgsd</line>\n    <line>fg</line>\n    <line>sdfg</line>\n    <line>sdf</line>\n    <line>gsd</line>\n    <line>fg</line>\n    <line>sdg</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(851,'eng-GB',1,247,213,'',1,0,0,1,'','ezboolean'),(153,'eng-GB',65,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',65,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',65,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',65,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',59,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(1,'eng-GB',3,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',3,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(671,'eng-GB',68,56,196,'myblog.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(825,'eng-GB',1,239,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(802,'eng-GB',1,233,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(803,'eng-GB',1,233,211,'http://ez.no',0,0,0,0,'http://ez.no','ezstring'),(804,'eng-GB',1,233,212,'dfgl sdflg sdiofg usdoigfu osdigu iosdgf sdgfsd\nfg\nsdfg\nsdfg\nsdg',0,0,0,0,'','eztext'),(150,'eng-GB',65,56,157,'Blog test',0,0,0,0,'','ezinisetting'),(151,'eng-GB',65,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',62,56,157,'Blog',0,0,0,0,'','ezinisetting'),(150,'eng-GB',59,56,157,'Blog',0,0,0,0,'','ezinisetting'),(152,'eng-GB',65,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"64\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_test_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069677595\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(150,'eng-GB',60,56,157,'Blog',0,0,0,0,'','ezinisetting'),(151,'eng-GB',60,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(483,'eng-GB',4,149,12,'',0,0,0,0,'','ezuser'),(481,'eng-GB',4,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',4,149,9,'yu',0,0,0,0,'yu','ezstring'),(481,'eng-GB',1,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',1,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',1,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',4,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',4,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',4,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',4,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-4-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',5,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',5,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',5,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',5,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',5,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',5,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',5,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-5-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',6,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',6,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',6,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',6,149,197,'',0,0,0,0,'','ezstring'),(713,'eng-GB',6,149,198,'',0,0,0,0,'','ezstring'),(725,'eng-GB',6,149,199,'',0,0,0,0,'','eztext'),(737,'eng-GB',6,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"wenyue_yu.\"\n         suffix=\"\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-6-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-6-eng-GB/wenyue_yu.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"5\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',7,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',7,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',7,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',7,149,197,'Derector',0,0,0,0,'derector','ezstring'),(713,'eng-GB',7,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',7,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',7,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-7-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',8,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',8,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',8,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',8,149,197,'Director',0,0,0,0,'director','ezstring'),(713,'eng-GB',8,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',8,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',8,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"wenyue_yu_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"139\"\n         height=\"200\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(481,'eng-GB',9,149,8,'wenyue',0,0,0,0,'wenyue','ezstring'),(482,'eng-GB',9,149,9,'yu',0,0,0,0,'yu','ezstring'),(483,'eng-GB',9,149,12,'',0,0,0,0,'','ezuser'),(701,'eng-GB',9,149,197,'Director',0,0,0,0,'director','ezstring'),(713,'eng-GB',9,149,198,'norway',0,0,0,0,'norway','ezstring'),(725,'eng-GB',9,149,199,'kghjohtkæ',0,0,0,0,'','eztext'),(737,'eng-GB',9,149,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"wenyue_yu.jpg\"\n         suffix=\"jpg\"\n         basename=\"wenyue_yu\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu.jpg\"\n         original_filename=\"a7.jpg\"\n         mime_type=\"original\"\n         width=\"369\"\n         height=\"528\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"737\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"wenyue_yu_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"369\"\n         height=\"528\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"wenyue_yu_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"69\"\n         height=\"100\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"wenyue_yu_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/wenyue_yu/737-8-eng-GB/wenyue_yu_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"139\"\n         height=\"200\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',4,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',4,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',4,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',4,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-4-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(807,'eng-GB',1,234,206,'sina',3,0,0,0,'','ezurl'),(806,'eng-GB',1,234,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A famous chinese news site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(805,'eng-GB',1,234,204,'Sina ',0,0,0,0,'sina','ezstring'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(695,'eng-GB',5,14,197,'Uberguru',0,0,0,0,'uberguru','ezstring'),(707,'eng-GB',5,14,198,'Skien/Norway',0,0,0,0,'skien/norway','ezstring'),(719,'eng-GB',5,14,199,'developer... ;)',0,0,0,0,'','eztext'),(731,'eng-GB',5,14,200,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"administrator_user.jpg\"\n         suffix=\"jpg\"\n         basename=\"administrator_user\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user.jpg\"\n         original_filename=\"dscn9308.jpg\"\n         mime_type=\"original\"\n         width=\"1600\"\n         height=\"1200\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"731\"\n            attribute_version=\"4\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"administrator_user_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"administrator_user_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"administrator_user_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB\"\n         url=\"var/forum/storage/images/users/administrator_users/administrator_user/731-5-eng-GB/administrator_user_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(790,'eng-GB',1,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',1,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(792,'eng-GB',1,229,4,'Software',0,0,0,0,'software','ezstring'),(793,'eng-GB',1,229,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links about software..</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(794,'eng-GB',1,230,4,'Movie',0,0,0,0,'movie','ezstring'),(795,'eng-GB',1,230,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links to movie sites.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(796,'eng-GB',1,231,4,'News',0,0,0,0,'news','ezstring'),(797,'eng-GB',1,231,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Links for news site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(798,'eng-GB',1,232,204,'VG',0,0,0,0,'vg','ezstring'),(799,'eng-GB',1,232,205,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Know norwegian news from here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(800,'eng-GB',1,232,206,'VG',2,0,0,0,'','ezurl'),(801,'eng-GB',1,233,209,'bård',0,0,0,0,'bård','ezstring'),(788,'eng-GB',1,227,207,'Which color do you like?',0,0,0,0,'which color do you like?','ezstring'),(789,'eng-GB',1,227,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name>Colors</name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Blue</option>\n    <option id=\"1\"\n            additional_price=\"\">Yellow</option>\n    <option id=\"2\"\n            additional_price=\"\">Red</option>\n    <option id=\"3\"\n            additional_price=\"\">Orange</option>\n    <option id=\"4\"\n            additional_price=\"\">Green</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(838,'eng-GB',1,244,202,'A Pirate\'s Life',0,0,0,0,'a pirate\'s life','ezstring'),(839,'eng-GB',1,244,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Pirates of the Caribbean wasn&apos;t on my list of movies to see until I read what a few others were saying about it. Now I can&apos;t recommend it enough. This movie is an excellent 2.5 hours of pure escapism. Great sets, intriguing cinematography, fun characters, and a downright entertaining story. A good blend of action, humor, and a small dose of Disney-esque romance. As [Capt&apos;n!] Jack Sparrow, Johnny Depp gives us another brilliant performance, holding the audience with his charm and swagger throughout the film&apos;s entirety. He continues to impress me with his wide range of ability and the consistently strong characters of his recent career. And need I say what nice visuals Keira Knightley adds to this film?</paragraph>\n  <paragraph>Without giving anything away, my favorite parts of this movie were the over-obvious tie-ins with Disney&apos;s original ride. Anyone who remembers even a portion of the scenes which float by are bound to have a few chuckles over the way they&apos;re cleverly integrated into the movie. The pirates&apos; movements in and out of moonlight make for nicely added effects which I don&apos;t remember the ride revealing. (Note: The film is rated PG-13 for a reason: the pirates&apos; true identity and their violence may give small children nightmares, so think twice if ye have little ones.)</paragraph>\n  <paragraph>Aside from a few lengthy sword fights, I can&apos;t think of any reason not to say: you should run, not walk, to your nearest theater and see this movie as soon as possible. If you&apos;re not rambling out loud when you walk out of the theater, you&apos;ll at least be mumbling to yourself like a pirate, and hopefully you&apos;ll have had as good a time as I did.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(840,'eng-GB',1,244,213,'',1,0,0,1,'','ezboolean'),(834,'eng-GB',1,242,211,'fghvmbnmbvnm',0,0,0,0,'fghvmbnmbvnm','ezstring'),(835,'eng-GB',1,242,212,'fgn fdgh fdgh fdgh\nkløæ\nølæ\nløæ\nløæ\nløæ\nhjlh\nhj',0,0,0,0,'','eztext'),(836,'eng-GB',1,243,4,'Entertainment',0,0,0,0,'entertainment','ezstring'),(837,'eng-GB',1,243,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Music, books, film, television, shopping, travel, and many other fine escapes and vices. If it&apos;s downright fun, it probably belongs here.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(826,'eng-GB',1,239,211,'sdfgsd',0,0,0,0,'sdfgsd','ezstring'),(827,'eng-GB',1,239,212,'sdfgsdgsd\nsdg\nsdf',0,0,0,0,'','eztext'),(828,'eng-GB',1,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',1,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(788,'eng-GB',2,227,207,'Which color do you like?',0,0,0,0,'which color do you like?','ezstring'),(789,'eng-GB',2,227,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Blue</option>\n    <option id=\"1\"\n            additional_price=\"\">Yellow</option>\n    <option id=\"2\"\n            additional_price=\"\">Red</option>\n    <option id=\"3\"\n            additional_price=\"\">Orange</option>\n    <option id=\"4\"\n            additional_price=\"\">Green</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(828,'eng-GB',2,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',2,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(830,'eng-GB',1,241,207,'Which one is the best of matrix movies?',0,0,0,0,'which one is the best of matrix movies?','ezstring'),(831,'eng-GB',1,241,208,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezoption>\n  <name></name>\n  <options>\n    <option id=\"0\"\n            additional_price=\"\">Matrix</option>\n    <option id=\"1\"\n            additional_price=\"\">Matrix reloaded</option>\n    <option id=\"2\"\n            additional_price=\"\">Matrix revoluaton</option>\n  </options>\n</ezoption>',0,0,0,0,'','ezoption'),(832,'eng-GB',1,242,209,'ghghj',0,0,0,0,'ghghj','ezstring'),(833,'eng-GB',1,242,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(776,'eng-GB',1,220,209,'båbåb',0,0,0,0,'båbåb','ezstring'),(777,'eng-GB',1,220,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(778,'eng-GB',1,220,211,'http://piranha.no',0,0,0,0,'http://piranha.no','ezstring'),(779,'eng-GB',1,220,212,'sdfgsd fgsdgsd\ngf\nsdfg\nsdfgdsg\nsdgf\n',0,0,0,0,'','eztext'),(786,'eng-GB',1,226,202,'For Posterity\'s Sake',0,0,0,0,'for posterity\'s sake','ezstring'),(787,'eng-GB',1,226,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Seven years ago, yesterday, I packed up everything I owned, left many friends behind in San Diego, and moved to San Francisco, (where I knew absolutely no one) to start my job at HotWired on August 12, 1996. The Creative Director hired me as a junior designer, since I knew very little about design for the Web, despite the fact that I had more print design experience than almost every other designer there at the time.</paragraph>\n  <paragraph>Three months after starting the low-paying job, I was depressed, out of money, missed my friends in San Diego, and was convinced I had made the wrong move. I gave notice, told my landlord I was moving out, and arranged to go back to my old job at a design agency in San Diego. Two days before I was to leave HotWired, Jonathan Louie (Design Director at the time) convinced me otherwise, offering me a new position and my first promotion of many more to come. I stayed, and lost a girlfriend in San Diego as a result.</paragraph>\n  <paragraph>Fast-forward to the present?</paragraph>\n  <paragraph>In two days, it will have been 9 months since I left Wired to go out on my own. Friends who know the journey and my initial intimidation of taking the job at HotWired say to me, &quot;?and look where you are now.&quot;</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(761,'eng-GB',2,212,4,'Personal',0,0,0,0,'personal','ezstring'),(762,'eng-GB',2,212,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>A glimpse into the life of me.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(763,'eng-GB',2,213,4,'Computers',0,0,0,0,'computers','ezstring'),(764,'eng-GB',2,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Computers, handhelds, electronic gadgets, and the software which connects them all. I&apos;m an early adopter, which means I often end up paying the price, in more than one way.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(522,'eng-GB',2,161,140,'About me',0,0,0,0,'about me','ezstring'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_me.\"\n         suffix=\"\"\n         basename=\"about_me\"\n         dirpath=\"var/blog/storage/images/about_me/524-2-eng-GB\"\n         url=\"var/blog/storage/images/about_me/524-2-eng-GB/about_me.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429664\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429664\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         timestamp=\"1069429665\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(841,'eng-GB',1,245,209,'kjlh',0,0,0,0,'kjlh','ezstring'),(842,'eng-GB',1,245,210,'kjh',0,0,0,0,'kjh','ezstring'),(843,'eng-GB',1,245,211,'kjh',0,0,0,0,'kjh','ezstring'),(844,'eng-GB',1,245,212,'kjlhkhkjhklhj',0,0,0,0,'','eztext'),(845,'eng-GB',1,246,209,'kjhkjh',0,0,0,0,'kjhkjh','ezstring'),(846,'eng-GB',1,246,210,'bf@ez.no',0,0,0,0,'bf@ez.no','ezstring'),(847,'eng-GB',1,246,211,'sdfgsdfg',0,0,0,0,'sdfgsdfg','ezstring'),(848,'eng-GB',1,246,212,'sdfgsdfgsdfgds\nfgsd\nfg\nsdfg',0,0,0,0,'','eztext'),(849,'eng-GB',1,247,202,'I overslept today',0,0,0,0,'i overslept today','ezstring'),(152,'eng-GB',59,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"58\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069408652\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',59,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(852,'eng-GB',2,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(853,'eng-GB',3,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(854,'eng-GB',4,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(855,'eng-GB',5,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(856,'eng-GB',6,1,214,'Recent items',0,0,0,0,'recent items','ezstring'),(857,'eng-GB',1,41,214,'Recent items',0,0,0,0,'recent items','ezstring'),(858,'eng-GB',1,42,214,'Recent items',0,0,0,0,'recent items','ezstring'),(859,'eng-GB',1,44,214,'Recent items',0,0,0,0,'recent items','ezstring'),(860,'eng-GB',1,46,214,'Recent items',0,0,0,0,'recent items','ezstring'),(861,'eng-GB',2,46,214,'Recent items',0,0,0,0,'recent items','ezstring'),(862,'eng-GB',1,49,214,'Recent items',0,0,0,0,'recent items','ezstring'),(863,'eng-GB',2,49,214,'Recent items',0,0,0,0,'recent items','ezstring'),(864,'eng-GB',1,212,214,'Recent items',0,0,0,0,'recent items','ezstring'),(865,'eng-GB',2,212,214,'Recent items',0,0,0,0,'recent items','ezstring'),(866,'eng-GB',1,213,214,'Recent items',0,0,0,0,'recent items','ezstring'),(867,'eng-GB',2,213,214,'Recent items',0,0,0,0,'recent items','ezstring'),(868,'eng-GB',1,228,214,'Recent items',0,0,0,0,'recent items','ezstring'),(869,'eng-GB',1,229,214,'Recent items',0,0,0,0,'recent items','ezstring'),(870,'eng-GB',1,230,214,'Recent items',0,0,0,0,'recent items','ezstring'),(871,'eng-GB',1,231,214,'Recent items',0,0,0,0,'recent items','ezstring'),(872,'eng-GB',1,240,214,'Recent items',0,0,0,0,'recent items','ezstring'),(873,'eng-GB',2,240,214,'Recent items',0,0,0,0,'recent items','ezstring'),(874,'eng-GB',1,243,214,'Recent items',0,0,0,0,'recent items','ezstring'),(790,'eng-GB',2,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',2,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Worthwhile websites, weblogs, journals, news pubs, and the like</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(868,'eng-GB',2,228,214,'Recent Links',0,0,0,0,'recent links','ezstring'),(125,'eng-GB',3,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',3,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Parenthetical thoughts, concepts, and brainstorms</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(863,'eng-GB',3,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring'),(875,'eng-GB',2,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(876,'eng-GB',3,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(877,'eng-GB',4,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(878,'eng-GB',5,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(879,'eng-GB',6,1,215,'All Categories',0,0,0,0,'all categories','ezstring'),(880,'eng-GB',1,41,215,'All Categories',0,0,0,0,'all categories','ezstring'),(881,'eng-GB',1,42,215,'All Categories',0,0,0,0,'all categories','ezstring'),(882,'eng-GB',1,44,215,'All Categories',0,0,0,0,'all categories','ezstring'),(883,'eng-GB',1,46,215,'All Categories',0,0,0,0,'all categories','ezstring'),(884,'eng-GB',2,46,215,'All Categories',0,0,0,0,'all categories','ezstring'),(885,'eng-GB',1,49,215,'All Categories',0,0,0,0,'all categories','ezstring'),(886,'eng-GB',2,49,215,'All Categories',0,0,0,0,'all categories','ezstring'),(887,'eng-GB',3,49,215,'All Categories',0,0,0,0,'all categories','ezstring'),(888,'eng-GB',1,212,215,'All Categories',0,0,0,0,'all categories','ezstring'),(889,'eng-GB',2,212,215,'All Categories',0,0,0,0,'all categories','ezstring'),(890,'eng-GB',1,213,215,'All Categories',0,0,0,0,'all categories','ezstring'),(891,'eng-GB',2,213,215,'All Categories',0,0,0,0,'all categories','ezstring'),(892,'eng-GB',1,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(893,'eng-GB',2,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(894,'eng-GB',1,229,215,'All Categories',0,0,0,0,'all categories','ezstring'),(895,'eng-GB',1,230,215,'All Categories',0,0,0,0,'all categories','ezstring'),(896,'eng-GB',1,231,215,'All Categories',0,0,0,0,'all categories','ezstring'),(897,'eng-GB',1,240,215,'All Categories',0,0,0,0,'all categories','ezstring'),(898,'eng-GB',2,240,215,'All Categories',0,0,0,0,'all categories','ezstring'),(899,'eng-GB',1,243,215,'All Categories',0,0,0,0,'all categories','ezstring'),(790,'eng-GB',3,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',3,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Worthwhile websites, weblogs, journals, news pubs, and the like</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(868,'eng-GB',3,228,214,'Recent Links',0,0,0,0,'recent links','ezstring'),(893,'eng-GB',3,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(125,'eng-GB',4,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',4,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Parenthetical thoughts, concepts, and brainstorms</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(863,'eng-GB',4,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring'),(887,'eng-GB',4,49,215,'Entry Categories',0,0,0,0,'entry categories','ezstring'),(900,'eng-GB',2,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(901,'eng-GB',3,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(902,'eng-GB',4,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(903,'eng-GB',5,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(904,'eng-GB',6,1,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(905,'eng-GB',1,41,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(906,'eng-GB',1,42,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(907,'eng-GB',1,44,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(908,'eng-GB',1,46,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(909,'eng-GB',2,46,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(910,'eng-GB',1,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(911,'eng-GB',2,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(912,'eng-GB',3,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(913,'eng-GB',4,49,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(914,'eng-GB',1,212,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(915,'eng-GB',2,212,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(916,'eng-GB',1,213,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(917,'eng-GB',2,213,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(918,'eng-GB',1,228,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(919,'eng-GB',2,228,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(920,'eng-GB',3,228,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(921,'eng-GB',1,229,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(922,'eng-GB',1,230,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(923,'eng-GB',1,231,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(924,'eng-GB',1,240,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(925,'eng-GB',2,240,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(926,'eng-GB',1,243,216,'Item Archive',0,0,0,0,'item archive','ezstring'),(790,'eng-GB',4,228,4,'Links',0,0,0,0,'links','ezstring'),(791,'eng-GB',4,228,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Worthwhile websites, weblogs, journals, news pubs, and the like</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(920,'eng-GB',4,228,216,'Link Archive',0,0,0,0,'link archive','ezstring'),(868,'eng-GB',4,228,214,'Recent Links',0,0,0,0,'recent links','ezstring'),(893,'eng-GB',4,228,215,'All Categories',0,0,0,0,'all categories','ezstring'),(125,'eng-GB',5,49,4,'Blogs',0,0,0,0,'blogs','ezstring'),(126,'eng-GB',5,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Parenthetical thoughts, concepts, and brainstorms</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(913,'eng-GB',5,49,216,'Log Archive',0,0,0,0,'log archive','ezstring'),(863,'eng-GB',5,49,214,'Excerpts from Recent Entries',0,0,0,0,'excerpts from recent entries','ezstring'),(887,'eng-GB',5,49,215,'Entry Categories',0,0,0,0,'entry categories','ezstring'),(153,'eng-GB',61,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(152,'eng-GB',60,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"59\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069408729\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(828,'eng-GB',3,240,4,'Polls',0,0,0,0,'polls','ezstring'),(829,'eng-GB',3,240,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(925,'eng-GB',3,240,216,'Poll Archive',0,0,0,0,'poll archive','ezstring'),(873,'eng-GB',3,240,214,'Recent polls',0,0,0,0,'recent polls','ezstring'),(898,'eng-GB',3,240,215,'All Categories',0,0,0,0,'all categories','ezstring'),(152,'eng-GB',62,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog.gif\"\n         suffix=\"gif\"\n         basename=\"blog\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"61\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"blog_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069429748\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',60,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',60,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',60,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',60,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(956,'eng-GB',1,248,209,'ete',0,0,0,0,'ete','ezstring'),(957,'eng-GB',1,248,210,'ete',0,0,0,0,'ete','ezstring'),(958,'eng-GB',1,248,211,'ete',0,0,0,0,'ete','ezstring'),(959,'eng-GB',1,248,212,'te',0,0,0,0,'','eztext'),(960,'eng-GB',1,249,209,'',0,0,0,0,'','ezstring'),(961,'eng-GB',1,249,210,'',0,0,0,0,'','ezstring'),(962,'eng-GB',1,249,211,'',0,0,0,0,'','ezstring'),(963,'eng-GB',1,249,212,'',0,0,0,0,'','eztext'),(964,'eng-GB',1,250,209,'what is the problem?',0,0,0,0,'what is the problem?','ezstring'),(965,'eng-GB',1,250,210,'ade@yahoo.com',0,0,0,0,'ade@yahoo.com','ezstring'),(966,'eng-GB',1,250,211,'http://mysite.com',0,0,0,0,'http://mysite.com','ezstring'),(967,'eng-GB',1,250,212,'sdfdsseff\nsef',0,0,0,0,'','eztext'),(150,'eng-GB',64,56,157,'Blog test',0,0,0,0,'','ezinisetting'),(151,'eng-GB',64,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(152,'eng-GB',64,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"blog_test.gif\"\n         suffix=\"gif\"\n         basename=\"blog_test\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test.gif\"\n         original_filename=\"blog.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069334362\">\n  <original attribute_id=\"152\"\n            attribute_version=\"63\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"blog_test_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"blog_test_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB\"\n         url=\"var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069334363\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',64,56,160,'blog_blue',0,0,0,0,'blog_blue','ezpackage'),(154,'eng-GB',64,56,161,'blog_package',0,0,0,0,'blog_package','ezstring'),(437,'eng-GB',64,56,180,'bf@ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',64,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting'),(671,'eng-GB',61,56,196,'myblog.wy.dvh1.ez.no',0,0,0,0,'','ezinisetting');
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
INSERT INTO ezcontentobject_link VALUES (1,1,4,49),(4,1,5,49);
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(233,'bård',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(235,'kjh',1,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(165,'',1,'eng-GB','eng-GB'),(236,'Soft house',1,'eng-GB','eng-GB'),(237,'New big discovery today',1,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(214,'Today I got my new car!',1,'eng-GB','eng-GB'),(215,'Special things happened today',1,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(169,'test',1,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(213,'Computers',1,'eng-GB','eng-GB'),(168,'',1,'eng-GB','eng-GB'),(234,'Sina',1,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(212,'Personal',1,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(240,'Polls',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(107,'John Doe',1,'eng-GB','eng-GB'),(107,'John Doe',2,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(111,'vid la',1,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(240,'Polls',2,'eng-GB','eng-GB'),(227,'Which color do you like?',2,'eng-GB','eng-GB'),(221,'New Poll',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(239,'Bård',1,'eng-GB','eng-GB'),(149,'wenyue yu',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(216,'New Poll',1,'eng-GB','eng-GB'),(49,'Blogs',2,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(238,'No comments on this one',1,'eng-GB','eng-GB'),(171,'',1,'eng-GB','eng-GB'),(172,'',1,'eng-GB','eng-GB'),(173,'',1,'eng-GB','eng-GB'),(174,'',1,'eng-GB','eng-GB'),(175,'',1,'eng-GB','eng-GB'),(176,'',1,'eng-GB','eng-GB'),(177,'',1,'eng-GB','eng-GB'),(178,'',1,'eng-GB','eng-GB'),(179,'',1,'eng-GB','eng-GB'),(180,'',1,'eng-GB','eng-GB'),(181,'',1,'eng-GB','eng-GB'),(182,'',1,'eng-GB','eng-GB'),(183,'',1,'eng-GB','eng-GB'),(184,'',1,'eng-GB','eng-GB'),(185,'',1,'eng-GB','eng-GB'),(186,'New Forum topic',1,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(149,'wenyue yu',2,'eng-GB','eng-GB'),(191,'',1,'eng-GB','eng-GB'),(192,'',1,'eng-GB','eng-GB'),(193,'',1,'eng-GB','eng-GB'),(194,'New Forum topic',1,'eng-GB','eng-GB'),(228,'Links',1,'eng-GB','eng-GB'),(149,'wenyue yu',3,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(231,'News',1,'eng-GB','eng-GB'),(200,'test',1,'eng-GB','eng-GB'),(201,'Re:test',1,'eng-GB','eng-GB'),(232,'VG',1,'eng-GB','eng-GB'),(227,'Which color do you like?',1,'eng-GB','eng-GB'),(226,'For Posterity\'s Sake',1,'eng-GB','eng-GB'),(212,'Personal',2,'eng-GB','eng-GB'),(213,'Computers',2,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(206,'Bård Farstad',1,'eng-GB','eng-GB'),(230,'Movie',1,'eng-GB','eng-GB'),(229,'Software',1,'eng-GB','eng-GB'),(149,'wenyue yu',4,'eng-GB','eng-GB'),(149,'wenyue yu',5,'eng-GB','eng-GB'),(149,'wenyue yu',6,'eng-GB','eng-GB'),(149,'wenyue yu',7,'eng-GB','eng-GB'),(149,'wenyue yu',8,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(224,'New Poll',1,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(222,'New Poll',1,'eng-GB','eng-GB'),(225,'New Poll',1,'eng-GB','eng-GB'),(218,'New Poll',1,'eng-GB','eng-GB'),(219,'Bård Farstad',1,'eng-GB','eng-GB'),(220,'båbåb',1,'eng-GB','eng-GB'),(217,'New Poll',1,'eng-GB','eng-GB'),(1,'Blog',6,'eng-GB','eng-GB'),(161,'About me',2,'eng-GB','eng-GB'),(241,'Which one is the best of matrix movies?',1,'eng-GB','eng-GB'),(242,'ghghj',1,'eng-GB','eng-GB'),(243,'Entertainment',1,'eng-GB','eng-GB'),(244,'A Pirate\'s Life',1,'eng-GB','eng-GB'),(56,'Blog',43,'eng-GB','eng-GB'),(56,'Blog',47,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(245,'kjlh',1,'eng-GB','eng-GB'),(56,'Blog',48,'eng-GB','eng-GB'),(246,'kjhkjh',1,'eng-GB','eng-GB'),(56,'Blog',49,'eng-GB','eng-GB'),(247,'I overslept today',1,'eng-GB','eng-GB'),(228,'Links',2,'eng-GB','eng-GB'),(49,'Blogs',3,'eng-GB','eng-GB'),(228,'Links',3,'eng-GB','eng-GB'),(49,'Blogs',4,'eng-GB','eng-GB'),(228,'Links',4,'eng-GB','eng-GB'),(49,'Blogs',5,'eng-GB','eng-GB'),(56,'Blog',50,'eng-GB','eng-GB'),(56,'Blog',51,'eng-GB','eng-GB'),(240,'Polls',3,'eng-GB','eng-GB'),(56,'Blog',52,'eng-GB','eng-GB'),(56,'Blog',55,'eng-GB','eng-GB'),(56,'Blog',56,'eng-GB','eng-GB'),(56,'Blog',57,'eng-GB','eng-GB'),(56,'Blog',58,'eng-GB','eng-GB'),(56,'Blog',59,'eng-GB','eng-GB'),(56,'Blog',60,'eng-GB','eng-GB'),(248,'ete',1,'eng-GB','eng-GB'),(249,'',1,'eng-GB','eng-GB'),(250,'what is the problem?',1,'eng-GB','eng-GB'),(56,'Blog test',63,'eng-GB','eng-GB'),(161,'About me',3,'eng-GB','eng-GB'),(56,'Blog',61,'eng-GB','eng-GB'),(56,'Blog',62,'eng-GB','eng-GB'),(56,'Blog test',64,'eng-GB','eng-GB'),(56,'Blog test',65,'eng-GB','eng-GB'),(56,'Blog',66,'eng-GB','eng-GB'),(56,'Blog',67,'eng-GB','eng-GB'),(56,'Blog',68,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,6,1,1,'/1/2/',9,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,5,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,9,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,10,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,5,1,2,'/1/2/50/',9,1,0,'blogs',50),(54,48,56,68,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/blog',54),(153,50,212,2,1,3,'/1/2/50/153/',9,1,0,'blogs/personal',153),(127,2,161,2,1,2,'/1/2/127/',9,1,0,'about_me',127),(91,14,107,2,1,3,'/1/5/14/91/',9,1,0,'users/editors/john_doe',91),(92,14,111,1,1,3,'/1/5/14/92/',9,1,0,'users/editors/vid_la',92),(154,50,213,2,1,3,'/1/2/50/154/',9,1,0,'blogs/computers',154),(95,46,115,4,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,3,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(181,180,248,1,1,5,'/1/2/50/154/180/181/',1,1,0,'blogs/computers/i_overslept_today/ete',181),(180,154,247,1,1,4,'/1/2/50/154/180/',9,1,0,'blogs/computers/i_overslept_today',180),(117,13,149,8,1,3,'/1/5/13/117/',9,1,0,'users/administrator_users/wenyue_yu',117),(179,177,246,1,1,5,'/1/2/50/176/177/179/',1,1,0,'blogs/entertainment/a_pirates_life/kjhkjh',179),(178,177,245,1,1,5,'/1/2/50/176/177/178/',1,1,0,'blogs/entertainment/a_pirates_life/kjlh',178),(176,50,243,1,1,3,'/1/2/50/176/',9,1,0,'blogs/entertainment',176),(177,176,244,1,1,4,'/1/2/50/176/177/',9,1,0,'blogs/entertainment/a_pirates_life',177),(175,169,242,1,1,5,'/1/2/50/154/169/175/',1,1,0,'blogs/computers/new_big_discovery_today/ghghj',175),(174,173,241,1,1,3,'/1/2/173/174/',9,1,1,'polls/which_one_is_the_best_of_matrix_movies',174),(173,2,240,3,1,2,'/1/2/173/',8,1,0,'polls',173),(172,169,239,1,1,5,'/1/2/50/154/169/172/',1,1,0,'blogs/computers/new_big_discovery_today/brd',172),(171,154,238,1,1,4,'/1/2/50/154/171/',9,1,0,'blogs/computers/no_comments_on_this_one',171),(170,162,236,1,1,4,'/1/2/161/162/170/',9,1,0,'links/software/soft_house',170),(169,154,237,1,1,4,'/1/2/50/154/169/',9,1,0,'blogs/computers/new_big_discovery_today',169),(168,164,234,1,1,4,'/1/2/161/164/168/',9,1,0,'links/news/sina_',168),(166,164,232,1,1,4,'/1/2/161/164/166/',9,1,0,'links/news/vg',166),(167,156,235,1,1,5,'/1/2/50/154/156/167/',9,1,0,'blogs/computers/special_things_happened_today/kjh',167),(165,156,233,1,1,5,'/1/2/50/154/156/165/',9,1,0,'blogs/computers/special_things_happened_today/brd',165),(164,161,231,1,1,3,'/1/2/161/164/',9,1,0,'links/news',164),(162,161,229,1,1,3,'/1/2/161/162/',9,1,0,'links/software',162),(163,161,230,1,1,3,'/1/2/161/163/',9,1,0,'links/movie',163),(145,13,206,1,1,3,'/1/5/13/145/',9,1,0,'users/administrator_users/brd_farstad',145),(161,2,228,4,1,2,'/1/2/161/',9,1,0,'links',161),(160,173,227,2,1,3,'/1/2/173/160/',9,1,0,'polls/which_color_do_you_like',160),(158,156,220,1,1,5,'/1/2/50/154/156/158/',9,1,0,'blogs/computers/special_things_happened_today/bbb',158),(159,153,226,1,1,4,'/1/2/50/153/159/',9,1,0,'blogs/personal/for_posteritys_sake',159),(155,153,214,1,1,4,'/1/2/50/153/155/',9,1,0,'blogs/personal/today_i_got_my_new_car',155),(156,154,215,1,1,4,'/1/2/50/154/156/',9,1,0,'blogs/computers/special_things_happened_today',156),(157,156,219,1,1,5,'/1/2/50/154/156/157/',9,1,0,'blogs/computers/special_things_happened_today/brd_farstad',157);
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
INSERT INTO ezcontentobject_version VALUES (804,1,14,6,1068710443,1068710484,1,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,3,0,0),(831,235,14,1,1068718746,1068718760,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(474,43,14,1,1066384288,1066384365,3,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(480,45,14,1,1066388718,1066388815,3,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(884,56,14,68,1069686815,1069686910,1,0,0),(490,49,14,1,1066398007,1066398020,3,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(741,175,149,1,1068108534,1068108624,0,0,0),(882,56,14,66,1069677684,1069677719,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(734,168,149,1,1068048359,1068048594,0,0,0),(832,236,14,1,1068718999,1068719250,1,0,0),(833,237,14,1,1068718996,1068719051,1,0,0),(731,165,149,1,1068048190,1068048359,0,0,0),(806,212,14,1,1068711059,1068711069,3,0,0),(836,240,14,1,1068719631,1068719643,3,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(881,56,14,65,1069677489,1069677521,3,0,0),(870,56,14,59,1069408374,1069408627,3,0,0),(740,174,149,1,1068050123,1068108534,0,0,0),(880,56,14,64,1069676476,1069676517,3,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(877,56,14,61,1069414825,1069414843,3,0,0),(842,244,14,1,1068727900,1068727917,1,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(845,43,14,9,1068729346,1068729356,1,0,0),(739,173,149,1,1068050088,1068050123,0,0,0),(809,215,14,1,1068713628,1068713677,1,0,0),(838,240,14,2,1068720650,1068720665,3,0,0),(738,172,149,1,1068049706,1068050088,0,0,0),(735,169,149,1,1068048594,1068048622,0,0,0),(871,56,14,60,1069408694,1069408711,3,0,0),(808,214,14,1,1068711110,1068711140,1,0,0),(737,171,149,1,1068049618,1068049706,0,0,0),(878,56,14,62,1069429714,1069429727,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(835,239,14,1,1068719292,1068719374,1,0,0),(834,238,14,1,1068719106,1068719128,1,0,0),(598,107,14,1,1066916843,1066916865,3,0,0),(599,107,14,2,1066916931,1066916941,1,0,0),(672,1,14,2,1067871685,1067871717,3,1,0),(604,111,14,1,1066917488,1066917523,1,0,0),(883,56,14,67,1069685397,1069685414,3,0,0),(668,49,14,2,1067357193,1068711012,3,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(695,1,14,3,1068035768,1068035779,3,1,0),(848,245,14,1,1068730464,1068730475,1,0,0),(844,115,14,4,1068729296,1068729308,1,0,0),(813,219,14,1,1068716892,1068716920,1,0,0),(840,242,14,1,1068720892,1068720915,1,0,0),(709,149,14,1,1068040987,1068041016,3,0,0),(841,243,14,1,1068727844,1068727871,1,0,0),(807,213,14,1,1068711079,1068711091,3,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(839,241,14,1,1068720708,1068720802,1,0,0),(837,227,14,2,1068719654,1068719695,1,0,0),(742,176,149,1,1068108624,1068108805,0,0,0),(743,177,149,1,1068108805,1068108834,0,0,0),(744,178,149,1,1068108834,1068108898,0,0,0),(745,179,149,1,1068108898,1068109016,0,0,0),(746,180,149,1,1068109016,1068109220,0,0,0),(747,181,149,1,1068109220,1068109255,0,0,0),(748,182,149,1,1068109255,1068109498,0,0,0),(749,183,149,1,1068109498,1068109663,0,0,0),(750,184,149,1,1068109663,1068109781,0,0,0),(751,185,149,1,1068109781,1068109829,0,0,0),(752,186,149,1,1068109829,1068109829,0,0,0),(757,149,14,2,1068111093,1068111116,3,0,0),(758,191,149,1,1068111317,1068111376,0,0,0),(759,192,149,1,1068111376,1068111870,0,0,0),(760,193,149,1,1068111870,1068111917,0,0,0),(761,194,149,1,1068111917,1068111917,0,0,0),(825,229,14,1,1068718643,1068718672,1,0,0),(830,234,14,1,1068718886,1068718957,1,0,0),(766,149,14,3,1068112999,1068113012,3,0,0),(829,233,14,1,1068718686,1068718705,1,0,0),(769,200,149,1,1068120480,1068120496,0,0,0),(770,201,149,1,1068120737,1068120756,0,0,0),(824,228,14,1,1068718602,1068718628,3,0,0),(819,213,14,2,1068717682,1068717696,1,0,0),(818,212,14,2,1068717602,1068717667,1,0,0),(828,232,14,1,1068718770,1068718860,1,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(879,56,14,63,1069676374,1069676432,3,0,0),(823,227,14,1,1068717981,1068718128,3,0,0),(822,226,14,1,1068717922,1068717934,1,0,0),(782,206,14,1,1068123519,1068123599,1,0,0),(827,231,14,1,1068718724,1068718745,1,0,0),(826,230,14,1,1068718683,1068718712,1,0,0),(785,149,149,4,1068129024,1068129067,3,0,0),(786,149,149,5,1068129453,1068129479,3,0,0),(787,149,149,6,1068129554,1068129569,3,0,0),(789,149,149,7,1068130370,1068130443,3,0,0),(790,149,149,8,1068130529,1068130543,1,0,0),(791,149,149,9,1068132647,1068132647,0,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(874,250,10,1,1069409353,1069411652,0,0,0),(796,14,14,5,1068468183,1068468218,1,0,0),(814,220,14,1,1068716938,1068716967,1,0,0),(847,116,14,3,1068729385,1068729395,1,0,0),(873,249,10,1,1069409163,1069409163,0,0,0),(852,247,14,1,1068796274,1068796296,1,0,0),(850,246,14,1,1068737185,1068737197,1,0,0),(846,45,14,10,1068729368,1068729376,1,0,0),(872,248,10,1,1069408745,1069408767,1,0,0),(805,161,14,2,1068710499,1068710511,1,0,0),(853,228,14,2,1068803436,1068803511,3,0,0),(854,49,14,3,1068803526,1068803558,3,0,0),(855,228,14,3,1068803993,1068804002,3,0,0),(856,49,14,4,1068804018,1068804028,3,0,0),(857,228,14,4,1068804664,1068804675,1,0,0),(858,49,14,5,1068804682,1068804689,1,0,0),(861,240,14,3,1069172829,1069172878,1,0,0);
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
INSERT INTO ezimagefile VALUES (43,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_medium.gif'),(42,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_reference.gif'),(41,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog.gif'),(5,152,'var/blog/storage/images/setup/look_and_feel/blog/152-52-eng-GB/blog_logo.gif'),(6,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog.gif'),(7,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_reference.gif'),(8,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_medium.gif'),(9,152,'var/blog/storage/images/setup/look_and_feel/blog/152-55-eng-GB/blog_logo.gif'),(10,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog.gif'),(11,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_reference.gif'),(12,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_medium.gif'),(13,152,'var/blog/storage/images/setup/look_and_feel/blog/152-56-eng-GB/blog_logo.gif'),(14,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog.gif'),(15,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_reference.gif'),(16,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_medium.gif'),(17,152,'var/blog/storage/images/setup/look_and_feel/blog/152-57-eng-GB/blog_logo.gif'),(18,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog.gif'),(19,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_reference.gif'),(20,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_medium.gif'),(21,152,'var/blog/storage/images/setup/look_and_feel/blog/152-58-eng-GB/blog_logo.gif'),(22,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog.gif'),(23,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_reference.gif'),(24,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_medium.gif'),(25,152,'var/blog/storage/images/setup/look_and_feel/blog/152-59-eng-GB/blog_logo.gif'),(26,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog.gif'),(27,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_reference.gif'),(28,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_medium.gif'),(29,152,'var/blog/storage/images/setup/look_and_feel/blog/152-60-eng-GB/blog_logo.gif'),(30,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog.gif'),(31,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_reference.gif'),(32,152,'var/blog/storage/images/setup/look_and_feel/blog/152-61-eng-GB/blog_medium.gif'),(33,103,'var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png'),(34,103,'var/blog/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png'),(35,109,'var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png'),(36,109,'var/blog/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png'),(37,324,'var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png'),(38,324,'var/blog/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png'),(39,328,'var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png'),(40,328,'var/blog/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png'),(44,152,'var/blog/storage/images/setup/look_and_feel/blog/152-62-eng-GB/blog_logo.gif'),(45,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test.gif'),(46,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_reference.gif'),(47,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-63-eng-GB/blog_test_medium.gif'),(48,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test.gif'),(49,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_reference.gif'),(50,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-64-eng-GB/blog_test_medium.gif'),(51,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test.gif'),(52,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_reference.gif'),(53,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_medium.gif'),(54,152,'var/blog/storage/images/setup/look_and_feel/blog_test/152-65-eng-GB/blog_test_logo.gif'),(55,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog.gif'),(56,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_reference.gif'),(57,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_medium.gif'),(58,152,'var/blog/storage/images/setup/look_and_feel/blog/152-66-eng-GB/blog_logo.gif'),(59,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog.gif'),(60,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_reference.gif'),(61,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_medium.gif'),(62,152,'var/blog/storage/images/setup/look_and_feel/blog/152-67-eng-GB/blog_logo.gif'),(63,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog.gif'),(64,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_reference.gif'),(65,152,'var/blog/storage/images/setup/look_and_feel/blog/152-68-eng-GB/blog_medium.gif');
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
INSERT INTO ezinfocollection VALUES (1,137,1068027503,'c6194244e6057c2ed46e92ac8c59be21',1068027503),(2,137,1068028058,'c6194244e6057c2ed46e92ac8c59be21',1068028058),(3,227,1068718291,'c6194244e6057c2ed46e92ac8c59be21',1068718291),(4,227,1068718359,'c6194244e6057c2ed46e92ac8c59be21',1068718359),(5,227,1068721732,'c6194244e6057c2ed46e92ac8c59be21',1068721732),(6,227,1068723204,'c6194244e6057c2ed46e92ac8c59be21',1068723204),(7,227,1068723216,'c6194244e6057c2ed46e92ac8c59be21',1068723216),(8,227,1068723236,'c6194244e6057c2ed46e92ac8c59be21',1068723236),(9,227,1068723826,'c6194244e6057c2ed46e92ac8c59be21',1068723826),(10,227,1068723856,'c6194244e6057c2ed46e92ac8c59be21',1068723856),(11,227,1068724005,'c6194244e6057c2ed46e92ac8c59be21',1068724005),(12,227,1068724227,'c6194244e6057c2ed46e92ac8c59be21',1068724227),(13,227,1068726335,'c6194244e6057c2ed46e92ac8c59be21',1068726335),(14,227,1068726772,'c6194244e6057c2ed46e92ac8c59be21',1068726772),(15,227,1068727910,'c6194244e6057c2ed46e92ac8c59be21',1068727910),(16,227,1068729189,'9d6d05ca28ed8f65e38e0e7f01741744',1068729189),(17,227,1068729968,'cf64399b65e473dd59293d990f30bfbf',1068729968),(18,227,1068731428,'c6194244e6057c2ed46e92ac8c59be21',1068731428),(19,227,1068731436,'c6194244e6057c2ed46e92ac8c59be21',1068731436),(20,227,1068731442,'c6194244e6057c2ed46e92ac8c59be21',1068731442),(21,227,1068732540,'c6194244e6057c2ed46e92ac8c59be21',1068732540),(22,227,1068736388,'c6194244e6057c2ed46e92ac8c59be21',1068736388),(23,227,1068736850,'c6194244e6057c2ed46e92ac8c59be21',1068736850),(24,227,1068737071,'c6194244e6057c2ed46e92ac8c59be21',1068737071),(25,227,1068796372,'c6194244e6057c2ed46e92ac8c59be21',1068796372),(26,227,1068823513,'246f16e4f01d95e37296d2c33eff57d7',1068823513),(27,227,1068823514,'246f16e4f01d95e37296d2c33eff57d7',1068823514),(28,241,1069173187,'246f16e4f01d95e37296d2c33eff57d7',1069173187),(29,227,1069173197,'246f16e4f01d95e37296d2c33eff57d7',1069173197),(30,227,1069344809,'246f16e4f01d95e37296d2c33eff57d7',1069344809);
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
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'bf@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137),(7,3,'',0,0,208,789,227),(8,4,'',2,0,208,789,227),(9,5,'',2,0,208,789,227),(10,6,'',3,0,208,789,227),(11,7,'',4,0,208,789,227),(12,8,'',1,0,208,789,227),(13,9,'',1,0,208,789,227),(14,10,'',1,0,208,789,227),(15,11,'',3,0,208,789,227),(16,12,'',3,0,208,789,227),(17,13,'',3,0,208,789,227),(18,14,'',0,0,208,789,227),(19,15,'',1,0,208,789,227),(20,16,'',2,0,208,789,227),(21,17,'',2,0,208,789,227),(22,18,'',0,0,208,789,227),(23,19,'',0,0,208,789,227),(24,20,'',0,0,208,789,227),(25,21,'',0,0,208,789,227),(26,22,'',0,0,208,789,227),(27,23,'',1,0,208,789,227),(28,24,'',1,0,208,789,227),(29,25,'',2,0,208,789,227),(30,26,'',0,0,208,789,227),(31,27,'',0,0,208,789,227),(32,28,'',1,0,208,831,241),(33,29,'',3,0,208,789,227),(34,30,'',4,0,208,789,227);
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
INSERT INTO eznode_assignment VALUES (510,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(522,227,1,2,9,1,1,0,0),(524,229,1,161,9,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(540,242,1,169,1,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(184,43,1,44,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(191,45,1,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(584,56,68,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(582,56,66,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(445,175,1,2,1,1,0,0,0),(438,168,1,2,1,1,0,0,0),(581,56,65,48,9,1,1,0,0),(541,243,1,50,9,1,1,0,0),(435,165,1,115,1,1,0,0,0),(542,244,1,176,9,1,1,0,0),(512,212,1,50,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(513,213,1,50,9,1,1,0,0),(570,56,59,48,9,1,1,0,0),(444,174,1,2,1,1,0,0,0),(580,56,64,48,9,1,1,0,0),(321,45,6,46,9,1,1,0,0),(577,56,61,48,9,1,1,0,0),(554,49,3,2,9,1,1,0,0),(387,116,2,46,9,1,1,0,0),(556,49,4,2,9,1,1,0,0),(443,173,1,2,1,1,0,0,0),(439,169,1,2,1,1,1,0,0),(544,115,4,46,9,1,1,0,0),(442,172,1,2,1,1,0,0,0),(515,215,1,154,9,1,1,0,0),(571,56,60,48,9,1,1,0,0),(545,43,9,46,9,1,1,0,0),(441,171,1,115,1,1,0,0,0),(335,45,8,46,9,1,1,0,0),(578,56,62,48,9,1,1,0,0),(546,45,10,46,9,1,1,0,0),(548,245,1,177,1,1,1,0,0),(300,107,1,14,9,1,1,0,0),(301,107,2,14,9,1,1,0,0),(375,1,2,1,9,1,1,0,0),(306,111,1,14,9,1,1,0,0),(583,56,67,48,9,1,1,0,0),(371,49,2,2,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(398,1,3,1,9,1,1,0,0),(561,240,3,2,8,1,1,0,0),(519,212,2,50,9,1,1,0,0),(555,228,3,2,9,1,1,0,0),(579,56,63,48,9,1,1,0,0),(574,250,1,2,1,1,1,0,0),(552,247,1,154,9,1,1,0,0),(413,149,1,13,9,1,1,0,0),(553,228,2,2,9,1,1,0,0),(516,219,1,156,9,1,1,0,0),(514,214,1,153,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(550,246,1,177,1,1,1,0,0),(547,116,3,46,9,1,1,0,0),(446,176,1,2,1,1,0,0,0),(447,177,1,2,1,1,0,0,0),(448,178,1,2,1,1,0,0,0),(449,179,1,2,1,1,0,0,0),(450,180,1,2,1,1,0,0,0),(451,181,1,2,1,1,0,0,0),(452,182,1,2,1,1,0,0,0),(453,183,1,2,1,1,0,0,0),(454,184,1,2,1,1,0,0,0),(455,185,1,2,1,1,0,0,0),(456,186,1,2,1,1,1,0,0),(573,249,1,180,1,1,1,0,0),(461,149,2,13,9,1,1,0,0),(572,248,1,180,1,1,1,0,0),(462,191,1,115,1,1,0,0,0),(463,192,1,2,1,1,0,0,0),(464,193,1,2,1,1,0,0,0),(465,194,1,2,1,1,1,0,0),(533,238,1,154,9,1,1,0,0),(539,241,1,173,9,1,1,0,0),(470,149,3,13,9,1,1,0,0),(537,227,2,173,9,1,1,2,0),(473,200,1,114,1,1,1,0,0),(474,201,1,135,1,1,1,0,0),(532,237,1,154,9,1,1,0,0),(529,234,1,164,9,1,1,0,0),(528,233,1,156,9,1,1,0,0),(527,232,1,164,9,1,1,0,0),(538,240,2,2,8,1,1,0,0),(526,231,1,161,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(525,230,1,161,9,1,1,0,0),(531,236,1,162,9,1,1,0,0),(530,235,1,156,9,1,1,0,0),(486,206,1,13,9,1,1,0,0),(535,240,1,2,9,1,1,0,0),(534,239,1,169,1,1,1,0,0),(489,149,4,13,9,1,1,0,0),(490,149,5,13,9,1,1,0,0),(491,149,6,13,9,1,1,0,0),(493,149,7,13,9,1,1,0,0),(494,149,8,13,9,1,1,0,0),(495,149,9,13,9,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(523,228,1,2,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(520,213,2,50,9,1,1,0,0),(558,49,5,2,9,1,1,0,0),(521,226,1,153,9,1,1,0,0),(557,228,4,2,9,1,1,0,0),(517,220,1,156,9,1,1,0,0),(511,161,2,2,9,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (227,0,'ezpublish',142,3,0,0,'','','',''),(226,0,'ezpublish',141,3,0,0,'','','',''),(225,0,'ezpublish',211,2,0,0,'','','',''),(224,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',142,2,0,0,'','','',''),(222,0,'ezpublish',141,2,0,0,'','','',''),(221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','',''),(228,0,'ezpublish',1,6,0,0,'','','',''),(229,0,'ezpublish',161,2,0,0,'','','',''),(230,0,'ezpublish',49,2,0,0,'','','',''),(231,0,'ezpublish',212,1,0,0,'','','',''),(232,0,'ezpublish',213,1,0,0,'','','',''),(233,0,'ezpublish',214,1,0,0,'','','',''),(234,0,'ezpublish',215,1,0,0,'','','',''),(235,0,'ezpublish',219,1,0,0,'','','',''),(236,0,'ezpublish',220,1,0,0,'','','',''),(237,0,'ezpublish',212,2,0,0,'','','',''),(238,0,'ezpublish',213,2,0,0,'','','',''),(239,0,'ezpublish',226,1,0,0,'','','',''),(240,0,'ezpublish',227,1,0,0,'','','',''),(241,0,'ezpublish',228,1,0,0,'','','',''),(242,0,'ezpublish',229,1,0,0,'','','',''),(243,0,'ezpublish',230,1,0,0,'','','',''),(244,0,'ezpublish',231,1,0,0,'','','',''),(245,0,'ezpublish',233,1,0,0,'','','',''),(246,0,'ezpublish',232,1,0,0,'','','',''),(247,0,'ezpublish',235,1,0,0,'','','',''),(248,0,'ezpublish',234,1,0,0,'','','',''),(249,0,'ezpublish',237,1,0,0,'','','',''),(250,0,'ezpublish',236,1,0,0,'','','',''),(251,0,'ezpublish',238,1,0,0,'','','',''),(252,0,'ezpublish',239,1,0,0,'','','',''),(253,0,'ezpublish',240,1,0,0,'','','',''),(254,0,'ezpublish',227,2,0,0,'','','',''),(255,0,'ezpublish',240,2,0,0,'','','',''),(256,0,'ezpublish',241,1,0,0,'','','',''),(257,0,'ezpublish',242,1,0,0,'','','',''),(258,0,'ezpublish',243,1,0,0,'','','',''),(259,0,'ezpublish',244,1,0,0,'','','',''),(260,0,'ezpublish',56,47,0,0,'','','',''),(261,0,'ezpublish',115,4,0,0,'','','',''),(262,0,'ezpublish',43,9,0,0,'','','',''),(263,0,'ezpublish',45,10,0,0,'','','',''),(264,0,'ezpublish',116,3,0,0,'','','',''),(265,0,'ezpublish',245,1,0,0,'','','',''),(266,0,'ezpublish',56,48,0,0,'','','',''),(267,0,'ezpublish',246,1,0,0,'','','',''),(268,0,'ezpublish',56,49,0,0,'','','',''),(269,0,'ezpublish',247,1,0,0,'','','',''),(270,0,'ezpublish',228,2,0,0,'','','',''),(271,0,'ezpublish',49,3,0,0,'','','',''),(272,0,'ezpublish',228,3,0,0,'','','',''),(273,0,'ezpublish',49,4,0,0,'','','',''),(274,0,'ezpublish',228,4,0,0,'','','',''),(275,0,'ezpublish',49,5,0,0,'','','',''),(276,0,'ezpublish',56,50,0,0,'','','',''),(277,0,'ezpublish',56,51,0,0,'','','',''),(278,0,'ezpublish',240,3,0,0,'','','',''),(279,0,'ezpublish',56,52,0,0,'','','',''),(280,0,'ezpublish',56,55,0,0,'','','',''),(281,0,'ezpublish',56,56,0,0,'','','',''),(282,0,'ezpublish',56,57,0,0,'','','',''),(283,0,'ezpublish',56,58,0,0,'','','',''),(284,0,'ezpublish',56,59,0,0,'','','',''),(285,0,'ezpublish',56,60,0,0,'','','',''),(286,0,'ezpublish',248,1,0,0,'','','',''),(287,0,'ezpublish',251,1,0,0,'','','',''),(288,0,'ezpublish',56,61,0,0,'','','',''),(289,0,'ezpublish',56,62,0,0,'','','',''),(290,0,'ezpublish',56,63,0,0,'','','',''),(291,0,'ezpublish',56,64,0,0,'','','',''),(292,0,'ezpublish',56,65,0,0,'','','',''),(293,0,'ezpublish',56,66,0,0,'','','',''),(294,0,'ezpublish',56,67,0,0,'','','',''),(295,0,'ezpublish',56,68,0,0,'','','','');
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(341,8,'read','content','*'),(388,1,'read','content',''),(387,1,'login','user','*'),(389,1,'create','content',''),(390,1,'edit','content',''),(391,1,'versionread','content','');
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
INSERT INTO ezpolicy_limitation VALUES (306,388,'Class',0,'read','content'),(307,389,'Class',0,'create','content'),(308,390,'Class',0,'edit','content'),(309,391,'Class',0,'versionread','content'),(310,391,'Owner',0,'versionread','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (633,306,'5'),(632,306,'26'),(631,306,'25'),(630,306,'24'),(629,306,'23'),(628,306,'2'),(627,306,'12'),(626,306,'10'),(625,306,'1'),(634,307,'26'),(635,308,'26'),(636,309,'26'),(637,310,'1');
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
INSERT INTO ezsearch_object_word_link VALUES (3825,226,1567,0,7,1566,1490,23,1068717935,13,203,'',0),(3025,149,1340,0,4,1316,0,4,1068041016,2,199,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(4796,43,1906,0,2,1905,0,14,1066384365,11,155,'',0),(4795,43,1905,0,1,1904,1906,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(4794,43,1904,0,0,0,1905,14,1066384365,11,152,'',0),(4802,45,1908,0,5,1907,0,14,1066388816,11,155,'',0),(4801,45,1907,0,4,25,1908,14,1066388816,11,155,'',0),(4800,45,25,0,3,34,1907,14,1066388816,11,155,'',0),(4799,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(4932,248,1994,0,1,1994,1994,26,1069409151,13,210,'',0),(4886,49,1954,0,5,33,0,1,1066398020,13,119,'',0),(3754,219,1524,0,70,1523,1503,26,1068716920,13,212,'',0),(3753,219,1523,0,69,1522,1524,26,1068716920,13,212,'',0),(3752,219,1522,0,68,1509,1523,26,1068716920,13,212,'',0),(3495,161,1399,0,383,1438,0,10,1068047603,1,141,'',0),(3494,161,1438,0,382,1466,1399,10,1068047603,1,141,'',0),(3493,161,1466,0,381,1455,1438,10,1068047603,1,141,'',0),(3492,161,1455,0,380,1485,1466,10,1068047603,1,141,'',0),(3491,161,1485,0,379,1484,1455,10,1068047603,1,141,'',0),(3490,161,1484,0,378,1483,1485,10,1068047603,1,141,'',0),(3489,161,1483,0,377,1387,1484,10,1068047603,1,141,'',0),(3488,161,1387,0,376,1406,1483,10,1068047603,1,141,'',0),(3487,161,1406,0,375,1457,1387,10,1068047603,1,141,'',0),(3486,161,1457,0,374,1452,1406,10,1068047603,1,141,'',0),(3485,161,1452,0,373,1482,1457,10,1068047603,1,141,'',0),(3484,161,1482,0,372,1407,1452,10,1068047603,1,141,'',0),(3483,161,1407,0,371,1403,1482,10,1068047603,1,141,'',0),(3482,161,1403,0,370,1448,1407,10,1068047603,1,141,'',0),(3481,161,1448,0,369,1481,1403,10,1068047603,1,141,'',0),(3480,161,1481,0,368,1480,1448,10,1068047603,1,141,'',0),(3479,161,1480,0,367,1479,1481,10,1068047603,1,141,'',0),(3478,161,1479,0,366,1478,1480,10,1068047603,1,141,'',0),(3477,161,1478,0,365,1399,1479,10,1068047603,1,141,'',0),(3476,161,1399,0,364,1440,1478,10,1068047603,1,141,'',0),(3475,161,1440,0,363,1386,1399,10,1068047603,1,141,'',0),(3474,161,1386,0,362,1477,1440,10,1068047603,1,141,'',0),(3473,161,1477,0,361,1412,1386,10,1068047603,1,141,'',0),(3472,161,1412,0,360,1401,1477,10,1068047603,1,141,'',0),(3471,161,1401,0,359,1457,1412,10,1068047603,1,141,'',0),(3470,161,1457,0,358,1399,1401,10,1068047603,1,141,'',0),(3469,161,1399,0,357,1476,1457,10,1068047603,1,141,'',0),(3468,161,1476,0,356,1382,1399,10,1068047603,1,141,'',0),(3467,161,1382,0,355,1448,1476,10,1068047603,1,141,'',0),(3466,161,1448,0,354,1427,1382,10,1068047603,1,141,'',0),(3465,161,1427,0,353,1438,1448,10,1068047603,1,141,'',0),(3464,161,1438,0,352,1396,1427,10,1068047603,1,141,'',0),(3463,161,1396,0,351,1475,1438,10,1068047603,1,141,'',0),(3462,161,1475,0,350,1474,1396,10,1068047603,1,141,'',0),(3461,161,1474,0,349,1448,1475,10,1068047603,1,141,'',0),(3460,161,1448,0,348,1419,1474,10,1068047603,1,141,'',0),(3459,161,1419,0,347,1473,1448,10,1068047603,1,141,'',0),(3458,161,1473,0,346,89,1419,10,1068047603,1,141,'',0),(3457,161,89,0,345,1454,1473,10,1068047603,1,141,'',0),(3456,161,1454,0,344,1460,89,10,1068047603,1,141,'',0),(3455,161,1460,0,343,1392,1454,10,1068047603,1,141,'',0),(3454,161,1392,0,342,1457,1460,10,1068047603,1,141,'',0),(3453,161,1457,0,341,1419,1392,10,1068047603,1,141,'',0),(3452,161,1419,0,340,1445,1457,10,1068047603,1,141,'',0),(3451,161,1445,0,339,1472,1419,10,1068047603,1,141,'',0),(3450,161,1472,0,338,1400,1445,10,1068047603,1,141,'',0),(3449,161,1400,0,337,1438,1472,10,1068047603,1,141,'',0),(3448,161,1438,0,336,1471,1400,10,1068047603,1,141,'',0),(3447,161,1471,0,335,1470,1438,10,1068047603,1,141,'',0),(3446,161,1470,0,334,1402,1471,10,1068047603,1,141,'',0),(3445,161,1402,0,333,1469,1470,10,1068047603,1,141,'',0),(3444,161,1469,0,332,1428,1402,10,1068047603,1,141,'',0),(3443,161,1428,0,331,1432,1469,10,1068047603,1,141,'',0),(3442,161,1432,0,330,1468,1428,10,1068047603,1,141,'',0),(3441,161,1468,0,329,1410,1432,10,1068047603,1,141,'',0),(3440,161,1410,0,328,1416,1468,10,1068047603,1,141,'',0),(3439,161,1416,0,327,1436,1410,10,1068047603,1,141,'',0),(3438,161,1436,0,326,1453,1416,10,1068047603,1,141,'',0),(3437,161,1453,0,325,1420,1436,10,1068047603,1,141,'',0),(3436,161,1420,0,324,1413,1453,10,1068047603,1,141,'',0),(3435,161,1413,0,323,1382,1420,10,1068047603,1,141,'',0),(3434,161,1382,0,322,1467,1413,10,1068047603,1,141,'',0),(3433,161,1467,0,321,1432,1382,10,1068047603,1,141,'',0),(3432,161,1432,0,320,1393,1467,10,1068047603,1,141,'',0),(3431,161,1393,0,319,1466,1432,10,1068047603,1,141,'',0),(3430,161,1466,0,318,1430,1393,10,1068047603,1,141,'',0),(3429,161,1430,0,317,1399,1466,10,1068047603,1,141,'',0),(3428,161,1399,0,316,1465,1430,10,1068047603,1,141,'',0),(3427,161,1465,0,315,1464,1399,10,1068047603,1,141,'',0),(3426,161,1464,0,314,1463,1465,10,1068047603,1,141,'',0),(3425,161,1463,0,313,1462,1464,10,1068047603,1,141,'',0),(3424,161,1462,0,312,1461,1463,10,1068047603,1,141,'',0),(3423,161,1461,0,311,1388,1462,10,1068047603,1,141,'',0),(3422,161,1388,0,310,1460,1461,10,1068047603,1,141,'',0),(3421,161,1460,0,309,1459,1388,10,1068047603,1,141,'',0),(3420,161,1459,0,308,1399,1460,10,1068047603,1,141,'',0),(3419,161,1399,0,307,1428,1459,10,1068047603,1,141,'',0),(3418,161,1428,0,306,1443,1399,10,1068047603,1,141,'',0),(3417,161,1443,0,305,1393,1428,10,1068047603,1,141,'',0),(3416,161,1393,0,304,1393,1443,10,1068047603,1,141,'',0),(3415,161,1393,0,303,1458,1393,10,1068047603,1,141,'',0),(3414,161,1458,0,302,1457,1393,10,1068047603,1,141,'',0),(3751,219,1509,0,67,1521,1522,26,1068716920,13,212,'',0),(3750,219,1521,0,66,1526,1509,26,1068716920,13,212,'',0),(3749,219,1526,0,65,1503,1521,26,1068716920,13,212,'',0),(3748,219,1503,0,64,1524,1526,26,1068716920,13,212,'',0),(3747,219,1524,0,63,1523,1503,26,1068716920,13,212,'',0),(3746,219,1523,0,62,1522,1524,26,1068716920,13,212,'',0),(3745,219,1522,0,61,1509,1523,26,1068716920,13,212,'',0),(3744,219,1509,0,60,1525,1522,26,1068716920,13,212,'',0),(3743,219,1525,0,59,1503,1509,26,1068716920,13,212,'',0),(3742,219,1503,0,58,1524,1525,26,1068716920,13,212,'',0),(3741,219,1524,0,57,1523,1503,26,1068716920,13,212,'',0),(3740,219,1523,0,56,1522,1524,26,1068716920,13,212,'',0),(3739,219,1522,0,55,1509,1523,26,1068716920,13,212,'',0),(3738,219,1509,0,54,1525,1522,26,1068716920,13,212,'',0),(3737,219,1525,0,53,1503,1509,26,1068716920,13,212,'',0),(3736,219,1503,0,52,1524,1525,26,1068716920,13,212,'',0),(3735,219,1524,0,51,1523,1503,26,1068716920,13,212,'',0),(3734,219,1523,0,50,1522,1524,26,1068716920,13,212,'',0),(3733,219,1522,0,49,1509,1523,26,1068716920,13,212,'',0),(3732,219,1509,0,48,1525,1522,26,1068716920,13,212,'',0),(3731,219,1525,0,47,1503,1509,26,1068716920,13,212,'',0),(3730,219,1503,0,46,1524,1525,26,1068716920,13,212,'',0),(3729,219,1524,0,45,1523,1503,26,1068716920,13,212,'',0),(3728,219,1523,0,44,1522,1524,26,1068716920,13,212,'',0),(3727,219,1522,0,43,1509,1523,26,1068716920,13,212,'',0),(3726,219,1509,0,42,1525,1522,26,1068716920,13,212,'',0),(3725,219,1525,0,41,1503,1509,26,1068716920,13,212,'',0),(3724,219,1503,0,40,1524,1525,26,1068716920,13,212,'',0),(3723,219,1524,0,39,1523,1503,26,1068716920,13,212,'',0),(3722,219,1523,0,38,1522,1524,26,1068716920,13,212,'',0),(3721,219,1522,0,37,1509,1523,26,1068716920,13,212,'',0),(3720,219,1509,0,36,1525,1522,26,1068716920,13,212,'',0),(3719,219,1525,0,35,1503,1509,26,1068716920,13,212,'',0),(3718,219,1503,0,34,1524,1525,26,1068716920,13,212,'',0),(3717,219,1524,0,33,1523,1503,26,1068716920,13,212,'',0),(3716,219,1523,0,32,1522,1524,26,1068716920,13,212,'',0),(3715,219,1522,0,31,1509,1523,26,1068716920,13,212,'',0),(3714,219,1509,0,30,1525,1522,26,1068716920,13,212,'',0),(3713,219,1525,0,29,1503,1509,26,1068716920,13,212,'',0),(3712,219,1503,0,28,1524,1525,26,1068716920,13,212,'',0),(3711,219,1524,0,27,1523,1503,26,1068716920,13,212,'',0),(3710,219,1523,0,26,1522,1524,26,1068716920,13,212,'',0),(3709,219,1522,0,25,1509,1523,26,1068716920,13,212,'',0),(3708,219,1509,0,24,1525,1522,26,1068716920,13,212,'',0),(3707,219,1525,0,23,1503,1509,26,1068716920,13,212,'',0),(3706,219,1503,0,22,1524,1525,26,1068716920,13,212,'',0),(3705,219,1524,0,21,1523,1503,26,1068716920,13,212,'',0),(3704,219,1523,0,20,1522,1524,26,1068716920,13,212,'',0),(3703,219,1522,0,19,1509,1523,26,1068716920,13,212,'',0),(3702,219,1509,0,18,1525,1522,26,1068716920,13,212,'',0),(3701,219,1525,0,17,1503,1509,26,1068716920,13,212,'',0),(3700,219,1503,0,16,1524,1525,26,1068716920,13,212,'',0),(3699,219,1524,0,15,1523,1503,26,1068716920,13,212,'',0),(3698,219,1523,0,14,1522,1524,26,1068716920,13,212,'',0),(3697,219,1522,0,13,1509,1523,26,1068716920,13,212,'',0),(3696,219,1509,0,12,1521,1522,26,1068716920,13,212,'',0),(3695,219,1521,0,11,1501,1509,26,1068716920,13,212,'',0),(3694,219,1501,0,10,1520,1521,26,1068716920,13,212,'',0),(3693,219,1520,0,9,1519,1501,26,1068716920,13,212,'',0),(3692,219,1519,0,8,1518,1520,26,1068716920,13,212,'',0),(3691,219,1518,0,7,1517,1519,26,1068716920,13,212,'',0),(3690,219,1517,0,6,1490,1518,26,1068716920,13,212,'',0),(3689,219,1490,0,5,1516,1517,26,1068716920,13,212,'',0),(3688,219,1516,0,4,1515,1490,26,1068716920,13,211,'',0),(3687,219,1515,0,3,1514,1516,26,1068716920,13,211,'',0),(3686,219,1514,0,2,1318,1515,26,1068716920,13,210,'',0),(3685,219,1318,0,1,1140,1514,26,1068716920,13,209,'',0),(3684,219,1140,0,0,0,1318,26,1068716920,13,209,'',0),(3683,215,1513,0,83,1511,0,23,1068713677,13,203,'',0),(3682,215,1511,0,82,1510,1513,23,1068713677,13,203,'',0),(3681,215,1510,0,81,1509,1511,23,1068713677,13,203,'',0),(3680,215,1509,0,80,1512,1510,23,1068713677,13,203,'',0),(3679,215,1512,0,79,1511,1509,23,1068713677,13,203,'',0),(3678,215,1511,0,78,1510,1512,23,1068713677,13,203,'',0),(3677,215,1510,0,77,1509,1511,23,1068713677,13,203,'',0),(3676,215,1509,0,76,1512,1510,23,1068713677,13,203,'',0),(3675,215,1512,0,75,1511,1509,23,1068713677,13,203,'',0),(3674,215,1511,0,74,1510,1512,23,1068713677,13,203,'',0),(3673,215,1510,0,73,1509,1511,23,1068713677,13,203,'',0),(3672,215,1509,0,72,1512,1510,23,1068713677,13,203,'',0),(3671,215,1512,0,71,1511,1509,23,1068713677,13,203,'',0),(3670,215,1511,0,70,1510,1512,23,1068713677,13,203,'',0),(3669,215,1510,0,69,1509,1511,23,1068713677,13,203,'',0),(3668,215,1509,0,68,1512,1510,23,1068713677,13,203,'',0),(3667,215,1512,0,67,1511,1509,23,1068713677,13,203,'',0),(3666,215,1511,0,66,1510,1512,23,1068713677,13,203,'',0),(3665,215,1510,0,65,1509,1511,23,1068713677,13,203,'',0),(3664,215,1509,0,64,1512,1510,23,1068713677,13,203,'',0),(3663,215,1512,0,63,1511,1509,23,1068713677,13,203,'',0),(3662,215,1511,0,62,1510,1512,23,1068713677,13,203,'',0),(3661,215,1510,0,61,1509,1511,23,1068713677,13,203,'',0),(3660,215,1509,0,60,1512,1510,23,1068713677,13,203,'',0),(3659,215,1512,0,59,1511,1509,23,1068713677,13,203,'',0),(3658,215,1511,0,58,1510,1512,23,1068713677,13,203,'',0),(3657,215,1510,0,57,1509,1511,23,1068713677,13,203,'',0),(3656,215,1509,0,56,1509,1510,23,1068713677,13,203,'',0),(3655,215,1509,0,55,1513,1509,23,1068713677,13,203,'',0),(3654,215,1513,0,54,1511,1509,23,1068713677,13,203,'',0),(3653,215,1511,0,53,1510,1513,23,1068713677,13,203,'',0),(3652,215,1510,0,52,1509,1511,23,1068713677,13,203,'',0),(3651,215,1509,0,51,1512,1510,23,1068713677,13,203,'',0),(3650,215,1512,0,50,1511,1509,23,1068713677,13,203,'',0),(3649,215,1511,0,49,1510,1512,23,1068713677,13,203,'',0),(3648,215,1510,0,48,1509,1511,23,1068713677,13,203,'',0),(3647,215,1509,0,47,1512,1510,23,1068713677,13,203,'',0),(3646,215,1512,0,46,1511,1509,23,1068713677,13,203,'',0),(3645,215,1511,0,45,1510,1512,23,1068713677,13,203,'',0),(3644,215,1510,0,44,1509,1511,23,1068713677,13,203,'',0),(3643,215,1509,0,43,1512,1510,23,1068713677,13,203,'',0),(3642,215,1512,0,42,1511,1509,23,1068713677,13,203,'',0),(3641,215,1511,0,41,1510,1512,23,1068713677,13,203,'',0),(3640,215,1510,0,40,1509,1511,23,1068713677,13,203,'',0),(3639,215,1509,0,39,1512,1510,23,1068713677,13,203,'',0),(3638,215,1512,0,38,1511,1509,23,1068713677,13,203,'',0),(3637,215,1511,0,37,1510,1512,23,1068713677,13,203,'',0),(3636,215,1510,0,36,1509,1511,23,1068713677,13,203,'',0),(3635,215,1509,0,35,1512,1510,23,1068713677,13,203,'',0),(3634,215,1512,0,34,1511,1509,23,1068713677,13,203,'',0),(3633,215,1511,0,33,1510,1512,23,1068713677,13,203,'',0),(3632,215,1510,0,32,1509,1511,23,1068713677,13,203,'',0),(3631,215,1509,0,31,1509,1510,23,1068713677,13,203,'',0),(3630,215,1509,0,30,1513,1509,23,1068713677,13,203,'',0),(3629,215,1513,0,29,1511,1509,23,1068713677,13,203,'',0),(3628,215,1511,0,28,1510,1513,23,1068713677,13,203,'',0),(3627,215,1510,0,27,1509,1511,23,1068713677,13,203,'',0),(3626,215,1509,0,26,1512,1510,23,1068713677,13,203,'',0),(3625,215,1512,0,25,1511,1509,23,1068713677,13,203,'',0),(3624,215,1511,0,24,1510,1512,23,1068713677,13,203,'',0),(3623,215,1510,0,23,1509,1511,23,1068713677,13,203,'',0),(3622,215,1509,0,22,1512,1510,23,1068713677,13,203,'',0),(3621,215,1512,0,21,1511,1509,23,1068713677,13,203,'',0),(3620,215,1511,0,20,1510,1512,23,1068713677,13,203,'',0),(3619,215,1510,0,19,1509,1511,23,1068713677,13,203,'',0),(3618,215,1509,0,18,1512,1510,23,1068713677,13,203,'',0),(3617,215,1512,0,17,1511,1509,23,1068713677,13,203,'',0),(3616,215,1511,0,16,1510,1512,23,1068713677,13,203,'',0),(3615,215,1510,0,15,1509,1511,23,1068713677,13,203,'',0),(3614,215,1509,0,14,1509,1510,23,1068713677,13,203,'',0),(3613,215,1509,0,13,1509,1509,23,1068713677,13,203,'',0),(3612,215,1509,0,12,1509,1509,23,1068713677,13,203,'',0),(3611,215,1509,0,11,1509,1509,23,1068713677,13,203,'',0),(3610,215,1509,0,10,1508,1509,23,1068713677,13,203,'',0),(3609,215,1508,0,9,1507,1509,23,1068713677,13,203,'',0),(3608,215,1507,0,8,1506,1508,23,1068713677,13,203,'',0),(3607,215,1506,0,7,1505,1507,23,1068713677,13,203,'',0),(3606,215,1505,0,6,1504,1506,23,1068713677,13,203,'',0),(3605,215,1504,0,5,1503,1505,23,1068713677,13,203,'',0),(3604,215,1503,0,4,1489,1504,23,1068713677,13,203,'',0),(3603,215,1489,0,3,1502,1503,23,1068713677,13,202,'',0),(3602,215,1502,0,2,1501,1489,23,1068713677,13,202,'',0),(3601,215,1501,0,1,1500,1502,23,1068713677,13,202,'',0),(3600,215,1500,0,0,0,1501,23,1068713677,13,202,'',0),(3599,214,1499,0,100,1498,0,23,1068711140,13,203,'',0),(3598,214,1498,0,99,1497,1499,23,1068711140,13,203,'',0),(3597,214,1497,0,98,1496,1498,23,1068711140,13,203,'',0),(3596,214,1496,0,97,1495,1497,23,1068711140,13,203,'',0),(3595,214,1495,0,96,1499,1496,23,1068711140,13,203,'',0),(3594,214,1499,0,95,1498,1495,23,1068711140,13,203,'',0),(3593,214,1498,0,94,1497,1499,23,1068711140,13,203,'',0),(3592,214,1497,0,93,1496,1498,23,1068711140,13,203,'',0),(3591,214,1496,0,92,1495,1497,23,1068711140,13,203,'',0),(3590,214,1495,0,91,1499,1496,23,1068711140,13,203,'',0),(3589,214,1499,0,90,1498,1495,23,1068711140,13,203,'',0),(3588,214,1498,0,89,1497,1499,23,1068711140,13,203,'',0),(3587,214,1497,0,88,1496,1498,23,1068711140,13,203,'',0),(3586,214,1496,0,87,1495,1497,23,1068711140,13,203,'',0),(3585,214,1495,0,86,1499,1496,23,1068711140,13,203,'',0),(3584,214,1499,0,85,1498,1495,23,1068711140,13,203,'',0),(3583,214,1498,0,84,1497,1499,23,1068711140,13,203,'',0),(3582,214,1497,0,83,1496,1498,23,1068711140,13,203,'',0),(3581,214,1496,0,82,1495,1497,23,1068711140,13,203,'',0),(3580,214,1495,0,81,1499,1496,23,1068711140,13,203,'',0),(3579,214,1499,0,80,1498,1495,23,1068711140,13,203,'',0),(3578,214,1498,0,79,1497,1499,23,1068711140,13,203,'',0),(3577,214,1497,0,78,1496,1498,23,1068711140,13,203,'',0),(3576,214,1496,0,77,1495,1497,23,1068711140,13,203,'',0),(3575,214,1495,0,76,1499,1496,23,1068711140,13,203,'',0),(3574,214,1499,0,75,1498,1495,23,1068711140,13,203,'',0),(3573,214,1498,0,74,1497,1499,23,1068711140,13,203,'',0),(3572,214,1497,0,73,1496,1498,23,1068711140,13,203,'',0),(3571,214,1496,0,72,1495,1497,23,1068711140,13,203,'',0),(3570,214,1495,0,71,1499,1496,23,1068711140,13,203,'',0),(3569,214,1499,0,70,1498,1495,23,1068711140,13,203,'',0),(3568,214,1498,0,69,1497,1499,23,1068711140,13,203,'',0),(3567,214,1497,0,68,1496,1498,23,1068711140,13,203,'',0),(3566,214,1496,0,67,1495,1497,23,1068711140,13,203,'',0),(3565,214,1495,0,66,1499,1496,23,1068711140,13,203,'',0),(3564,214,1499,0,65,1498,1495,23,1068711140,13,203,'',0),(3563,214,1498,0,64,1497,1499,23,1068711140,13,203,'',0),(3562,214,1497,0,63,1496,1498,23,1068711140,13,203,'',0),(3561,214,1496,0,62,1495,1497,23,1068711140,13,203,'',0),(3560,214,1495,0,61,1499,1496,23,1068711140,13,203,'',0),(3559,214,1499,0,60,1498,1495,23,1068711140,13,203,'',0),(3558,214,1498,0,59,1497,1499,23,1068711140,13,203,'',0),(3557,214,1497,0,58,1496,1498,23,1068711140,13,203,'',0),(3556,214,1496,0,57,1495,1497,23,1068711140,13,203,'',0),(3555,214,1495,0,56,1499,1496,23,1068711140,13,203,'',0),(3554,214,1499,0,55,1498,1495,23,1068711140,13,203,'',0),(3553,214,1498,0,54,1497,1499,23,1068711140,13,203,'',0),(3552,214,1497,0,53,1496,1498,23,1068711140,13,203,'',0),(3551,214,1496,0,52,1495,1497,23,1068711140,13,203,'',0),(3550,214,1495,0,51,1499,1496,23,1068711140,13,203,'',0),(3549,214,1499,0,50,1498,1495,23,1068711140,13,203,'',0),(3548,214,1498,0,49,1497,1499,23,1068711140,13,203,'',0),(3547,214,1497,0,48,1496,1498,23,1068711140,13,203,'',0),(3546,214,1496,0,47,1495,1497,23,1068711140,13,203,'',0),(3545,214,1495,0,46,1499,1496,23,1068711140,13,203,'',0),(3544,214,1499,0,45,1498,1495,23,1068711140,13,203,'',0),(3543,214,1498,0,44,1497,1499,23,1068711140,13,203,'',0),(3542,214,1497,0,43,1496,1498,23,1068711140,13,203,'',0),(3541,214,1496,0,42,1495,1497,23,1068711140,13,203,'',0),(3540,214,1495,0,41,1499,1496,23,1068711140,13,203,'',0),(3539,214,1499,0,40,1498,1495,23,1068711140,13,203,'',0),(3538,214,1498,0,39,1497,1499,23,1068711140,13,203,'',0),(3537,214,1497,0,38,1496,1498,23,1068711140,13,203,'',0),(3536,214,1496,0,37,1495,1497,23,1068711140,13,203,'',0),(3535,214,1495,0,36,1499,1496,23,1068711140,13,203,'',0),(3534,214,1499,0,35,1498,1495,23,1068711140,13,203,'',0),(3533,214,1498,0,34,1497,1499,23,1068711140,13,203,'',0),(3532,214,1497,0,33,1496,1498,23,1068711140,13,203,'',0),(3531,214,1496,0,32,1495,1497,23,1068711140,13,203,'',0),(3530,214,1495,0,31,1499,1496,23,1068711140,13,203,'',0),(3529,214,1499,0,30,1498,1495,23,1068711140,13,203,'',0),(3528,214,1498,0,29,1497,1499,23,1068711140,13,203,'',0),(3527,214,1497,0,28,1496,1498,23,1068711140,13,203,'',0),(3526,214,1496,0,27,1495,1497,23,1068711140,13,203,'',0),(3525,214,1495,0,26,1499,1496,23,1068711140,13,203,'',0),(3524,214,1499,0,25,1498,1495,23,1068711140,13,203,'',0),(3523,214,1498,0,24,1497,1499,23,1068711140,13,203,'',0),(3522,214,1497,0,23,1496,1498,23,1068711140,13,203,'',0),(3521,214,1496,0,22,1495,1497,23,1068711140,13,203,'',0),(3520,214,1495,0,21,1499,1496,23,1068711140,13,203,'',0),(3519,214,1499,0,20,1498,1495,23,1068711140,13,203,'',0),(3518,214,1498,0,19,1497,1499,23,1068711140,13,203,'',0),(3517,214,1497,0,18,1496,1498,23,1068711140,13,203,'',0),(3516,214,1496,0,17,1495,1497,23,1068711140,13,203,'',0),(3515,214,1495,0,16,1499,1496,23,1068711140,13,203,'',0),(3514,214,1499,0,15,1498,1495,23,1068711140,13,203,'',0),(3513,214,1498,0,14,1497,1499,23,1068711140,13,203,'',0),(3512,214,1497,0,13,1496,1498,23,1068711140,13,203,'',0),(3511,214,1496,0,12,1495,1497,23,1068711140,13,203,'',0),(3510,214,1495,0,11,1499,1496,23,1068711140,13,203,'',0),(3509,214,1499,0,10,1498,1495,23,1068711140,13,203,'',0),(3508,214,1498,0,9,1497,1499,23,1068711140,13,203,'',0),(3507,214,1497,0,8,1496,1498,23,1068711140,13,203,'',0),(3506,214,1496,0,7,1495,1497,23,1068711140,13,203,'',0),(3505,214,1495,0,6,1494,1496,23,1068711140,13,203,'',0),(3504,214,1494,0,5,1493,1495,23,1068711140,13,202,'',0),(3503,214,1493,0,4,1492,1494,23,1068711140,13,202,'',0),(3502,214,1492,0,3,1491,1493,23,1068711140,13,202,'',0),(3501,214,1491,0,2,1490,1492,23,1068711140,13,202,'',0),(3500,214,1490,0,1,1489,1491,23,1068711140,13,202,'',0),(3499,214,1489,0,0,0,1490,23,1068711140,13,202,'',0),(3787,213,1538,0,0,0,1538,1,1068711091,13,4,'',0),(3779,212,1532,0,0,0,89,1,1068711069,13,4,'',0),(1106,107,571,0,1,570,0,4,1066916865,2,9,'',0),(1105,107,570,0,0,0,571,4,1066916865,2,8,'',0),(1107,111,572,0,0,0,573,4,1066917523,2,8,'',0),(1108,111,573,0,1,572,0,4,1066917523,2,9,'',0),(3908,226,1618,0,90,1617,1619,23,1068717935,13,203,'',0),(3907,226,1617,0,89,1490,1618,23,1068717935,13,203,'',0),(3906,226,1490,0,88,1585,1617,23,1068717935,13,203,'',0),(3905,226,1585,0,87,1555,1490,23,1068717935,13,203,'',0),(3904,226,1555,0,86,1616,1585,23,1068717935,13,203,'',0),(3887,226,1599,0,69,1605,1606,23,1068717935,13,203,'',0),(3886,226,1605,0,68,1519,1599,23,1068717935,13,203,'',0),(3885,226,1519,0,67,1604,1605,23,1068717935,13,203,'',0),(3884,226,1604,0,66,1490,1519,23,1068717935,13,203,'',0),(3876,226,1560,0,58,1599,1535,23,1068717935,13,203,'',0),(3875,226,1599,0,57,934,1560,23,1068717935,13,203,'',0),(3874,226,934,0,56,1598,1599,23,1068717935,13,203,'',0),(3873,226,1598,0,55,1597,934,23,1068717935,13,203,'',0),(3872,226,1597,0,54,1581,1598,23,1068717935,13,203,'',0),(3871,226,1581,0,53,1490,1597,23,1068717935,13,203,'',0),(3883,226,1490,0,65,1603,1604,23,1068717935,13,203,'',0),(3882,226,1603,0,64,1602,1490,23,1068717935,13,203,'',0),(3881,226,1602,0,63,1535,1603,23,1068717935,13,203,'',0),(3880,226,1535,0,62,1601,1602,23,1068717935,13,203,'',0),(3879,226,1601,0,61,1600,1535,23,1068717935,13,203,'',0),(3878,226,1600,0,60,1535,1601,23,1068717935,13,203,'',0),(3877,226,1535,0,59,1560,1600,23,1068717935,13,203,'',0),(3413,161,1457,0,301,943,1458,10,1068047603,1,141,'',0),(3412,161,943,0,300,1408,1457,10,1068047603,1,141,'',0),(3411,161,1408,0,299,1412,943,10,1068047603,1,141,'',0),(3410,161,1412,0,298,1428,1408,10,1068047603,1,141,'',0),(3409,161,1428,0,297,1388,1412,10,1068047603,1,141,'',0),(3408,161,1388,0,296,1418,1428,10,1068047603,1,141,'',0),(3407,161,1418,0,295,1456,1388,10,1068047603,1,141,'',0),(3406,161,1456,0,294,1397,1418,10,1068047603,1,141,'',0),(3405,161,1397,0,293,1445,1456,10,1068047603,1,141,'',0),(3404,161,1445,0,292,1455,1397,10,1068047603,1,141,'',0),(3403,161,1455,0,291,1454,1445,10,1068047603,1,141,'',0),(3402,161,1454,0,290,1453,1455,10,1068047603,1,141,'',0),(3401,161,1453,0,289,1452,1454,10,1068047603,1,141,'',0),(3400,161,1452,0,288,1401,1453,10,1068047603,1,141,'',0),(3399,161,1401,0,287,1451,1452,10,1068047603,1,141,'',0),(3398,161,1451,0,286,1450,1401,10,1068047603,1,141,'',0),(3397,161,1450,0,285,1449,1451,10,1068047603,1,141,'',0),(3396,161,1449,0,284,1448,1450,10,1068047603,1,141,'',0),(3395,161,1448,0,283,1447,1449,10,1068047603,1,141,'',0),(3394,161,1447,0,282,1446,1448,10,1068047603,1,141,'',0),(3393,161,1446,0,281,1411,1447,10,1068047603,1,141,'',0),(3392,161,1411,0,280,1445,1446,10,1068047603,1,141,'',0),(3391,161,1445,0,279,1444,1411,10,1068047603,1,141,'',0),(3390,161,1444,0,278,1413,1445,10,1068047603,1,141,'',0),(3389,161,1413,0,277,1443,1444,10,1068047603,1,141,'',0),(3388,161,1443,0,276,1442,1413,10,1068047603,1,141,'',0),(3387,161,1442,0,275,1441,1443,10,1068047603,1,141,'',0),(3386,161,1441,0,274,1393,1442,10,1068047603,1,141,'',0),(3385,161,1393,0,273,1441,1441,10,1068047603,1,141,'',0),(3384,161,1441,0,272,1440,1393,10,1068047603,1,141,'',0),(3383,161,1440,0,271,1439,1441,10,1068047603,1,141,'',0),(3382,161,1439,0,270,1438,1440,10,1068047603,1,141,'',0),(3381,161,1438,0,269,1437,1439,10,1068047603,1,141,'',0),(3380,161,1437,0,268,1436,1438,10,1068047603,1,141,'',0),(3379,161,1436,0,267,1435,1437,10,1068047603,1,141,'',0),(3378,161,1435,0,266,1406,1436,10,1068047603,1,141,'',0),(3377,161,1406,0,265,1388,1435,10,1068047603,1,141,'',0),(3376,161,1388,0,264,1434,1406,10,1068047603,1,141,'',0),(3375,161,1434,0,263,1433,1388,10,1068047603,1,141,'',0),(3374,161,1433,0,262,1432,1434,10,1068047603,1,141,'',0),(3373,161,1432,0,261,1431,1433,10,1068047603,1,141,'',0),(3372,161,1431,0,260,1430,1432,10,1068047603,1,141,'',0),(3371,161,1430,0,259,943,1431,10,1068047603,1,141,'',0),(3370,161,943,0,258,1393,1430,10,1068047603,1,141,'',0),(3369,161,1393,0,257,1429,943,10,1068047603,1,141,'',0),(3368,161,1429,0,256,1428,1393,10,1068047603,1,141,'',0),(3367,161,1428,0,255,1410,1429,10,1068047603,1,141,'',0),(3366,161,1410,0,254,1388,1428,10,1068047603,1,141,'',0),(3365,161,1388,0,253,1427,1410,10,1068047603,1,141,'',0),(3364,161,1427,0,252,1426,1388,10,1068047603,1,141,'',0),(3363,161,1426,0,251,1425,1427,10,1068047603,1,141,'',0),(3362,161,1425,0,250,1393,1426,10,1068047603,1,141,'',0),(3361,161,1393,0,249,1425,1425,10,1068047603,1,141,'',0),(3360,161,1425,0,248,1424,1393,10,1068047603,1,141,'',0),(3359,161,1424,0,247,1423,1425,10,1068047603,1,141,'',0),(3358,161,1423,0,246,1400,1424,10,1068047603,1,141,'',0),(3357,161,1400,0,245,1422,1423,10,1068047603,1,141,'',0),(3356,161,1422,0,244,1421,1400,10,1068047603,1,141,'',0),(3355,161,1421,0,243,1420,1422,10,1068047603,1,141,'',0),(3354,161,1420,0,242,1419,1421,10,1068047603,1,141,'',0),(3353,161,1419,0,241,1387,1420,10,1068047603,1,141,'',0),(3352,161,1387,0,240,1399,1419,10,1068047603,1,141,'',0),(3351,161,1399,0,239,1418,1387,10,1068047603,1,141,'',0),(3350,161,1418,0,238,1417,1399,10,1068047603,1,141,'',0),(3349,161,1417,0,237,1416,1418,10,1068047603,1,141,'',0),(3348,161,1416,0,236,1415,1417,10,1068047603,1,141,'',0),(3347,161,1415,0,235,1414,1416,10,1068047603,1,141,'',0),(3346,161,1414,0,234,1413,1415,10,1068047603,1,141,'',0),(3345,161,1413,0,233,1412,1414,10,1068047603,1,141,'',0),(3344,161,1412,0,232,1411,1413,10,1068047603,1,141,'',0),(3343,161,1411,0,231,1410,1412,10,1068047603,1,141,'',0),(3342,161,1410,0,230,89,1411,10,1068047603,1,141,'',0),(3341,161,89,0,229,1409,1410,10,1068047603,1,141,'',0),(3340,161,1409,0,228,1408,89,10,1068047603,1,141,'',0),(3339,161,1408,0,227,1407,1409,10,1068047603,1,141,'',0),(3338,161,1407,0,226,1406,1408,10,1068047603,1,141,'',0),(3337,161,1406,0,225,1389,1407,10,1068047603,1,141,'',0),(3336,161,1389,0,224,1405,1406,10,1068047603,1,141,'',0),(3335,161,1405,0,223,1404,1389,10,1068047603,1,141,'',0),(3334,161,1404,0,222,1403,1405,10,1068047603,1,141,'',0),(3333,161,1403,0,221,1402,1404,10,1068047603,1,141,'',0),(3332,161,1402,0,220,1401,1403,10,1068047603,1,141,'',0),(3331,161,1401,0,219,1400,1402,10,1068047603,1,141,'',0),(3330,161,1400,0,218,1399,1401,10,1068047603,1,141,'',0),(3329,161,1399,0,217,1398,1400,10,1068047603,1,141,'',0),(3328,161,1398,0,216,1388,1399,10,1068047603,1,141,'',0),(3327,161,1388,0,215,1389,1398,10,1068047603,1,141,'',0),(3326,161,1389,0,214,1397,1388,10,1068047603,1,141,'',0),(3325,161,1397,0,213,1396,1389,10,1068047603,1,141,'',0),(3324,161,1396,0,212,1395,1397,10,1068047603,1,141,'',0),(3323,161,1395,0,211,1394,1396,10,1068047603,1,141,'',0),(3322,161,1394,0,210,1386,1395,10,1068047603,1,141,'',0),(3321,161,1386,0,209,1393,1394,10,1068047603,1,141,'',0),(3320,161,1393,0,208,1392,1386,10,1068047603,1,141,'',0),(3319,161,1392,0,207,1391,1393,10,1068047603,1,141,'',0),(3318,161,1391,0,206,1390,1392,10,1068047603,1,141,'',0),(3317,161,1390,0,205,1389,1391,10,1068047603,1,141,'',0),(3316,161,1389,0,204,1384,1390,10,1068047603,1,141,'',0),(3315,161,1384,0,203,1383,1389,10,1068047603,1,141,'',0),(3314,161,1383,0,202,1388,1384,10,1068047603,1,141,'',0),(3313,161,1388,0,201,1387,1383,10,1068047603,1,141,'',0),(3312,161,1387,0,200,1386,1388,10,1068047603,1,141,'',0),(3311,161,1386,0,199,1385,1387,10,1068047603,1,141,'',0),(3310,161,1385,0,198,1384,1386,10,1068047603,1,141,'',0),(3309,161,1384,0,197,1383,1385,10,1068047603,1,141,'',0),(3308,161,1383,0,196,1382,1384,10,1068047603,1,141,'',0),(3307,161,1382,0,195,944,1383,10,1068047603,1,141,'',0),(3306,161,944,0,194,943,1382,10,1068047603,1,141,'',0),(3305,161,943,0,193,1399,944,10,1068047603,1,141,'',0),(3304,161,1399,0,192,1438,943,10,1068047603,1,141,'',0),(3303,161,1438,0,191,1466,1399,10,1068047603,1,141,'',0),(3302,161,1466,0,190,1455,1438,10,1068047603,1,141,'',0),(3301,161,1455,0,189,1485,1466,10,1068047603,1,141,'',0),(3300,161,1485,0,188,1484,1455,10,1068047603,1,141,'',0),(3299,161,1484,0,187,1483,1485,10,1068047603,1,141,'',0),(3298,161,1483,0,186,1387,1484,10,1068047603,1,141,'',0),(3297,161,1387,0,185,1406,1483,10,1068047603,1,141,'',0),(3296,161,1406,0,184,1457,1387,10,1068047603,1,141,'',0),(3295,161,1457,0,183,1452,1406,10,1068047603,1,141,'',0),(3294,161,1452,0,182,1482,1457,10,1068047603,1,141,'',0),(3293,161,1482,0,181,1407,1452,10,1068047603,1,141,'',0),(3292,161,1407,0,180,1403,1482,10,1068047603,1,141,'',0),(3291,161,1403,0,179,1448,1407,10,1068047603,1,141,'',0),(3290,161,1448,0,178,1481,1403,10,1068047603,1,141,'',0),(3289,161,1481,0,177,1480,1448,10,1068047603,1,141,'',0),(3288,161,1480,0,176,1479,1481,10,1068047603,1,141,'',0),(3287,161,1479,0,175,1478,1480,10,1068047603,1,141,'',0),(3286,161,1478,0,174,1399,1479,10,1068047603,1,141,'',0),(3285,161,1399,0,173,1440,1478,10,1068047603,1,141,'',0),(3284,161,1440,0,172,1386,1399,10,1068047603,1,141,'',0),(3283,161,1386,0,171,1477,1440,10,1068047603,1,141,'',0),(3282,161,1477,0,170,1412,1386,10,1068047603,1,141,'',0),(3281,161,1412,0,169,1401,1477,10,1068047603,1,141,'',0),(3280,161,1401,0,168,1457,1412,10,1068047603,1,141,'',0),(3279,161,1457,0,167,1399,1401,10,1068047603,1,141,'',0),(3278,161,1399,0,166,1476,1457,10,1068047603,1,141,'',0),(3277,161,1476,0,165,1382,1399,10,1068047603,1,141,'',0),(3276,161,1382,0,164,1448,1476,10,1068047603,1,141,'',0),(3275,161,1448,0,163,1427,1382,10,1068047603,1,141,'',0),(3274,161,1427,0,162,1438,1448,10,1068047603,1,141,'',0),(3273,161,1438,0,161,1396,1427,10,1068047603,1,141,'',0),(3272,161,1396,0,160,1475,1438,10,1068047603,1,141,'',0),(3271,161,1475,0,159,1474,1396,10,1068047603,1,141,'',0),(3270,161,1474,0,158,1448,1475,10,1068047603,1,141,'',0),(3269,161,1448,0,157,1419,1474,10,1068047603,1,141,'',0),(3268,161,1419,0,156,1473,1448,10,1068047603,1,141,'',0),(3267,161,1473,0,155,89,1419,10,1068047603,1,141,'',0),(3266,161,89,0,154,1454,1473,10,1068047603,1,141,'',0),(3265,161,1454,0,153,1460,89,10,1068047603,1,141,'',0),(3264,161,1460,0,152,1392,1454,10,1068047603,1,141,'',0),(3263,161,1392,0,151,1457,1460,10,1068047603,1,141,'',0),(3262,161,1457,0,150,1419,1392,10,1068047603,1,141,'',0),(3261,161,1419,0,149,1445,1457,10,1068047603,1,141,'',0),(3260,161,1445,0,148,1472,1419,10,1068047603,1,141,'',0),(3259,161,1472,0,147,1400,1445,10,1068047603,1,141,'',0),(3258,161,1400,0,146,1438,1472,10,1068047603,1,141,'',0),(3257,161,1438,0,145,1471,1400,10,1068047603,1,141,'',0),(3256,161,1471,0,144,1470,1438,10,1068047603,1,141,'',0),(3255,161,1470,0,143,1402,1471,10,1068047603,1,141,'',0),(3254,161,1402,0,142,1469,1470,10,1068047603,1,141,'',0),(3253,161,1469,0,141,1428,1402,10,1068047603,1,141,'',0),(3252,161,1428,0,140,1432,1469,10,1068047603,1,141,'',0),(3251,161,1432,0,139,1468,1428,10,1068047603,1,141,'',0),(3250,161,1468,0,138,1410,1432,10,1068047603,1,141,'',0),(3249,161,1410,0,137,1416,1468,10,1068047603,1,141,'',0),(3248,161,1416,0,136,1436,1410,10,1068047603,1,141,'',0),(3247,161,1436,0,135,1453,1416,10,1068047603,1,141,'',0),(3246,161,1453,0,134,1420,1436,10,1068047603,1,141,'',0),(3245,161,1420,0,133,1413,1453,10,1068047603,1,141,'',0),(3244,161,1413,0,132,1382,1420,10,1068047603,1,141,'',0),(3243,161,1382,0,131,1467,1413,10,1068047603,1,141,'',0),(3242,161,1467,0,130,1432,1382,10,1068047603,1,141,'',0),(3241,161,1432,0,129,1393,1467,10,1068047603,1,141,'',0),(3240,161,1393,0,128,1466,1432,10,1068047603,1,141,'',0),(3239,161,1466,0,127,1430,1393,10,1068047603,1,141,'',0),(3238,161,1430,0,126,1399,1466,10,1068047603,1,141,'',0),(3237,161,1399,0,125,1465,1430,10,1068047603,1,141,'',0),(3236,161,1465,0,124,1464,1399,10,1068047603,1,141,'',0),(3235,161,1464,0,123,1463,1465,10,1068047603,1,141,'',0),(3234,161,1463,0,122,1462,1464,10,1068047603,1,141,'',0),(3233,161,1462,0,121,1461,1463,10,1068047603,1,141,'',0),(3232,161,1461,0,120,1388,1462,10,1068047603,1,141,'',0),(3231,161,1388,0,119,1460,1461,10,1068047603,1,141,'',0),(3230,161,1460,0,118,1459,1388,10,1068047603,1,141,'',0),(3229,161,1459,0,117,1399,1460,10,1068047603,1,141,'',0),(3228,161,1399,0,116,1428,1459,10,1068047603,1,141,'',0),(3227,161,1428,0,115,1443,1399,10,1068047603,1,141,'',0),(3226,161,1443,0,114,1393,1428,10,1068047603,1,141,'',0),(3225,161,1393,0,113,1393,1443,10,1068047603,1,141,'',0),(3224,161,1393,0,112,1458,1393,10,1068047603,1,141,'',0),(3223,161,1458,0,111,1457,1393,10,1068047603,1,141,'',0),(3764,219,1522,0,80,1509,1523,26,1068716920,13,212,'',0),(3763,219,1509,0,79,1525,1522,26,1068716920,13,212,'',0),(3762,219,1525,0,78,1503,1509,26,1068716920,13,212,'',0),(3761,219,1503,0,77,1524,1525,26,1068716920,13,212,'',0),(3760,219,1524,0,76,1523,1503,26,1068716920,13,212,'',0),(3757,219,1509,0,73,1525,1522,26,1068716920,13,212,'',0),(3758,219,1522,0,74,1509,1523,26,1068716920,13,212,'',0),(3759,219,1523,0,75,1522,1524,26,1068716920,13,212,'',0),(3756,219,1525,0,72,1503,1509,26,1068716920,13,212,'',0),(3755,219,1503,0,71,1524,1525,26,1068716920,13,212,'',0),(4798,45,33,0,1,32,34,14,1066388816,11,152,'',0),(4793,115,1903,0,2,7,0,14,1066991725,11,155,'',0),(4792,115,7,0,1,1903,1903,14,1066991725,11,155,'',0),(4791,115,1903,0,0,0,7,14,1066991725,11,152,'',0),(4806,116,1911,0,3,25,0,14,1066992054,11,155,'',0),(4805,116,25,0,2,1910,1911,14,1066992054,11,155,'',0),(4804,116,1910,0,1,1909,25,14,1066992054,11,152,'',0),(4803,116,1909,0,0,0,1910,14,1066992054,11,152,'',0),(4797,45,32,0,0,0,33,14,1066388816,11,152,'',0),(3903,226,1616,0,85,1535,1555,23,1068717935,13,203,'',0),(3902,226,1535,0,84,1615,1616,23,1068717935,13,203,'',0),(3784,212,1536,0,5,1535,1537,1,1068711069,13,119,'',0),(3783,212,1535,0,4,1534,1536,1,1068711069,13,119,'',0),(3782,212,1534,0,3,1533,1535,1,1068711069,13,119,'',0),(3781,212,1533,0,2,89,1534,1,1068711069,13,119,'',0),(3862,226,1339,0,44,1591,1592,23,1068717935,13,203,'',0),(3068,14,1362,0,5,1316,0,4,1033920830,2,199,'',0),(3067,14,1316,0,4,1361,1362,4,1033920830,2,198,'',0),(3869,226,1596,0,51,1595,1490,23,1068717935,13,203,'',0),(3822,226,1564,0,4,1563,1565,23,1068717935,13,203,'',0),(3024,149,1316,0,3,1339,1340,4,1068041016,2,198,'',0),(3868,226,1595,0,50,1594,1596,23,1068717935,13,203,'',0),(3867,226,1594,0,49,89,1595,23,1068717935,13,203,'',0),(3866,226,89,0,48,1593,1594,23,1068717935,13,203,'',0),(3865,226,1593,0,47,1381,89,23,1068717935,13,203,'',0),(3864,226,1381,0,46,1592,1593,23,1068717935,13,203,'',0),(3863,226,1592,0,45,1339,1381,23,1068717935,13,203,'',0),(3870,226,1490,0,52,1596,1581,23,1068717935,13,203,'',0),(3777,220,1531,0,8,1509,1499,26,1068716967,13,212,'',0),(3776,220,1509,0,7,1526,1531,26,1068716967,13,212,'',0),(3775,220,1526,0,6,1530,1509,26,1068716967,13,212,'',0),(3774,220,1530,0,5,1529,1526,26,1068716967,13,212,'',0),(3771,220,1515,0,2,1514,1528,26,1068716967,13,211,'',0),(3772,220,1528,0,3,1515,1529,26,1068716967,13,211,'',0),(3773,220,1529,0,4,1528,1530,26,1068716967,13,212,'',0),(3861,226,1591,0,43,1535,1339,23,1068717935,13,203,'',0),(3860,226,1535,0,42,1590,1591,23,1068717935,13,203,'',0),(3859,226,1590,0,41,1589,1535,23,1068717935,13,203,'',0),(3858,226,1589,0,40,1588,1590,23,1068717935,13,203,'',0),(3857,226,1588,0,39,1587,1589,23,1068717935,13,203,'',0),(3856,226,1587,0,38,1586,1588,23,1068717935,13,203,'',0),(3222,161,1457,0,110,943,1458,10,1068047603,1,141,'',0),(3221,161,943,0,109,1408,1457,10,1068047603,1,141,'',0),(3220,161,1408,0,108,1412,943,10,1068047603,1,141,'',0),(3219,161,1412,0,107,1428,1408,10,1068047603,1,141,'',0),(3218,161,1428,0,106,1388,1412,10,1068047603,1,141,'',0),(3217,161,1388,0,105,1418,1428,10,1068047603,1,141,'',0),(3216,161,1418,0,104,1456,1388,10,1068047603,1,141,'',0),(3215,161,1456,0,103,1397,1418,10,1068047603,1,141,'',0),(3214,161,1397,0,102,1445,1456,10,1068047603,1,141,'',0),(3213,161,1445,0,101,1455,1397,10,1068047603,1,141,'',0),(3212,161,1455,0,100,1454,1445,10,1068047603,1,141,'',0),(3211,161,1454,0,99,1453,1455,10,1068047603,1,141,'',0),(3210,161,1453,0,98,1452,1454,10,1068047603,1,141,'',0),(3209,161,1452,0,97,1401,1453,10,1068047603,1,141,'',0),(3208,161,1401,0,96,1451,1452,10,1068047603,1,141,'',0),(3207,161,1451,0,95,1450,1401,10,1068047603,1,141,'',0),(3206,161,1450,0,94,1449,1451,10,1068047603,1,141,'',0),(3205,161,1449,0,93,1448,1450,10,1068047603,1,141,'',0),(3204,161,1448,0,92,1447,1449,10,1068047603,1,141,'',0),(3203,161,1447,0,91,1446,1448,10,1068047603,1,141,'',0),(3202,161,1446,0,90,1411,1447,10,1068047603,1,141,'',0),(3201,161,1411,0,89,1445,1446,10,1068047603,1,141,'',0),(3200,161,1445,0,88,1444,1411,10,1068047603,1,141,'',0),(3199,161,1444,0,87,1413,1445,10,1068047603,1,141,'',0),(3198,161,1413,0,86,1443,1444,10,1068047603,1,141,'',0),(3197,161,1443,0,85,1442,1413,10,1068047603,1,141,'',0),(3196,161,1442,0,84,1441,1443,10,1068047603,1,141,'',0),(3195,161,1441,0,83,1393,1442,10,1068047603,1,141,'',0),(3194,161,1393,0,82,1441,1441,10,1068047603,1,141,'',0),(3193,161,1441,0,81,1440,1393,10,1068047603,1,141,'',0),(3192,161,1440,0,80,1439,1441,10,1068047603,1,141,'',0),(3191,161,1439,0,79,1438,1440,10,1068047603,1,141,'',0),(3190,161,1438,0,78,1437,1439,10,1068047603,1,141,'',0),(3189,161,1437,0,77,1436,1438,10,1068047603,1,141,'',0),(3188,161,1436,0,76,1435,1437,10,1068047603,1,141,'',0),(3187,161,1435,0,75,1406,1436,10,1068047603,1,141,'',0),(3186,161,1406,0,74,1388,1435,10,1068047603,1,141,'',0),(3185,161,1388,0,73,1434,1406,10,1068047603,1,141,'',0),(3184,161,1434,0,72,1433,1388,10,1068047603,1,141,'',0),(3183,161,1433,0,71,1432,1434,10,1068047603,1,141,'',0),(3182,161,1432,0,70,1431,1433,10,1068047603,1,141,'',0),(3181,161,1431,0,69,1430,1432,10,1068047603,1,141,'',0),(3180,161,1430,0,68,943,1431,10,1068047603,1,141,'',0),(3179,161,943,0,67,1393,1430,10,1068047603,1,141,'',0),(3178,161,1393,0,66,1429,943,10,1068047603,1,141,'',0),(3177,161,1429,0,65,1428,1393,10,1068047603,1,141,'',0),(3176,161,1428,0,64,1410,1429,10,1068047603,1,141,'',0),(3175,161,1410,0,63,1388,1428,10,1068047603,1,141,'',0),(3174,161,1388,0,62,1427,1410,10,1068047603,1,141,'',0),(3173,161,1427,0,61,1426,1388,10,1068047603,1,141,'',0),(3172,161,1426,0,60,1425,1427,10,1068047603,1,141,'',0),(3171,161,1425,0,59,1393,1426,10,1068047603,1,141,'',0),(3170,161,1393,0,58,1425,1425,10,1068047603,1,141,'',0),(3169,161,1425,0,57,1424,1393,10,1068047603,1,141,'',0),(3168,161,1424,0,56,1423,1425,10,1068047603,1,141,'',0),(3167,161,1423,0,55,1400,1424,10,1068047603,1,141,'',0),(3166,161,1400,0,54,1422,1423,10,1068047603,1,141,'',0),(3165,161,1422,0,53,1421,1400,10,1068047603,1,141,'',0),(3164,161,1421,0,52,1420,1422,10,1068047603,1,141,'',0),(3163,161,1420,0,51,1419,1421,10,1068047603,1,141,'',0),(3162,161,1419,0,50,1387,1420,10,1068047603,1,141,'',0),(3161,161,1387,0,49,1399,1419,10,1068047603,1,141,'',0),(3160,161,1399,0,48,1418,1387,10,1068047603,1,141,'',0),(3159,161,1418,0,47,1417,1399,10,1068047603,1,141,'',0),(3158,161,1417,0,46,1416,1418,10,1068047603,1,141,'',0),(3157,161,1416,0,45,1415,1417,10,1068047603,1,141,'',0),(3156,161,1415,0,44,1414,1416,10,1068047603,1,141,'',0),(3155,161,1414,0,43,1413,1415,10,1068047603,1,141,'',0),(3154,161,1413,0,42,1412,1414,10,1068047603,1,141,'',0),(3153,161,1412,0,41,1411,1413,10,1068047603,1,141,'',0),(3152,161,1411,0,40,1410,1412,10,1068047603,1,141,'',0),(3151,161,1410,0,39,89,1411,10,1068047603,1,141,'',0),(3150,161,89,0,38,1409,1410,10,1068047603,1,141,'',0),(3149,161,1409,0,37,1408,89,10,1068047603,1,141,'',0),(3148,161,1408,0,36,1407,1409,10,1068047603,1,141,'',0),(3147,161,1407,0,35,1406,1408,10,1068047603,1,141,'',0),(3146,161,1406,0,34,1389,1407,10,1068047603,1,141,'',0),(3145,161,1389,0,33,1405,1406,10,1068047603,1,141,'',0),(3144,161,1405,0,32,1404,1389,10,1068047603,1,141,'',0),(3143,161,1404,0,31,1403,1405,10,1068047603,1,141,'',0),(3142,161,1403,0,30,1402,1404,10,1068047603,1,141,'',0),(3141,161,1402,0,29,1401,1403,10,1068047603,1,141,'',0),(3140,161,1401,0,28,1400,1402,10,1068047603,1,141,'',0),(3139,161,1400,0,27,1399,1401,10,1068047603,1,141,'',0),(3138,161,1399,0,26,1398,1400,10,1068047603,1,141,'',0),(3137,161,1398,0,25,1388,1399,10,1068047603,1,141,'',0),(3136,161,1388,0,24,1389,1398,10,1068047603,1,141,'',0),(3135,161,1389,0,23,1397,1388,10,1068047603,1,141,'',0),(3134,161,1397,0,22,1396,1389,10,1068047603,1,141,'',0),(3133,161,1396,0,21,1395,1397,10,1068047603,1,141,'',0),(3132,161,1395,0,20,1394,1396,10,1068047603,1,141,'',0),(3131,161,1394,0,19,1386,1395,10,1068047603,1,141,'',0),(3130,161,1386,0,18,1393,1394,10,1068047603,1,141,'',0),(3129,161,1393,0,17,1392,1386,10,1068047603,1,141,'',0),(3128,161,1392,0,16,1391,1393,10,1068047603,1,141,'',0),(3127,161,1391,0,15,1390,1392,10,1068047603,1,141,'',0),(3126,161,1390,0,14,1389,1391,10,1068047603,1,141,'',0),(3125,161,1389,0,13,1384,1390,10,1068047603,1,141,'',0),(3124,161,1384,0,12,1383,1389,10,1068047603,1,141,'',0),(3123,161,1383,0,11,1388,1384,10,1068047603,1,141,'',0),(3122,161,1388,0,10,1387,1383,10,1068047603,1,141,'',0),(3121,161,1387,0,9,1386,1388,10,1068047603,1,141,'',0),(3120,161,1386,0,8,1385,1387,10,1068047603,1,141,'',0),(3119,161,1385,0,7,1384,1386,10,1068047603,1,141,'',0),(3118,161,1384,0,6,1383,1385,10,1068047603,1,141,'',0),(3117,161,1383,0,5,1382,1384,10,1068047603,1,141,'',0),(3116,161,1382,0,4,944,1383,10,1068047603,1,141,'',0),(3115,161,944,0,3,943,1382,10,1068047603,1,141,'',0),(3114,161,943,0,2,1381,944,10,1068047603,1,141,'',0),(3113,161,1381,0,1,934,943,10,1068047603,1,140,'',0),(3112,161,934,0,0,0,1381,10,1068047603,1,140,'',0),(3855,226,1586,0,37,1411,1587,23,1068717935,13,203,'',0),(3854,226,1411,0,36,1585,1586,23,1068717935,13,203,'',0),(3853,226,1585,0,35,1492,1411,23,1068717935,13,203,'',0),(3852,226,1492,0,34,1584,1585,23,1068717935,13,203,'',0),(3851,226,1584,0,33,1578,1492,23,1068717935,13,203,'',0),(3850,226,1578,0,32,1558,1584,23,1068717935,13,203,'',0),(3849,226,1558,0,31,1583,1578,23,1068717935,13,203,'',0),(3848,226,1583,0,30,1582,1558,23,1068717935,13,203,'',0),(3847,226,1582,0,29,1581,1583,23,1068717935,13,203,'',0),(3846,226,1581,0,28,1490,1582,23,1068717935,13,203,'',0),(3845,226,1490,0,27,1580,1581,23,1068717935,13,203,'',0),(3844,226,1580,0,26,1579,1490,23,1068717935,13,203,'',0),(3843,226,1579,0,25,1575,1580,23,1068717935,13,203,'',0),(3842,226,1575,0,24,1578,1579,23,1068717935,13,203,'',0),(3841,226,1578,0,23,1577,1575,23,1068717935,13,203,'',0),(3840,226,1577,0,22,33,1578,23,1068717935,13,203,'',0),(3839,226,33,0,21,1576,1577,23,1068717935,13,203,'',0),(3838,226,1576,0,20,1575,33,23,1068717935,13,203,'',0),(3837,226,1575,0,19,1388,1576,23,1068717935,13,203,'',0),(3836,226,1388,0,18,1574,1575,23,1068717935,13,203,'',0),(3835,226,1574,0,17,1573,1388,23,1068717935,13,203,'',0),(3834,226,1573,0,16,1572,1574,23,1068717935,13,203,'',0),(3833,226,1572,0,15,1571,1573,23,1068717935,13,203,'',0),(3832,226,1571,0,14,1570,1572,23,1068717935,13,203,'',0),(3831,226,1570,0,13,1490,1571,23,1068717935,13,203,'',0),(3830,226,1490,0,12,1569,1570,23,1068717935,13,203,'',0),(3829,226,1569,0,11,1554,1490,23,1068717935,13,203,'',0),(3828,226,1554,0,10,1568,1569,23,1068717935,13,203,'',0),(3827,226,1568,0,9,1490,1554,23,1068717935,13,203,'',0),(3826,226,1490,0,8,1567,1568,23,1068717935,13,203,'',0),(3770,220,1514,0,1,1527,1515,26,1068716967,13,210,'',0),(3769,220,1527,0,0,0,1514,26,1068716967,13,209,'',0),(3768,219,1526,0,84,1503,0,26,1068716920,13,212,'',0),(3765,219,1523,0,81,1522,1524,26,1068716920,13,212,'',0),(3766,219,1524,0,82,1523,1503,26,1068716920,13,212,'',0),(3767,219,1503,0,83,1524,1526,26,1068716920,13,212,'',0),(3804,213,1543,0,17,1550,1551,1,1068711091,13,119,'',0),(3803,213,1550,0,16,1549,1543,1,1068711091,13,119,'',0),(3824,226,1566,0,6,1565,1567,23,1068717935,13,203,'',0),(3823,226,1565,0,5,1564,1566,23,1068717935,13,203,'',0),(3802,213,1549,0,15,1548,1550,1,1068711091,13,119,'',0),(3801,213,1548,0,14,1547,1549,1,1068711091,13,119,'',0),(3800,213,1547,0,13,1490,1548,1,1068711091,13,119,'',0),(3799,213,1490,0,12,1546,1547,1,1068711091,13,119,'',0),(3798,213,1546,0,11,1545,1490,1,1068711091,13,119,'',0),(3821,226,1563,0,3,1562,1564,23,1068717935,13,202,'',0),(3820,226,1562,0,2,1561,1563,23,1068717935,13,202,'',0),(3819,226,1561,0,1,1560,1562,23,1068717935,13,202,'',0),(3818,226,1560,0,0,0,1561,23,1068717935,13,202,'',0),(3817,213,1559,0,30,1558,0,1,1068711091,13,119,'',0),(3816,213,1558,0,29,1557,1559,1,1068711091,13,119,'',0),(3815,213,1557,0,28,1519,1558,1,1068711091,13,119,'',0),(3814,213,1519,0,27,1388,1557,1,1068711091,13,119,'',0),(3813,213,1388,0,26,1556,1519,1,1068711091,13,119,'',0),(3796,213,1544,0,9,1543,1545,1,1068711091,13,119,'',0),(3797,213,1545,0,10,1544,1546,1,1068711091,13,119,'',0),(3066,14,1361,0,3,1360,1316,4,1033920830,2,198,'',0),(3065,14,1360,0,2,1359,1361,4,1033920830,2,197,'',0),(3064,14,1359,0,1,1358,1360,4,1033920830,2,9,'',0),(3063,14,1358,0,0,0,1359,4,1033920830,2,8,'',0),(2993,206,1140,0,0,0,1318,4,1068123599,2,8,'',0),(2994,206,1318,0,1,1140,1094,4,1068123599,2,9,'',0),(2995,206,1094,0,2,1318,1319,4,1068123599,2,197,'',0),(2996,206,1319,0,3,1094,1320,4,1068123599,2,197,'',0),(2997,206,1320,0,4,1319,1316,4,1068123599,2,198,'',0),(2998,206,1316,0,5,1320,1321,4,1068123599,2,198,'',0),(2999,206,1321,0,6,1316,0,4,1068123599,2,199,'',0),(3812,213,1556,0,25,1535,1388,1,1068711091,13,119,'',0),(3811,213,1535,0,24,1555,1556,1,1068711091,13,119,'',0),(3810,213,1555,0,23,1554,1535,1,1068711091,13,119,'',0),(3809,213,1554,0,22,1553,1555,1,1068711091,13,119,'',0),(3808,213,1553,0,21,1552,1554,1,1068711091,13,119,'',0),(3807,213,1552,0,20,1490,1553,1,1068711091,13,119,'',0),(3806,213,1490,0,19,1551,1552,1,1068711091,13,119,'',0),(3805,213,1551,0,18,1543,1490,1,1068711091,13,119,'',0),(3023,149,1339,0,2,1338,1316,4,1068041016,2,197,'',0),(3022,149,1338,0,1,1337,1339,4,1068041016,2,9,'',0),(3021,149,1337,0,0,0,1338,4,1068041016,2,8,'',0),(3901,226,1615,0,83,1614,1535,23,1068717935,13,203,'',0),(3900,226,1614,0,82,1613,1615,23,1068717935,13,203,'',0),(3899,226,1613,0,81,1612,1614,23,1068717935,13,203,'',0),(3898,226,1612,0,80,1611,1613,23,1068717935,13,203,'',0),(3897,226,1611,0,79,1535,1612,23,1068717935,13,203,'',0),(3896,226,1535,0,78,1411,1611,23,1068717935,13,203,'',0),(3895,226,1411,0,77,1610,1535,23,1068717935,13,203,'',0),(3111,1,1380,0,0,0,0,1,1033917596,1,4,'',0),(3795,213,1543,0,8,1542,1544,1,1068711091,13,119,'',0),(3794,213,1542,0,7,1535,1543,1,1068711091,13,119,'',0),(3793,213,1535,0,6,33,1542,1,1068711091,13,119,'',0),(3792,213,33,0,5,1541,1535,1,1068711091,13,119,'',0),(3791,213,1541,0,4,1540,33,1,1068711091,13,119,'',0),(3790,213,1540,0,3,1539,1541,1,1068711091,13,119,'',0),(3789,213,1539,0,2,1538,1540,1,1068711091,13,119,'',0),(3788,213,1538,0,1,1538,1539,1,1068711091,13,119,'',0),(3786,212,1381,0,7,1537,0,1,1068711069,13,119,'',0),(3785,212,1537,0,6,1536,1381,1,1068711069,13,119,'',0),(3778,220,1499,0,9,1531,0,26,1068716967,13,212,'',0),(3780,212,89,0,1,1532,1533,1,1068711069,13,119,'',0),(3894,226,1610,0,76,1595,1411,23,1068717935,13,203,'',0),(3893,226,1595,0,75,1609,1610,23,1068717935,13,203,'',0),(3892,226,1609,0,74,1608,1595,23,1068717935,13,203,'',0),(3891,226,1608,0,73,1607,1609,23,1068717935,13,203,'',0),(3890,226,1607,0,72,1557,1608,23,1068717935,13,203,'',0),(3889,226,1557,0,71,1606,1607,23,1068717935,13,203,'',0),(3888,226,1606,0,70,1599,1557,23,1068717935,13,203,'',0),(3909,226,1619,0,91,1618,1537,23,1068717935,13,203,'',0),(3910,226,1537,0,92,1619,1620,23,1068717935,13,203,'',0),(3911,226,1620,0,93,1537,1621,23,1068717935,13,203,'',0),(3912,226,1621,0,94,1620,1492,23,1068717935,13,203,'',0),(3913,226,1492,0,95,1621,1573,23,1068717935,13,203,'',0),(3914,226,1573,0,96,1492,1388,23,1068717935,13,203,'',0),(3915,226,1388,0,97,1573,1575,23,1068717935,13,203,'',0),(3916,226,1575,0,98,1388,1576,23,1068717935,13,203,'',0),(3917,226,1576,0,99,1575,33,23,1068717935,13,203,'',0),(3918,226,33,0,100,1576,1617,23,1068717935,13,203,'',0),(3919,226,1617,0,101,33,1622,23,1068717935,13,203,'',0),(3920,226,1622,0,102,1617,1490,23,1068717935,13,203,'',0),(3921,226,1490,0,103,1622,1604,23,1068717935,13,203,'',0),(3922,226,1604,0,104,1490,1623,23,1068717935,13,203,'',0),(3923,226,1623,0,105,1604,1535,23,1068717935,13,203,'',0),(3924,226,1535,0,106,1623,1624,23,1068717935,13,203,'',0),(3925,226,1624,0,107,1535,1625,23,1068717935,13,203,'',0),(3926,226,1625,0,108,1624,1490,23,1068717935,13,203,'',0),(3927,226,1490,0,109,1625,1626,23,1068717935,13,203,'',0),(3928,226,1626,0,110,1490,1627,23,1068717935,13,203,'',0),(3929,226,1627,0,111,1626,1628,23,1068717935,13,203,'',0),(3930,226,1628,0,112,1627,1492,23,1068717935,13,203,'',0),(3931,226,1492,0,113,1628,1629,23,1068717935,13,203,'',0),(3932,226,1629,0,114,1492,1490,23,1068717935,13,203,'',0),(3933,226,1490,0,115,1629,1617,23,1068717935,13,203,'',0),(3934,226,1617,0,116,1490,1630,23,1068717935,13,203,'',0),(3935,226,1630,0,117,1617,1619,23,1068717935,13,203,'',0),(3936,226,1619,0,118,1630,33,23,1068717935,13,203,'',0),(3937,226,33,0,119,1619,1631,23,1068717935,13,203,'',0),(3938,226,1631,0,120,33,1578,23,1068717935,13,203,'',0),(3939,226,1578,0,121,1631,1632,23,1068717935,13,203,'',0),(3940,226,1632,0,122,1578,1633,23,1068717935,13,203,'',0),(3941,226,1633,0,123,1632,1578,23,1068717935,13,203,'',0),(3942,226,1578,0,124,1633,1492,23,1068717935,13,203,'',0),(3943,226,1492,0,125,1578,1634,23,1068717935,13,203,'',0),(3944,226,1634,0,126,1492,1585,23,1068717935,13,203,'',0),(3945,226,1585,0,127,1634,1411,23,1068717935,13,203,'',0),(3946,226,1411,0,128,1585,89,23,1068717935,13,203,'',0),(3947,226,89,0,129,1411,1599,23,1068717935,13,203,'',0),(3948,226,1599,0,130,89,1635,23,1068717935,13,203,'',0),(3949,226,1635,0,131,1599,1388,23,1068717935,13,203,'',0),(3950,226,1388,0,132,1635,1575,23,1068717935,13,203,'',0),(3951,226,1575,0,133,1388,1576,23,1068717935,13,203,'',0),(3952,226,1576,0,134,1575,1636,23,1068717935,13,203,'',0),(3953,226,1636,0,135,1576,1637,23,1068717935,13,203,'',0),(3954,226,1637,0,136,1636,1638,23,1068717935,13,203,'',0),(3955,226,1638,0,137,1637,1490,23,1068717935,13,203,'',0),(3956,226,1490,0,138,1638,1617,23,1068717935,13,203,'',0),(3957,226,1617,0,139,1490,1578,23,1068717935,13,203,'',0),(3958,226,1578,0,140,1617,1639,23,1068717935,13,203,'',0),(3959,226,1639,0,141,1578,1586,23,1068717935,13,203,'',0),(3960,226,1586,0,142,1639,1640,23,1068717935,13,203,'',0),(3961,226,1640,0,143,1586,1641,23,1068717935,13,203,'',0),(3962,226,1641,0,144,1640,1599,23,1068717935,13,203,'',0),(3963,226,1599,0,145,1641,1339,23,1068717935,13,203,'',0),(3964,226,1339,0,146,1599,1411,23,1068717935,13,203,'',0),(3965,226,1411,0,147,1339,1535,23,1068717935,13,203,'',0),(3966,226,1535,0,148,1411,1611,23,1068717935,13,203,'',0),(3967,226,1611,0,149,1535,1622,23,1068717935,13,203,'',0),(3968,226,1622,0,150,1611,1381,23,1068717935,13,203,'',0),(3969,226,1381,0,151,1622,1642,23,1068717935,13,203,'',0),(3970,226,1642,0,152,1381,1643,23,1068717935,13,203,'',0),(3971,226,1643,0,153,1642,1381,23,1068717935,13,203,'',0),(3972,226,1381,0,154,1643,89,23,1068717935,13,203,'',0),(3973,226,89,0,155,1381,1493,23,1068717935,13,203,'',0),(3974,226,1493,0,156,89,1644,23,1068717935,13,203,'',0),(3975,226,1644,0,157,1493,33,23,1068717935,13,203,'',0),(3976,226,33,0,158,1644,1492,23,1068717935,13,203,'',0),(3977,226,1492,0,159,33,1645,23,1068717935,13,203,'',0),(3978,226,1645,0,160,1492,1646,23,1068717935,13,203,'',0),(3979,226,1646,0,161,1645,1537,23,1068717935,13,203,'',0),(3980,226,1537,0,162,1646,1572,23,1068717935,13,203,'',0),(3981,226,1572,0,163,1537,1519,23,1068717935,13,203,'',0),(3982,226,1519,0,164,1572,1578,23,1068717935,13,203,'',0),(3983,226,1578,0,165,1519,1647,23,1068717935,13,203,'',0),(3984,226,1647,0,166,1578,1490,23,1068717935,13,203,'',0),(3985,226,1490,0,167,1647,1648,23,1068717935,13,203,'',0),(3986,226,1648,0,168,1490,33,23,1068717935,13,203,'',0),(3987,226,33,0,169,1648,1649,23,1068717935,13,203,'',0),(3988,226,1649,0,170,33,89,23,1068717935,13,203,'',0),(3989,226,89,0,171,1649,1650,23,1068717935,13,203,'',0),(3990,226,1650,0,172,89,1388,23,1068717935,13,203,'',0),(3991,226,1388,0,173,1650,1575,23,1068717935,13,203,'',0),(3992,226,1575,0,174,1388,1576,23,1068717935,13,203,'',0),(3993,226,1576,0,175,1575,1593,23,1068717935,13,203,'',0),(3994,226,1593,0,176,1576,89,23,1068717935,13,203,'',0),(3995,226,89,0,177,1593,1651,23,1068717935,13,203,'',0),(3996,226,1651,0,178,89,1652,23,1068717935,13,203,'',0),(3997,226,1652,0,179,1651,1653,23,1068717935,13,203,'',0),(3998,226,1653,0,180,1652,1578,23,1068717935,13,203,'',0),(3999,226,1578,0,181,1653,1535,23,1068717935,13,203,'',0),(4000,226,1535,0,182,1578,1654,23,1068717935,13,203,'',0),(4001,226,1654,0,183,1535,1388,23,1068717935,13,203,'',0),(4002,226,1388,0,184,1654,1636,23,1068717935,13,203,'',0),(4003,226,1636,0,185,1388,1637,23,1068717935,13,203,'',0),(4004,226,1637,0,186,1636,1655,23,1068717935,13,203,'',0),(4005,226,1655,0,187,1637,1656,23,1068717935,13,203,'',0),(4006,226,1656,0,188,1655,1657,23,1068717935,13,203,'',0),(4007,226,1657,0,189,1656,1658,23,1068717935,13,203,'',0),(4008,226,1658,0,190,1657,1659,23,1068717935,13,203,'',0),(4009,226,1659,0,191,1658,1613,23,1068717935,13,203,'',0),(4010,226,1613,0,192,1659,1596,23,1068717935,13,203,'',0),(4011,226,1596,0,193,1613,1490,23,1068717935,13,203,'',0),(4012,226,1490,0,194,1596,1571,23,1068717935,13,203,'',0),(4013,226,1571,0,195,1490,1660,23,1068717935,13,203,'',0),(4014,226,1660,0,196,1571,1578,23,1068717935,13,203,'',0),(4015,226,1578,0,197,1660,1632,23,1068717935,13,203,'',0),(4016,226,1632,0,198,1578,1619,23,1068717935,13,203,'',0),(4017,226,1619,0,199,1632,1587,23,1068717935,13,203,'',0),(4018,226,1587,0,200,1619,1492,23,1068717935,13,203,'',0),(4019,226,1492,0,201,1587,1661,23,1068717935,13,203,'',0),(4020,226,1661,0,202,1492,1573,23,1068717935,13,203,'',0),(4021,226,1573,0,203,1661,1662,23,1068717935,13,203,'',0),(4022,226,1662,0,204,1573,1663,23,1068717935,13,203,'',0),(4023,226,1663,0,205,1662,1535,23,1068717935,13,203,'',0),(4024,226,1535,0,206,1663,1664,23,1068717935,13,203,'',0),(4025,226,1664,0,207,1535,33,23,1068717935,13,203,'',0),(4026,226,33,0,208,1664,1492,23,1068717935,13,203,'',0),(4027,226,1492,0,209,33,1665,23,1068717935,13,203,'',0),(4028,226,1665,0,210,1492,1666,23,1068717935,13,203,'',0),(4029,226,1666,0,211,1665,1537,23,1068717935,13,203,'',0),(4030,226,1537,0,212,1666,1667,23,1068717935,13,203,'',0),(4031,226,1667,0,213,1537,1535,23,1068717935,13,203,'',0),(4032,226,1535,0,214,1667,1585,23,1068717935,13,203,'',0),(4033,226,1585,0,215,1535,1411,23,1068717935,13,203,'',0),(4034,226,1411,0,216,1585,1586,23,1068717935,13,203,'',0),(4035,226,1586,0,217,1411,1668,23,1068717935,13,203,'',0),(4036,226,1668,0,218,1586,1578,23,1068717935,13,203,'',0),(4037,226,1578,0,219,1668,1381,23,1068717935,13,203,'',0),(4038,226,1381,0,220,1578,1669,23,1068717935,13,203,'',0),(4039,226,1669,0,221,1381,33,23,1068717935,13,203,'',0),(4040,226,33,0,222,1669,32,23,1068717935,13,203,'',0),(4041,226,32,0,223,33,1580,23,1068717935,13,203,'',0),(4042,226,1580,0,224,32,1670,23,1068717935,13,203,'',0),(4043,226,1670,0,225,1580,1671,23,1068717935,13,203,'',0),(4044,226,1671,0,226,1670,1672,23,1068717935,13,203,'',0),(4045,226,1672,0,227,1671,0,23,1068717935,13,203,'',0),(4441,227,1723,0,4,1670,0,25,1068718128,1,207,'',0),(4440,227,1670,0,3,1722,1723,25,1068718128,1,207,'',0),(4439,227,1722,0,2,1721,1670,25,1068718128,1,207,'',0),(4438,227,1721,0,1,1543,1722,25,1068718128,1,207,'',0),(4437,227,1543,0,0,0,1721,25,1068718128,1,207,'',0),(4880,228,1723,0,9,1535,0,1,1068718629,12,119,'',0),(4052,229,1542,0,0,0,11,1,1068718672,12,4,'',0),(4053,229,11,0,1,1542,934,1,1068718672,12,119,'',0),(4054,229,934,0,2,11,1542,1,1068718672,12,119,'',0),(4055,229,1542,0,3,934,0,1,1068718672,12,119,'',0),(4056,230,1676,0,0,0,11,1,1068718712,12,4,'',0),(4057,230,11,0,1,1676,1578,1,1068718712,12,119,'',0),(4058,230,1578,0,2,11,1676,1,1068718712,12,119,'',0),(4059,230,1676,0,3,1578,1677,1,1068718712,12,119,'',0),(4060,230,1677,0,4,1676,0,1,1068718712,12,119,'',0),(4061,231,1678,0,0,0,11,1,1068718746,12,4,'',0),(4062,231,11,0,1,1678,1560,1,1068718746,12,119,'',0),(4063,231,1560,0,2,11,1678,1,1068718746,12,119,'',0),(4064,231,1678,0,3,1560,1679,1,1068718746,12,119,'',0),(4065,231,1679,0,4,1678,0,1,1068718746,12,119,'',0),(4066,233,1140,0,0,0,1514,26,1068718705,13,209,'',0),(4067,233,1514,0,1,1140,1515,26,1068718705,13,210,'',0),(4068,233,1515,0,2,1514,1516,26,1068718705,13,211,'',0),(4069,233,1516,0,3,1515,1680,26,1068718705,13,211,'',0),(4070,233,1680,0,4,1516,1681,26,1068718705,13,212,'',0),(4071,233,1681,0,5,1680,1682,26,1068718705,13,212,'',0),(4072,233,1682,0,6,1681,1683,26,1068718705,13,212,'',0),(4073,233,1683,0,7,1682,1684,26,1068718705,13,212,'',0),(4074,233,1684,0,8,1683,1685,26,1068718705,13,212,'',0),(4075,233,1685,0,9,1684,1686,26,1068718705,13,212,'',0),(4076,233,1686,0,10,1685,1687,26,1068718705,13,212,'',0),(4077,233,1687,0,11,1686,1509,26,1068718705,13,212,'',0),(4078,233,1509,0,12,1687,1509,26,1068718705,13,212,'',0),(4079,233,1509,0,13,1509,1688,26,1068718705,13,212,'',0),(4080,233,1688,0,14,1509,0,26,1068718705,13,212,'',0),(4081,232,1689,0,0,0,1663,24,1068718861,12,204,'',0),(4082,232,1663,0,1,1689,1690,24,1068718861,12,205,'',0),(4083,232,1690,0,2,1663,1678,24,1068718861,12,205,'',0),(4084,232,1678,0,3,1690,1691,24,1068718861,12,205,'',0),(4085,232,1691,0,4,1678,1692,24,1068718861,12,205,'',0),(4086,232,1692,0,5,1691,0,24,1068718861,12,205,'',0),(4087,235,1693,0,0,0,1694,26,1068718760,13,209,'',0),(4088,235,1694,0,1,1693,1695,26,1068718760,13,210,'',0),(4089,235,1695,0,2,1694,1515,26,1068718760,13,211,'',0),(4090,235,1515,0,3,1695,1516,26,1068718760,13,212,'',0),(4091,235,1516,0,4,1515,0,26,1068718760,13,212,'',0),(4092,234,1696,0,0,0,89,24,1068718957,12,204,'',0),(4093,234,89,0,1,1696,1697,24,1068718957,12,205,'',0),(4094,234,1697,0,2,89,1698,24,1068718957,12,205,'',0),(4095,234,1698,0,3,1697,1678,24,1068718957,12,205,'',0),(4096,234,1678,0,4,1698,1679,24,1068718957,12,205,'',0),(4097,234,1679,0,5,1678,0,24,1068718957,12,205,'',0),(4098,237,1493,0,0,0,1699,23,1068719051,13,202,'',0),(4099,237,1699,0,1,1493,1700,23,1068719051,13,202,'',0),(4100,237,1700,0,2,1699,1489,23,1068719051,13,202,'',0),(4101,237,1489,0,3,1700,1490,23,1068719051,13,202,'',0),(4102,237,1490,0,4,1489,1701,23,1068719051,13,203,'',0),(4103,237,1701,0,5,1490,1535,23,1068719051,13,203,'',0),(4104,237,1535,0,6,1701,1702,23,1068719051,13,203,'',0),(4105,237,1702,0,7,1535,1655,23,1068719051,13,203,'',0),(4106,237,1655,0,8,1702,1562,23,1068719051,13,203,'',0),(4107,237,1562,0,9,1655,1699,23,1068719051,13,203,'',0),(4108,237,1699,0,10,1562,934,23,1068719051,13,203,'',0),(4109,237,934,0,11,1699,1703,23,1068719051,13,203,'',0),(4110,237,1703,0,12,934,1704,23,1068719051,13,203,'',0),(4111,237,1704,0,13,1703,1509,23,1068719051,13,203,'',0),(4112,237,1509,0,14,1704,1509,23,1068719051,13,203,'',0),(4113,237,1509,0,15,1509,1704,23,1068719051,13,203,'',0),(4114,237,1704,0,16,1509,1509,23,1068719051,13,203,'',0),(4115,237,1509,0,17,1704,1509,23,1068719051,13,203,'',0),(4116,237,1509,0,18,1509,1704,23,1068719051,13,203,'',0),(4117,237,1704,0,19,1509,1509,23,1068719051,13,203,'',0),(4118,237,1509,0,20,1704,1509,23,1068719051,13,203,'',0),(4119,237,1509,0,21,1509,1704,23,1068719051,13,203,'',0),(4120,237,1704,0,22,1509,1509,23,1068719051,13,203,'',0),(4121,237,1509,0,23,1704,1509,23,1068719051,13,203,'',0),(4122,237,1509,0,24,1509,1704,23,1068719051,13,203,'',0),(4123,237,1704,0,25,1509,1509,23,1068719051,13,203,'',0),(4124,237,1509,0,26,1704,1509,23,1068719051,13,203,'',0),(4125,237,1509,0,27,1509,1704,23,1068719051,13,203,'',0),(4126,237,1704,0,28,1509,1509,23,1068719051,13,203,'',0),(4127,237,1509,0,29,1704,1509,23,1068719051,13,203,'',0),(4128,237,1509,0,30,1509,1704,23,1068719051,13,203,'',0),(4129,237,1704,0,31,1509,1509,23,1068719051,13,203,'',0),(4130,237,1509,0,32,1704,1509,23,1068719051,13,203,'',0),(4131,237,1509,0,33,1509,1704,23,1068719051,13,203,'',0),(4132,237,1704,0,34,1509,1509,23,1068719051,13,203,'',0),(4133,237,1509,0,35,1704,1509,23,1068719051,13,203,'',0),(4134,237,1509,0,36,1509,1704,23,1068719051,13,203,'',0),(4135,237,1704,0,37,1509,1509,23,1068719051,13,203,'',0),(4136,237,1509,0,38,1704,1509,23,1068719051,13,203,'',0),(4137,237,1509,0,39,1509,1704,23,1068719051,13,203,'',0),(4138,237,1704,0,40,1509,1509,23,1068719051,13,203,'',0),(4139,237,1509,0,41,1704,1509,23,1068719051,13,203,'',0),(4140,237,1509,0,42,1509,1704,23,1068719051,13,203,'',0),(4141,237,1704,0,43,1509,1509,23,1068719051,13,203,'',0),(4142,237,1509,0,44,1704,1509,23,1068719051,13,203,'',0),(4143,237,1509,0,45,1509,1704,23,1068719051,13,203,'',0),(4144,237,1704,0,46,1509,1509,23,1068719051,13,203,'',0),(4145,237,1509,0,47,1704,1509,23,1068719051,13,203,'',0),(4146,237,1509,0,48,1509,1704,23,1068719051,13,203,'',0),(4147,237,1704,0,49,1509,1509,23,1068719051,13,203,'',0),(4148,237,1509,0,50,1704,1509,23,1068719051,13,203,'',0),(4149,237,1509,0,51,1509,1704,23,1068719051,13,203,'',0),(4150,237,1704,0,52,1509,1509,23,1068719051,13,203,'',0),(4151,237,1509,0,53,1704,1509,23,1068719051,13,203,'',0),(4152,237,1509,0,54,1509,1704,23,1068719051,13,203,'',0),(4153,237,1704,0,55,1509,1509,23,1068719051,13,203,'',0),(4154,237,1509,0,56,1704,1509,23,1068719051,13,203,'',0),(4155,237,1509,0,57,1509,1704,23,1068719051,13,203,'',0),(4156,237,1704,0,58,1509,1509,23,1068719051,13,203,'',0),(4157,237,1509,0,59,1704,1509,23,1068719051,13,203,'',0),(4158,237,1509,0,60,1509,1704,23,1068719051,13,203,'',0),(4159,237,1704,0,61,1509,1509,23,1068719051,13,203,'',0),(4160,237,1509,0,62,1704,1509,23,1068719051,13,203,'',0),(4161,237,1509,0,63,1509,1704,23,1068719051,13,203,'',0),(4162,237,1704,0,64,1509,1509,23,1068719051,13,203,'',0),(4163,237,1509,0,65,1704,1509,23,1068719051,13,203,'',0),(4164,237,1509,0,66,1509,1704,23,1068719051,13,203,'',0),(4165,237,1704,0,67,1509,1509,23,1068719051,13,203,'',0),(4166,237,1509,0,68,1704,1509,23,1068719051,13,203,'',0),(4167,237,1509,0,69,1509,1704,23,1068719051,13,203,'',0),(4168,237,1704,0,70,1509,1509,23,1068719051,13,203,'',0),(4169,237,1509,0,71,1704,1509,23,1068719051,13,203,'',0),(4170,237,1509,0,72,1509,1704,23,1068719051,13,203,'',0),(4171,237,1704,0,73,1509,1509,23,1068719051,13,203,'',0),(4172,237,1509,0,74,1704,1509,23,1068719051,13,203,'',0),(4173,237,1509,0,75,1509,1704,23,1068719051,13,203,'',0),(4174,237,1704,0,76,1509,1509,23,1068719051,13,203,'',0),(4175,237,1509,0,77,1704,1509,23,1068719051,13,203,'',0),(4176,237,1509,0,78,1509,1704,23,1068719051,13,203,'',0),(4177,237,1704,0,79,1509,1509,23,1068719051,13,203,'',0),(4178,237,1509,0,80,1704,1509,23,1068719051,13,203,'',0),(4179,237,1509,0,81,1509,1704,23,1068719051,13,203,'',0),(4180,237,1704,0,82,1509,1509,23,1068719051,13,203,'',0),(4181,237,1509,0,83,1704,1509,23,1068719051,13,203,'',0),(4182,237,1509,0,84,1509,1704,23,1068719051,13,203,'',0),(4183,237,1704,0,85,1509,1509,23,1068719051,13,203,'',0),(4184,237,1509,0,86,1704,1509,23,1068719051,13,203,'',0),(4185,237,1509,0,87,1509,1704,23,1068719051,13,203,'',0),(4186,237,1704,0,88,1509,1509,23,1068719051,13,203,'',0),(4187,237,1509,0,89,1704,1509,23,1068719051,13,203,'',0),(4188,237,1509,0,90,1509,1704,23,1068719051,13,203,'',0),(4189,237,1704,0,91,1509,1509,23,1068719051,13,203,'',0),(4190,237,1509,0,92,1704,1509,23,1068719051,13,203,'',0),(4191,237,1509,0,93,1509,1704,23,1068719051,13,203,'',0),(4192,237,1704,0,94,1509,1509,23,1068719051,13,203,'',0),(4193,237,1509,0,95,1704,1509,23,1068719051,13,203,'',0),(4194,237,1509,0,96,1509,1704,23,1068719051,13,203,'',0),(4195,237,1704,0,97,1509,1509,23,1068719051,13,203,'',0),(4196,237,1509,0,98,1704,1509,23,1068719051,13,203,'',0),(4197,237,1509,0,99,1509,1704,23,1068719051,13,203,'',0),(4198,237,1704,0,100,1509,1509,23,1068719051,13,203,'',0),(4199,237,1509,0,101,1704,1509,23,1068719051,13,203,'',0),(4200,237,1509,0,102,1509,1705,23,1068719051,13,203,'',0),(4201,237,1705,0,103,1509,1704,23,1068719051,13,203,'',0),(4202,237,1704,0,104,1705,1509,23,1068719051,13,203,'',0),(4203,237,1509,0,105,1704,1509,23,1068719051,13,203,'',0),(4204,237,1509,0,106,1509,1704,23,1068719051,13,203,'',0),(4205,237,1704,0,107,1509,1509,23,1068719051,13,203,'',0),(4206,237,1509,0,108,1704,1509,23,1068719051,13,203,'',0),(4207,237,1509,0,109,1509,1704,23,1068719051,13,203,'',0),(4208,237,1704,0,110,1509,1509,23,1068719051,13,203,'',0),(4209,237,1509,0,111,1704,1509,23,1068719051,13,203,'',0),(4210,237,1509,0,112,1509,1704,23,1068719051,13,203,'',0),(4211,237,1704,0,113,1509,1509,23,1068719051,13,203,'',0),(4212,237,1509,0,114,1704,1509,23,1068719051,13,203,'',0),(4213,237,1509,0,115,1509,1704,23,1068719051,13,203,'',0),(4214,237,1704,0,116,1509,1509,23,1068719051,13,203,'',0),(4215,237,1509,0,117,1704,1509,23,1068719051,13,203,'',0),(4216,237,1509,0,118,1509,1704,23,1068719051,13,203,'',0),(4217,237,1704,0,119,1509,1509,23,1068719051,13,203,'',0),(4218,237,1509,0,120,1704,1509,23,1068719051,13,203,'',0),(4219,237,1509,0,121,1509,1704,23,1068719051,13,203,'',0),(4220,237,1704,0,122,1509,1509,23,1068719051,13,203,'',0),(4221,237,1509,0,123,1704,1509,23,1068719051,13,203,'',0),(4222,237,1509,0,124,1509,1704,23,1068719051,13,203,'',0),(4223,237,1704,0,125,1509,1509,23,1068719051,13,203,'',0),(4224,237,1509,0,126,1704,1509,23,1068719051,13,203,'',0),(4225,237,1509,0,127,1509,1704,23,1068719051,13,203,'',0),(4226,237,1704,0,128,1509,1509,23,1068719051,13,203,'',0),(4227,237,1509,0,129,1704,1509,23,1068719051,13,203,'',0),(4228,237,1509,0,130,1509,1704,23,1068719051,13,203,'',0),(4229,237,1704,0,131,1509,1509,23,1068719051,13,203,'',0),(4230,237,1509,0,132,1704,1509,23,1068719051,13,203,'',0),(4231,237,1509,0,133,1509,1704,23,1068719051,13,203,'',0),(4232,237,1704,0,134,1509,1509,23,1068719051,13,203,'',0),(4233,237,1509,0,135,1704,1509,23,1068719051,13,203,'',0),(4234,237,1509,0,136,1509,1704,23,1068719051,13,203,'',0),(4235,237,1704,0,137,1509,1509,23,1068719051,13,203,'',0),(4236,237,1509,0,138,1704,1509,23,1068719051,13,203,'',0),(4237,237,1509,0,139,1509,1704,23,1068719051,13,203,'',0),(4238,237,1704,0,140,1509,1509,23,1068719051,13,203,'',0),(4239,237,1509,0,141,1704,1509,23,1068719051,13,203,'',0),(4240,237,1509,0,142,1509,1704,23,1068719051,13,203,'',0),(4241,237,1704,0,143,1509,1509,23,1068719051,13,203,'',0),(4242,237,1509,0,144,1704,1509,23,1068719051,13,203,'',0),(4243,237,1509,0,145,1509,1704,23,1068719051,13,203,'',0),(4244,237,1704,0,146,1509,1509,23,1068719051,13,203,'',0),(4245,237,1509,0,147,1704,1509,23,1068719051,13,203,'',0),(4246,237,1509,0,148,1509,1704,23,1068719051,13,203,'',0),(4247,237,1704,0,149,1509,1509,23,1068719051,13,203,'',0),(4248,237,1509,0,150,1704,1509,23,1068719051,13,203,'',0),(4249,237,1509,0,151,1509,1704,23,1068719051,13,203,'',0),(4250,237,1704,0,152,1509,1509,23,1068719051,13,203,'',0),(4251,237,1509,0,153,1704,1509,23,1068719051,13,203,'',0),(4252,237,1509,0,154,1509,1704,23,1068719051,13,203,'',0),(4253,237,1704,0,155,1509,1509,23,1068719051,13,203,'',0),(4254,237,1509,0,156,1704,1509,23,1068719051,13,203,'',0),(4255,237,1509,0,157,1509,1704,23,1068719051,13,203,'',0),(4256,237,1704,0,158,1509,1509,23,1068719051,13,203,'',0),(4257,237,1509,0,159,1704,1509,23,1068719051,13,203,'',0),(4258,237,1509,0,160,1509,1704,23,1068719051,13,203,'',0),(4259,237,1704,0,161,1509,1509,23,1068719051,13,203,'',0),(4260,237,1509,0,162,1704,1509,23,1068719051,13,203,'',0),(4261,237,1509,0,163,1509,1704,23,1068719051,13,203,'',0),(4262,237,1704,0,164,1509,1509,23,1068719051,13,203,'',0),(4263,237,1509,0,165,1704,1509,23,1068719051,13,203,'',0),(4264,237,1509,0,166,1509,1704,23,1068719051,13,203,'',0),(4265,237,1704,0,167,1509,1509,23,1068719051,13,203,'',0),(4266,237,1509,0,168,1704,1509,23,1068719051,13,203,'',0),(4267,237,1509,0,169,1509,1704,23,1068719051,13,203,'',0),(4268,237,1704,0,170,1509,1509,23,1068719051,13,203,'',0),(4269,237,1509,0,171,1704,1509,23,1068719051,13,203,'',0),(4270,237,1509,0,172,1509,1704,23,1068719051,13,203,'',0),(4271,237,1704,0,173,1509,1509,23,1068719051,13,203,'',0),(4272,237,1509,0,174,1704,1509,23,1068719051,13,203,'',0),(4273,237,1509,0,175,1509,1704,23,1068719051,13,203,'',0),(4274,237,1704,0,176,1509,1509,23,1068719051,13,203,'',0),(4275,237,1509,0,177,1704,1509,23,1068719051,13,203,'',0),(4276,237,1509,0,178,1509,1704,23,1068719051,13,203,'',0),(4277,237,1704,0,179,1509,1509,23,1068719051,13,203,'',0),(4278,237,1509,0,180,1704,1509,23,1068719051,13,203,'',0),(4279,237,1509,0,181,1509,1704,23,1068719051,13,203,'',0),(4280,237,1704,0,182,1509,1509,23,1068719051,13,203,'',0),(4281,237,1509,0,183,1704,1509,23,1068719051,13,203,'',0),(4282,237,1509,0,184,1509,1704,23,1068719051,13,203,'',0),(4283,237,1704,0,185,1509,1509,23,1068719051,13,203,'',0),(4284,237,1509,0,186,1704,1509,23,1068719051,13,203,'',0),(4285,237,1509,0,187,1509,1704,23,1068719051,13,203,'',0),(4286,237,1704,0,188,1509,1509,23,1068719051,13,203,'',0),(4287,237,1509,0,189,1704,1509,23,1068719051,13,203,'',0),(4288,237,1509,0,190,1509,1704,23,1068719051,13,203,'',0),(4289,237,1704,0,191,1509,1509,23,1068719051,13,203,'',0),(4290,237,1509,0,192,1704,1509,23,1068719051,13,203,'',0),(4291,237,1509,0,193,1509,1704,23,1068719051,13,203,'',0),(4292,237,1704,0,194,1509,1509,23,1068719051,13,203,'',0),(4293,237,1509,0,195,1704,1509,23,1068719051,13,203,'',0),(4294,237,1509,0,196,1509,1704,23,1068719051,13,203,'',0),(4295,237,1704,0,197,1509,1509,23,1068719051,13,203,'',0),(4296,237,1509,0,198,1704,1509,23,1068719051,13,203,'',0),(4297,237,1509,0,199,1509,1704,23,1068719051,13,203,'',0),(4298,237,1704,0,200,1509,1509,23,1068719051,13,203,'',0),(4299,237,1509,0,201,1704,1509,23,1068719051,13,203,'',0),(4300,237,1509,0,202,1509,1704,23,1068719051,13,203,'',0),(4301,237,1704,0,203,1509,1509,23,1068719051,13,203,'',0),(4302,237,1509,0,204,1704,1509,23,1068719051,13,203,'',0),(4303,237,1509,0,205,1509,1704,23,1068719051,13,203,'',0),(4304,237,1704,0,206,1509,1509,23,1068719051,13,203,'',0),(4305,237,1509,0,207,1704,1509,23,1068719051,13,203,'',0),(4306,237,1509,0,208,1509,1704,23,1068719051,13,203,'',0),(4307,237,1704,0,209,1509,1509,23,1068719051,13,203,'',0),(4308,237,1509,0,210,1704,1509,23,1068719051,13,203,'',0),(4309,237,1509,0,211,1509,1704,23,1068719051,13,203,'',0),(4310,237,1704,0,212,1509,1509,23,1068719051,13,203,'',0),(4311,237,1509,0,213,1704,1509,23,1068719051,13,203,'',0),(4312,237,1509,0,214,1509,1704,23,1068719051,13,203,'',0),(4313,237,1704,0,215,1509,1509,23,1068719051,13,203,'',0),(4314,237,1509,0,216,1704,1509,23,1068719051,13,203,'',0),(4315,237,1509,0,217,1509,1704,23,1068719051,13,203,'',0),(4316,237,1704,0,218,1509,1509,23,1068719051,13,203,'',0),(4317,237,1509,0,219,1704,1509,23,1068719051,13,203,'',0),(4318,237,1509,0,220,1509,1704,23,1068719051,13,203,'',0),(4319,237,1704,0,221,1509,1509,23,1068719051,13,203,'',0),(4320,237,1509,0,222,1704,1509,23,1068719051,13,203,'',0),(4321,237,1509,0,223,1509,1704,23,1068719051,13,203,'',0),(4322,237,1704,0,224,1509,1509,23,1068719051,13,203,'',0),(4323,237,1509,0,225,1704,1509,23,1068719051,13,203,'',0),(4324,237,1509,0,226,1509,1704,23,1068719051,13,203,'',0),(4325,237,1704,0,227,1509,1509,23,1068719051,13,203,'',0),(4326,237,1509,0,228,1704,1509,23,1068719051,13,203,'',0),(4327,237,1509,0,229,1509,1704,23,1068719051,13,203,'',0),(4328,237,1704,0,230,1509,1509,23,1068719051,13,203,'',0),(4329,237,1509,0,231,1704,1509,23,1068719051,13,203,'',0),(4330,237,1509,0,232,1509,1704,23,1068719051,13,203,'',0),(4331,237,1704,0,233,1509,1509,23,1068719051,13,203,'',0),(4332,237,1509,0,234,1704,1509,23,1068719051,13,203,'',0),(4333,237,1509,0,235,1509,1704,23,1068719051,13,203,'',0),(4334,237,1704,0,236,1509,1509,23,1068719051,13,203,'',0),(4335,237,1509,0,237,1704,1509,23,1068719051,13,203,'',0),(4336,237,1509,0,238,1509,1706,23,1068719051,13,203,'',0),(4337,237,1706,0,239,1509,0,23,1068719051,13,213,'',1),(4338,236,1707,0,0,0,1708,24,1068719251,12,204,'',0),(4339,236,1708,0,1,1707,1709,24,1068719251,12,204,'',0),(4340,236,1709,0,2,1708,1542,24,1068719251,12,205,'',0),(4341,236,1542,0,3,1709,1692,24,1068719251,12,205,'',0),(4342,236,1692,0,4,1542,0,24,1068719251,12,205,'',0),(4343,238,1583,0,0,0,1710,23,1068719129,13,202,'',0),(4344,238,1710,0,1,1583,1587,23,1068719129,13,202,'',0),(4345,238,1587,0,2,1710,1711,23,1068719129,13,202,'',0),(4346,238,1711,0,3,1587,1558,23,1068719129,13,202,'',0),(4347,238,1558,0,4,1711,1712,23,1068719129,13,202,'',0),(4348,238,1712,0,5,1558,1713,23,1068719129,13,203,'',0),(4349,238,1713,0,6,1712,1714,23,1068719129,13,203,'',0),(4350,238,1714,0,7,1713,1715,23,1068719129,13,203,'',0),(4351,238,1715,0,8,1714,1499,23,1068719129,13,203,'',0),(4352,238,1499,0,9,1715,1503,23,1068719129,13,203,'',0),(4353,238,1503,0,10,1499,1716,23,1068719129,13,203,'',0),(4354,238,1716,0,11,1503,1713,23,1068719129,13,203,'',0),(4355,238,1713,0,12,1716,1714,23,1068719129,13,203,'',0),(4356,238,1714,0,13,1713,1715,23,1068719129,13,203,'',0),(4357,238,1715,0,14,1714,1499,23,1068719129,13,203,'',0),(4358,238,1499,0,15,1715,1503,23,1068719129,13,203,'',0),(4359,238,1503,0,16,1499,1716,23,1068719129,13,203,'',0),(4360,238,1716,0,17,1503,1713,23,1068719129,13,203,'',0),(4361,238,1713,0,18,1716,1714,23,1068719129,13,203,'',0),(4362,238,1714,0,19,1713,1715,23,1068719129,13,203,'',0),(4363,238,1715,0,20,1714,1499,23,1068719129,13,203,'',0),(4364,238,1499,0,21,1715,1503,23,1068719129,13,203,'',0),(4365,238,1503,0,22,1499,1716,23,1068719129,13,203,'',0),(4366,238,1716,0,23,1503,1713,23,1068719129,13,203,'',0),(4367,238,1713,0,24,1716,1714,23,1068719129,13,203,'',0),(4368,238,1714,0,25,1713,1715,23,1068719129,13,203,'',0),(4369,238,1715,0,26,1714,1499,23,1068719129,13,203,'',0),(4370,238,1499,0,27,1715,1503,23,1068719129,13,203,'',0),(4371,238,1503,0,28,1499,1716,23,1068719129,13,203,'',0),(4372,238,1716,0,29,1503,1713,23,1068719129,13,203,'',0),(4373,238,1713,0,30,1716,1714,23,1068719129,13,203,'',0),(4374,238,1714,0,31,1713,1715,23,1068719129,13,203,'',0),(4375,238,1715,0,32,1714,1499,23,1068719129,13,203,'',0),(4376,238,1499,0,33,1715,1503,23,1068719129,13,203,'',0),(4377,238,1503,0,34,1499,1526,23,1068719129,13,203,'',0),(4378,238,1526,0,35,1503,1712,23,1068719129,13,203,'',0),(4379,238,1712,0,36,1526,1713,23,1068719129,13,203,'',0),(4380,238,1713,0,37,1712,1714,23,1068719129,13,203,'',0),(4381,238,1714,0,38,1713,1715,23,1068719129,13,203,'',0),(4382,238,1715,0,39,1714,1499,23,1068719129,13,203,'',0),(4383,238,1499,0,40,1715,1503,23,1068719129,13,203,'',0),(4384,238,1503,0,41,1499,1716,23,1068719129,13,203,'',0),(4385,238,1716,0,42,1503,1713,23,1068719129,13,203,'',0),(4386,238,1713,0,43,1716,1714,23,1068719129,13,203,'',0),(4387,238,1714,0,44,1713,1715,23,1068719129,13,203,'',0),(4388,238,1715,0,45,1714,1499,23,1068719129,13,203,'',0),(4389,238,1499,0,46,1715,1503,23,1068719129,13,203,'',0),(4390,238,1503,0,47,1499,1716,23,1068719129,13,203,'',0),(4391,238,1716,0,48,1503,1713,23,1068719129,13,203,'',0),(4392,238,1713,0,49,1716,1714,23,1068719129,13,203,'',0),(4393,238,1714,0,50,1713,1715,23,1068719129,13,203,'',0),(4394,238,1715,0,51,1714,1499,23,1068719129,13,203,'',0),(4395,238,1499,0,52,1715,1503,23,1068719129,13,203,'',0),(4396,238,1503,0,53,1499,1716,23,1068719129,13,203,'',0),(4397,238,1716,0,54,1503,1713,23,1068719129,13,203,'',0),(4398,238,1713,0,55,1716,1714,23,1068719129,13,203,'',0),(4399,238,1714,0,56,1713,1715,23,1068719129,13,203,'',0),(4400,238,1715,0,57,1714,1499,23,1068719129,13,203,'',0),(4401,238,1499,0,58,1715,1503,23,1068719129,13,203,'',0),(4402,238,1503,0,59,1499,1716,23,1068719129,13,203,'',0),(4403,238,1716,0,60,1503,1713,23,1068719129,13,203,'',0),(4404,238,1713,0,61,1716,1714,23,1068719129,13,203,'',0),(4405,238,1714,0,62,1713,1715,23,1068719129,13,203,'',0),(4406,238,1715,0,63,1714,1499,23,1068719129,13,203,'',0),(4407,238,1499,0,64,1715,1503,23,1068719129,13,203,'',0),(4408,238,1503,0,65,1499,1526,23,1068719129,13,203,'',0),(4409,238,1526,0,66,1503,1712,23,1068719129,13,203,'',0),(4410,238,1712,0,67,1526,1713,23,1068719129,13,203,'',0),(4411,238,1713,0,68,1712,1714,23,1068719129,13,203,'',0),(4412,238,1714,0,69,1713,1715,23,1068719129,13,203,'',0),(4413,238,1715,0,70,1714,1499,23,1068719129,13,203,'',0),(4414,238,1499,0,71,1715,1503,23,1068719129,13,203,'',0),(4415,238,1503,0,72,1499,1716,23,1068719129,13,203,'',0),(4416,238,1716,0,73,1503,1713,23,1068719129,13,203,'',0),(4417,238,1713,0,74,1716,1714,23,1068719129,13,203,'',0),(4418,238,1714,0,75,1713,1715,23,1068719129,13,203,'',0),(4419,238,1715,0,76,1714,1499,23,1068719129,13,203,'',0),(4420,238,1499,0,77,1715,1503,23,1068719129,13,203,'',0),(4421,238,1503,0,78,1499,1716,23,1068719129,13,203,'',0),(4422,238,1716,0,79,1503,1713,23,1068719129,13,203,'',0),(4423,238,1713,0,80,1716,1714,23,1068719129,13,203,'',0),(4424,238,1714,0,81,1713,1715,23,1068719129,13,203,'',0),(4425,238,1715,0,82,1714,1499,23,1068719129,13,203,'',0),(4426,238,1499,0,83,1715,1503,23,1068719129,13,203,'',0),(4427,238,1503,0,84,1499,1526,23,1068719129,13,203,'',0),(4428,238,1526,0,85,1503,1717,23,1068719129,13,203,'',0),(4429,238,1717,0,86,1526,0,23,1068719129,13,213,'',0),(4430,239,1140,0,0,0,1514,26,1068719374,13,209,'',0),(4431,239,1514,0,1,1140,1529,26,1068719374,13,210,'',0),(4432,239,1529,0,2,1514,1718,26,1068719374,13,211,'',0),(4433,239,1718,0,3,1529,1688,26,1068719374,13,212,'',0),(4434,239,1688,0,4,1718,1719,26,1068719374,13,212,'',0),(4435,239,1719,0,5,1688,0,26,1068719374,13,212,'',0),(4889,240,1957,0,0,0,0,1,1068719643,1,4,'',0),(4443,241,1543,0,0,0,1558,25,1068720802,1,207,'',0),(4444,241,1558,0,1,1543,1725,25,1068720802,1,207,'',0),(4445,241,1725,0,2,1558,1535,25,1068720802,1,207,'',0),(4446,241,1535,0,3,1725,1726,25,1068720802,1,207,'',0),(4447,241,1726,0,4,1535,1537,25,1068720802,1,207,'',0),(4448,241,1537,0,5,1726,1727,25,1068720802,1,207,'',0),(4449,241,1727,0,6,1537,1728,25,1068720802,1,207,'',0),(4450,241,1728,0,7,1727,0,25,1068720802,1,207,'',0),(4451,242,1729,0,0,0,1514,26,1068720915,13,209,'',0),(4452,242,1514,0,1,1729,1730,26,1068720915,13,210,'',0),(4453,242,1730,0,2,1514,1731,26,1068720915,13,211,'',0),(4454,242,1731,0,3,1730,1732,26,1068720915,13,212,'',0),(4455,242,1732,0,4,1731,1732,26,1068720915,13,212,'',0),(4456,242,1732,0,5,1732,1732,26,1068720915,13,212,'',0),(4457,242,1732,0,6,1732,1733,26,1068720915,13,212,'',0),(4458,242,1733,0,7,1732,1734,26,1068720915,13,212,'',0),(4459,242,1734,0,8,1733,1735,26,1068720915,13,212,'',0),(4460,242,1735,0,9,1734,1735,26,1068720915,13,212,'',0),(4461,242,1735,0,10,1735,1735,26,1068720915,13,212,'',0),(4462,242,1735,0,11,1735,1736,26,1068720915,13,212,'',0),(4463,242,1736,0,12,1735,1737,26,1068720915,13,212,'',0),(4464,242,1737,0,13,1736,0,26,1068720915,13,212,'',0),(4465,243,1738,0,0,0,1094,1,1068727871,13,4,'',0),(4466,243,1094,0,1,1738,1739,1,1068727871,13,119,'',0),(4467,243,1739,0,2,1094,1740,1,1068727871,13,119,'',0),(4468,243,1740,0,3,1739,1741,1,1068727871,13,119,'',0),(4469,243,1741,0,4,1740,1742,1,1068727871,13,119,'',0),(4470,243,1742,0,5,1741,1743,1,1068727871,13,119,'',0),(4471,243,1743,0,6,1742,33,1,1068727871,13,119,'',0),(4472,243,33,0,7,1743,1572,1,1068727871,13,119,'',0),(4473,243,1572,0,8,33,1609,1,1068727871,13,119,'',0),(4474,243,1609,0,9,1572,1744,1,1068727871,13,119,'',0),(4475,243,1744,0,10,1609,1745,1,1068727871,13,119,'',0),(4476,243,1745,0,11,1744,33,1,1068727871,13,119,'',0),(4477,243,33,0,12,1745,1746,1,1068727871,13,119,'',0),(4478,243,1746,0,13,33,1747,1,1068727871,13,119,'',0),(4479,243,1747,0,14,1746,1655,1,1068727871,13,119,'',0),(4480,243,1655,0,15,1747,1562,1,1068727871,13,119,'',0),(4481,243,1562,0,16,1655,1748,1,1068727871,13,119,'',0),(4482,243,1748,0,17,1562,1749,1,1068727871,13,119,'',0),(4483,243,1749,0,18,1748,1655,1,1068727871,13,119,'',0),(4484,243,1655,0,19,1749,1750,1,1068727871,13,119,'',0),(4485,243,1750,0,20,1655,1751,1,1068727871,13,119,'',0),(4486,243,1751,0,21,1750,1692,1,1068727871,13,119,'',0),(4487,243,1692,0,22,1751,0,1,1068727871,13,119,'',0),(4488,244,89,0,0,0,1752,23,1068727918,13,202,'',0),(4489,244,1752,0,1,89,1562,23,1068727918,13,202,'',0),(4490,244,1562,0,2,1752,1536,23,1068727918,13,202,'',0),(4491,244,1536,0,3,1562,1753,23,1068727918,13,202,'',0),(4492,244,1753,0,4,1536,1537,23,1068727918,13,203,'',0),(4493,244,1537,0,5,1753,1535,23,1068727918,13,203,'',0),(4494,244,1535,0,6,1537,1754,23,1068727918,13,203,'',0),(4495,244,1754,0,7,1535,1755,23,1068727918,13,203,'',0),(4496,244,1755,0,8,1754,1756,23,1068727918,13,203,'',0),(4497,244,1756,0,9,1755,1587,23,1068727918,13,203,'',0),(4498,244,1587,0,10,1756,1492,23,1068727918,13,203,'',0),(4499,244,1492,0,11,1587,1757,23,1068727918,13,203,'',0),(4500,244,1757,0,12,1492,1537,23,1068727918,13,203,'',0),(4501,244,1537,0,13,1757,1728,23,1068727918,13,203,'',0),(4502,244,1728,0,14,1537,1578,23,1068727918,13,203,'',0),(4503,244,1578,0,15,1728,1758,23,1068727918,13,203,'',0),(4504,244,1758,0,16,1578,1759,23,1068727918,13,203,'',0),(4505,244,1759,0,17,1758,1490,23,1068727918,13,203,'',0),(4506,244,1490,0,18,1759,1760,23,1068727918,13,203,'',0),(4507,244,1760,0,19,1490,1761,23,1068727918,13,203,'',0),(4508,244,1761,0,20,1760,89,23,1068727918,13,203,'',0),(4509,244,89,0,21,1761,1762,23,1068727918,13,203,'',0),(4510,244,1762,0,22,89,1763,23,1068727918,13,203,'',0),(4511,244,1763,0,23,1762,1764,23,1068727918,13,203,'',0),(4512,244,1764,0,24,1763,1765,23,1068727918,13,203,'',0),(4513,244,1765,0,25,1764,934,23,1068727918,13,203,'',0),(4514,244,934,0,26,1765,1655,23,1068727918,13,203,'',0),(4515,244,1655,0,27,934,1766,23,1068727918,13,203,'',0),(4516,244,1766,0,28,1655,1490,23,1068727918,13,203,'',0),(4517,244,1490,0,29,1766,1767,23,1068727918,13,203,'',0),(4518,244,1767,0,30,1490,1756,23,1068727918,13,203,'',0),(4519,244,1756,0,31,1767,1768,23,1068727918,13,203,'',0),(4520,244,1768,0,32,1756,1655,23,1068727918,13,203,'',0),(4521,244,1655,0,33,1768,1769,23,1068727918,13,203,'',0),(4522,244,1769,0,34,1655,1711,23,1068727918,13,203,'',0),(4523,244,1711,0,35,1769,1676,23,1068727918,13,203,'',0),(4524,244,1676,0,36,1711,1725,23,1068727918,13,203,'',0),(4525,244,1725,0,37,1676,1548,23,1068727918,13,203,'',0),(4526,244,1548,0,38,1725,1770,23,1068727918,13,203,'',0),(4527,244,1770,0,39,1548,1771,23,1068727918,13,203,'',0),(4528,244,1771,0,40,1770,1772,23,1068727918,13,203,'',0),(4529,244,1772,0,41,1771,1537,23,1068727918,13,203,'',0),(4530,244,1537,0,42,1772,1773,23,1068727918,13,203,'',0),(4531,244,1773,0,43,1537,1774,23,1068727918,13,203,'',0),(4532,244,1774,0,44,1773,1775,23,1068727918,13,203,'',0),(4533,244,1775,0,45,1774,1776,23,1068727918,13,203,'',0),(4534,244,1776,0,46,1775,1777,23,1068727918,13,203,'',0),(4535,244,1777,0,47,1776,1778,23,1068727918,13,203,'',0),(4536,244,1778,0,48,1777,1749,23,1068727918,13,203,'',0),(4537,244,1749,0,49,1778,1779,23,1068727918,13,203,'',0),(4538,244,1779,0,50,1749,33,23,1068727918,13,203,'',0),(4539,244,33,0,51,1779,89,23,1068727918,13,203,'',0),(4540,244,89,0,52,33,1748,23,1068727918,13,203,'',0),(4541,244,1748,0,53,89,1780,23,1068727918,13,203,'',0),(4542,244,1780,0,54,1748,1781,23,1068727918,13,203,'',0),(4543,244,1781,0,55,1780,89,23,1068727918,13,203,'',0),(4544,244,89,0,56,1781,1782,23,1068727918,13,203,'',0),(4545,244,1782,0,57,89,1783,23,1068727918,13,203,'',0),(4546,244,1783,0,58,1782,1537,23,1068727918,13,203,'',0),(4547,244,1537,0,59,1783,1784,23,1068727918,13,203,'',0),(4548,244,1784,0,60,1537,1785,23,1068727918,13,203,'',0),(4549,244,1785,0,61,1784,33,23,1068727918,13,203,'',0),(4550,244,33,0,62,1785,89,23,1068727918,13,203,'',0),(4551,244,89,0,63,33,1786,23,1068727918,13,203,'',0),(4552,244,1786,0,64,89,1787,23,1068727918,13,203,'',0),(4553,244,1787,0,65,1786,1537,23,1068727918,13,203,'',0),(4554,244,1537,0,66,1787,1788,23,1068727918,13,203,'',0),(4555,244,1788,0,67,1537,1789,23,1068727918,13,203,'',0),(4556,244,1789,0,68,1788,1790,23,1068727918,13,203,'',0),(4557,244,1790,0,69,1789,1593,23,1068727918,13,203,'',0),(4558,244,1593,0,70,1790,1791,23,1068727918,13,203,'',0),(4559,244,1791,0,71,1593,1792,23,1068727918,13,203,'',0),(4560,244,1792,0,72,1791,1793,23,1068727918,13,203,'',0),(4561,244,1793,0,73,1792,1794,23,1068727918,13,203,'',0),(4562,244,1794,0,74,1793,1795,23,1068727918,13,203,'',0),(4563,244,1795,0,75,1794,1796,23,1068727918,13,203,'',0),(4564,244,1796,0,76,1795,1797,23,1068727918,13,203,'',0),(4565,244,1797,0,77,1796,1798,23,1068727918,13,203,'',0),(4566,244,1798,0,78,1797,1799,23,1068727918,13,203,'',0),(4567,244,1799,0,79,1798,1800,23,1068727918,13,203,'',0),(4568,244,1800,0,80,1799,1801,23,1068727918,13,203,'',0),(4569,244,1801,0,81,1800,1802,23,1068727918,13,203,'',0),(4570,244,1802,0,82,1801,1535,23,1068727918,13,203,'',0),(4571,244,1535,0,83,1802,1803,23,1068727918,13,203,'',0),(4572,244,1803,0,84,1535,1804,23,1068727918,13,203,'',0),(4573,244,1804,0,85,1803,1805,23,1068727918,13,203,'',0),(4574,244,1805,0,86,1804,1806,23,1068727918,13,203,'',0),(4575,244,1806,0,87,1805,33,23,1068727918,13,203,'',0),(4576,244,33,0,88,1806,1807,23,1068727918,13,203,'',0),(4577,244,1807,0,89,33,1808,23,1068727918,13,203,'',0),(4578,244,1808,0,90,1807,1535,23,1068727918,13,203,'',0),(4579,244,1535,0,91,1808,1740,23,1068727918,13,203,'',0),(4580,244,1740,0,92,1535,1562,23,1068727918,13,203,'',0),(4581,244,1562,0,93,1740,1809,23,1068727918,13,203,'',0),(4582,244,1809,0,94,1562,1810,23,1068727918,13,203,'',0),(4583,244,1810,0,95,1809,1811,23,1068727918,13,203,'',0),(4584,244,1811,0,96,1810,1578,23,1068727918,13,203,'',0),(4585,244,1578,0,97,1811,1812,23,1068727918,13,203,'',0),(4586,244,1812,0,98,1578,1381,23,1068727918,13,203,'',0),(4587,244,1381,0,99,1812,1804,23,1068727918,13,203,'',0),(4588,244,1804,0,100,1381,1805,23,1068727918,13,203,'',0),(4589,244,1805,0,101,1804,1813,23,1068727918,13,203,'',0),(4590,244,1813,0,102,1805,1814,23,1068727918,13,203,'',0),(4591,244,1814,0,103,1813,1537,23,1068727918,13,203,'',0),(4592,244,1537,0,104,1814,1815,23,1068727918,13,203,'',0),(4593,244,1815,0,105,1537,33,23,1068727918,13,203,'',0),(4594,244,33,0,106,1815,1535,23,1068727918,13,203,'',0),(4595,244,1535,0,107,33,1816,23,1068727918,13,203,'',0),(4596,244,1816,0,108,1535,1817,23,1068727918,13,203,'',0),(4597,244,1817,0,109,1816,1779,23,1068727918,13,203,'',0),(4598,244,1779,0,110,1817,1537,23,1068727918,13,203,'',0),(4599,244,1537,0,111,1779,1805,23,1068727918,13,203,'',0),(4600,244,1805,0,112,1537,1818,23,1068727918,13,203,'',0),(4601,244,1818,0,113,1805,1819,23,1068727918,13,203,'',0),(4602,244,1819,0,114,1818,33,23,1068727918,13,203,'',0),(4603,244,33,0,115,1819,1820,23,1068727918,13,203,'',0),(4604,244,1820,0,116,33,1490,23,1068727918,13,203,'',0),(4605,244,1490,0,117,1820,1668,23,1068727918,13,203,'',0),(4606,244,1668,0,118,1490,1761,23,1068727918,13,203,'',0),(4607,244,1761,0,119,1668,1821,23,1068727918,13,203,'',0),(4608,244,1821,0,120,1761,1822,23,1068727918,13,203,'',0),(4609,244,1822,0,121,1821,1823,23,1068727918,13,203,'',0),(4610,244,1823,0,122,1822,1824,23,1068727918,13,203,'',0),(4611,244,1824,0,123,1823,1825,23,1068727918,13,203,'',0),(4612,244,1825,0,124,1824,1578,23,1068727918,13,203,'',0),(4613,244,1578,0,125,1825,1711,23,1068727918,13,203,'',0),(4614,244,1711,0,126,1578,1740,23,1068727918,13,203,'',0),(4615,244,1740,0,127,1711,1826,23,1068727918,13,203,'',0),(4616,244,1826,0,128,1740,1827,23,1068727918,13,203,'',0),(4617,244,1827,0,129,1826,1828,23,1068727918,13,203,'',0),(4618,244,1828,0,130,1827,1829,23,1068727918,13,203,'',0),(4619,244,1829,0,131,1828,1492,23,1068727918,13,203,'',0),(4620,244,1492,0,132,1829,1830,23,1068727918,13,203,'',0),(4621,244,1830,0,133,1492,1831,23,1068727918,13,203,'',0),(4622,244,1831,0,134,1830,1537,23,1068727918,13,203,'',0),(4623,244,1537,0,135,1831,1711,23,1068727918,13,203,'',0),(4624,244,1711,0,136,1537,1676,23,1068727918,13,203,'',0),(4625,244,1676,0,137,1711,1764,23,1068727918,13,203,'',0),(4626,244,1764,0,138,1676,1535,23,1068727918,13,203,'',0),(4627,244,1535,0,139,1764,1832,23,1068727918,13,203,'',0),(4628,244,1832,0,140,1535,1833,23,1068727918,13,203,'',0),(4629,244,1833,0,141,1832,1834,23,1068727918,13,203,'',0),(4630,244,1834,0,142,1833,1835,23,1068727918,13,203,'',0),(4631,244,1835,0,143,1834,1804,23,1068727918,13,203,'',0),(4632,244,1804,0,144,1835,1788,23,1068727918,13,203,'',0),(4633,244,1788,0,145,1804,1562,23,1068727918,13,203,'',0),(4634,244,1562,0,146,1788,1836,23,1068727918,13,203,'',0),(4635,244,1836,0,147,1562,1837,23,1068727918,13,203,'',0),(4636,244,1837,0,148,1836,1838,23,1068727918,13,203,'',0),(4637,244,1838,0,149,1837,1662,23,1068727918,13,203,'',0),(4638,244,1662,0,150,1838,1839,23,1068727918,13,203,'',0),(4639,244,1839,0,151,1662,1840,23,1068727918,13,203,'',0),(4640,244,1840,0,152,1839,89,23,1068727918,13,203,'',0),(4641,244,89,0,153,1840,1841,23,1068727918,13,203,'',0),(4642,244,1841,0,154,89,1537,23,1068727918,13,203,'',0),(4643,244,1537,0,155,1841,1535,23,1068727918,13,203,'',0),(4644,244,1535,0,156,1537,1842,23,1068727918,13,203,'',0),(4645,244,1842,0,157,1535,1543,23,1068727918,13,203,'',0),(4646,244,1543,0,158,1842,1843,23,1068727918,13,203,'',0),(4647,244,1843,0,159,1543,1844,23,1068727918,13,203,'',0),(4648,244,1844,0,160,1843,1671,23,1068727918,13,203,'',0),(4649,244,1671,0,161,1844,1845,23,1068727918,13,203,'',0),(4650,244,1845,0,162,1671,1578,23,1068727918,13,203,'',0),(4651,244,1578,0,163,1845,1657,23,1068727918,13,203,'',0),(4652,244,1657,0,164,1578,89,23,1068727918,13,203,'',0),(4653,244,89,0,165,1657,1762,23,1068727918,13,203,'',0),(4654,244,1762,0,166,89,1846,23,1068727918,13,203,'',0),(4655,244,1846,0,167,1762,1832,23,1068727918,13,203,'',0),(4656,244,1832,0,168,1846,1535,23,1068727918,13,203,'',0),(4657,244,1535,0,169,1832,1559,23,1068727918,13,203,'',0),(4658,244,1559,0,170,1535,1847,23,1068727918,13,203,'',0),(4659,244,1847,0,171,1559,1848,23,1068727918,13,203,'',0),(4660,244,1848,0,172,1847,1849,23,1068727918,13,203,'',0),(4661,244,1849,0,173,1848,1850,23,1068727918,13,203,'',0),(4662,244,1850,0,174,1849,1534,23,1068727918,13,203,'',0),(4663,244,1534,0,175,1850,1535,23,1068727918,13,203,'',0),(4664,244,1535,0,176,1534,1676,23,1068727918,13,203,'',0),(4665,244,1676,0,177,1535,1535,23,1068727918,13,203,'',0),(4666,244,1535,0,178,1676,1753,23,1068727918,13,203,'',0),(4667,244,1753,0,179,1535,1851,23,1068727918,13,203,'',0),(4668,244,1851,0,180,1753,1388,23,1068727918,13,203,'',0),(4669,244,1388,0,181,1851,33,23,1068727918,13,203,'',0),(4670,244,33,0,182,1388,1619,23,1068727918,13,203,'',0),(4671,244,1619,0,183,33,1537,23,1068727918,13,203,'',0),(4672,244,1537,0,184,1619,1852,23,1068727918,13,203,'',0),(4673,244,1852,0,185,1537,1853,23,1068727918,13,203,'',0),(4674,244,1853,0,186,1852,1560,23,1068727918,13,203,'',0),(4675,244,1560,0,187,1853,1854,23,1068727918,13,203,'',0),(4676,244,1854,0,188,1560,1855,23,1068727918,13,203,'',0),(4677,244,1855,0,189,1854,1856,23,1068727918,13,203,'',0),(4678,244,1856,0,190,1855,1543,23,1068727918,13,203,'',0),(4679,244,1543,0,191,1856,1490,23,1068727918,13,203,'',0),(4680,244,1490,0,192,1543,1857,23,1068727918,13,203,'',0),(4681,244,1857,0,193,1490,1756,23,1068727918,13,203,'',0),(4682,244,1756,0,194,1857,1858,23,1068727918,13,203,'',0),(4683,244,1858,0,195,1756,1535,23,1068727918,13,203,'',0),(4684,244,1535,0,196,1858,1837,23,1068727918,13,203,'',0),(4685,244,1837,0,197,1535,1859,23,1068727918,13,203,'',0),(4686,244,1859,0,198,1837,1860,23,1068727918,13,203,'',0),(4687,244,1860,0,199,1859,1535,23,1068727918,13,203,'',0),(4688,244,1535,0,200,1860,1740,23,1068727918,13,203,'',0),(4689,244,1740,0,201,1535,1725,23,1068727918,13,203,'',0),(4690,244,1725,0,202,1740,1861,23,1068727918,13,203,'',0),(4691,244,1861,0,203,1725,1862,23,1068727918,13,203,'',0),(4692,244,1862,0,204,1861,1863,23,1068727918,13,203,'',0),(4693,244,1863,0,205,1862,1560,23,1068727918,13,203,'',0),(4694,244,1560,0,206,1863,89,23,1068727918,13,203,'',0),(4695,244,89,0,207,1560,1864,23,1068727918,13,203,'',0),(4696,244,1864,0,208,89,1535,23,1068727918,13,203,'',0),(4697,244,1535,0,209,1864,1753,23,1068727918,13,203,'',0),(4698,244,1753,0,210,1535,1865,23,1068727918,13,203,'',0),(4699,244,1865,0,211,1753,1866,23,1068727918,13,203,'',0),(4700,244,1866,0,212,1865,33,23,1068727918,13,203,'',0),(4701,244,33,0,213,1866,1867,23,1068727918,13,203,'',0),(4702,244,1867,0,214,33,1868,23,1068727918,13,203,'',0),(4703,244,1868,0,215,1867,1869,23,1068727918,13,203,'',0),(4704,244,1869,0,216,1868,1870,23,1068727918,13,203,'',0),(4705,244,1870,0,217,1869,1786,23,1068727918,13,203,'',0),(4706,244,1786,0,218,1870,1871,23,1068727918,13,203,'',0),(4707,244,1871,0,219,1786,1872,23,1068727918,13,203,'',0),(4708,244,1872,0,220,1871,1873,23,1068727918,13,203,'',0),(4709,244,1873,0,221,1872,1874,23,1068727918,13,203,'',0),(4710,244,1874,0,222,1873,1875,23,1068727918,13,203,'',0),(4711,244,1875,0,223,1874,1747,23,1068727918,13,203,'',0),(4712,244,1747,0,224,1875,1876,23,1068727918,13,203,'',0),(4713,244,1876,0,225,1747,1657,23,1068727918,13,203,'',0),(4714,244,1657,0,226,1876,1598,23,1068727918,13,203,'',0),(4715,244,1598,0,227,1657,1877,23,1068727918,13,203,'',0),(4716,244,1877,0,228,1598,1878,23,1068727918,13,203,'',0),(4717,244,1878,0,229,1877,1691,23,1068727918,13,203,'',0),(4718,244,1691,0,230,1878,89,23,1068727918,13,203,'',0),(4719,244,89,0,231,1691,1762,23,1068727918,13,203,'',0),(4720,244,1762,0,232,89,1879,23,1068727918,13,203,'',0),(4721,244,1879,0,233,1762,1880,23,1068727918,13,203,'',0),(4722,244,1880,0,234,1879,1881,23,1068727918,13,203,'',0),(4723,244,1881,0,235,1880,1490,23,1068727918,13,203,'',0),(4724,244,1490,0,236,1881,1767,23,1068727918,13,203,'',0),(4725,244,1767,0,237,1490,1756,23,1068727918,13,203,'',0),(4726,244,1756,0,238,1767,1874,23,1068727918,13,203,'',0),(4727,244,1874,0,239,1756,1537,23,1068727918,13,203,'',0),(4728,244,1537,0,240,1874,1882,23,1068727918,13,203,'',0),(4729,244,1882,0,241,1537,1864,23,1068727918,13,203,'',0),(4730,244,1864,0,242,1882,1883,23,1068727918,13,203,'',0),(4731,244,1883,0,243,1864,1578,23,1068727918,13,203,'',0),(4732,244,1578,0,244,1883,1668,23,1068727918,13,203,'',0),(4733,244,1668,0,245,1578,1670,23,1068727918,13,203,'',0),(4734,244,1670,0,246,1668,1884,23,1068727918,13,203,'',0),(4735,244,1884,0,247,1670,1885,23,1068727918,13,203,'',0),(4736,244,1885,0,248,1884,1883,23,1068727918,13,203,'',0),(4737,244,1883,0,249,1885,1886,23,1068727918,13,203,'',0),(4738,244,1886,0,250,1883,1578,23,1068727918,13,203,'',0),(4739,244,1578,0,251,1886,1887,23,1068727918,13,203,'',0),(4740,244,1887,0,252,1578,1888,23,1068727918,13,203,'',0),(4741,244,1888,0,253,1887,1889,23,1068727918,13,203,'',0),(4742,244,1889,0,254,1888,33,23,1068727918,13,203,'',0),(4743,244,33,0,255,1889,1758,23,1068727918,13,203,'',0),(4744,244,1758,0,256,33,1711,23,1068727918,13,203,'',0),(4745,244,1711,0,257,1758,1676,23,1068727918,13,203,'',0),(4746,244,1676,0,258,1711,1593,23,1068727918,13,203,'',0),(4747,244,1593,0,259,1676,1890,23,1068727918,13,203,'',0),(4748,244,1890,0,260,1593,1593,23,1068727918,13,203,'',0),(4749,244,1593,0,261,1890,1891,23,1068727918,13,203,'',0),(4750,244,1891,0,262,1593,1747,23,1068727918,13,203,'',0),(4751,244,1747,0,263,1891,1670,23,1068727918,13,203,'',0),(4752,244,1670,0,264,1747,1848,23,1068727918,13,203,'',0),(4753,244,1848,0,265,1670,1883,23,1068727918,13,203,'',0),(4754,244,1883,0,266,1848,1892,23,1068727918,13,203,'',0),(4755,244,1892,0,267,1883,1619,23,1068727918,13,203,'',0),(4756,244,1619,0,268,1892,1893,23,1068727918,13,203,'',0),(4757,244,1893,0,269,1619,1894,23,1068727918,13,203,'',0),(4758,244,1894,0,270,1893,1670,23,1068727918,13,203,'',0),(4759,244,1670,0,271,1894,1886,23,1068727918,13,203,'',0),(4760,244,1886,0,272,1670,1619,23,1068727918,13,203,'',0),(4761,244,1619,0,273,1886,1537,23,1068727918,13,203,'',0),(4762,244,1537,0,274,1619,1535,23,1068727918,13,203,'',0),(4763,244,1535,0,275,1537,1889,23,1068727918,13,203,'',0),(4764,244,1889,0,276,1535,1670,23,1068727918,13,203,'',0),(4765,244,1670,0,277,1889,1895,23,1068727918,13,203,'',0),(4766,244,1895,0,278,1670,1411,23,1068727918,13,203,'',0),(4767,244,1411,0,279,1895,1896,23,1068727918,13,203,'',0),(4768,244,1896,0,280,1411,1897,23,1068727918,13,203,'',0),(4769,244,1897,0,281,1896,1898,23,1068727918,13,203,'',0),(4770,244,1898,0,282,1897,1578,23,1068727918,13,203,'',0),(4771,244,1578,0,283,1898,1899,23,1068727918,13,203,'',0),(4772,244,1899,0,284,1578,1723,23,1068727918,13,203,'',0),(4773,244,1723,0,285,1899,89,23,1068727918,13,203,'',0),(4774,244,89,0,286,1723,1752,23,1068727918,13,203,'',0),(4775,244,1752,0,287,89,33,23,1068727918,13,203,'',0),(4776,244,33,0,288,1752,1900,23,1068727918,13,203,'',0),(4777,244,1900,0,289,33,1670,23,1068727918,13,203,'',0),(4778,244,1670,0,290,1900,1895,23,1068727918,13,203,'',0),(4779,244,1895,0,291,1670,1657,23,1068727918,13,203,'',0),(4780,244,1657,0,292,1895,1604,23,1068727918,13,203,'',0),(4781,244,1604,0,293,1657,1593,23,1068727918,13,203,'',0),(4782,244,1593,0,294,1604,1782,23,1068727918,13,203,'',0),(4783,244,1782,0,295,1593,89,23,1068727918,13,203,'',0),(4784,244,89,0,296,1782,1611,23,1068727918,13,203,'',0),(4785,244,1611,0,297,89,1593,23,1068727918,13,203,'',0),(4786,244,1593,0,298,1611,1490,23,1068727918,13,203,'',0),(4787,244,1490,0,299,1593,1901,23,1068727918,13,203,'',0),(4788,244,1901,0,300,1490,1706,23,1068727918,13,203,'',0),(4789,244,1706,0,301,1901,0,23,1068727918,13,213,'',1),(4807,245,1694,0,0,0,1693,26,1068730476,13,209,'',0),(4808,245,1693,0,1,1694,1693,26,1068730476,13,210,'',0),(4809,245,1693,0,2,1693,1912,26,1068730476,13,211,'',0),(4810,245,1912,0,3,1693,0,26,1068730476,13,212,'',0),(4812,246,1914,0,0,0,1514,26,1068737197,13,209,'',0),(4813,246,1514,0,1,1914,1915,26,1068737197,13,210,'',0),(4814,246,1915,0,2,1514,1916,26,1068737197,13,211,'',0),(4815,246,1916,0,3,1915,1917,26,1068737197,13,212,'',0),(4816,246,1917,0,4,1916,1687,26,1068737197,13,212,'',0),(4817,246,1687,0,5,1917,1509,26,1068737197,13,212,'',0),(4818,246,1509,0,6,1687,0,26,1068737197,13,212,'',0),(4820,247,1490,0,0,0,1919,23,1068796296,13,202,'',0),(4821,247,1919,0,1,1490,1489,23,1068796296,13,202,'',0),(4822,247,1489,0,2,1919,1920,23,1068796296,13,202,'',0),(4823,247,1920,0,3,1489,1921,23,1068796296,13,203,'',0),(4824,247,1921,0,4,1920,1509,23,1068796296,13,203,'',0),(4825,247,1509,0,5,1921,1509,23,1068796296,13,203,'',0),(4826,247,1509,0,6,1509,1509,23,1068796296,13,203,'',0),(4827,247,1509,0,7,1509,1688,23,1068796296,13,203,'',0),(4828,247,1688,0,8,1509,1719,23,1068796296,13,203,'',0),(4829,247,1719,0,9,1688,1922,23,1068796296,13,203,'',0),(4830,247,1922,0,10,1719,1923,23,1068796296,13,203,'',0),(4831,247,1923,0,11,1922,1687,23,1068796296,13,203,'',0),(4832,247,1687,0,12,1923,1509,23,1068796296,13,203,'',0),(4833,247,1509,0,13,1687,1719,23,1068796296,13,203,'',0),(4834,247,1719,0,14,1509,1924,23,1068796296,13,203,'',0),(4835,247,1924,0,15,1719,1687,23,1068796296,13,203,'',0),(4836,247,1687,0,16,1924,1688,23,1068796296,13,203,'',0),(4837,247,1688,0,17,1687,1706,23,1068796296,13,203,'',0),(4838,247,1706,0,18,1688,0,23,1068796296,13,213,'',1),(4879,228,1535,0,8,33,1723,1,1068718629,12,119,'',0),(4878,228,33,0,7,1949,1535,1,1068718629,12,119,'',0),(4877,228,1949,0,6,1678,33,1,1068718629,12,119,'',0),(4876,228,1678,0,5,1948,1949,1,1068718629,12,119,'',0),(4875,228,1948,0,4,1947,1678,1,1068718629,12,119,'',0),(4874,228,1947,0,3,1946,1948,1,1068718629,12,119,'',0),(4873,228,1946,0,2,1945,1947,1,1068718629,12,119,'',0),(4872,228,1945,0,1,11,1946,1,1068718629,12,119,'',0),(4871,228,11,0,0,0,1945,1,1068718629,12,4,'',0),(4885,49,33,0,4,1953,1954,1,1066398020,13,119,'',0),(4884,49,1953,0,3,1952,33,1,1066398020,13,119,'',0),(4883,49,1952,0,2,1951,1953,1,1066398020,13,119,'',0),(4882,49,1951,0,1,1950,1952,1,1066398020,13,119,'',0),(4881,49,1950,0,0,0,1951,1,1066398020,13,4,'',0),(4931,248,1994,0,0,0,1994,26,1069409151,13,209,'',0),(4933,248,1994,0,2,1994,1995,26,1069409151,13,211,'',0),(4934,248,1995,0,3,1994,0,26,1069409151,13,212,'',0),(4960,56,2018,0,0,0,0,15,1066643397,11,161,'',0);
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
INSERT INTO ezsearch_return_count VALUES (1,1,1066398569,1),(2,2,1066909621,1),(3,3,1066910511,1),(4,4,1066912239,1),(5,5,1066982534,1),(6,6,1066991890,4),(7,6,1066992837,4),(8,6,1066992963,4),(9,6,1066992972,0),(10,6,1066993049,0),(11,6,1066993056,4),(12,6,1066993091,4),(13,6,1066993127,4),(14,6,1066993135,4),(15,6,1066993895,4),(16,6,1066993946,4),(17,6,1066993995,4),(18,6,1066994001,4),(19,6,1066994050,4),(20,6,1066994057,4),(21,6,1066994067,4),(22,7,1066996820,0),(23,5,1066997190,1),(24,5,1066997194,1),(25,8,1066998830,1),(26,8,1066998836,1),(27,8,1066998870,1),(28,9,1066998915,1),(29,10,1067003146,0),(30,11,1067003155,2),(31,6,1067005771,4),(32,6,1067005777,4),(33,6,1067005801,4),(34,12,1067006770,1),(35,12,1067006774,1),(36,12,1067006777,1),(37,12,1067006787,1),(38,12,1067006803,1),(39,12,1067006996,1),(40,12,1067008585,1),(41,12,1067008597,1),(42,12,1067008602,0),(43,12,1067008608,1),(44,12,1067008613,0),(45,12,1067008620,0),(46,12,1067008625,0),(47,12,1067008629,1),(48,12,1067008655,1),(49,12,1067008659,0),(50,12,1067008663,0),(51,12,1067008667,0),(52,12,1067008711,0),(53,12,1067008717,0),(54,12,1067008720,1),(55,12,1067008725,0),(56,12,1067008920,1),(57,12,1067008925,1),(58,12,1067008929,0),(59,12,1067008934,1),(60,12,1067009005,1),(61,12,1067009023,1),(62,12,1067009042,1),(63,12,1067009051,0),(64,13,1067009056,1),(65,14,1067009067,0),(66,14,1067009073,0),(67,13,1067009594,1),(68,13,1067009816,1),(69,13,1067009953,1),(70,13,1067010181,1),(71,13,1067010352,1),(72,13,1067010359,1),(73,13,1067010370,1),(74,13,1067010509,1),(75,6,1067241668,5),(76,6,1067241727,5),(77,6,1067241742,5),(78,6,1067241760,5),(79,6,1067241810,5),(80,6,1067241892,5),(81,6,1067241928,5),(82,6,1067241953,5),(83,14,1067252984,0),(84,14,1067252987,0),(85,14,1067253026,0),(86,14,1067253160,0),(87,14,1067253218,0),(88,14,1067253285,0),(89,5,1067520640,1),(90,5,1067520646,1),(91,5,1067520658,1),(92,5,1067520704,0),(93,5,1067520753,0),(94,5,1067520761,1),(95,5,1067520769,1),(96,5,1067521324,1),(97,5,1067521402,1),(98,5,1067521453,1),(99,5,1067521532,1),(100,5,1067521615,1),(101,5,1067521674,1),(102,5,1067521990,1),(103,5,1067522592,1),(104,5,1067522620,1),(105,5,1067522888,1),(106,5,1067522987,1),(107,5,1067523012,1),(108,5,1067523144,1),(109,5,1067523213,1),(110,5,1067523261,1),(111,5,1067523798,1),(112,5,1067523805,1),(113,5,1067523820,1),(114,5,1067523858,1),(115,5,1067524474,1),(116,5,1067524629,1),(117,5,1067524696,1),(118,15,1067526426,0),(119,15,1067526433,0),(120,15,1067526701,0),(121,15,1067527009,0),(122,5,1067527022,1),(123,5,1067527033,1),(124,5,1067527051,1),(125,5,1067527069,1),(126,5,1067527076,0),(127,5,1067527124,1),(128,5,1067527176,1),(129,16,1067527188,0),(130,16,1067527227,0),(131,16,1067527244,0),(132,16,1067527301,0),(133,5,1067527315,0),(134,5,1067527349,0),(135,5,1067527412,0),(136,5,1067527472,1),(137,5,1067527502,1),(138,5,1067527508,0),(139,17,1067527848,0),(140,5,1067527863,1),(141,5,1067527890,1),(142,5,1067527906,1),(143,5,1067527947,1),(144,5,1067527968,0),(145,5,1067527993,0),(146,5,1067528010,1),(147,5,1067528029,0),(148,5,1067528045,0),(149,5,1067528050,0),(150,5,1067528056,0),(151,5,1067528061,0),(152,5,1067528063,0),(153,18,1067528100,1),(154,18,1067528113,0),(155,18,1067528190,1),(156,18,1067528236,1),(157,18,1067528270,1),(158,18,1067528309,1),(159,5,1067528323,0),(160,18,1067528334,1),(161,18,1067528355,1),(162,5,1067528368,0),(163,5,1067528377,1),(164,19,1067528402,0),(165,19,1067528770,0),(166,19,1067528924,0),(167,19,1067528963,0),(168,19,1067529028,0),(169,19,1067529054,0),(170,19,1067529119,0),(171,19,1067529169,0),(172,19,1067529211,0),(173,19,1067529263,0),(174,20,1067943156,3),(175,4,1067943454,1),(176,4,1067943503,1),(177,4,1067943525,1),(178,21,1067943559,1),(179,21,1067945657,1),(180,21,1067945693,1),(181,21,1067945697,1),(182,21,1067945707,1),(183,22,1067945890,0),(184,20,1067945898,3),(185,23,1067946301,6),(186,24,1067946325,1),(187,24,1067946432,1),(188,25,1067946484,4),(189,26,1067946492,1),(190,27,1067946577,1),(191,25,1067946691,4),(192,4,1067946702,1),(193,4,1067947201,1),(194,4,1067947228,1),(195,4,1067948201,1),(196,5,1068028867,0),(197,12,1068028883,0),(198,28,1068028898,2),(199,5,1068040205,0),(200,29,1068048420,0),(201,29,1068048455,1),(202,30,1068048466,0),(203,29,1068048480,0),(204,30,1068048487,2),(205,29,1068048592,0),(206,30,1068048615,2),(207,30,1068048653,2),(208,30,1068048698,2),(209,30,1068048707,2),(210,30,1068048799,2),(211,30,1068048825,2),(212,30,1068048830,2),(213,30,1068048852,2),(214,30,1068048874,2),(215,30,1068048890,2),(216,30,1068048918,2),(217,30,1068048928,2),(218,31,1068048940,2),(219,31,1068048964,2),(220,20,1068049003,0),(221,20,1068049007,2),(222,25,1068049014,3),(223,25,1068049043,3),(224,25,1068049062,3),(225,25,1068049082,3),(226,32,1068112266,5),(227,30,1068468248,3),(228,33,1068714725,2),(229,34,1068719240,1),(230,34,1068719687,1),(231,34,1068719760,1),(232,34,1068719777,1),(233,35,1068719791,1),(234,35,1068723985,1),(235,34,1068727787,1),(236,36,1068728624,1),(237,36,1068728739,1),(238,36,1068728770,1),(239,36,1068728776,1),(240,36,1068728947,1),(241,36,1068728959,1),(242,36,1068728966,1),(243,36,1068728973,1),(244,36,1068728982,1),(245,36,1068729022,1),(246,36,1068729042,1),(247,35,1068731397,1),(248,35,1068734726,1),(249,35,1068734805,1),(250,35,1068734826,1),(251,37,1068734855,0),(252,35,1068734862,1),(253,35,1068735020,1),(254,38,1068735027,0),(255,35,1068735649,1),(256,35,1068735665,1),(257,35,1068735532,1),(258,36,1068735742,1),(259,39,1068735756,0),(260,35,1068735761,1),(261,35,1068738591,1),(262,35,1068796351,1),(263,40,1068796826,1),(264,41,1068811757,1),(265,36,1068817966,1),(266,35,1068817932,1),(267,42,1069425924,0);
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
INSERT INTO ezsearch_search_phrase VALUES (1,'documents'),(2,'wenyue'),(3,'xxx'),(4,'release'),(5,'test'),(6,'ez'),(7,'f1'),(8,'bjørn'),(9,'abb'),(10,'2-2'),(11,'3.2'),(12,'bård'),(13,'Vidar'),(14,'tewtet'),(15,'dcv'),(16,'gr'),(17,'tewt'),(18,'members'),(19,'regte'),(20,'news'),(21,'german'),(22,'info'),(23,'information'),(24,'folder'),(25,'about'),(26,'2'),(27,'systems'),(28,'the'),(29,'football'),(30,'foo'),(31,'my'),(32,'reply'),(33,'today'),(34,'no comments'),(35,'car'),(36,'pirate'),(37,'carre'),(38,'cards'),(39,'cat'),(40,'overslept'),(41,'sina'),(42,'calendar');
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
INSERT INTO ezsearch_word VALUES (1527,'båbåb',1),(6,'media',1),(7,'setup',3),(1906,'grouplist',1),(1905,'class',1),(1904,'classes',1),(11,'links',5),(25,'content',2),(34,'feel',2),(33,'and',8),(32,'look',3),(1494,'car',1),(1463,'platea',1),(1462,'habitasse',1),(1493,'new',3),(1461,'hac',1),(1519,'more',3),(934,'about',5),(1534,'into',2),(1492,'my',3),(1491,'got',1),(1521,'dsfgljk',1),(1520,'speacial',1),(1460,'fringilla',1),(89,'a',5),(1523,'sdlfgj',1),(1535,'the',7),(1536,'life',2),(1459,'nonummy',1),(1512,'g.sdfg',1),(1094,'music',2),(1552,'often',1),(1507,'d',1),(1458,'fermentum',1),(1457,'vestibulum',1),(1525,'gfdsfgljk',1),(1456,'erat',1),(1455,'vitae',1),(1454,'mi',1),(1453,'magna',1),(1465,'duis',1),(1452,'tempor',1),(1544,'connects',1),(1451,'lectus',1),(1501,'things',2),(1559,'way',2),(1558,'one',4),(1557,'than',2),(1556,'price',1),(1555,'paying',2),(1516,'ez.no',3),(1515,'http',4),(1506,'gfosdfg',1),(1514,'bf@ez.no',6),(1513,'g',1),(1518,'seen',1),(1464,'dictumst',1),(1340,'kghjohtkæ',1),(1339,'director',2),(1503,'sd',3),(1504,'oifgu',1),(1505,'sdoiguosdiu',1),(1911,'urltranslator',1),(1540,'electronic',1),(1502,'happened',1),(1539,'handhelds',1),(1541,'gadgets',1),(1517,'ve',1),(1543,'which',4),(1903,'cache',1),(1542,'software',3),(1537,'of',4),(1450,'rhoncus',1),(1449,'nunc',1),(1448,'lacus',1),(1447,'accumsan',1),(1446,'vehicula',1),(1445,'velit',1),(1444,'elementum',1),(1443,'tellus',1),(1442,'suscipit',1),(1441,'commodo',1),(1440,'sagittis',1),(1439,'enim',1),(1438,'vel',1),(1490,'i',7),(1489,'today',4),(1538,'computers',1),(1532,'personal',1),(1380,'blog',1),(1526,'gf',3),(1529,'sdfgsd',2),(1321,'sig',1),(1320,'oslo',1),(1319,'guru',1),(1318,'farstad',2),(1362,'developer',1),(1316,'norway',3),(1361,'skien',1),(1437,'felis',1),(1360,'uberguru',1),(1359,'user',1),(1522,'jsdklgj',1),(1533,'glimpse',1),(1531,'sdfgdsg',1),(1530,'fgsdgsd',1),(571,'doe',1),(570,'john',1),(572,'vid',1),(573,'la',1),(1554,'up',2),(1436,'ullamcorper',1),(1435,'pellentesque',1),(1434,'fusce',1),(1433,'tortor',1),(1432,'scelerisque',1),(1431,'pharetra',1),(1430,'aenean',1),(1429,'facilisis',1),(1428,'ut',1),(1427,'tristique',1),(1426,'eros',1),(1425,'turpis',1),(1424,'eu',1),(1423,'metus',1),(1422,'blandit',1),(1421,'ac',1),(1420,'neque',1),(1419,'dapibus',1),(1418,'volutpat',1),(1417,'iaculis',1),(1416,'id',1),(1415,'purus',1),(1414,'imperdiet',1),(1413,'phasellus',1),(1412,'libero',1),(1411,'at',3),(1410,'tincidunt',1),(1409,'molestie',1),(1408,'eget',1),(1407,'dignissim',1),(1406,'est',1),(1405,'proin',1),(1404,'odio',1),(1403,'morbi',1),(1402,'nulla',1),(1401,'et',1),(1400,'wisi',1),(1399,'diam',1),(1398,'gravida',1),(1397,'aliquam',1),(1396,'quam',1),(1395,'nisl',1),(1394,'eleifend',1),(1393,'sed',1),(1392,'mauris',1),(1391,'egestas',1),(1390,'maecenas',1),(1389,'massa',1),(1388,'in',4),(1387,'elit',1),(1386,'adipiscing',1),(1385,'consectetuer',1),(1384,'amet',1),(1383,'sit',1),(944,'ipsum',1),(943,'lorem',1),(1382,'dolor',1),(1381,'me',4),(1500,'special',1),(1499,'sdgf',3),(1498,'lsdgf',1),(1908,'56',1),(1907,'edit',1),(1910,'translator',1),(1909,'url',1),(1553,'end',1),(1528,'piranha.no',1),(1497,'sdlkfg',1),(1496,'sdfklgj',1),(1495,'sdgfklj',1),(1511,'iosdf',1),(1524,'skldg',1),(1338,'yu',1),(1337,'wenyue',1),(1551,'means',1),(1547,'m',1),(1510,'sdfhsdjkghsdigfu',1),(1509,'sdfg',7),(1508,'dfg',1),(1954,'brainstorms',1),(1550,'adopter',1),(1549,'early',1),(1548,'an',2),(1358,'administrator',1),(1546,'all',1),(1545,'them',1),(1140,'bård',4),(1466,'interdum',1),(1467,'ornare',1),(1468,'non',1),(1469,'sapien',1),(1470,'facilisi',1),(1471,'suspendisse',1),(1472,'nec',1),(1473,'congue',1),(1474,'sem',1),(1475,'viverra',1),(1476,'consequat',1),(1477,'donec',1),(1478,'nam',1),(1479,'bibendum',1),(1480,'dui',1),(1481,'porttitor',1),(1482,'integer',1),(1483,'cursus',1),(1484,'quis',1),(1485,'laoreet',1),(1560,'for',3),(1561,'posterity',1),(1562,'s',4),(1563,'sake',1),(1564,'seven',1),(1565,'years',1),(1566,'ago',1),(1567,'yesterday',1),(1568,'packed',1),(1569,'everything',1),(1570,'owned',1),(1571,'left',1),(1572,'many',2),(1573,'friends',1),(1574,'behind',1),(1575,'san',1),(1576,'diego',1),(1577,'moved',1),(1578,'to',3),(1579,'francisco',1),(1580,'where',1),(1581,'knew',1),(1582,'absolutely',1),(1583,'no',2),(1584,'start',1),(1585,'job',1),(1586,'hotwired',1),(1587,'on',3),(1588,'august',1),(1589,'12',1),(1590,'1996',1),(1591,'creative',1),(1592,'hired',1),(1593,'as',2),(1594,'junior',1),(1595,'designer',1),(1596,'since',1),(1597,'very',1),(1598,'little',2),(1599,'design',1),(1600,'web',1),(1601,'despite',1),(1602,'fact',1),(1603,'that',1),(1604,'had',2),(1605,'print',1),(1606,'experience',1),(1607,'almost',1),(1608,'every',1),(1609,'other',2),(1610,'there',1),(1611,'time',2),(1612,'three',1),(1613,'months',1),(1614,'after',1),(1615,'starting',1),(1616,'low',1),(1617,'was',1),(1618,'depressed',1),(1619,'out',2),(1620,'money',1),(1621,'missed',1),(1622,'convinced',1),(1623,'made',1),(1624,'wrong',1),(1625,'move',1),(1626,'gave',1),(1627,'notice',1),(1628,'told',1),(1629,'landlord',1),(1630,'moving',1),(1631,'arranged',1),(1632,'go',1),(1633,'back',1),(1634,'old',1),(1635,'agency',1),(1636,'two',1),(1637,'days',1),(1638,'before',1),(1639,'leave',1),(1640,'jonathan',1),(1641,'louie',1),(1642,'otherwise',1),(1643,'offering',1),(1644,'position',1),(1645,'first',1),(1646,'promotion',1),(1647,'come',1),(1648,'stayed',1),(1649,'lost',1),(1650,'girlfriend',1),(1651,'result',1),(1652,'fast',1),(1653,'forward',1),(1654,'present',1),(1655,'it',4),(1656,'will',1),(1657,'have',2),(1658,'been',1),(1659,'9',1),(1660,'wired',1),(1661,'own',1),(1662,'who',2),(1663,'know',2),(1664,'journey',1),(1665,'initial',1),(1666,'intimidation',1),(1667,'taking',1),(1668,'say',2),(1669,'\"',1),(1670,'you',3),(1671,'are',2),(1672,'now.\"',1),(1723,'like',3),(1722,'do',1),(1721,'color',1),(1676,'movie',2),(1677,'sites',1),(1678,'news',4),(1679,'site',2),(1680,'dfgl',1),(1681,'sdflg',1),(1682,'sdiofg',1),(1683,'usdoigfu',1),(1684,'osdigu',1),(1685,'iosdgf',1),(1686,'sdgfsd',1),(1687,'fg',3),(1688,'sdg',3),(1689,'vg',1),(1690,'norwegian',1),(1691,'from',2),(1692,'here',3),(1693,'kjh',2),(1694,'kjlh',2),(1695,'kljh',1),(1696,'sina',1),(1697,'famous',1),(1698,'chinese',1),(1699,'big',1),(1700,'discovery',1),(1701,'discovered',1),(1702,'internet',1),(1703,'20meters',1),(1704,'dsfg',1),(1705,'v',1),(1706,'1',3),(1707,'soft',1),(1708,'house',1),(1709,'download',1),(1710,'comments',1),(1711,'this',2),(1712,'dsgf',1),(1713,'iosdufg',1),(1714,'iosdufgo',1),(1715,'idsfg',1),(1716,'gfdsgf',1),(1717,'0',1),(1718,'sdfgsdgsd',1),(1719,'sdf',2),(1957,'polls',1),(1725,'is',2),(1726,'best',1),(1727,'matrix',1),(1728,'movies',2),(1729,'ghghj',1),(1730,'fghvmbnmbvnm',1),(1731,'fgn',1),(1732,'fdgh',1),(1733,'kløæ',1),(1734,'ølæ',1),(1735,'løæ',1),(1736,'hjlh',1),(1737,'hj',1),(1738,'entertainment',1),(1739,'books',1),(1740,'film',2),(1741,'television',1),(1742,'shopping',1),(1743,'travel',1),(1744,'fine',1),(1745,'escapes',1),(1746,'vices',1),(1747,'if',2),(1748,'downright',2),(1749,'fun',2),(1750,'probably',1),(1751,'belongs',1),(1752,'pirate',1),(1753,'pirates',1),(1754,'caribbean',1),(1755,'wasn',1),(1756,'t',1),(1757,'list',1),(1758,'see',1),(1759,'until',1),(1760,'read',1),(1761,'what',1),(1762,'few',1),(1763,'others',1),(1764,'were',1),(1765,'saying',1),(1766,'now',1),(1767,'can',1),(1768,'recommend',1),(1769,'enough',1),(1770,'excellent',1),(1771,'2.5',1),(1772,'hours',1),(1773,'pure',1),(1774,'escapism',1),(1775,'great',1),(1776,'sets',1),(1777,'intriguing',1),(1778,'cinematography',1),(1779,'characters',1),(1780,'entertaining',1),(1781,'story',1),(1782,'good',1),(1783,'blend',1),(1784,'action',1),(1785,'humor',1),(1786,'small',1),(1787,'dose',1),(1788,'disney',1),(1789,'esque',1),(1790,'romance',1),(1791,'capt',1),(1792,'n',1),(1793,'jack',1),(1794,'sparrow',1),(1795,'johnny',1),(1796,'depp',1),(1797,'gives',1),(1798,'us',1),(1799,'another',1),(1800,'brilliant',1),(1801,'performance',1),(1802,'holding',1),(1803,'audience',1),(1804,'with',1),(1805,'his',1),(1806,'charm',1),(1807,'swagger',1),(1808,'throughout',1),(1809,'entirety',1),(1810,'he',1),(1811,'continues',1),(1812,'impress',1),(1813,'wide',1),(1814,'range',1),(1815,'ability',1),(1816,'consistently',1),(1817,'strong',1),(1818,'recent',1),(1819,'career',1),(1820,'need',1),(1821,'nice',1),(1822,'visuals',1),(1823,'keira',1),(1824,'knightley',1),(1825,'adds',1),(1826,'without',1),(1827,'giving',1),(1828,'anything',1),(1829,'away',1),(1830,'favorite',1),(1831,'parts',1),(1832,'over',1),(1833,'obvious',1),(1834,'tie',1),(1835,'ins',1),(1836,'original',1),(1837,'ride',1),(1838,'anyone',1),(1839,'remembers',1),(1840,'even',1),(1841,'portion',1),(1842,'scenes',1),(1843,'float',1),(1844,'by',1),(1845,'bound',1),(1846,'chuckles',1),(1847,'they',1),(1848,'re',1),(1849,'cleverly',1),(1850,'integrated',1),(1851,'movements',1),(1852,'moonlight',1),(1853,'make',1),(1854,'nicely',1),(1855,'added',1),(1856,'effects',1),(1857,'don',1),(1858,'remember',1),(1859,'revealing',1),(1860,'note',1),(1861,'rated',1),(1862,'pg',1),(1863,'13',1),(1864,'reason',1),(1865,'true',1),(1866,'identity',1),(1867,'their',1),(1868,'violence',1),(1869,'may',1),(1870,'give',1),(1871,'children',1),(1872,'nightmares',1),(1873,'so',1),(1874,'think',1),(1875,'twice',1),(1876,'ye',1),(1877,'ones.',1),(1878,'aside',1),(1879,'lengthy',1),(1880,'sword',1),(1881,'fights',1),(1882,'any',1),(1883,'not',1),(1884,'should',1),(1885,'run',1),(1886,'walk',1),(1887,'your',1),(1888,'nearest',1),(1889,'theater',1),(1890,'soon',1),(1891,'possible',1),(1892,'rambling',1),(1893,'loud',1),(1894,'when',1),(1895,'ll',1),(1896,'least',1),(1897,'be',1),(1898,'mumbling',1),(1899,'yourself',1),(1900,'hopefully',1),(1901,'did',1),(1912,'kjlhkhkjhklhj',1),(1914,'kjhkjh',1),(1915,'sdfgsdfg',1),(1916,'sdfgsdfgsdfgds',1),(1917,'fgsd',1),(1919,'overslept',1),(1920,'sdgj',1),(1921,'sdlgjsdkfjlgh',1),(1922,'gds',1),(1923,'sdfgsdfgsd',1),(1924,'gsd',1),(1948,'journals',1),(1947,'weblogs',1),(1946,'websites',1),(1953,'concepts',1),(1952,'thoughts',1),(1951,'parenthetical',1),(1945,'worthwhile',1),(1950,'blogs',1),(1949,'pubs',1),(2018,'blog_package',1),(1994,'ete',1),(1995,'te',1);
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
INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart'),(2,'Users','','ezusernavigationpart'),(3,'Media','','ezmedianavigationpart'),(4,'News','','ezcontentnavigationpart'),(5,'Contact','','ezcontentnavigationpart'),(6,'Files','','ezcontentnavigationpart'),(11,'Set up object','','ezsetupnavigationpart'),(12,'Links','','ezcontentnavigationpart'),(13,'Blogs','','ezcontentnavigationpart');
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
INSERT INTO ezsession VALUES ('6b757a80dcd2886681c0a2dc420526f6',1069945958,'LastAccessesURI|s:47:\"/content/view/full/50/year/2003/month/11/day/14\";eZUserInfoCache_Timestamp|i:1068468222;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1068711948;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069684819;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}userLimitations|a:3:{i:378;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"298\";s:9:\"policy_id\";s:3:\"378\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:379;a:0:{}i:380;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"299\";s:9:\"policy_id\";s:3:\"380\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:2:{i:298;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"577\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"580\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"581\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"578\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"582\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"583\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"584\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"579\";s:13:\"limitation_id\";s:3:\"298\";s:5:\"value\";s:1:\"5\";}}i:299;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"585\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"586\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"587\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"588\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"589\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"20\";}i:5;a:3:{s:2:\"id\";s:3:\"590\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"21\";}i:6;a:3:{s:2:\"id\";s:3:\"591\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:2:\"22\";}i:7;a:3:{s:2:\"id\";s:3:\"592\";s:13:\"limitation_id\";s:3:\"299\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1068718972;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:13:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"23\";s:4:\"name\";s:3:\"Log\";}i:10;a:2:{s:2:\"id\";s:2:\"24\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"25\";s:4:\"name\";s:4:\"Poll\";}i:12;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;Preferences-advanced_menu|s:2:\"on\";FromGroupID|s:1:\"1\";CurrentViewMode|s:4:\"full\";ContentNodeID|s:2:\"50\";ContentObjectID|s:2:\"49\";DeleteIDArray|a:1:{i:0;s:3:\"126\";}DeleteClassIDArray|a:3:{i:0;s:2:\"20\";i:1;s:2:\"21\";i:2;s:2:\"22\";}eZUserLoggedInID|s:2:\"14\";DisableRoleCache|i:1;BrowseParameters|a:14:{s:11:\"action_name\";s:26:\"NewObjectAddNodeAssignment\";s:20:\"description_template\";s:41:\"design:content/browse_first_placement.tpl\";s:4:\"keys\";a:2:{s:5:\"class\";s:2:\"26\";s:10:\"classgroup\";a:1:{i:0;s:1:\"1\";}}s:15:\"persistent_data\";a:1:{s:7:\"ClassID\";s:2:\"26\";}s:7:\"content\";a:1:{s:8:\"class_id\";s:2:\"26\";}s:9:\"from_page\";s:15:\"/content/action\";s:4:\"type\";s:26:\"NewObjectAddNodeAssignment\";s:9:\"selection\";s:6:\"single\";s:11:\"return_type\";s:6:\"NodeID\";s:20:\"browse_custom_action\";b:0;s:18:\"custom_action_data\";b:0;s:10:\"start_node\";s:1:\"2\";s:12:\"ignore_nodes\";a:0:{}s:15:\"top_level_nodes\";a:4:{i:0;s:1:\"2\";i:1;s:1:\"5\";i:2;s:2:\"43\";i:3;b:0;}}InformationCollectionMap|a:1:{i:227;i:25;}eZUserDiscountRulesTimestamp|i:1069163989;eZUserDiscountRules14|a:0:{}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}'),('6a6eab2fe29cf6a677d9d42876bc9557',1069688009,''),('4838c02be7388f035b70ffed64eab28f',1069945466,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069428831;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069428831;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069686263;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:5:{i:0;a:5:{s:2:\"id\";s:3:\"387\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"388\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"389\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:6:\"create\";s:10:\"limitation\";s:0:\"\";}i:3;a:5:{s:2:\"id\";s:3:\"390\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";s:10:\"limitation\";s:0:\"\";}i:4;a:5:{s:2:\"id\";s:3:\"391\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:11:\"versionread\";s:10:\"limitation\";s:0:\"\";}}}userLimitations|a:2:{i:388;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"306\";s:9:\"policy_id\";s:3:\"388\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:390;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"308\";s:9:\"policy_id\";s:3:\"390\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"edit\";}}}eZUserDiscountRulesTimestamp|i:1069428831;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitationValues|a:2:{i:306;a:9:{i:0;a:3:{s:2:\"id\";s:3:\"625\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"626\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"627\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"628\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"629\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"630\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"631\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"632\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:2:\"26\";}i:8;a:3:{s:2:\"id\";s:3:\"633\";s:13:\"limitation_id\";s:3:\"306\";s:5:\"value\";s:1:\"5\";}}i:308;a:1:{i:0;a:3:{s:2:\"id\";s:3:\"635\";s:13:\"limitation_id\";s:3:\"308\";s:5:\"value\";s:2:\"26\";}}}LastAccessesURI|s:22:\"/content/view/full/127\";'),('54ad1acc56a6071b0772022b7c30d3a9',1069946117,'eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069428875;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069428875;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069686609;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069677674;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:13:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"23\";s:4:\"name\";s:3:\"Log\";}i:10;a:2:{s:2:\"id\";s:2:\"24\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"25\";s:4:\"name\";s:4:\"Poll\";}i:12;a:2:{s:2:\"id\";s:2:\"26\";s:4:\"name\";s:7:\"Comment\";}}Preferences-advanced_menu|s:2:\"on\";eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}LastAccessesURI|s:20:\"/content/view/full/2\";eZUserDiscountRulesTimestamp|i:1069434106;eZUserDiscountRules14|a:0:{}CurrentViewMode|s:4:\"full\";ContentNodeID|s:1:\"2\";ContentObjectID|s:1:\"1\";DeleteIDArray|a:1:{i:0;s:3:\"182\";}FromGroupID|b:0;');
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
INSERT INTO ezurl VALUES (1,'http://ez.no',1068713677,1068713677,1,0,'dfcdb471b240d964dc3f57b998eb0533'),(2,'http://www.vg.no',1068718860,1068718860,1,0,'26f1033e463720ae68742157890bc752'),(3,'http://www.sina.com.cn',1068718957,1068718957,1,0,'4f12a25ee6cc3d6123be77df850e343e'),(4,'http://download.hzinfo.com',1068719250,1068719250,1,0,'4c9c884a40d63b7d9555ffb77fe75466'),(5,'http://vg.no',1069407573,1069407573,1,0,'5d85f44aadfc8b160572da41691d0162');
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
INSERT INTO ezurl_object_link VALUES (1,768,1),(2,800,1),(3,807,1),(4,814,1),(5,945,57);
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,NULL),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,NULL),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,NULL),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,NULL),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,NULL),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,NULL),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,NULL),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,NULL),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,NULL),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,154,0),(125,'discussions/forum_main_group/music_discussion/latest_msg_not_sticky','70cf693961dcdd67766bf941c3ed2202','content/view/full/130',1,0,0),(126,'discussions/forum_main_group/music_discussion/not_sticky_2','969f470c93e2131a0884648b91691d0b','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,189,0),(124,'discussions/forum_main_group/music_discussion/new_topic_sticky/reply','f3dd8b6512a0b04b426ef7d7307b7229','content/view/full/129',1,0,0),(157,'blogs/computers','2f8fda683b5e2473a80187cbce012bb8','content/view/full/154',1,0,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,153,0),(123,'discussions/forum_main_group/music_discussion/new_topic_sticky','bf37b4a370ddb3935d0625a5b348dd20','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,189,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,189,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/forum_main_group/music_discussion/important_sticky','2f16cf3039c97025a43f23182b4b6d60','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(74,'users/editors/john_doe','470ba5117b9390b819f7c2519c0a6092','content/view/full/91',1,0,0),(75,'users/editors/vid_la','73f7efbac10f9f69aa4f7b19c97cfb16','content/view/full/92',1,0,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(155,'news/*','5319b79408bf223063ba67c14ad03ee0','blogs/{1}',1,0,1),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(104,'discussions/music_discussion','09533dfccc8477debe545d31bccf391f','content/view/full/114',1,149,0),(105,'discussions/forum_main_group/music_discussion/this_is_a_new_topic','cec6b1593bf03079990a89a3fdc60c56','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(107,'discussions/sports_discussion','c551943f4df3c58a693f8ba55e9b6aeb','content/view/full/115',1,151,0),(117,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/foo_bar','741cdf9f1ee1fa974ea7ec755f538271','content/view/full/122',1,0,0),(109,'users/administrator_users/wenyue_yu','823d93f67a2868cf64fecf47ea766bce','content/view/full/117',1,0,0),(111,'discussions/forum_main_group/sports_discussion/football','6e9c09d390322aa44bb5108b93f5f17c','content/view/full/119',1,0,0),(154,'blogs','51704a6cacf71c8d5211445d9e80515f','content/view/full/50',1,0,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,189,0),(114,'discussions/forum_main_group/music_discussion/this_is_a_new_topic/my_reply','1e03a7609698aa8a98dccf1178df0e6f','content/view/full/120',1,0,0),(118,'discussions/forum_main_group/music_discussion/what_about_pop','c4ebc99b2ed9792d1aee0e5fe210b852','content/view/full/123',1,0,0),(119,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic','6c20d2df5a828dcdb6a4fcb4897bb643','content/view/full/124',1,0,0),(120,'discussions/forum_main_group/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','de98a1bb645ea84919a5e34688ff84e2','content/view/full/125',1,0,0),(128,'discussions/forum_main_group/sports_discussion/football/reply_2','13a443b7e046bb36831640f1d19e33d9','content/view/full/133',1,0,0),(130,'discussions/forum_main_group/music_discussion/lkj_ssssstick','75ee87c770e4e8be9d44200cdb71d071','content/view/full/135',1,0,0),(131,'discussions/forum_main_group/music_discussion/foo','12c58f35c1114deeb172aba728c50ca8','content/view/full/136',1,0,0),(132,'discussions/forum_main_group/music_discussion/lkj_ssssstick/reply','6040856b4ec5bcc1c699d95020005be5','content/view/full/137',1,0,0),(135,'discussions/forum_main_group/music_discussion/lkj_ssssstick/uyuiyui','4c48104ea6e5ec2a78067374d9561fcb','content/view/full/140',1,0,0),(136,'discussions/forum_main_group/music_discussion/test2','53f71d4ff69ffb3bf8c8ccfb525eabd3','content/view/full/141',1,0,0),(137,'discussions/forum_main_group/music_discussion/t4','5da27cda0fbcd5290338b7d22cfd730c','content/view/full/142',1,0,0),(138,'discussions/forum_main_group/music_discussion/lkj_ssssstick/klj_jkl_klj','9ae60fa076882d6807506c2232143d27','content/view/full/143',1,0,0),(139,'discussions/forum_main_group/music_discussion/test2/retest2','a17d07fbbd2d1b6d0fbbf8ca1509cd01','content/view/full/144',1,0,0),(140,'users/administrator_users/brd_farstad','875930f56fad1a5cc6fbcac4ed6d3f8d','content/view/full/145',1,0,0),(141,'discussions/forum_main_group/music_discussion/lkj_ssssstick/my_reply','1f95000d1f993ffa16a0cf83b78515bf','content/view/full/146',1,0,0),(142,'discussions/forum_main_group/music_discussion/lkj_ssssstick/retest','0686f14064a420e6ee95aabf89c4a4f2','content/view/full/147',1,0,0),(144,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf','21f0ee2122dd5264192adc15c1e69c03','content/view/full/149',1,0,0),(156,'blogs/personal','10a5d8f539ef0a468722f8327f7950ab','content/view/full/153',1,0,0),(146,'discussions/forum_main_group/music_discussion/hjg_dghsdjgf/dfghd_fghklj','460d30ba47855079ac8605e1c8085993','content/view/full/151',1,0,0),(159,'blogs/computers/special_things_happened_today','4427c3eda2e43a04f639ef1d5f1bb71e','content/view/full/156',1,0,0),(158,'blogs/personal/today_i_got_my_new_car','ce9118c9b6c16328082445f6d8098a0d','content/view/full/155',1,0,0),(149,'discussions/forum_main_group/music_discussion','a1a79985f113d5b05b22c9686b46b175','content/view/full/114',1,0,0),(150,'discussions/music_discussion/*','2ec2a3bfcf01ad3f1323390ab26dfeac','discussions/forum_main_group/music_discussion/{1}',1,0,1),(151,'discussions/forum_main_group/sports_discussion','b68c5a82b8b2035eeee5788cb223bb7e','content/view/full/115',1,0,0),(152,'discussions/sports_discussion/*','7acbf48218ca6e1d80c267911860d34f','discussions/forum_main_group/sports_discussion/{1}',1,0,1),(153,'about_me','50793f253d2dc015e93a2f75163b0894','content/view/full/127',1,0,0),(160,'blogs/computers/special_things_happened_today/brd_farstad','4d1dddb2000bdf69e822fb41d4000919','content/view/full/157',1,0,0),(161,'blogs/computers/special_things_happened_today/bbb','afc9fd5431105082994247c0ae0992b3','content/view/full/158',1,0,0),(162,'blogs/personal/for_posteritys_sake','c6c14fe1f69ebc2a9db76192fcb204f5','content/view/full/159',1,0,0),(163,'which_color_do_you_like','f5680eeedb1965bec60c5ccf3ed7d54d','content/view/full/160',1,177,0),(164,'links','807765384d9d5527da8848df14a4f02f','content/view/full/161',1,0,0),(165,'links/software','820b5cb7cb1cca5488a27bf91b6ae276','content/view/full/162',1,0,0),(166,'links/movie','50872e7d3c617f11baa0ca70bc89500b','content/view/full/163',1,0,0),(167,'links/news','61e2cd3056056408f1b41435a3f953c3','content/view/full/164',1,0,0),(168,'blogs/computers/special_things_happened_today/brd','40f4dda88233928fac915274a90476b5','content/view/full/165',1,0,0),(169,'links/news/vg','ae1126bc66ec164212018a497469e3b5','content/view/full/166',1,0,0),(170,'blogs/computers/special_things_happened_today/kjh','0cca438ee3d1d3b2cdfaa9d45dbac2a7','content/view/full/167',1,0,0),(171,'links/news/sina_','68e911c6f20934bdc959741837d8d092','content/view/full/168',1,0,0),(172,'blogs/computers/new_big_discovery_today','d174bf1f78f8c3cbf985909a26880d88','content/view/full/169',1,0,0),(173,'links/software/soft_house','aa5de9806ca77bb313e748c9bcf5def8','content/view/full/170',1,0,0),(174,'blogs/computers/no_comments_on_this_one','0df10f829cc6d968d74ece063eaee683','content/view/full/171',1,0,0),(175,'blogs/computers/new_big_discovery_today/brd','2aee5cbd251dbc484e78fba61e5bb7cf','content/view/full/172',1,0,0),(176,'polls','952e8cf2c863b8ddc656bac6ad0b729b','content/view/full/173',1,0,0),(177,'polls/which_color_do_you_like','92ac06c57d7d5888ad2553923877bd81','content/view/full/160',1,0,0),(178,'polls/which_one_is_the_best_of_matrix_movies','bb0ff8ca87eb02ff2219a32c5c73b7f4','content/view/full/174',1,0,0),(179,'blogs/computers/new_big_discovery_today/ghghj','cd10884873caf4a20621b35199f331c4','content/view/full/175',1,0,0),(180,'blogs/entertainment','9dd2cf029c6cfcddc067d936dc750b3d','content/view/full/176',1,0,0),(181,'blogs/entertainment/a_pirates_life','bb23fe0ca4a2afc405c4a70d5ff0abd0','content/view/full/177',1,0,0),(183,'blogs/entertainment/a_pirates_life/kjlh','dbf2c1455eff8c6100181582298d197f','content/view/full/178',1,0,0),(184,'blogs/entertainment/a_pirates_life/kjhkjh','e73acc89936bc771971a97eb45d51c66','content/view/full/179',1,0,0),(185,'blogs/computers/i_overslept_today','9497b5cd127ce3f9f04e3d74c8fc4da5','content/view/full/180',1,0,0),(186,'blogs/computers/i_overslept_today/ete','622a2ab0df1cfaed60e3ee0789670b09','content/view/full/181',1,0,0),(188,'setup/look_and_feel/blog_test','6d9593beb391a932e45ca5823e3c6359','content/view/full/54',1,189,0),(189,'setup/look_and_feel/blog','a0aa455a1c24b5d1d0448546c83836cf','content/view/full/54',1,0,0);
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

