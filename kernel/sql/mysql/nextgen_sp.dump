-- MySQL dump 9.06
--
-- Host: localhost    Database: nextgen_sp
---------------------------------------------------------
-- Server version	4.0.3-beta

--
-- Table structure for table 'ezbinaryfile'
--

CREATE TABLE ezbinaryfile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezbinaryfile'
--


--
-- Table structure for table 'ezcart'
--

CREATE TABLE ezcart (
  id int(11) NOT NULL auto_increment,
  session_id varchar(255) NOT NULL default '',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcart'
--

INSERT INTO ezcart (id, session_id, productcollection_id) VALUES (2,'1822a8c48c3c26c2c12883826a72a9ab',3);

--
-- Table structure for table 'ezcontentclass'
--

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
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclass'
--

INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (1,0,'Folder','folder','<name>',-1,1,1024392098,1031224170);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2,0,'Article','article','<title>',-1,8,1024392098,1031225013);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (3,0,'User group','user_group','<name>',-1,1,1024392098,1031224194);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (4,0,'User','user','<first_name> <last_name>',-1,1,1024392098,1031223207);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (5,0,'Simple Product','simple_product','<name>',8,8,1031228120,1031228251);

--
-- Table structure for table 'ezcontentclass_attribute'
--

CREATE TABLE ezcontentclass_attribute (
  id int(11) NOT NULL auto_increment,
  version int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  identifier varchar(50) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  data_type_string varchar(50) NOT NULL default '',
  is_searchable int(1) NOT NULL default '0',
  is_required  int(1) default '0',
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
  data_text4 varchar(50) default NULL,
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclass_attribute'
--

INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (13,0,2,'body','Body','eztext',1,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (8,0,4,'first_name','First name','ezstring',1,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (5,0,1,'name','Description','ezstring',1,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (9,0,4,'last_name','Last name','ezstring',1,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (12,0,4,'user_account','User account','ezuser',0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (7,0,3,'description','Description','ezstring',1,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (3,0,2,'integer','Integer text','ezinteger',0,3,1,100,0,3,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (2,0,2,'intro','Intro','ezstring',1,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (4,0,1,'name','Name','ezstring',1,1,255,0,0,0,0,0,0,0,'Folder','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (6,0,3,'name','Name','ezstring',1,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (1,0,2,'title','Title','ezstring',1,1,255,0,0,0,0,0,0,0,'New article','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (15,0,5,'description','description','ezstring',1,2,255,0,0,0,0,0,0,0,'About','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (14,0,5,'name','Name','ezstring',1,1,255,0,0,0,0,0,0,0,'Product','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (16,0,5,'price','Price','ezprice',1,3,0,0,0,0,0,0,0,0,'','','','');

--
-- Table structure for table 'ezcontentclass_classgroup'
--

CREATE TABLE ezcontentclass_classgroup (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (contentclass_id,contentclass_version,group_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclass_classgroup'
--

INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (1,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (2,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (4,0,2,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (6,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (6,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (7,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (1,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (2,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (4,1,2,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (5,1,3,'Products');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (5,0,3,'Products');

--
-- Table structure for table 'ezcontentclassgroup'
--

CREATE TABLE ezcontentclassgroup (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentclassgroup'
--

INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Content',1,1,1031216928,1031216937);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (2,'Users',1,1,1031216941,1031216949);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (3,'Products',8,8,1031227910,1031227919);

--
-- Table structure for table 'ezcontentobject'
--

CREATE TABLE ezcontentobject (
  id int(11) NOT NULL auto_increment,
  owner_id int(11) NOT NULL default '0',
  parent_id int(11) NOT NULL default '0',
  main_node_id int(11) NOT NULL default '0',
  section_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  current_version int(11) default NULL,
  is_published int(11) default NULL,
  permission_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject'
--

INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (1,0,0,2,1,1,'This folder contains some information about...',2,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (13,8,1,14,1,2,'Second Article',1,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (12,8,1,13,2,2,'First Article',1,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (4,0,0,5,0,3,'This is the master users',2,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (8,1,1,6,0,4,'Sergey Pushchin',2,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (10,1,1,11,0,3,'Other users',1,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (11,1,1,12,0,4,'Floyd Floyd',1,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (14,8,1,16,3,1,'Here are products',1,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (15,8,1,17,3,5,'eZ Publish 3.0',1,0,1);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id) VALUES (16,8,1,18,3,5,'eZ Publish book',1,0,1);

--
-- Table structure for table 'ezcontentobject_attribute'
--

CREATE TABLE ezcontentobject_attribute (
  id int(11) NOT NULL auto_increment,
  language_code varchar(20) NOT NULL default '',
  version int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  contentclassattribute_id int(11) NOT NULL default '0',
  data_text text,
  data_int int(11) default NULL,
  data_float float default NULL,
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_attribute'
--

INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (1,'en_GB',1,1,4,'My folder',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (2,'en_GB',1,1,5,'This folder contains some information about...',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (26,'en_GB',1,12,13,'bla bla bla bla bla bla\nbla bla blabla bla bla\n bla blbla bla blaa bla \nbla bla bla bla bla bla',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (25,'en_GB',1,12,3,'',10,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (24,'en_GB',1,12,2,'bla bla bla',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (7,'en_GB',1,4,5,'Main group',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (8,'en_GB',1,4,6,'This is the master users',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (15,'en_GB',1,8,12,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (13,'en_GB',1,8,8,'Sergey',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (14,'en_GB',1,8,9,'Pushchin',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (13,'en_GB',2,8,8,'Sergey',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (14,'en_GB',2,8,9,'Pushchin',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (15,'en_GB',2,8,12,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (7,'en_GB',2,4,5,'Main group',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (8,'en_GB',2,4,6,'This is the master users',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (23,'en_GB',1,12,1,'First Article',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (22,'en_GB',1,11,12,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (21,'en_GB',1,11,9,'Floyd',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (20,'en_GB',1,11,8,'Floyd',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (19,'en_GB',1,10,7,'Other users',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (18,'en_GB',1,10,6,'Other users',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (27,'en_GB',1,13,1,'Second Article',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (28,'en_GB',1,13,2,'This is the second article',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (29,'en_GB',1,13,3,'',20,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (30,'en_GB',1,13,13,'foo foo foo foo\nfoo foo foo foo\nfoo foo foo foo\nfoo foo foo foo\nfoo foo foo foo\nfoo foo foo foo\n',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (1,'en_GB',2,1,4,'My folder',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (2,'en_GB',2,1,5,'This folder contains some information about...',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (31,'en_GB',1,14,4,'Products',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (32,'en_GB',1,14,5,'Here are products',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (33,'en_GB',1,15,14,'eZ Publish 3.0',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (34,'en_GB',1,15,15,'eZ Publish 3.0 (The best CMS ever)',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (35,'en_GB',1,15,16,'',0,10000);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (36,'en_GB',1,16,14,'eZ Publish book',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (37,'en_GB',1,16,15,'How to manage your content with the eZ Publish 3.0',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (38,'en_GB',1,16,16,'',0,500);

--
-- Table structure for table 'ezcontentobject_link'
--

CREATE TABLE ezcontentobject_link (
  id int(11) NOT NULL auto_increment,
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_link'
--


--
-- Table structure for table 'ezcontentobject_perm_set'
--

CREATE TABLE ezcontentobject_perm_set (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_perm_set'
--

INSERT INTO ezcontentobject_perm_set (id, name) VALUES (1,'test set');
INSERT INTO ezcontentobject_perm_set (id, name) VALUES (2,'test set 2');

--
-- Table structure for table 'ezcontentobject_permission'
--

CREATE TABLE ezcontentobject_permission (
  id int(11) NOT NULL auto_increment,
  permission_id int(11) NOT NULL default '0',
  user_group_id int(11) NOT NULL default '0',
  read_permission int(11) NOT NULL default '0',
  create_permission int(11) NOT NULL default '0',
  edit_permission int(11) NOT NULL default '0',
  remove_permission int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_permission'
--

INSERT INTO ezcontentobject_permission (id, permission_id, user_group_id, read_permission, create_permission, edit_permission, remove_permission) VALUES (1,1,2,0,1,1,0);
INSERT INTO ezcontentobject_permission (id, permission_id, user_group_id, read_permission, create_permission, edit_permission, remove_permission) VALUES (2,1,3,0,1,1,1);

--
-- Table structure for table 'ezcontentobject_tree'
--

CREATE TABLE ezcontentobject_tree (
  node_id int(11) NOT NULL auto_increment,
  parent_node_id int(11) NOT NULL default '0',
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  contentobject_is_published int(11) default NULL,
  crc32_path int(11) default NULL,
  depth int(11) NOT NULL default '0',
  path_string varchar(255) NOT NULL default '',
  md5_path varchar(15) default NULL,
  left_margin int(11) NOT NULL default '0',
  right_margin int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id),
  KEY ezcontentobject_tree_path (path_string),
  KEY ezcontentobject_tree_p_node_id (parent_node_id),
  KEY ezcontentobject_tree_co_id (contentobject_id),
  KEY ezcontentobject_tree_depth (depth)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_tree'
--

INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (1,1,0,1,1,NULL,0,'/1/',NULL,1,16);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (2,1,1,1,NULL,NULL,1,'/1/2/',NULL,2,7);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (14,2,13,NULL,NULL,NULL,2,'/1/2/14/',NULL,7,8);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (13,2,12,NULL,NULL,NULL,2,'/1/2/13/',NULL,7,8);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (5,1,4,1,NULL,NULL,1,'/1/5/',NULL,8,15);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (9,5,8,NULL,NULL,NULL,2,'/1/5/9/',NULL,15,16);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (11,5,10,NULL,NULL,NULL,2,'/1/5/11/',NULL,15,16);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (12,11,11,NULL,NULL,NULL,3,'/1/5/11/12/',NULL,16,17);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (15,2,14,NULL,NULL,NULL,2,'/1/2/15/',NULL,7,8);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (16,15,15,NULL,NULL,NULL,3,'/1/2/15/16/',NULL,8,9);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin) VALUES (17,15,16,NULL,NULL,NULL,3,'/1/2/15/17/',NULL,8,9);

--
-- Table structure for table 'ezcontentobject_version'
--

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezcontentobject_version'
--

INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (1,1,0,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (20,1,8,2,1031225239,1031225244,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (19,13,8,1,1031225118,1031225195,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (4,4,0,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (8,8,1,1,1031223286,1031223570,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (9,8,1,2,1031223658,1031223663,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (10,4,1,2,1031223678,1031223910,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (18,12,8,1,1031225029,1031225094,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (16,11,1,1,1031224473,1031224508,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (15,10,1,1,1031224414,1031224439,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (21,14,8,1,1031228325,1031228360,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (22,15,8,1,1031228401,1031228474,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (23,16,8,1,1031228487,1031228562,0,0,0);

--
-- Table structure for table 'ezenumobjectvalue'
--

CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumid int(11) NOT NULL default '0',
  enumelement varchar(50) NOT NULL default '',
  enumvalue varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezenumobjectvalue'
--


--
-- Table structure for table 'ezenumvalue'
--

CREATE TABLE ezenumvalue (
  id int(11) NOT NULL auto_increment,
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(50) NOT NULL default '',
  enumvalue varchar(50) NOT NULL default '',
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezenumvalue'
--


--
-- Table structure for table 'ezimage'
--

CREATE TABLE ezimage (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezimage'
--


--
-- Table structure for table 'ezimagevariation'
--

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezimagevariation'
--


--
-- Table structure for table 'ezorder'
--

CREATE TABLE ezorder (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezorder'
--

INSERT INTO ezorder (id, user_id, productcollection_id, created) VALUES (1,8,2,1031228716);

--
-- Table structure for table 'ezpolicy'
--

CREATE TABLE ezpolicy (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy'
--

INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (61,3,'edit','class','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (62,3,'*','search','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (63,3,'read','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (64,3,'create','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (46,1,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (47,2,'*','*','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (59,4,'*','class','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (60,3,'list','class','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (58,4,'*','content','*');

--
-- Table structure for table 'ezpolicy_limitation'
--

CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  policy_id int(11) default NULL,
  identifier varchar(255) NOT NULL default '',
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy_limitation'
--

INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (15,64,'ParentClassID',0,'','');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (14,61,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (13,46,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (12,46,'ClassID',0,'','');

--
-- Table structure for table 'ezpolicy_limitation_value'
--

CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezpolicy_limitation_value'
--

INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (32,15,1);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (31,14,2);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (30,13,1);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (29,12,2);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (28,12,1);

--
-- Table structure for table 'ezproductcollection'
--

CREATE TABLE ezproductcollection (
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezproductcollection'
--

INSERT INTO ezproductcollection (id) VALUES (1);
INSERT INTO ezproductcollection (id) VALUES (2);
INSERT INTO ezproductcollection (id) VALUES (3);

--
-- Table structure for table 'ezproductcollection_item'
--

CREATE TABLE ezproductcollection_item (
  id int(11) NOT NULL auto_increment,
  productcollection_id int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  item_count int(11) NOT NULL default '0',
  price_is_inc_vat int(11) NOT NULL default '0',
  price int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezproductcollection_item'
--

INSERT INTO ezproductcollection_item (id, productcollection_id, contentobject_id, item_count, price_is_inc_vat, price) VALUES (1,1,15,1,0,10000);
INSERT INTO ezproductcollection_item (id, productcollection_id, contentobject_id, item_count, price_is_inc_vat, price) VALUES (2,2,16,1,0,500);

--
-- Table structure for table 'ezrole'
--

CREATE TABLE ezrole (
  id int(11) NOT NULL auto_increment,
  version int(11) default '0',
  name varchar(255) NOT NULL default '',
  value char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezrole'
--

INSERT INTO ezrole (id, version, name, value) VALUES (1,0,'Anonimous','');
INSERT INTO ezrole (id, version, name, value) VALUES (2,0,'Admin','*');
INSERT INTO ezrole (id, version, name, value) VALUES (3,0,'editor','');
INSERT INTO ezrole (id, version, name, value) VALUES (4,0,'advanced editor','');

--
-- Table structure for table 'ezsearch_object_word_link'
--

CREATE TABLE ezsearch_object_word_link (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) NOT NULL default '0',
  word_id int(11) NOT NULL default '0',
  frequency float NOT NULL default '0',
  placement int(11) NOT NULL default '0',
  prev_word_id int(11) NOT NULL default '0',
  next_word_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  contentclass_attribute_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_object_word_link_object (contentobject_id),
  KEY ezsearch_object_word_link_word (word_id),
  KEY ezsearch_object_word_link_frequency (frequency)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_object_word_link'
--

INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (4,8,4,0,1,3,0,4,9);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (3,8,3,0,0,0,4,4,8);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (5,4,5,0,0,0,6,3,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (6,4,6,0,1,5,7,3,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (7,4,7,0,2,6,8,3,6);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (8,4,8,0,3,7,9,3,6);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (9,4,9,0,4,8,10,3,6);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (10,4,10,0,5,9,11,3,6);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (11,4,11,0,6,10,0,3,6);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (23,9,11,0,3,14,0,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (22,9,14,0,2,11,11,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (21,9,11,0,1,14,14,1,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (20,9,14,0,0,0,11,1,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (24,10,14,0,0,0,11,3,6);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (25,10,11,0,1,14,14,3,6);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (26,10,14,0,2,11,11,3,7);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (27,10,11,0,3,14,0,3,7);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (28,11,15,0,0,0,15,4,8);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (29,11,15,0,1,15,0,4,9);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (30,12,16,0,0,0,17,2,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (31,12,17,0,1,16,18,2,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (32,12,18,0,2,17,18,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (33,12,18,0,3,18,18,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (34,12,18,0,4,18,18,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (35,12,18,0,5,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (36,12,18,0,6,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (37,12,18,0,7,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (38,12,18,0,8,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (39,12,18,0,9,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (40,12,18,0,10,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (41,12,18,0,11,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (42,12,18,0,12,18,19,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (43,12,19,0,13,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (44,12,18,0,14,19,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (45,12,18,0,15,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (46,12,18,0,16,18,20,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (47,12,20,0,17,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (48,12,18,0,18,20,21,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (49,12,21,0,19,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (50,12,18,0,20,21,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (51,12,18,0,21,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (52,12,18,0,22,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (53,12,18,0,23,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (54,12,18,0,24,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (55,12,18,0,25,18,18,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (56,12,18,0,26,18,0,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (57,13,22,0,0,0,17,2,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (58,13,17,0,1,22,7,2,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (59,13,7,0,2,17,8,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (60,13,8,0,3,7,9,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (61,13,9,0,4,8,22,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (62,13,22,0,5,9,17,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (63,13,17,0,6,22,23,2,2);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (64,13,23,0,7,17,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (65,13,23,0,8,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (66,13,23,0,9,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (67,13,23,0,10,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (68,13,23,0,11,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (69,13,23,0,12,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (70,13,23,0,13,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (71,13,23,0,14,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (72,13,23,0,15,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (73,13,23,0,16,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (74,13,23,0,17,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (75,13,23,0,18,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (76,13,23,0,19,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (77,13,23,0,20,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (78,13,23,0,21,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (79,13,23,0,22,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (80,13,23,0,23,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (81,13,23,0,24,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (82,13,23,0,25,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (83,13,23,0,26,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (84,13,23,0,27,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (85,13,23,0,28,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (86,13,23,0,29,23,23,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (87,13,23,0,30,23,0,2,13);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (88,1,24,0,0,0,25,1,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (89,1,25,0,1,24,7,1,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (90,1,7,0,2,25,25,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (91,1,25,0,3,7,26,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (92,1,26,0,4,25,27,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (93,1,27,0,5,26,28,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (94,1,28,0,6,27,29,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (95,1,29,0,7,28,0,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (96,14,30,0,0,0,31,1,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (97,14,31,0,1,30,32,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (98,14,32,0,2,31,30,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (99,14,30,0,3,32,0,1,5);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (100,15,33,0,0,0,34,5,14);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (101,15,34,0,1,33,35,5,14);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (102,15,35,0,2,34,36,5,14);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (103,15,36,0,3,35,33,5,14);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (104,15,33,0,4,36,34,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (105,15,34,0,5,33,35,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (106,15,35,0,6,34,36,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (107,15,36,0,7,35,37,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (108,15,37,0,8,36,38,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (109,15,38,0,9,37,39,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (110,15,39,0,10,38,40,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (111,15,40,0,11,39,41,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (112,15,41,0,12,40,0,5,16);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (113,16,33,0,0,0,34,5,14);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (114,16,34,0,1,33,42,5,14);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (115,16,42,0,2,34,43,5,14);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (116,16,43,0,3,42,44,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (117,16,44,0,4,43,45,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (118,16,45,0,5,44,46,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (119,16,46,0,6,45,47,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (120,16,47,0,7,46,48,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (121,16,48,0,8,47,9,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (122,16,9,0,9,48,33,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (123,16,33,0,10,9,34,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (124,16,34,0,11,33,35,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (125,16,35,0,12,34,36,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (126,16,36,0,13,35,49,5,15);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, contentclass_attribute_id) VALUES (127,16,49,0,14,36,0,5,16);

--
-- Table structure for table 'ezsearch_return_count'
--

CREATE TABLE ezsearch_return_count (
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_return_count'
--


--
-- Table structure for table 'ezsearch_search_phrase'
--

CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_search_phrase'
--


--
-- Table structure for table 'ezsearch_word'
--

CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  word varchar(150) default NULL,
  object_count int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_word (word)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsearch_word'
--

INSERT INTO ezsearch_word (id, word, object_count) VALUES (4,'pushchin',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (3,'sergey',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (5,'main',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (6,'group',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (7,'this',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (8,'is',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (9,'the',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (10,'master',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (11,'users',5);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (14,'other',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (15,'floyd',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (16,'first',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (17,'article',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (18,'bla',22);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (19,'blabla',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (20,'blbla',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (21,'blaa',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (22,'second',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (23,'foo',24);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (24,'my',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (25,'folder',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (26,'contains',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (27,'some',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (28,'information',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (29,'about',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (30,'products',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (31,'here',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (32,'are',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (33,'ez',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (34,'publish',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (35,'3',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (36,'0',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (37,'(the',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (38,'best',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (39,'cms',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (40,'ever)',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (41,'10000',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (42,'book',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (43,'how',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (44,'to',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (45,'manage',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (46,'your',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (47,'content',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (48,'with',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (49,'500',1);

--
-- Table structure for table 'ezsection'
--

CREATE TABLE ezsection (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  locale varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsection'
--

INSERT INTO ezsection (id, name, locale) VALUES (1,'Articles','nor-NO');
INSERT INTO ezsection (id, name, locale) VALUES (2,'Sport','en-US');
INSERT INTO ezsection (id, name, locale) VALUES (3,'Products','nor-No');

--
-- Table structure for table 'ezsession'
--

CREATE TABLE ezsession (
  session_key varchar(32) NOT NULL default '',
  expiration_time int(11) unsigned NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (session_key),
  KEY expiration_time (expiration_time)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezsession'
--

INSERT INTO ezsession (session_key, expiration_time, data) VALUES ('1822a8c48c3c26c2c12883826a72a9ab',1031488029,'eZExecutionStack|a:0:{}BrowseFromPage|s:18:\"/section/assign/3/\";BrowseActionName|s:13:\"AssignSection\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;eZUserLoggedInID|s:1:\"8\";');

--
-- Table structure for table 'ezuser'
--

CREATE TABLE ezuser (
  contentobject_id int(11) NOT NULL default '0',
  login varchar(150) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  password_hash_type int(11) NOT NULL default '1',
  password_hash varchar(50) default NULL
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser'
--

INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (1,'anonymous','anon@anon.com',1,'');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (8,'sp','sp@sp',3,'077194387c925d3dc9e6e6777ad685e4');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (11,'floyd','floyd@floyd',3,'d5f746e0f6d7a8e60cc9dda3ec17c494');

--
-- Table structure for table 'ezuser_role'
--

CREATE TABLE ezuser_role (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezuser_role'
--

INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (14,4,8);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (13,2,8);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (11,1,0);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (12,1,4);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (8,3,8);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (10,3,10);

--
-- Table structure for table 'ezwishlist'
--

CREATE TABLE ezwishlist (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezwishlist'
--

INSERT INTO ezwishlist (id, user_id, productcollection_id) VALUES (1,8,1);

--
-- Table structure for table 'ezworkflow'
--

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow'
--

INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES (1,0,1,'group_ezserial','Publish',-1,-1,1024392098,1024392098);
INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES (2,0,1,'group_ezserial','Editor approval',-1,-1,1024392098,1024392098);
INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES (3,0,1,'group_ezserial','Advanced approval',-1,-1,1024392098,1024392098);

--
-- Table structure for table 'ezworkflow_assign'
--

CREATE TABLE ezworkflow_assign (
  id int(11) NOT NULL auto_increment,
  workflow_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  access_type int(11) NOT NULL default '0',
  as_tree int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_assign'
--


--
-- Table structure for table 'ezworkflow_event'
--

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
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_event'
--

INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (1,0,1,'event_ezpublish','Publish object',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (2,0,2,'event_ezapprove','Approve by editor',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (3,0,2,'event_ezmessage','Send message to editor',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (4,0,3,'event_ezmessage','Send first message',NULL,NULL,NULL,NULL,'First test message from event',NULL,NULL,NULL,1);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (5,0,3,'event_ezapprove','Approve by editor',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (6,0,3,'event_ezpublish','Unpublish',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (7,0,3,'event_ezmessage','Send second message',NULL,NULL,NULL,NULL,'Some text',NULL,NULL,NULL,4);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (8,0,3,'event_ezpublish','Publish',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5);

--
-- Table structure for table 'ezworkflow_group'
--

CREATE TABLE ezworkflow_group (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_group'
--

INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Standard',-1,-1,1024392098,1024392098);
INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (2,'Custom',-1,-1,1024392098,1024392098);

--
-- Table structure for table 'ezworkflow_group_link'
--

CREATE TABLE ezworkflow_group_link (
  workflow_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (workflow_id,group_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_group_link'
--

INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES (1,1);
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES (2,1);
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES (3,1);
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES (3,2);

--
-- Table structure for table 'ezworkflow_process'
--

CREATE TABLE ezworkflow_process (
  id int(11) NOT NULL auto_increment,
  workflow_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  content_id int(11) NOT NULL default '0',
  content_version int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  event_id int(11) NOT NULL default '0',
  event_position int(11) NOT NULL default '0',
  last_event_id int(11) NOT NULL default '0',
  last_event_position int(11) NOT NULL default '0',
  last_event_status int(11) NOT NULL default '0',
  event_status int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  activation_date int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'ezworkflow_process'
--


