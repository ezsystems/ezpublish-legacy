# MySQL dump 8.13
#
# Host: localhost    Database: nextgen
#--------------------------------------------------------
# Server version	3.23.36-log

#
# Table structure for table 'ezbinaryfile'
#

CREATE TABLE ezbinaryfile (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;

#
# Dumping data for table 'ezbinaryfile'
#

INSERT INTO ezbinaryfile VALUES (383,1,'laNihy.bin','test2','text/plain');
INSERT INTO ezbinaryfile VALUES (383,2,'XUQdsw.bin','psi-0.8.6-1.src.rpm','application/x-rpm');
INSERT INTO ezbinaryfile VALUES (383,3,'laNihy.bin','test2','text/plain');
INSERT INTO ezbinaryfile VALUES (383,4,'laNihy.bin','test2','text/plain');

#
# Table structure for table 'ezcart'
#

CREATE TABLE ezcart (
  id int(11) NOT NULL auto_increment,
  session_id varchar(255) NOT NULL default '',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcart'
#


#
# Table structure for table 'ezcontentclass'
#

CREATE TABLE ezcontentclass (
  id int(11) NOT NULL auto_increment,
  version int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  identifier varchar(50) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  object_name varchar(255) NOT NULL default '',
  contentobject_name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentclass'
#

INSERT INTO ezcontentclass VALUES (8,0,'Folder','folder',-1,1,1024392098,1030382194,'','<name>');
INSERT INTO ezcontentclass VALUES (28,0,'User','',1,1,1030382542,1030382621,'','<first_name>, <last_name>');
INSERT INTO ezcontentclass VALUES (29,0,'User group','',1,1,1030382628,1030382770,'','<name>');
INSERT INTO ezcontentclass VALUES (30,0,'Article','',1,1,1030383324,1030383418,'','<title>');
INSERT INTO ezcontentclass VALUES (31,0,'Image','',1,1,1030384419,1030384458,'','<name>');

#
# Table structure for table 'ezcontentclass_attribute'
#

CREATE TABLE ezcontentclass_attribute (
  id int(11) NOT NULL auto_increment,
  version int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  identifier varchar(50) NOT NULL default '',
  name varchar(255) NOT NULL default '',
  data_type_string varchar(50) NOT NULL default '',
  is_searchable int(1) NOT NULL default '0',
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

#
# Dumping data for table 'ezcontentclass_attribute'
#

INSERT INTO ezcontentclass_attribute VALUES (2,0,8,'name','Name','ezstring',1,1,155,0,0,0,0,0,0,0,'New folder','','','');
INSERT INTO ezcontentclass_attribute VALUES (3,0,8,'description','Description','ezxmltext',1,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (30,0,28,'first_name','First Name','ezstring',1,1,200,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (31,0,28,'last_name','Last name','ezstring',1,2,200,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (32,0,28,'account_info','Account info','ezuser',1,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (33,0,28,'image','Image','ezimage',1,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (34,0,29,'name','Name','ezstring',1,1,200,0,0,0,0,0,0,0,'New user group','','','');
INSERT INTO ezcontentclass_attribute VALUES (35,0,30,'title','Title','ezstring',1,1,150,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (36,0,30,'sub_title','Sub title','ezstring',1,2,200,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (38,0,30,'author','Author','ezauthor',1,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (39,0,30,'intro','Intro','ezxmltext',1,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (40,0,30,'body','Body','ezxmltext',1,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (41,0,30,'thumbnail_image','Thumbnail image','ezimage',1,6,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (44,0,31,'image','Image','ezimage',0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (42,0,31,'name','Name','ezstring',1,1,200,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (43,0,31,'caption','Caption','ezstring',1,2,200,0,0,0,0,0,0,0,'','','','');

#
# Table structure for table 'ezcontentclass_classgroup'
#

CREATE TABLE ezcontentclass_classgroup (
  contentclass_id int(11) NOT NULL default '0',
  contentclass_version int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  group_name varchar(255) default NULL,
  PRIMARY KEY  (contentclass_id,contentclass_version,group_id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentclass_classgroup'
#

INSERT INTO ezcontentclass_classgroup VALUES (8,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (9,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (9,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (11,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (12,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (11,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (13,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (13,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (31,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (31,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (30,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (30,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (29,0,6,'User management');
INSERT INTO ezcontentclass_classgroup VALUES (29,1,6,'User management');
INSERT INTO ezcontentclass_classgroup VALUES (28,0,6,'User management');
INSERT INTO ezcontentclass_classgroup VALUES (28,1,6,'User management');
INSERT INTO ezcontentclass_classgroup VALUES (26,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (26,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (27,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (27,0,1,'Content');

#
# Table structure for table 'ezcontentclassgroup'
#

CREATE TABLE ezcontentclassgroup (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentclassgroup'
#

INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,1,1029767060,1029767275);
INSERT INTO ezcontentclassgroup VALUES (6,'User management',1,1,1030382530,1030382537);

#
# Table structure for table 'ezcontentobject'
#

CREATE TABLE ezcontentobject (
  id int(11) NOT NULL auto_increment,
  parent_id int(11) NOT NULL default '0',
  main_node_id int(11) NOT NULL default '0',
  contentclass_id int(11) NOT NULL default '0',
  name varchar(255) default NULL,
  current_version int(11) default NULL,
  is_published int(11) default NULL,
  permission_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject'
#

INSERT INTO ezcontentobject VALUES (90,1,90,8,'Articles',1,0,1);
INSERT INTO ezcontentobject VALUES (4,0,91,29,'Top level user group',2,0,1);
INSERT INTO ezcontentobject VALUES (92,1,92,28,'Bård, Farstad',1,0,1);
INSERT INTO ezcontentobject VALUES (93,1,93,8,'News',1,0,1);
INSERT INTO ezcontentobject VALUES (94,1,94,30,'eZ publish',1,0,1);
INSERT INTO ezcontentobject VALUES (95,1,95,8,'Images',1,0,1);
INSERT INTO ezcontentobject VALUES (96,1,96,31,'Bike',1,0,1);
INSERT INTO ezcontentobject VALUES (1,0,0,8,'Front page',2,0,1);

#
# Table structure for table 'ezcontentobject_attribute'
#

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

#
# Dumping data for table 'ezcontentobject_attribute'
#

INSERT INTO ezcontentobject_attribute VALUES (408,'en_GB',1,96,43,'A biker..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (409,'en_GB',1,96,44,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (404,'en_GB',1,94,41,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (403,'en_GB',1,94,40,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish.</paragraph><paragraph>This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish. This is the body. All about eZ publish.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (402,'en_GB',1,94,39,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish. This is the intro. All about eZ publish.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (401,'en_GB',1,94,38,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"Default\"  email=\"\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (400,'en_GB',1,94,36,'All about the new eZ publish !',0,0);
INSERT INTO ezcontentobject_attribute VALUES (399,'en_GB',1,94,35,'eZ publish',0,0);
INSERT INTO ezcontentobject_attribute VALUES (398,'en_GB',1,93,3,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Here is the latest news</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (397,'en_GB',1,93,2,'News',0,0);
INSERT INTO ezcontentobject_attribute VALUES (389,'en_GB',2,1,3,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>This is the top level for this site.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (388,'en_GB',2,1,2,'Front page',0,0);
INSERT INTO ezcontentobject_attribute VALUES (396,'en_GB',1,92,33,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (395,'en_GB',1,92,32,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (394,'en_GB',1,92,31,'Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (393,'en_GB',1,92,30,'Bård',0,0);
INSERT INTO ezcontentobject_attribute VALUES (392,'en_GB',2,4,34,'Top level user group',0,0);
INSERT INTO ezcontentobject_attribute VALUES (392,'en_GB',1,4,34,'Top level user group',0,0);
INSERT INTO ezcontentobject_attribute VALUES (391,'en_GB',1,90,3,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Here you can place articles.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (390,'en_GB',1,90,2,'Articles',0,0);
INSERT INTO ezcontentobject_attribute VALUES (388,'en_GB',1,1,2,'New folder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (389,'en_GB',1,1,3,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>This is my new folder</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (407,'en_GB',1,96,42,'Bike',0,0);
INSERT INTO ezcontentobject_attribute VALUES (406,'en_GB',1,95,3,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Here you should put all your images...</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (405,'en_GB',1,95,2,'Images',0,0);

#
# Table structure for table 'ezcontentobject_link'
#

CREATE TABLE ezcontentobject_link (
  id int(11) NOT NULL auto_increment,
  from_contentobject_id int(11) NOT NULL default '0',
  from_contentobject_version int(11) NOT NULL default '0',
  to_contentobject_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject_link'
#


#
# Table structure for table 'ezcontentobject_perm_set'
#

CREATE TABLE ezcontentobject_perm_set (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject_perm_set'
#

INSERT INTO ezcontentobject_perm_set VALUES (1,'test set');
INSERT INTO ezcontentobject_perm_set VALUES (2,'test set 2');

#
# Table structure for table 'ezcontentobject_permission'
#

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

#
# Dumping data for table 'ezcontentobject_permission'
#

INSERT INTO ezcontentobject_permission VALUES (1,1,2,0,1,1,0);
INSERT INTO ezcontentobject_permission VALUES (2,1,3,0,1,1,1);

#
# Table structure for table 'ezcontentobject_tree'
#

CREATE TABLE ezcontentobject_tree (
  node_id int(11) NOT NULL auto_increment,
  parent_node_id int(11) NOT NULL default '0',
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  contentobject_is_published int(11) default NULL,
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

#
# Dumping data for table 'ezcontentobject_tree'
#

INSERT INTO ezcontentobject_tree VALUES (0,0,0,1,1,0,'/0/',NULL,1,16);
UPDATE ezcontentobject_tree set node_id='0';
INSERT INTO ezcontentobject_tree VALUES (1,0,1,1,NULL,1,'/0/1/',NULL,2,7);
INSERT INTO ezcontentobject_tree VALUES (4,0,4,1,NULL,1,'/0/4/',NULL,8,15);
INSERT INTO ezcontentobject_tree VALUES (5,4,5,1,NULL,2,'/0/4/5/',NULL,9,12);
INSERT INTO ezcontentobject_tree VALUES (6,4,6,1,NULL,2,'/0/4/6/',NULL,13,14);
INSERT INTO ezcontentobject_tree VALUES (7,5,7,1,NULL,3,'/0/4/5/7/',NULL,10,11);
INSERT INTO ezcontentobject_tree VALUES (64,1,64,NULL,NULL,2,'/0/1/64/',NULL,7,8);
INSERT INTO ezcontentobject_tree VALUES (96,95,96,NULL,NULL,3,'/0/1/95/96/',NULL,8,9);
INSERT INTO ezcontentobject_tree VALUES (95,1,95,NULL,NULL,2,'/0/1/95/',NULL,7,8);
INSERT INTO ezcontentobject_tree VALUES (89,1,89,NULL,NULL,2,'/0/1/89/',NULL,7,8);
INSERT INTO ezcontentobject_tree VALUES (90,1,90,NULL,NULL,2,'/0/1/90/',NULL,7,8);
INSERT INTO ezcontentobject_tree VALUES (91,1,91,NULL,NULL,2,'/0/1/91/',NULL,7,8);
INSERT INTO ezcontentobject_tree VALUES (92,4,92,NULL,NULL,2,'/0/4/92/',NULL,15,16);
INSERT INTO ezcontentobject_tree VALUES (93,1,93,NULL,NULL,2,'/0/1/93/',NULL,7,8);
INSERT INTO ezcontentobject_tree VALUES (94,90,94,NULL,NULL,3,'/0/1/90/94/',NULL,8,9);
INSERT INTO ezcontentobject_tree VALUES (81,4,81,NULL,NULL,2,'/0/4/81/',NULL,15,16);

#
# Table structure for table 'ezcontentobject_version'
#

CREATE TABLE ezcontentobject_version (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) default NULL,
  version int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  status int(11) NOT NULL default '0',
  workflow_event_pos int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject_version'
#

INSERT INTO ezcontentobject_version VALUES (187,96,1,0,1030384518,0,0,0);
INSERT INTO ezcontentobject_version VALUES (186,95,1,0,1030384409,0,0,0);
INSERT INTO ezcontentobject_version VALUES (185,94,1,0,1030383619,0,0,0);
INSERT INTO ezcontentobject_version VALUES (184,93,1,0,1030382959,0,0,0);
INSERT INTO ezcontentobject_version VALUES (183,1,2,1030382900,1030382929,0,0,0);
INSERT INTO ezcontentobject_version VALUES (182,92,1,0,1030382885,0,0,0);
INSERT INTO ezcontentobject_version VALUES (181,4,2,1030382778,1030382782,0,0,0);
INSERT INTO ezcontentobject_version VALUES (180,4,1,0,1030382750,0,0,0);
INSERT INTO ezcontentobject_version VALUES (179,90,1,0,1030382489,0,0,0);
INSERT INTO ezcontentobject_version VALUES (178,1,1,0,1030382265,0,0,0);

#
# Table structure for table 'ezenumobjectvalue'
#

CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumid int(11) NOT NULL default '0',
  enumelement varchar(50) NOT NULL default '',
  enumvalue varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid)
) TYPE=MyISAM;

#
# Dumping data for table 'ezenumobjectvalue'
#

INSERT INTO ezenumobjectvalue VALUES (328,1,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (328,3,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (328,4,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (328,5,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (328,6,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (328,7,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,3,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,1,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,1,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,2,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,3,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,1,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,4,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,4,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,5,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (359,0,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (359,0,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,5,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,6,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,6,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,7,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,7,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,7,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,8,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,8,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,9,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,9,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,10,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,10,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,11,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,11,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,12,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,12,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,12,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,13,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (343,13,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,14,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,14,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,15,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (343,15,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,13,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (343,14,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (371,1,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (371,1,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (371,2,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (371,2,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (371,2,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (381,1,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (371,3,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (381,1,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (381,2,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (381,2,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (381,3,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (381,3,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (381,3,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (381,4,3,'red','1');
INSERT INTO ezenumobjectvalue VALUES (381,4,4,'black','2');
INSERT INTO ezenumobjectvalue VALUES (381,4,5,'pink','3');
INSERT INTO ezenumobjectvalue VALUES (2,2,1,'Good','1');
INSERT INTO ezenumobjectvalue VALUES (2,3,1,'Good','1');

#
# Table structure for table 'ezenumvalue'
#

CREATE TABLE ezenumvalue (
  id int(11) NOT NULL auto_increment,
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(50) NOT NULL default '',
  enumvalue varchar(50) NOT NULL default '',
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version)
) TYPE=MyISAM;

#
# Dumping data for table 'ezenumvalue'
#

INSERT INTO ezenumvalue VALUES (1,5,1,'Good','1',1);
INSERT INTO ezenumvalue VALUES (2,5,1,'Poor','2',2);
INSERT INTO ezenumvalue VALUES (2,5,0,'Poor','2',2);
INSERT INTO ezenumvalue VALUES (1,5,0,'Good','1',1);
INSERT INTO ezenumvalue VALUES (3,22,1,'red','1',1);
INSERT INTO ezenumvalue VALUES (4,22,1,'black','2',2);
INSERT INTO ezenumvalue VALUES (5,22,1,'pink','3',3);
INSERT INTO ezenumvalue VALUES (5,22,0,'pink','3',3);
INSERT INTO ezenumvalue VALUES (4,22,0,'black','2',2);
INSERT INTO ezenumvalue VALUES (3,22,0,'red','1',1);

#
# Table structure for table 'ezimage'
#

CREATE TABLE ezimage (
  contentobject_attribute_id int(11) NOT NULL default '0',
  version int(11) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  original_filename varchar(255) NOT NULL default '',
  mime_type varchar(50) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;

#
# Dumping data for table 'ezimage'
#

INSERT INTO ezimage VALUES (370,1,'EOnjMO.jpg','DSC00007.JPG','image/jpeg');
INSERT INTO ezimage VALUES (370,2,'EOnjMO.jpg','DSC00007.JPG','image/jpeg');
INSERT INTO ezimage VALUES (370,3,'EOnjMO.jpg','DSC00007.JPG','image/jpeg');
INSERT INTO ezimage VALUES (380,1,'aGbnA6.jpg','DSC00007.JPG','image/jpeg');
INSERT INTO ezimage VALUES (380,2,'aGbnA6.jpg','DSC00007.JPG','image/jpeg');
INSERT INTO ezimage VALUES (380,3,'aGbnA6.jpg','DSC00007.JPG','image/jpeg');
INSERT INTO ezimage VALUES (380,4,'aGbnA6.jpg','DSC00007.JPG','image/jpeg');
INSERT INTO ezimage VALUES (387,1,'b8dirE.jpg','bike.jpg','image/jpeg');
INSERT INTO ezimage VALUES (387,2,'b8dirE.jpg','bike.jpg','image/jpeg');
INSERT INTO ezimage VALUES (387,3,'TG4W63.gif','avkledd_kort155x219.gif','image/gif');
INSERT INTO ezimage VALUES (387,4,'TG4W63.gif','avkledd_kort155x219.gif','image/gif');
INSERT INTO ezimage VALUES (396,1,'mAa0CP.gif','avkledd_kort155x219.gif','image/gif');
INSERT INTO ezimage VALUES (404,1,'PEEsJs.png','snapshot5.png','image/png');
INSERT INTO ezimage VALUES (409,1,'00FFf5.jpg','bike.jpg','image/jpeg');

#
# Table structure for table 'ezimagevariation'
#

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

#
# Dumping data for table 'ezimagevariation'
#

INSERT INTO ezimagevariation VALUES (370,1,'EOnjMO_600x600_370.jpg','E/O/n/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (370,2,'EOnjMO_100x100_370.jpg','E/O/n/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (370,2,'EOnjMO_600x600_370.jpg','E/O/n/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (370,3,'EOnjMO_100x100_370.jpg','E/O/n/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (370,3,'EOnjMO_600x600_370.jpg','E/O/n/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (380,1,'1Gh531_600x600_380.jpg','1/G/h/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (380,2,'aGbnA6_100x100_380.jpg','a/G/b/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (380,2,'aGbnA6_600x600_380.jpg','a/G/b/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (380,3,'aGbnA6_100x100_380.jpg','a/G/b/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (380,3,'aGbnA6_600x600_380.jpg','a/G/b/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (380,4,'aGbnA6_100x100_380.jpg','a/G/b/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (387,1,'b8dirE_100x100_387.jpg','b/8/d/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (387,1,'b8dirE_600x600_387.jpg','b/8/d/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (387,2,'b8dirE_100x100_387.jpg','b/8/d/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (387,2,'TG4W63_600x600_387.gif','T/G/4/',600,600,155,219);
INSERT INTO ezimagevariation VALUES (387,3,'TG4W63_100x100_387.gif','T/G/4/',100,100,70,100);
INSERT INTO ezimagevariation VALUES (387,3,'UhCwgg_600x600_387.gif','U/h/C/',600,600,0,0);
INSERT INTO ezimagevariation VALUES (387,4,'UhCwgg_100x100_387.gif','U/h/C/',100,100,0,0);
INSERT INTO ezimagevariation VALUES (396,1,'mAa0CP_100x100_396.gif','m/A/a/',100,100,70,100);
INSERT INTO ezimagevariation VALUES (396,1,'mAa0CP_600x600_396.gif','m/A/a/',600,600,155,219);
INSERT INTO ezimagevariation VALUES (404,1,'PEEsJs_100x100_404.png','P/E/E/',100,100,80,100);
INSERT INTO ezimagevariation VALUES (404,1,'PEEsJs_600x600_404.png','P/E/E/',600,600,240,300);
INSERT INTO ezimagevariation VALUES (409,1,'00FFf5_600x600_409.jpg','0/0/F/',600,600,400,300);

#
# Table structure for table 'ezorder'
#

CREATE TABLE ezorder (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezorder'
#


#
# Table structure for table 'ezpolicy'
#

CREATE TABLE ezpolicy (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezpolicy'
#

INSERT INTO ezpolicy VALUES (3,2,'*','*','*');
INSERT INTO ezpolicy VALUES (4,2,'*','class','*');
INSERT INTO ezpolicy VALUES (5,2,'*','content','*');
INSERT INTO ezpolicy VALUES (6,2,'*','search','*');
INSERT INTO ezpolicy VALUES (7,3,'list','class','*');
INSERT INTO ezpolicy VALUES (8,3,'edit','class','');
INSERT INTO ezpolicy VALUES (9,3,'sitemap','content','*');
INSERT INTO ezpolicy VALUES (10,3,'delete','content','*');
INSERT INTO ezpolicy VALUES (11,3,'edit','content','*');
INSERT INTO ezpolicy VALUES (12,3,'view','content','*');
INSERT INTO ezpolicy VALUES (13,3,'*','search','*');
INSERT INTO ezpolicy VALUES (45,4,'*','content','*');
INSERT INTO ezpolicy VALUES (44,4,'search','search','*');
INSERT INTO ezpolicy VALUES (42,4,'sitemap','content','*');
INSERT INTO ezpolicy VALUES (40,1,'sitemap','content','');
INSERT INTO ezpolicy VALUES (50,16,'*','content','*');
INSERT INTO ezpolicy VALUES (49,16,'search','search','*');
INSERT INTO ezpolicy VALUES (48,16,'edit','class','*');
INSERT INTO ezpolicy VALUES (47,16,'sitemap','content','*');
INSERT INTO ezpolicy VALUES (41,1,'search','search','');
INSERT INTO ezpolicy VALUES (43,4,'edit','class','*');
INSERT INTO ezpolicy VALUES (46,4,'*','search','*');
INSERT INTO ezpolicy VALUES (51,16,'*','search','*');
INSERT INTO ezpolicy VALUES (52,17,'sitemap','content','');
INSERT INTO ezpolicy VALUES (53,17,'search','search','');

#
# Table structure for table 'ezpolicy_limitation'
#

CREATE TABLE ezpolicy_limitation (
  id int(11) NOT NULL auto_increment,
  policy_id int(11) default NULL,
  identifier varchar(255) NOT NULL default '',
  role_id int(11) default NULL,
  function_name varchar(255) default NULL,
  module_name varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezpolicy_limitation'
#

INSERT INTO ezpolicy_limitation VALUES (45,53,'ObjectID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (44,53,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (43,52,'ObjectID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (42,52,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (5,8,'ClassID',3,'edit','class');
INSERT INTO ezpolicy_limitation VALUES (38,40,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (39,40,'ObjectID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (40,41,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (41,41,'ObjectID',0,'','');

#
# Table structure for table 'ezpolicy_limitation_value'
#

CREATE TABLE ezpolicy_limitation_value (
  id int(11) NOT NULL auto_increment,
  limitation_id int(11) default NULL,
  value int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezpolicy_limitation_value'
#

INSERT INTO ezpolicy_limitation_value VALUES (132,45,45);
INSERT INTO ezpolicy_limitation_value VALUES (131,45,17);
INSERT INTO ezpolicy_limitation_value VALUES (130,45,3);
INSERT INTO ezpolicy_limitation_value VALUES (129,45,1);
INSERT INTO ezpolicy_limitation_value VALUES (133,45,46);
INSERT INTO ezpolicy_limitation_value VALUES (128,44,2);
INSERT INTO ezpolicy_limitation_value VALUES (126,43,45);
INSERT INTO ezpolicy_limitation_value VALUES (125,43,17);
INSERT INTO ezpolicy_limitation_value VALUES (124,43,3);
INSERT INTO ezpolicy_limitation_value VALUES (123,43,1);
INSERT INTO ezpolicy_limitation_value VALUES (127,43,46);
INSERT INTO ezpolicy_limitation_value VALUES (122,42,2);
INSERT INTO ezpolicy_limitation_value VALUES (13,5,2);
INSERT INTO ezpolicy_limitation_value VALUES (114,39,45);
INSERT INTO ezpolicy_limitation_value VALUES (113,39,17);
INSERT INTO ezpolicy_limitation_value VALUES (112,39,3);
INSERT INTO ezpolicy_limitation_value VALUES (111,39,1);
INSERT INTO ezpolicy_limitation_value VALUES (110,38,2);
INSERT INTO ezpolicy_limitation_value VALUES (115,39,46);
INSERT INTO ezpolicy_limitation_value VALUES (120,41,45);
INSERT INTO ezpolicy_limitation_value VALUES (119,41,17);
INSERT INTO ezpolicy_limitation_value VALUES (118,41,3);
INSERT INTO ezpolicy_limitation_value VALUES (117,41,1);
INSERT INTO ezpolicy_limitation_value VALUES (116,40,2);
INSERT INTO ezpolicy_limitation_value VALUES (121,41,46);

#
# Table structure for table 'ezproductcollection'
#

CREATE TABLE ezproductcollection (
  id int(11) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezproductcollection'
#

INSERT INTO ezproductcollection VALUES (1);
INSERT INTO ezproductcollection VALUES (2);

#
# Table structure for table 'ezproductcollection_item'
#

CREATE TABLE ezproductcollection_item (
  id int(11) NOT NULL auto_increment,
  productcollection_id int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  item_count int(11) NOT NULL default '0',
  price_is_inc_vat int(11) NOT NULL default '0',
  price int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezproductcollection_item'
#


#
# Table structure for table 'ezrole'
#

CREATE TABLE ezrole (
  id int(11) NOT NULL auto_increment,
  version int(11) default '0',
  name varchar(255) NOT NULL default '',
  value char(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezrole'
#

INSERT INTO ezrole VALUES (1,0,'Anonimous','');
INSERT INTO ezrole VALUES (2,0,'Admin','*');
INSERT INTO ezrole VALUES (3,0,'editor','');
INSERT INTO ezrole VALUES (4,0,'advanced editor1','');
INSERT INTO ezrole VALUES (17,1,'Anonimous',NULL);
INSERT INTO ezrole VALUES (16,4,'advanced editor1',NULL);

#
# Table structure for table 'ezsearch_object_word_link'
#

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

#
# Dumping data for table 'ezsearch_object_word_link'
#

INSERT INTO ezsearch_object_word_link VALUES (16,9,14,0,7,13,0,7,15);
INSERT INTO ezsearch_object_word_link VALUES (15,9,13,0,6,12,14,7,13);
INSERT INTO ezsearch_object_word_link VALUES (14,9,12,0,5,8,13,7,13);
INSERT INTO ezsearch_object_word_link VALUES (13,9,8,0,4,11,12,7,13);
INSERT INTO ezsearch_object_word_link VALUES (12,9,11,0,3,10,8,7,13);
INSERT INTO ezsearch_object_word_link VALUES (11,9,10,0,2,9,11,7,13);
INSERT INTO ezsearch_object_word_link VALUES (10,9,9,0,1,8,10,7,13);
INSERT INTO ezsearch_object_word_link VALUES (9,9,8,0,0,0,9,7,13);
INSERT INTO ezsearch_object_word_link VALUES (17,13,15,0,0,0,16,8,2);
INSERT INTO ezsearch_object_word_link VALUES (18,13,16,0,1,15,17,8,2);
INSERT INTO ezsearch_object_word_link VALUES (19,13,17,0,2,16,18,8,2);
INSERT INTO ezsearch_object_word_link VALUES (20,13,18,0,3,17,19,8,2);
INSERT INTO ezsearch_object_word_link VALUES (21,13,19,0,4,18,20,8,2);
INSERT INTO ezsearch_object_word_link VALUES (22,13,20,0,5,19,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (23,13,21,0,6,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (24,13,22,0,7,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (25,13,23,0,8,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (26,13,20,0,9,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (27,13,21,0,10,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (28,13,22,0,11,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (29,13,23,0,12,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (30,13,20,0,13,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (31,13,21,0,14,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (32,13,22,0,15,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (33,13,23,0,16,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (34,13,20,0,17,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (35,13,21,0,18,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (36,13,22,0,19,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (37,13,23,0,20,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (38,13,20,0,21,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (39,13,21,0,22,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (40,13,22,0,23,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (41,13,23,0,24,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (42,13,20,0,25,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (43,13,21,0,26,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (44,13,22,0,27,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (45,13,23,0,28,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (46,13,20,0,29,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (47,13,21,0,30,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (48,13,22,0,31,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (49,13,23,0,32,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (50,13,20,0,33,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (51,13,21,0,34,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (52,13,22,0,35,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (53,13,23,0,36,22,20,8,3);
INSERT INTO ezsearch_object_word_link VALUES (54,13,20,0,37,23,21,8,3);
INSERT INTO ezsearch_object_word_link VALUES (55,13,21,0,38,20,22,8,3);
INSERT INTO ezsearch_object_word_link VALUES (56,13,22,0,39,21,23,8,3);
INSERT INTO ezsearch_object_word_link VALUES (57,13,23,0,40,22,0,8,3);
INSERT INTO ezsearch_object_word_link VALUES (58,14,17,0,0,0,24,9,4);
INSERT INTO ezsearch_object_word_link VALUES (59,14,24,0,1,17,0,9,4);
INSERT INTO ezsearch_object_word_link VALUES (60,29,25,0,0,0,26,13,14);
INSERT INTO ezsearch_object_word_link VALUES (61,29,26,0,1,25,27,13,14);
INSERT INTO ezsearch_object_word_link VALUES (62,29,27,0,2,26,28,13,14);
INSERT INTO ezsearch_object_word_link VALUES (63,29,28,0,3,27,29,13,16);
INSERT INTO ezsearch_object_word_link VALUES (64,29,29,0,4,28,30,13,16);
INSERT INTO ezsearch_object_word_link VALUES (65,29,30,0,5,29,31,13,16);
INSERT INTO ezsearch_object_word_link VALUES (66,29,31,0,6,30,29,13,16);
INSERT INTO ezsearch_object_word_link VALUES (67,29,29,0,7,31,30,13,16);
INSERT INTO ezsearch_object_word_link VALUES (68,29,30,0,8,29,32,13,16);
INSERT INTO ezsearch_object_word_link VALUES (69,29,32,0,9,30,29,13,16);
INSERT INTO ezsearch_object_word_link VALUES (70,29,29,0,10,32,30,13,16);
INSERT INTO ezsearch_object_word_link VALUES (71,29,30,0,11,29,32,13,16);
INSERT INTO ezsearch_object_word_link VALUES (72,29,32,0,12,30,32,13,16);
INSERT INTO ezsearch_object_word_link VALUES (73,29,32,0,13,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (74,29,32,0,14,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (75,29,32,0,15,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (76,29,32,0,16,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (77,29,32,0,17,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (78,29,32,0,18,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (79,29,32,0,19,32,33,13,17);
INSERT INTO ezsearch_object_word_link VALUES (80,29,33,0,20,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (81,29,32,0,21,33,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (82,29,32,0,22,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (83,29,32,0,23,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (84,29,32,0,24,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (85,29,32,0,25,32,32,13,17);
INSERT INTO ezsearch_object_word_link VALUES (86,29,32,0,26,32,0,13,17);
INSERT INTO ezsearch_object_word_link VALUES (135,25,14,0,5,64,0,11,13);
INSERT INTO ezsearch_object_word_link VALUES (134,25,64,0,4,63,14,11,13);
INSERT INTO ezsearch_object_word_link VALUES (133,25,63,0,3,61,64,11,12);
INSERT INTO ezsearch_object_word_link VALUES (132,25,61,0,2,62,63,11,12);
INSERT INTO ezsearch_object_word_link VALUES (131,25,62,0,1,61,61,11,10);
INSERT INTO ezsearch_object_word_link VALUES (130,25,61,0,0,0,62,11,10);
INSERT INTO ezsearch_object_word_link VALUES (105,80,46,0,0,0,47,7,19);
INSERT INTO ezsearch_object_word_link VALUES (106,80,47,0,1,46,48,7,19);
INSERT INTO ezsearch_object_word_link VALUES (107,80,48,0,2,47,0,7,23);
INSERT INTO ezsearch_object_word_link VALUES (119,79,54,0,3,53,0,7,23);
INSERT INTO ezsearch_object_word_link VALUES (118,79,53,0,2,46,54,7,19);
INSERT INTO ezsearch_object_word_link VALUES (117,79,46,0,1,17,53,7,19);
INSERT INTO ezsearch_object_word_link VALUES (116,79,17,0,0,0,46,7,19);
INSERT INTO ezsearch_object_word_link VALUES (120,85,46,0,0,0,47,7,19);
INSERT INTO ezsearch_object_word_link VALUES (121,85,47,0,1,46,55,7,19);
INSERT INTO ezsearch_object_word_link VALUES (122,85,55,0,2,47,56,7,19);
INSERT INTO ezsearch_object_word_link VALUES (123,85,56,0,3,55,0,7,23);
INSERT INTO ezsearch_object_word_link VALUES (145,88,69,0,1,17,0,27,28);
INSERT INTO ezsearch_object_word_link VALUES (144,88,17,0,0,0,69,27,28);
INSERT INTO ezsearch_object_word_link VALUES (196,1,100,0,9,15,0,8,3);
INSERT INTO ezsearch_object_word_link VALUES (195,1,15,0,8,99,100,8,3);
INSERT INTO ezsearch_object_word_link VALUES (194,1,99,0,7,86,15,8,3);
INSERT INTO ezsearch_object_word_link VALUES (193,1,86,0,6,85,99,8,3);
INSERT INTO ezsearch_object_word_link VALUES (192,1,85,0,5,98,86,8,3);
INSERT INTO ezsearch_object_word_link VALUES (191,1,98,0,4,16,85,8,3);
INSERT INTO ezsearch_object_word_link VALUES (190,1,16,0,3,15,98,8,3);
INSERT INTO ezsearch_object_word_link VALUES (189,1,15,0,2,97,16,8,3);
INSERT INTO ezsearch_object_word_link VALUES (154,89,75,0,0,0,19,8,2);
INSERT INTO ezsearch_object_word_link VALUES (155,89,19,0,1,75,15,8,2);
INSERT INTO ezsearch_object_word_link VALUES (156,89,15,0,2,19,16,8,3);
INSERT INTO ezsearch_object_word_link VALUES (157,89,16,0,3,15,17,8,3);
INSERT INTO ezsearch_object_word_link VALUES (158,89,17,0,4,16,75,8,3);
INSERT INTO ezsearch_object_word_link VALUES (159,89,75,0,5,17,19,8,3);
INSERT INTO ezsearch_object_word_link VALUES (160,89,19,0,6,75,0,8,3);
INSERT INTO ezsearch_object_word_link VALUES (161,90,76,0,0,0,77,8,2);
INSERT INTO ezsearch_object_word_link VALUES (162,90,77,0,1,76,78,8,3);
INSERT INTO ezsearch_object_word_link VALUES (163,90,78,0,2,77,79,8,3);
INSERT INTO ezsearch_object_word_link VALUES (164,90,79,0,3,78,80,8,3);
INSERT INTO ezsearch_object_word_link VALUES (165,90,80,0,4,79,76,8,3);
INSERT INTO ezsearch_object_word_link VALUES (166,90,76,0,5,80,0,8,3);
INSERT INTO ezsearch_object_word_link VALUES (174,91,88,0,3,87,0,29,34);
INSERT INTO ezsearch_object_word_link VALUES (173,91,87,0,2,86,88,29,34);
INSERT INTO ezsearch_object_word_link VALUES (172,91,86,0,1,85,87,29,34);
INSERT INTO ezsearch_object_word_link VALUES (171,91,85,0,0,0,86,29,34);
INSERT INTO ezsearch_object_word_link VALUES (175,92,89,0,0,0,90,28,30);
INSERT INTO ezsearch_object_word_link VALUES (176,92,90,0,1,89,0,28,31);
INSERT INTO ezsearch_object_word_link VALUES (188,1,97,0,1,96,15,8,2);
INSERT INTO ezsearch_object_word_link VALUES (187,1,96,0,0,0,97,8,2);
INSERT INTO ezsearch_object_word_link VALUES (197,93,101,0,0,0,77,8,2);
INSERT INTO ezsearch_object_word_link VALUES (198,93,77,0,1,101,16,8,3);
INSERT INTO ezsearch_object_word_link VALUES (199,93,16,0,2,77,98,8,3);
INSERT INTO ezsearch_object_word_link VALUES (200,93,98,0,3,16,102,8,3);
INSERT INTO ezsearch_object_word_link VALUES (201,93,102,0,4,98,101,8,3);
INSERT INTO ezsearch_object_word_link VALUES (202,93,101,0,5,102,0,8,3);
INSERT INTO ezsearch_object_word_link VALUES (203,94,103,0,0,0,104,30,35);
INSERT INTO ezsearch_object_word_link VALUES (204,94,104,0,1,103,105,30,35);
INSERT INTO ezsearch_object_word_link VALUES (205,94,105,0,2,104,106,30,36);
INSERT INTO ezsearch_object_word_link VALUES (206,94,106,0,3,105,98,30,36);
INSERT INTO ezsearch_object_word_link VALUES (207,94,98,0,4,106,75,30,36);
INSERT INTO ezsearch_object_word_link VALUES (208,94,75,0,5,98,103,30,36);
INSERT INTO ezsearch_object_word_link VALUES (209,94,103,0,6,75,104,30,36);
INSERT INTO ezsearch_object_word_link VALUES (210,94,104,0,7,103,107,30,36);
INSERT INTO ezsearch_object_word_link VALUES (211,94,107,0,8,104,15,30,36);
INSERT INTO ezsearch_object_word_link VALUES (212,94,15,0,9,107,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (213,94,16,0,10,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (214,94,98,0,11,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (215,94,108,0,12,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (216,94,105,0,13,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (217,94,106,0,14,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (218,94,103,0,15,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (219,94,104,0,16,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (220,94,15,0,17,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (221,94,16,0,18,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (222,94,98,0,19,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (223,94,108,0,20,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (224,94,105,0,21,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (225,94,106,0,22,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (226,94,103,0,23,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (227,94,104,0,24,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (228,94,15,0,25,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (229,94,16,0,26,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (230,94,98,0,27,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (231,94,108,0,28,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (232,94,105,0,29,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (233,94,106,0,30,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (234,94,103,0,31,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (235,94,104,0,32,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (236,94,15,0,33,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (237,94,16,0,34,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (238,94,98,0,35,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (239,94,108,0,36,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (240,94,105,0,37,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (241,94,106,0,38,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (242,94,103,0,39,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (243,94,104,0,40,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (244,94,15,0,41,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (245,94,16,0,42,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (246,94,98,0,43,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (247,94,108,0,44,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (248,94,105,0,45,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (249,94,106,0,46,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (250,94,103,0,47,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (251,94,104,0,48,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (252,94,15,0,49,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (253,94,16,0,50,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (254,94,98,0,51,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (255,94,108,0,52,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (256,94,105,0,53,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (257,94,106,0,54,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (258,94,103,0,55,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (259,94,104,0,56,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (260,94,15,0,57,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (261,94,16,0,58,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (262,94,98,0,59,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (263,94,108,0,60,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (264,94,105,0,61,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (265,94,106,0,62,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (266,94,103,0,63,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (267,94,104,0,64,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (268,94,15,0,65,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (269,94,16,0,66,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (270,94,98,0,67,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (271,94,108,0,68,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (272,94,105,0,69,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (273,94,106,0,70,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (274,94,103,0,71,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (275,94,104,0,72,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (276,94,15,0,73,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (277,94,16,0,74,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (278,94,98,0,75,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (279,94,108,0,76,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (280,94,105,0,77,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (281,94,106,0,78,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (282,94,103,0,79,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (283,94,104,0,80,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (284,94,15,0,81,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (285,94,16,0,82,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (286,94,98,0,83,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (287,94,108,0,84,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (288,94,105,0,85,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (289,94,106,0,86,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (290,94,103,0,87,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (291,94,104,0,88,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (292,94,15,0,89,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (293,94,16,0,90,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (294,94,98,0,91,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (295,94,108,0,92,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (296,94,105,0,93,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (297,94,106,0,94,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (298,94,103,0,95,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (299,94,104,0,96,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (300,94,15,0,97,104,16,30,39);
INSERT INTO ezsearch_object_word_link VALUES (301,94,16,0,98,15,98,30,39);
INSERT INTO ezsearch_object_word_link VALUES (302,94,98,0,99,16,108,30,39);
INSERT INTO ezsearch_object_word_link VALUES (303,94,108,0,100,98,105,30,39);
INSERT INTO ezsearch_object_word_link VALUES (304,94,105,0,101,108,106,30,39);
INSERT INTO ezsearch_object_word_link VALUES (305,94,106,0,102,105,103,30,39);
INSERT INTO ezsearch_object_word_link VALUES (306,94,103,0,103,106,104,30,39);
INSERT INTO ezsearch_object_word_link VALUES (307,94,104,0,104,103,15,30,39);
INSERT INTO ezsearch_object_word_link VALUES (308,94,15,0,105,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (309,94,16,0,106,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (310,94,98,0,107,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (311,94,109,0,108,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (312,94,105,0,109,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (313,94,106,0,110,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (314,94,103,0,111,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (315,94,104,0,112,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (316,94,15,0,113,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (317,94,16,0,114,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (318,94,98,0,115,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (319,94,109,0,116,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (320,94,105,0,117,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (321,94,106,0,118,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (322,94,103,0,119,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (323,94,104,0,120,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (324,94,15,0,121,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (325,94,16,0,122,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (326,94,98,0,123,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (327,94,109,0,124,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (328,94,105,0,125,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (329,94,106,0,126,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (330,94,103,0,127,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (331,94,104,0,128,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (332,94,15,0,129,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (333,94,16,0,130,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (334,94,98,0,131,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (335,94,109,0,132,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (336,94,105,0,133,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (337,94,106,0,134,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (338,94,103,0,135,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (339,94,104,0,136,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (340,94,15,0,137,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (341,94,16,0,138,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (342,94,98,0,139,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (343,94,109,0,140,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (344,94,105,0,141,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (345,94,106,0,142,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (346,94,103,0,143,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (347,94,104,0,144,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (348,94,15,0,145,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (349,94,16,0,146,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (350,94,98,0,147,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (351,94,109,0,148,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (352,94,105,0,149,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (353,94,106,0,150,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (354,94,103,0,151,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (355,94,104,0,152,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (356,94,15,0,153,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (357,94,16,0,154,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (358,94,98,0,155,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (359,94,109,0,156,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (360,94,105,0,157,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (361,94,106,0,158,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (362,94,103,0,159,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (363,94,104,0,160,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (364,94,15,0,161,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (365,94,16,0,162,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (366,94,98,0,163,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (367,94,109,0,164,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (368,94,105,0,165,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (369,94,106,0,166,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (370,94,103,0,167,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (371,94,104,0,168,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (372,94,15,0,169,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (373,94,16,0,170,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (374,94,98,0,171,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (375,94,109,0,172,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (376,94,105,0,173,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (377,94,106,0,174,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (378,94,103,0,175,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (379,94,104,0,176,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (380,94,15,0,177,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (381,94,16,0,178,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (382,94,98,0,179,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (383,94,109,0,180,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (384,94,105,0,181,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (385,94,106,0,182,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (386,94,103,0,183,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (387,94,104,0,184,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (388,94,15,0,185,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (389,94,16,0,186,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (390,94,98,0,187,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (391,94,109,0,188,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (392,94,105,0,189,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (393,94,106,0,190,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (394,94,103,0,191,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (395,94,104,0,192,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (396,94,15,0,193,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (397,94,16,0,194,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (398,94,98,0,195,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (399,94,109,0,196,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (400,94,105,0,197,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (401,94,106,0,198,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (402,94,103,0,199,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (403,94,104,0,200,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (404,94,15,0,201,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (405,94,16,0,202,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (406,94,98,0,203,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (407,94,109,0,204,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (408,94,105,0,205,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (409,94,106,0,206,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (410,94,103,0,207,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (411,94,104,0,208,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (412,94,15,0,209,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (413,94,16,0,210,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (414,94,98,0,211,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (415,94,109,0,212,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (416,94,105,0,213,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (417,94,106,0,214,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (418,94,103,0,215,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (419,94,104,0,216,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (420,94,15,0,217,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (421,94,16,0,218,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (422,94,98,0,219,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (423,94,109,0,220,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (424,94,105,0,221,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (425,94,106,0,222,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (426,94,103,0,223,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (427,94,104,0,224,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (428,94,15,0,225,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (429,94,16,0,226,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (430,94,98,0,227,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (431,94,109,0,228,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (432,94,105,0,229,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (433,94,106,0,230,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (434,94,103,0,231,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (435,94,104,0,232,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (436,94,15,0,233,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (437,94,16,0,234,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (438,94,98,0,235,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (439,94,109,0,236,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (440,94,105,0,237,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (441,94,106,0,238,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (442,94,103,0,239,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (443,94,104,0,240,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (444,94,15,0,241,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (445,94,16,0,242,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (446,94,98,0,243,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (447,94,109,0,244,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (448,94,105,0,245,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (449,94,106,0,246,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (450,94,103,0,247,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (451,94,104,0,248,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (452,94,15,0,249,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (453,94,16,0,250,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (454,94,98,0,251,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (455,94,109,0,252,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (456,94,105,0,253,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (457,94,106,0,254,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (458,94,103,0,255,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (459,94,104,0,256,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (460,94,15,0,257,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (461,94,16,0,258,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (462,94,98,0,259,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (463,94,109,0,260,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (464,94,105,0,261,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (465,94,106,0,262,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (466,94,103,0,263,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (467,94,104,0,264,103,15,30,40);
INSERT INTO ezsearch_object_word_link VALUES (468,94,15,0,265,104,16,30,40);
INSERT INTO ezsearch_object_word_link VALUES (469,94,16,0,266,15,98,30,40);
INSERT INTO ezsearch_object_word_link VALUES (470,94,98,0,267,16,109,30,40);
INSERT INTO ezsearch_object_word_link VALUES (471,94,109,0,268,98,105,30,40);
INSERT INTO ezsearch_object_word_link VALUES (472,94,105,0,269,109,106,30,40);
INSERT INTO ezsearch_object_word_link VALUES (473,94,106,0,270,105,103,30,40);
INSERT INTO ezsearch_object_word_link VALUES (474,94,103,0,271,106,104,30,40);
INSERT INTO ezsearch_object_word_link VALUES (475,94,104,0,272,103,0,30,40);
INSERT INTO ezsearch_object_word_link VALUES (476,95,110,0,0,0,77,8,2);
INSERT INTO ezsearch_object_word_link VALUES (477,95,77,0,1,110,78,8,3);
INSERT INTO ezsearch_object_word_link VALUES (478,95,78,0,2,77,111,8,3);
INSERT INTO ezsearch_object_word_link VALUES (479,95,111,0,3,78,112,8,3);
INSERT INTO ezsearch_object_word_link VALUES (480,95,112,0,4,111,105,8,3);
INSERT INTO ezsearch_object_word_link VALUES (481,95,105,0,5,112,113,8,3);
INSERT INTO ezsearch_object_word_link VALUES (482,95,113,0,6,105,110,8,3);
INSERT INTO ezsearch_object_word_link VALUES (483,95,110,0,7,113,0,8,3);
INSERT INTO ezsearch_object_word_link VALUES (484,96,114,0,0,0,115,31,42);
INSERT INTO ezsearch_object_word_link VALUES (485,96,115,0,1,114,116,31,43);
INSERT INTO ezsearch_object_word_link VALUES (486,96,116,0,2,115,0,31,43);

#
# Table structure for table 'ezsearch_return_count'
#

CREATE TABLE ezsearch_return_count (
  id int(11) NOT NULL auto_increment,
  phrase_id int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezsearch_return_count'
#

INSERT INTO ezsearch_return_count VALUES (1,1,1029767538,1);
INSERT INTO ezsearch_return_count VALUES (2,2,1029767967,0);
INSERT INTO ezsearch_return_count VALUES (3,3,1029768046,2);
INSERT INTO ezsearch_return_count VALUES (4,4,1029768619,0);
INSERT INTO ezsearch_return_count VALUES (5,5,1029929767,1);
INSERT INTO ezsearch_return_count VALUES (6,6,1029929854,1);
INSERT INTO ezsearch_return_count VALUES (7,7,1030371766,1);
INSERT INTO ezsearch_return_count VALUES (8,7,1030379092,1);
INSERT INTO ezsearch_return_count VALUES (9,8,1030384351,1);
INSERT INTO ezsearch_return_count VALUES (10,9,1030384358,1);
INSERT INTO ezsearch_return_count VALUES (11,10,1030384362,1);
INSERT INTO ezsearch_return_count VALUES (12,11,1030384366,1);
INSERT INTO ezsearch_return_count VALUES (13,12,1030384371,0);

#
# Table structure for table 'ezsearch_search_phrase'
#

CREATE TABLE ezsearch_search_phrase (
  id int(11) NOT NULL auto_increment,
  phrase varchar(250) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezsearch_search_phrase'
#

INSERT INTO ezsearch_search_phrase VALUES (1,'rosa');
INSERT INTO ezsearch_search_phrase VALUES (2,'good');
INSERT INTO ezsearch_search_phrase VALUES (3,'my');
INSERT INTO ezsearch_search_phrase VALUES (4,'the');
INSERT INTO ezsearch_search_phrase VALUES (5,'355');
INSERT INTO ezsearch_search_phrase VALUES (6,'ferrari');
INSERT INTO ezsearch_search_phrase VALUES (7,'image');
INSERT INTO ezsearch_search_phrase VALUES (8,'ez');
INSERT INTO ezsearch_search_phrase VALUES (9,'publish');
INSERT INTO ezsearch_search_phrase VALUES (10,'ez publish');
INSERT INTO ezsearch_search_phrase VALUES (11,'\"ez publish\"');
INSERT INTO ezsearch_search_phrase VALUES (12,'\"publish ez\"');

#
# Table structure for table 'ezsearch_word'
#

CREATE TABLE ezsearch_word (
  id int(11) NOT NULL auto_increment,
  word varchar(150) default NULL,
  object_count int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_word (word)
) TYPE=MyISAM;

#
# Dumping data for table 'ezsearch_word'
#

INSERT INTO ezsearch_word VALUES (13,'telefon',1);
INSERT INTO ezsearch_word VALUES (12,'rosa',1);
INSERT INTO ezsearch_word VALUES (11,'om',1);
INSERT INTO ezsearch_word VALUES (10,'historie',1);
INSERT INTO ezsearch_word VALUES (9,'liten',1);
INSERT INTO ezsearch_word VALUES (8,'en',2);
INSERT INTO ezsearch_word VALUES (14,'test',2);
INSERT INTO ezsearch_word VALUES (15,'this',37);
INSERT INTO ezsearch_word VALUES (16,'is',37);
INSERT INTO ezsearch_word VALUES (17,'my',5);
INSERT INTO ezsearch_word VALUES (18,'favourite',1);
INSERT INTO ezsearch_word VALUES (19,'folder',3);
INSERT INTO ezsearch_word VALUES (20,'lorem',9);
INSERT INTO ezsearch_word VALUES (21,'ipsolum',9);
INSERT INTO ezsearch_word VALUES (22,'loris',9);
INSERT INTO ezsearch_word VALUES (23,'liroad',9);
INSERT INTO ezsearch_word VALUES (24,'book',1);
INSERT INTO ezsearch_word VALUES (25,'bananer',1);
INSERT INTO ezsearch_word VALUES (26,'i',1);
INSERT INTO ezsearch_word VALUES (27,'skien',1);
INSERT INTO ezsearch_word VALUES (28,'asdfgsdf',1);
INSERT INTO ezsearch_word VALUES (29,'gsdfg',3);
INSERT INTO ezsearch_word VALUES (30,'sdfg',3);
INSERT INTO ezsearch_word VALUES (31,'asdfasdfgsdf',1);
INSERT INTO ezsearch_word VALUES (32,'gsdf',15);
INSERT INTO ezsearch_word VALUES (33,'gsdfgsdf',1);
INSERT INTO ezsearch_word VALUES (63,'berlinetta',1);
INSERT INTO ezsearch_word VALUES (62,'355',1);
INSERT INTO ezsearch_word VALUES (61,'ferrari',2);
INSERT INTO ezsearch_word VALUES (46,'article',3);
INSERT INTO ezsearch_word VALUES (47,'name',2);
INSERT INTO ezsearch_word VALUES (48,'99',1);
INSERT INTO ezsearch_word VALUES (54,'2',1);
INSERT INTO ezsearch_word VALUES (53,'123',1);
INSERT INTO ezsearch_word VALUES (55,'4',1);
INSERT INTO ezsearch_word VALUES (56,'122',1);
INSERT INTO ezsearch_word VALUES (64,'fast',1);
INSERT INTO ezsearch_word VALUES (69,'image',1);
INSERT INTO ezsearch_word VALUES (100,'site',1);
INSERT INTO ezsearch_word VALUES (99,'for',1);
INSERT INTO ezsearch_word VALUES (98,'the',36);
INSERT INTO ezsearch_word VALUES (97,'page',1);
INSERT INTO ezsearch_word VALUES (96,'front',1);
INSERT INTO ezsearch_word VALUES (75,'new',3);
INSERT INTO ezsearch_word VALUES (76,'articles',2);
INSERT INTO ezsearch_word VALUES (77,'here',3);
INSERT INTO ezsearch_word VALUES (78,'you',2);
INSERT INTO ezsearch_word VALUES (79,'can',1);
INSERT INTO ezsearch_word VALUES (80,'place',1);
INSERT INTO ezsearch_word VALUES (88,'group',1);
INSERT INTO ezsearch_word VALUES (87,'user',1);
INSERT INTO ezsearch_word VALUES (86,'level',2);
INSERT INTO ezsearch_word VALUES (85,'top',2);
INSERT INTO ezsearch_word VALUES (89,'brd',1);
INSERT INTO ezsearch_word VALUES (90,'farstad',1);
INSERT INTO ezsearch_word VALUES (101,'news',2);
INSERT INTO ezsearch_word VALUES (102,'latest',1);
INSERT INTO ezsearch_word VALUES (103,'ez',35);
INSERT INTO ezsearch_word VALUES (104,'publish',35);
INSERT INTO ezsearch_word VALUES (105,'all',35);
INSERT INTO ezsearch_word VALUES (106,'about',34);
INSERT INTO ezsearch_word VALUES (107,'!',1);
INSERT INTO ezsearch_word VALUES (108,'intro',12);
INSERT INTO ezsearch_word VALUES (109,'body',21);
INSERT INTO ezsearch_word VALUES (110,'images',2);
INSERT INTO ezsearch_word VALUES (111,'should',1);
INSERT INTO ezsearch_word VALUES (112,'put',1);
INSERT INTO ezsearch_word VALUES (113,'your',1);
INSERT INTO ezsearch_word VALUES (114,'bike',1);
INSERT INTO ezsearch_word VALUES (115,'a',1);
INSERT INTO ezsearch_word VALUES (116,'biker',1);

#
# Table structure for table 'ezsession'
#

CREATE TABLE ezsession (
  session_key varchar(32) NOT NULL default '',
  expiration_time int(11) unsigned NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (session_key),
  KEY expiration_time (expiration_time)
) TYPE=MyISAM;

#
# Dumping data for table 'ezsession'
#

INSERT INTO ezsession VALUES ('307d69a294d70db3c2f076da6be76fc4',1030630968,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|s:2:\"87\";');
INSERT INTO ezsession VALUES ('725274b60eadddfc42c3a63c6f337804',1030370604,'eZUserLoggedInID|i:1;eZExecutionStack|a:0:{}');
INSERT INTO ezsession VALUES ('a98558213ff9bd36bc128d08430589b5',1030087153,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('b7c43ff87d49a77bcba04d923f403b78',1030083720,'');
INSERT INTO ezsession VALUES ('2359223bc4fa92cede824626a5208f80',1030109590,'');
INSERT INTO ezsession VALUES ('b8887c50d2c2adf723b8c092fd7e8040',1030114175,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('3d66a494c0259d21c687c390aa6a3f83',1030114178,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('1bcda826bb216421fc6a5d9dbb4d0137',1030114180,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('2e34451bc45d70310f1fb304f9f2b16a',1030114332,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('ee4925667bca64c0fb3cba7465d67f5a',1030114409,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('c8ca97d9bf258cca84edae5b33ec8f83',1030171778,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('ac9b0bf8141c6c0a8425e0073be7dbaa',1030256074,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('213474da08ef7f3c1b40e4c7c8696bea',1030283011,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('9ba953daaf9c2edf517e9e1daf38d695',1030345041,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('c2caf6833435c47cf709a4b725441aac',1030361669,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('9a4883c8d7254b0a51b6de942caf1889',1030608548,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('c740c8fb9251ac8e9d834fd327a0df8b',1030613479,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('96a9d6edbddda59631e55e2c450d3f37',1030621207,'eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('2833e15cfe8a51269c8e0180658d4b43',1030628102,'eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('5983d7569d5c5fe230840966184e2bd2',1030628294,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('6d8dc21f46cffbfba63c188fd9b05936',1030628304,'');
INSERT INTO ezsession VALUES ('c952a4a1bec6d89e243d549dbd7b5705',1030629037,'');
INSERT INTO ezsession VALUES ('72668993d3f14b8804843cdf59fba08b',1030629110,'');
INSERT INTO ezsession VALUES ('0d11b62df285440635087927841bf4b4',1030631855,'');
INSERT INTO ezsession VALUES ('4f0af0e99339358ad0ed72efd1bc1996',1030632580,'');
INSERT INTO ezsession VALUES ('34d9778446182cb6491c73486bfe789c',1030632941,'');
INSERT INTO ezsession VALUES ('6d1bb425563bc1f40c8d38be6416bb04',1030633042,'');
INSERT INTO ezsession VALUES ('30097d3af1bd9979b00660ba5eec0e8f',1030633080,'');
INSERT INTO ezsession VALUES ('3816bd43f3431a416e156bd4021a099a',1030633416,'');
INSERT INTO ezsession VALUES ('28b2c770cae62cebbdc85d66f8e075bf',1030634052,'');
INSERT INTO ezsession VALUES ('308535f7431f73f144a023604c5164f6',1030634107,'');
INSERT INTO ezsession VALUES ('01e109e1a75668cf2f755d3a0f66bb69',1030636382,'eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('2d7411087546e925afc6e2a5fbf79dfa',1030635626,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('da38fc1a944b5ccbe689311bbe807ea9',1030637020,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('ac9d6e2110ee60aab546ee5dfdb6a025',1030643872,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');

#
# Table structure for table 'ezuser'
#

CREATE TABLE ezuser (
  contentobject_id int(11) NOT NULL default '0',
  login varchar(150) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  password_hash_type int(11) NOT NULL default '1',
  password_hash varchar(50) default NULL
) TYPE=MyISAM;

#
# Dumping data for table 'ezuser'
#

INSERT INTO ezuser VALUES (1,'anonymous','anon@anon.com',1,'');
INSERT INTO ezuser VALUES (87,'bf','bf',3,'f1f931686151a0d0624b9de7c21b44cd');
INSERT INTO ezuser VALUES (92,'bf','bf@ez.no',3,'f1f931686151a0d0624b9de7c21b44cd');

#
# Table structure for table 'ezuser_role'
#

CREATE TABLE ezuser_role (
  id int(11) NOT NULL auto_increment,
  role_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezuser_role'
#

INSERT INTO ezuser_role VALUES (1,1,49);
INSERT INTO ezuser_role VALUES (2,2,50);
INSERT INTO ezuser_role VALUES (3,3,51);
INSERT INTO ezuser_role VALUES (4,3,53);
INSERT INTO ezuser_role VALUES (5,4,53);
INSERT INTO ezuser_role VALUES (6,1,8);
INSERT INTO ezuser_role VALUES (7,1,4);
INSERT INTO ezuser_role VALUES (8,3,8);

#
# Table structure for table 'ezwishlist'
#

CREATE TABLE ezwishlist (
  id int(11) NOT NULL auto_increment,
  user_id int(11) NOT NULL default '0',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezwishlist'
#

INSERT INTO ezwishlist VALUES (1,1,1);
INSERT INTO ezwishlist VALUES (2,87,2);

#
# Table structure for table 'ezworkflow'
#

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

#
# Dumping data for table 'ezworkflow'
#

INSERT INTO ezworkflow VALUES (1,0,1,'group_ezserial','Publish',-1,-1,1024392098,1024392098);
INSERT INTO ezworkflow VALUES (2,0,1,'group_ezserial','Editor approval',-1,-1,1024392098,1024392098);
INSERT INTO ezworkflow VALUES (3,0,1,'group_ezserial','Advanced approval',-1,-1,1024392098,1024392098);

#
# Table structure for table 'ezworkflow_assign'
#

CREATE TABLE ezworkflow_assign (
  id int(11) NOT NULL auto_increment,
  workflow_id int(11) NOT NULL default '0',
  node_id int(11) NOT NULL default '0',
  access_type int(11) NOT NULL default '0',
  as_tree int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezworkflow_assign'
#


#
# Table structure for table 'ezworkflow_event'
#

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

#
# Dumping data for table 'ezworkflow_event'
#

INSERT INTO ezworkflow_event VALUES (1,0,1,'event_ezpublish','Publish object',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);
INSERT INTO ezworkflow_event VALUES (2,0,2,'event_ezapprove','Approve by editor',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1);
INSERT INTO ezworkflow_event VALUES (3,0,2,'event_ezmessage','Send message to editor',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2);
INSERT INTO ezworkflow_event VALUES (4,0,3,'event_ezmessage','Send first message',NULL,NULL,NULL,NULL,'First test message from event',NULL,NULL,NULL,1);
INSERT INTO ezworkflow_event VALUES (5,0,3,'event_ezapprove','Approve by editor',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2);
INSERT INTO ezworkflow_event VALUES (6,0,3,'event_ezpublish','Unpublish',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3);
INSERT INTO ezworkflow_event VALUES (7,0,3,'event_ezmessage','Send second message',NULL,NULL,NULL,NULL,'Some text',NULL,NULL,NULL,4);
INSERT INTO ezworkflow_event VALUES (8,0,3,'event_ezpublish','Publish',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5);

#
# Table structure for table 'ezworkflow_group'
#

CREATE TABLE ezworkflow_group (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  modifier_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezworkflow_group'
#

INSERT INTO ezworkflow_group VALUES (1,'Standard',-1,-1,1024392098,1024392098);
INSERT INTO ezworkflow_group VALUES (2,'Custom',-1,-1,1024392098,1024392098);

#
# Table structure for table 'ezworkflow_group_link'
#

CREATE TABLE ezworkflow_group_link (
  workflow_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (workflow_id,group_id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezworkflow_group_link'
#

INSERT INTO ezworkflow_group_link VALUES (1,1);
INSERT INTO ezworkflow_group_link VALUES (2,1);
INSERT INTO ezworkflow_group_link VALUES (3,1);
INSERT INTO ezworkflow_group_link VALUES (3,2);

#
# Table structure for table 'ezworkflow_process'
#

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

#
# Dumping data for table 'ezworkflow_process'
#


#
# Table structure for table 'object_tree'
#

CREATE TABLE object_tree (
  node_id int(11) NOT NULL default '0',
  parent_node_id int(11) NOT NULL default '0',
  object_id varchar(255) default NULL,
  depth int(11) NOT NULL default '0',
  path varchar(255) NOT NULL default '',
  md5_path varchar(15) default NULL
) TYPE=MyISAM;

#
# Dumping data for table 'object_tree'
#

INSERT INTO object_tree VALUES (1,0,'Root node',1,'/',NULL);
INSERT INTO object_tree VALUES (2,1,'Sports',2,'/1/',NULL);
INSERT INTO object_tree VALUES (3,1,'News',2,'/1/',NULL);
INSERT INTO object_tree VALUES (4,2,'Motor',3,'/1/2/',NULL);
INSERT INTO object_tree VALUES (5,4,'F1',4,'/1/2/4/',NULL);
INSERT INTO object_tree VALUES (6,4,'F3000',4,'/1/2/4/',NULL);
INSERT INTO object_tree VALUES (7,5,'Fn1',5,'/1/2/4/5/',NULL);
INSERT INTO object_tree VALUES (8,5,'Fn2',5,'/1/2/4/5/',NULL);
INSERT INTO object_tree VALUES (9,3,'Domestic',3,'/1/3/',NULL);
INSERT INTO object_tree VALUES (10,3,'News 1',4,'/1/3/9/',NULL);
INSERT INTO object_tree VALUES (11,3,'News 2',4,'/1/3/9/',NULL);
INSERT INTO object_tree VALUES (12,3,'Fn1',3,'/1/3/',NULL);

