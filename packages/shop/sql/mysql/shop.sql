-- MySQL dump 10.2
--
-- Host: localhost    Database: shop
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
INSERT INTO ezbasket VALUES (6,'4e056741be0b1770b826de919af0ad8e',11),(14,'96838e2837b7ec740be66d1d1512fb9c',25),(24,'2709afc0124fcde6e6bd1ec3e98c377b',43),(18,'6b757a80dcd2886681c0a2dc420526f6',32),(19,'5a46feb97b42e9033959f223e332d629',33),(25,'d144d837cf1aa4b2cfe9d79040b43308',44),(26,'460a923efa1a53d177ee3b7244c04e8e',45),(27,'5385528c29f6908be71c2d3922979714',46),(28,'528a3407f964179aa9ac7ce28f571756',47),(29,'5230996141fe53a16fa4df1ac02cd9b9',48),(30,'0715f5bd606d66c08243d4c67bd00113',49),(31,'8911f1e666abd88d7e98ecc73bf660ea',50),(32,'08f57047b34038570d0e62b8860952f9',51),(33,'c5dd18266345142bb20c57d636c7c8f2',52),(34,'9d0b93e442855fcb7bd821a57f8eca88',53),(35,'9fce2c74428fd4af54542d7473f6acee',54),(36,'3db55e6c42f0409d91be8fe575bd0821',55),(37,'b65259d5737b8010b281531d84358478',56),(54,'464212df929324762edc60211494fb9e',76),(39,'1e9a94f5455d3f67f888567016d88683',58),(40,'9d660d57da1f1f02106e5f90c4bd0ae9',59),(41,'9c0fdb01041d138528b605e65a9da8cd',60),(42,'e28ef2f5db0828140c44212cc15c880b',61),(43,'868707b573014747f34171a801bba22e',62),(44,'7548de0deed02f93ed1186bc4a4508bb',63),(45,'e9602cfbf30dbb61c2453d7191ca0a86',64),(47,'aeaf8a0e2a1647a9fd602f229abda50f',67),(48,'08c8b37c737d2f25e72c34bd1515736d',68),(49,'ad5fbbfd62fb0d669908cc510c28b6f7',69),(51,'a9bb3c392685347c8dc4969c4eb9181d',72),(52,'4a40866bc688bb17c40a9d820d8d7f2a',73),(53,'1dde902a168671f48191995b2f7222f1',74),(55,'f01cd9ad6c05527b5a586df7a6e97468',77),(56,'b9a0d3aac222f6ebd2210d5a467c08d1',78),(57,'e9dfb7981bccd905ef01a27f66aabee6',79),(58,'6504c19a10041a915e9c9d7e234c8702',80),(59,'1d8825262cbd96439e0418110ef44615',81),(60,'310c286311d138e22ca04681dfacc2a7',82);
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
INSERT INTO ezcontentbrowserecent VALUES (81,14,50,1069687270,'News'),(35,111,99,1067006746,'foo bar corp'),(65,149,135,1068126974,'lkj ssssstick'),(49,10,12,1069246037,'Guest accounts'),(64,206,135,1068123651,'lkj ssssstick');
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
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1066907423),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1069678307),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(14,0,'Setup link','setup_link','<title>',14,14,1066383719,1066383885),(15,0,'Template look','template_look','<title>',14,14,1066390045,1069416268),(12,1,'File','file','<name>',14,14,1052385472,1067353799),(23,0,'Product','product','<name>',14,14,1068472452,1068557806),(24,0,'Feedback form','feedback_form','<name>',14,14,1068554718,1068555117),(25,0,'Review','review','<topic>',14,14,1068565707,1068648892);
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
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(154,0,14,'description','Description','eztext',1,0,3,2,0,0,0,0,0,0,0,'','','','','',0,1),(153,0,14,'icon','Icon','ezimage',0,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(152,0,14,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(155,0,14,'link','Link','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','','',0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','','',0,1),(177,0,2,'frontpage_image','Frontpage image','ezinteger',0,0,6,0,0,0,0,0,0,0,0,'','','','','',0,1),(123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','','',0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','','',0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(147,1,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(146,1,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','','',0,1),(148,1,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(218,0,15,'footer','Footer','ezstring',1,0,8,0,0,0,0,0,0,0,0,'Copyright &copy;','','','','',0,1),(196,0,15,'siteurl','Site URL','ezinisetting',0,0,7,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteURL','0;1;2;3','override;user;admin;demo;intranet',0,1),(180,0,15,'email','Email','ezinisetting',0,0,6,1,0,0,0,0,0,0,0,'site.ini','MailSettings','AdminEmail','0;1;2;3','override;user;admin;demo;intranet',0,1),(160,0,15,'css','CSS','ezpackage',0,0,4,1,0,0,0,0,0,0,0,'css','','','','',0,1),(161,0,15,'id','id','ezstring',1,0,5,0,0,0,0,0,0,0,0,'','','','','',0,1),(215,0,25,'topic','Topic','ezstring',0,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(216,0,25,'description','Description','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','','',0,1),(217,0,25,'rating','Rating','ezinteger',1,1,3,0,5,0,3,0,0,0,0,'','','','','',0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','','',0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','','',0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(201,0,23,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1),(202,0,23,'product_number','Product number','ezstring',1,0,2,0,0,0,0,0,0,0,0,'','','','','',0,1),(203,0,23,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','','',0,1),(204,0,23,'image','Image','ezimage',0,0,4,1,0,0,0,0,0,0,0,'','','','','',0,1),(205,0,23,'price','Price','ezprice',0,0,5,1,0,0,0,1,0,0,0,'','','','','',0,1),(159,0,15,'image','Image','ezimage',0,0,3,0,0,0,0,0,0,0,0,'','','','','',0,1),(158,0,15,'meta_data','Meta data','ezinisetting',0,0,2,6,0,0,0,0,0,0,0,'site.ini','SiteSettings','MetaDataArray','0;1;2;3','override;user;admin;demo;intranet',0,1),(157,0,15,'title','Title','ezinisetting',0,0,1,1,0,0,0,0,0,0,0,'site.ini','SiteSettings','SiteName','0;1;2;3','override;user;admin;demo;intranet',0,1),(210,0,24,'message','Message','eztext',1,0,5,10,0,0,0,0,0,0,0,'','','','','',1,1),(209,0,24,'email','E-mail','ezstring',1,0,4,0,0,0,0,0,0,0,0,'','','','','',1,1),(208,0,24,'subject','Subject','ezstring',1,0,3,0,0,0,0,0,0,0,0,'','','','','',1,1),(207,0,24,'description','Description','ezxmltext',1,0,2,15,0,0,0,0,0,0,0,'','','','','',0,1),(206,0,24,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','','',0,1);
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
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content'),(14,0,4,'Setup'),(15,0,4,'Setup'),(12,1,3,'Media'),(16,0,1,'Content'),(17,0,1,'Content'),(21,1,1,'Content'),(20,0,1,'Content'),(21,0,1,'Content'),(23,0,1,'Content'),(24,0,1,'Content'),(25,0,1,'Content');
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
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Shop',8,0,1033917596,1069686123,1,''),(4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',7,0,1033920830,1068556425,1,''),(228,14,1,23,'Compaq pressario',1,0,1068562987,1068562987,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,''),(42,14,11,1,'Setup',1,0,1066383068,1066383068,1,''),(43,14,11,14,'Classes',11,0,1066384365,1068640429,1,''),(44,14,11,1,'Setup links',1,0,1066384457,1066384457,1,''),(45,14,11,14,'Look and feel',11,0,1066388816,1068640502,1,''),(46,14,11,1,'Look and feel',2,0,1066389805,1066389902,1,''),(47,14,1,15,'New Template look',1,0,0,0,0,''),(122,14,1,5,'New Image',1,0,0,0,0,''),(49,14,4,1,'News',1,0,1066398020,1066398020,1,''),(51,14,1,14,'New Setup link',1,0,0,0,0,''),(53,14,1,15,'New Template look',1,0,0,0,0,''),(56,14,11,15,'Shop',56,0,1066643397,1069687079,1,''),(230,14,1,23,'P223498',1,0,1068563080,1068563080,1,''),(232,14,1,25,'Good',3,0,1068567344,1068632190,1,''),(160,14,4,2,'News bulletin October',2,0,1068047455,1069686818,1,''),(161,14,1,10,'Shipping and returns',2,0,1068047603,1068542655,1,''),(219,14,1,10,'Privacy notice',1,0,1068542692,1068542692,1,''),(129,14,1,2,'New Article',1,0,0,0,0,''),(127,14,4,2,'New Article',1,0,0,0,0,''),(83,14,2,4,'New User',1,0,0,0,0,''),(84,14,2,4,'New User',1,0,0,0,0,''),(85,14,5,1,'New Folder',1,0,0,0,0,''),(87,14,5,16,'New Company',1,0,0,0,2,''),(88,14,2,4,'New User',1,0,0,0,0,''),(91,14,1,15,'New Template look',1,0,0,0,0,''),(229,14,1,23,'F100',1,0,1068563029,1068563029,1,''),(165,149,1,21,'New Forum topic',1,0,0,0,2,''),(96,14,2,4,'New User',1,0,0,0,0,''),(126,14,4,2,'New Article',1,0,0,0,0,''),(103,14,2,4,'New User',1,0,0,0,0,''),(104,14,2,4,'New User',1,0,0,0,0,''),(105,14,2,4,'New User',1,0,0,0,0,''),(106,14,2,4,'New User',1,0,0,0,0,''),(107,14,2,4,'John Doe',2,0,1066916865,1066916941,1,''),(111,14,2,4,'vid la',1,0,1066917523,1066917523,1,''),(213,14,1,1,'Products',2,0,1068473231,1068556203,1,''),(115,14,11,14,'Cache',5,0,1066991725,1068640475,1,''),(116,14,11,14,'URL translator',4,0,1066992054,1068640525,1,''),(117,14,4,2,'New Article',1,0,0,0,0,''),(143,14,1,14,'New Setup link',1,0,0,0,0,''),(144,14,1,14,'New Setup link',1,0,0,0,0,''),(145,14,1,14,'New Setup link',1,0,0,0,0,''),(246,14,1,4,'New User',1,0,0,0,0,''),(215,14,1,1,'Mac',1,0,1068474891,1068474891,1,''),(216,14,1,1,'Monitor',1,0,1068474919,1068474919,1,''),(217,14,1,1,'LCD',1,0,1068474983,1068474983,1,''),(218,14,1,23,'compaq m2000',2,0,1068479750,1068479824,1,''),(168,149,0,21,'New Forum topic',1,0,0,0,2,''),(169,149,0,21,'New Forum topic',1,0,0,0,2,''),(214,14,1,1,'PC',1,0,1068474871,1068474871,1,''),(171,149,1,21,'New Forum topic',1,0,0,0,2,''),(172,149,0,21,'New Forum topic',1,0,0,0,2,''),(173,149,0,21,'New Forum topic',1,0,0,0,2,''),(174,149,0,21,'New Forum topic',1,0,0,0,2,''),(175,149,0,21,'New Forum topic',1,0,0,0,2,''),(176,149,0,21,'New Forum topic',1,0,0,0,2,''),(177,149,0,21,'New Forum topic',1,0,0,0,2,''),(178,149,0,21,'New Forum topic',1,0,0,0,2,''),(179,149,0,21,'New Forum topic',1,0,0,0,2,''),(180,149,0,21,'New Forum topic',1,0,0,0,2,''),(181,149,0,21,'New Forum topic',1,0,0,0,2,''),(182,149,0,21,'New Forum topic',1,0,0,0,2,''),(183,149,0,21,'New Forum topic',1,0,0,0,2,''),(184,149,0,21,'New Forum topic',1,0,0,0,2,''),(185,149,0,21,'New Forum topic',1,0,0,0,2,''),(186,149,0,21,'New Forum topic',1,0,0,0,2,''),(187,14,1,4,'New User',1,0,0,0,0,''),(191,149,1,21,'New Forum topic',1,0,0,0,2,''),(189,14,1,4,'New User',1,0,0,0,0,''),(192,149,0,21,'New Forum topic',1,0,0,0,2,''),(193,149,0,21,'New Forum topic',1,0,0,0,2,''),(194,149,0,21,'New Forum topic',1,0,0,0,2,''),(225,14,1,23,'New Product',1,0,0,0,0,''),(226,14,1,23,'New Product',1,0,0,0,0,''),(200,149,1,21,'New Forum topic',1,0,0,0,2,''),(201,149,1,22,'New Forum reply',1,0,0,0,2,''),(224,14,1,23,'New Product',1,0,0,0,0,''),(223,14,1,23,'New Product',1,0,0,0,0,''),(222,14,1,24,'Contact us',1,0,1068554919,1068554919,1,''),(206,14,2,4,'Bård Farstad',1,0,1068123599,1068123599,1,''),(227,14,1,23,'option test',1,0,1068557743,1068557743,1,''),(220,14,1,10,'Conditions of use',1,0,1068542738,1068542738,1,''),(211,14,1,23,'iPod',3,0,1068472652,1068556361,1,''),(212,14,1,23,'Nokia G101',2,0,1068472760,1068473309,1,''),(235,14,1,25,'The Best Expansion Pack?',1,0,1068647907,1068647907,1,''),(236,14,1,25,'Whimper',1,0,1068649441,1068649441,1,''),(237,14,1,25,'An Utter Disappointment',1,0,1068649592,1068649592,1,''),(238,14,1,25,'asdfasdf',1,0,1068652630,1068652630,1,''),(239,14,1,4,'Test Testersen',1,0,1069246036,1069246036,1,''),(240,14,1,25,'New Review',1,0,0,0,0,''),(241,14,1,25,'testttt',1,0,1069328695,1069328695,1,''),(242,14,1,25,'ohoh',1,0,1069328774,1069328774,1,''),(243,14,1,4,'New User',1,0,0,0,2,''),(244,14,1,4,'New User',1,0,0,0,2,''),(245,14,1,4,'New User',1,0,0,0,0,''),(247,14,1,4,'New User',1,0,0,0,0,''),(248,14,1,4,'New User',1,0,0,0,2,''),(249,14,1,4,'New User',1,0,0,0,2,''),(250,14,4,2,'News bulletin November',1,0,1069687269,1069687269,1,'');
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
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',6,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',6,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring'),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring'),(21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser'),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring'),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring'),(20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring'),(23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring'),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring'),(25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring'),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring'),(27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring'),(98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring'),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(100,'eng-GB',1,42,4,'Setup',0,0,0,0,'setup','ezstring'),(101,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(103,'eng-GB',11,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-11-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',11,43,152,'Classes',0,0,0,0,'classes','ezstring'),(106,'eng-GB',1,44,4,'Setup links',0,0,0,0,'setup links','ezstring'),(107,'eng-GB',1,44,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(102,'eng-GB',2,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',2,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',2,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',2,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',3,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',3,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',3,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',3,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',4,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',4,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',4,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',4,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(102,'eng-GB',5,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',5,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',5,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',5,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',11,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel.png\"\n         original_filename=\"look_and_feel.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-11-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(108,'eng-GB',11,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(112,'eng-GB',1,46,4,'Fonts and colors',0,0,0,0,'fonts and colors','ezstring'),(113,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(112,'eng-GB',2,46,4,'Look and feel',0,0,0,0,'look and feel','ezstring'),(113,'eng-GB',2,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(125,'eng-GB',1,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(761,'eng-GB',2,211,205,'',0,100,0,0,'','ezprice'),(28,'eng-GB',3,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',3,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',3,14,12,'',0,0,0,0,'','ezuser'),(676,'eng-GB',1,200,190,'',0,0,0,0,'','ezboolean'),(677,'eng-GB',1,200,194,'',0,0,0,0,'','ezsubtreesubscription'),(678,'eng-GB',1,201,191,'Re:test',0,0,0,0,'re:test','ezstring'),(679,'eng-GB',1,201,193,'fdsf',0,0,0,0,'','eztext'),(763,'eng-GB',2,212,202,'G101',0,0,0,0,'g101','ezstring'),(762,'eng-GB',2,212,201,'Nokia G101',0,0,0,0,'nokia g101','ezstring'),(153,'eng-GB',4,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(153,'eng-GB',5,56,160,'intranet1',0,0,0,0,'intranet1','ezpackage'),(558,'eng-GB',1,171,189,'',0,0,0,0,'','eztext'),(553,'eng-GB',1,169,190,'',0,0,0,0,'','ezboolean'),(554,'eng-GB',1,169,194,'',0,0,0,0,'','ezsubtreesubscription'),(767,'eng-GB',1,213,4,'Hardware',0,0,0,0,'hardware','ezstring'),(557,'eng-GB',1,171,188,'',0,0,0,0,'','ezstring'),(552,'eng-GB',1,169,189,'sfsvggs\nsfsf',0,0,0,0,'','eztext'),(547,'eng-GB',1,168,188,'',0,0,0,0,'','ezstring'),(548,'eng-GB',1,168,189,'',0,0,0,0,'','eztext'),(549,'eng-GB',1,168,190,'',0,0,0,0,'','ezboolean'),(550,'eng-GB',1,168,194,'',0,0,0,0,'','ezsubtreesubscription'),(551,'eng-GB',1,169,188,'test',0,0,0,0,'test','ezstring'),(521,'eng-GB',1,160,177,'',0,0,0,0,'','ezinteger'),(516,'eng-GB',1,160,1,'News bulletin',0,0,0,0,'news bulletin','ezstring'),(517,'eng-GB',1,160,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(518,'eng-GB',1,160,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n  <paragraph>This is the latest news from lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall . lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall lorem ipsum dill dall </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(519,'eng-GB',1,160,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin.\"\n         suffix=\"\"\n         basename=\"news_bulletin\"\n         dirpath=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB\"\n         url=\"var/forum/storage/images/news/news_bulletin/519-1-eng-GB/news_bulletin.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(520,'eng-GB',1,160,123,'',0,0,0,0,'','ezboolean'),(154,'eng-GB',52,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',52,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(766,'eng-GB',2,212,205,'',0,120,0,0,'','ezprice'),(535,'eng-GB',1,165,188,'',0,0,0,0,'','ezstring'),(536,'eng-GB',1,165,189,'',0,0,0,0,'','eztext'),(537,'eng-GB',1,165,190,'',0,0,0,0,'','ezboolean'),(538,'eng-GB',1,165,194,'',0,0,0,0,'','ezsubtreesubscription'),(152,'eng-GB',7,56,159,'',0,0,0,0,'','ezimage'),(153,'eng-GB',7,56,160,'left_menu',0,0,0,0,'left_menu','ezpackage'),(154,'eng-GB',7,56,161,'intranet888',0,0,0,0,'intranet888','ezstring'),(152,'eng-GB',50,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-50-eng-GB/my_shop_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',50,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(110,'eng-GB',9,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',9,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',2,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',2,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/url_translator/328-2-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(740,'eng-GB',1,206,8,'Bård',0,0,0,0,'bård','ezstring'),(741,'eng-GB',1,206,9,'Farstad',0,0,0,0,'farstad','ezstring'),(742,'eng-GB',1,206,12,'',0,0,0,0,'','ezuser'),(940,'eng-GB',1,250,1,'News bulletin November',0,0,0,0,'news bulletin november','ezstring'),(519,'eng-GB',2,160,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin_october.\"\n         suffix=\"\"\n         basename=\"news_bulletin_october\"\n         dirpath=\"var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB\"\n         url=\"var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB/news_bulletin_october.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"\">\n  <original attribute_id=\"519\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(520,'eng-GB',2,160,123,'',1,0,0,1,'','ezboolean'),(521,'eng-GB',2,160,177,'',0,0,0,0,'','ezinteger'),(639,'eng-GB',1,192,189,'',0,0,0,0,'','eztext'),(640,'eng-GB',1,192,190,'',0,0,0,0,'','ezboolean'),(627,'eng-GB',1,189,8,'',0,0,0,0,'','ezstring'),(628,'eng-GB',1,189,9,'',0,0,0,0,'','ezstring'),(629,'eng-GB',1,189,12,'',0,0,0,0,'','ezuser'),(634,'eng-GB',1,191,188,'',0,0,0,0,'','ezstring'),(635,'eng-GB',1,191,189,'',0,0,0,0,'','eztext'),(636,'eng-GB',1,191,190,'',0,0,0,0,'','ezboolean'),(637,'eng-GB',1,191,194,'',0,0,0,0,'','ezsubtreesubscription'),(638,'eng-GB',1,192,188,'',0,0,0,0,'','ezstring'),(609,'eng-GB',1,184,188,'',0,0,0,0,'','ezstring'),(610,'eng-GB',1,184,189,'',0,0,0,0,'','eztext'),(611,'eng-GB',1,184,190,'',0,0,0,0,'','ezboolean'),(612,'eng-GB',1,184,194,'',0,0,0,0,'','ezsubtreesubscription'),(613,'eng-GB',1,185,188,'',0,0,0,0,'','ezstring'),(614,'eng-GB',1,185,189,'',0,0,0,0,'','eztext'),(615,'eng-GB',1,185,190,'',0,0,0,0,'','ezboolean'),(616,'eng-GB',1,185,194,'',0,0,0,0,'','ezsubtreesubscription'),(617,'eng-GB',1,186,188,'',0,0,0,0,'','ezstring'),(618,'eng-GB',1,186,189,'',0,0,0,0,'','eztext'),(619,'eng-GB',1,186,190,'',0,0,0,0,'','ezboolean'),(620,'eng-GB',1,186,194,'',0,0,0,0,'','ezsubtreesubscription'),(621,'eng-GB',1,187,8,'',0,0,0,0,'','ezstring'),(622,'eng-GB',1,187,9,'',0,0,0,0,'','ezstring'),(623,'eng-GB',1,187,12,'',0,0,0,0,'','ezuser'),(765,'eng-GB',2,212,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"nokia_g101.jpg\"\n         suffix=\"jpg\"\n         basename=\"nokia_g101\"\n         dirpath=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB/nokia_g101.jpg\"\n         original_filename=\"dscn0009.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"nokia\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"765\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"nokia_g101_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB/nokia_g101_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"nokia_g101_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB/nokia_g101_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"150\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"nokia_g101_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB/nokia_g101_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"225\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"nokia_g101_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/nokia_g101/765-2-eng-GB/nokia_g101_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(603,'eng-GB',1,182,190,'',0,0,0,0,'','ezboolean'),(604,'eng-GB',1,182,194,'',0,0,0,0,'','ezsubtreesubscription'),(605,'eng-GB',1,183,188,'',0,0,0,0,'','ezstring'),(606,'eng-GB',1,183,189,'',0,0,0,0,'','eztext'),(607,'eng-GB',1,183,190,'',0,0,0,0,'','ezboolean'),(608,'eng-GB',1,183,194,'',0,0,0,0,'','ezsubtreesubscription'),(602,'eng-GB',1,182,189,'',0,0,0,0,'','eztext'),(406,'eng-GB',1,129,177,'',0,0,0,0,'','ezinteger'),(675,'eng-GB',1,200,189,'sefsefsf\nsf\nsf',0,0,0,0,'','eztext'),(401,'eng-GB',1,129,1,'New article',0,0,0,0,'new article','ezstring'),(402,'eng-GB',1,129,120,'',1045487555,0,0,0,'','ezxmltext'),(403,'eng-GB',1,129,121,'',1045487555,0,0,0,'','ezxmltext'),(404,'eng-GB',1,129,122,'',0,0,0,0,'','ezimage'),(405,'eng-GB',1,129,123,'',0,0,0,0,'','ezboolean'),(151,'eng-GB',52,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(601,'eng-GB',1,182,188,'',0,0,0,0,'','ezstring'),(302,'eng-GB',1,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',1,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',1,107,12,'',0,0,0,0,'','ezuser'),(302,'eng-GB',2,107,8,'John',0,0,0,0,'john','ezstring'),(303,'eng-GB',2,107,9,'Doe',0,0,0,0,'doe','ezstring'),(304,'eng-GB',2,107,12,'',0,0,0,0,'','ezuser'),(315,'eng-GB',1,111,12,'',0,0,0,0,'','ezuser'),(313,'eng-GB',1,111,8,'vid',0,0,0,0,'vid','ezstring'),(314,'eng-GB',1,111,9,'la',0,0,0,0,'la','ezstring'),(522,'eng-GB',2,161,140,'Shipping and returns',0,0,0,0,'shipping and returns','ezstring'),(1,'eng-GB',7,1,4,'Shop',0,0,0,0,'shop','ezstring'),(2,'eng-GB',7,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(768,'eng-GB',1,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Hardware products</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(757,'eng-GB',2,211,201,'iPod',0,0,0,0,'ipod','ezstring'),(522,'eng-GB',1,161,140,'About this forum',0,0,0,0,'about this forum','ezstring'),(523,'eng-GB',1,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus. </paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',1,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"about_this_forum.\"\n         suffix=\"\"\n         basename=\"about_this_forum\"\n         dirpath=\"var/forum/storage/images/about_this_forum/524-1-eng-GB\"\n         url=\"var/forum/storage/images/about_this_forum/524-1-eng-GB/about_this_forum.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',52,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"51\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-52-eng-GB/my_shop_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',52,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(108,'eng-GB',2,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(102,'eng-GB',6,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',6,43,153,'',0,0,0,0,'','ezimage'),(104,'eng-GB',6,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',6,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(109,'eng-GB',2,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',2,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',2,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(102,'eng-GB',7,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',7,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"classes1.png\"\n         suffix=\"png\"\n         basename=\"classes1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"classes1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/classes//103-7-eng-GB/classes1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',7,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',7,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',3,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',3,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',3,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',3,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(323,'eng-GB',1,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',1,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"cache1.png\"\n         suffix=\"png\"\n         basename=\"cache1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"cache1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache//324-1-eng-GB/cache1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',1,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',1,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(108,'eng-GB',4,45,152,'Setup Objects',0,0,0,0,'setup objects','ezstring'),(109,'eng-GB',4,45,153,'',0,0,0,0,'','ezimage'),(110,'eng-GB',4,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',4,45,155,'content/view/full/44',0,0,0,0,'content/view/full/44','ezstring'),(327,'eng-GB',1,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',1,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"url_translator1.png\"\n         suffix=\"png\"\n         basename=\"url_translator1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"url_translator1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/url_translator//328-1-eng-GB/url_translator1_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',1,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',1,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(108,'eng-GB',5,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',5,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"1\"\n         filename=\"look_and_feel1.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel1\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <alias name=\"reference\"\n         filename=\"look_and_feel1_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_reference.png\"\n         mime_type=\"image/png\"\n         width=\"600\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel1_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel//109-5-eng-GB/look_and_feel1_large.png\"\n         mime_type=\"image/png\"\n         width=\"300\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',5,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',5,45,155,'content/view/full/48',0,0,0,0,'content/view/full/48','ezstring'),(151,'eng-GB',51,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(108,'eng-GB',6,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',6,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-6-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(125,'eng-GB',2,49,4,'News',0,0,0,0,'news','ezstring'),(126,'eng-GB',2,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(110,'eng-GB',6,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',6,45,155,'content/view/full/54',0,0,0,0,'content/view/full/54','ezstring'),(323,'eng-GB',2,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',2,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/cache/324-2-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',2,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',2,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(152,'eng-GB',51,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.jpg\"\n         suffix=\"jpg\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop.jpg\"\n         original_filename=\"logo1\"\n         mime_type=\"original\"\n         width=\"300\"\n         height=\"100\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"50\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"100\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"200\"\n         height=\"66\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-51-eng-GB/my_shop_logo.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"174\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(600,'eng-GB',1,181,194,'',0,0,0,0,'','ezsubtreesubscription'),(591,'eng-GB',1,179,190,'',0,0,0,0,'','ezboolean'),(592,'eng-GB',1,179,194,'',0,0,0,0,'','ezsubtreesubscription'),(593,'eng-GB',1,180,188,'',0,0,0,0,'','ezstring'),(594,'eng-GB',1,180,189,'',0,0,0,0,'','eztext'),(595,'eng-GB',1,180,190,'',0,0,0,0,'','ezboolean'),(596,'eng-GB',1,180,194,'',0,0,0,0,'','ezsubtreesubscription'),(597,'eng-GB',1,181,188,'',0,0,0,0,'','ezstring'),(598,'eng-GB',1,181,189,'',0,0,0,0,'','eztext'),(599,'eng-GB',1,181,190,'',0,0,0,0,'','ezboolean'),(573,'eng-GB',1,175,188,'',0,0,0,0,'','ezstring'),(574,'eng-GB',1,175,189,'',0,0,0,0,'','eztext'),(575,'eng-GB',1,175,190,'',0,0,0,0,'','ezboolean'),(576,'eng-GB',1,175,194,'',0,0,0,0,'','ezsubtreesubscription'),(577,'eng-GB',1,176,188,'',0,0,0,0,'','ezstring'),(578,'eng-GB',1,176,189,'',0,0,0,0,'','eztext'),(579,'eng-GB',1,176,190,'',0,0,0,0,'','ezboolean'),(580,'eng-GB',1,176,194,'',0,0,0,0,'','ezsubtreesubscription'),(581,'eng-GB',1,177,188,'',0,0,0,0,'','ezstring'),(582,'eng-GB',1,177,189,'',0,0,0,0,'','eztext'),(583,'eng-GB',1,177,190,'',0,0,0,0,'','ezboolean'),(584,'eng-GB',1,177,194,'',0,0,0,0,'','ezsubtreesubscription'),(585,'eng-GB',1,178,188,'',0,0,0,0,'','ezstring'),(586,'eng-GB',1,178,189,'',0,0,0,0,'','eztext'),(587,'eng-GB',1,178,190,'',0,0,0,0,'','ezboolean'),(588,'eng-GB',1,178,194,'',0,0,0,0,'','ezsubtreesubscription'),(589,'eng-GB',1,179,188,'',0,0,0,0,'','ezstring'),(590,'eng-GB',1,179,189,'',0,0,0,0,'','eztext'),(561,'eng-GB',1,172,188,'',0,0,0,0,'','ezstring'),(562,'eng-GB',1,172,189,'',0,0,0,0,'','eztext'),(563,'eng-GB',1,172,190,'',0,0,0,0,'','ezboolean'),(564,'eng-GB',1,172,194,'',0,0,0,0,'','ezsubtreesubscription'),(565,'eng-GB',1,173,188,'',0,0,0,0,'','ezstring'),(566,'eng-GB',1,173,189,'',0,0,0,0,'','eztext'),(567,'eng-GB',1,173,190,'',0,0,0,0,'','ezboolean'),(568,'eng-GB',1,173,194,'',0,0,0,0,'','ezsubtreesubscription'),(569,'eng-GB',1,174,188,'',0,0,0,0,'','ezstring'),(570,'eng-GB',1,174,189,'',0,0,0,0,'','eztext'),(571,'eng-GB',1,174,190,'',0,0,0,0,'','ezboolean'),(572,'eng-GB',1,174,194,'',0,0,0,0,'','ezsubtreesubscription'),(560,'eng-GB',1,171,194,'',0,0,0,0,'','ezsubtreesubscription'),(559,'eng-GB',1,171,190,'',0,0,0,0,'','ezboolean'),(108,'eng-GB',7,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',7,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"6\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-7-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',7,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',7,45,155,'content/edit/54',0,0,0,0,'content/edit/54','ezstring'),(108,'eng-GB',8,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',8,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"7\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB\"\n         url=\"var/intranet/storage/images/setup/setup_links/look_and_feel/109-8-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',8,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',8,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(389,'eng-GB',1,126,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(390,'eng-GB',1,126,123,'',0,0,0,0,'','ezboolean'),(391,'eng-GB',1,126,177,'',0,0,0,0,'','ezinteger'),(392,'eng-GB',1,127,1,'New article',0,0,0,0,'new article','ezstring'),(393,'eng-GB',1,127,120,'',1045487555,0,0,0,'','ezxmltext'),(394,'eng-GB',1,127,121,'',1045487555,0,0,0,'','ezxmltext'),(395,'eng-GB',1,127,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(396,'eng-GB',1,127,123,'',0,0,0,0,'','ezboolean'),(397,'eng-GB',1,127,177,'',0,0,0,0,'','ezinteger'),(152,'eng-GB',53,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop.gif\"\n         original_filename=\"qxllogo.gif\"\n         mime_type=\"original\"\n         width=\"196\"\n         height=\"67\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069165845\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"169\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069322201\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',53,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(770,'eng-GB',1,214,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(769,'eng-GB',1,214,4,'PC',0,0,0,0,'pc','ezstring'),(28,'eng-GB',2,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',2,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',2,14,12,'',0,0,0,0,'','ezuser'),(104,'eng-GB',8,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',8,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',9,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',9,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/look_and_feel/109-9-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(386,'eng-GB',1,126,1,'tt',0,0,0,0,'tt','ezstring'),(387,'eng-GB',1,126,120,'',1045487555,0,0,0,'','ezxmltext'),(388,'eng-GB',1,126,121,'',1045487555,0,0,0,'','ezxmltext'),(153,'eng-GB',54,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(150,'eng-GB',48,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',47,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',49,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',50,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',51,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',52,56,157,'My shop',0,0,0,0,'','ezinisetting'),(150,'eng-GB',53,56,157,'My shop',0,0,0,0,'','ezinisetting'),(674,'eng-GB',1,200,188,'test',0,0,0,0,'test','ezstring'),(641,'eng-GB',1,192,194,'',0,0,0,0,'','ezsubtreesubscription'),(642,'eng-GB',1,193,188,'',0,0,0,0,'','ezstring'),(643,'eng-GB',1,193,189,'',0,0,0,0,'','eztext'),(644,'eng-GB',1,193,190,'',0,0,0,0,'','ezboolean'),(645,'eng-GB',1,193,194,'',0,0,0,0,'','ezsubtreesubscription'),(646,'eng-GB',1,194,188,'',0,0,0,0,'','ezstring'),(647,'eng-GB',1,194,189,'',0,0,0,0,'','eztext'),(648,'eng-GB',1,194,190,'',0,0,0,0,'','ezboolean'),(649,'eng-GB',1,194,194,'',0,0,0,0,'','ezsubtreesubscription'),(764,'eng-GB',2,212,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdfgklj sdfgsdf</line>\n    <line>gsd</line>\n    <line>fgs</line>\n    <line>dfg</line>\n    <line>sdfg</line>\n    <line>sd</line>\n    <line>gsdf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',54,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop.gif\"\n         original_filename=\"qxllogo.gif\"\n         mime_type=\"original\"\n         width=\"196\"\n         height=\"67\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069165845\">\n  <original attribute_id=\"152\"\n            attribute_version=\"53\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069165849\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"170\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069416749\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',49,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB/my_shop.gif\"\n         original_filename=\"qxllogo.gif\"\n         mime_type=\"original\"\n         width=\"196\"\n         height=\"67\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"48\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-49-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"169\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(151,'eng-GB',49,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(669,'eng-GB',52,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(152,'eng-GB',47,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-47-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-47-eng-GB/my_shop.gif\"\n         original_filename=\"qxllogo.gif\"\n         mime_type=\"original\"\n         width=\"196\"\n         height=\"67\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"43\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-47-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-47-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-47-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-47-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',47,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',47,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',47,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',47,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',56,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',56,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',56,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',56,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',56,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(329,'eng-GB',2,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',2,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(151,'eng-GB',48,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(153,'eng-GB',55,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(152,'eng-GB',55,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop.gif\"\n         original_filename=\"shop.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069421350\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"184\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         timestamp=\"1069421433\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(867,'eng-GB',1,239,12,'',0,0,0,0,'','ezuser'),(154,'eng-GB',48,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',48,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',48,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(151,'eng-GB',47,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(102,'eng-GB',8,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',8,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/classes/103-8-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(152,'eng-GB',48,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"my_shop.gif\"\n         suffix=\"gif\"\n         basename=\"my_shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB/my_shop.gif\"\n         original_filename=\"qxllogo.gif\"\n         mime_type=\"original\"\n         width=\"196\"\n         height=\"67\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"152\"\n            attribute_version=\"47\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"my_shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB/my_shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"my_shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB/my_shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"196\"\n         height=\"67\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"logo\"\n         filename=\"my_shop_logo.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/my_shop/152-48-eng-GB/my_shop_logo.gif\"\n         mime_type=\"image/gif\"\n         width=\"169\"\n         height=\"58\"\n         alias_key=\"-447475028\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(153,'eng-GB',48,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(323,'eng-GB',3,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',3,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB\"\n         url=\"var/corporate/storage/images/setup/setup_links/cache/324-3-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',3,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',3,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(516,'eng-GB',2,160,1,'News bulletin October',0,0,0,0,'news bulletin october','ezstring'),(1,'eng-GB',8,1,4,'Shop',0,0,0,0,'shop','ezstring'),(2,'eng-GB',8,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(152,'eng-GB',56,56,159,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"shop.gif\"\n         suffix=\"gif\"\n         basename=\"shop\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop.gif\"\n         original_filename=\"shop.gif\"\n         mime_type=\"original\"\n         width=\"194\"\n         height=\"61\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069421350\">\n  <original attribute_id=\"152\"\n            attribute_version=\"55\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"shop_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"-1689502181\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"shop_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB\"\n         url=\"var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"194\"\n         height=\"61\"\n         alias_key=\"1446888826\"\n         timestamp=\"1069421352\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(775,'eng-GB',1,217,4,'LCD',0,0,0,0,'lcd','ezstring'),(771,'eng-GB',1,215,4,'Mac',0,0,0,0,'mac','ezstring'),(772,'eng-GB',1,215,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(773,'eng-GB',1,216,4,'Monitor',0,0,0,0,'monitor','ezstring'),(774,'eng-GB',1,216,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(760,'eng-GB',2,211,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"ipod.jpg\"\n         suffix=\"jpg\"\n         basename=\"ipod\"\n         dirpath=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB/ipod.jpg\"\n         original_filename=\"contact.jpg\"\n         mime_type=\"original\"\n         width=\"105\"\n         height=\"146\"\n         alternative_text=\"iPod\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"760\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"ipod_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB/ipod_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"432\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"ipod_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB/ipod_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"216\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"ipod_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB/ipod_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"144\"\n         height=\"200\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"ipod_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/ipod/760-2-eng-GB/ipod_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"72\"\n         height=\"100\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(458,'eng-GB',1,143,152,'',0,0,0,0,'','ezstring'),(459,'eng-GB',1,143,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(460,'eng-GB',1,143,154,'',0,0,0,0,'','eztext'),(461,'eng-GB',1,143,155,'',0,0,0,0,'','ezstring'),(462,'eng-GB',1,144,152,'',0,0,0,0,'','ezstring'),(463,'eng-GB',1,144,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(464,'eng-GB',1,144,154,'',0,0,0,0,'','eztext'),(465,'eng-GB',1,144,155,'',0,0,0,0,'','ezstring'),(466,'eng-GB',1,145,152,'',0,0,0,0,'','ezstring'),(467,'eng-GB',1,145,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"\"\n         is_valid=\"\"\n         filename=\"\"\n         suffix=\"\"\n         basename=\"\"\n         dirpath=\"\"\n         url=\"\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\" />',0,0,0,0,'','ezimage'),(468,'eng-GB',1,145,154,'',0,0,0,0,'','eztext'),(469,'eng-GB',1,145,155,'',0,0,0,0,'','ezstring'),(758,'eng-GB',2,211,202,'i100',0,0,0,0,'i100','ezstring'),(759,'eng-GB',2,211,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>MP3 player</header>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(912,'eng-GB',1,246,8,'',0,0,0,0,'','ezstring'),(913,'eng-GB',1,246,9,'',0,0,0,0,'','ezstring'),(914,'eng-GB',1,246,12,'',0,0,0,0,'','ezuser'),(518,'eng-GB',2,160,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>We release a new website. As you all can see it is a great step forward from the old site.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(154,'eng-GB',55,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',55,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',55,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',55,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring'),(919,'eng-GB',1,247,8,'',0,0,0,0,'','ezstring'),(920,'eng-GB',1,247,9,'',0,0,0,0,'','ezstring'),(921,'eng-GB',1,247,12,'',0,0,0,0,'','ezuser'),(517,'eng-GB',2,160,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Here are the latest news from this webshop. We will publish these news as soon as we have new products, new releases and important information to tell. </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',4,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',4,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>This folder contains some information about...</line>\n    <object id=\"49\" />\n  </paragraph>\n  <section>\n    <header>Music discussion</header>\n    <paragraph>\n      <object id=\"141\" />\n    </paragraph>\n  </section>\n  <section>\n    <header>Sports discussion</header>\n    <paragraph>\n      <object id=\"142\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(1,'eng-GB',5,1,4,'Forum',0,0,0,0,'forum','ezstring'),(2,'eng-GB',5,1,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Welcome to our community site</paragraph>\n  <paragraph>\n    <table>\n      <tr>\n        <td>\n          <section>\n            <header>Latest discussions in music</header>\n            <paragraph>\n              <object id=\"141\" />\n            </paragraph>\n          </section>\n        </td>\n        <td>\n          <section>\n            <header>Latest discussions in sports</header>\n            <paragraph>\n              <object id=\"142\" />\n            </paragraph>\n          </section>\n        </td>\n      </tr>\n    </table>\n  </paragraph>\n  <section>\n    <header>Latest news:</header>\n    <paragraph>\n      <object id=\"49\" />\n    </paragraph>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',4,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',4,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',4,14,12,'',0,0,0,0,'','ezuser'),(28,'eng-GB',5,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',5,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',5,14,12,'',0,0,0,0,'','ezuser'),(757,'eng-GB',1,211,201,'iPod',0,0,0,0,'ipod','ezstring'),(758,'eng-GB',1,211,202,'i100',0,0,0,0,'i100','ezstring'),(759,'eng-GB',1,211,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>MP3 player</header>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(760,'eng-GB',1,211,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"ipod.jpg\"\n         suffix=\"jpg\"\n         basename=\"ipod\"\n         dirpath=\"var/shop/storage/images/ipod/760-1-eng-GB\"\n         url=\"var/shop/storage/images/ipod/760-1-eng-GB/ipod.jpg\"\n         original_filename=\"contact.jpg\"\n         mime_type=\"original\"\n         width=\"105\"\n         height=\"146\"\n         alternative_text=\"iPod\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"ipod_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/ipod/760-1-eng-GB\"\n         url=\"var/shop/storage/images/ipod/760-1-eng-GB/ipod_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"432\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"ipod_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/ipod/760-1-eng-GB\"\n         url=\"var/shop/storage/images/ipod/760-1-eng-GB/ipod_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"216\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(761,'eng-GB',1,211,205,'',0,100,0,0,'','ezprice'),(762,'eng-GB',1,212,201,'Nokia G101',0,0,0,0,'nokia g101','ezstring'),(763,'eng-GB',1,212,202,'G101',0,0,0,0,'g101','ezstring'),(764,'eng-GB',1,212,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sdfgklj sdfgsdf</line>\n    <line>gsd</line>\n    <line>fgs</line>\n    <line>dfg</line>\n    <line>sdfg</line>\n    <line>sd</line>\n    <line>gsdf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(765,'eng-GB',1,212,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"nokia_g101.jpg\"\n         suffix=\"jpg\"\n         basename=\"nokia_g101\"\n         dirpath=\"var/shop/storage/images/nokia_g101/765-1-eng-GB\"\n         url=\"var/shop/storage/images/nokia_g101/765-1-eng-GB/nokia_g101.jpg\"\n         original_filename=\"dscn0009.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"nokia\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(766,'eng-GB',1,212,205,'',0,120,0,0,'','ezprice'),(776,'eng-GB',1,217,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext'),(777,'eng-GB',1,218,201,'compaq m2000',0,0,0,0,'compaq m2000','ezstring'),(778,'eng-GB',1,218,202,'23',0,0,0,0,'23','ezstring'),(779,'eng-GB',1,218,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sfsf</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(780,'eng-GB',1,218,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"compaq_m2000.gif\"\n         suffix=\"gif\"\n         basename=\"compaq_m2000\"\n         dirpath=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-1-eng-GB\"\n         url=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-1-eng-GB/compaq_m2000.gif\"\n         original_filename=\"bnr-microsoft.gif\"\n         mime_type=\"original\"\n         width=\"250\"\n         height=\"60\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(781,'eng-GB',1,218,205,'',0,300,0,0,'','ezprice'),(777,'eng-GB',2,218,201,'compaq m2000',0,0,0,0,'compaq m2000','ezstring'),(778,'eng-GB',2,218,202,'23',0,0,0,0,'23','ezstring'),(779,'eng-GB',2,218,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>sfsf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(780,'eng-GB',2,218,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"compaq_m2000.gif\"\n         suffix=\"gif\"\n         basename=\"compaq_m2000\"\n         dirpath=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB/compaq_m2000.gif\"\n         original_filename=\"bnr-microsoft.gif\"\n         mime_type=\"original\"\n         width=\"250\"\n         height=\"60\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"780\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"compaq_m2000_reference.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB/compaq_m2000_reference.gif\"\n         mime_type=\"image/gif\"\n         width=\"250\"\n         height=\"60\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"compaq_m2000_medium.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB/compaq_m2000_medium.gif\"\n         mime_type=\"image/gif\"\n         width=\"200\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"compaq_m2000_large.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB/compaq_m2000_large.gif\"\n         mime_type=\"image/gif\"\n         width=\"250\"\n         height=\"60\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"compaq_m2000_small.gif\"\n         suffix=\"gif\"\n         dirpath=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB\"\n         url=\"var/shop/storage/images/hardware/pc/compaq_m2000/780-2-eng-GB/compaq_m2000_small.gif\"\n         mime_type=\"image/gif\"\n         width=\"100\"\n         height=\"24\"\n         alias_key=\"-1588460780\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(781,'eng-GB',2,218,205,'',0,300,0,0,'','ezprice'),(523,'eng-GB',2,161,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(524,'eng-GB',2,161,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"shipping_and_returns.\"\n         suffix=\"\"\n         basename=\"shipping_and_returns\"\n         dirpath=\"var/shop/storage/images/shipping_and_returns/524-2-eng-GB\"\n         url=\"var/shop/storage/images/shipping_and_returns/524-2-eng-GB/shipping_and_returns.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"1\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(782,'eng-GB',1,219,140,'Privacy notice',0,0,0,0,'privacy notice','ezstring'),(783,'eng-GB',1,219,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</paragraph>\n  <paragraph>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In sit amet massa. Maecenas egestas, mauris sed adipiscing eleifend, nisl quam aliquam massa, in gravida diam wisi et nulla. Morbi odio. Proin massa est, dignissim eget, molestie a, tincidunt at, libero. Phasellus imperdiet, purus id iaculis volutpat, diam elit dapibus neque, ac blandit wisi metus eu turpis. Sed turpis eros, tristique in, tincidunt ut, facilisis sed, lorem. Aenean pharetra scelerisque tortor. Fusce in est. Pellentesque ullamcorper felis vel enim sagittis commodo. Sed commodo suscipit tellus.</paragraph>\n  <paragraph>\n    <line>Phasellus elementum, velit at vehicula accumsan, lacus nunc rhoncus lectus, et tempor magna mi vitae velit. Aliquam erat volutpat. In ut libero eget lorem vestibulum fermentum. Sed sed tellus ut diam nonummy fringilla. In hac habitasse platea dictumst. Duis diam. Aenean interdum. Sed scelerisque ornare dolor. Phasellus neque magna, ullamcorper id, tincidunt non, scelerisque ut, sapien. Nulla facilisi. Suspendisse vel wisi nec velit dapibus vestibulum. Mauris fringilla, mi a congue dapibus, lacus sem viverra quam, vel tristique lacus dolor consequat diam. Vestibulum et libero. Donec adipiscing sagittis diam. Nam bibendum dui porttitor lacus. Morbi dignissim. Integer tempor. Vestibulum est elit, cursus quis, laoreet vitae, interdum vel, diam</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(784,'eng-GB',1,219,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"privacy_notice.\"\n         suffix=\"\"\n         basename=\"privacy_notice\"\n         dirpath=\"var/shop/storage/images/privacy_notice/784-1-eng-GB\"\n         url=\"var/shop/storage/images/privacy_notice/784-1-eng-GB/privacy_notice.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"524\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n</ezimage>',0,0,0,0,'','ezimage'),(785,'eng-GB',1,220,140,'Conditions of use',0,0,0,0,'conditions of use','ezstring'),(786,'eng-GB',1,220,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...lorem ipsum...</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(787,'eng-GB',1,220,142,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"conditions_of_use.\"\n         suffix=\"\"\n         basename=\"conditions_of_use\"\n         dirpath=\"var/shop/storage/images/conditions_of_use/787-1-eng-GB\"\n         url=\"var/shop/storage/images/conditions_of_use/787-1-eng-GB/conditions_of_use.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(767,'eng-GB',2,213,4,'Products',0,0,0,0,'products','ezstring'),(768,'eng-GB',2,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Our products</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(28,'eng-GB',6,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',6,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',6,14,12,'',0,0,0,0,'','ezuser'),(28,'eng-GB',7,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(29,'eng-GB',7,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',7,14,12,'',0,0,0,0,'','ezuser'),(757,'eng-GB',3,211,201,'iPod',0,0,0,0,'ipod','ezstring'),(758,'eng-GB',3,211,202,'i100',0,0,0,0,'i100','ezstring'),(759,'eng-GB',3,211,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <section>\n    <header>MP3 player</header>\n  </section>\n</section>',1045487555,0,0,0,'','ezxmltext'),(760,'eng-GB',3,211,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"ipod.jpg\"\n         suffix=\"jpg\"\n         basename=\"ipod\"\n         dirpath=\"var/shop/storage/images/products/ipod/760-3-eng-GB\"\n         url=\"var/shop/storage/images/products/ipod/760-3-eng-GB/ipod.jpg\"\n         original_filename=\"contact.jpg\"\n         mime_type=\"original\"\n         width=\"105\"\n         height=\"146\"\n         alternative_text=\"iPod\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"760\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"ipod_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/ipod/760-3-eng-GB\"\n         url=\"var/shop/storage/images/products/ipod/760-3-eng-GB/ipod_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"432\"\n         height=\"600\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"ipod_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/ipod/760-3-eng-GB\"\n         url=\"var/shop/storage/images/products/ipod/760-3-eng-GB/ipod_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"216\"\n         height=\"300\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"ipod_medium.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/ipod/760-3-eng-GB\"\n         url=\"var/shop/storage/images/products/ipod/760-3-eng-GB/ipod_medium.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"144\"\n         height=\"200\"\n         alias_key=\"1874955560\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"ipod_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/ipod/760-3-eng-GB\"\n         url=\"var/shop/storage/images/products/ipod/760-3-eng-GB/ipod_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"72\"\n         height=\"100\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(791,'eng-GB',1,222,206,'Contact us',0,0,0,0,'contact us','ezstring'),(792,'eng-GB',1,222,207,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>Please contact us.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(793,'eng-GB',1,222,208,'',0,0,0,0,'','ezstring'),(794,'eng-GB',1,222,209,'',0,0,0,0,'','ezstring'),(795,'eng-GB',1,222,210,'',0,0,0,0,'','eztext'),(761,'eng-GB',3,211,205,'',0,100,0,0,'','ezprice'),(796,'eng-GB',1,223,201,'',0,0,0,0,'','ezstring'),(797,'eng-GB',1,223,202,'',0,0,0,0,'','ezstring'),(798,'eng-GB',1,223,203,'',1045487555,0,0,0,'','ezxmltext'),(799,'eng-GB',1,223,204,'',0,0,0,0,'','ezimage'),(800,'eng-GB',1,223,205,'',0,0,0,0,'','ezprice'),(801,'eng-GB',1,224,201,'',0,0,0,0,'','ezstring'),(802,'eng-GB',1,224,202,'',0,0,0,0,'','ezstring'),(803,'eng-GB',1,224,203,'',1045487555,0,0,0,'','ezxmltext'),(804,'eng-GB',1,224,204,'',0,0,0,0,'','ezimage'),(805,'eng-GB',1,224,205,'',0,0,0,0,'','ezprice'),(806,'eng-GB',1,225,201,'',0,0,0,0,'','ezstring'),(807,'eng-GB',1,225,202,'',0,0,0,0,'','ezstring'),(808,'eng-GB',1,225,203,'',1045487555,0,0,0,'','ezxmltext'),(809,'eng-GB',1,225,204,'',0,0,0,0,'','ezimage'),(810,'eng-GB',1,225,205,'',0,0,0,0,'','ezprice'),(811,'eng-GB',1,226,201,'',0,0,0,0,'','ezstring'),(812,'eng-GB',1,226,202,'',0,0,0,0,'','ezstring'),(813,'eng-GB',1,226,203,'',1045487555,0,0,0,'','ezxmltext'),(814,'eng-GB',1,226,204,'',0,0,0,0,'','ezimage'),(815,'eng-GB',1,226,205,'',0,0,0,0,'','ezprice'),(816,'eng-GB',1,227,201,'option test',0,0,0,0,'option test','ezstring'),(817,'eng-GB',1,227,202,'10200',0,0,0,0,'10200','ezstring'),(818,'eng-GB',1,227,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>gjksdfjkghsdjkf gsdjkgh sdkjgh sdjkf</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(819,'eng-GB',1,227,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"option_test.\"\n         suffix=\"\"\n         basename=\"option_test\"\n         dirpath=\"var/shop/storage/images/products/option_test/819-1-eng-GB\"\n         url=\"var/shop/storage/images/products/option_test/819-1-eng-GB/option_test.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(820,'eng-GB',1,227,205,'',0,123,0,0,'','ezprice'),(29,'eng-GB',8,14,9,'User',0,0,0,0,'user','ezstring'),(30,'eng-GB',8,14,12,'',0,0,0,0,'','ezuser'),(28,'eng-GB',8,14,8,'Administrator',0,0,0,0,'administrator','ezstring'),(875,'eng-GB',1,241,215,'testttt',0,0,0,0,'testttt','ezstring'),(876,'eng-GB',1,241,216,'revju',0,0,0,0,'','eztext'),(877,'eng-GB',1,241,217,'',0,0,0,0,'','ezinteger'),(878,'eng-GB',1,242,215,'ohoh',0,0,0,0,'ohoh','ezstring'),(879,'eng-GB',1,242,216,'hoho',0,0,0,0,'','eztext'),(880,'eng-GB',1,242,217,'',0,0,0,0,'','ezinteger'),(825,'eng-GB',1,228,201,'Compaq pressario',0,0,0,0,'compaq pressario','ezstring'),(826,'eng-GB',1,228,202,'p1001',0,0,0,0,'p1001','ezstring'),(827,'eng-GB',1,228,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd comdafg sdfg sdkjfgh sdfg sdfg sd </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(828,'eng-GB',1,228,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"compaq_pressario.jpg\"\n         suffix=\"jpg\"\n         basename=\"compaq_pressario\"\n         dirpath=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB/compaq_pressario.jpg\"\n         original_filename=\"dscn0001.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"compaq_pressario_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB/compaq_pressario_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"compaq_pressario_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB/compaq_pressario_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"compaq_pressario_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/compaq_pressario/828-1-eng-GB/compaq_pressario_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"225\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(829,'eng-GB',1,228,205,'',0,1249,0,0,'','ezprice'),(830,'eng-GB',1,229,201,'F100',0,0,0,0,'f100','ezstring'),(831,'eng-GB',1,229,202,'f1001',0,0,0,0,'f1001','ezstring'),(832,'eng-GB',1,229,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg asdfg sdjkflg sdjkg sdfg sdfg </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(833,'eng-GB',1,229,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"f100.jpg\"\n         suffix=\"jpg\"\n         basename=\"f100\"\n         dirpath=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB/f100.jpg\"\n         original_filename=\"dscn1653.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"f100_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB/f100_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"f100_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB/f100_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"f100_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/f100/833-1-eng-GB/f100_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"225\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(834,'eng-GB',1,229,205,'',0,1247,0,0,'','ezprice'),(835,'eng-GB',1,230,201,'P223498',0,0,0,0,'p223498','ezstring'),(836,'eng-GB',1,230,202,'c3po',0,0,0,0,'c3po','ezstring'),(837,'eng-GB',1,230,203,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  sdfgj sdfjkgh sdjkgh sdjkgf sdf  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(838,'eng-GB',1,230,204,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"p223498.jpg\"\n         suffix=\"jpg\"\n         basename=\"p223498\"\n         dirpath=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB/p223498.jpg\"\n         original_filename=\"dscn1646.jpg\"\n         mime_type=\"original\"\n         width=\"1024\"\n         height=\"768\"\n         alternative_text=\"COo\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"p223498_reference.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB/p223498_reference.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"600\"\n         height=\"450\"\n         alias_key=\"-294625821\"\n         is_valid=\"1\" />\n  <alias name=\"small\"\n         filename=\"p223498_small.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB/p223498_small.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"100\"\n         height=\"75\"\n         alias_key=\"-164556570\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"p223498_large.jpg\"\n         suffix=\"jpg\"\n         dirpath=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB\"\n         url=\"var/shop/storage/images/products/pc/p223498/838-1-eng-GB/p223498_large.jpg\"\n         mime_type=\"image/jpeg\"\n         width=\"300\"\n         height=\"225\"\n         alias_key=\"924963484\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(839,'eng-GB',1,230,205,'',0,21347,0,0,'','ezprice'),(843,'eng-GB',2,232,216,'It had a good beat and I can dance to it! Further, the graphics were pretty cool on its website.',0,0,0,0,'','eztext'),(842,'eng-GB',1,232,215,'Good',0,0,0,0,'good','ezstring'),(843,'eng-GB',1,232,216,'It had a good beat and I can dance to it! Further, the graphics were pretty cool on its website.\n',0,0,0,0,'','eztext'),(844,'eng-GB',1,232,217,'',3,0,0,3,'','ezinteger'),(842,'eng-GB',2,232,215,'Good',0,0,0,0,'good','ezstring'),(844,'eng-GB',2,232,217,'',3,0,0,3,'','ezinteger'),(153,'eng-GB',49,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',49,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',49,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',49,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(153,'eng-GB',50,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',50,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',50,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',50,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(767,'eng-GB',3,213,4,'Products',0,0,0,0,'products','ezstring'),(768,'eng-GB',3,213,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>\n    <line>Our products</line>\n  </paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(842,'eng-GB',3,232,215,'Good',0,0,0,0,'good','ezstring'),(843,'eng-GB',3,232,216,'It had a good beat and I can dance to it! Further, the graphics were pretty cool on its website.',0,0,0,0,'','eztext'),(844,'eng-GB',3,232,217,'',3,0,0,3,'','ezinteger'),(153,'eng-GB',51,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',51,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',51,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',51,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(854,'eng-GB',1,235,216,'I won\'t claim this _is_ the best expansion pack (mostly since House Party and Livin\' Large are distant memories for me), but I will go so far as to say that Makin\' Magic is the best in recent memory. I\'ve been a huge critic of the Sims franchise (largely as a result of TSO) in recent months, this expansion goes a long way toward answering my two chief concerns: that Maxis has lost sight of their fan base, and that additional game play options were needed in the stand alone game. So I am pleasantly suprised that Makin\' Magic responds to the cries of players who want a simpler lifestyle for their Sims, much less that it did it by providing game play options. Tom and Barbara Goode can finally make their debut in my neighborhood as they were meant to--as a self-sufficent couple living off the land (by making and selling nectar, butter, bread, and beeswax). :)\n\nOkay, so we _still_ don\'t have foundations and gable roofs, but the Sims 2 preview disk claims those features are coming in the new game. Wow. I\'m left with nothing to complain about. Would that Maxis had done the same with TSO. At any rate, the offline franchise seems to have faired much better than its on-line counterpart, and seems fated to be with us for many years to come. Kudos to Maxis for this one!\n\nhttp://ez.no\n',0,0,0,0,'','eztext'),(853,'eng-GB',1,235,215,'The Best Expansion Pack?',0,0,0,0,'the best expansion pack?','ezstring'),(323,'eng-GB',4,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',4,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache.png\"\n         original_filename=\"gnome-ccperiph.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"324\"\n            attribute_version=\"3\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"cache_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-4-eng-GB/cache_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',4,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',4,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(102,'eng-GB',9,43,152,'Classes',0,0,0,0,'classes','ezstring'),(103,'eng-GB',9,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes.png\"\n         original_filename=\"gnome-settings.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"103\"\n            attribute_version=\"8\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"classes_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-9-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(104,'eng-GB',9,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',9,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(108,'eng-GB',10,45,152,'Look and feel',0,0,0,0,'look and feel','ezstring'),(109,'eng-GB',10,45,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"look_and_feel.png\"\n         suffix=\"png\"\n         basename=\"look_and_feel\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel.png\"\n         original_filename=\"gnome-color-browser.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"109\"\n            attribute_version=\"9\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"look_and_feel_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"look_and_feel_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n  <alias name=\"icon\"\n         filename=\"look_and_feel_icon.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_icon.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1993047904\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"look_and_feel_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/look_and_feel/109-10-eng-GB/look_and_feel_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(110,'eng-GB',10,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',10,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(327,'eng-GB',3,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',3,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator.png\"\n         original_filename=\"gnome-globe.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"328\"\n            attribute_version=\"2\"\n            attribute_language=\"eng-GB\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"url_translator_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-3-eng-GB/url_translator_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(329,'eng-GB',3,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',3,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(103,'eng-GB',10,43,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"classes.png\"\n         suffix=\"png\"\n         basename=\"classes\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB/classes.png\"\n         original_filename=\"classes.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"classes_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"medium\"\n         filename=\"classes_medium.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/classes/103-10-eng-GB/classes_medium.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"1446888826\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(102,'eng-GB',10,43,152,'Classes',0,0,0,0,'classes','ezstring'),(104,'eng-GB',10,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',10,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(104,'eng-GB',11,43,154,'',0,0,0,0,'','eztext'),(105,'eng-GB',11,43,155,'class/grouplist',0,0,0,0,'class/grouplist','ezstring'),(323,'eng-GB',5,115,152,'Cache',0,0,0,0,'cache','ezstring'),(324,'eng-GB',5,115,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"cache.png\"\n         suffix=\"png\"\n         basename=\"cache\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache.png\"\n         original_filename=\"cache.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"cache_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"cache_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/cache/324-5-eng-GB/cache_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(327,'eng-GB',4,116,152,'URL translator',0,0,0,0,'url translator','ezstring'),(328,'eng-GB',4,116,153,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"1\"\n         filename=\"url_translator.png\"\n         suffix=\"png\"\n         basename=\"url_translator\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator.png\"\n         original_filename=\"url_translator.png\"\n         mime_type=\"original\"\n         width=\"48\"\n         height=\"48\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n  <alias name=\"reference\"\n         filename=\"url_translator_reference.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_reference.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1689502181\"\n         is_valid=\"1\" />\n  <alias name=\"large\"\n         filename=\"url_translator_large.png\"\n         suffix=\"png\"\n         dirpath=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB\"\n         url=\"var/shop/storage/images/setup/setup_links/url_translator/328-4-eng-GB/url_translator_large.png\"\n         mime_type=\"image/png\"\n         width=\"48\"\n         height=\"48\"\n         alias_key=\"-1095359119\"\n         is_valid=\"1\" />\n</ezimage>',0,0,0,0,'','ezimage'),(325,'eng-GB',5,115,154,'',0,0,0,0,'','eztext'),(326,'eng-GB',5,115,155,'setup/cache',0,0,0,0,'setup/cache','ezstring'),(110,'eng-GB',11,45,154,'',0,0,0,0,'','eztext'),(111,'eng-GB',11,45,155,'content/edit/56',0,0,0,0,'content/edit/56','ezstring'),(329,'eng-GB',4,116,154,'',0,0,0,0,'','eztext'),(330,'eng-GB',4,116,155,'content/urltranslator/',0,0,0,0,'content/urltranslator/','ezstring'),(855,'eng-GB',1,235,217,'',5,0,0,5,'','ezinteger'),(856,'eng-GB',1,236,215,'Whimper',0,0,0,0,'whimper','ezstring'),(857,'eng-GB',1,236,216,'The action isn\'t bad but the unseen sidekick (guiding you through the levels only verbally through the speakers) who sounds like one of the developers...HINT: Not a professional voiceover actor...is just plain annoying. You can\'t turn him off! The levels and graphics aren\'t anything to blow you away but die-hard fans may get a kick out of it. ',0,0,0,0,'','eztext'),(858,'eng-GB',1,236,217,'',0,0,0,0,'','ezinteger'),(859,'eng-GB',1,237,215,'An Utter Disappointment',0,0,0,0,'an utter disappointment','ezstring'),(860,'eng-GB',1,237,216,'I bought this game right when it came out for full pop, expecting the rich storyline, innovative gameplay and mindblowing graphics innovations of the first one.\n\nWhat I got was a poorly written jumble of FPS cliches tied together with a pretty attractive gaming engine. The game felt like it wanted to be Halo without the talented writing and edge of your seat gameplay.\n\n\nThe single replay value I found in the game was the level where you are defending against an onslaught of enemies, and get to plan your defense. That was not only fun and challenging, but offered multiple paths to success. If only the the developers had focused on that sort of gameplay instead of rehashing every bad FPS cliche from all the game of the past 5 years, this might have been a game worthy of the kind of replay the first Unreal has seen (I\'ve played through it over a dozen times, usually when I upgraded my machine).\n\n\nAs it stands Unreal II will fade into the past as not a terrible game (Daikatana stands at the top of that heap, or perhaps its bottom) but as a mediocre game that could have been great. \n',0,0,0,0,'','eztext'),(861,'eng-GB',1,237,217,'',1,0,0,1,'','ezinteger'),(862,'eng-GB',1,238,215,'asdfasdf',0,0,0,0,'asdfasdf','ezstring'),(863,'eng-GB',1,238,216,'asdfasdf',0,0,0,0,'','eztext'),(864,'eng-GB',1,238,217,'',0,0,0,0,'','ezinteger'),(865,'eng-GB',1,239,8,'Test',0,0,0,0,'test','ezstring'),(866,'eng-GB',1,239,9,'Testersen',0,0,0,0,'testersen','ezstring'),(153,'eng-GB',53,56,160,'shop_blue',0,0,0,0,'shop_blue','ezpackage'),(154,'eng-GB',53,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',53,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',53,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(941,'eng-GB',1,250,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>This month started off with the release of two new products. Product A and Product B.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(942,'eng-GB',1,250,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\">\n  <paragraph>They are both part of a new product portfolio that will be the basis of this shop. There will be examples on products like this in many different categories. </paragraph>\n  <paragraph>In these categories you can add as many products you like, set prices and write product texts. You should also always add pictures of the product so that the users can see the product they are reading about.</paragraph>\n</section>',1045487555,0,0,0,'','ezxmltext'),(943,'eng-GB',1,250,122,'<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<ezimage serial_number=\"1\"\n         is_valid=\"\"\n         filename=\"news_bulletin_november.\"\n         suffix=\"\"\n         basename=\"news_bulletin_november\"\n         dirpath=\"var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB\"\n         url=\"var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB/news_bulletin_november.\"\n         original_filename=\"\"\n         mime_type=\"\"\n         width=\"\"\n         height=\"\"\n         alternative_text=\"\"\n         alias_key=\"1293033771\"\n         timestamp=\"1069686831\">\n  <original attribute_id=\"\"\n            attribute_version=\"\"\n            attribute_language=\"\" />\n</ezimage>',0,0,0,0,'','ezimage'),(944,'eng-GB',1,250,123,'',0,0,0,0,'','ezboolean'),(945,'eng-GB',1,250,177,'',0,0,0,0,'','ezinteger'),(872,'eng-GB',1,240,215,'Ttttiille',0,0,0,0,'ttttiille','ezstring'),(873,'eng-GB',1,240,216,'this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... this is .... ',0,0,0,0,'','eztext'),(874,'eng-GB',1,240,217,'',0,0,0,0,'','ezinteger'),(906,'eng-GB',48,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(905,'eng-GB',47,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(150,'eng-GB',55,56,157,'My shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',55,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',54,56,157,'My shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',54,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(150,'eng-GB',56,56,157,'Shop',0,0,0,0,'','ezinisetting'),(151,'eng-GB',56,56,158,'author=eZ systems package team\ncopyright=eZ systems\ndescription=Content Management System and stuff\nkeywords=cms',0,0,0,0,'','ezinisetting'),(895,'eng-GB',1,245,8,'',0,0,0,0,'','ezstring'),(896,'eng-GB',1,245,9,'',0,0,0,0,'','ezstring'),(897,'eng-GB',1,245,12,'',0,0,0,0,'','ezuser'),(907,'eng-GB',49,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(908,'eng-GB',50,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(909,'eng-GB',51,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(910,'eng-GB',52,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(911,'eng-GB',53,56,218,'Copyright &copy;',0,0,0,0,'copyright &copy;','ezstring'),(154,'eng-GB',54,56,161,'shop_package',0,0,0,0,'shop_package','ezstring'),(437,'eng-GB',54,56,180,'wy@ez.no',0,0,0,0,'','ezinisetting'),(669,'eng-GB',54,56,196,'ez.no',0,0,0,0,'','ezinisetting'),(911,'eng-GB',54,56,218,'Copyright &copy; eZ systems as 1999-2003',0,0,0,0,'copyright &copy; ez systems as 1999-2003','ezstring');
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
INSERT INTO ezcontentobject_link VALUES (1,1,4,49),(10,1,8,49),(8,1,7,49),(4,1,5,49),(9,211,3,212),(7,1,6,49);
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
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB'),(42,'Setup',1,'eng-GB','eng-GB'),(43,'Classes',1,'eng-GB','eng-GB'),(44,'Setup links',1,'eng-GB','eng-GB'),(43,'Classes',2,'eng-GB','eng-GB'),(43,'Classes',3,'eng-GB','eng-GB'),(43,'Classes',4,'eng-GB','eng-GB'),(43,'Classes',5,'eng-GB','eng-GB'),(45,'Setup Objects',1,'eng-GB','eng-GB'),(46,'Fonts and colors',1,'eng-GB','eng-GB'),(46,'Look and feel',2,'eng-GB','eng-GB'),(47,'New Template look',1,'eng-GB','eng-GB'),(116,'URL translator',2,'eng-GB','eng-GB'),(126,'New Article',1,'eng-GB','eng-GB'),(49,'News',1,'eng-GB','eng-GB'),(56,'Corporate',37,'eng-GB','eng-GB'),(211,'iPod',3,'eng-GB','eng-GB'),(45,'Look and feel',7,'eng-GB','eng-GB'),(51,'New Setup link',1,'eng-GB','eng-GB'),(45,'Look and feel',8,'eng-GB','eng-GB'),(53,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',2,'eng-GB','eng-GB'),(56,'Intranet',1,'eng-GB','eng-GB'),(56,'Intranet',3,'eng-GB','eng-GB'),(56,'Intranet',4,'eng-GB','eng-GB'),(56,'Intranet',5,'eng-GB','eng-GB'),(56,'Intranet',6,'eng-GB','eng-GB'),(43,'Classes',8,'eng-GB','eng-GB'),(165,'',1,'eng-GB','eng-GB'),(223,'New Product',1,'eng-GB','eng-GB'),(224,'New Product',1,'eng-GB','eng-GB'),(56,'Corporate',36,'eng-GB','eng-GB'),(161,'About this forum',1,'eng-GB','eng-GB'),(56,'Intranetyy',30,'eng-GB','eng-GB'),(56,'Intranet',25,'eng-GB','eng-GB'),(56,'Intranet',24,'eng-GB','eng-GB'),(127,'New Article',1,'eng-GB','eng-GB'),(56,'Intranet',22,'eng-GB','eng-GB'),(56,'Intranet',23,'eng-GB','eng-GB'),(56,'Corporate',35,'eng-GB','eng-GB'),(122,'New Image',1,'eng-GB','eng-GB'),(45,'Look and feel',9,'eng-GB','eng-GB'),(56,'Intranet',7,'eng-GB','eng-GB'),(56,'Intranet',8,'eng-GB','eng-GB'),(56,'Intranet',9,'eng-GB','eng-GB'),(56,'Corporate',38,'eng-GB','eng-GB'),(56,'Intranet',10,'eng-GB','eng-GB'),(83,'New User',1,'eng-GB','eng-GB'),(84,'New User',1,'eng-GB','eng-GB'),(56,'Intranet',11,'eng-GB','eng-GB'),(85,'New Folder',1,'eng-GB','eng-GB'),(87,'New Company',1,'eng-GB','eng-GB'),(88,'New User',1,'eng-GB','eng-GB'),(56,'Corporate',33,'eng-GB','eng-GB'),(56,'Intranetyy',31,'eng-GB','eng-GB'),(56,'Corporate',32,'eng-GB','eng-GB'),(56,'Intranet',12,'eng-GB','eng-GB'),(56,'Intranet',13,'eng-GB','eng-GB'),(91,'New Template look',1,'eng-GB','eng-GB'),(56,'Intranet',18,'eng-GB','eng-GB'),(212,'Nokia G101',2,'eng-GB','eng-GB'),(214,'PC',1,'eng-GB','eng-GB'),(161,'Shipping and returns',2,'eng-GB','eng-GB'),(56,'Corporate',39,'eng-GB','eng-GB'),(169,'test',1,'eng-GB','eng-GB'),(96,'New User',1,'eng-GB','eng-GB'),(213,'Hardware',1,'eng-GB','eng-GB'),(168,'',1,'eng-GB','eng-GB'),(222,'Contact us',1,'eng-GB','eng-GB'),(56,'Corporate',34,'eng-GB','eng-GB'),(56,'Intranet',20,'eng-GB','eng-GB'),(160,'News bulletin',1,'eng-GB','eng-GB'),(103,'New User',1,'eng-GB','eng-GB'),(104,'New User',1,'eng-GB','eng-GB'),(227,'option test',1,'eng-GB','eng-GB'),(105,'New User',1,'eng-GB','eng-GB'),(106,'New User',1,'eng-GB','eng-GB'),(107,'John Doe',1,'eng-GB','eng-GB'),(107,'John Doe',2,'eng-GB','eng-GB'),(1,'Corporate',2,'eng-GB','eng-GB'),(111,'vid la',1,'eng-GB','eng-GB'),(43,'Classes',6,'eng-GB','eng-GB'),(45,'Setup Objects',2,'eng-GB','eng-GB'),(43,'Classes',7,'eng-GB','eng-GB'),(45,'Setup Objects',3,'eng-GB','eng-GB'),(115,'Cache',1,'eng-GB','eng-GB'),(45,'Setup Objects',4,'eng-GB','eng-GB'),(116,'URL translator',1,'eng-GB','eng-GB'),(117,'New Article',1,'eng-GB','eng-GB'),(45,'Look and feel',5,'eng-GB','eng-GB'),(45,'Look and feel',6,'eng-GB','eng-GB'),(56,'Intranet',19,'eng-GB','eng-GB'),(115,'Cache',2,'eng-GB','eng-GB'),(56,'Intranet',21,'eng-GB','eng-GB'),(115,'Cache',3,'eng-GB','eng-GB'),(56,'Intranet',26,'eng-GB','eng-GB'),(56,'Intranetyy',27,'eng-GB','eng-GB'),(56,'Intranetyy',28,'eng-GB','eng-GB'),(129,'New Article',1,'eng-GB','eng-GB'),(56,'Intranetyy',29,'eng-GB','eng-GB'),(56,'Corporate',41,'eng-GB','eng-GB'),(56,'Corporate',42,'eng-GB','eng-GB'),(56,'Corporate',40,'eng-GB','eng-GB'),(1,'Forum',3,'eng-GB','eng-GB'),(56,'Forum',45,'eng-GB','eng-GB'),(229,'F100',1,'eng-GB','eng-GB'),(228,'Compaq pressario',1,'eng-GB','eng-GB'),(216,'Monitor',1,'eng-GB','eng-GB'),(143,'New Setup link',1,'eng-GB','eng-GB'),(144,'New Setup link',1,'eng-GB','eng-GB'),(145,'New Setup link',1,'eng-GB','eng-GB'),(56,'Forum',44,'eng-GB','eng-GB'),(215,'Mac',1,'eng-GB','eng-GB'),(1,'Forum',6,'eng-GB','eng-GB'),(211,'iPod',2,'eng-GB','eng-GB'),(14,'Administrator User',2,'eng-GB','eng-GB'),(226,'New Product',1,'eng-GB','eng-GB'),(225,'New Product',1,'eng-GB','eng-GB'),(171,'',1,'eng-GB','eng-GB'),(172,'',1,'eng-GB','eng-GB'),(173,'',1,'eng-GB','eng-GB'),(174,'',1,'eng-GB','eng-GB'),(175,'',1,'eng-GB','eng-GB'),(176,'',1,'eng-GB','eng-GB'),(177,'',1,'eng-GB','eng-GB'),(178,'',1,'eng-GB','eng-GB'),(179,'',1,'eng-GB','eng-GB'),(180,'',1,'eng-GB','eng-GB'),(181,'',1,'eng-GB','eng-GB'),(182,'',1,'eng-GB','eng-GB'),(183,'',1,'eng-GB','eng-GB'),(184,'',1,'eng-GB','eng-GB'),(185,'',1,'eng-GB','eng-GB'),(186,'New Forum topic',1,'eng-GB','eng-GB'),(187,'New User',1,'eng-GB','eng-GB'),(189,'test2 test2',1,'eng-GB','eng-GB'),(160,'News bulletin October',2,'eng-GB','eng-GB'),(191,'',1,'eng-GB','eng-GB'),(192,'',1,'eng-GB','eng-GB'),(193,'',1,'eng-GB','eng-GB'),(194,'New Forum topic',1,'eng-GB','eng-GB'),(220,'Conditions of use',1,'eng-GB','eng-GB'),(1,'Shop',8,'eng-GB','eng-GB'),(56,'Forum',46,'eng-GB','eng-GB'),(200,'test',1,'eng-GB','eng-GB'),(201,'Re:test',1,'eng-GB','eng-GB'),(213,'Products',2,'eng-GB','eng-GB'),(219,'Privacy notice',1,'eng-GB','eng-GB'),(219,'Shipping and returns',0,'eng-GB','eng-GB'),(218,'compaq m2000',2,'eng-GB','eng-GB'),(1,'Shop',7,'eng-GB','eng-GB'),(14,'Administrator User',3,'eng-GB','eng-GB'),(14,'Administrator User',4,'eng-GB','eng-GB'),(206,'Bård Farstad',1,'eng-GB','eng-GB'),(14,'Administrator User',6,'eng-GB','eng-GB'),(14,'Administrator User',7,'eng-GB','eng-GB'),(249,'New User',1,'eng-GB','eng-GB'),(248,'New User',1,'eng-GB','eng-GB'),(247,'New User',1,'eng-GB','eng-GB'),(56,'My shop',55,'eng-GB','eng-GB'),(246,'New User',1,'eng-GB','eng-GB'),(1,'Forum',4,'eng-GB','eng-GB'),(1,'Forum',5,'eng-GB','eng-GB'),(218,'compaq m2000',1,'eng-GB','eng-GB'),(14,'Administrator User',5,'eng-GB','eng-GB'),(217,'LCD',1,'eng-GB','eng-GB'),(211,'iPod',1,'eng-GB','eng-GB'),(212,'Nokia G101',1,'eng-GB','eng-GB'),(230,'P223498',1,'eng-GB','eng-GB'),(56,'My shop',43,'eng-GB','eng-GB'),(56,'My shop',47,'eng-GB','eng-GB'),(56,'My shop',48,'eng-GB','eng-GB'),(232,'Good',1,'eng-GB','eng-GB'),(232,'Good',2,'eng-GB','eng-GB'),(56,'My shop',49,'eng-GB','eng-GB'),(56,'My shop',50,'eng-GB','eng-GB'),(232,'Good',3,'eng-GB','eng-GB'),(56,'My shop',51,'eng-GB','eng-GB'),(115,'Cache',4,'eng-GB','eng-GB'),(43,'Classes',9,'eng-GB','eng-GB'),(45,'Look and feel',10,'eng-GB','eng-GB'),(116,'URL translator',3,'eng-GB','eng-GB'),(115,'Cache',5,'eng-GB','eng-GB'),(43,'Classes',10,'eng-GB','eng-GB'),(43,'Classes',11,'eng-GB','eng-GB'),(45,'Look and feel',11,'eng-GB','eng-GB'),(116,'URL translator',4,'eng-GB','eng-GB'),(56,'My shop',52,'eng-GB','eng-GB'),(235,'The Best Expansion Pack?',1,'eng-GB','eng-GB'),(236,'Whimper',1,'eng-GB','eng-GB'),(237,'An Utter Disappointment',1,'eng-GB','eng-GB'),(238,'asdfasdf',1,'eng-GB','eng-GB'),(56,'My shop',53,'eng-GB','eng-GB'),(239,'Test Testersen',1,'eng-GB','eng-GB'),(240,'Ttttiille',1,'eng-GB','eng-GB'),(14,'Administrator User',8,'eng-GB','eng-GB'),(241,'testttt',1,'eng-GB','eng-GB'),(242,'ohoh',1,'eng-GB','eng-GB'),(243,'New User',1,'eng-GB','eng-GB'),(244,'New User',1,'eng-GB','eng-GB'),(245,'New User',1,'eng-GB','eng-GB'),(56,'My shop',54,'eng-GB','eng-GB'),(250,'News bulletin November',1,'eng-GB','eng-GB'),(56,'Shop',56,'eng-GB','eng-GB');
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
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,8,1,1,'/1/2/',8,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,7,1,3,'/1/5/13/15/',9,1,0,'users/administrator_users/administrator_user',15),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43),(44,1,42,1,1,1,'/1/44/',9,1,0,'setup',44),(45,46,43,11,1,3,'/1/44/46/45/',9,1,0,'setup/setup_links/classes',45),(46,44,44,1,1,2,'/1/44/46/',9,1,0,'setup/setup_links',46),(47,46,45,11,1,3,'/1/44/46/47/',9,1,0,'setup/setup_links/look_and_feel',47),(48,44,46,2,1,2,'/1/44/48/',9,1,0,'setup/look_and_feel',48),(50,2,49,1,1,2,'/1/2/50/',9,1,1,'news',50),(54,48,56,56,1,3,'/1/44/48/54/',9,1,0,'setup/look_and_feel/shop',54),(126,50,160,2,1,3,'/1/2/50/126/',9,1,0,'news/news_bulletin_october',126),(127,2,161,2,1,2,'/1/2/127/',9,1,5,'shipping_and_returns',127),(176,50,250,1,1,3,'/1/2/50/176/',9,1,0,'news/news_bulletin_november',176),(91,14,107,2,1,3,'/1/5/14/91/',9,1,0,'users/editors/john_doe',91),(92,14,111,1,1,3,'/1/5/14/92/',9,1,0,'users/editors/vid_la',92),(154,2,213,2,1,2,'/1/2/154/',9,1,2,'products',154),(95,46,115,5,1,3,'/1/44/46/95/',9,1,0,'setup/setup_links/cache',95),(96,46,116,4,1,3,'/1/44/46/96/',9,1,0,'setup/setup_links/url_translator',96),(165,155,228,1,1,4,'/1/2/154/155/165/',9,1,0,'products/pc/compaq_pressario',165),(166,155,229,1,1,4,'/1/2/154/155/166/',9,1,0,'products/pc/f100',166),(167,155,230,1,1,4,'/1/2/154/155/167/',9,1,0,'products/pc/p223498',167),(168,153,232,3,1,4,'/1/2/154/153/168/',9,1,0,'products/nokia_g101/good',168),(169,153,235,1,1,4,'/1/2/154/153/169/',1,1,0,'products/nokia_g101/the_best_expansion_pack',169),(170,153,236,1,1,4,'/1/2/154/153/170/',1,1,0,'products/nokia_g101/whimper',170),(171,153,237,1,1,4,'/1/2/154/153/171/',1,1,0,'products/nokia_g101/an_utter_disappointment',171),(155,154,214,1,1,3,'/1/2/154/155/',9,1,0,'products/pc',155),(156,154,215,1,1,3,'/1/2/154/156/',9,1,0,'products/mac',156),(157,155,216,1,1,4,'/1/2/154/155/157/',9,1,0,'products/pc/monitor',157),(158,157,217,1,1,5,'/1/2/154/155/157/158/',9,1,0,'products/pc/monitor/lcd',158),(159,155,218,2,1,4,'/1/2/154/155/159/',9,1,0,'products/pc/compaq_m2000',159),(160,2,219,1,1,2,'/1/2/160/',9,1,3,'privacy_notice',160),(161,2,220,1,1,2,'/1/2/161/',9,1,4,'conditions_of_use',161),(164,154,227,1,1,3,'/1/2/154/164/',9,1,0,'products/option_test',164),(163,2,222,1,1,2,'/1/2/163/',9,1,6,'contact_us__1',163),(145,13,206,1,1,3,'/1/5/13/145/',9,1,0,'users/administrator_users/brd_farstad',145),(174,167,241,1,1,5,'/1/2/154/155/167/174/',1,1,0,'products/pc/p223498/testttt',174),(175,167,242,1,1,5,'/1/2/154/155/167/175/',1,1,0,'products/pc/p223498/ohoh',175),(172,153,238,1,1,4,'/1/2/154/153/172/',1,1,0,'products/nokia_g101/asdfasdf',172),(173,12,239,1,1,3,'/1/5/12/173/',1,1,0,'users/guest_accounts/test_testersen',173),(152,154,211,3,1,3,'/1/2/154/152/',9,1,0,'products/ipod',152),(153,154,212,2,1,3,'/1/2/154/153/',9,1,0,'products/nokia_g101',153);
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
INSERT INTO ezcontentobject_version VALUES (800,1,14,6,1068473139,1068473148,3,1,0),(4,4,14,1,0,0,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(824,227,14,1,1068557677,1068557743,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0),(473,42,14,1,1066383039,1066383068,1,0,0),(847,43,14,11,1068640411,1068640429,1,0,0),(475,44,14,1,1066384403,1066384457,1,0,0),(476,43,14,2,1066384496,1066384561,3,0,0),(477,43,14,3,1066387640,1066387690,3,0,0),(478,43,14,4,1066388115,1066388183,3,0,0),(479,43,14,5,1066388613,1066388707,3,0,0),(849,45,14,11,1068640482,1068640502,1,0,0),(481,46,14,1,1066389789,1066389805,3,0,0),(482,46,14,2,1066389882,1066389902,1,0,0),(650,126,14,1,1067008555,1067008788,0,0,0),(829,56,14,47,1068564149,1068565446,3,0,0),(490,49,14,1,1066398007,1066398020,1,0,0),(631,45,14,7,1067002652,1067002675,3,0,0),(741,175,149,1,1068108534,1068108624,0,0,0),(664,129,14,1,1067344356,1067344356,0,0,0),(867,56,14,55,1069420922,1069421350,3,0,0),(620,115,14,2,1066997200,1066997221,3,0,0),(734,168,149,1,1068048359,1068048594,0,0,0),(859,241,14,1,1069328679,1069328695,1,0,0),(826,228,14,1,1068562944,1068562986,1,0,0),(731,165,149,1,1068048190,1068048359,0,0,0),(724,160,14,1,1068047416,1068047455,3,0,0),(832,232,14,1,1068566678,1068567343,3,0,0),(683,45,14,9,1067950316,1067950326,3,0,0),(682,43,14,8,1067950294,1067950307,3,0,0),(681,115,14,3,1067950253,1067950265,3,0,0),(851,56,14,52,1068640667,1068640675,3,0,0),(725,161,14,1,1068047518,1068047603,3,0,0),(865,56,14,54,1069416400,1069416424,3,0,0),(830,56,14,48,1068565455,1068565485,3,0,0),(740,174,149,1,1068050123,1068108534,0,0,0),(856,56,14,53,1069165818,1069165845,3,0,0),(651,127,14,1,1067243907,1067245036,0,0,0),(619,45,14,6,1066995597,1066996371,3,0,0),(835,56,14,50,1068628662,1068628685,3,0,0),(842,43,14,9,1068639982,1068639989,3,0,0),(684,116,14,2,1067950335,1067950343,3,0,0),(844,116,14,3,1068640009,1068640016,3,0,0),(739,173,149,1,1068050088,1068050123,0,0,0),(803,212,14,2,1068473282,1068473309,1,0,0),(836,213,14,3,1068629484,1068629484,0,0,0),(738,172,149,1,1068049706,1068050088,0,0,0),(735,169,149,1,1068048594,1068048622,0,0,0),(834,56,14,49,1068628412,1068628438,3,0,0),(802,211,14,2,1068473243,1068473266,3,0,0),(737,171,149,1,1068049618,1068049706,0,0,0),(838,56,14,51,1068634614,1068634626,3,0,0),(632,45,14,8,1067002781,1067002791,3,0,0),(828,230,14,1,1068563047,1068563079,1,0,0),(827,229,14,1,1068563000,1068563029,1,0,0),(598,107,14,1,1066916843,1066916865,3,0,0),(599,107,14,2,1066916931,1066916941,1,0,0),(810,1,14,7,1068542616,1068542626,3,1,0),(604,111,14,1,1066917488,1066917523,1,0,0),(873,56,14,56,1069686954,1069687079,1,0,0),(668,49,14,2,1067357193,1067357193,0,0,0),(609,43,14,6,1066989725,1066989762,3,0,0),(610,45,14,2,1066989773,1066989792,3,0,0),(611,43,14,7,1066989980,1066990055,3,0,0),(612,45,14,3,1066990063,1066990178,3,0,0),(613,115,14,1,1066991569,1066991725,3,0,0),(614,45,14,4,1066991894,1066991945,3,0,0),(615,116,14,1,1066992008,1066992053,3,0,0),(616,45,14,5,1066992186,1066992656,3,0,0),(871,1,14,8,1069686109,1069686122,1,1,0),(846,43,14,10,1068640261,1068640329,3,0,0),(843,45,14,10,1068639995,1068640002,3,0,0),(805,215,14,1,1068474883,1068474891,1,0,0),(703,143,14,1,1068040391,1068040391,0,0,0),(704,144,14,1,1068040434,1068040434,0,0,0),(705,145,14,1,1068040688,1068040688,0,0,0),(852,235,14,1,1068646887,1068647892,1,0,0),(841,115,14,4,1068639963,1068639974,3,0,0),(804,214,14,1,1068474859,1068474871,1,0,0),(801,213,14,1,1068473196,1068473231,3,0,0),(720,14,14,2,1068044312,1068044322,3,0,0),(837,232,14,3,1068632181,1068632189,1,0,0),(833,232,14,2,1068567384,1068567407,3,0,0),(742,176,149,1,1068108624,1068108805,0,0,0),(743,177,149,1,1068108805,1068108834,0,0,0),(744,178,149,1,1068108834,1068108898,0,0,0),(745,179,149,1,1068108898,1068109016,0,0,0),(746,180,149,1,1068109016,1068109220,0,0,0),(747,181,149,1,1068109220,1068109255,0,0,0),(748,182,149,1,1068109255,1068109498,0,0,0),(749,183,149,1,1068109498,1068109663,0,0,0),(750,184,149,1,1068109663,1068109781,0,0,0),(751,185,149,1,1068109781,1068109829,0,0,0),(752,186,149,1,1068109829,1068109829,0,0,0),(753,187,14,1,1068110619,1068110619,0,0,0),(755,189,14,1,1068110880,1068110880,0,0,0),(758,191,149,1,1068111317,1068111376,0,0,0),(759,192,149,1,1068111376,1068111870,0,0,0),(760,193,149,1,1068111870,1068111917,0,0,0),(761,194,149,1,1068111917,1068111917,0,0,0),(817,222,14,1,1068554893,1068554919,1,0,0),(823,226,14,1,1068557670,1068557670,0,0,0),(822,225,14,1,1068557668,1068557668,0,0,0),(769,200,149,1,1068120480,1068120496,0,0,0),(770,201,149,1,1068120737,1068120756,0,0,0),(816,14,14,7,1068546819,1068556425,1,0,0),(813,220,14,1,1068542707,1068542738,1,0,0),(812,219,14,1,1068542674,1068542692,1,0,0),(811,161,14,2,1068542639,1068542655,1,0,0),(821,224,14,1,1068557633,1068557633,0,0,0),(809,218,14,2,1068479815,1068479823,1,0,0),(777,14,14,3,1068121854,1068123057,3,0,0),(808,218,14,1,1068479703,1068479749,3,0,0),(815,14,14,6,1068545948,1068545957,3,0,0),(818,213,14,2,1068556190,1068556203,1,0,0),(782,206,14,1,1068123519,1068123599,1,0,0),(820,223,14,1,1068557207,1068557207,0,0,0),(819,211,14,3,1068556326,1068556361,1,0,0),(874,250,14,1,1069686828,1069687269,1,0,0),(872,160,14,2,1069686675,1069686817,1,0,0),(868,247,14,1,1069676471,1069676471,0,0,0),(866,246,14,1,1069418372,1069418372,0,0,0),(792,1,14,4,1068212220,1068212328,3,1,0),(793,1,14,5,1068212545,1068212663,3,1,0),(794,14,14,4,1068213048,1068213064,3,0,0),(807,217,14,1,1068474973,1068474983,1,0,0),(796,14,14,5,1068468183,1068468218,3,0,0),(806,216,14,1,1068474907,1068474919,1,0,0),(798,211,14,1,1068472612,1068472652,3,0,0),(799,212,14,1,1068472666,1068472760,3,0,0),(848,115,14,5,1068640455,1068640475,1,0,0),(850,116,14,4,1068640509,1068640525,1,0,0),(853,236,14,1,1068649417,1068649441,1,0,0),(854,237,14,1,1068649557,1068649592,1,0,0),(855,238,14,1,1068652581,1068652613,1,0,0),(857,239,14,1,1069245965,1069246036,1,0,0),(858,240,14,1,1069249251,1069251941,0,0,0),(860,242,14,1,1069328731,1069328764,1,0,0),(861,14,14,8,1069329522,1069329522,0,0,0),(864,245,14,1,1069407224,1069407224,0,0,0);
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
INSERT INTO ezimagefile VALUES (1,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-53-eng-GB/my_shop_logo.gif'),(2,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop.gif'),(3,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_reference.gif'),(4,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_medium.gif'),(5,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-54-eng-GB/my_shop_logo.gif'),(7,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop.gif'),(8,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_reference.gif'),(9,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_medium.gif'),(10,152,'var/shop/storage/images/setup/look_and_feel/my_shop/152-55-eng-GB/my_shop_logo.gif'),(11,519,'var/shop/storage/images/news/news_bulletin_october/519-2-eng-GB/news_bulletin_october.'),(12,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop.gif'),(13,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_reference.gif'),(14,152,'var/shop/storage/images/setup/look_and_feel/shop/152-56-eng-GB/shop_medium.gif'),(15,943,'var/shop/storage/images/news/news_bulletin_november/943-1-eng-GB/news_bulletin_november.');
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
INSERT INTO ezinfocollection VALUES (1,137,1068027503,'c6194244e6057c2ed46e92ac8c59be21',1068027503),(2,137,1068028058,'c6194244e6057c2ed46e92ac8c59be21',1068028058),(3,222,1068630829,'c6194244e6057c2ed46e92ac8c59be21',1068630829),(4,222,1068634393,'c6194244e6057c2ed46e92ac8c59be21',1068634393),(5,222,1068642711,'c6194244e6057c2ed46e92ac8c59be21',1068642711);
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
INSERT INTO ezinfocollection_attribute VALUES (1,1,'',0,0,183,443,137),(2,1,'',0,0,185,445,137),(3,1,'',0,0,184,444,137),(4,2,'FOo bar ',0,0,183,443,137),(5,2,'bf@ez.no',0,0,185,445,137),(6,2,'This is my feedback.',0,0,184,444,137),(7,3,'test',0,0,208,793,222),(8,3,'wy@ez.no',0,0,209,794,222),(9,3,'sfsfsf',0,0,210,795,222),(10,4,'test',0,0,208,793,222),(11,4,'wy@ez.no',0,0,209,794,222),(12,4,'fwsfwsf',0,0,210,795,222),(13,5,'wer',0,0,208,793,222),(14,5,'wy@ez.no',0,0,209,794,222),(15,5,'ewrw',0,0,210,795,222);
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
INSERT INTO eznode_assignment VALUES (504,1,6,1,9,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(513,217,1,157,9,1,1,0,0),(515,218,2,155,9,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(539,213,3,2,9,1,1,0,0),(182,41,1,1,9,1,1,0,0),(183,42,1,1,9,1,1,0,0),(550,43,11,46,9,1,1,0,0),(185,44,1,44,9,1,1,0,0),(188,43,3,46,9,1,1,0,0),(187,43,2,46,9,1,1,44,0),(189,43,4,46,9,1,1,0,0),(190,43,5,46,9,1,1,0,0),(552,45,11,46,9,1,1,0,0),(192,46,1,44,9,1,1,0,0),(193,46,2,44,9,1,1,0,0),(531,56,47,48,9,1,1,0,0),(322,115,2,46,9,1,1,0,0),(334,45,7,46,9,1,1,0,0),(570,56,55,48,9,1,1,0,0),(201,49,1,2,9,1,1,0,0),(445,175,1,2,1,1,0,0,0),(438,168,1,2,1,1,0,0,0),(367,129,1,2,1,1,1,0,0),(568,56,54,48,9,1,1,0,0),(536,232,2,153,9,1,1,154,0),(435,165,1,115,1,1,0,0,0),(540,232,3,153,9,1,1,0,0),(428,160,1,50,9,1,1,0,0),(429,161,1,2,9,1,1,0,0),(386,45,9,46,9,1,1,0,0),(385,43,8,46,9,1,1,0,0),(384,115,3,46,9,1,1,0,0),(554,56,52,48,9,1,1,0,0),(505,213,1,2,9,1,1,0,0),(532,56,48,48,9,1,1,0,0),(444,174,1,2,1,1,0,0,0),(559,56,53,48,9,1,1,0,0),(354,127,1,50,1,1,0,0,0),(353,126,1,50,1,1,0,0,0),(321,45,6,46,9,1,1,0,0),(538,56,50,48,9,1,1,0,0),(556,236,1,153,1,1,1,0,0),(387,116,2,46,9,1,1,0,0),(558,238,1,153,1,1,1,0,0),(443,173,1,2,1,1,0,0,0),(439,169,1,2,1,1,1,0,0),(555,235,1,153,1,1,1,0,0),(442,172,1,2,1,1,0,0,0),(507,211,2,154,9,1,1,2,0),(537,56,49,48,9,1,1,0,0),(544,115,4,46,9,1,1,0,0),(441,171,1,115,1,1,0,0,0),(335,45,8,46,9,1,1,0,0),(541,56,51,48,9,1,1,0,0),(545,43,9,46,9,1,1,0,0),(547,116,3,46,9,1,1,0,0),(300,107,1,14,9,1,1,0,0),(301,107,2,14,9,1,1,0,0),(516,1,7,1,9,1,1,0,0),(306,111,1,14,9,1,1,0,0),(576,56,56,48,9,1,1,0,0),(371,49,2,2,9,1,1,0,0),(311,43,6,46,9,1,1,0,0),(312,45,2,46,9,1,1,0,0),(313,43,7,46,9,1,1,0,0),(314,45,3,46,9,1,1,0,0),(315,115,1,46,9,1,1,0,0),(316,45,4,46,9,1,1,0,0),(317,116,1,46,9,1,1,0,0),(318,45,5,46,9,1,1,0,0),(574,1,8,1,8,1,1,0,0),(560,239,1,12,1,1,1,0,0),(511,215,1,154,9,1,1,0,0),(557,237,1,153,1,1,1,0,0),(407,143,1,112,1,1,1,0,0),(408,144,1,112,1,1,1,0,0),(409,145,1,112,1,1,1,0,0),(551,115,5,46,9,1,1,0,0),(553,116,4,46,9,1,1,0,0),(509,212,2,154,9,1,1,2,0),(510,214,1,154,9,1,1,0,0),(424,14,2,13,9,1,1,0,0),(549,43,10,46,9,1,1,0,0),(546,45,10,46,9,1,1,0,0),(446,176,1,2,1,1,0,0,0),(447,177,1,2,1,1,0,0,0),(448,178,1,2,1,1,0,0,0),(449,179,1,2,1,1,0,0,0),(450,180,1,2,1,1,0,0,0),(451,181,1,2,1,1,0,0,0),(452,182,1,2,1,1,0,0,0),(453,183,1,2,1,1,0,0,0),(454,184,1,2,1,1,0,0,0),(455,185,1,2,1,1,0,0,0),(456,186,1,2,1,1,1,0,0),(457,187,1,12,1,1,1,0,0),(459,189,1,12,1,1,1,0,0),(462,191,1,115,1,1,0,0,0),(463,192,1,2,1,1,0,0,0),(464,193,1,2,1,1,0,0,0),(465,194,1,2,1,1,1,0,0),(526,227,1,154,9,1,1,0,0),(534,232,1,154,9,1,1,0,0),(530,230,1,155,9,1,1,0,0),(473,200,1,114,1,1,1,0,0),(474,201,1,135,1,1,1,0,0),(525,211,3,154,9,1,1,0,0),(521,14,6,13,9,1,1,0,0),(524,213,2,2,9,1,1,0,0),(519,220,1,2,9,1,1,0,0),(529,229,1,155,9,1,1,0,0),(518,219,1,2,9,1,1,0,0),(481,14,3,13,9,1,1,0,0),(517,161,2,2,9,1,1,0,0),(523,222,1,2,9,1,1,0,0),(522,14,7,13,9,1,1,0,0),(486,206,1,13,9,1,1,0,0),(528,228,1,155,9,1,1,0,0),(562,241,1,167,1,1,1,0,0),(577,250,1,50,9,1,1,0,0),(575,160,2,50,9,1,1,0,0),(571,247,1,12,1,1,1,0,0),(569,246,1,12,1,1,1,0,0),(496,1,4,1,9,1,1,0,0),(497,1,5,1,9,1,1,0,0),(498,14,4,13,9,1,1,0,0),(514,218,1,155,9,1,1,0,0),(500,14,5,13,9,1,1,0,0),(512,216,1,155,9,1,1,0,0),(502,211,1,2,9,1,1,0,0),(503,212,1,2,9,1,1,0,0),(561,240,1,165,1,1,1,0,0),(563,242,1,167,1,1,1,0,0),(564,14,8,13,9,1,1,0,0),(567,245,1,12,1,1,1,0,0);
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
INSERT INTO eznotificationevent VALUES (221,0,'ezpublish',210,1,0,0,'','','',''),(220,0,'ezpublish',14,5,0,0,'','','',''),(219,0,'ezpublish',209,1,0,0,'','','',''),(218,0,'ezpublish',14,4,0,0,'','','',''),(217,0,'ezpublish',1,5,0,0,'','','',''),(216,0,'ezpublish',1,4,0,0,'','','',''),(215,0,'ezpublish',149,8,0,0,'','','',''),(214,0,'ezpublish',149,7,0,0,'','','',''),(213,0,'ezpublish',149,6,0,0,'','','',''),(212,0,'ezpublish',149,5,0,0,'','','',''),(211,0,'ezpublish',149,4,0,0,'','','',''),(210,0,'ezpublish',208,1,0,0,'','','',''),(209,0,'ezpublish',207,1,0,0,'','','',''),(208,0,'ezpublish',206,1,0,0,'','','',''),(207,0,'ezpublish',14,3,0,0,'','','',''),(206,0,'ezpublish',205,1,0,0,'','','',''),(205,0,'ezpublish',202,2,0,0,'','','',''),(204,0,'ezpublish',203,5,0,0,'','','',''),(203,0,'ezpublish',203,4,0,0,'','','',''),(202,0,'ezpublish',204,1,0,0,'','','',''),(201,0,'ezpublish',203,3,0,0,'','','',''),(200,0,'ezpublish',203,2,0,0,'','','',''),(199,0,'ezpublish',203,1,0,0,'','','',''),(198,0,'ezpublish',202,1,0,0,'','','',''),(197,0,'ezpublish',199,1,0,0,'','','',''),(196,0,'ezpublish',56,46,0,0,'','','',''),(195,0,'ezpublish',149,3,0,0,'','','',''),(194,0,'ezpublish',198,1,0,0,'','','',''),(193,0,'ezpublish',197,1,0,0,'','','',''),(192,0,'ezpublish',196,1,0,0,'','','',''),(191,0,'ezpublish',195,1,0,0,'','','',''),(190,0,'ezpublish',190,1,0,0,'','','',''),(189,0,'ezpublish',149,2,0,0,'','','',''),(188,0,'ezpublish',188,1,0,0,'','','',''),(187,0,'ezpublish',170,1,0,0,'','','',''),(186,0,'ezpublish',167,1,0,0,'','','',''),(185,0,'ezpublish',166,1,0,0,'','','',''),(184,0,'ezpublish',164,1,0,0,'','','',''),(183,0,'ezpublish',163,1,0,0,'','','',''),(182,0,'ezpublish',162,1,0,0,'','','',''),(180,0,'ezpublish',160,1,0,0,'','','',''),(181,0,'ezpublish',161,1,0,0,'','','',''),(222,0,'ezpublish',211,1,0,0,'','','',''),(223,0,'ezpublish',212,1,0,0,'','','',''),(224,0,'ezpublish',1,6,0,0,'','','',''),(225,0,'ezpublish',213,1,0,0,'','','',''),(226,0,'ezpublish',211,2,0,0,'','','',''),(227,0,'ezpublish',212,2,0,0,'','','',''),(228,0,'ezpublish',214,1,0,0,'','','',''),(229,0,'ezpublish',215,1,0,0,'','','',''),(230,0,'ezpublish',216,1,0,0,'','','',''),(231,0,'ezpublish',217,1,0,0,'','','',''),(232,0,'ezpublish',218,1,0,0,'','','',''),(233,0,'ezpublish',218,2,0,0,'','','',''),(234,0,'ezpublish',1,7,0,0,'','','',''),(235,0,'ezpublish',161,2,0,0,'','','',''),(236,0,'ezpublish',219,1,0,0,'','','',''),(237,0,'ezpublish',220,1,0,0,'','','',''),(238,0,'ezpublish',221,1,0,0,'','','',''),(239,0,'ezpublish',14,6,0,0,'','','',''),(240,0,'ezpublish',222,1,0,0,'','','',''),(241,0,'ezpublish',213,2,0,0,'','','',''),(242,0,'ezpublish',14,7,0,0,'','','',''),(243,0,'ezpublish',211,3,0,0,'','','',''),(244,0,'ezpublish',227,1,0,0,'','','',''),(245,0,'ezpublish',228,1,0,0,'','','',''),(246,0,'ezpublish',229,1,0,0,'','','',''),(247,0,'ezpublish',230,1,0,0,'','','',''),(248,0,'ezpublish',56,43,0,0,'','','',''),(249,0,'ezpublish',56,47,0,0,'','','',''),(250,0,'ezpublish',56,48,0,0,'','','',''),(251,0,'ezpublish',232,1,0,0,'','','',''),(252,0,'ezpublish',232,2,0,0,'','','',''),(253,0,'ezpublish',56,49,0,0,'','','',''),(254,0,'ezpublish',56,50,0,0,'','','',''),(255,0,'ezpublish',232,3,0,0,'','','',''),(256,0,'ezpublish',56,51,0,0,'','','',''),(257,0,'ezpublish',115,4,0,0,'','','',''),(258,0,'ezpublish',43,9,0,0,'','','',''),(259,0,'ezpublish',45,10,0,0,'','','',''),(260,0,'ezpublish',116,3,0,0,'','','',''),(261,0,'ezpublish',43,10,0,0,'','','',''),(262,0,'ezpublish',43,11,0,0,'','','',''),(263,0,'ezpublish',115,5,0,0,'','','',''),(264,0,'ezpublish',45,11,0,0,'','','',''),(265,0,'ezpublish',116,4,0,0,'','','',''),(266,0,'ezpublish',56,52,0,0,'','','',''),(267,0,'ezpublish',235,1,0,0,'','','',''),(268,0,'ezpublish',236,1,0,0,'','','',''),(269,0,'ezpublish',237,1,0,0,'','','',''),(270,0,'ezpublish',238,1,0,0,'','','',''),(271,0,'ezpublish',56,53,0,0,'','','',''),(272,0,'ezpublish',239,1,0,0,'','','',''),(273,0,'ezpublish',241,1,0,0,'','','',''),(274,0,'ezpublish',242,1,0,0,'','','',''),(275,0,'ezpublish',56,54,0,0,'','','',''),(276,0,'ezpublish',56,55,0,0,'','','',''),(277,0,'ezpublish',1,8,0,0,'','','',''),(278,0,'ezpublish',160,2,0,0,'','','',''),(279,0,'ezpublish',56,56,0,0,'','','',''),(280,0,'ezpublish',250,1,0,0,'','','','');
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
INSERT INTO ezorder VALUES (1,14,3,1068475886,0,1,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>test</first-name>\n  <last-name>test</last-name>\n  <email>wy@ez.no</email>\n  <address>efwf</address>\n</shop_account>','simple',0),(2,14,5,1068479320,0,2,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>testetw</first-name>\n  <last-name>test</last-name>\n  <email>t@ez.no</email>\n  <address>sfs</address>\n</shop_account>','simple',0),(3,14,7,1068479864,0,3,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>test</first-name>\n  <last-name>wer</last-name>\n  <email>wy@ez.no</email>\n  <address>werwe</address>\n</shop_account>','simple',0),(4,14,8,1068543796,0,4,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>BÃ¥rd</first-name>\n  <last-name>Farstad</last-name>\n  <email>bf@ez.no</email>\n  <address>mofsermosfmoasdmfosdmfgmsdofgmo</address>\n</shop_account>','simple',0),(5,14,10,1068543948,0,5,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>foo</first-name>\n  <last-name>bf</last-name>\n  <email>bf@ez.no</email>\n  <address>dfgsdfgsd\r\nfgsdf\r\n</address>\n</shop_account>','simple',0),(6,14,14,1068545032,0,6,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>oijh</first-name>\n  <last-name>iou</last-name>\n  <email>bf@ez.no</email>\n  <address>dsfg\r\nsdgf\r\nds\r\n</address>\n</shop_account>','simple',0),(7,14,16,1068545316,0,7,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>hj</first-name>\n  <last-name>jkh</last-name>\n  <email>bf@ez.no</email>\n  <address>lk</address>\n</shop_account>','simple',0),(8,14,18,1068545671,0,8,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>jklh</first-name>\n  <last-name>jk</last-name>\n  <email>bf@ez.no</email>\n  <address>ljkhkjh</address>\n</shop_account>','simple',0),(9,14,11,1068547237,1,0,'','','default',0),(10,14,12,1068551308,1,0,'','','default',0),(11,14,20,1068551614,0,9,'','','default',0),(12,14,21,1068553286,0,10,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>nbg</first-name>\n  <last-name>hjg</last-name>\n  <email>bf@ez.no</email>\n  <address>hj</address>\n</shop_account>','simple',0),(13,14,24,1068563272,0,11,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>dfg</first-name>\n  <last-name>sdg</last-name>\n  <email>bf@ez.no</email>\n  <address>dfg</address>\n</shop_account>','simple',0),(14,14,27,1068633577,0,12,'','','default',0),(15,14,29,1068633830,0,13,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>werwwer</first-name>\n  <last-name>wer</last-name>\n  <email>wr@ese.no</email>\n  <address>wefrwedsfsf\r\nsfs</address>\n</shop_account>','simple',0),(16,14,31,1068642068,0,14,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>klj</first-name>\n  <last-name>lkj</last-name>\n  <email>bf@ez.no</email>\n  <address>jkdf\r\ndfghd\r\nf</address>\n</shop_account>','simple',0),(17,14,34,1068644005,0,15,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>test</first-name>\n  <last-name>test</last-name>\n  <email>ewtwt@ez.no</email>\n  <address>sfsf</address>\n</shop_account>','simple',0),(18,14,35,1068644918,1,0,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>bf@ez.no</email>\n  <street1>sfs</street1>\n  <street2>sdf</street2>\n  <zip>sfs</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment></comment>\n</shop_account>','ez',0),(19,14,36,1068645768,0,16,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>wy@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment>sdfsf</comment>\n</shop_account>','ez',0),(20,14,38,1068646412,0,17,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>wy@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment>dw</comment>\n</shop_account>','ez',0),(21,14,40,1068646646,0,18,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>wy@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment>fes</comment>\n</shop_account>','ez',0),(22,14,41,1068647937,1,0,'','','default',0),(23,14,42,1068648442,0,19,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>wy@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment>re</comment>\n</shop_account>','ez',0),(24,14,66,1069416842,0,20,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>bf@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment>fg</comment>\n</shop_account>','ez',0),(25,10,71,1069419020,0,21,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>test</first-name>\n  <last-name>test</last-name>\n  <email>etes@yahoo.com</email>\n  <street1>ssffss</street1>\n  <street2>sf</street2>\n  <zip>fsf</zip>\n  <place>sf</place>\n  <state>sfs</state>\n  <country>Norway</country>\n  <comment>sfsf</comment>\n</shop_account>','ez',0),(26,14,57,1069422184,1,0,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>bf@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment></comment>\n</shop_account>','ez',0),(27,14,57,1069423279,1,0,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>bf@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment></comment>\n</shop_account>','ez',0),(28,14,75,1069425632,0,22,'','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<shop_account>\n  <first-name>Administrator</first-name>\n  <last-name>User</last-name>\n  <email>bf@ez.no</email>\n  <street1>sf</street1>\n  <street2>esf</street2>\n  <zip>sf</zip>\n  <place>sfs</place>\n  <state></state>\n  <country>Norway</country>\n  <comment></comment>\n</shop_account>','ez',0);
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
INSERT INTO ezpolicy VALUES (308,2,'*','*','*'),(341,8,'read','content','*'),(381,1,'login','user','*'),(382,1,'read','content',''),(383,1,'buy','shop','*');
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
INSERT INTO ezpolicy_limitation VALUES (301,382,'Class',0,'read','content');
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
INSERT INTO ezpolicy_limitation_value VALUES (608,301,'5'),(607,301,'25'),(606,301,'24'),(605,301,'23'),(604,301,'2'),(603,301,'12'),(602,301,'10'),(601,301,'1');
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
INSERT INTO ezproductcollection VALUES (1,1068472798),(2,1068475844),(3,1068475844),(4,1068479286),(5,1068479286),(6,1068479841),(7,1068479841),(8,1068472798),(9,1068543932),(10,1068543932),(11,1068544463),(12,1068544990),(13,1068545010),(14,1068545010),(15,1068545301),(16,1068545301),(17,1068545655),(18,1068545655),(19,1068545764),(20,1068544990),(21,1068545764),(22,1068562405),(23,1068563235),(24,1068563235),(25,1068563439),(26,1068624693),(27,1068562405),(28,1068633642),(29,1068633642),(30,1068633842),(31,1068624693),(32,1068642084),(33,1068643029),(34,1068633842),(35,1068644018),(36,1068644018),(37,1068645783),(38,1068645783),(39,1068646424),(40,1068646424),(41,1068647920),(42,1068647920),(43,1068648458),(44,1068726645),(45,1068728131),(46,1068812018),(47,1068824562),(48,1068824850),(49,1069064928),(50,1069069476),(51,1069069484),(52,1069077647),(53,1069077657),(54,1069150170),(55,1069161341),(56,1069233622),(57,1069318921),(58,1069327822),(59,1069330638),(60,1069334255),(61,1069406828),(62,1069407034),(63,1069407047),(64,1069407214),(65,1069416750),(66,1069416750),(67,1069416860),(68,1069418322),(69,1069418586),(70,1069418957),(71,1069418957),(72,1069419038),(73,1069424348),(74,1069425097),(75,1069318921),(76,1069425695),(77,1069425643),(78,1069430399),(79,1069674101),(80,1069676434),(81,1069680435),(82,1069686070);
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
INSERT INTO ezproductcollection_item VALUES (1,1,211,1,100,1,0,0),(2,1,211,1,100,1,0,0),(3,2,211,4,100,1,0,0),(4,3,211,4,100,1,0,0),(5,4,212,8,120,1,0,0),(6,5,212,8,120,1,0,0),(7,6,218,3,300,1,0,0),(8,7,218,3,300,1,0,0),(9,1,211,1,100,1,0,0),(10,8,211,1,100,1,0,0),(11,8,211,1,100,1,0,0),(12,8,211,1,100,1,0,0),(13,9,212,1,120,1,0,0),(14,10,212,1,120,1,0,0),(15,11,212,1,120,1,0,0),(16,13,211,1,100,1,0,0),(17,14,211,1,100,1,0,0),(18,15,211,1,100,1,0,0),(19,16,211,1,100,1,0,0),(20,17,211,1,100,1,0,0),(21,18,211,1,100,1,0,0),(22,12,211,5,100,1,0,0),(23,11,212,1,120,1,0,0),(24,20,211,5,100,1,0,0),(25,19,211,1,100,1,0,0),(26,19,212,1,120,1,0,0),(27,19,218,1,300,1,0,0),(28,21,211,1,100,1,0,0),(29,21,212,1,120,1,0,0),(30,21,218,1,300,1,0,0),(31,23,230,1,21347,1,0,0),(32,23,229,1,1247,1,0,0),(33,24,230,1,21347,1,0,0),(34,24,229,1,1247,1,0,0),(35,25,211,1,100,1,0,0),(36,25,212,1,120,1,0,0),(37,26,212,1,120,1,0,0),(38,26,211,1,100,1,0,0),(39,26,228,1,1249,1,0,0),(40,22,218,1,300,1,0,0),(41,27,218,1,300,1,0,0),(42,28,218,1,300,1,0,0),(43,29,218,1,300,1,0,0),(44,30,218,1,300,1,0,0),(45,30,218,1,300,1,0,0),(46,26,211,1,100,1,0,0),(47,31,212,1,120,1,0,0),(48,31,211,1,100,1,0,0),(49,31,228,1,1249,1,0,0),(50,31,211,1,100,1,0,0),(51,34,218,1,300,1,0,0),(52,34,218,1,300,1,0,0),(53,35,218,1,300,1,0,0),(54,36,218,1,300,1,0,0),(55,37,218,1,300,1,0,0),(56,38,218,1,300,1,0,0),(57,39,218,1,300,1,0,0),(58,40,218,1,300,1,0,0),(59,41,211,1,100,1,0,0),(60,41,211,1,100,1,0,0),(61,42,211,1,100,1,0,0),(62,42,211,1,100,1,0,0),(64,54,212,1,120,1,0,0),(65,56,230,1,21347,1,0,0),(66,56,229,1,1247,1,0,0),(67,57,229,1,1247,1,0,0),(68,57,230,1,21347,1,0,0),(72,65,218,1,300,1,0,0),(73,66,218,1,300,1,0,0),(74,68,228,1,1249,1,0,0),(75,69,230,1,21347,1,0,0),(76,70,230,1,21347,1,0,0),(77,71,230,1,21347,1,0,0),(78,75,229,1,1247,1,0,0),(79,75,230,1,21347,1,0,0),(81,80,228,1,1249,1,0,0),(82,80,218,1,300,1,0,0);
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
INSERT INTO ezsearch_object_word_link VALUES (3590,219,1471,0,81,1470,1422,10,1068542692,1,141,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0),(29,42,7,0,0,0,0,1,1066383068,11,4,'',0),(4292,43,1639,0,2,1638,0,14,1066384365,11,155,'',0),(4291,43,1638,0,1,1637,1639,14,1066384365,11,155,'',0),(33,44,7,0,0,0,11,1,1066384457,11,4,'',0),(34,44,11,0,1,7,0,1,1066384457,11,4,'',0),(4290,43,1637,0,0,0,1638,14,1066384365,11,152,'',0),(4301,45,1642,0,5,1641,0,14,1066388816,11,155,'',0),(4300,45,1641,0,4,25,1642,14,1066388816,11,155,'',0),(4299,45,25,0,3,34,1641,14,1066388816,11,155,'',0),(4298,45,34,0,2,33,25,14,1066388816,11,152,'',0),(58,46,34,0,2,33,0,1,1066389805,11,4,'',0),(57,46,33,0,1,32,34,1,1066389805,11,4,'',0),(56,46,32,0,0,0,33,1,1066389805,11,4,'',0),(4903,56,1965,0,7,1964,0,15,1066643397,11,218,'',0),(61,49,37,0,0,0,0,1,1066398020,4,4,'',0),(3509,219,1516,0,0,0,1517,10,1068542692,1,140,'',0),(3508,161,1428,0,384,1468,0,10,1068047603,1,141,'',0),(3507,161,1468,0,383,1496,1428,10,1068047603,1,141,'',0),(3506,161,1496,0,382,1485,1468,10,1068047603,1,141,'',0),(3505,161,1485,0,381,1515,1496,10,1068047603,1,141,'',0),(3504,161,1515,0,380,1514,1485,10,1068047603,1,141,'',0),(3503,161,1514,0,379,1513,1515,10,1068047603,1,141,'',0),(3502,161,1513,0,378,1416,1514,10,1068047603,1,141,'',0),(3501,161,1416,0,377,1435,1513,10,1068047603,1,141,'',0),(3500,161,1435,0,376,1487,1416,10,1068047603,1,141,'',0),(3499,161,1487,0,375,1482,1435,10,1068047603,1,141,'',0),(3498,161,1482,0,374,1512,1487,10,1068047603,1,141,'',0),(3497,161,1512,0,373,1436,1482,10,1068047603,1,141,'',0),(3496,161,1436,0,372,1432,1512,10,1068047603,1,141,'',0),(3495,161,1432,0,371,1478,1436,10,1068047603,1,141,'',0),(3494,161,1478,0,370,1511,1432,10,1068047603,1,141,'',0),(3493,161,1511,0,369,1510,1478,10,1068047603,1,141,'',0),(3492,161,1510,0,368,1509,1511,10,1068047603,1,141,'',0),(3491,161,1509,0,367,1508,1510,10,1068047603,1,141,'',0),(3490,161,1508,0,366,1428,1509,10,1068047603,1,141,'',0),(3489,161,1428,0,365,1470,1508,10,1068047603,1,141,'',0),(3488,161,1470,0,364,1415,1428,10,1068047603,1,141,'',0),(3487,161,1415,0,363,1507,1470,10,1068047603,1,141,'',0),(3486,161,1507,0,362,1442,1415,10,1068047603,1,141,'',0),(3485,161,1442,0,361,1430,1507,10,1068047603,1,141,'',0),(3484,161,1430,0,360,1487,1442,10,1068047603,1,141,'',0),(3483,161,1487,0,359,1428,1430,10,1068047603,1,141,'',0),(3482,161,1428,0,358,1506,1487,10,1068047603,1,141,'',0),(3481,161,1506,0,357,1411,1428,10,1068047603,1,141,'',0),(3480,161,1411,0,356,1478,1506,10,1068047603,1,141,'',0),(3479,161,1478,0,355,1457,1411,10,1068047603,1,141,'',0),(3478,161,1457,0,354,1468,1478,10,1068047603,1,141,'',0),(3477,161,1468,0,353,1425,1457,10,1068047603,1,141,'',0),(3476,161,1425,0,352,1505,1468,10,1068047603,1,141,'',0),(3475,161,1505,0,351,1504,1425,10,1068047603,1,141,'',0),(3474,161,1504,0,350,1478,1505,10,1068047603,1,141,'',0),(3473,161,1478,0,349,1449,1504,10,1068047603,1,141,'',0),(3472,161,1449,0,348,1503,1478,10,1068047603,1,141,'',0),(3471,161,1503,0,347,1439,1449,10,1068047603,1,141,'',0),(3470,161,1439,0,346,1484,1503,10,1068047603,1,141,'',0),(3469,161,1484,0,345,1490,1439,10,1068047603,1,141,'',0),(3468,161,1490,0,344,1421,1484,10,1068047603,1,141,'',0),(3467,161,1421,0,343,1487,1490,10,1068047603,1,141,'',0),(3466,161,1487,0,342,1449,1421,10,1068047603,1,141,'',0),(3465,161,1449,0,341,1475,1487,10,1068047603,1,141,'',0),(3464,161,1475,0,340,1502,1449,10,1068047603,1,141,'',0),(3463,161,1502,0,339,1429,1475,10,1068047603,1,141,'',0),(3462,161,1429,0,338,1468,1502,10,1068047603,1,141,'',0),(3461,161,1468,0,337,1501,1429,10,1068047603,1,141,'',0),(3460,161,1501,0,336,1500,1468,10,1068047603,1,141,'',0),(3459,161,1500,0,335,1431,1501,10,1068047603,1,141,'',0),(3458,161,1431,0,334,1499,1500,10,1068047603,1,141,'',0),(3457,161,1499,0,333,1458,1431,10,1068047603,1,141,'',0),(3456,161,1458,0,332,1462,1499,10,1068047603,1,141,'',0),(3455,161,1462,0,331,1498,1458,10,1068047603,1,141,'',0),(3454,161,1498,0,330,1440,1462,10,1068047603,1,141,'',0),(3453,161,1440,0,329,1446,1498,10,1068047603,1,141,'',0),(3452,161,1446,0,328,1466,1440,10,1068047603,1,141,'',0),(3451,161,1466,0,327,1483,1446,10,1068047603,1,141,'',0),(3450,161,1483,0,326,1450,1466,10,1068047603,1,141,'',0),(3449,161,1450,0,325,1443,1483,10,1068047603,1,141,'',0),(3448,161,1443,0,324,1411,1450,10,1068047603,1,141,'',0),(3447,161,1411,0,323,1497,1443,10,1068047603,1,141,'',0),(3446,161,1497,0,322,1462,1411,10,1068047603,1,141,'',0),(3445,161,1462,0,321,1422,1497,10,1068047603,1,141,'',0),(3444,161,1422,0,320,1496,1462,10,1068047603,1,141,'',0),(3443,161,1496,0,319,1460,1422,10,1068047603,1,141,'',0),(3442,161,1460,0,318,1428,1496,10,1068047603,1,141,'',0),(3441,161,1428,0,317,1495,1460,10,1068047603,1,141,'',0),(3440,161,1495,0,316,1494,1428,10,1068047603,1,141,'',0),(3439,161,1494,0,315,1493,1495,10,1068047603,1,141,'',0),(3438,161,1493,0,314,1492,1494,10,1068047603,1,141,'',0),(3437,161,1492,0,313,1491,1493,10,1068047603,1,141,'',0),(3436,161,1491,0,312,1417,1492,10,1068047603,1,141,'',0),(3435,161,1417,0,311,1490,1491,10,1068047603,1,141,'',0),(3434,161,1490,0,310,1489,1417,10,1068047603,1,141,'',0),(3433,161,1489,0,309,1428,1490,10,1068047603,1,141,'',0),(3432,161,1428,0,308,1458,1489,10,1068047603,1,141,'',0),(3431,161,1458,0,307,1473,1428,10,1068047603,1,141,'',0),(3430,161,1473,0,306,1422,1458,10,1068047603,1,141,'',0),(3429,161,1422,0,305,1422,1473,10,1068047603,1,141,'',0),(3428,161,1422,0,304,1488,1422,10,1068047603,1,141,'',0),(4989,250,1768,0,85,1989,0,2,1069687269,4,121,'',0),(4988,250,1989,0,84,1660,1768,2,1069687269,4,121,'',0),(4987,250,1660,0,83,1732,1989,2,1069687269,4,121,'',0),(4986,250,1732,0,82,1969,1660,2,1069687269,4,121,'',0),(4985,250,1969,0,81,1142,1732,2,1069687269,4,121,'',0),(4984,250,1142,0,80,1954,1969,2,1069687269,4,121,'',0),(4983,250,1954,0,79,1612,1142,2,1069687269,4,121,'',0),(4982,250,1612,0,78,1988,1954,2,1069687269,4,121,'',0),(4981,250,1988,0,77,1142,1612,2,1069687269,4,121,'',0),(4980,250,1142,0,76,1672,1988,2,1069687269,4,121,'',0),(4979,250,1672,0,75,1668,1142,2,1069687269,4,121,'',0),(4978,250,1668,0,74,1969,1672,2,1069687269,4,121,'',0),(4977,250,1969,0,73,1142,1668,2,1069687269,4,121,'',0),(4976,250,1142,0,72,1519,1969,2,1069687269,4,121,'',0),(4975,250,1519,0,71,1987,1142,2,1069687269,4,121,'',0),(4974,250,1987,0,70,1979,1519,2,1069687269,4,121,'',0),(4973,250,1979,0,69,1986,1987,2,1069687269,4,121,'',0),(4972,250,1986,0,68,1985,1979,2,1069687269,4,121,'',0),(4971,250,1985,0,67,1984,1986,2,1069687269,4,121,'',0),(4970,250,1984,0,66,1797,1985,2,1069687269,4,121,'',0),(4969,250,1797,0,65,1983,1984,2,1069687269,4,121,'',0),(4968,250,1983,0,64,1969,1797,2,1069687269,4,121,'',0),(4967,250,1969,0,63,1982,1983,2,1069687269,4,121,'',0),(4966,250,1982,0,62,33,1969,2,1069687269,4,121,'',0),(4965,250,33,0,61,1981,1982,2,1069687269,4,121,'',0),(4964,250,1981,0,60,1980,33,2,1069687269,4,121,'',0),(4963,250,1980,0,59,1804,1981,2,1069687269,4,121,'',0),(4962,250,1804,0,58,1797,1980,2,1069687269,4,121,'',0),(4961,250,1797,0,57,1534,1804,2,1069687269,4,121,'',0),(4960,250,1534,0,56,1783,1797,2,1069687269,4,121,'',0),(4959,250,1783,0,55,1670,1534,2,1069687269,4,121,'',0),(4958,250,1670,0,54,1979,1783,2,1069687269,4,121,'',0),(4957,250,1979,0,53,1612,1670,2,1069687269,4,121,'',0),(4956,250,1612,0,52,1797,1979,2,1069687269,4,121,'',0),(4955,250,1797,0,51,1978,1612,2,1069687269,4,121,'',0),(4954,250,1978,0,50,1947,1797,2,1069687269,4,121,'',0),(4953,250,1947,0,49,1417,1978,2,1069687269,4,121,'',0),(4952,250,1417,0,48,1978,1947,2,1069687269,4,121,'',0),(4951,250,1978,0,47,1977,1417,2,1069687269,4,121,'',0),(4950,250,1977,0,46,1783,1978,2,1069687269,4,121,'',0),(4949,250,1783,0,45,1417,1977,2,1069687269,4,121,'',0),(4948,250,1417,0,44,73,1783,2,1069687269,4,121,'',0),(4947,250,73,0,43,1804,1417,2,1069687269,4,121,'',0),(4946,250,1804,0,42,1534,73,2,1069687269,4,121,'',0),(4945,250,1534,0,41,1620,1804,2,1069687269,4,121,'',0),(4944,250,1620,0,40,1976,1534,2,1069687269,4,121,'',0),(4943,250,1976,0,39,1782,1620,2,1069687269,4,121,'',0),(4942,250,1782,0,38,1666,1976,2,1069687269,4,121,'',0),(4941,250,1666,0,37,1975,1782,2,1069687269,4,121,'',0),(4940,250,1975,0,36,1940,1666,2,1069687269,4,121,'',0),(4939,250,1940,0,35,73,1975,2,1069687269,4,121,'',0),(4938,250,73,0,34,1519,1940,2,1069687269,4,121,'',0),(4937,250,1519,0,33,1974,73,2,1069687269,4,121,'',0),(4936,250,1974,0,32,1142,1519,2,1069687269,4,121,'',0),(4935,250,1142,0,31,1782,1974,2,1069687269,4,121,'',0),(4934,250,1782,0,30,1666,1142,2,1069687269,4,121,'',0),(4933,250,1666,0,29,1672,1782,2,1069687269,4,121,'',0),(4932,250,1672,0,28,1973,1666,2,1069687269,4,121,'',0),(4931,250,1973,0,27,1969,1672,2,1069687269,4,121,'',0),(4930,250,1969,0,26,1761,1973,2,1069687269,4,121,'',0),(4929,250,1761,0,25,1439,1969,2,1069687269,4,121,'',0),(4928,250,1439,0,24,1519,1761,2,1069687269,4,121,'',0),(4927,250,1519,0,23,1972,1439,2,1069687269,4,121,'',0),(4926,250,1972,0,22,1971,1519,2,1069687269,4,121,'',0),(4925,250,1971,0,21,1660,1972,2,1069687269,4,121,'',0),(4924,250,1660,0,20,1732,1971,2,1069687269,4,121,'',0),(4923,250,1732,0,19,1970,1660,2,1069687269,4,121,'',0),(4922,250,1970,0,18,1969,1732,2,1069687269,4,120,'',0),(4921,250,1969,0,17,33,1970,2,1069687269,4,120,'',0),(4920,250,33,0,16,1439,1969,2,1069687269,4,120,'',0),(4919,250,1439,0,15,1969,33,2,1069687269,4,120,'',0),(4918,250,1969,0,14,1534,1439,2,1069687269,4,120,'',0),(4917,250,1534,0,13,1761,1969,2,1069687269,4,120,'',0),(4916,250,1761,0,12,1693,1534,2,1069687269,4,120,'',0),(4915,250,1693,0,11,1519,1761,2,1069687269,4,120,'',0),(4914,250,1519,0,10,1953,1693,2,1069687269,4,120,'',0),(4913,250,1953,0,9,1142,1519,2,1069687269,4,120,'',0),(4912,250,1142,0,8,1765,1953,2,1069687269,4,120,'',0),(4911,250,1765,0,7,1738,1142,2,1069687269,4,120,'',0),(4910,250,1738,0,6,1968,1765,2,1069687269,4,120,'',0),(4909,250,1968,0,5,1967,1738,2,1069687269,4,120,'',0),(4908,250,1967,0,4,73,1968,2,1069687269,4,120,'',0),(4907,250,73,0,3,1966,1967,2,1069687269,4,120,'',0),(4906,250,1966,0,2,1941,73,2,1069687269,4,1,'',0),(4905,250,1941,0,1,37,1966,2,1069687269,4,1,'',0),(4904,250,37,0,0,0,1941,2,1069687269,4,1,'',0),(4895,160,1958,0,49,1957,0,2,1068047455,4,121,'',0),(4894,160,1957,0,48,1142,1958,2,1068047455,4,121,'',0),(4893,160,1142,0,47,1144,1957,2,1068047455,4,121,'',0),(4892,160,1144,0,46,1956,1142,2,1068047455,4,121,'',0),(4891,160,1956,0,45,1955,1144,2,1068047455,4,121,'',0),(4890,160,1955,0,44,1917,1956,2,1068047455,4,121,'',0),(4889,160,1917,0,43,1439,1955,2,1068047455,4,121,'',0),(4888,160,1439,0,42,74,1917,2,1068047455,4,121,'',0),(4887,160,74,0,41,1607,1439,2,1068047455,4,121,'',0),(4886,160,1607,0,40,1954,74,2,1068047455,4,121,'',0),(4885,160,1954,0,39,1612,1607,2,1068047455,4,121,'',0),(4884,160,1612,0,38,1890,1954,2,1068047455,4,121,'',0),(4883,160,1890,0,37,1797,1612,2,1068047455,4,121,'',0),(4882,160,1797,0,36,1670,1890,2,1068047455,4,121,'',0),(4881,160,1670,0,35,1622,1797,2,1068047455,4,121,'',0),(4880,160,1622,0,34,1761,1670,2,1068047455,4,121,'',0),(4879,160,1761,0,33,1439,1622,2,1068047455,4,121,'',0),(4878,160,1439,0,32,1953,1761,2,1068047455,4,121,'',0),(4877,160,1953,0,31,1747,1439,2,1068047455,4,121,'',0),(4876,160,1747,0,30,1952,1953,2,1068047455,4,121,'',0),(4875,160,1952,0,29,1614,1747,2,1068047455,4,120,'',0),(4874,160,1614,0,28,1951,1952,2,1068047455,4,120,'',0),(4873,160,1951,0,27,1950,1614,2,1068047455,4,120,'',0),(4872,160,1950,0,26,33,1951,2,1068047455,4,120,'',0),(4871,160,33,0,25,1949,1950,2,1068047455,4,120,'',0),(4870,160,1949,0,24,1761,33,2,1068047455,4,120,'',0),(4869,160,1761,0,23,1534,1949,2,1068047455,4,120,'',0),(4868,160,1534,0,22,1761,1761,2,1068047455,4,120,'',0),(4867,160,1761,0,21,1750,1534,2,1068047455,4,120,'',0),(4866,160,1750,0,20,1747,1761,2,1068047455,4,120,'',0),(4865,160,1747,0,19,1670,1750,2,1068047455,4,120,'',0),(4864,160,1670,0,18,1948,1747,2,1068047455,4,120,'',0),(4863,160,1948,0,17,1670,1670,2,1068047455,4,120,'',0),(4862,160,1670,0,16,37,1948,2,1068047455,4,120,'',0),(4861,160,37,0,15,1947,1670,2,1068047455,4,120,'',0),(4860,160,1947,0,14,1946,37,2,1068047455,4,120,'',0),(4859,160,1946,0,13,1666,1947,2,1068047455,4,120,'',0),(4858,160,1666,0,12,1747,1946,2,1068047455,4,120,'',0),(4857,160,1747,0,11,1945,1666,2,1068047455,4,120,'',0),(4856,160,1945,0,10,73,1747,2,1068047455,4,120,'',0),(4855,160,73,0,9,1144,1945,2,1068047455,4,120,'',0),(4854,160,1144,0,8,37,73,2,1068047455,4,120,'',0),(4853,160,37,0,7,1944,1144,2,1068047455,4,120,'',0),(4852,160,1944,0,6,1142,37,2,1068047455,4,120,'',0),(4851,160,1142,0,5,1660,1944,2,1068047455,4,120,'',0),(4850,160,1660,0,4,1943,1142,2,1068047455,4,120,'',0),(4849,160,1943,0,3,1942,1660,2,1068047455,4,120,'',0),(4848,160,1942,0,2,1941,1943,2,1068047455,4,1,'',0),(4847,160,1941,0,1,37,1942,2,1068047455,4,1,'',0),(4846,160,37,0,0,0,1941,2,1068047455,4,1,'',0),(1106,107,571,0,1,570,0,4,1066916865,2,9,'',0),(1105,107,570,0,0,0,571,4,1066916865,2,8,'',0),(1107,111,572,0,0,0,573,4,1066917523,2,8,'',0),(1108,111,573,0,1,572,0,4,1066917523,2,9,'',0),(3530,219,1425,0,21,1424,1426,10,1068542692,1,141,'',0),(3529,219,1424,0,20,1423,1425,10,1068542692,1,141,'',0),(3528,219,1423,0,19,1415,1424,10,1068542692,1,141,'',0),(3527,219,1415,0,18,1422,1423,10,1068542692,1,141,'',0),(3514,219,1412,0,5,1411,1413,10,1068542692,1,141,'',0),(3515,219,1413,0,6,1412,1414,10,1068542692,1,141,'',0),(3516,219,1414,0,7,1413,1415,10,1068542692,1,141,'',0),(3517,219,1415,0,8,1414,1416,10,1068542692,1,141,'',0),(3518,219,1416,0,9,1415,1417,10,1068542692,1,141,'',0),(3519,219,1417,0,10,1416,1412,10,1068542692,1,141,'',0),(3520,219,1412,0,11,1417,1413,10,1068542692,1,141,'',0),(3521,219,1413,0,12,1412,1418,10,1068542692,1,141,'',0),(3522,219,1418,0,13,1413,1419,10,1068542692,1,141,'',0),(3523,219,1419,0,14,1418,1420,10,1068542692,1,141,'',0),(3524,219,1420,0,15,1419,1421,10,1068542692,1,141,'',0),(3525,219,1421,0,16,1420,1422,10,1068542692,1,141,'',0),(3637,219,1422,0,128,1496,1462,10,1068542692,1,141,'',0),(3636,219,1496,0,127,1460,1422,10,1068542692,1,141,'',0),(3635,219,1460,0,126,1428,1496,10,1068542692,1,141,'',0),(3634,219,1428,0,125,1495,1460,10,1068542692,1,141,'',0),(3633,219,1495,0,124,1494,1428,10,1068542692,1,141,'',0),(3427,161,1488,0,303,1487,1422,10,1068047603,1,141,'',0),(3426,161,1487,0,302,943,1488,10,1068047603,1,141,'',0),(3425,161,943,0,301,1437,1487,10,1068047603,1,141,'',0),(3424,161,1437,0,300,1442,943,10,1068047603,1,141,'',0),(3423,161,1442,0,299,1458,1437,10,1068047603,1,141,'',0),(3422,161,1458,0,298,1417,1442,10,1068047603,1,141,'',0),(3421,161,1417,0,297,1448,1458,10,1068047603,1,141,'',0),(3420,161,1448,0,296,1486,1417,10,1068047603,1,141,'',0),(3419,161,1486,0,295,1426,1448,10,1068047603,1,141,'',0),(3418,161,1426,0,294,1475,1486,10,1068047603,1,141,'',0),(3417,161,1475,0,293,1485,1426,10,1068047603,1,141,'',0),(3416,161,1485,0,292,1484,1475,10,1068047603,1,141,'',0),(3415,161,1484,0,291,1483,1485,10,1068047603,1,141,'',0),(3414,161,1483,0,290,1482,1484,10,1068047603,1,141,'',0),(3413,161,1482,0,289,1430,1483,10,1068047603,1,141,'',0),(3412,161,1430,0,288,1481,1482,10,1068047603,1,141,'',0),(3411,161,1481,0,287,1480,1430,10,1068047603,1,141,'',0),(3410,161,1480,0,286,1479,1481,10,1068047603,1,141,'',0),(3409,161,1479,0,285,1478,1480,10,1068047603,1,141,'',0),(3408,161,1478,0,284,1477,1479,10,1068047603,1,141,'',0),(3407,161,1477,0,283,1476,1478,10,1068047603,1,141,'',0),(3406,161,1476,0,282,1441,1477,10,1068047603,1,141,'',0),(3405,161,1441,0,281,1475,1476,10,1068047603,1,141,'',0),(3404,161,1475,0,280,1474,1441,10,1068047603,1,141,'',0),(3403,161,1474,0,279,1443,1475,10,1068047603,1,141,'',0),(3402,161,1443,0,278,1473,1474,10,1068047603,1,141,'',0),(3401,161,1473,0,277,1472,1443,10,1068047603,1,141,'',0),(3400,161,1472,0,276,1471,1473,10,1068047603,1,141,'',0),(3399,161,1471,0,275,1422,1472,10,1068047603,1,141,'',0),(3398,161,1422,0,274,1471,1471,10,1068047603,1,141,'',0),(3397,161,1471,0,273,1470,1422,10,1068047603,1,141,'',0),(3396,161,1470,0,272,1469,1471,10,1068047603,1,141,'',0),(3395,161,1469,0,271,1468,1470,10,1068047603,1,141,'',0),(3394,161,1468,0,270,1467,1469,10,1068047603,1,141,'',0),(3393,161,1467,0,269,1466,1468,10,1068047603,1,141,'',0),(3392,161,1466,0,268,1465,1467,10,1068047603,1,141,'',0),(3391,161,1465,0,267,1435,1466,10,1068047603,1,141,'',0),(3390,161,1435,0,266,1417,1465,10,1068047603,1,141,'',0),(3389,161,1417,0,265,1464,1435,10,1068047603,1,141,'',0),(3388,161,1464,0,264,1463,1417,10,1068047603,1,141,'',0),(3387,161,1463,0,263,1462,1464,10,1068047603,1,141,'',0),(3386,161,1462,0,262,1461,1463,10,1068047603,1,141,'',0),(3385,161,1461,0,261,1460,1462,10,1068047603,1,141,'',0),(3384,161,1460,0,260,943,1461,10,1068047603,1,141,'',0),(3383,161,943,0,259,1422,1460,10,1068047603,1,141,'',0),(3382,161,1422,0,258,1459,943,10,1068047603,1,141,'',0),(3381,161,1459,0,257,1458,1422,10,1068047603,1,141,'',0),(3380,161,1458,0,256,1440,1459,10,1068047603,1,141,'',0),(3379,161,1440,0,255,1417,1458,10,1068047603,1,141,'',0),(3378,161,1417,0,254,1457,1440,10,1068047603,1,141,'',0),(3377,161,1457,0,253,1456,1417,10,1068047603,1,141,'',0),(3376,161,1456,0,252,1455,1457,10,1068047603,1,141,'',0),(3375,161,1455,0,251,1422,1456,10,1068047603,1,141,'',0),(3374,161,1422,0,250,1455,1455,10,1068047603,1,141,'',0),(3373,161,1455,0,249,1454,1422,10,1068047603,1,141,'',0),(3372,161,1454,0,248,1453,1455,10,1068047603,1,141,'',0),(3371,161,1453,0,247,1429,1454,10,1068047603,1,141,'',0),(3370,161,1429,0,246,1452,1453,10,1068047603,1,141,'',0),(3369,161,1452,0,245,1451,1429,10,1068047603,1,141,'',0),(3368,161,1451,0,244,1450,1452,10,1068047603,1,141,'',0),(3367,161,1450,0,243,1449,1451,10,1068047603,1,141,'',0),(3366,161,1449,0,242,1416,1450,10,1068047603,1,141,'',0),(3365,161,1416,0,241,1428,1449,10,1068047603,1,141,'',0),(3364,161,1428,0,240,1448,1416,10,1068047603,1,141,'',0),(3363,161,1448,0,239,1447,1428,10,1068047603,1,141,'',0),(3362,161,1447,0,238,1446,1448,10,1068047603,1,141,'',0),(3361,161,1446,0,237,1445,1447,10,1068047603,1,141,'',0),(3360,161,1445,0,236,1444,1446,10,1068047603,1,141,'',0),(3359,161,1444,0,235,1443,1445,10,1068047603,1,141,'',0),(3358,161,1443,0,234,1442,1444,10,1068047603,1,141,'',0),(3357,161,1442,0,233,1441,1443,10,1068047603,1,141,'',0),(3356,161,1441,0,232,1440,1442,10,1068047603,1,141,'',0),(3355,161,1440,0,231,1439,1441,10,1068047603,1,141,'',0),(3354,161,1439,0,230,1438,1440,10,1068047603,1,141,'',0),(3353,161,1438,0,229,1437,1439,10,1068047603,1,141,'',0),(3352,161,1437,0,228,1436,1438,10,1068047603,1,141,'',0),(3351,161,1436,0,227,1435,1437,10,1068047603,1,141,'',0),(3350,161,1435,0,226,1418,1436,10,1068047603,1,141,'',0),(3349,161,1418,0,225,1434,1435,10,1068047603,1,141,'',0),(3348,161,1434,0,224,1433,1418,10,1068047603,1,141,'',0),(3347,161,1433,0,223,1432,1434,10,1068047603,1,141,'',0),(3346,161,1432,0,222,1431,1433,10,1068047603,1,141,'',0),(3345,161,1431,0,221,1430,1432,10,1068047603,1,141,'',0),(3344,161,1430,0,220,1429,1431,10,1068047603,1,141,'',0),(3343,161,1429,0,219,1428,1430,10,1068047603,1,141,'',0),(3342,161,1428,0,218,1427,1429,10,1068047603,1,141,'',0),(3341,161,1427,0,217,1417,1428,10,1068047603,1,141,'',0),(3340,161,1417,0,216,1418,1427,10,1068047603,1,141,'',0),(3339,161,1418,0,215,1426,1417,10,1068047603,1,141,'',0),(3338,161,1426,0,214,1425,1418,10,1068047603,1,141,'',0),(3337,161,1425,0,213,1424,1426,10,1068047603,1,141,'',0),(3336,161,1424,0,212,1423,1425,10,1068047603,1,141,'',0),(3335,161,1423,0,211,1415,1424,10,1068047603,1,141,'',0),(3334,161,1415,0,210,1422,1423,10,1068047603,1,141,'',0),(3333,161,1422,0,209,1421,1415,10,1068047603,1,141,'',0),(3332,161,1421,0,208,1420,1422,10,1068047603,1,141,'',0),(3331,161,1420,0,207,1419,1421,10,1068047603,1,141,'',0),(3330,161,1419,0,206,1418,1420,10,1068047603,1,141,'',0),(3329,161,1418,0,205,1413,1419,10,1068047603,1,141,'',0),(3328,161,1413,0,204,1412,1418,10,1068047603,1,141,'',0),(3327,161,1412,0,203,1417,1413,10,1068047603,1,141,'',0),(3326,161,1417,0,202,1416,1412,10,1068047603,1,141,'',0),(3325,161,1416,0,201,1415,1417,10,1068047603,1,141,'',0),(3324,161,1415,0,200,1414,1416,10,1068047603,1,141,'',0),(3323,161,1414,0,199,1413,1415,10,1068047603,1,141,'',0),(3322,161,1413,0,198,1412,1414,10,1068047603,1,141,'',0),(3321,161,1412,0,197,1411,1413,10,1068047603,1,141,'',0),(3320,161,1411,0,196,944,1412,10,1068047603,1,141,'',0),(3319,161,944,0,195,943,1411,10,1068047603,1,141,'',0),(3318,161,943,0,194,1428,944,10,1068047603,1,141,'',0),(3317,161,1428,0,193,1468,943,10,1068047603,1,141,'',0),(3316,161,1468,0,192,1496,1428,10,1068047603,1,141,'',0),(3315,161,1496,0,191,1485,1468,10,1068047603,1,141,'',0),(3314,161,1485,0,190,1515,1496,10,1068047603,1,141,'',0),(3313,161,1515,0,189,1514,1485,10,1068047603,1,141,'',0),(3312,161,1514,0,188,1513,1515,10,1068047603,1,141,'',0),(3311,161,1513,0,187,1416,1514,10,1068047603,1,141,'',0),(3310,161,1416,0,186,1435,1513,10,1068047603,1,141,'',0),(3309,161,1435,0,185,1487,1416,10,1068047603,1,141,'',0),(3308,161,1487,0,184,1482,1435,10,1068047603,1,141,'',0),(3307,161,1482,0,183,1512,1487,10,1068047603,1,141,'',0),(3306,161,1512,0,182,1436,1482,10,1068047603,1,141,'',0),(3305,161,1436,0,181,1432,1512,10,1068047603,1,141,'',0),(3304,161,1432,0,180,1478,1436,10,1068047603,1,141,'',0),(3303,161,1478,0,179,1511,1432,10,1068047603,1,141,'',0),(3302,161,1511,0,178,1510,1478,10,1068047603,1,141,'',0),(3301,161,1510,0,177,1509,1511,10,1068047603,1,141,'',0),(3300,161,1509,0,176,1508,1510,10,1068047603,1,141,'',0),(3299,161,1508,0,175,1428,1509,10,1068047603,1,141,'',0),(3298,161,1428,0,174,1470,1508,10,1068047603,1,141,'',0),(3297,161,1470,0,173,1415,1428,10,1068047603,1,141,'',0),(3296,161,1415,0,172,1507,1470,10,1068047603,1,141,'',0),(3295,161,1507,0,171,1442,1415,10,1068047603,1,141,'',0),(3294,161,1442,0,170,1430,1507,10,1068047603,1,141,'',0),(3293,161,1430,0,169,1487,1442,10,1068047603,1,141,'',0),(3292,161,1487,0,168,1428,1430,10,1068047603,1,141,'',0),(3291,161,1428,0,167,1506,1487,10,1068047603,1,141,'',0),(3290,161,1506,0,166,1411,1428,10,1068047603,1,141,'',0),(3289,161,1411,0,165,1478,1506,10,1068047603,1,141,'',0),(3288,161,1478,0,164,1457,1411,10,1068047603,1,141,'',0),(3287,161,1457,0,163,1468,1478,10,1068047603,1,141,'',0),(3286,161,1468,0,162,1425,1457,10,1068047603,1,141,'',0),(3285,161,1425,0,161,1505,1468,10,1068047603,1,141,'',0),(3284,161,1505,0,160,1504,1425,10,1068047603,1,141,'',0),(3283,161,1504,0,159,1478,1505,10,1068047603,1,141,'',0),(3282,161,1478,0,158,1449,1504,10,1068047603,1,141,'',0),(3281,161,1449,0,157,1503,1478,10,1068047603,1,141,'',0),(3280,161,1503,0,156,1439,1449,10,1068047603,1,141,'',0),(3279,161,1439,0,155,1484,1503,10,1068047603,1,141,'',0),(3278,161,1484,0,154,1490,1439,10,1068047603,1,141,'',0),(3277,161,1490,0,153,1421,1484,10,1068047603,1,141,'',0),(3276,161,1421,0,152,1487,1490,10,1068047603,1,141,'',0),(3275,161,1487,0,151,1449,1421,10,1068047603,1,141,'',0),(3274,161,1449,0,150,1475,1487,10,1068047603,1,141,'',0),(3273,161,1475,0,149,1502,1449,10,1068047603,1,141,'',0),(3272,161,1502,0,148,1429,1475,10,1068047603,1,141,'',0),(3271,161,1429,0,147,1468,1502,10,1068047603,1,141,'',0),(3270,161,1468,0,146,1501,1429,10,1068047603,1,141,'',0),(3269,161,1501,0,145,1500,1468,10,1068047603,1,141,'',0),(3268,161,1500,0,144,1431,1501,10,1068047603,1,141,'',0),(3267,161,1431,0,143,1499,1500,10,1068047603,1,141,'',0),(3266,161,1499,0,142,1458,1431,10,1068047603,1,141,'',0),(3265,161,1458,0,141,1462,1499,10,1068047603,1,141,'',0),(3264,161,1462,0,140,1498,1458,10,1068047603,1,141,'',0),(3263,161,1498,0,139,1440,1462,10,1068047603,1,141,'',0),(3262,161,1440,0,138,1446,1498,10,1068047603,1,141,'',0),(3261,161,1446,0,137,1466,1440,10,1068047603,1,141,'',0),(3260,161,1466,0,136,1483,1446,10,1068047603,1,141,'',0),(3259,161,1483,0,135,1450,1466,10,1068047603,1,141,'',0),(3258,161,1450,0,134,1443,1483,10,1068047603,1,141,'',0),(3257,161,1443,0,133,1411,1450,10,1068047603,1,141,'',0),(3256,161,1411,0,132,1497,1443,10,1068047603,1,141,'',0),(3255,161,1497,0,131,1462,1411,10,1068047603,1,141,'',0),(3254,161,1462,0,130,1422,1497,10,1068047603,1,141,'',0),(3253,161,1422,0,129,1496,1462,10,1068047603,1,141,'',0),(3252,161,1496,0,128,1460,1422,10,1068047603,1,141,'',0),(3251,161,1460,0,127,1428,1496,10,1068047603,1,141,'',0),(3250,161,1428,0,126,1495,1460,10,1068047603,1,141,'',0),(3249,161,1495,0,125,1494,1428,10,1068047603,1,141,'',0),(3248,161,1494,0,124,1493,1495,10,1068047603,1,141,'',0),(3247,161,1493,0,123,1492,1494,10,1068047603,1,141,'',0),(3246,161,1492,0,122,1491,1493,10,1068047603,1,141,'',0),(3245,161,1491,0,121,1417,1492,10,1068047603,1,141,'',0),(3244,161,1417,0,120,1490,1491,10,1068047603,1,141,'',0),(3243,161,1490,0,119,1489,1417,10,1068047603,1,141,'',0),(3242,161,1489,0,118,1428,1490,10,1068047603,1,141,'',0),(3241,161,1428,0,117,1458,1489,10,1068047603,1,141,'',0),(3240,161,1458,0,116,1473,1428,10,1068047603,1,141,'',0),(3239,161,1473,0,115,1422,1458,10,1068047603,1,141,'',0),(3238,161,1422,0,114,1422,1473,10,1068047603,1,141,'',0),(3237,161,1422,0,113,1488,1422,10,1068047603,1,141,'',0),(3236,161,1488,0,112,1487,1422,10,1068047603,1,141,'',0),(3539,219,1432,0,30,1431,1433,10,1068542692,1,141,'',0),(3538,219,1431,0,29,1430,1432,10,1068542692,1,141,'',0),(3537,219,1430,0,28,1429,1431,10,1068542692,1,141,'',0),(3536,219,1429,0,27,1428,1430,10,1068542692,1,141,'',0),(3533,219,1417,0,24,1418,1427,10,1068542692,1,141,'',0),(3534,219,1427,0,25,1417,1428,10,1068542692,1,141,'',0),(3535,219,1428,0,26,1427,1429,10,1068542692,1,141,'',0),(3532,219,1418,0,23,1426,1417,10,1068542692,1,141,'',0),(3531,219,1426,0,22,1425,1418,10,1068542692,1,141,'',0),(4297,45,33,0,1,32,34,14,1066388816,11,152,'',0),(4295,115,1640,0,2,7,0,14,1066991725,11,155,'',0),(4294,115,7,0,1,1640,1640,14,1066991725,11,155,'',0),(4293,115,1640,0,0,0,7,14,1066991725,11,152,'',0),(4305,116,1645,0,3,25,0,14,1066992054,11,155,'',0),(4304,116,25,0,2,1644,1645,14,1066992054,11,155,'',0),(4303,116,1644,0,1,1643,25,14,1066992054,11,152,'',0),(4302,116,1643,0,0,0,1644,14,1066992054,11,152,'',0),(4296,45,32,0,0,0,33,14,1066388816,11,152,'',0),(3526,219,1422,0,17,1421,1415,10,1068542692,1,141,'',0),(3120,218,1405,0,1,1404,1406,23,1068479750,1,201,'',0),(3121,218,1406,0,2,1405,1407,23,1068479750,1,202,'',23),(3122,218,1407,0,3,1406,0,23,1068479750,1,203,'',0),(3510,219,1517,0,1,1516,943,10,1068542692,1,140,'',0),(3511,219,943,0,2,1517,944,10,1068542692,1,141,'',0),(3512,219,944,0,3,943,1411,10,1068542692,1,141,'',0),(3513,219,1411,0,4,944,1412,10,1068542692,1,141,'',0),(3967,14,1540,0,5,1316,0,4,1033920830,2,199,'',0),(3966,14,1316,0,4,1539,1540,4,1033920830,2,198,'',0),(3631,219,1493,0,122,1492,1494,10,1068542692,1,141,'',0),(3586,219,1467,0,77,1466,1468,10,1068542692,1,141,'',0),(3630,219,1492,0,121,1491,1493,10,1068542692,1,141,'',0),(3629,219,1491,0,120,1417,1492,10,1068542692,1,141,'',0),(3628,219,1417,0,119,1490,1491,10,1068542692,1,141,'',0),(3627,219,1490,0,118,1489,1417,10,1068542692,1,141,'',0),(3626,219,1489,0,117,1428,1490,10,1068542692,1,141,'',0),(3625,219,1428,0,116,1458,1489,10,1068542692,1,141,'',0),(3632,219,1494,0,123,1493,1495,10,1068542692,1,141,'',0),(3551,219,1443,0,42,1442,1444,10,1068542692,1,141,'',0),(3550,219,1442,0,41,1441,1443,10,1068542692,1,141,'',0),(3549,219,1441,0,40,1440,1442,10,1068542692,1,141,'',0),(3548,219,1440,0,39,1439,1441,10,1068542692,1,141,'',0),(3545,219,1437,0,36,1436,1438,10,1068542692,1,141,'',0),(3546,219,1438,0,37,1437,1439,10,1068542692,1,141,'',0),(3547,219,1439,0,38,1438,1440,10,1068542692,1,141,'',0),(3624,219,1458,0,115,1473,1428,10,1068542692,1,141,'',0),(3623,219,1473,0,114,1422,1458,10,1068542692,1,141,'',0),(3622,219,1422,0,113,1422,1473,10,1068542692,1,141,'',0),(3621,219,1422,0,112,1488,1422,10,1068542692,1,141,'',0),(3620,219,1488,0,111,1487,1422,10,1068542692,1,141,'',0),(3619,219,1487,0,110,943,1488,10,1068542692,1,141,'',0),(3235,161,1487,0,111,943,1488,10,1068047603,1,141,'',0),(3234,161,943,0,110,1437,1487,10,1068047603,1,141,'',0),(3233,161,1437,0,109,1442,943,10,1068047603,1,141,'',0),(3232,161,1442,0,108,1458,1437,10,1068047603,1,141,'',0),(3231,161,1458,0,107,1417,1442,10,1068047603,1,141,'',0),(3230,161,1417,0,106,1448,1458,10,1068047603,1,141,'',0),(3229,161,1448,0,105,1486,1417,10,1068047603,1,141,'',0),(3228,161,1486,0,104,1426,1448,10,1068047603,1,141,'',0),(3227,161,1426,0,103,1475,1486,10,1068047603,1,141,'',0),(3226,161,1475,0,102,1485,1426,10,1068047603,1,141,'',0),(3225,161,1485,0,101,1484,1475,10,1068047603,1,141,'',0),(3224,161,1484,0,100,1483,1485,10,1068047603,1,141,'',0),(3223,161,1483,0,99,1482,1484,10,1068047603,1,141,'',0),(3222,161,1482,0,98,1430,1483,10,1068047603,1,141,'',0),(3221,161,1430,0,97,1481,1482,10,1068047603,1,141,'',0),(3220,161,1481,0,96,1480,1430,10,1068047603,1,141,'',0),(3219,161,1480,0,95,1479,1481,10,1068047603,1,141,'',0),(3218,161,1479,0,94,1478,1480,10,1068047603,1,141,'',0),(3217,161,1478,0,93,1477,1479,10,1068047603,1,141,'',0),(3216,161,1477,0,92,1476,1478,10,1068047603,1,141,'',0),(3215,161,1476,0,91,1441,1477,10,1068047603,1,141,'',0),(3214,161,1441,0,90,1475,1476,10,1068047603,1,141,'',0),(3213,161,1475,0,89,1474,1441,10,1068047603,1,141,'',0),(3212,161,1474,0,88,1443,1475,10,1068047603,1,141,'',0),(3211,161,1443,0,87,1473,1474,10,1068047603,1,141,'',0),(3210,161,1473,0,86,1472,1443,10,1068047603,1,141,'',0),(3209,161,1472,0,85,1471,1473,10,1068047603,1,141,'',0),(3208,161,1471,0,84,1422,1472,10,1068047603,1,141,'',0),(3207,161,1422,0,83,1471,1471,10,1068047603,1,141,'',0),(3206,161,1471,0,82,1470,1422,10,1068047603,1,141,'',0),(3205,161,1470,0,81,1469,1471,10,1068047603,1,141,'',0),(3204,161,1469,0,80,1468,1470,10,1068047603,1,141,'',0),(3203,161,1468,0,79,1467,1469,10,1068047603,1,141,'',0),(3202,161,1467,0,78,1466,1468,10,1068047603,1,141,'',0),(3201,161,1466,0,77,1465,1467,10,1068047603,1,141,'',0),(3200,161,1465,0,76,1435,1466,10,1068047603,1,141,'',0),(3199,161,1435,0,75,1417,1465,10,1068047603,1,141,'',0),(3198,161,1417,0,74,1464,1435,10,1068047603,1,141,'',0),(3197,161,1464,0,73,1463,1417,10,1068047603,1,141,'',0),(3196,161,1463,0,72,1462,1464,10,1068047603,1,141,'',0),(3195,161,1462,0,71,1461,1463,10,1068047603,1,141,'',0),(3194,161,1461,0,70,1460,1462,10,1068047603,1,141,'',0),(3193,161,1460,0,69,943,1461,10,1068047603,1,141,'',0),(3192,161,943,0,68,1422,1460,10,1068047603,1,141,'',0),(3191,161,1422,0,67,1459,943,10,1068047603,1,141,'',0),(3190,161,1459,0,66,1458,1422,10,1068047603,1,141,'',0),(3189,161,1458,0,65,1440,1459,10,1068047603,1,141,'',0),(3188,161,1440,0,64,1417,1458,10,1068047603,1,141,'',0),(3187,161,1417,0,63,1457,1440,10,1068047603,1,141,'',0),(3186,161,1457,0,62,1456,1417,10,1068047603,1,141,'',0),(3185,161,1456,0,61,1455,1457,10,1068047603,1,141,'',0),(3184,161,1455,0,60,1422,1456,10,1068047603,1,141,'',0),(3183,161,1422,0,59,1455,1455,10,1068047603,1,141,'',0),(3182,161,1455,0,58,1454,1422,10,1068047603,1,141,'',0),(3181,161,1454,0,57,1453,1455,10,1068047603,1,141,'',0),(3180,161,1453,0,56,1429,1454,10,1068047603,1,141,'',0),(3179,161,1429,0,55,1452,1453,10,1068047603,1,141,'',0),(3178,161,1452,0,54,1451,1429,10,1068047603,1,141,'',0),(3177,161,1451,0,53,1450,1452,10,1068047603,1,141,'',0),(3176,161,1450,0,52,1449,1451,10,1068047603,1,141,'',0),(3175,161,1449,0,51,1416,1450,10,1068047603,1,141,'',0),(3174,161,1416,0,50,1428,1449,10,1068047603,1,141,'',0),(3173,161,1428,0,49,1448,1416,10,1068047603,1,141,'',0),(3172,161,1448,0,48,1447,1428,10,1068047603,1,141,'',0),(3171,161,1447,0,47,1446,1448,10,1068047603,1,141,'',0),(3170,161,1446,0,46,1445,1447,10,1068047603,1,141,'',0),(3169,161,1445,0,45,1444,1446,10,1068047603,1,141,'',0),(3168,161,1444,0,44,1443,1445,10,1068047603,1,141,'',0),(3167,161,1443,0,43,1442,1444,10,1068047603,1,141,'',0),(3166,161,1442,0,42,1441,1443,10,1068047603,1,141,'',0),(3165,161,1441,0,41,1440,1442,10,1068047603,1,141,'',0),(3164,161,1440,0,40,1439,1441,10,1068047603,1,141,'',0),(3163,161,1439,0,39,1438,1440,10,1068047603,1,141,'',0),(3162,161,1438,0,38,1437,1439,10,1068047603,1,141,'',0),(3161,161,1437,0,37,1436,1438,10,1068047603,1,141,'',0),(3160,161,1436,0,36,1435,1437,10,1068047603,1,141,'',0),(3159,161,1435,0,35,1418,1436,10,1068047603,1,141,'',0),(3158,161,1418,0,34,1434,1435,10,1068047603,1,141,'',0),(3157,161,1434,0,33,1433,1418,10,1068047603,1,141,'',0),(3156,161,1433,0,32,1432,1434,10,1068047603,1,141,'',0),(3155,161,1432,0,31,1431,1433,10,1068047603,1,141,'',0),(3154,161,1431,0,30,1430,1432,10,1068047603,1,141,'',0),(3153,161,1430,0,29,1429,1431,10,1068047603,1,141,'',0),(3152,161,1429,0,28,1428,1430,10,1068047603,1,141,'',0),(3151,161,1428,0,27,1427,1429,10,1068047603,1,141,'',0),(3150,161,1427,0,26,1417,1428,10,1068047603,1,141,'',0),(3149,161,1417,0,25,1418,1427,10,1068047603,1,141,'',0),(3148,161,1418,0,24,1426,1417,10,1068047603,1,141,'',0),(3147,161,1426,0,23,1425,1418,10,1068047603,1,141,'',0),(3146,161,1425,0,22,1424,1426,10,1068047603,1,141,'',0),(3145,161,1424,0,21,1423,1425,10,1068047603,1,141,'',0),(3144,161,1423,0,20,1415,1424,10,1068047603,1,141,'',0),(3143,161,1415,0,19,1422,1423,10,1068047603,1,141,'',0),(3142,161,1422,0,18,1421,1415,10,1068047603,1,141,'',0),(3141,161,1421,0,17,1420,1422,10,1068047603,1,141,'',0),(3140,161,1420,0,16,1419,1421,10,1068047603,1,141,'',0),(3139,161,1419,0,15,1418,1420,10,1068047603,1,141,'',0),(3138,161,1418,0,14,1413,1419,10,1068047603,1,141,'',0),(3137,161,1413,0,13,1412,1418,10,1068047603,1,141,'',0),(3136,161,1412,0,12,1417,1413,10,1068047603,1,141,'',0),(3135,161,1417,0,11,1416,1412,10,1068047603,1,141,'',0),(3134,161,1416,0,10,1415,1417,10,1068047603,1,141,'',0),(3133,161,1415,0,9,1414,1416,10,1068047603,1,141,'',0),(3132,161,1414,0,8,1413,1415,10,1068047603,1,141,'',0),(3131,161,1413,0,7,1412,1414,10,1068047603,1,141,'',0),(3130,161,1412,0,6,1411,1413,10,1068047603,1,141,'',0),(3129,161,1411,0,5,944,1412,10,1068047603,1,141,'',0),(3128,161,944,0,4,943,1411,10,1068047603,1,141,'',0),(3127,161,943,0,3,1410,944,10,1068047603,1,141,'',0),(3126,161,1410,0,2,33,943,10,1068047603,1,140,'',0),(3124,161,1409,0,0,0,33,10,1068047603,1,140,'',0),(3125,161,33,0,1,1409,1410,10,1068047603,1,140,'',0),(3618,219,943,0,109,1437,1487,10,1068542692,1,141,'',0),(3617,219,1437,0,108,1442,943,10,1068542692,1,141,'',0),(3616,219,1442,0,107,1458,1437,10,1068542692,1,141,'',0),(3615,219,1458,0,106,1417,1442,10,1068542692,1,141,'',0),(3614,219,1417,0,105,1448,1458,10,1068542692,1,141,'',0),(3613,219,1448,0,104,1486,1417,10,1068542692,1,141,'',0),(3612,219,1486,0,103,1426,1448,10,1068542692,1,141,'',0),(3611,219,1426,0,102,1475,1486,10,1068542692,1,141,'',0),(3610,219,1475,0,101,1485,1426,10,1068542692,1,141,'',0),(3609,219,1485,0,100,1484,1475,10,1068542692,1,141,'',0),(3608,219,1484,0,99,1483,1485,10,1068542692,1,141,'',0),(3607,219,1483,0,98,1482,1484,10,1068542692,1,141,'',0),(3606,219,1482,0,97,1430,1483,10,1068542692,1,141,'',0),(3605,219,1430,0,96,1481,1482,10,1068542692,1,141,'',0),(3604,219,1481,0,95,1480,1430,10,1068542692,1,141,'',0),(3603,219,1480,0,94,1479,1481,10,1068542692,1,141,'',0),(3602,219,1479,0,93,1478,1480,10,1068542692,1,141,'',0),(3601,219,1478,0,92,1477,1479,10,1068542692,1,141,'',0),(3600,219,1477,0,91,1476,1478,10,1068542692,1,141,'',0),(3599,219,1476,0,90,1441,1477,10,1068542692,1,141,'',0),(3598,219,1441,0,89,1475,1476,10,1068542692,1,141,'',0),(3597,219,1475,0,88,1474,1441,10,1068542692,1,141,'',0),(3596,219,1474,0,87,1443,1475,10,1068542692,1,141,'',0),(3595,219,1443,0,86,1473,1474,10,1068542692,1,141,'',0),(3594,219,1473,0,85,1472,1443,10,1068542692,1,141,'',0),(3593,219,1472,0,84,1471,1473,10,1068542692,1,141,'',0),(3592,219,1471,0,83,1422,1472,10,1068542692,1,141,'',0),(3591,219,1422,0,82,1471,1471,10,1068542692,1,141,'',0),(3544,219,1436,0,35,1435,1437,10,1068542692,1,141,'',0),(3543,219,1435,0,34,1418,1436,10,1068542692,1,141,'',0),(3542,219,1418,0,33,1434,1435,10,1068542692,1,141,'',0),(3540,219,1433,0,31,1432,1434,10,1068542692,1,141,'',0),(3541,219,1434,0,32,1433,1418,10,1068542692,1,141,'',0),(3589,219,1470,0,80,1469,1471,10,1068542692,1,141,'',0),(3568,219,1455,0,59,1422,1456,10,1068542692,1,141,'',0),(3567,219,1422,0,58,1455,1455,10,1068542692,1,141,'',0),(3566,219,1455,0,57,1454,1422,10,1068542692,1,141,'',0),(3588,219,1469,0,79,1468,1470,10,1068542692,1,141,'',0),(3587,219,1468,0,78,1467,1469,10,1068542692,1,141,'',0),(3565,219,1454,0,56,1453,1455,10,1068542692,1,141,'',0),(3564,219,1453,0,55,1429,1454,10,1068542692,1,141,'',0),(3563,219,1429,0,54,1452,1453,10,1068542692,1,141,'',0),(3561,219,1451,0,52,1450,1452,10,1068542692,1,141,'',0),(3560,219,1450,0,51,1449,1451,10,1068542692,1,141,'',0),(3585,219,1466,0,76,1465,1467,10,1068542692,1,141,'',0),(3584,219,1465,0,75,1435,1466,10,1068542692,1,141,'',0),(3583,219,1435,0,74,1417,1465,10,1068542692,1,141,'',0),(3582,219,1417,0,73,1464,1435,10,1068542692,1,141,'',0),(3581,219,1464,0,72,1463,1417,10,1068542692,1,141,'',0),(3580,219,1463,0,71,1462,1464,10,1068542692,1,141,'',0),(3579,219,1462,0,70,1461,1463,10,1068542692,1,141,'',0),(3578,219,1461,0,69,1460,1462,10,1068542692,1,141,'',0),(3577,219,1460,0,68,943,1461,10,1068542692,1,141,'',0),(3559,219,1449,0,50,1416,1450,10,1068542692,1,141,'',0),(3562,219,1452,0,53,1451,1429,10,1068542692,1,141,'',0),(3965,14,1539,0,3,1538,1316,4,1033920830,2,198,'',0),(3964,14,1538,0,2,1537,1539,4,1033920830,2,197,'',0),(3963,14,1537,0,1,1536,1538,4,1033920830,2,9,'',0),(3962,14,1536,0,0,0,1537,4,1033920830,2,8,'',0),(2993,206,1140,0,0,0,1318,4,1068123599,2,8,'',0),(2994,206,1318,0,1,1140,1094,4,1068123599,2,9,'',0),(2995,206,1094,0,2,1318,1319,4,1068123599,2,197,'',0),(2996,206,1319,0,3,1094,1320,4,1068123599,2,197,'',0),(2997,206,1320,0,4,1319,1316,4,1068123599,2,198,'',0),(2998,206,1316,0,5,1320,1321,4,1068123599,2,198,'',0),(2999,206,1321,0,6,1316,0,4,1068123599,2,199,'',0),(3576,219,943,0,67,1422,1460,10,1068542692,1,141,'',0),(3575,219,1422,0,66,1459,943,10,1068542692,1,141,'',0),(3574,219,1459,0,65,1458,1422,10,1068542692,1,141,'',0),(3573,219,1458,0,64,1440,1459,10,1068542692,1,141,'',0),(3572,219,1440,0,63,1417,1458,10,1068542692,1,141,'',0),(3571,219,1417,0,62,1457,1440,10,1068542692,1,141,'',0),(3570,219,1457,0,61,1456,1417,10,1068542692,1,141,'',0),(3569,219,1456,0,60,1455,1457,10,1068542692,1,141,'',0),(3119,218,1404,0,0,0,1405,23,1068479750,1,201,'',0),(3114,217,1399,0,0,0,0,1,1068474983,1,4,'',0),(3113,216,1398,0,0,0,0,1,1068474919,1,4,'',0),(3112,215,1397,0,0,0,0,1,1068474891,1,4,'',0),(3111,214,1396,0,0,0,0,1,1068474871,1,4,'',0),(3971,211,1544,0,3,1543,0,23,1068472652,1,203,'',0),(3961,213,1534,0,2,1535,0,1,1068473231,1,119,'',0),(4845,1,1940,0,0,0,0,1,1033917596,1,4,'',0),(3960,213,1535,0,1,1534,1534,1,1068473231,1,119,'',0),(3959,213,1534,0,0,0,1535,1,1068473231,1,4,'',0),(3558,219,1416,0,49,1428,1449,10,1068542692,1,141,'',0),(3557,219,1428,0,48,1448,1416,10,1068542692,1,141,'',0),(3556,219,1448,0,47,1447,1428,10,1068542692,1,141,'',0),(3555,219,1447,0,46,1446,1448,10,1068542692,1,141,'',0),(3554,219,1446,0,45,1445,1447,10,1068542692,1,141,'',0),(3553,219,1445,0,44,1444,1446,10,1068542692,1,141,'',0),(3552,219,1444,0,43,1443,1445,10,1068542692,1,141,'',0),(3972,227,1545,0,0,0,1546,23,1068557743,1,201,'',0),(3970,211,1543,0,2,1542,1544,23,1068472652,1,203,'',0),(3969,211,1542,0,1,1541,1543,23,1068472652,1,202,'',0),(3968,211,1541,0,0,0,1542,23,1068472652,1,201,'',0),(3110,212,1395,0,10,1394,0,23,1068472760,1,203,'',0),(3109,212,1394,0,9,1393,1395,23,1068472760,1,203,'',0),(3108,212,1393,0,8,1392,1394,23,1068472760,1,203,'',0),(3107,212,1392,0,7,1391,1393,23,1068472760,1,203,'',0),(3106,212,1391,0,6,1390,1392,23,1068472760,1,203,'',0),(3105,212,1390,0,5,1389,1391,23,1068472760,1,203,'',0),(3104,212,1389,0,4,1388,1390,23,1068472760,1,203,'',0),(3103,212,1388,0,3,1387,1389,23,1068472760,1,203,'',0),(3102,212,1387,0,2,1387,1388,23,1068472760,1,202,'',0),(3101,212,1387,0,1,1386,1387,23,1068472760,1,201,'',0),(3100,212,1386,0,0,0,1387,23,1068472760,1,201,'',0),(3638,219,1462,0,129,1422,1497,10,1068542692,1,141,'',0),(3639,219,1497,0,130,1462,1411,10,1068542692,1,141,'',0),(3640,219,1411,0,131,1497,1443,10,1068542692,1,141,'',0),(3641,219,1443,0,132,1411,1450,10,1068542692,1,141,'',0),(3642,219,1450,0,133,1443,1483,10,1068542692,1,141,'',0),(3643,219,1483,0,134,1450,1466,10,1068542692,1,141,'',0),(3644,219,1466,0,135,1483,1446,10,1068542692,1,141,'',0),(3645,219,1446,0,136,1466,1440,10,1068542692,1,141,'',0),(3646,219,1440,0,137,1446,1498,10,1068542692,1,141,'',0),(3647,219,1498,0,138,1440,1462,10,1068542692,1,141,'',0),(3648,219,1462,0,139,1498,1458,10,1068542692,1,141,'',0),(3649,219,1458,0,140,1462,1499,10,1068542692,1,141,'',0),(3650,219,1499,0,141,1458,1431,10,1068542692,1,141,'',0),(3651,219,1431,0,142,1499,1500,10,1068542692,1,141,'',0),(3652,219,1500,0,143,1431,1501,10,1068542692,1,141,'',0),(3653,219,1501,0,144,1500,1468,10,1068542692,1,141,'',0),(3654,219,1468,0,145,1501,1429,10,1068542692,1,141,'',0),(3655,219,1429,0,146,1468,1502,10,1068542692,1,141,'',0),(3656,219,1502,0,147,1429,1475,10,1068542692,1,141,'',0),(3657,219,1475,0,148,1502,1449,10,1068542692,1,141,'',0),(3658,219,1449,0,149,1475,1487,10,1068542692,1,141,'',0),(3659,219,1487,0,150,1449,1421,10,1068542692,1,141,'',0),(3660,219,1421,0,151,1487,1490,10,1068542692,1,141,'',0),(3661,219,1490,0,152,1421,1484,10,1068542692,1,141,'',0),(3662,219,1484,0,153,1490,1439,10,1068542692,1,141,'',0),(3663,219,1439,0,154,1484,1503,10,1068542692,1,141,'',0),(3664,219,1503,0,155,1439,1449,10,1068542692,1,141,'',0),(3665,219,1449,0,156,1503,1478,10,1068542692,1,141,'',0),(3666,219,1478,0,157,1449,1504,10,1068542692,1,141,'',0),(3667,219,1504,0,158,1478,1505,10,1068542692,1,141,'',0),(3668,219,1505,0,159,1504,1425,10,1068542692,1,141,'',0),(3669,219,1425,0,160,1505,1468,10,1068542692,1,141,'',0),(3670,219,1468,0,161,1425,1457,10,1068542692,1,141,'',0),(3671,219,1457,0,162,1468,1478,10,1068542692,1,141,'',0),(3672,219,1478,0,163,1457,1411,10,1068542692,1,141,'',0),(3673,219,1411,0,164,1478,1506,10,1068542692,1,141,'',0),(3674,219,1506,0,165,1411,1428,10,1068542692,1,141,'',0),(3675,219,1428,0,166,1506,1487,10,1068542692,1,141,'',0),(3676,219,1487,0,167,1428,1430,10,1068542692,1,141,'',0),(3677,219,1430,0,168,1487,1442,10,1068542692,1,141,'',0),(3678,219,1442,0,169,1430,1507,10,1068542692,1,141,'',0),(3679,219,1507,0,170,1442,1415,10,1068542692,1,141,'',0),(3680,219,1415,0,171,1507,1470,10,1068542692,1,141,'',0),(3681,219,1470,0,172,1415,1428,10,1068542692,1,141,'',0),(3682,219,1428,0,173,1470,1508,10,1068542692,1,141,'',0),(3683,219,1508,0,174,1428,1509,10,1068542692,1,141,'',0),(3684,219,1509,0,175,1508,1510,10,1068542692,1,141,'',0),(3685,219,1510,0,176,1509,1511,10,1068542692,1,141,'',0),(3686,219,1511,0,177,1510,1478,10,1068542692,1,141,'',0),(3687,219,1478,0,178,1511,1432,10,1068542692,1,141,'',0),(3688,219,1432,0,179,1478,1436,10,1068542692,1,141,'',0),(3689,219,1436,0,180,1432,1512,10,1068542692,1,141,'',0),(3690,219,1512,0,181,1436,1482,10,1068542692,1,141,'',0),(3691,219,1482,0,182,1512,1487,10,1068542692,1,141,'',0),(3692,219,1487,0,183,1482,1435,10,1068542692,1,141,'',0),(3693,219,1435,0,184,1487,1416,10,1068542692,1,141,'',0),(3694,219,1416,0,185,1435,1513,10,1068542692,1,141,'',0),(3695,219,1513,0,186,1416,1514,10,1068542692,1,141,'',0),(3696,219,1514,0,187,1513,1515,10,1068542692,1,141,'',0),(3697,219,1515,0,188,1514,1485,10,1068542692,1,141,'',0),(3698,219,1485,0,189,1515,1496,10,1068542692,1,141,'',0),(3699,219,1496,0,190,1485,1468,10,1068542692,1,141,'',0),(3700,219,1468,0,191,1496,1428,10,1068542692,1,141,'',0),(3701,219,1428,0,192,1468,943,10,1068542692,1,141,'',0),(3702,219,943,0,193,1428,944,10,1068542692,1,141,'',0),(3703,219,944,0,194,943,1411,10,1068542692,1,141,'',0),(3704,219,1411,0,195,944,1412,10,1068542692,1,141,'',0),(3705,219,1412,0,196,1411,1413,10,1068542692,1,141,'',0),(3706,219,1413,0,197,1412,1414,10,1068542692,1,141,'',0),(3707,219,1414,0,198,1413,1415,10,1068542692,1,141,'',0),(3708,219,1415,0,199,1414,1416,10,1068542692,1,141,'',0),(3709,219,1416,0,200,1415,1417,10,1068542692,1,141,'',0),(3710,219,1417,0,201,1416,1412,10,1068542692,1,141,'',0),(3711,219,1412,0,202,1417,1413,10,1068542692,1,141,'',0),(3712,219,1413,0,203,1412,1418,10,1068542692,1,141,'',0),(3713,219,1418,0,204,1413,1419,10,1068542692,1,141,'',0),(3714,219,1419,0,205,1418,1420,10,1068542692,1,141,'',0),(3715,219,1420,0,206,1419,1421,10,1068542692,1,141,'',0),(3716,219,1421,0,207,1420,1422,10,1068542692,1,141,'',0),(3717,219,1422,0,208,1421,1415,10,1068542692,1,141,'',0),(3718,219,1415,0,209,1422,1423,10,1068542692,1,141,'',0),(3719,219,1423,0,210,1415,1424,10,1068542692,1,141,'',0),(3720,219,1424,0,211,1423,1425,10,1068542692,1,141,'',0),(3721,219,1425,0,212,1424,1426,10,1068542692,1,141,'',0),(3722,219,1426,0,213,1425,1418,10,1068542692,1,141,'',0),(3723,219,1418,0,214,1426,1417,10,1068542692,1,141,'',0),(3724,219,1417,0,215,1418,1427,10,1068542692,1,141,'',0),(3725,219,1427,0,216,1417,1428,10,1068542692,1,141,'',0),(3726,219,1428,0,217,1427,1429,10,1068542692,1,141,'',0),(3727,219,1429,0,218,1428,1430,10,1068542692,1,141,'',0),(3728,219,1430,0,219,1429,1431,10,1068542692,1,141,'',0),(3729,219,1431,0,220,1430,1432,10,1068542692,1,141,'',0),(3730,219,1432,0,221,1431,1433,10,1068542692,1,141,'',0),(3731,219,1433,0,222,1432,1434,10,1068542692,1,141,'',0),(3732,219,1434,0,223,1433,1418,10,1068542692,1,141,'',0),(3733,219,1418,0,224,1434,1435,10,1068542692,1,141,'',0),(3734,219,1435,0,225,1418,1436,10,1068542692,1,141,'',0),(3735,219,1436,0,226,1435,1437,10,1068542692,1,141,'',0),(3736,219,1437,0,227,1436,1438,10,1068542692,1,141,'',0),(3737,219,1438,0,228,1437,1439,10,1068542692,1,141,'',0),(3738,219,1439,0,229,1438,1440,10,1068542692,1,141,'',0),(3739,219,1440,0,230,1439,1441,10,1068542692,1,141,'',0),(3740,219,1441,0,231,1440,1442,10,1068542692,1,141,'',0),(3741,219,1442,0,232,1441,1443,10,1068542692,1,141,'',0),(3742,219,1443,0,233,1442,1444,10,1068542692,1,141,'',0),(3743,219,1444,0,234,1443,1445,10,1068542692,1,141,'',0),(3744,219,1445,0,235,1444,1446,10,1068542692,1,141,'',0),(3745,219,1446,0,236,1445,1447,10,1068542692,1,141,'',0),(3746,219,1447,0,237,1446,1448,10,1068542692,1,141,'',0),(3747,219,1448,0,238,1447,1428,10,1068542692,1,141,'',0),(3748,219,1428,0,239,1448,1416,10,1068542692,1,141,'',0),(3749,219,1416,0,240,1428,1449,10,1068542692,1,141,'',0),(3750,219,1449,0,241,1416,1450,10,1068542692,1,141,'',0),(3751,219,1450,0,242,1449,1451,10,1068542692,1,141,'',0),(3752,219,1451,0,243,1450,1452,10,1068542692,1,141,'',0),(3753,219,1452,0,244,1451,1429,10,1068542692,1,141,'',0),(3754,219,1429,0,245,1452,1453,10,1068542692,1,141,'',0),(3755,219,1453,0,246,1429,1454,10,1068542692,1,141,'',0),(3756,219,1454,0,247,1453,1455,10,1068542692,1,141,'',0),(3757,219,1455,0,248,1454,1422,10,1068542692,1,141,'',0),(3758,219,1422,0,249,1455,1455,10,1068542692,1,141,'',0),(3759,219,1455,0,250,1422,1456,10,1068542692,1,141,'',0),(3760,219,1456,0,251,1455,1457,10,1068542692,1,141,'',0),(3761,219,1457,0,252,1456,1417,10,1068542692,1,141,'',0),(3762,219,1417,0,253,1457,1440,10,1068542692,1,141,'',0),(3763,219,1440,0,254,1417,1458,10,1068542692,1,141,'',0),(3764,219,1458,0,255,1440,1459,10,1068542692,1,141,'',0),(3765,219,1459,0,256,1458,1422,10,1068542692,1,141,'',0),(3766,219,1422,0,257,1459,943,10,1068542692,1,141,'',0),(3767,219,943,0,258,1422,1460,10,1068542692,1,141,'',0),(3768,219,1460,0,259,943,1461,10,1068542692,1,141,'',0),(3769,219,1461,0,260,1460,1462,10,1068542692,1,141,'',0),(3770,219,1462,0,261,1461,1463,10,1068542692,1,141,'',0),(3771,219,1463,0,262,1462,1464,10,1068542692,1,141,'',0),(3772,219,1464,0,263,1463,1417,10,1068542692,1,141,'',0),(3773,219,1417,0,264,1464,1435,10,1068542692,1,141,'',0),(3774,219,1435,0,265,1417,1465,10,1068542692,1,141,'',0),(3775,219,1465,0,266,1435,1466,10,1068542692,1,141,'',0),(3776,219,1466,0,267,1465,1467,10,1068542692,1,141,'',0),(3777,219,1467,0,268,1466,1468,10,1068542692,1,141,'',0),(3778,219,1468,0,269,1467,1469,10,1068542692,1,141,'',0),(3779,219,1469,0,270,1468,1470,10,1068542692,1,141,'',0),(3780,219,1470,0,271,1469,1471,10,1068542692,1,141,'',0),(3781,219,1471,0,272,1470,1422,10,1068542692,1,141,'',0),(3782,219,1422,0,273,1471,1471,10,1068542692,1,141,'',0),(3783,219,1471,0,274,1422,1472,10,1068542692,1,141,'',0),(3784,219,1472,0,275,1471,1473,10,1068542692,1,141,'',0),(3785,219,1473,0,276,1472,1443,10,1068542692,1,141,'',0),(3786,219,1443,0,277,1473,1474,10,1068542692,1,141,'',0),(3787,219,1474,0,278,1443,1475,10,1068542692,1,141,'',0),(3788,219,1475,0,279,1474,1441,10,1068542692,1,141,'',0),(3789,219,1441,0,280,1475,1476,10,1068542692,1,141,'',0),(3790,219,1476,0,281,1441,1477,10,1068542692,1,141,'',0),(3791,219,1477,0,282,1476,1478,10,1068542692,1,141,'',0),(3792,219,1478,0,283,1477,1479,10,1068542692,1,141,'',0),(3793,219,1479,0,284,1478,1480,10,1068542692,1,141,'',0),(3794,219,1480,0,285,1479,1481,10,1068542692,1,141,'',0),(3795,219,1481,0,286,1480,1430,10,1068542692,1,141,'',0),(3796,219,1430,0,287,1481,1482,10,1068542692,1,141,'',0),(3797,219,1482,0,288,1430,1483,10,1068542692,1,141,'',0),(3798,219,1483,0,289,1482,1484,10,1068542692,1,141,'',0),(3799,219,1484,0,290,1483,1485,10,1068542692,1,141,'',0),(3800,219,1485,0,291,1484,1475,10,1068542692,1,141,'',0),(3801,219,1475,0,292,1485,1426,10,1068542692,1,141,'',0),(3802,219,1426,0,293,1475,1486,10,1068542692,1,141,'',0),(3803,219,1486,0,294,1426,1448,10,1068542692,1,141,'',0),(3804,219,1448,0,295,1486,1417,10,1068542692,1,141,'',0),(3805,219,1417,0,296,1448,1458,10,1068542692,1,141,'',0),(3806,219,1458,0,297,1417,1442,10,1068542692,1,141,'',0),(3807,219,1442,0,298,1458,1437,10,1068542692,1,141,'',0),(3808,219,1437,0,299,1442,943,10,1068542692,1,141,'',0),(3809,219,943,0,300,1437,1487,10,1068542692,1,141,'',0),(3810,219,1487,0,301,943,1488,10,1068542692,1,141,'',0),(3811,219,1488,0,302,1487,1422,10,1068542692,1,141,'',0),(3812,219,1422,0,303,1488,1422,10,1068542692,1,141,'',0),(3813,219,1422,0,304,1422,1473,10,1068542692,1,141,'',0),(3814,219,1473,0,305,1422,1458,10,1068542692,1,141,'',0),(3815,219,1458,0,306,1473,1428,10,1068542692,1,141,'',0),(3816,219,1428,0,307,1458,1489,10,1068542692,1,141,'',0),(3817,219,1489,0,308,1428,1490,10,1068542692,1,141,'',0),(3818,219,1490,0,309,1489,1417,10,1068542692,1,141,'',0),(3819,219,1417,0,310,1490,1491,10,1068542692,1,141,'',0),(3820,219,1491,0,311,1417,1492,10,1068542692,1,141,'',0),(3821,219,1492,0,312,1491,1493,10,1068542692,1,141,'',0),(3822,219,1493,0,313,1492,1494,10,1068542692,1,141,'',0),(3823,219,1494,0,314,1493,1495,10,1068542692,1,141,'',0),(3824,219,1495,0,315,1494,1428,10,1068542692,1,141,'',0),(3825,219,1428,0,316,1495,1460,10,1068542692,1,141,'',0),(3826,219,1460,0,317,1428,1496,10,1068542692,1,141,'',0),(3827,219,1496,0,318,1460,1422,10,1068542692,1,141,'',0),(3828,219,1422,0,319,1496,1462,10,1068542692,1,141,'',0),(3829,219,1462,0,320,1422,1497,10,1068542692,1,141,'',0),(3830,219,1497,0,321,1462,1411,10,1068542692,1,141,'',0),(3831,219,1411,0,322,1497,1443,10,1068542692,1,141,'',0),(3832,219,1443,0,323,1411,1450,10,1068542692,1,141,'',0),(3833,219,1450,0,324,1443,1483,10,1068542692,1,141,'',0),(3834,219,1483,0,325,1450,1466,10,1068542692,1,141,'',0),(3835,219,1466,0,326,1483,1446,10,1068542692,1,141,'',0),(3836,219,1446,0,327,1466,1440,10,1068542692,1,141,'',0),(3837,219,1440,0,328,1446,1498,10,1068542692,1,141,'',0),(3838,219,1498,0,329,1440,1462,10,1068542692,1,141,'',0),(3839,219,1462,0,330,1498,1458,10,1068542692,1,141,'',0),(3840,219,1458,0,331,1462,1499,10,1068542692,1,141,'',0),(3841,219,1499,0,332,1458,1431,10,1068542692,1,141,'',0),(3842,219,1431,0,333,1499,1500,10,1068542692,1,141,'',0),(3843,219,1500,0,334,1431,1501,10,1068542692,1,141,'',0),(3844,219,1501,0,335,1500,1468,10,1068542692,1,141,'',0),(3845,219,1468,0,336,1501,1429,10,1068542692,1,141,'',0),(3846,219,1429,0,337,1468,1502,10,1068542692,1,141,'',0),(3847,219,1502,0,338,1429,1475,10,1068542692,1,141,'',0),(3848,219,1475,0,339,1502,1449,10,1068542692,1,141,'',0),(3849,219,1449,0,340,1475,1487,10,1068542692,1,141,'',0),(3850,219,1487,0,341,1449,1421,10,1068542692,1,141,'',0),(3851,219,1421,0,342,1487,1490,10,1068542692,1,141,'',0),(3852,219,1490,0,343,1421,1484,10,1068542692,1,141,'',0),(3853,219,1484,0,344,1490,1439,10,1068542692,1,141,'',0),(3854,219,1439,0,345,1484,1503,10,1068542692,1,141,'',0),(3855,219,1503,0,346,1439,1449,10,1068542692,1,141,'',0),(3856,219,1449,0,347,1503,1478,10,1068542692,1,141,'',0),(3857,219,1478,0,348,1449,1504,10,1068542692,1,141,'',0),(3858,219,1504,0,349,1478,1505,10,1068542692,1,141,'',0),(3859,219,1505,0,350,1504,1425,10,1068542692,1,141,'',0),(3860,219,1425,0,351,1505,1468,10,1068542692,1,141,'',0),(3861,219,1468,0,352,1425,1457,10,1068542692,1,141,'',0),(3862,219,1457,0,353,1468,1478,10,1068542692,1,141,'',0),(3863,219,1478,0,354,1457,1411,10,1068542692,1,141,'',0),(3864,219,1411,0,355,1478,1506,10,1068542692,1,141,'',0),(3865,219,1506,0,356,1411,1428,10,1068542692,1,141,'',0),(3866,219,1428,0,357,1506,1487,10,1068542692,1,141,'',0),(3867,219,1487,0,358,1428,1430,10,1068542692,1,141,'',0),(3868,219,1430,0,359,1487,1442,10,1068542692,1,141,'',0),(3869,219,1442,0,360,1430,1507,10,1068542692,1,141,'',0),(3870,219,1507,0,361,1442,1415,10,1068542692,1,141,'',0),(3871,219,1415,0,362,1507,1470,10,1068542692,1,141,'',0),(3872,219,1470,0,363,1415,1428,10,1068542692,1,141,'',0),(3873,219,1428,0,364,1470,1508,10,1068542692,1,141,'',0),(3874,219,1508,0,365,1428,1509,10,1068542692,1,141,'',0),(3875,219,1509,0,366,1508,1510,10,1068542692,1,141,'',0),(3876,219,1510,0,367,1509,1511,10,1068542692,1,141,'',0),(3877,219,1511,0,368,1510,1478,10,1068542692,1,141,'',0),(3878,219,1478,0,369,1511,1432,10,1068542692,1,141,'',0),(3879,219,1432,0,370,1478,1436,10,1068542692,1,141,'',0),(3880,219,1436,0,371,1432,1512,10,1068542692,1,141,'',0),(3881,219,1512,0,372,1436,1482,10,1068542692,1,141,'',0),(3882,219,1482,0,373,1512,1487,10,1068542692,1,141,'',0),(3883,219,1487,0,374,1482,1435,10,1068542692,1,141,'',0),(3884,219,1435,0,375,1487,1416,10,1068542692,1,141,'',0),(3885,219,1416,0,376,1435,1513,10,1068542692,1,141,'',0),(3886,219,1513,0,377,1416,1514,10,1068542692,1,141,'',0),(3887,219,1514,0,378,1513,1515,10,1068542692,1,141,'',0),(3888,219,1515,0,379,1514,1485,10,1068542692,1,141,'',0),(3889,219,1485,0,380,1515,1496,10,1068542692,1,141,'',0),(3890,219,1496,0,381,1485,1468,10,1068542692,1,141,'',0),(3891,219,1468,0,382,1496,1428,10,1068542692,1,141,'',0),(3892,219,1428,0,383,1468,0,10,1068542692,1,141,'',0),(3893,220,1518,0,0,0,1519,10,1068542738,1,140,'',0),(3894,220,1519,0,1,1518,1520,10,1068542738,1,140,'',0),(3895,220,1520,0,2,1519,943,10,1068542738,1,140,'',0),(3896,220,943,0,3,1520,944,10,1068542738,1,141,'',0),(3897,220,944,0,4,943,943,10,1068542738,1,141,'',0),(3898,220,943,0,5,944,944,10,1068542738,1,141,'',0),(3899,220,944,0,6,943,943,10,1068542738,1,141,'',0),(3900,220,943,0,7,944,944,10,1068542738,1,141,'',0),(3901,220,944,0,8,943,943,10,1068542738,1,141,'',0),(3902,220,943,0,9,944,944,10,1068542738,1,141,'',0),(3903,220,944,0,10,943,943,10,1068542738,1,141,'',0),(3904,220,943,0,11,944,944,10,1068542738,1,141,'',0),(3905,220,944,0,12,943,943,10,1068542738,1,141,'',0),(3906,220,943,0,13,944,944,10,1068542738,1,141,'',0),(3907,220,944,0,14,943,943,10,1068542738,1,141,'',0),(3908,220,943,0,15,944,944,10,1068542738,1,141,'',0),(3909,220,944,0,16,943,943,10,1068542738,1,141,'',0),(3910,220,943,0,17,944,944,10,1068542738,1,141,'',0),(3911,220,944,0,18,943,943,10,1068542738,1,141,'',0),(3912,220,943,0,19,944,944,10,1068542738,1,141,'',0),(3913,220,944,0,20,943,943,10,1068542738,1,141,'',0),(3914,220,943,0,21,944,944,10,1068542738,1,141,'',0),(3915,220,944,0,22,943,943,10,1068542738,1,141,'',0),(3916,220,943,0,23,944,944,10,1068542738,1,141,'',0),(3917,220,944,0,24,943,943,10,1068542738,1,141,'',0),(3918,220,943,0,25,944,944,10,1068542738,1,141,'',0),(3919,220,944,0,26,943,943,10,1068542738,1,141,'',0),(3920,220,943,0,27,944,944,10,1068542738,1,141,'',0),(3921,220,944,0,28,943,943,10,1068542738,1,141,'',0),(3922,220,943,0,29,944,944,10,1068542738,1,141,'',0),(3923,220,944,0,30,943,943,10,1068542738,1,141,'',0),(3924,220,943,0,31,944,944,10,1068542738,1,141,'',0),(3925,220,944,0,32,943,943,10,1068542738,1,141,'',0),(3926,220,943,0,33,944,944,10,1068542738,1,141,'',0),(3927,220,944,0,34,943,943,10,1068542738,1,141,'',0),(3928,220,943,0,35,944,944,10,1068542738,1,141,'',0),(3929,220,944,0,36,943,943,10,1068542738,1,141,'',0),(3930,220,943,0,37,944,944,10,1068542738,1,141,'',0),(3931,220,944,0,38,943,943,10,1068542738,1,141,'',0),(3932,220,943,0,39,944,944,10,1068542738,1,141,'',0),(3933,220,944,0,40,943,943,10,1068542738,1,141,'',0),(3934,220,943,0,41,944,944,10,1068542738,1,141,'',0),(3935,220,944,0,42,943,943,10,1068542738,1,141,'',0),(3936,220,943,0,43,944,944,10,1068542738,1,141,'',0),(3937,220,944,0,44,943,0,10,1068542738,1,141,'',0),(3980,228,1552,0,1,1404,1553,23,1068562987,1,201,'',0),(3979,228,1404,0,0,0,1552,23,1068562987,1,201,'',0),(3978,227,1551,0,6,1550,0,23,1068557743,1,203,'',0),(3977,227,1550,0,5,1549,1551,23,1068557743,1,203,'',0),(3976,227,1549,0,4,1548,1550,23,1068557743,1,203,'',0),(3975,227,1548,0,3,1547,1549,23,1068557743,1,203,'',0),(3974,227,1547,0,2,1546,1548,23,1068557743,1,202,'',10200),(3973,227,1546,0,1,1545,1547,23,1068557743,1,201,'',0),(3954,222,1521,0,0,0,1522,24,1068554919,1,206,'',0),(3955,222,1522,0,1,1521,1523,24,1068554919,1,206,'',0),(3956,222,1523,0,2,1522,1521,24,1068554919,1,207,'',0),(3957,222,1521,0,3,1523,1522,24,1068554919,1,207,'',0),(3958,222,1522,0,4,1521,0,24,1068554919,1,207,'',0),(3981,228,1553,0,2,1552,1554,23,1068562987,1,202,'',0),(3982,228,1554,0,3,1553,1393,23,1068562987,1,203,'',0),(3983,228,1393,0,4,1554,1555,23,1068562987,1,203,'',0),(3984,228,1555,0,5,1393,1393,23,1068562987,1,203,'',0),(3985,228,1393,0,6,1555,1393,23,1068562987,1,203,'',0),(3986,228,1393,0,7,1393,1394,23,1068562987,1,203,'',0),(3987,228,1394,0,8,1393,1554,23,1068562987,1,203,'',0),(3988,228,1554,0,9,1394,1393,23,1068562987,1,203,'',0),(3989,228,1393,0,10,1554,1555,23,1068562987,1,203,'',0),(3990,228,1555,0,11,1393,1393,23,1068562987,1,203,'',0),(3991,228,1393,0,12,1555,1393,23,1068562987,1,203,'',0),(3992,228,1393,0,13,1393,1394,23,1068562987,1,203,'',0),(3993,228,1394,0,14,1393,1554,23,1068562987,1,203,'',0),(3994,228,1554,0,15,1394,1393,23,1068562987,1,203,'',0),(3995,228,1393,0,16,1554,1555,23,1068562987,1,203,'',0),(3996,228,1555,0,17,1393,1393,23,1068562987,1,203,'',0),(3997,228,1393,0,18,1555,1393,23,1068562987,1,203,'',0),(3998,228,1393,0,19,1393,1394,23,1068562987,1,203,'',0),(3999,228,1394,0,20,1393,1554,23,1068562987,1,203,'',0),(4000,228,1554,0,21,1394,1393,23,1068562987,1,203,'',0),(4001,228,1393,0,22,1554,1555,23,1068562987,1,203,'',0),(4002,228,1555,0,23,1393,1393,23,1068562987,1,203,'',0),(4003,228,1393,0,24,1555,1393,23,1068562987,1,203,'',0),(4004,228,1393,0,25,1393,1394,23,1068562987,1,203,'',0),(4005,228,1394,0,26,1393,1554,23,1068562987,1,203,'',0),(4006,228,1554,0,27,1394,1393,23,1068562987,1,203,'',0),(4007,228,1393,0,28,1554,1555,23,1068562987,1,203,'',0),(4008,228,1555,0,29,1393,1393,23,1068562987,1,203,'',0),(4009,228,1393,0,30,1555,1393,23,1068562987,1,203,'',0),(4010,228,1393,0,31,1393,1394,23,1068562987,1,203,'',0),(4011,228,1394,0,32,1393,1554,23,1068562987,1,203,'',0),(4012,228,1554,0,33,1394,1393,23,1068562987,1,203,'',0),(4013,228,1393,0,34,1554,1555,23,1068562987,1,203,'',0),(4014,228,1555,0,35,1393,1393,23,1068562987,1,203,'',0),(4015,228,1393,0,36,1555,1393,23,1068562987,1,203,'',0),(4016,228,1393,0,37,1393,1394,23,1068562987,1,203,'',0),(4017,228,1394,0,38,1393,1554,23,1068562987,1,203,'',0),(4018,228,1554,0,39,1394,1393,23,1068562987,1,203,'',0),(4019,228,1393,0,40,1554,1555,23,1068562987,1,203,'',0),(4020,228,1555,0,41,1393,1393,23,1068562987,1,203,'',0),(4021,228,1393,0,42,1555,1393,23,1068562987,1,203,'',0),(4022,228,1393,0,43,1393,1394,23,1068562987,1,203,'',0),(4023,228,1394,0,44,1393,1554,23,1068562987,1,203,'',0),(4024,228,1554,0,45,1394,1393,23,1068562987,1,203,'',0),(4025,228,1393,0,46,1554,1555,23,1068562987,1,203,'',0),(4026,228,1555,0,47,1393,1393,23,1068562987,1,203,'',0),(4027,228,1393,0,48,1555,1393,23,1068562987,1,203,'',0),(4028,228,1393,0,49,1393,1394,23,1068562987,1,203,'',0),(4029,228,1394,0,50,1393,1554,23,1068562987,1,203,'',0),(4030,228,1554,0,51,1394,1393,23,1068562987,1,203,'',0),(4031,228,1393,0,52,1554,1555,23,1068562987,1,203,'',0),(4032,228,1555,0,53,1393,1393,23,1068562987,1,203,'',0),(4033,228,1393,0,54,1555,1393,23,1068562987,1,203,'',0),(4034,228,1393,0,55,1393,1394,23,1068562987,1,203,'',0),(4035,228,1394,0,56,1393,1554,23,1068562987,1,203,'',0),(4036,228,1554,0,57,1394,1393,23,1068562987,1,203,'',0),(4037,228,1393,0,58,1554,1555,23,1068562987,1,203,'',0),(4038,228,1555,0,59,1393,1393,23,1068562987,1,203,'',0),(4039,228,1393,0,60,1555,1393,23,1068562987,1,203,'',0),(4040,228,1393,0,61,1393,1394,23,1068562987,1,203,'',0),(4041,228,1394,0,62,1393,1554,23,1068562987,1,203,'',0),(4042,228,1554,0,63,1394,1393,23,1068562987,1,203,'',0),(4043,228,1393,0,64,1554,1555,23,1068562987,1,203,'',0),(4044,228,1555,0,65,1393,1393,23,1068562987,1,203,'',0),(4045,228,1393,0,66,1555,1393,23,1068562987,1,203,'',0),(4046,228,1393,0,67,1393,1394,23,1068562987,1,203,'',0),(4047,228,1394,0,68,1393,0,23,1068562987,1,203,'',0),(4048,229,1556,0,0,0,1557,23,1068563029,1,201,'',0),(4049,229,1557,0,1,1556,1558,23,1068563029,1,202,'',0),(4050,229,1558,0,2,1557,1559,23,1068563029,1,203,'',0),(4051,229,1559,0,3,1558,1560,23,1068563029,1,203,'',0),(4052,229,1560,0,4,1559,1393,23,1068563029,1,203,'',0),(4053,229,1393,0,5,1560,1393,23,1068563029,1,203,'',0),(4054,229,1393,0,6,1393,1558,23,1068563029,1,203,'',0),(4055,229,1558,0,7,1393,1559,23,1068563029,1,203,'',0),(4056,229,1559,0,8,1558,1560,23,1068563029,1,203,'',0),(4057,229,1560,0,9,1559,1393,23,1068563029,1,203,'',0),(4058,229,1393,0,10,1560,1393,23,1068563029,1,203,'',0),(4059,229,1393,0,11,1393,1558,23,1068563029,1,203,'',0),(4060,229,1558,0,12,1393,1559,23,1068563029,1,203,'',0),(4061,229,1559,0,13,1558,1560,23,1068563029,1,203,'',0),(4062,229,1560,0,14,1559,1393,23,1068563029,1,203,'',0),(4063,229,1393,0,15,1560,1393,23,1068563029,1,203,'',0),(4064,229,1393,0,16,1393,1558,23,1068563029,1,203,'',0),(4065,229,1558,0,17,1393,1559,23,1068563029,1,203,'',0),(4066,229,1559,0,18,1558,1560,23,1068563029,1,203,'',0),(4067,229,1560,0,19,1559,1393,23,1068563029,1,203,'',0),(4068,229,1393,0,20,1560,1393,23,1068563029,1,203,'',0),(4069,229,1393,0,21,1393,1558,23,1068563029,1,203,'',0),(4070,229,1558,0,22,1393,1559,23,1068563029,1,203,'',0),(4071,229,1559,0,23,1558,1560,23,1068563029,1,203,'',0),(4072,229,1560,0,24,1559,1393,23,1068563029,1,203,'',0),(4073,229,1393,0,25,1560,1393,23,1068563029,1,203,'',0),(4074,229,1393,0,26,1393,1558,23,1068563029,1,203,'',0),(4075,229,1558,0,27,1393,1559,23,1068563029,1,203,'',0),(4076,229,1559,0,28,1558,1560,23,1068563029,1,203,'',0),(4077,229,1560,0,29,1559,1393,23,1068563029,1,203,'',0),(4078,229,1393,0,30,1560,1393,23,1068563029,1,203,'',0),(4079,229,1393,0,31,1393,1558,23,1068563029,1,203,'',0),(4080,229,1558,0,32,1393,1559,23,1068563029,1,203,'',0),(4081,229,1559,0,33,1558,1560,23,1068563029,1,203,'',0),(4082,229,1560,0,34,1559,1393,23,1068563029,1,203,'',0),(4083,229,1393,0,35,1560,1393,23,1068563029,1,203,'',0),(4084,229,1393,0,36,1393,1558,23,1068563029,1,203,'',0),(4085,229,1558,0,37,1393,1559,23,1068563029,1,203,'',0),(4086,229,1559,0,38,1558,1560,23,1068563029,1,203,'',0),(4087,229,1560,0,39,1559,1393,23,1068563029,1,203,'',0),(4088,229,1393,0,40,1560,1393,23,1068563029,1,203,'',0),(4089,229,1393,0,41,1393,1558,23,1068563029,1,203,'',0),(4090,229,1558,0,42,1393,1559,23,1068563029,1,203,'',0),(4091,229,1559,0,43,1558,1560,23,1068563029,1,203,'',0),(4092,229,1560,0,44,1559,1393,23,1068563029,1,203,'',0),(4093,229,1393,0,45,1560,1393,23,1068563029,1,203,'',0),(4094,229,1393,0,46,1393,1558,23,1068563029,1,203,'',0),(4095,229,1558,0,47,1393,1559,23,1068563029,1,203,'',0),(4096,229,1559,0,48,1558,1560,23,1068563029,1,203,'',0),(4097,229,1560,0,49,1559,1393,23,1068563029,1,203,'',0),(4098,229,1393,0,50,1560,1393,23,1068563029,1,203,'',0),(4099,229,1393,0,51,1393,1558,23,1068563029,1,203,'',0),(4100,229,1558,0,52,1393,1559,23,1068563029,1,203,'',0),(4101,229,1559,0,53,1558,1560,23,1068563029,1,203,'',0),(4102,229,1560,0,54,1559,1393,23,1068563029,1,203,'',0),(4103,229,1393,0,55,1560,1393,23,1068563029,1,203,'',0),(4104,229,1393,0,56,1393,1558,23,1068563029,1,203,'',0),(4105,229,1558,0,57,1393,1559,23,1068563029,1,203,'',0),(4106,229,1559,0,58,1558,1560,23,1068563029,1,203,'',0),(4107,229,1560,0,59,1559,1393,23,1068563029,1,203,'',0),(4108,229,1393,0,60,1560,1393,23,1068563029,1,203,'',0),(4109,229,1393,0,61,1393,1558,23,1068563029,1,203,'',0),(4110,229,1558,0,62,1393,1559,23,1068563029,1,203,'',0),(4111,229,1559,0,63,1558,1560,23,1068563029,1,203,'',0),(4112,229,1560,0,64,1559,1393,23,1068563029,1,203,'',0),(4113,229,1393,0,65,1560,1393,23,1068563029,1,203,'',0),(4114,229,1393,0,66,1393,1558,23,1068563029,1,203,'',0),(4115,229,1558,0,67,1393,1559,23,1068563029,1,203,'',0),(4116,229,1559,0,68,1558,1560,23,1068563029,1,203,'',0),(4117,229,1560,0,69,1559,1393,23,1068563029,1,203,'',0),(4118,229,1393,0,70,1560,1393,23,1068563029,1,203,'',0),(4119,229,1393,0,71,1393,1558,23,1068563029,1,203,'',0),(4120,229,1558,0,72,1393,1559,23,1068563029,1,203,'',0),(4121,229,1559,0,73,1558,1560,23,1068563029,1,203,'',0),(4122,229,1560,0,74,1559,1393,23,1068563029,1,203,'',0),(4123,229,1393,0,75,1560,1393,23,1068563029,1,203,'',0),(4124,229,1393,0,76,1393,1558,23,1068563029,1,203,'',0),(4125,229,1558,0,77,1393,1559,23,1068563029,1,203,'',0),(4126,229,1559,0,78,1558,1560,23,1068563029,1,203,'',0),(4127,229,1560,0,79,1559,1393,23,1068563029,1,203,'',0),(4128,229,1393,0,80,1560,1393,23,1068563029,1,203,'',0),(4129,229,1393,0,81,1393,1558,23,1068563029,1,203,'',0),(4130,229,1558,0,82,1393,1559,23,1068563029,1,203,'',0),(4131,229,1559,0,83,1558,1560,23,1068563029,1,203,'',0),(4132,229,1560,0,84,1559,1393,23,1068563029,1,203,'',0),(4133,229,1393,0,85,1560,1393,23,1068563029,1,203,'',0),(4134,229,1393,0,86,1393,1558,23,1068563029,1,203,'',0),(4135,229,1558,0,87,1393,1559,23,1068563029,1,203,'',0),(4136,229,1559,0,88,1558,1560,23,1068563029,1,203,'',0),(4137,229,1560,0,89,1559,1393,23,1068563029,1,203,'',0),(4138,229,1393,0,90,1560,1393,23,1068563029,1,203,'',0),(4139,229,1393,0,91,1393,1558,23,1068563029,1,203,'',0),(4140,229,1558,0,92,1393,1559,23,1068563029,1,203,'',0),(4141,229,1559,0,93,1558,1560,23,1068563029,1,203,'',0),(4142,229,1560,0,94,1559,1393,23,1068563029,1,203,'',0),(4143,229,1393,0,95,1560,1393,23,1068563029,1,203,'',0),(4144,229,1393,0,96,1393,0,23,1068563029,1,203,'',0),(4145,230,1561,0,0,0,1562,23,1068563080,1,201,'',0),(4146,230,1562,0,1,1561,1563,23,1068563080,1,202,'',0),(4147,230,1563,0,2,1562,1564,23,1068563080,1,203,'',0),(4148,230,1564,0,3,1563,1565,23,1068563080,1,203,'',0),(4149,230,1565,0,4,1564,1566,23,1068563080,1,203,'',0),(4150,230,1566,0,5,1565,1567,23,1068563080,1,203,'',0),(4151,230,1567,0,6,1566,1563,23,1068563080,1,203,'',0),(4152,230,1563,0,7,1567,1564,23,1068563080,1,203,'',0),(4153,230,1564,0,8,1563,1565,23,1068563080,1,203,'',0),(4154,230,1565,0,9,1564,1566,23,1068563080,1,203,'',0),(4155,230,1566,0,10,1565,1567,23,1068563080,1,203,'',0),(4156,230,1567,0,11,1566,1563,23,1068563080,1,203,'',0),(4157,230,1563,0,12,1567,1564,23,1068563080,1,203,'',0),(4158,230,1564,0,13,1563,1565,23,1068563080,1,203,'',0),(4159,230,1565,0,14,1564,1566,23,1068563080,1,203,'',0),(4160,230,1566,0,15,1565,1567,23,1068563080,1,203,'',0),(4161,230,1567,0,16,1566,1563,23,1068563080,1,203,'',0),(4162,230,1563,0,17,1567,1564,23,1068563080,1,203,'',0),(4163,230,1564,0,18,1563,1565,23,1068563080,1,203,'',0),(4164,230,1565,0,19,1564,1566,23,1068563080,1,203,'',0),(4165,230,1566,0,20,1565,1567,23,1068563080,1,203,'',0),(4166,230,1567,0,21,1566,1563,23,1068563080,1,203,'',0),(4167,230,1563,0,22,1567,1564,23,1068563080,1,203,'',0),(4168,230,1564,0,23,1563,1565,23,1068563080,1,203,'',0),(4169,230,1565,0,24,1564,1566,23,1068563080,1,203,'',0),(4170,230,1566,0,25,1565,1567,23,1068563080,1,203,'',0),(4171,230,1567,0,26,1566,1563,23,1068563080,1,203,'',0),(4172,230,1563,0,27,1567,1564,23,1068563080,1,203,'',0),(4173,230,1564,0,28,1563,1565,23,1068563080,1,203,'',0),(4174,230,1565,0,29,1564,1566,23,1068563080,1,203,'',0),(4175,230,1566,0,30,1565,1567,23,1068563080,1,203,'',0),(4176,230,1567,0,31,1566,1563,23,1068563080,1,203,'',0),(4177,230,1563,0,32,1567,1564,23,1068563080,1,203,'',0),(4178,230,1564,0,33,1563,1565,23,1068563080,1,203,'',0),(4179,230,1565,0,34,1564,1566,23,1068563080,1,203,'',0),(4180,230,1566,0,35,1565,1567,23,1068563080,1,203,'',0),(4181,230,1567,0,36,1566,1563,23,1068563080,1,203,'',0),(4182,230,1563,0,37,1567,1564,23,1068563080,1,203,'',0),(4183,230,1564,0,38,1563,1565,23,1068563080,1,203,'',0),(4184,230,1565,0,39,1564,1566,23,1068563080,1,203,'',0),(4185,230,1566,0,40,1565,1567,23,1068563080,1,203,'',0),(4186,230,1567,0,41,1566,1563,23,1068563080,1,203,'',0),(4187,230,1563,0,42,1567,1564,23,1068563080,1,203,'',0),(4188,230,1564,0,43,1563,1565,23,1068563080,1,203,'',0),(4189,230,1565,0,44,1564,1566,23,1068563080,1,203,'',0),(4190,230,1566,0,45,1565,1567,23,1068563080,1,203,'',0),(4191,230,1567,0,46,1566,1563,23,1068563080,1,203,'',0),(4192,230,1563,0,47,1567,1564,23,1068563080,1,203,'',0),(4193,230,1564,0,48,1563,1565,23,1068563080,1,203,'',0),(4194,230,1565,0,49,1564,1566,23,1068563080,1,203,'',0),(4195,230,1566,0,50,1565,1567,23,1068563080,1,203,'',0),(4196,230,1567,0,51,1566,1563,23,1068563080,1,203,'',0),(4197,230,1563,0,52,1567,1564,23,1068563080,1,203,'',0),(4198,230,1564,0,53,1563,1565,23,1068563080,1,203,'',0),(4199,230,1565,0,54,1564,1566,23,1068563080,1,203,'',0),(4200,230,1566,0,55,1565,1567,23,1068563080,1,203,'',0),(4201,230,1567,0,56,1566,0,23,1068563080,1,203,'',0),(4269,232,1623,0,20,1622,0,25,1068567344,1,217,'',3),(4268,232,1622,0,19,1621,1623,25,1068567344,1,216,'',0),(4267,232,1621,0,18,1620,1622,25,1068567344,1,216,'',0),(4266,232,1620,0,17,1619,1621,25,1068567344,1,216,'',0),(4265,232,1619,0,16,1618,1620,25,1068567344,1,216,'',0),(4264,232,1618,0,15,1617,1619,25,1068567344,1,216,'',0),(4263,232,1617,0,14,1616,1618,25,1068567344,1,216,'',0),(4262,232,1616,0,13,1142,1617,25,1068567344,1,216,'',0),(4261,232,1142,0,12,1615,1616,25,1068567344,1,216,'',0),(4260,232,1615,0,11,1607,1142,25,1068567344,1,216,'',0),(4259,232,1607,0,10,1614,1615,25,1068567344,1,216,'',0),(4258,232,1614,0,9,1613,1607,25,1068567344,1,216,'',0),(4257,232,1613,0,8,1612,1614,25,1068567344,1,216,'',0),(4256,232,1612,0,7,1611,1613,25,1068567344,1,216,'',0),(4255,232,1611,0,6,33,1612,25,1068567344,1,216,'',0),(4254,232,33,0,5,1610,1611,25,1068567344,1,216,'',0),(4253,232,1610,0,4,1609,33,25,1068567344,1,216,'',0),(4252,232,1609,0,3,1439,1610,25,1068567344,1,216,'',0),(4251,232,1439,0,2,1608,1609,25,1068567344,1,216,'',0),(4250,232,1608,0,1,1607,1439,25,1068567344,1,216,'',0),(4249,232,1607,0,0,0,1608,25,1068567344,1,216,'',0),(4307,235,1611,0,0,0,1647,25,1068647907,1,216,'',0),(4308,235,1647,0,1,1611,1648,25,1068647907,1,216,'',0),(4309,235,1648,0,2,1647,1649,25,1068647907,1,216,'',0),(4310,235,1649,0,3,1648,73,25,1068647907,1,216,'',0),(4311,235,73,0,4,1649,1650,25,1068647907,1,216,'',0),(4312,235,1650,0,5,73,1142,25,1068647907,1,216,'',0),(4313,235,1142,0,6,1650,1651,25,1068647907,1,216,'',0),(4314,235,1651,0,7,1142,1652,25,1068647907,1,216,'',0),(4315,235,1652,0,8,1651,1653,25,1068647907,1,216,'',0),(4316,235,1653,0,9,1652,1654,25,1068647907,1,216,'',0),(4317,235,1654,0,10,1653,1655,25,1068647907,1,216,'',0),(4318,235,1655,0,11,1654,1656,25,1068647907,1,216,'',0),(4319,235,1656,0,12,1655,1657,25,1068647907,1,216,'',0),(4320,235,1657,0,13,1656,33,25,1068647907,1,216,'',0),(4321,235,33,0,14,1657,1658,25,1068647907,1,216,'',0),(4322,235,1658,0,15,33,1659,25,1068647907,1,216,'',0),(4323,235,1659,0,16,1658,1660,25,1068647907,1,216,'',0),(4324,235,1660,0,17,1659,1661,25,1068647907,1,216,'',0),(4325,235,1661,0,18,1660,1662,25,1068647907,1,216,'',0),(4326,235,1662,0,19,1661,1663,25,1068647907,1,216,'',0),(4327,235,1663,0,20,1662,1664,25,1068647907,1,216,'',0),(4328,235,1664,0,21,1663,1665,25,1068647907,1,216,'',0),(4329,235,1665,0,22,1664,1611,25,1068647907,1,216,'',0),(4330,235,1611,0,23,1665,1666,25,1068647907,1,216,'',0),(4331,235,1666,0,24,1611,1667,25,1068647907,1,216,'',0),(4332,235,1667,0,25,1666,1668,25,1068647907,1,216,'',0),(4333,235,1668,0,26,1667,1669,25,1068647907,1,216,'',0),(4334,235,1669,0,27,1668,1670,25,1068647907,1,216,'',0),(4335,235,1670,0,28,1669,1614,25,1068647907,1,216,'',0),(4336,235,1614,0,29,1670,1671,25,1068647907,1,216,'',0),(4337,235,1671,0,30,1614,1672,25,1068647907,1,216,'',0),(4338,235,1672,0,31,1671,1673,25,1068647907,1,216,'',0),(4339,235,1673,0,32,1672,1674,25,1068647907,1,216,'',0),(4340,235,1674,0,33,1673,74,25,1068647907,1,216,'',0),(4341,235,74,0,34,1674,1142,25,1068647907,1,216,'',0),(4342,235,1142,0,35,74,1651,25,1068647907,1,216,'',0),(4343,235,1651,0,36,1142,1417,25,1068647907,1,216,'',0),(4344,235,1417,0,37,1651,1675,25,1068647907,1,216,'',0),(4345,235,1675,0,38,1417,1676,25,1068647907,1,216,'',0),(4346,235,1676,0,39,1675,1611,25,1068647907,1,216,'',0),(4347,235,1611,0,40,1676,1677,25,1068647907,1,216,'',0),(4348,235,1677,0,41,1611,1678,25,1068647907,1,216,'',0),(4349,235,1678,0,42,1677,1439,25,1068647907,1,216,'',0),(4350,235,1439,0,43,1678,1679,25,1068647907,1,216,'',0),(4351,235,1679,0,44,1439,1680,25,1068647907,1,216,'',0),(4352,235,1680,0,45,1679,1519,25,1068647907,1,216,'',0),(4353,235,1519,0,46,1680,1142,25,1068647907,1,216,'',0),(4354,235,1142,0,47,1519,1681,25,1068647907,1,216,'',0),(4355,235,1681,0,48,1142,1682,25,1068647907,1,216,'',0),(4356,235,1682,0,49,1681,1683,25,1068647907,1,216,'',0),(4357,235,1683,0,50,1682,1670,25,1068647907,1,216,'',0),(4358,235,1670,0,51,1683,1439,25,1068647907,1,216,'',0),(4359,235,1439,0,52,1670,1684,25,1068647907,1,216,'',0),(4360,235,1684,0,53,1439,1519,25,1068647907,1,216,'',0),(4361,235,1519,0,54,1684,1685,25,1068647907,1,216,'',0),(4362,235,1685,0,55,1519,1417,25,1068647907,1,216,'',0),(4363,235,1417,0,56,1685,1675,25,1068647907,1,216,'',0),(4364,235,1675,0,57,1417,1686,25,1068647907,1,216,'',0),(4365,235,1686,0,58,1675,73,25,1068647907,1,216,'',0),(4366,235,73,0,59,1686,1652,25,1068647907,1,216,'',0),(4367,235,1652,0,60,73,1687,25,1068647907,1,216,'',0),(4368,235,1687,0,61,1652,1439,25,1068647907,1,216,'',0),(4369,235,1439,0,62,1687,1688,25,1068647907,1,216,'',0),(4370,235,1688,0,63,1439,1689,25,1068647907,1,216,'',0),(4371,235,1689,0,64,1688,1690,25,1068647907,1,216,'',0),(4372,235,1690,0,65,1689,1691,25,1068647907,1,216,'',0),(4373,235,1691,0,66,1690,1692,25,1068647907,1,216,'',0),(4374,235,1692,0,67,1691,1693,25,1068647907,1,216,'',0),(4375,235,1693,0,68,1692,1694,25,1068647907,1,216,'',0),(4376,235,1694,0,69,1693,1695,25,1068647907,1,216,'',0),(4377,235,1695,0,70,1694,1672,25,1068647907,1,216,'',0),(4378,235,1672,0,71,1695,1696,25,1068647907,1,216,'',0),(4379,235,1696,0,72,1672,1697,25,1068647907,1,216,'',0),(4380,235,1697,0,73,1696,1698,25,1068647907,1,216,'',0),(4381,235,1698,0,74,1697,1699,25,1068647907,1,216,'',0),(4382,235,1699,0,75,1698,1519,25,1068647907,1,216,'',0),(4383,235,1519,0,76,1699,1700,25,1068647907,1,216,'',0),(4384,235,1700,0,77,1519,1701,25,1068647907,1,216,'',0),(4385,235,1701,0,78,1700,1702,25,1068647907,1,216,'',0),(4386,235,1702,0,79,1701,33,25,1068647907,1,216,'',0),(4387,235,33,0,80,1702,1672,25,1068647907,1,216,'',0),(4388,235,1672,0,81,33,1703,25,1068647907,1,216,'',0),(4389,235,1703,0,82,1672,1704,25,1068647907,1,216,'',0),(4390,235,1704,0,83,1703,1705,25,1068647907,1,216,'',0),(4391,235,1705,0,84,1704,1706,25,1068647907,1,216,'',0),(4392,235,1706,0,85,1705,1617,25,1068647907,1,216,'',0),(4393,235,1617,0,86,1706,1707,25,1068647907,1,216,'',0),(4394,235,1707,0,87,1617,1417,25,1068647907,1,216,'',0),(4395,235,1417,0,88,1707,1142,25,1068647907,1,216,'',0),(4396,235,1142,0,89,1417,1708,25,1068647907,1,216,'',0),(4397,235,1708,0,90,1142,1709,25,1068647907,1,216,'',0),(4398,235,1709,0,91,1708,1704,25,1068647907,1,216,'',0),(4399,235,1704,0,92,1709,1668,25,1068647907,1,216,'',0),(4400,235,1668,0,93,1704,1611,25,1068647907,1,216,'',0),(4401,235,1611,0,94,1668,1710,25,1068647907,1,216,'',0),(4402,235,1710,0,95,1611,1711,25,1068647907,1,216,'',0),(4403,235,1711,0,96,1710,1712,25,1068647907,1,216,'',0),(4404,235,1712,0,97,1711,1672,25,1068647907,1,216,'',0),(4405,235,1672,0,98,1712,1673,25,1068647907,1,216,'',0),(4406,235,1673,0,99,1672,1674,25,1068647907,1,216,'',0),(4407,235,1674,0,100,1673,1713,25,1068647907,1,216,'',0),(4408,235,1713,0,101,1674,1614,25,1068647907,1,216,'',0),(4409,235,1614,0,102,1713,1142,25,1068647907,1,216,'',0),(4410,235,1142,0,103,1614,1714,25,1068647907,1,216,'',0),(4411,235,1714,0,104,1142,1519,25,1068647907,1,216,'',0),(4412,235,1519,0,105,1714,1715,25,1068647907,1,216,'',0),(4413,235,1715,0,106,1519,1716,25,1068647907,1,216,'',0),(4414,235,1716,0,107,1715,1717,25,1068647907,1,216,'',0),(4415,235,1717,0,108,1716,1439,25,1068647907,1,216,'',0),(4416,235,1439,0,109,1717,1718,25,1068647907,1,216,'',0),(4417,235,1718,0,110,1439,1719,25,1068647907,1,216,'',0),(4418,235,1719,0,111,1718,1663,25,1068647907,1,216,'',0),(4419,235,1663,0,112,1719,1700,25,1068647907,1,216,'',0),(4420,235,1700,0,113,1663,1681,25,1068647907,1,216,'',0),(4421,235,1681,0,114,1700,1720,25,1068647907,1,216,'',0),(4422,235,1720,0,115,1681,1721,25,1068647907,1,216,'',0),(4423,235,1721,0,116,1720,1672,25,1068647907,1,216,'',0),(4424,235,1672,0,117,1721,1607,25,1068647907,1,216,'',0),(4425,235,1607,0,118,1672,1722,25,1068647907,1,216,'',0),(4426,235,1722,0,119,1607,1607,25,1068647907,1,216,'',0),(4427,235,1607,0,120,1722,1723,25,1068647907,1,216,'',0),(4428,235,1723,0,121,1607,1724,25,1068647907,1,216,'',0),(4429,235,1724,0,122,1723,1704,25,1068647907,1,216,'',0),(4430,235,1704,0,123,1724,1705,25,1068647907,1,216,'',0),(4431,235,1705,0,124,1704,1706,25,1068647907,1,216,'',0),(4432,235,1706,0,125,1705,1725,25,1068647907,1,216,'',0),(4433,235,1725,0,126,1706,33,25,1068647907,1,216,'',0),(4434,235,33,0,127,1725,1726,25,1068647907,1,216,'',0),(4435,235,1726,0,128,33,1727,25,1068647907,1,216,'',0),(4436,235,1727,0,129,1726,1612,25,1068647907,1,216,'',0),(4437,235,1612,0,130,1727,1728,25,1068647907,1,216,'',0),(4438,235,1728,0,131,1612,1729,25,1068647907,1,216,'',0),(4439,235,1729,0,132,1728,1700,25,1068647907,1,216,'',0),(4440,235,1700,0,133,1729,1730,25,1068647907,1,216,'',0),(4441,235,1730,0,134,1700,1417,25,1068647907,1,216,'',0),(4442,235,1417,0,135,1730,1692,25,1068647907,1,216,'',0),(4443,235,1692,0,136,1417,1731,25,1068647907,1,216,'',0),(4444,235,1731,0,137,1692,1670,25,1068647907,1,216,'',0),(4445,235,1670,0,138,1731,1732,25,1068647907,1,216,'',0),(4446,235,1732,0,139,1670,1617,25,1068647907,1,216,'',0),(4447,235,1617,0,140,1732,1733,25,1068647907,1,216,'',0),(4448,235,1733,0,141,1617,1614,25,1068647907,1,216,'',0),(4449,235,1614,0,142,1733,1670,25,1068647907,1,216,'',0),(4450,235,1670,0,143,1614,1439,25,1068647907,1,216,'',0),(4451,235,1439,0,144,1670,1734,25,1068647907,1,216,'',0),(4452,235,1734,0,145,1439,1735,25,1068647907,1,216,'',0),(4453,235,1735,0,146,1734,1736,25,1068647907,1,216,'',0),(4454,235,1736,0,147,1735,1737,25,1068647907,1,216,'',0),(4455,235,1737,0,148,1736,1738,25,1068647907,1,216,'',0),(4456,235,1738,0,149,1737,1142,25,1068647907,1,216,'',0),(4457,235,1142,0,150,1738,1739,25,1068647907,1,216,'',0),(4458,235,1739,0,151,1142,1723,25,1068647907,1,216,'',0),(4459,235,1723,0,152,1739,1740,25,1068647907,1,216,'',0),(4460,235,1740,0,153,1723,33,25,1068647907,1,216,'',0),(4461,235,33,0,154,1740,1741,25,1068647907,1,216,'',0),(4462,235,1741,0,155,33,1742,25,1068647907,1,216,'',0),(4463,235,1742,0,156,1741,1743,25,1068647907,1,216,'',0),(4464,235,1743,0,157,1742,1744,25,1068647907,1,216,'',0),(4465,235,1744,0,158,1743,33,25,1068647907,1,216,'',0),(4466,235,33,0,159,1744,1745,25,1068647907,1,216,'',0),(4467,235,1745,0,160,33,1746,25,1068647907,1,216,'',0),(4468,235,1746,0,161,1745,1668,25,1068647907,1,216,'',0),(4469,235,1668,0,162,1746,1747,25,1068647907,1,216,'',0),(4470,235,1747,0,163,1668,1748,25,1068647907,1,216,'',0),(4471,235,1748,0,164,1747,1749,25,1068647907,1,216,'',0),(4472,235,1749,0,165,1748,1648,25,1068647907,1,216,'',0),(4473,235,1648,0,166,1749,1750,25,1068647907,1,216,'',0),(4474,235,1750,0,167,1648,1751,25,1068647907,1,216,'',0),(4475,235,1751,0,168,1750,33,25,1068647907,1,216,'',0),(4476,235,33,0,169,1751,1752,25,1068647907,1,216,'',0),(4477,235,1752,0,170,33,1753,25,1068647907,1,216,'',0),(4478,235,1753,0,171,1752,1665,25,1068647907,1,216,'',0),(4479,235,1665,0,172,1753,1142,25,1068647907,1,216,'',0),(4480,235,1142,0,173,1665,1681,25,1068647907,1,216,'',0),(4481,235,1681,0,174,1142,1754,25,1068647907,1,216,'',0),(4482,235,1754,0,175,1681,1755,25,1068647907,1,216,'',0),(4483,235,1755,0,176,1754,1756,25,1068647907,1,216,'',0),(4484,235,1756,0,177,1755,1757,25,1068647907,1,216,'',0),(4485,235,1757,0,178,1756,1758,25,1068647907,1,216,'',0),(4486,235,1758,0,179,1757,1759,25,1068647907,1,216,'',0),(4487,235,1759,0,180,1758,1660,25,1068647907,1,216,'',0),(4488,235,1660,0,181,1759,1760,25,1068647907,1,216,'',0),(4489,235,1760,0,182,1660,1417,25,1068647907,1,216,'',0),(4490,235,1417,0,183,1760,1142,25,1068647907,1,216,'',0),(4491,235,1142,0,184,1417,1761,25,1068647907,1,216,'',0),(4492,235,1761,0,185,1142,1704,25,1068647907,1,216,'',0),(4493,235,1704,0,186,1761,1762,25,1068647907,1,216,'',0),(4494,235,1762,0,187,1704,1611,25,1068647907,1,216,'',0),(4495,235,1611,0,188,1762,1763,25,1068647907,1,216,'',0),(4496,235,1763,0,189,1611,1764,25,1068647907,1,216,'',0),(4497,235,1764,0,190,1763,1765,25,1068647907,1,216,'',0),(4498,235,1765,0,191,1764,1766,25,1068647907,1,216,'',0),(4499,235,1766,0,192,1765,1614,25,1068647907,1,216,'',0),(4500,235,1614,0,193,1766,1767,25,1068647907,1,216,'',0),(4501,235,1767,0,194,1614,1768,25,1068647907,1,216,'',0),(4502,235,1768,0,195,1767,1769,25,1068647907,1,216,'',0),(4503,235,1769,0,196,1768,1672,25,1068647907,1,216,'',0),(4504,235,1672,0,197,1769,1696,25,1068647907,1,216,'',0),(4505,235,1696,0,198,1672,1608,25,1068647907,1,216,'',0),(4506,235,1608,0,199,1696,1770,25,1068647907,1,216,'',0),(4507,235,1770,0,200,1608,1142,25,1068647907,1,216,'',0),(4508,235,1142,0,201,1770,1771,25,1068647907,1,216,'',0),(4509,235,1771,0,202,1142,1765,25,1068647907,1,216,'',0),(4510,235,1765,0,203,1771,1685,25,1068647907,1,216,'',0),(4511,235,1685,0,204,1765,1441,25,1068647907,1,216,'',0),(4512,235,1441,0,205,1685,1772,25,1068647907,1,216,'',0),(4513,235,1772,0,206,1441,1773,25,1068647907,1,216,'',0),(4514,235,1773,0,207,1772,1142,25,1068647907,1,216,'',0),(4515,235,1142,0,208,1773,1774,25,1068647907,1,216,'',0),(4516,235,1774,0,209,1142,1682,25,1068647907,1,216,'',0),(4517,235,1682,0,210,1774,1775,25,1068647907,1,216,'',0),(4518,235,1775,0,211,1682,1614,25,1068647907,1,216,'',0),(4519,235,1614,0,212,1775,1750,25,1068647907,1,216,'',0),(4520,235,1750,0,213,1614,1776,25,1068647907,1,216,'',0),(4521,235,1776,0,214,1750,1720,25,1068647907,1,216,'',0),(4522,235,1720,0,215,1776,1777,25,1068647907,1,216,'',0),(4523,235,1777,0,216,1720,1778,25,1068647907,1,216,'',0),(4524,235,1778,0,217,1777,1621,25,1068647907,1,216,'',0),(4525,235,1621,0,218,1778,1620,25,1068647907,1,216,'',0),(4526,235,1620,0,219,1621,1779,25,1068647907,1,216,'',0),(4527,235,1779,0,220,1620,1780,25,1068647907,1,216,'',0),(4528,235,1780,0,221,1779,33,25,1068647907,1,216,'',0),(4529,235,33,0,222,1780,1775,25,1068647907,1,216,'',0),(4530,235,1775,0,223,33,1781,25,1068647907,1,216,'',0),(4531,235,1781,0,224,1775,1614,25,1068647907,1,216,'',0),(4532,235,1614,0,225,1781,1782,25,1068647907,1,216,'',0),(4533,235,1782,0,226,1614,1765,25,1068647907,1,216,'',0),(4534,235,1765,0,227,1782,1522,25,1068647907,1,216,'',0),(4535,235,1522,0,228,1765,1663,25,1068647907,1,216,'',0),(4536,235,1663,0,229,1522,1783,25,1068647907,1,216,'',0),(4537,235,1783,0,230,1663,1784,25,1068647907,1,216,'',0),(4538,235,1784,0,231,1783,1614,25,1068647907,1,216,'',0),(4539,235,1614,0,232,1784,1785,25,1068647907,1,216,'',0),(4540,235,1785,0,233,1614,1786,25,1068647907,1,216,'',0),(4541,235,1786,0,234,1785,1614,25,1068647907,1,216,'',0),(4542,235,1614,0,235,1786,1696,25,1068647907,1,216,'',0),(4543,235,1696,0,236,1614,1663,25,1068647907,1,216,'',0),(4544,235,1663,0,237,1696,73,25,1068647907,1,216,'',0),(4545,235,73,0,238,1663,1787,25,1068647907,1,216,'',0),(4546,235,1787,0,239,73,1788,25,1068647907,1,216,'',0),(4547,235,1788,0,240,1787,1789,25,1068647907,1,216,'',0),(4548,235,1789,0,241,1788,1790,25,1068647907,1,216,'',0),(4549,235,1790,0,242,1789,0,25,1068647907,1,217,'',5),(4550,236,1142,0,0,0,1791,25,1068649441,1,216,'',0),(4551,236,1791,0,1,1142,1792,25,1068649441,1,216,'',0),(4552,236,1792,0,2,1791,1648,25,1068649441,1,216,'',0),(4553,236,1648,0,3,1792,1793,25,1068649441,1,216,'',0),(4554,236,1793,0,4,1648,1665,25,1068649441,1,216,'',0),(4555,236,1665,0,5,1793,1142,25,1068649441,1,216,'',0),(4556,236,1142,0,6,1665,1794,25,1068649441,1,216,'',0),(4557,236,1794,0,7,1142,1795,25,1068649441,1,216,'',0),(4558,236,1795,0,8,1794,1796,25,1068649441,1,216,'',0),(4559,236,1796,0,9,1795,1797,25,1068649441,1,216,'',0),(4560,236,1797,0,10,1796,1798,25,1068649441,1,216,'',0),(4561,236,1798,0,11,1797,1142,25,1068649441,1,216,'',0),(4562,236,1142,0,12,1798,1799,25,1068649441,1,216,'',0),(4563,236,1799,0,13,1142,1800,25,1068649441,1,216,'',0),(4564,236,1800,0,14,1799,1801,25,1068649441,1,216,'',0),(4565,236,1801,0,15,1800,1798,25,1068649441,1,216,'',0),(4566,236,1798,0,16,1801,1142,25,1068649441,1,216,'',0),(4567,236,1142,0,17,1798,1802,25,1068649441,1,216,'',0),(4568,236,1802,0,18,1142,1716,25,1068649441,1,216,'',0),(4569,236,1716,0,19,1802,1803,25,1068649441,1,216,'',0),(4570,236,1803,0,20,1716,1804,25,1068649441,1,216,'',0),(4571,236,1804,0,21,1803,1787,25,1068649441,1,216,'',0),(4572,236,1787,0,22,1804,1519,25,1068649441,1,216,'',0),(4573,236,1519,0,23,1787,1142,25,1068649441,1,216,'',0),(4574,236,1142,0,24,1519,1805,25,1068649441,1,216,'',0),(4575,236,1805,0,25,1142,1806,25,1068649441,1,216,'',0),(4576,236,1806,0,26,1805,1807,25,1068649441,1,216,'',0),(4577,236,1807,0,27,1806,1439,25,1068649441,1,216,'',0),(4578,236,1439,0,28,1807,1808,25,1068649441,1,216,'',0),(4579,236,1808,0,29,1439,1809,25,1068649441,1,216,'',0),(4580,236,1809,0,30,1808,1810,25,1068649441,1,216,'',0),(4581,236,1810,0,31,1809,74,25,1068649441,1,216,'',0),(4582,236,74,0,32,1810,1811,25,1068649441,1,216,'',0),(4583,236,1811,0,33,74,1812,25,1068649441,1,216,'',0),(4584,236,1812,0,34,1811,1813,25,1068649441,1,216,'',0),(4585,236,1813,0,35,1812,1797,25,1068649441,1,216,'',0),(4586,236,1797,0,36,1813,1612,25,1068649441,1,216,'',0),(4587,236,1612,0,37,1797,1648,25,1068649441,1,216,'',0),(4588,236,1648,0,38,1612,1814,25,1068649441,1,216,'',0),(4589,236,1814,0,39,1648,1815,25,1068649441,1,216,'',0),(4590,236,1815,0,40,1814,1738,25,1068649441,1,216,'',0),(4591,236,1738,0,41,1815,1142,25,1068649441,1,216,'',0),(4592,236,1142,0,42,1738,1799,25,1068649441,1,216,'',0),(4593,236,1799,0,43,1142,33,25,1068649441,1,216,'',0),(4594,236,33,0,44,1799,1616,25,1068649441,1,216,'',0),(4595,236,1616,0,45,33,1816,25,1068649441,1,216,'',0),(4596,236,1816,0,46,1616,1648,25,1068649441,1,216,'',0),(4597,236,1648,0,47,1816,1817,25,1068649441,1,216,'',0),(4598,236,1817,0,48,1648,1614,25,1068649441,1,216,'',0),(4599,236,1614,0,49,1817,1818,25,1068649441,1,216,'',0),(4600,236,1818,0,50,1614,1797,25,1068649441,1,216,'',0),(4601,236,1797,0,51,1818,1819,25,1068649441,1,216,'',0),(4602,236,1819,0,52,1797,1665,25,1068649441,1,216,'',0),(4603,236,1665,0,53,1819,1820,25,1068649441,1,216,'',0),(4604,236,1820,0,54,1665,1821,25,1068649441,1,216,'',0),(4605,236,1821,0,55,1820,1822,25,1068649441,1,216,'',0),(4606,236,1822,0,56,1821,1823,25,1068649441,1,216,'',0),(4607,236,1823,0,57,1822,1824,25,1068649441,1,216,'',0),(4608,236,1824,0,58,1823,1439,25,1068649441,1,216,'',0),(4609,236,1439,0,59,1824,1825,25,1068649441,1,216,'',0),(4610,236,1825,0,60,1439,1826,25,1068649441,1,216,'',0),(4611,236,1826,0,61,1825,1519,25,1068649441,1,216,'',0),(4612,236,1519,0,62,1826,1607,25,1068649441,1,216,'',0),(4613,236,1607,0,63,1519,1827,25,1068649441,1,216,'',0),(4614,236,1827,0,64,1607,0,25,1068649441,1,217,'',0),(4615,237,1611,0,0,0,1828,25,1068649592,1,216,'',0),(4616,237,1828,0,1,1611,73,25,1068649592,1,216,'',0),(4617,237,73,0,2,1828,1704,25,1068649592,1,216,'',0),(4618,237,1704,0,3,73,1829,25,1068649592,1,216,'',0),(4619,237,1829,0,4,1704,1830,25,1068649592,1,216,'',0),(4620,237,1830,0,5,1829,1607,25,1068649592,1,216,'',0),(4621,237,1607,0,6,1830,1831,25,1068649592,1,216,'',0),(4622,237,1831,0,7,1607,1826,25,1068649592,1,216,'',0),(4623,237,1826,0,8,1831,1663,25,1068649592,1,216,'',0),(4624,237,1663,0,9,1826,1832,25,1068649592,1,216,'',0),(4625,237,1832,0,10,1663,1833,25,1068649592,1,216,'',0),(4626,237,1833,0,11,1832,1834,25,1068649592,1,216,'',0),(4627,237,1834,0,12,1833,1142,25,1068649592,1,216,'',0),(4628,237,1142,0,13,1834,1835,25,1068649592,1,216,'',0),(4629,237,1835,0,14,1142,1836,25,1068649592,1,216,'',0),(4630,237,1836,0,15,1835,1837,25,1068649592,1,216,'',0),(4631,237,1837,0,16,1836,1838,25,1068649592,1,216,'',0),(4632,237,1838,0,17,1837,33,25,1068649592,1,216,'',0),(4633,237,33,0,18,1838,1839,25,1068649592,1,216,'',0),(4634,237,1839,0,19,33,1616,25,1068649592,1,216,'',0),(4635,237,1616,0,20,1839,1840,25,1068649592,1,216,'',0),(4636,237,1840,0,21,1616,1519,25,1068649592,1,216,'',0),(4637,237,1519,0,22,1840,1142,25,1068649592,1,216,'',0),(4638,237,1142,0,23,1519,1841,25,1068649592,1,216,'',0),(4639,237,1841,0,24,1142,1787,25,1068649592,1,216,'',0),(4640,237,1787,0,25,1841,1842,25,1068649592,1,216,'',0),(4641,237,1842,0,26,1787,1611,25,1068649592,1,216,'',0),(4642,237,1611,0,27,1842,1843,25,1068649592,1,216,'',0),(4643,237,1843,0,28,1611,1844,25,1068649592,1,216,'',0),(4644,237,1844,0,29,1843,1439,25,1068649592,1,216,'',0),(4645,237,1439,0,30,1844,1845,25,1068649592,1,216,'',0),(4646,237,1845,0,31,1439,1846,25,1068649592,1,216,'',0),(4647,237,1846,0,32,1845,1847,25,1068649592,1,216,'',0),(4648,237,1847,0,33,1846,1519,25,1068649592,1,216,'',0),(4649,237,1519,0,34,1847,1848,25,1068649592,1,216,'',0),(4650,237,1848,0,35,1519,1849,25,1068649592,1,216,'',0),(4651,237,1849,0,36,1848,1850,25,1068649592,1,216,'',0),(4652,237,1850,0,37,1849,1851,25,1068649592,1,216,'',0),(4653,237,1851,0,38,1850,1765,25,1068649592,1,216,'',0),(4654,237,1765,0,39,1851,1439,25,1068649592,1,216,'',0),(4655,237,1439,0,40,1765,1618,25,1068649592,1,216,'',0),(4656,237,1618,0,41,1439,1852,25,1068649592,1,216,'',0),(4657,237,1852,0,42,1618,1853,25,1068649592,1,216,'',0),(4658,237,1853,0,43,1852,1854,25,1068649592,1,216,'',0),(4659,237,1854,0,44,1853,1142,25,1068649592,1,216,'',0),(4660,237,1142,0,45,1854,1704,25,1068649592,1,216,'',0),(4661,237,1704,0,46,1142,1855,25,1068649592,1,216,'',0),(4662,237,1855,0,47,1704,1804,25,1068649592,1,216,'',0),(4663,237,1804,0,48,1855,1607,25,1068649592,1,216,'',0),(4664,237,1607,0,49,1804,1856,25,1068649592,1,216,'',0),(4665,237,1856,0,50,1607,1614,25,1068649592,1,216,'',0),(4666,237,1614,0,51,1856,1782,25,1068649592,1,216,'',0),(4667,237,1782,0,52,1614,1857,25,1068649592,1,216,'',0),(4668,237,1857,0,53,1782,1858,25,1068649592,1,216,'',0),(4669,237,1858,0,54,1857,1142,25,1068649592,1,216,'',0),(4670,237,1142,0,55,1858,1859,25,1068649592,1,216,'',0),(4671,237,1859,0,56,1142,1860,25,1068649592,1,216,'',0),(4672,237,1860,0,57,1859,33,25,1068649592,1,216,'',0),(4673,237,33,0,58,1860,1861,25,1068649592,1,216,'',0),(4674,237,1861,0,59,33,1519,25,1068649592,1,216,'',0),(4675,237,1519,0,60,1861,1862,25,1068649592,1,216,'',0),(4676,237,1862,0,61,1519,1863,25,1068649592,1,216,'',0),(4677,237,1863,0,62,1862,1838,25,1068649592,1,216,'',0),(4678,237,1838,0,63,1863,1142,25,1068649592,1,216,'',0),(4679,237,1142,0,64,1838,1864,25,1068649592,1,216,'',0),(4680,237,1864,0,65,1142,1865,25,1068649592,1,216,'',0),(4681,237,1865,0,66,1864,1866,25,1068649592,1,216,'',0),(4682,237,1866,0,67,1865,1611,25,1068649592,1,216,'',0),(4683,237,1611,0,68,1866,1867,25,1068649592,1,216,'',0),(4684,237,1867,0,69,1611,1417,25,1068649592,1,216,'',0),(4685,237,1417,0,70,1867,1142,25,1068649592,1,216,'',0),(4686,237,1142,0,71,1417,1704,25,1068649592,1,216,'',0),(4687,237,1704,0,72,1142,1844,25,1068649592,1,216,'',0),(4688,237,1844,0,73,1704,1142,25,1068649592,1,216,'',0),(4689,237,1142,0,74,1844,1868,25,1068649592,1,216,'',0),(4690,237,1868,0,75,1142,1869,25,1068649592,1,216,'',0),(4691,237,1869,0,76,1868,1797,25,1068649592,1,216,'',0),(4692,237,1797,0,77,1869,1660,25,1068649592,1,216,'',0),(4693,237,1660,0,78,1797,1870,25,1068649592,1,216,'',0),(4694,237,1870,0,79,1660,1871,25,1068649592,1,216,'',0),(4695,237,1871,0,80,1870,1872,25,1068649592,1,216,'',0),(4696,237,1872,0,81,1871,1873,25,1068649592,1,216,'',0),(4697,237,1873,0,82,1872,1519,25,1068649592,1,216,'',0),(4698,237,1519,0,83,1873,1874,25,1068649592,1,216,'',0),(4699,237,1874,0,84,1519,33,25,1068649592,1,216,'',0),(4700,237,33,0,85,1874,1824,25,1068649592,1,216,'',0),(4701,237,1824,0,86,33,1614,25,1068649592,1,216,'',0),(4702,237,1614,0,87,1824,1875,25,1068649592,1,216,'',0),(4703,237,1875,0,88,1614,1862,25,1068649592,1,216,'',0),(4704,237,1862,0,89,1875,1876,25,1068649592,1,216,'',0),(4705,237,1876,0,90,1862,1672,25,1068649592,1,216,'',0),(4706,237,1672,0,91,1876,1844,25,1068649592,1,216,'',0),(4707,237,1844,0,92,1672,1807,25,1068649592,1,216,'',0),(4708,237,1807,0,93,1844,1800,25,1068649592,1,216,'',0),(4709,237,1800,0,94,1807,1877,25,1068649592,1,216,'',0),(4710,237,1877,0,95,1800,33,25,1068649592,1,216,'',0),(4711,237,33,0,96,1877,1878,25,1068649592,1,216,'',0),(4712,237,1878,0,97,33,1665,25,1068649592,1,216,'',0),(4713,237,1665,0,98,1878,1879,25,1068649592,1,216,'',0),(4714,237,1879,0,99,1665,1880,25,1068649592,1,216,'',0),(4715,237,1880,0,100,1879,1881,25,1068649592,1,216,'',0),(4716,237,1881,0,101,1880,1614,25,1068649592,1,216,'',0),(4717,237,1614,0,102,1881,1882,25,1068649592,1,216,'',0),(4718,237,1882,0,103,1614,1883,25,1068649592,1,216,'',0),(4719,237,1883,0,104,1882,1800,25,1068649592,1,216,'',0),(4720,237,1800,0,105,1883,1142,25,1068649592,1,216,'',0),(4721,237,1142,0,106,1800,1142,25,1068649592,1,216,'',0),(4722,237,1142,0,107,1142,1805,25,1068649592,1,216,'',0),(4723,237,1805,0,108,1142,1608,25,1068649592,1,216,'',0),(4724,237,1608,0,109,1805,1884,25,1068649592,1,216,'',0),(4725,237,1884,0,110,1608,1620,25,1068649592,1,216,'',0),(4726,237,1620,0,111,1884,1672,25,1068649592,1,216,'',0),(4727,237,1672,0,112,1620,1885,25,1068649592,1,216,'',0),(4728,237,1885,0,113,1672,1519,25,1068649592,1,216,'',0),(4729,237,1519,0,114,1885,1838,25,1068649592,1,216,'',0),(4730,237,1838,0,115,1519,1886,25,1068649592,1,216,'',0),(4731,237,1886,0,116,1838,1519,25,1068649592,1,216,'',0),(4732,237,1519,0,117,1886,1887,25,1068649592,1,216,'',0),(4733,237,1887,0,118,1519,1888,25,1068649592,1,216,'',0),(4734,237,1888,0,119,1887,1793,25,1068649592,1,216,'',0),(4735,237,1793,0,120,1888,1848,25,1068649592,1,216,'',0),(4736,237,1848,0,121,1793,1889,25,1068649592,1,216,'',0),(4737,237,1889,0,122,1848,1144,25,1068649592,1,216,'',0),(4738,237,1144,0,123,1889,1890,25,1068649592,1,216,'',0),(4739,237,1890,0,124,1144,1142,25,1068649592,1,216,'',0),(4740,237,1142,0,125,1890,1704,25,1068649592,1,216,'',0),(4741,237,1704,0,126,1142,1519,25,1068649592,1,216,'',0),(4742,237,1519,0,127,1704,1142,25,1068649592,1,216,'',0),(4743,237,1142,0,128,1519,1891,25,1068649592,1,216,'',0),(4744,237,1891,0,129,1142,1790,25,1068649592,1,216,'',0),(4745,237,1790,0,130,1891,1784,25,1068649592,1,216,'',0),(4746,237,1784,0,131,1790,73,25,1068649592,1,216,'',0),(4747,237,73,0,132,1784,1892,25,1068649592,1,216,'',0),(4748,237,1892,0,133,73,1750,25,1068649592,1,216,'',0),(4749,237,1750,0,134,1892,1678,25,1068649592,1,216,'',0),(4750,237,1678,0,135,1750,1439,25,1068649592,1,216,'',0),(4751,237,1439,0,136,1678,1704,25,1068649592,1,216,'',0),(4752,237,1704,0,137,1439,1893,25,1068649592,1,216,'',0),(4753,237,1893,0,138,1704,1519,25,1068649592,1,216,'',0),(4754,237,1519,0,139,1893,1142,25,1068649592,1,216,'',0),(4755,237,1142,0,140,1519,1894,25,1068649592,1,216,'',0),(4756,237,1894,0,141,1142,1519,25,1068649592,1,216,'',0),(4757,237,1519,0,142,1894,1865,25,1068649592,1,216,'',0),(4758,237,1865,0,143,1519,1142,25,1068649592,1,216,'',0),(4759,237,1142,0,144,1865,1841,25,1068649592,1,216,'',0),(4760,237,1841,0,145,1142,1895,25,1068649592,1,216,'',0),(4761,237,1895,0,146,1841,1697,25,1068649592,1,216,'',0),(4762,237,1697,0,147,1895,1896,25,1068649592,1,216,'',0),(4763,237,1896,0,148,1697,1611,25,1068649592,1,216,'',0),(4764,237,1611,0,149,1896,1677,25,1068649592,1,216,'',0),(4765,237,1677,0,150,1611,1897,25,1068649592,1,216,'',0),(4766,237,1897,0,151,1677,1798,25,1068649592,1,216,'',0),(4767,237,1798,0,152,1897,1607,25,1068649592,1,216,'',0),(4768,237,1607,0,153,1798,1898,25,1068649592,1,216,'',0),(4769,237,1898,0,154,1607,1439,25,1068649592,1,216,'',0),(4770,237,1439,0,155,1898,1899,25,1068649592,1,216,'',0),(4771,237,1899,0,156,1439,1900,25,1068649592,1,216,'',0),(4772,237,1900,0,157,1899,1901,25,1068649592,1,216,'',0),(4773,237,1901,0,158,1900,1830,25,1068649592,1,216,'',0),(4774,237,1830,0,159,1901,1611,25,1068649592,1,216,'',0),(4775,237,1611,0,160,1830,1902,25,1068649592,1,216,'',0),(4776,237,1902,0,161,1611,1692,25,1068649592,1,216,'',0),(4777,237,1692,0,162,1902,1903,25,1068649592,1,216,'',0),(4778,237,1903,0,163,1692,1670,25,1068649592,1,216,'',0),(4779,237,1670,0,164,1903,1607,25,1068649592,1,216,'',0),(4780,237,1607,0,165,1670,1904,25,1068649592,1,216,'',0),(4781,237,1904,0,166,1607,1895,25,1068649592,1,216,'',0),(4782,237,1895,0,167,1904,1905,25,1068649592,1,216,'',0),(4783,237,1905,0,168,1895,1666,25,1068649592,1,216,'',0),(4784,237,1666,0,169,1905,1906,25,1068649592,1,216,'',0),(4785,237,1906,0,170,1666,1907,25,1068649592,1,216,'',0),(4786,237,1907,0,171,1906,1142,25,1068649592,1,216,'',0),(4787,237,1142,0,172,1907,1891,25,1068649592,1,216,'',0),(4788,237,1891,0,173,1142,1670,25,1068649592,1,216,'',0),(4789,237,1670,0,174,1891,1807,25,1068649592,1,216,'',0),(4790,237,1807,0,175,1670,1439,25,1068649592,1,216,'',0),(4791,237,1439,0,176,1807,1908,25,1068649592,1,216,'',0),(4792,237,1908,0,177,1439,1704,25,1068649592,1,216,'',0),(4793,237,1704,0,178,1908,1909,25,1068649592,1,216,'',0),(4794,237,1909,0,179,1704,1904,25,1068649592,1,216,'',0),(4795,237,1904,0,180,1909,1441,25,1068649592,1,216,'',0),(4796,237,1441,0,181,1904,1142,25,1068649592,1,216,'',0),(4797,237,1142,0,182,1441,1910,25,1068649592,1,216,'',0),(4798,237,1910,0,183,1142,1519,25,1068649592,1,216,'',0),(4799,237,1519,0,184,1910,1672,25,1068649592,1,216,'',0),(4800,237,1672,0,185,1519,1911,25,1068649592,1,216,'',0),(4801,237,1911,0,186,1672,1912,25,1068649592,1,216,'',0),(4802,237,1912,0,187,1911,1913,25,1068649592,1,216,'',0),(4803,237,1913,0,188,1912,1621,25,1068649592,1,216,'',0),(4804,237,1621,0,189,1913,1914,25,1068649592,1,216,'',0),(4805,237,1914,0,190,1621,1665,25,1068649592,1,216,'',0),(4806,237,1665,0,191,1914,1670,25,1068649592,1,216,'',0),(4807,237,1670,0,192,1665,1439,25,1068649592,1,216,'',0),(4808,237,1439,0,193,1670,1915,25,1068649592,1,216,'',0),(4809,237,1915,0,194,1439,1704,25,1068649592,1,216,'',0),(4810,237,1704,0,195,1915,1672,25,1068649592,1,216,'',0),(4811,237,1672,0,196,1704,1916,25,1068649592,1,216,'',0),(4812,237,1916,0,197,1672,1750,25,1068649592,1,216,'',0),(4813,237,1750,0,198,1916,1678,25,1068649592,1,216,'',0),(4814,237,1678,0,199,1750,1917,25,1068649592,1,216,'',0),(4815,237,1917,0,200,1678,1918,25,1068649592,1,216,'',0),(4816,237,1918,0,201,1917,0,25,1068649592,1,217,'',1),(4817,238,1919,0,0,0,1827,25,1068652630,1,216,'',0),(4818,238,1827,0,1,1919,0,25,1068652630,1,217,'',0),(4820,239,1546,0,0,0,1921,4,1069246036,1,8,'',0),(4821,239,1921,0,1,1546,1922,4,1069246036,1,9,'',0),(4822,239,1922,0,2,1921,1316,4,1069246036,1,197,'',0),(4823,239,1316,0,3,1922,1923,4,1069246036,1,198,'',0),(4824,239,1923,0,4,1316,0,4,1069246036,1,199,'',0),(4825,241,1924,0,0,0,1827,25,1069328695,1,216,'',0),(4826,241,1827,0,1,1924,0,25,1069328695,1,217,'',0),(4827,242,1925,0,0,0,1827,25,1069328774,1,216,'',0),(4828,242,1827,0,1,1925,0,25,1069328774,1,217,'',0),(4902,56,1964,0,6,1670,1965,15,1066643397,11,218,'',0),(4901,56,1670,0,5,1963,1964,15,1066643397,11,218,'',0),(4900,56,1963,0,4,1962,1670,15,1066643397,11,218,'',0),(4899,56,1962,0,3,1961,1963,15,1066643397,11,218,'',0),(4898,56,1961,0,2,1960,1962,15,1066643397,11,218,'',0),(4897,56,1960,0,1,1959,1961,15,1066643397,11,218,'',0),(4896,56,1959,0,0,0,1960,15,1066643397,11,161,'',0);
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
INSERT INTO ezsearch_return_count VALUES (1,1,1066398569,1),(2,2,1066909621,1),(3,3,1066910511,1),(4,4,1066912239,1),(5,5,1066982534,1),(6,6,1066991890,4),(7,6,1066992837,4),(8,6,1066992963,4),(9,6,1066992972,0),(10,6,1066993049,0),(11,6,1066993056,4),(12,6,1066993091,4),(13,6,1066993127,4),(14,6,1066993135,4),(15,6,1066993895,4),(16,6,1066993946,4),(17,6,1066993995,4),(18,6,1066994001,4),(19,6,1066994050,4),(20,6,1066994057,4),(21,6,1066994067,4),(22,7,1066996820,0),(23,5,1066997190,1),(24,5,1066997194,1),(25,8,1066998830,1),(26,8,1066998836,1),(27,8,1066998870,1),(28,9,1066998915,1),(29,10,1067003146,0),(30,11,1067003155,2),(31,6,1067005771,4),(32,6,1067005777,4),(33,6,1067005801,4),(34,12,1067006770,1),(35,12,1067006774,1),(36,12,1067006777,1),(37,12,1067006787,1),(38,12,1067006803,1),(39,12,1067006996,1),(40,12,1067008585,1),(41,12,1067008597,1),(42,12,1067008602,0),(43,12,1067008608,1),(44,12,1067008613,0),(45,12,1067008620,0),(46,12,1067008625,0),(47,12,1067008629,1),(48,12,1067008655,1),(49,12,1067008659,0),(50,12,1067008663,0),(51,12,1067008667,0),(52,12,1067008711,0),(53,12,1067008717,0),(54,12,1067008720,1),(55,12,1067008725,0),(56,12,1067008920,1),(57,12,1067008925,1),(58,12,1067008929,0),(59,12,1067008934,1),(60,12,1067009005,1),(61,12,1067009023,1),(62,12,1067009042,1),(63,12,1067009051,0),(64,13,1067009056,1),(65,14,1067009067,0),(66,14,1067009073,0),(67,13,1067009594,1),(68,13,1067009816,1),(69,13,1067009953,1),(70,13,1067010181,1),(71,13,1067010352,1),(72,13,1067010359,1),(73,13,1067010370,1),(74,13,1067010509,1),(75,6,1067241668,5),(76,6,1067241727,5),(77,6,1067241742,5),(78,6,1067241760,5),(79,6,1067241810,5),(80,6,1067241892,5),(81,6,1067241928,5),(82,6,1067241953,5),(83,14,1067252984,0),(84,14,1067252987,0),(85,14,1067253026,0),(86,14,1067253160,0),(87,14,1067253218,0),(88,14,1067253285,0),(89,5,1067520640,1),(90,5,1067520646,1),(91,5,1067520658,1),(92,5,1067520704,0),(93,5,1067520753,0),(94,5,1067520761,1),(95,5,1067520769,1),(96,5,1067521324,1),(97,5,1067521402,1),(98,5,1067521453,1),(99,5,1067521532,1),(100,5,1067521615,1),(101,5,1067521674,1),(102,5,1067521990,1),(103,5,1067522592,1),(104,5,1067522620,1),(105,5,1067522888,1),(106,5,1067522987,1),(107,5,1067523012,1),(108,5,1067523144,1),(109,5,1067523213,1),(110,5,1067523261,1),(111,5,1067523798,1),(112,5,1067523805,1),(113,5,1067523820,1),(114,5,1067523858,1),(115,5,1067524474,1),(116,5,1067524629,1),(117,5,1067524696,1),(118,15,1067526426,0),(119,15,1067526433,0),(120,15,1067526701,0),(121,15,1067527009,0),(122,5,1067527022,1),(123,5,1067527033,1),(124,5,1067527051,1),(125,5,1067527069,1),(126,5,1067527076,0),(127,5,1067527124,1),(128,5,1067527176,1),(129,16,1067527188,0),(130,16,1067527227,0),(131,16,1067527244,0),(132,16,1067527301,0),(133,5,1067527315,0),(134,5,1067527349,0),(135,5,1067527412,0),(136,5,1067527472,1),(137,5,1067527502,1),(138,5,1067527508,0),(139,17,1067527848,0),(140,5,1067527863,1),(141,5,1067527890,1),(142,5,1067527906,1),(143,5,1067527947,1),(144,5,1067527968,0),(145,5,1067527993,0),(146,5,1067528010,1),(147,5,1067528029,0),(148,5,1067528045,0),(149,5,1067528050,0),(150,5,1067528056,0),(151,5,1067528061,0),(152,5,1067528063,0),(153,18,1067528100,1),(154,18,1067528113,0),(155,18,1067528190,1),(156,18,1067528236,1),(157,18,1067528270,1),(158,18,1067528309,1),(159,5,1067528323,0),(160,18,1067528334,1),(161,18,1067528355,1),(162,5,1067528368,0),(163,5,1067528377,1),(164,19,1067528402,0),(165,19,1067528770,0),(166,19,1067528924,0),(167,19,1067528963,0),(168,19,1067529028,0),(169,19,1067529054,0),(170,19,1067529119,0),(171,19,1067529169,0),(172,19,1067529211,0),(173,19,1067529263,0),(174,20,1067943156,3),(175,4,1067943454,1),(176,4,1067943503,1),(177,4,1067943525,1),(178,21,1067943559,1),(179,21,1067945657,1),(180,21,1067945693,1),(181,21,1067945697,1),(182,21,1067945707,1),(183,22,1067945890,0),(184,20,1067945898,3),(185,23,1067946301,6),(186,24,1067946325,1),(187,24,1067946432,1),(188,25,1067946484,4),(189,26,1067946492,1),(190,27,1067946577,1),(191,25,1067946691,4),(192,4,1067946702,1),(193,4,1067947201,1),(194,4,1067947228,1),(195,4,1067948201,1),(196,5,1068028867,0),(197,12,1068028883,0),(198,28,1068028898,2),(199,5,1068040205,0),(200,29,1068048420,0),(201,29,1068048455,1),(202,30,1068048466,0),(203,29,1068048480,0),(204,30,1068048487,2),(205,29,1068048592,0),(206,30,1068048615,2),(207,30,1068048653,2),(208,30,1068048698,2),(209,30,1068048707,2),(210,30,1068048799,2),(211,30,1068048825,2),(212,30,1068048830,2),(213,30,1068048852,2),(214,30,1068048874,2),(215,30,1068048890,2),(216,30,1068048918,2),(217,30,1068048928,2),(218,31,1068048940,2),(219,31,1068048964,2),(220,20,1068049003,0),(221,20,1068049007,2),(222,25,1068049014,3),(223,25,1068049043,3),(224,25,1068049062,3),(225,25,1068049082,3),(226,32,1068112266,5),(227,30,1068468248,3),(228,5,1068540763,0),(229,33,1068540772,0),(230,34,1068540778,1),(231,35,1068545355,1),(232,35,1068545429,1),(233,35,1068545463,1),(234,35,1068545543,1),(235,35,1068545984,1),(236,35,1068546316,1),(237,36,1068632170,1),(238,35,1068642029,1),(239,5,1069150386,1),(240,37,1069678121,1);
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
INSERT INTO ezsearch_search_phrase VALUES (1,'documents'),(2,'wenyue'),(3,'xxx'),(4,'release'),(5,'test'),(6,'ez'),(7,'f1'),(8,'bjørn'),(9,'abb'),(10,'2-2'),(11,'3.2'),(12,'bård'),(13,'Vidar'),(14,'tewtet'),(15,'dcv'),(16,'gr'),(17,'tewt'),(18,'members'),(19,'regte'),(20,'news'),(21,'german'),(22,'info'),(23,'information'),(24,'folder'),(25,'about'),(26,'2'),(27,'systems'),(28,'the'),(29,'football'),(30,'foo'),(31,'my'),(32,'reply'),(33,'hp'),(34,'pc'),(35,'ipod'),(36,'good'),(37,'f100');
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
INSERT INTO ezsearch_word VALUES (1513,'cursus',2),(6,'media',1),(7,'setup',3),(1639,'grouplist',1),(1638,'class',1),(1637,'classes',1),(11,'links',1),(25,'content',2),(34,'feel',2),(33,'and',9),(32,'look',2),(37,'news',3),(1493,'platea',2),(1965,'2003',1),(1492,'habitasse',2),(1491,'hac',2),(1490,'fringilla',2),(1489,'nonummy',2),(73,'this',4),(74,'is',3),(1508,'nam',2),(1507,'donec',2),(1488,'fermentum',2),(1487,'vestibulum',2),(1511,'porttitor',2),(1519,'of',5),(1520,'use',1),(1486,'erat',2),(1094,'music',1),(1551,'sdjkf',1),(1498,'non',2),(1485,'vitae',2),(1483,'magna',2),(1484,'mi',2),(1482,'tempor',2),(1481,'lectus',2),(1480,'rhoncus',2),(1399,'lcd',1),(1479,'nunc',2),(1478,'lacus',2),(1518,'conditions',1),(1504,'sem',2),(1503,'congue',2),(1394,'sd',2),(1502,'nec',2),(1506,'consequat',2),(1398,'monitor',1),(1397,'mac',1),(1945,'webshop',1),(1944,'latest',1),(1495,'duis',2),(1496,'interdum',2),(1497,'ornare',2),(1393,'sdfg',3),(1522,'us',2),(1494,'dictumst',2),(1523,'please',1),(1505,'viverra',2),(1548,'gjksdfjkghsdjkf',1),(1640,'cache',1),(1547,'10200',1),(1546,'test',2),(1545,'option',1),(1521,'contact',1),(1477,'accumsan',2),(1476,'vehicula',2),(1475,'velit',2),(1474,'elementum',2),(1473,'tellus',2),(1472,'suscipit',2),(1471,'commodo',2),(1470,'sagittis',2),(1469,'enim',2),(1468,'vel',2),(1467,'felis',2),(1466,'ullamcorper',2),(1942,'october',1),(1144,'from',2),(1941,'bulletin',2),(1142,'the',6),(1395,'gsdf',1),(1396,'pc',1),(1392,'dfg',1),(1321,'sig',1),(1320,'oslo',1),(1319,'guru',1),(1318,'farstad',1),(1540,'developer',1),(1316,'norway',3),(1539,'skien',1),(1465,'pellentesque',2),(1464,'fusce',2),(1538,'uberguru',1),(1510,'dui',2),(1509,'bibendum',2),(1517,'notice',1),(1516,'privacy',1),(1515,'laoreet',2),(571,'doe',1),(570,'john',1),(572,'vid',1),(573,'la',1),(1463,'tortor',2),(1462,'scelerisque',2),(1461,'pharetra',2),(1460,'aenean',2),(1459,'facilisis',2),(1458,'ut',2),(1457,'tristique',2),(1456,'eros',2),(1455,'turpis',2),(1454,'eu',2),(1453,'metus',2),(1452,'blandit',2),(1451,'ac',2),(1450,'neque',2),(1449,'dapibus',2),(1448,'volutpat',2),(1447,'iaculis',2),(1446,'id',2),(1445,'purus',2),(1444,'imperdiet',2),(1443,'phasellus',2),(1442,'libero',2),(1441,'at',4),(1440,'tincidunt',2),(1439,'a',8),(1438,'molestie',2),(1437,'eget',2),(1436,'dignissim',2),(1435,'est',2),(1434,'proin',2),(1433,'odio',2),(1432,'morbi',2),(1431,'nulla',2),(1430,'et',2),(1429,'wisi',2),(1428,'diam',2),(1427,'gravida',2),(1426,'aliquam',2),(1425,'quam',2),(1424,'nisl',2),(1423,'eleifend',2),(1422,'sed',2),(1421,'mauris',2),(1420,'egestas',2),(1419,'maecenas',2),(1418,'massa',2),(1417,'in',5),(1416,'elit',2),(1415,'adipiscing',2),(1414,'consectetuer',2),(1413,'amet',2),(1412,'sit',2),(1411,'dolor',2),(1410,'returns',1),(944,'ipsum',3),(943,'lorem',3),(1409,'shipping',1),(1940,'shop',2),(1407,'sfsf',1),(1406,'23',1),(1642,'56',1),(1641,'edit',1),(1644,'translator',1),(1643,'url',1),(1552,'pressario',1),(1514,'quis',2),(1535,'our',1),(1405,'m2000',1),(1534,'products',3),(1512,'integer',2),(1943,'here',1),(1964,'1999',1),(1550,'sdkjgh',1),(1501,'suspendisse',2),(1500,'facilisi',2),(1499,'sapien',2),(1404,'compaq',2),(1549,'gsdjkgh',1),(1536,'administrator',1),(1537,'user',1),(1140,'bård',1),(1544,'player',1),(1543,'mp3',1),(1542,'i100',1),(1541,'ipod',1),(1391,'fgs',1),(1390,'gsd',1),(1389,'sdfgsdf',1),(1388,'sdfgklj',1),(1387,'g101',1),(1386,'nokia',1),(1553,'p1001',1),(1554,'comdafg',1),(1555,'sdkjfgh',1),(1556,'f100',1),(1557,'f1001',1),(1558,'asdfg',1),(1559,'sdjkflg',1),(1560,'sdjkg',1),(1561,'p223498',1),(1562,'c3po',1),(1563,'sdfgj',1),(1564,'sdfjkgh',1),(1565,'sdjkgh',1),(1566,'sdjkgf',1),(1567,'sdf',1),(1622,'website',2),(1621,'its',3),(1620,'on',4),(1619,'cool',1),(1618,'pretty',2),(1617,'were',2),(1616,'graphics',3),(1615,'further',1),(1614,'to',5),(1613,'dance',1),(1612,'can',5),(1611,'i',3),(1610,'beat',1),(1609,'good',1),(1608,'had',3),(1607,'it',5),(1623,'3',1),(1645,'urltranslator',1),(1647,'won',1),(1648,'t',2),(1649,'claim',1),(1650,'_is_',1),(1651,'best',1),(1652,'expansion',1),(1653,'pack',1),(1654,'mostly',1),(1655,'since',1),(1656,'house',1),(1657,'party',1),(1658,'livin',1),(1659,'large',1),(1660,'are',4),(1661,'distant',1),(1662,'memories',1),(1663,'for',2),(1664,'me',1),(1665,'but',3),(1666,'will',4),(1667,'go',1),(1668,'so',2),(1669,'far',1),(1670,'as',5),(1671,'say',1),(1672,'that',3),(1673,'makin',1),(1674,'magic',1),(1675,'recent',1),(1676,'memory',1),(1677,'ve',2),(1678,'been',2),(1679,'huge',1),(1680,'critic',1),(1681,'sims',1),(1682,'franchise',1),(1683,'largely',1),(1684,'result',1),(1685,'tso',1),(1686,'months',1),(1687,'goes',1),(1688,'long',1),(1689,'way',1),(1690,'toward',1),(1691,'answering',1),(1692,'my',2),(1693,'two',2),(1694,'chief',1),(1695,'concerns',1),(1696,'maxis',1),(1697,'has',2),(1698,'lost',1),(1699,'sight',1),(1700,'their',1),(1701,'fan',1),(1702,'base',1),(1703,'additional',1),(1704,'game',2),(1705,'play',1),(1706,'options',1),(1707,'needed',1),(1708,'stand',1),(1709,'alone',1),(1710,'am',1),(1711,'pleasantly',1),(1712,'suprised',1),(1713,'responds',1),(1714,'cries',1),(1715,'players',1),(1716,'who',2),(1717,'want',1),(1718,'simpler',1),(1719,'lifestyle',1),(1720,'much',1),(1721,'less',1),(1722,'did',1),(1723,'by',1),(1724,'providing',1),(1725,'tom',1),(1726,'barbara',1),(1727,'goode',1),(1728,'finally',1),(1729,'make',1),(1730,'debut',1),(1731,'neighborhood',1),(1732,'they',2),(1733,'meant',1),(1734,'self',1),(1735,'sufficent',1),(1736,'couple',1),(1737,'living',1),(1738,'off',3),(1739,'land',1),(1740,'making',1),(1741,'selling',1),(1742,'nectar',1),(1743,'butter',1),(1744,'bread',1),(1745,'beeswax',1),(1746,'okay',1),(1747,'we',2),(1748,'_still_',1),(1749,'don',1),(1750,'have',3),(1751,'foundations',1),(1752,'gable',1),(1753,'roofs',1),(1754,'2',1),(1755,'preview',1),(1756,'disk',1),(1757,'claims',1),(1758,'those',1),(1759,'features',1),(1760,'coming',1),(1761,'new',3),(1762,'wow',1),(1763,'m',1),(1764,'left',1),(1765,'with',3),(1766,'nothing',1),(1767,'complain',1),(1768,'about',2),(1769,'would',1),(1770,'done',1),(1771,'same',1),(1772,'any',1),(1773,'rate',1),(1774,'offline',1),(1775,'seems',1),(1776,'faired',1),(1777,'better',1),(1778,'than',1),(1779,'line',1),(1780,'counterpart',1),(1781,'fated',1),(1782,'be',3),(1783,'many',2),(1784,'years',2),(1785,'come',1),(1786,'kudos',1),(1787,'one',3),(1788,'http',1),(1789,'ez.no',1),(1790,'5',2),(1791,'action',1),(1792,'isn',1),(1793,'bad',2),(1794,'unseen',1),(1795,'sidekick',1),(1796,'guiding',1),(1797,'you',4),(1798,'through',2),(1799,'levels',1),(1800,'only',2),(1801,'verbally',1),(1802,'speakers',1),(1803,'sounds',1),(1804,'like',3),(1805,'developers',2),(1806,'hint',1),(1807,'not',2),(1808,'professional',1),(1809,'voiceover',1),(1810,'actor',1),(1811,'just',1),(1812,'plain',1),(1813,'annoying',1),(1814,'turn',1),(1815,'him',1),(1816,'aren',1),(1817,'anything',1),(1818,'blow',1),(1819,'away',1),(1820,'die',1),(1821,'hard',1),(1822,'fans',1),(1823,'may',1),(1824,'get',2),(1825,'kick',1),(1826,'out',2),(1827,'0',4),(1828,'bought',1),(1829,'right',1),(1830,'when',1),(1831,'came',1),(1832,'full',1),(1833,'pop',1),(1834,'expecting',1),(1835,'rich',1),(1836,'storyline',1),(1837,'innovative',1),(1838,'gameplay',1),(1839,'mindblowing',1),(1840,'innovations',1),(1841,'first',1),(1842,'what',1),(1843,'got',1),(1844,'was',1),(1845,'poorly',1),(1846,'written',1),(1847,'jumble',1),(1848,'fps',1),(1849,'cliches',1),(1850,'tied',1),(1851,'together',1),(1852,'attractive',1),(1853,'gaming',1),(1854,'engine',1),(1855,'felt',1),(1856,'wanted',1),(1857,'halo',1),(1858,'without',1),(1859,'talented',1),(1860,'writing',1),(1861,'edge',1),(1862,'your',1),(1863,'seat',1),(1864,'single',1),(1865,'replay',1),(1866,'value',1),(1867,'found',1),(1868,'level',1),(1869,'where',1),(1870,'defending',1),(1871,'against',1),(1872,'an',1),(1873,'onslaught',1),(1874,'enemies',1),(1875,'plan',1),(1876,'defense',1),(1877,'fun',1),(1878,'challenging',1),(1879,'offered',1),(1880,'multiple',1),(1881,'paths',1),(1882,'success',1),(1883,'if',1),(1884,'focused',1),(1885,'sort',1),(1886,'instead',1),(1887,'rehashing',1),(1888,'every',1),(1889,'cliche',1),(1890,'all',2),(1891,'past',1),(1892,'might',1),(1893,'worthy',1),(1894,'kind',1),(1895,'unreal',1),(1896,'seen',1),(1897,'played',1),(1898,'over',1),(1899,'dozen',1),(1900,'times',1),(1901,'usually',1),(1902,'upgraded',1),(1903,'machine',1),(1904,'stands',1),(1905,'ii',1),(1906,'fade',1),(1907,'into',1),(1908,'terrible',1),(1909,'daikatana',1),(1910,'top',1),(1911,'heap',1),(1912,'or',1),(1913,'perhaps',1),(1914,'bottom',1),(1915,'mediocre',1),(1916,'could',1),(1917,'great',2),(1918,'1',1),(1919,'asdfasdf',1),(1921,'testersen',1),(1922,'lord',1),(1923,'sigg',1),(1924,'revju',1),(1925,'hoho',1),(1963,'systems',1),(1962,'ez',1),(1961,'&copy',1),(1960,'copyright',1),(1959,'shop_package',1),(1946,'publish',1),(1947,'these',2),(1948,'soon',1),(1949,'releases',1),(1950,'important',1),(1951,'information',1),(1952,'tell',1),(1953,'release',2),(1954,'see',2),(1955,'step',1),(1956,'forward',1),(1957,'old',1),(1958,'site',1),(1966,'november',1),(1967,'month',1),(1968,'started',1),(1969,'product',1),(1970,'b',1),(1971,'both',1),(1972,'part',1),(1973,'portfolio',1),(1974,'basis',1),(1975,'there',1),(1976,'examples',1),(1977,'different',1),(1978,'categories',1),(1979,'add',1),(1980,'set',1),(1981,'prices',1),(1982,'write',1),(1983,'texts',1),(1984,'should',1),(1985,'also',1),(1986,'always',1),(1987,'pictures',1),(1988,'users',1),(1989,'reading',1);
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
INSERT INTO ezsession VALUES ('1d8825262cbd96439e0418110ef44615',1069942286,'LastAccessesURI|s:22:\"/content/view/full/155\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069680437;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069680437;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069680437;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"381\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"382\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"383\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069680438;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitationValues|a:1:{i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}'),('6b757a80dcd2886681c0a2dc420526f6',1069946611,'eZUserInfoCache_Timestamp|i:1069678236;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069678236;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069687394;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}eZUserDiscountRulesTimestamp|i:1069678236;eZUserDiscountRules10|a:0:{}userLimitations|a:2:{i:380;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"300\";s:9:\"policy_id\";s:3:\"380\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:2:{i:300;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"593\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"596\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"597\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"594\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"598\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"599\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"600\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"595\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"5\";}}i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}LastAccessesURI|s:22:\"/content/view/full/152\";FromPage|s:23:\"/content/view/full/167/\";canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069678355;canInstantiateClasses|i:1;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRules14|a:0:{}classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"23\";s:4:\"name\";s:7:\"Product\";}i:10;a:2:{s:2:\"id\";s:2:\"24\";s:4:\"name\";s:13:\"Feedback form\";}i:11;a:2:{s:2:\"id\";s:2:\"25\";s:4:\"name\";s:6:\"Review\";}}Preferences-advanced_menu|s:2:\"on\";FromGroupID|b:0;UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}'),('9d660d57da1f1f02106e5f90c4bd0ae9',1069690228,'LastAccessesURI|s:22:\"/content/view/full/155\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069407511;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069407511;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069431025;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"381\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"382\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"383\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:2:{i:380;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"300\";s:9:\"policy_id\";s:3:\"380\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069330639;eZUserDiscountRules10|a:0:{}userLimitationValues|a:2:{i:300;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"593\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"596\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"597\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"594\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"598\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"599\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"600\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"595\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"5\";}}i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}RegisterUserID|i:244;'),('464212df929324762edc60211494fb9e',1069690231,'LastAccessesURI|s:22:\"/content/view/full/153\";eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069407515;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069407515;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069431030;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:380;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"300\";s:9:\"policy_id\";s:3:\"380\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069320852;eZUserDiscountRules10|a:0:{}userLimitationValues|a:1:{i:300;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"593\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"596\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"597\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"594\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"598\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"599\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"600\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"595\";s:13:\"limitation_id\";s:3:\"300\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRules14|a:0:{}DiscardObjectID|s:2:\"14\";DiscardObjectVersion|s:1:\"8\";DiscardObjectLanguage|b:0;DiscardConfirm|b:1;FromPage|s:23:\"/content/view/full/167/\";MyTemporaryOrderID|i:28;UserOrderID|s:2:\"28\";'),('61681817f87fc11f7c60bef4c46779d5',1069946502,'LastAccessesURI|s:21:\"/content/view/full/50\";eZUserInfoCache_Timestamp|i:1069686052;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069686126;eZUserGroupsCache_Timestamp|i:1069686052;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069687268;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}canInstantiateClasses|i:1;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserDiscountRulesTimestamp|i:1069686052;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"4\";}classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"23\";s:4:\"name\";s:7:\"Product\";}i:10;a:2:{s:2:\"id\";s:2:\"24\";s:4:\"name\";s:13:\"Feedback form\";}i:11;a:2:{s:2:\"id\";s:2:\"25\";s:4:\"name\";s:6:\"Review\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}'),('310c286311d138e22ca04681dfacc2a7',1069946533,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069686069;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069686069;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069687333;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069686069;eZUserDiscountRules10|a:0:{}userLimitations|a:1:{i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"4\";}LastAccessesURI|s:21:\"/content/view/full/50\";UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"381\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"382\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"383\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}'),('1dde902a168671f48191995b2f7222f1',1069690224,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069425098;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069425098;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069430881;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"381\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"382\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"383\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069425099;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitationValues|a:1:{i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}'),('b9a0d3aac222f6ebd2210d5a467c08d1',1069690225,'LastAccessesURI|s:22:\"/content/view/full/153\";eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069430462;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069430462;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069431023;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"381\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"382\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"383\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}eZUserDiscountRulesTimestamp|i:1069430462;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitationValues|a:1:{i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}'),('610eee016757bf20a073e9b635ca338f',1069946286,'eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_Timestamp|i:1069686948;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1069686949;eZUserGroupsCache_Timestamp|i:1069686948;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1069686948;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}canInstantiateClasses|i:1;Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:8:\"bf@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:12:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:6;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:7;a:2:{s:2:\"id\";s:2:\"14\";s:4:\"name\";s:10:\"Setup link\";}i:8;a:2:{s:2:\"id\";s:2:\"15\";s:4:\"name\";s:13:\"Template look\";}i:9;a:2:{s:2:\"id\";s:2:\"23\";s:4:\"name\";s:7:\"Product\";}i:10;a:2:{s:2:\"id\";s:2:\"24\";s:4:\"name\";s:13:\"Feedback form\";}i:11;a:2:{s:2:\"id\";s:2:\"25\";s:4:\"name\";s:6:\"Review\";}}Preferences-advanced_menu|s:2:\"on\";eZGlobalSection|a:1:{s:2:\"id\";s:2:\"11\";}'),('6504c19a10041a915e9c9d7e234c8702',1069942460,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069678868;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069678868;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069683255;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069676433;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"4\";}userLimitations|a:1:{i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}LastAccessesURI|s:21:\"/content/view/full/50\";FromPage|s:23:\"/content/view/full/159/\";UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"381\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"382\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"383\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}'),('e9dfb7981bccd905ef01a27f66aabee6',1069946687,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1069685994;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1069685994;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1069687427;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}eZUserDiscountRulesTimestamp|i:1069674100;eZUserDiscountRules10|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:382;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"301\";s:9:\"policy_id\";s:3:\"382\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:301;a:8:{i:0;a:3:{s:2:\"id\";s:3:\"601\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"602\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"10\";}i:2;a:3:{s:2:\"id\";s:3:\"603\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"12\";}i:3;a:3:{s:2:\"id\";s:3:\"604\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"2\";}i:4;a:3:{s:2:\"id\";s:3:\"605\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"23\";}i:5;a:3:{s:2:\"id\";s:3:\"606\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"24\";}i:6;a:3:{s:2:\"id\";s:3:\"607\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:2:\"25\";}i:7;a:3:{s:2:\"id\";s:3:\"608\";s:13:\"limitation_id\";s:3:\"301\";s:5:\"value\";s:1:\"5\";}}}LastAccessesURI|s:22:\"/content/view/full/155\";UserPolicies|a:1:{i:1;a:3:{i:0;a:5:{s:2:\"id\";s:3:\"381\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}i:1;a:5:{s:2:\"id\";s:3:\"382\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:2;a:5:{s:2:\"id\";s:3:\"383\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"shop\";s:13:\"function_name\";s:3:\"buy\";s:10:\"limitation\";s:1:\"*\";}}}');
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
INSERT INTO ezsubtree_notification_rule VALUES (1,'nospam@ez.no',0,112),(2,'wy@ez.no',0,112),(3,'nospam@ez.no',0,123),(9,'bf@ez.no',0,153),(8,'bf@ez.no',0,165),(6,'wy@ez.no',0,114),(7,'bf@ez.no',0,152),(10,'nospam@ez.no',0,165);
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
INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,NULL),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,NULL),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,NULL),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,NULL),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,NULL),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,NULL),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,NULL),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,NULL),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,NULL),(21,'setup','a0f848942ce863cf53c0fa6cc684007d','content/view/full/44',1,0,0),(22,'setup/classes','9e8c46c1357285763cd49ea56c57312d','content/view/full/45',1,24,0),(23,'setup/setup_links','675a9c5ab6fb3f5fdfaa609b7ef9d997','content/view/full/46',1,0,0),(24,'setup/setup_links/classes','75b3e86b0bb8a74fcb38f10fd02945e8','content/view/full/45',1,0,0),(25,'setup/setup_links/setup_objects','a695bd42e59634b44441ca4e4548e94a','content/view/full/47',1,80,0),(26,'setup/fonts_and_colors','db4641c5ea979dba4cfd99ea3267a456','content/view/full/48',1,27,0),(27,'setup/look_and_feel','11f42026b65f2d1801679ba58e443944','content/view/full/48',1,0,0),(83,'contact/persons/yu_wenyue','fc401743c753cd52d41b8bbeffbda14a','content/view/full/85',1,0,0),(29,'news','508c75c8507a2ae5223dfd2faeb98122','content/view/full/50',1,0,0),(125,'discussions/music_discussion/latest_msg_not_sticky','1980b453976fed108ef2874bac0f8477','content/view/full/130',1,0,0),(126,'discussions/music_discussion/not_sticky_2','06916ca78017a7482957aa4997f66664','content/view/full/131',1,0,0),(34,'setup/look_and_feel/intranet','6d6a9d6e8f6cadb080fffb1276dd1e5e','content/view/full/54',1,179,0),(124,'discussions/music_discussion/new_topic_sticky/reply','ae271e634c8d9cb077913b222e4b9d17','content/view/full/129',1,0,0),(121,'news/news_bulletin','9365952d8950c12f923a3a48e5e27fa3','content/view/full/126',1,178,0),(122,'about_this_forum','55803ba2746d617ca86e2a61b1d32d8b','content/view/full/127',1,157,0),(123,'discussions/music_discussion/new_topic_sticky','493ae5ad7ceb46af67edfdaf244d047a','content/view/full/128',1,0,0),(99,'setup/look_and_feel/corporate','ab9f681938bd76b97b3ab1256b61119e','content/view/full/54',1,179,0),(90,'contact/companies/foo_bar_corp/fido_barida','ce1be6fe76c4671d8616c8bf1b5365de','content/view/full/102',1,0,0),(93,'setup/look_and_feel/intranetyy','53849c55dbaf18cf2c0b278123c9a7b2','content/view/full/54',1,179,0),(87,'contact/companies/foo_bar_corp','b22fd60d77fb6f2a6f9ac44b28c6ff16','content/view/full/99',1,0,0),(88,'contact/companies/ez_sys/vidar_langseid','df1e0c77c37e8039c443cb24d9494996','content/view/full/100',1,0,0),(89,'contact/companies/ez_sys/brd_farstad','9c7d13ba2d21bc56807f81ee923bce94','content/view/full/101',1,0,0),(59,'contact/companies/abb','809afee2cd77358a08683bf42e27636f','content/view/full/78',1,0,0),(60,'files/products/online_editor','766820f3f5b43065be86e00af303dc78','content/view/full/79',1,0,0),(61,'files/products/ez_publish_32','bb00f9e0da1ab19bedc52774d1b75dd2','content/view/full/80',1,0,0),(127,'discussions/music_discussion/important_sticky','5b25f18de9f5bafe8050dafdaa759fca','content/view/full/132',1,0,0),(65,'munich1','3a6e2f1cb7b127c4984af22780094240','content/view/full/84',1,69,0),(66,'contact/persons/wenyue','05cf086075eeb7923d9ef1d22c358892','content/view/full/85',1,83,0),(84,'contact/persons/reiten_bjrn','af38d7e864c796edd66d5a0aaea69c8c','content/view/full/90',1,0,0),(69,'media/images/news/munich1','0492853131729dac783e4c4dc6e7a676','content/view/full/84',1,0,0),(71,'media/images/contact/mr_xxx','e613416ebc175f81b5660d2e1758d1d0','content/view/full/89',1,0,0),(72,'l','2db95e8e1a9267b7a1188556b2013b33','user/logout',0,0,0),(73,'contact/persons/bjrn','59dd7166c379c7fd437cd6afe746a285','content/view/full/90',1,84,0),(74,'users/editors/john_doe','470ba5117b9390b819f7c2519c0a6092','content/view/full/91',1,0,0),(75,'users/editors/vid_la','73f7efbac10f9f69aa4f7b19c97cfb16','content/view/full/92',1,0,0),(102,'discussions/this_is_a_new_topic','61d5152ba3d9318df59ebe28bce4c690','content/view/full/112',1,105,0),(150,'products/ipod','51e309072a95b78d03af9d6feb817f70','content/view/full/152',1,0,0),(78,'setup/setup_links/cache','1f2374cab6280ecfca991a7b6e5119c6','content/view/full/95',1,0,0),(79,'setup/setup_links/url_translator','7b226327c99e6fd78ad40eb66892d7ae','content/view/full/96',1,0,0),(80,'setup/setup_links/look_and_feel','37986c863618270fa0fa6936ba217c7b','content/view/full/47',1,0,0),(82,'images/*','04e9ea07da46830b94f38285ba6ea065','media/images/{1}',1,0,1),(86,'contact/companies/ez_sys','9e1c777b00ef2ded56fe0fdf13547570','content/view/full/98',1,0,0),(153,'products/mac','27d3c56b9e524933b967513a3484af66','content/view/full/156',1,0,0),(105,'discussions/music_discussion/this_is_a_new_topic','2344619129cdcf0b057b66b259d43a86','content/view/full/112',1,0,0),(106,'discussions/this_is_a_new_topic/*','3597b3c74225331ec401c8abc9f6d1d4','discussions/music_discussion/this_is_a_new_topic/{1}',1,0,1),(151,'products/nokia_g101','6b9acb43cb911430e4f12281c72052dc','content/view/full/153',1,0,0),(117,'discussions/music_discussion/this_is_a_new_topic/foo_bar','8ccf76d178398a5021594b8dcc111ef3','content/view/full/122',1,0,0),(178,'news/news_bulletin_october','4bb330d0024e02fb3954cbd69fca08c8','content/view/full/126',1,0,0),(111,'discussions/sports_discussion/football','687ae615eecb9131ce8600e02f087921','content/view/full/119',1,0,0),(149,'hardware','3ca14c518d1bf901acc339e7c9cd6d7f','content/view/full/154',1,162,0),(113,'forum/*','94b1ef84913dabe113cb907c181ee300','discussions/{1}',1,0,1),(115,'setup/look_and_feel/forum','00d91935e17d76f152f7aaf0c0defac2','content/view/full/54',1,179,0),(114,'discussions/music_discussion/this_is_a_new_topic/my_reply','295c0cf1dfb0786654b87ae7879269ce','content/view/full/120',1,0,0),(118,'discussions/music_discussion/what_about_pop','29e6fdc68db2a2820a4198ccf9606316','content/view/full/123',1,0,0),(119,'discussions/music_discussion/reply_wanted_for_this_topic','659797091633ef0b16807a67d6594e12','content/view/full/124',1,0,0),(120,'discussions/music_discussion/reply_wanted_for_this_topic/this_is_a_reply','cd75b5016b43b7dec4c22e911c98b00f','content/view/full/125',1,0,0),(128,'discussions/sports_discussion/football/reply_2','b99ca3fa56d5010fd9e2edb25c6c723c','content/view/full/133',1,0,0),(130,'discussions/music_discussion/lkj_ssssstick','515b0805b631e2e60f5a01a62078aafd','content/view/full/135',1,0,0),(131,'discussions/music_discussion/foo','c30b12e11f43e38e5007e437eb28f7fc','content/view/full/136',1,0,0),(132,'discussions/music_discussion/lkj_ssssstick/reply','b81320d415f41d95b962b73d36e2c248','content/view/full/137',1,0,0),(135,'discussions/music_discussion/lkj_ssssstick/uyuiyui','c560e70f61e30defc917cf5fd1824831','content/view/full/140',1,0,0),(136,'discussions/music_discussion/test2','79a4b87fad6297c89e32fcda0fdeadef','content/view/full/141',1,0,0),(137,'discussions/music_discussion/t4','a411ba84550a8808aa017d46d7f61899','content/view/full/142',1,0,0),(138,'discussions/music_discussion/lkj_ssssstick/klj_jkl_klj','ad8b440b5c57fce9f5ae28d271b2b629','content/view/full/143',1,0,0),(139,'discussions/music_discussion/test2/retest2','8e0e854c6f944f7b1fd9676c37258634','content/view/full/144',1,0,0),(140,'users/administrator_users/brd_farstad','875930f56fad1a5cc6fbcac4ed6d3f8d','content/view/full/145',1,0,0),(141,'discussions/music_discussion/lkj_ssssstick/my_reply','d0d8e13f8fc3f4d24ff7223c02bcd26d','content/view/full/146',1,0,0),(142,'discussions/music_discussion/lkj_ssssstick/retest','b9924edb42d7cb24b5b7ff0b3ae8d1f4','content/view/full/147',1,0,0),(152,'products/pc','a62e802a07be22168e981a80a1913f59','content/view/full/155',1,0,0),(144,'discussions/music_discussion/hjg_dghsdjgf','c9b3ef4c7c4cca6eacfc0d2e0a88747d','content/view/full/149',1,0,0),(146,'discussions/music_discussion/hjg_dghsdjgf/dfghd_fghklj','3353f2cdd52889bdf18d0071c9b3c85b','content/view/full/151',1,0,0),(147,'ipod','6cb5943c3186445c8cf1be9d0f2d4033','content/view/full/152',1,150,0),(148,'nokia_g101','a144bc0dfe258023250ad7b13a7b1e03','content/view/full/153',1,151,0),(154,'products/pc/monitor','43932094f5d6a4192391121641a4d25e','content/view/full/157',1,0,0),(155,'products/pc/monitor/lcd','560ee69360135cdba9e4b5e2139d55de','content/view/full/158',1,0,0),(156,'products/pc/compaq_m2000','ee0697d234f077390afd3244517a5deb','content/view/full/159',1,0,0),(157,'shipping_and_returns','b6e6c30236fd41d3623ad5cb6ac2bf7d','content/view/full/127',1,0,0),(158,'privacy_notice','8c8c68c20b331d0f4781cc125b98e700','content/view/full/160',1,0,0),(159,'conditions_of_use','53214a466568707294398ecd56b4f788','content/view/full/161',1,0,0),(162,'products','86024cad1e83101d97359d7351051156','content/view/full/154',1,0,0),(161,'contact_us__1','54f33014a45dc127271f59d0ff3e01f7','content/view/full/163',1,0,0),(163,'hardware/*','24c7f0cd68f9143e5c13f759ea1b90bd','products/{1}',1,0,1),(164,'products/option_test','e6e236c19f71724c1596e4225e327ce0','content/view/full/164',1,0,0),(165,'products/pc/compaq_pressario','d5cab4ca68ba4992555e86f0d0398d49','content/view/full/165',1,0,0),(166,'products/pc/f100','b534fc6ac7d95a261e95716215aa92bb','content/view/full/166',1,0,0),(167,'products/pc/p223498','b58d0f47d45864ab103164e3a75ed964','content/view/full/167',1,0,0),(168,'setup/look_and_feel/my_shop','dcc2fb6a7ef4778e4058de4a202ab95b','content/view/full/54',1,179,0),(169,'products/good','82f4dd56a317e99f2eda1145cb607304','content/view/full/168',1,170,0),(170,'products/nokia_g101/good','886a181a87942874de4f86f2aa0d6e7f','content/view/full/168',1,0,0),(171,'products/nokia_g101/the_best_expansion_pack','cad6a49cb63f723a0ad8e3645f264661','content/view/full/169',1,0,0),(172,'products/nokia_g101/whimper','cce4cafb3463ea9ebf42a8799ab51a4a','content/view/full/170',1,0,0),(173,'products/nokia_g101/an_utter_disappointment','9daee4420fb0af3dbd571eb65029bc2a','content/view/full/171',1,0,0),(174,'products/nokia_g101/asdfasdf','6222a99469ea4696f6c2b2b1f4d7b8a7','content/view/full/172',1,0,0),(175,'users/guest_accounts/test_testersen','94f21a6e83bd9026e80532d90a427cad','content/view/full/173',1,0,0),(176,'products/pc/p223498/testttt','5e7296784a203e64302e450dbce46d76','content/view/full/174',1,0,0),(177,'products/pc/p223498/ohoh','74d711fa7f51030efdb535e585c14abb','content/view/full/175',1,0,0),(179,'setup/look_and_feel/shop','10a4300fdb27b6b751340b62b885d81c','content/view/full/54',1,0,0),(180,'news/news_bulletin_november','ffa2ee44cb666a55101adbeaeeb6ad9f','content/view/full/176',1,0,0);
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
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','bf@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(206,'bfbf','bf@piranha.no',2,'78be1382dd64e987845778e68cf04968'),(107,'john','doe@ez.no',2,'e82dc887aa749d7bc91b9bc489e61968'),(108,'','',2,'b909d5bf76b64b7a6fac03f7eda11ee3'),(109,'','',2,'e4ab2f05e418842bb3abf148f9d06c1c'),(111,'vidla','vl@ez.no',2,'5289e8d223b023d527c47d58da538068'),(130,'','',2,'4ccb7125baf19de015388c99893fbb4d'),(246,'','',1,''),(187,'','',1,''),(189,'','',1,''),(239,'test','th@ez.no',2,'be778b473235e210cc577056226536a4'),(243,'','',1,''),(244,'','',1,''),(245,'','',1,''),(247,'','',1,''),(248,'','',1,''),(249,'','',1,'');
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
INSERT INTO ezuser_setting VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0),(107,1,0),(108,1,0),(109,1,0),(111,1,0),(130,1,0),(149,1,0),(154,0,0),(187,1,0),(188,0,0),(189,1,0),(197,0,0),(198,1,0),(206,1,0),(239,1,0),(243,1,0),(244,1,0),(245,1,0),(246,1,0),(247,1,0),(248,1,0),(249,1,0);
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

