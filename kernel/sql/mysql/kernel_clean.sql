# MySQL dump 8.13
#
# Host: localhost    Database: bf
#--------------------------------------------------------
# Server version	3.23.36-log

#
# Table structure for table 'ezapprovetasks'
#

CREATE TABLE ezapprovetasks (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) default NULL,
  task_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezapprovetasks'
#


#
# Table structure for table 'ezbasket'
#

CREATE TABLE ezbasket (
  id int(11) NOT NULL auto_increment,
  session_id varchar(255) NOT NULL default '',
  productcollection_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezbasket'
#


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


#
# Table structure for table 'ezcontentclass'
#

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

#
# Dumping data for table 'ezcontentclass'
#

INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1033922265);
INSERT INTO ezcontentclass VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1033922035);
INSERT INTO ezcontentclass VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1033922064);
INSERT INTO ezcontentclass VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1033922083);
INSERT INTO ezcontentclass VALUES (5,0,'Image','','<name>',8,14,1031484992,1033921948);
INSERT INTO ezcontentclass VALUES (1,1,'Folder','folder','<name>',-1,14,1024392098,1033922274);

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
  data_text4 varchar(50) default NULL,
  PRIMARY KEY  (id,version)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentclass_attribute'
#

INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (4,1,1,'name','Name','ezstring',0,0,1,255,0,0,0,0,0,0,0,'Folder','','','');
INSERT INTO ezcontentclass_attribute VALUES (12,0,4,'user_account','User account','ezuser',1,1,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (119,1,1,'description','Description','ezxmltext',0,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',1,0,4,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',1,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (117,0,5,'caption','Caption','ezxmltext',0,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',0,0,1,150,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (1,0,2,'title','Title','ezstring',0,1,1,255,0,0,0,0,0,0,0,'New article','','','');
INSERT INTO ezcontentclass_attribute VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','');

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

INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (2,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (4,0,2,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (5,0,3,'Media');
INSERT INTO ezcontentclass_classgroup VALUES (3,0,2,'');
INSERT INTO ezcontentclass_classgroup VALUES (1,1,1,'Content');

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

INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO ezcontentclassgroup VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO ezcontentclassgroup VALUES (3,'Media',8,14,1032009743,1033922120);

#
# Table structure for table 'ezcontentobject'
#

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
  published int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject'
#

INSERT INTO ezcontentobject VALUES (1,0,0,2,1,1,'Frontpage20',1,0,1,1033917596,1033917596);
INSERT INTO ezcontentobject VALUES (4,0,0,5,0,3,'Users',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (10,8,0,11,0,4,'Anonymous User',1,0,1,1033920665,1033920665);
INSERT INTO ezcontentobject VALUES (11,8,0,12,0,3,'Guest accounts',1,0,1,1033920746,1033920746);
INSERT INTO ezcontentobject VALUES (12,8,0,13,0,3,'Administrator users',1,0,1,1033920775,1033920775);
INSERT INTO ezcontentobject VALUES (13,8,0,14,0,3,'Editors',1,0,1,1033920794,1033920794);
INSERT INTO ezcontentobject VALUES (14,8,0,15,0,4,'Administrator User',1,0,1,1033920830,1033920830);

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

INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (2,'eng-GB',1,1,119,'This folder contains some information about...',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (7,'eng-GB',1,4,5,'Main group',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',2,1,4,'My folder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'eng-GB',2,1,119,'This folder contains some information about...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (21,'eng-GB',1,10,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0);
INSERT INTO ezcontentobject_attribute VALUES (19,'eng-GB',1,10,8,'Anonymous',0,0);
INSERT INTO ezcontentobject_attribute VALUES (20,'eng-GB',1,10,9,'User',0,0);
INSERT INTO ezcontentobject_attribute VALUES (23,'eng-GB',1,11,7,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0);
INSERT INTO ezcontentobject_attribute VALUES (25,'eng-GB',1,12,7,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (26,'eng-GB',1,13,6,'Editors',0,0);
INSERT INTO ezcontentobject_attribute VALUES (27,'eng-GB',1,13,7,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (28,'eng-GB',1,14,8,'Administrator',0,0);
INSERT INTO ezcontentobject_attribute VALUES (29,'eng-GB',1,14,9,'User',0,0);
INSERT INTO ezcontentobject_attribute VALUES (30,'eng-GB',1,14,12,'',0,0);

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

INSERT INTO ezcontentobject_link VALUES (1,79,2,31);
INSERT INTO ezcontentobject_link VALUES (2,79,2,32);
INSERT INTO ezcontentobject_link VALUES (3,79,2,33);
INSERT INTO ezcontentobject_link VALUES (4,79,2,47);
INSERT INTO ezcontentobject_link VALUES (5,79,2,54);
INSERT INTO ezcontentobject_link VALUES (6,79,2,55);
INSERT INTO ezcontentobject_link VALUES (7,79,2,56);
INSERT INTO ezcontentobject_link VALUES (8,39,6,79);
INSERT INTO ezcontentobject_link VALUES (9,34,4,34);
INSERT INTO ezcontentobject_link VALUES (10,34,4,35);
INSERT INTO ezcontentobject_link VALUES (11,34,4,36);
INSERT INTO ezcontentobject_link VALUES (12,32,10,102);
INSERT INTO ezcontentobject_link VALUES (13,32,10,106);
INSERT INTO ezcontentobject_link VALUES (14,32,10,107);
INSERT INTO ezcontentobject_link VALUES (15,32,10,124);
INSERT INTO ezcontentobject_link VALUES (16,119,3,102);
INSERT INTO ezcontentobject_link VALUES (17,119,3,106);
INSERT INTO ezcontentobject_link VALUES (18,119,3,107);
INSERT INTO ezcontentobject_link VALUES (19,119,3,124);
INSERT INTO ezcontentobject_link VALUES (20,31,11,118);
INSERT INTO ezcontentobject_link VALUES (21,31,11,31);
INSERT INTO ezcontentobject_link VALUES (22,162,1,102);
INSERT INTO ezcontentobject_link VALUES (23,162,1,106);
INSERT INTO ezcontentobject_link VALUES (24,162,1,107);
INSERT INTO ezcontentobject_link VALUES (25,162,1,124);
INSERT INTO ezcontentobject_link VALUES (26,173,16,102);
INSERT INTO ezcontentobject_link VALUES (27,173,16,106);
INSERT INTO ezcontentobject_link VALUES (28,173,16,107);
INSERT INTO ezcontentobject_link VALUES (29,183,6,33);
INSERT INTO ezcontentobject_link VALUES (30,183,6,101);

#
# Table structure for table 'ezcontentobject_tree'
#

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
  path_identification_string text,
  PRIMARY KEY  (node_id),
  KEY ezcontentobject_tree_path (path_string),
  KEY ezcontentobject_tree_p_node_id (parent_node_id),
  KEY ezcontentobject_tree_co_id (contentobject_id),
  KEY ezcontentobject_tree_depth (depth)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject_tree'
#

INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,NULL,0,'/1/',NULL,1,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (2,1,1,23,1,1360594808,1,'/1/2/','',2,7,'frontpage');
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,NULL,NULL,1,'/1/5/',NULL,8,15,NULL);
INSERT INTO ezcontentobject_tree VALUES (11,5,10,1,1,-1609495635,2,'/1/5/11/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (12,5,11,1,1,-1609495635,2,'/1/5/12/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (13,5,12,1,1,-1609495635,2,'/1/5/13/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (14,5,13,1,1,-1609495635,2,'/1/5/14/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (15,13,14,1,1,934329528,3,'/1/5/13/15/','',0,0,'users/administrator_users/');

#
# Table structure for table 'ezcontentobject_version'
#

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

#
# Dumping data for table 'ezcontentobject_version'
#

INSERT INTO ezcontentobject_version VALUES (1,1,0,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version VALUES (4,4,0,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version VALUES (436,1,8,2,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version VALUES (438,10,8,1,1033920649,1033920665,0,0,0);
INSERT INTO ezcontentobject_version VALUES (439,11,8,1,1033920737,1033920746,0,0,0);
INSERT INTO ezcontentobject_version VALUES (440,12,8,1,1033920760,1033920775,0,0,0);
INSERT INTO ezcontentobject_version VALUES (441,13,8,1,1033920786,1033920794,0,0,0);
INSERT INTO ezcontentobject_version VALUES (442,14,8,1,1033920808,1033920830,0,0,0);

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


#
# Table structure for table 'ezmedia'
#

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
  is_loop int(1) default NULL,
  PRIMARY KEY  (contentobject_attribute_id,version)
) TYPE=MyISAM;

#
# Dumping data for table 'ezmedia'
#


#
# Table structure for table 'ezmodule_run'
#

CREATE TABLE ezmodule_run (
  id int(11) NOT NULL auto_increment,
  workflow_process_id int(11) default NULL,
  module_name varchar(255) default NULL,
  function_name varchar(255) default NULL,
  module_data text,
  PRIMARY KEY  (id),
  UNIQUE KEY ezmodule_run_workflow_process_id_s (workflow_process_id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezmodule_run'
#


#
# Table structure for table 'eznode_assignment'
#

CREATE TABLE eznode_assignment (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  parent_node int(11) default NULL,
  main int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'eznode_assignment'
#

INSERT INTO eznode_assignment VALUES (2,1,1,1,1);
INSERT INTO eznode_assignment VALUES (3,4,2,1,1);
INSERT INTO eznode_assignment VALUES (4,8,2,5,1);
INSERT INTO eznode_assignment VALUES (144,4,4,1,1);
INSERT INTO eznode_assignment VALUES (147,210,1,5,1);
INSERT INTO eznode_assignment VALUES (146,209,1,5,1);
INSERT INTO eznode_assignment VALUES (145,1,2,1,1);
INSERT INTO eznode_assignment VALUES (148,9,1,2,1);
INSERT INTO eznode_assignment VALUES (149,10,1,5,1);
INSERT INTO eznode_assignment VALUES (150,11,1,5,1);
INSERT INTO eznode_assignment VALUES (151,12,1,5,1);
INSERT INTO eznode_assignment VALUES (152,13,1,5,1);
INSERT INTO eznode_assignment VALUES (153,14,1,13,1);

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

INSERT INTO ezpolicy VALUES (306,1,'read','content','');
INSERT INTO ezpolicy VALUES (314,3,'*','content','*');
INSERT INTO ezpolicy VALUES (308,2,'*','*','*');

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

INSERT INTO ezpolicy_limitation VALUES (244,306,'ClassID',0,'','');

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

INSERT INTO ezpolicy_limitation_value VALUES (407,244,1);
INSERT INTO ezpolicy_limitation_value VALUES (408,244,7);

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

INSERT INTO ezrole VALUES (1,0,'Anonymous','');
INSERT INTO ezrole VALUES (2,0,'Administrator','*');
INSERT INTO ezrole VALUES (3,0,'Editor','');

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


#
# Table structure for table 'ezsection'
#

CREATE TABLE ezsection (
  id int(11) NOT NULL auto_increment,
  name varchar(255) default NULL,
  locale varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezsection'
#

INSERT INTO ezsection VALUES (1,'Standard section','nor-NO');

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

INSERT INTO ezsession VALUES ('beea3def7581c9fac9a3959546d91691',1034181050,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('c427b1ec51dfaf03fb7675aea7769c85',1034181692,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');

#
# Table structure for table 'eztask'
#

CREATE TABLE eztask (
  id int(11) NOT NULL auto_increment,
  task_type int(11) NOT NULL default '0',
  status int(11) NOT NULL default '0',
  connection_type int(11) NOT NULL default '0',
  session_hash varchar(80) NOT NULL default '',
  creator_id int(11) NOT NULL default '0',
  receiver_id int(11) NOT NULL default '0',
  parent_task_type int(11) NOT NULL default '0',
  parent_task_id int(11) NOT NULL default '0',
  access_type int(11) NOT NULL default '0',
  object_type int(11) NOT NULL default '0',
  object_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  modified int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'eztask'
#


#
# Table structure for table 'eztask_message'
#

CREATE TABLE eztask_message (
  id int(11) NOT NULL auto_increment,
  task_id int(11) NOT NULL default '0',
  contentobject_id int(11) NOT NULL default '0',
  created int(11) NOT NULL default '0',
  creator_type int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'eztask_message'
#


#
# Table structure for table 'eztrigger'
#

CREATE TABLE eztrigger (
  id int(11) NOT NULL auto_increment,
  module_name varchar(200) NOT NULL default '',
  function_name varchar(200) NOT NULL default '',
  connect_type char(1) NOT NULL default '',
  workflow_id int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY eztrigger_def_id (module_name,function_name,connect_type)
) TYPE=MyISAM;

#
# Dumping data for table 'eztrigger'
#


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

INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',3,'db52c38a553f880386435b8bb1f74393');
INSERT INTO ezuser VALUES (14,'admin','nospam@ez.no',3,'adcd37bc8ee8b2845e8419ac0f752e0f');

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

INSERT INTO ezuser_role VALUES (24,1,4);
INSERT INTO ezuser_role VALUES (25,2,12);

#
# Table structure for table 'ezuser_setting'
#

CREATE TABLE ezuser_setting (
  user_id int(11) NOT NULL default '0',
  is_enabled int(1) NOT NULL default '0',
  max_login int(11) default NULL,
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezuser_setting'
#


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

INSERT INTO ezworkflow VALUES (1,0,1,'group_ezserial','Sp\'s forkflow',8,24,1031927869,1032856662);

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

INSERT INTO ezworkflow_event VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO ezworkflow_event VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);

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

#
# Table structure for table 'ezworkflow_group_link'
#

CREATE TABLE ezworkflow_group_link (
  workflow_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  workflow_version int(11) default NULL,
  group_name varchar(255) default NULL,
  PRIMARY KEY  (workflow_id,group_id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezworkflow_group_link'
#

INSERT INTO ezworkflow_group_link VALUES (1,1,0,'Standard');

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
  event_state int(11) default NULL,
  status int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezworkflow_process'
#


