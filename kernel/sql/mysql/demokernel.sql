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
INSERT INTO ezcontentclass VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1034264409);
INSERT INTO ezcontentclass VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1033922064);
INSERT INTO ezcontentclass VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1033922083);
INSERT INTO ezcontentclass VALUES (5,0,'Image','','<name>',8,14,1031484992,1035892890);
INSERT INTO ezcontentclass VALUES (6,0,'Forum','','<name>',14,14,1034181899,1034182029);
INSERT INTO ezcontentclass VALUES (21,0,'New Class','','<title>',14,14,1034191051,1034191079);
INSERT INTO ezcontentclass VALUES (8,0,'Forum message','','<topic>',14,14,1034185241,1034185314);
INSERT INTO ezcontentclass VALUES (22,0,'Book','book','<title>',14,14,1034251361,1034258912);
INSERT INTO ezcontentclass VALUES (23,0,'Book Review','book_review','<title>',14,14,1034258928,1034259321);
INSERT INTO ezcontentclass VALUES (24,0,'Info page','','<name>',14,14,1035882216,1035882267);

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
INSERT INTO ezcontentclass_attribute VALUES (12,0,4,'user_account','User account','ezuser',1,1,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (126,0,6,'description','Description','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (125,0,6,'icon','Icon','ezimage',1,0,2,1,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (120,0,2,'intro','Intro','ezxmltext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (1,0,2,'title','Title','ezstring',1,0,1,255,0,0,0,0,0,0,0,'New article','','','');
INSERT INTO ezcontentclass_attribute VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','');
INSERT INTO ezcontentclass_attribute VALUES (141,0,21,'title','title','ezstring',1,0,1,0,0,0,0,0,0,0,0,'grwegwgw','','','');
INSERT INTO ezcontentclass_attribute VALUES (128,0,8,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','');
INSERT INTO ezcontentclass_attribute VALUES (129,0,8,'message','Message','eztext',1,1,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (153,0,23,'review','Review','ezxmltext',1,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (152,0,23,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (151,0,23,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (149,0,23,'title','Title','ezstring',1,1,1,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (150,0,23,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (148,0,22,'photo','Photo','ezimage',0,0,7,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (147,0,22,'price','Price','ezprice',0,0,6,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (146,0,22,'availability','Availability','eztext',0,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (145,0,22,'description','Description','ezxmltext',0,0,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (144,0,22,'publisher','Publisher','ezstring',0,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (143,0,22,'author','Author','ezstring',0,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (142,0,22,'title','Title','ezstring',0,0,1,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (156,0,24,'image','Image','ezimage',1,0,3,1,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (155,0,24,'body','Body','ezxmltext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (154,0,24,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (117,0,5,'caption','Caption','ezxmltext',0,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',0,0,1,150,0,0,0,0,0,0,0,'','','','');

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
INSERT INTO ezcontentclass_classgroup VALUES (6,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (21,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (22,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (22,0,5,'Book Corner');
INSERT INTO ezcontentclass_classgroup VALUES (23,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (23,0,5,'Book Corner');
INSERT INTO ezcontentclass_classgroup VALUES (24,0,1,'Content');

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
INSERT INTO ezcontentclassgroup VALUES (4,'New Group',14,14,1034188307,1034188307);
INSERT INTO ezcontentclassgroup VALUES (5,'Book Corner',14,14,1034258883,1034258894);

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
INSERT INTO ezcontentobject VALUES (15,14,0,16,2,1,'White box',5,0,1,1035893229,1035893229);
INSERT INTO ezcontentobject VALUES (10,8,0,11,0,4,'Anonymous User',1,0,1,1033920665,1033920665);
INSERT INTO ezcontentobject VALUES (11,8,0,12,0,3,'Guest accounts',1,0,1,1033920746,1033920746);
INSERT INTO ezcontentobject VALUES (12,8,0,13,0,3,'Administrator users',1,0,1,1033920775,1033920775);
INSERT INTO ezcontentobject VALUES (13,8,0,14,0,3,'Editors',1,0,1,1033920794,1033920794);
INSERT INTO ezcontentobject VALUES (14,8,0,15,0,4,'Administrator User',1,0,1,1033920830,1033920830);
INSERT INTO ezcontentobject VALUES (17,14,0,18,2,1,'Flowers',4,0,1,1035886818,1035886818);
INSERT INTO ezcontentobject VALUES (93,14,0,92,2,1,'Water',1,0,1,1035887037,1035887037);
INSERT INTO ezcontentobject VALUES (94,14,0,93,2,5,'Water 1',3,0,1,1035888486,1035888486);
INSERT INTO ezcontentobject VALUES (23,14,0,24,3,1,'News',1,0,1,1034174464,1034174464);
INSERT INTO ezcontentobject VALUES (72,14,0,71,3,2,'Typhoon is near',9,0,1,1034264438,1034264438);
INSERT INTO ezcontentobject VALUES (25,14,0,26,3,1,'Frontpage',3,0,1,1034334677,1034334677);
INSERT INTO ezcontentobject VALUES (26,14,0,27,3,1,'Sport',3,0,1,1034334718,1034334718);
INSERT INTO ezcontentobject VALUES (87,14,0,86,2,5,'Flower 2',3,0,1,1035892937,1035892937);
INSERT INTO ezcontentobject VALUES (29,14,0,30,3,1,'World news',3,0,1,1034334767,1034334767);
INSERT INTO ezcontentobject VALUES (30,14,0,31,4,1,'Crossroads forum',1,0,1,1034181792,1034181792);
INSERT INTO ezcontentobject VALUES (31,14,0,32,4,1,'Forums',1,0,1,1034181825,1034181825);
INSERT INTO ezcontentobject VALUES (88,14,0,87,2,5,'',2,0,1,1035888272,1035888272);
INSERT INTO ezcontentobject VALUES (89,14,0,88,2,5,'',2,0,1,1035888302,1035888302);
INSERT INTO ezcontentobject VALUES (86,14,0,85,2,5,'Flower 1',5,0,1,1035892919,1035892919);
INSERT INTO ezcontentobject VALUES (84,14,0,83,2,1,'Forest',5,0,1,1035892777,1035892777);
INSERT INTO ezcontentobject VALUES (39,10,0,0,4,8,'New Forum message',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (43,10,0,43,4,8,'First reply',1,0,1,1034186575,1034186575);
INSERT INTO ezcontentobject VALUES (45,10,0,45,4,8,'I agree !',1,0,1,1034186992,1034186992);
INSERT INTO ezcontentobject VALUES (46,10,0,46,4,8,'This forum is bad!!!!!!!!!!',1,0,1,1034187189,1034187189);
INSERT INTO ezcontentobject VALUES (47,10,0,48,4,8,'This is my reply',1,0,1,1034187441,1034187441);
INSERT INTO ezcontentobject VALUES (90,14,0,89,2,5,'Forest 2',2,0,1,1035888387,1035888387);
INSERT INTO ezcontentobject VALUES (57,14,0,55,1,20,'',1,0,1,1034190865,1034190865);
INSERT INTO ezcontentobject VALUES (91,14,0,90,2,5,'Forest 3',2,0,1,1035888410,1035888410);
INSERT INTO ezcontentobject VALUES (62,14,0,60,5,1,'The Book Corner',1,0,1,1034251246,1034251246);
INSERT INTO ezcontentobject VALUES (63,14,0,61,5,1,'Top 20 Books',1,0,1,1034252134,1034252134);
INSERT INTO ezcontentobject VALUES (64,14,0,62,5,1,'Bestsellers',1,0,1,1034252256,1034252256);
INSERT INTO ezcontentobject VALUES (65,14,0,63,5,1,'Recommendations',1,0,1,1034252479,1034252479);
INSERT INTO ezcontentobject VALUES (66,14,0,64,5,1,'Authors',1,0,1,1034252585,1034252585);
INSERT INTO ezcontentobject VALUES (67,14,0,65,5,1,'Books',1,0,1,1034253542,1034253542);
INSERT INTO ezcontentobject VALUES (83,14,0,82,2,24,'Whitebox contemporary art gallery',4,0,1,1035904493,1035904493);
INSERT INTO ezcontentobject VALUES (70,14,0,69,5,23,'Fantastic',1,0,1,1034259506,1034259506);
INSERT INTO ezcontentobject VALUES (92,14,0,91,2,5,'Forest 4',2,0,1,1035888444,1035888444);
INSERT INTO ezcontentobject VALUES (85,14,0,84,2,5,'Forest 1',3,0,1,1035888358,1035888358);
INSERT INTO ezcontentobject VALUES (95,14,0,94,2,5,'Water 2',2,0,1,1035888527,1035888527);
INSERT INTO ezcontentobject VALUES (96,14,0,95,2,5,'Water 3',2,0,1,1035888554,1035888554);
INSERT INTO ezcontentobject VALUES (97,14,0,96,2,5,'Water 4',2,0,1,1035888617,1035888617);
INSERT INTO ezcontentobject VALUES (98,14,0,97,2,1,'Animals',1,0,1,1035887250,1035887250);
INSERT INTO ezcontentobject VALUES (99,14,0,98,2,5,'Animal 1',2,0,1,1035888720,1035888720);
INSERT INTO ezcontentobject VALUES (100,14,0,99,2,5,'Animal 2',2,0,1,1035888750,1035888750);
INSERT INTO ezcontentobject VALUES (101,14,0,100,2,5,'Animal 3',2,0,1,1035888654,1035888654);
INSERT INTO ezcontentobject VALUES (102,14,0,101,2,5,'Animal 4',2,0,1,1035888685,1035888685);
INSERT INTO ezcontentobject VALUES (103,14,0,102,2,1,'Landscape',1,0,1,1035887800,1035887800);
INSERT INTO ezcontentobject VALUES (104,14,0,103,2,5,'Landscape 1',2,0,1,1035888035,1035888035);
INSERT INTO ezcontentobject VALUES (105,14,0,104,2,5,'Landscape 2',2,0,1,1035888065,1035888065);
INSERT INTO ezcontentobject VALUES (106,14,0,105,2,5,'Landscape 3',2,0,1,1035888094,1035888094);
INSERT INTO ezcontentobject VALUES (107,14,0,106,2,5,'Landscape 4',2,0,1,1035888131,1035888131);
INSERT INTO ezcontentobject VALUES (108,14,0,0,2,5,'New Image',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (109,14,0,107,3,2,'New article',3,0,1,1035905739,1035905739);
INSERT INTO ezcontentobject VALUES (110,14,0,108,3,1,'Action',1,0,1,1035905816,1035905816);
INSERT INTO ezcontentobject VALUES (111,14,0,109,3,2,'eZ systems travel company',1,0,1,1035905861,1035905861);
INSERT INTO ezcontentobject VALUES (112,14,0,110,3,1,'Leisure',1,0,1,1035905944,1035905944);
INSERT INTO ezcontentobject VALUES (113,14,0,111,3,2,'Food for the soul',1,0,1,1035905997,1035905997);
INSERT INTO ezcontentobject VALUES (114,14,0,112,3,2,'We did it again',2,0,1,1035906867,1035906867);

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
INSERT INTO ezcontentobject_attribute VALUES (32,'eng-GB',1,15,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Contemporary art gallery.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (31,'eng-GB',1,15,4,'White box',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (266,'eng-GB',2,104,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Windy hills</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',1,17,4,'Gallery 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',1,17,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (265,'eng-GB',2,104,116,'Landscape 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (276,'eng-GB',1,107,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',2,17,4,'Gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',2,17,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (275,'eng-GB',1,107,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (273,'eng-GB',1,106,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (274,'eng-GB',1,107,116,'Landscape 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (272,'eng-GB',1,106,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (267,'eng-GB',2,104,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (31,'eng-GB',2,15,4,'White box',0,0);
INSERT INTO ezcontentobject_attribute VALUES (32,'eng-GB',2,15,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Contemporary art gallery.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (31,'eng-GB',3,15,4,'White box',0,0);
INSERT INTO ezcontentobject_attribute VALUES (32,'eng-GB',3,15,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Contemporary art gallery.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (52,'eng-GB',1,23,4,'News',0,0);
INSERT INTO ezcontentobject_attribute VALUES (53,'eng-GB',1,23,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>folder with the news</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',1,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',1,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on frideay evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',1,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',1,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',1,72,123,'',1,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',2,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',2,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',2,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',2,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',2,72,123,'',1,0);
INSERT INTO ezcontentobject_attribute VALUES (59,'eng-GB',1,25,4,'politics',0,0);
INSERT INTO ezcontentobject_attribute VALUES (60,'eng-GB',1,25,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>political news</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (61,'eng-GB',1,26,4,'sports',0,0);
INSERT INTO ezcontentobject_attribute VALUES (62,'eng-GB',1,26,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>sport news</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (236,'eng-GB',1,93,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'eng-GB',1,29,4,'world',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'eng-GB',1,29,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>around the word</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'eng-GB',1,30,4,'Crossroads forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'eng-GB',1,30,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'eng-GB',1,31,4,'Forums',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'eng-GB',1,31,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (245,'eng-GB',1,96,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (237,'eng-GB',1,94,116,'Water 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (238,'eng-GB',1,94,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (239,'eng-GB',1,94,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (237,'eng-GB',2,94,116,'Water 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (238,'eng-GB',2,94,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (239,'eng-GB',2,94,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (240,'eng-GB',1,95,116,'Water 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (241,'eng-GB',1,95,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (242,'eng-GB',1,95,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (243,'eng-GB',1,96,116,'Water 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (244,'eng-GB',1,96,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (235,'eng-GB',1,93,4,'Water',0,0);
INSERT INTO ezcontentobject_attribute VALUES (229,'eng-GB',1,91,116,'Forest 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (230,'eng-GB',1,91,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (231,'eng-GB',1,91,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (232,'eng-GB',1,92,116,'Forest 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (233,'eng-GB',1,92,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (234,'eng-GB',1,92,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (226,'eng-GB',1,90,116,'Forest 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (213,'eng-GB',2,85,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (225,'eng-GB',1,89,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',4,17,4,'Flowers',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',4,17,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (211,'eng-GB',2,85,116,'Forest 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (212,'eng-GB',2,85,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Caption..</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (88,'eng-GB',1,39,128,'New topic',0,0);
INSERT INTO ezcontentobject_attribute VALUES (89,'eng-GB',1,39,129,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (95,'eng-GB',1,43,128,'First reply',0,0);
INSERT INTO ezcontentobject_attribute VALUES (96,'eng-GB',1,43,129,'This is what I think about that...\r\n\r\n-b',0,0);
INSERT INTO ezcontentobject_attribute VALUES (98,'eng-GB',1,45,128,'I agree !',0,0);
INSERT INTO ezcontentobject_attribute VALUES (99,'eng-GB',1,45,129,'But how can you know it\'s true ?\r\n-c',0,0);
INSERT INTO ezcontentobject_attribute VALUES (100,'eng-GB',1,46,128,'This forum is bad!!!!!!!!!!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (101,'eng-GB',1,46,129,'Yeah!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (102,'eng-GB',1,47,128,'This is my reply',0,0);
INSERT INTO ezcontentobject_attribute VALUES (103,'eng-GB',1,47,129,'Foo bar..\r\n\r\n-d',0,0);
INSERT INTO ezcontentobject_attribute VALUES (113,'eng-GB',1,57,140,'rfdegrw3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (130,'eng-GB',1,62,4,'The Book Corner',0,0);
INSERT INTO ezcontentobject_attribute VALUES (131,'eng-GB',1,62,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (132,'eng-GB',1,63,4,'Top 20 Books',0,0);
INSERT INTO ezcontentobject_attribute VALUES (133,'eng-GB',1,63,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (134,'eng-GB',1,64,4,'Bestsellers',0,0);
INSERT INTO ezcontentobject_attribute VALUES (135,'eng-GB',1,64,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (136,'eng-GB',1,65,4,'Recommendations',0,0);
INSERT INTO ezcontentobject_attribute VALUES (137,'eng-GB',1,65,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (138,'eng-GB',1,66,4,'Authors',0,0);
INSERT INTO ezcontentobject_attribute VALUES (139,'eng-GB',1,66,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (140,'eng-GB',1,67,4,'Books',0,0);
INSERT INTO ezcontentobject_attribute VALUES (141,'eng-GB',1,67,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (224,'eng-GB',1,89,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (223,'eng-GB',1,89,116,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (214,'eng-GB',1,86,116,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (215,'eng-GB',1,86,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (216,'eng-GB',1,86,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (217,'eng-GB',1,87,116,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (218,'eng-GB',1,87,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (219,'eng-GB',1,87,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (220,'eng-GB',1,88,116,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (221,'eng-GB',1,88,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (222,'eng-GB',1,88,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',3,17,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',3,17,4,'Flowers',0,0);
INSERT INTO ezcontentobject_attribute VALUES (209,'eng-GB',1,84,4,'Forrest',0,0);
INSERT INTO ezcontentobject_attribute VALUES (210,'eng-GB',1,84,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (211,'eng-GB',1,85,116,'Forrest 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (212,'eng-GB',1,85,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Caption..</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (213,'eng-GB',1,85,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (208,'eng-GB',1,83,156,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (207,'eng-GB',1,83,155,'<?xml version=\"1.0\"?>\n<section>  <paragraph>The art gallery...</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (206,'eng-GB',1,83,154,'Whitebox contemporary art gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (156,'eng-GB',1,70,149,'Fantastic',0,0);
INSERT INTO ezcontentobject_attribute VALUES (157,'eng-GB',1,70,150,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (158,'eng-GB',1,70,151,'jezmondinio',0,0);
INSERT INTO ezcontentobject_attribute VALUES (159,'eng-GB',1,70,152,'Moscow, Russia',0,0);
INSERT INTO ezcontentobject_attribute VALUES (160,'eng-GB',1,70,153,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Habi senatum ut L. Verfinemus opublicia? Quam poerfer icati, C. C. Catro, quit, fue tela maio esi intem re di, nestiu cupim patruriam potiem se factuasdam aus auctum la puli publia nos stretil erra, et, C. Graris hos hosuam P. Si ponfecrei se, Casdactesine mac tam. Catili prae mantis iam que interra? Pat, fatique idem erfervi erit, nore culicavenius horbitas fue iam, quidefactus viliam Roma, constil host res publii probses locciemoerum con tus ad consus dum prae, se conum vis ocre confirm hilicae icienteriam idem esil tem hacteri factoret, ut nox nonimus, cotabefacit L. An defecut in Etris; in speri, que acioca L. Maet; Cas nox nulinte renica; nos, constraeque probus reis publibuntia mo Catqui pubissimis apere nor ut puli iaet in Italegero movente issimus niu consulintiu vitin dis. Opicae con intem, vivere porum spiordiem mo mactantenatu es mo Cat. Serenih libus sedo, num inatium diem host C. maiorei senam ora, senit; nonsult retis.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (271,'eng-GB',1,106,116,'Landscape 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (269,'eng-GB',1,105,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (270,'eng-GB',1,105,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (267,'eng-GB',1,104,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (268,'eng-GB',1,105,116,'Landscape 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (266,'eng-GB',1,104,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (265,'eng-GB',1,104,116,'Landscape 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (263,'eng-GB',1,103,4,'Landscape',0,0);
INSERT INTO ezcontentobject_attribute VALUES (264,'eng-GB',1,103,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (260,'eng-GB',1,102,116,'Animal 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (261,'eng-GB',1,102,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (262,'eng-GB',1,102,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (246,'eng-GB',1,97,116,'Water 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (247,'eng-GB',1,97,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (248,'eng-GB',1,97,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (249,'eng-GB',1,98,4,'Animals',0,0);
INSERT INTO ezcontentobject_attribute VALUES (250,'eng-GB',1,98,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (251,'eng-GB',1,99,116,'Animal 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (252,'eng-GB',1,99,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (253,'eng-GB',1,99,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (254,'eng-GB',1,100,116,'Animal 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (255,'eng-GB',1,100,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (256,'eng-GB',1,100,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (257,'eng-GB',1,101,116,'Animal 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (258,'eng-GB',1,101,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (259,'eng-GB',1,101,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',3,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',3,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',3,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',3,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',3,72,123,'',1,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',4,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (60,'eng-GB',2,25,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Hot news</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (59,'eng-GB',3,25,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute VALUES (60,'eng-GB',3,25,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Hot news</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (61,'eng-GB',2,26,4,'Sport',0,0);
INSERT INTO ezcontentobject_attribute VALUES (62,'eng-GB',2,26,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Sport news</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (61,'eng-GB',3,26,4,'Sport',0,0);
INSERT INTO ezcontentobject_attribute VALUES (62,'eng-GB',3,26,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Sport news</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'eng-GB',2,29,4,'World news',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'eng-GB',2,29,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Around the word</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'eng-GB',3,29,4,'World news',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'eng-GB',3,29,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Around the word</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (59,'eng-GB',2,25,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',4,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',4,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',4,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',4,72,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',5,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',5,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',5,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',5,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',5,72,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',6,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',6,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',6,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',6,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',6,72,123,'',1,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',7,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',7,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',7,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',7,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',7,72,123,'',1,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',8,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',8,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',8,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',8,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',8,72,123,'',1,0);
INSERT INTO ezcontentobject_attribute VALUES (166,'eng-GB',9,72,1,'Typhoon is near',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'eng-GB',9,72,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Huge typhoon now is very close to Skien town in norway. It is recomended to sit at home and drink bear, and do not go out in the street on Friday evening :)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'eng-GB',9,72,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>I\'m just kidding.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'eng-GB',9,72,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'eng-GB',9,72,123,'',1,0);
INSERT INTO ezcontentobject_attribute VALUES (228,'eng-GB',1,90,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (227,'eng-GB',1,90,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (268,'eng-GB',2,105,116,'Landscape 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (269,'eng-GB',2,105,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Snowy mountain</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (270,'eng-GB',2,105,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (271,'eng-GB',2,106,116,'Landscape 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (272,'eng-GB',2,106,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Bright blue sky</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (273,'eng-GB',2,106,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (274,'eng-GB',2,107,116,'Landscape 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (275,'eng-GB',2,107,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Natural shape in stone</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (276,'eng-GB',2,107,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',5,17,4,'Flowers',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',5,17,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',6,17,4,'Flowers',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',6,17,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (214,'eng-GB',2,86,116,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (215,'eng-GB',2,86,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (216,'eng-GB',2,86,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (214,'eng-GB',3,86,116,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (215,'eng-GB',3,86,117,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (216,'eng-GB',3,86,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (214,'eng-GB',4,86,116,'Flower 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (215,'eng-GB',4,86,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>White</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (216,'eng-GB',4,86,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (217,'eng-GB',2,87,116,'Flower 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (218,'eng-GB',2,87,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Yellow</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (219,'eng-GB',2,87,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (220,'eng-GB',2,88,116,'Flower 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (221,'eng-GB',2,88,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Yellow and blue</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (222,'eng-GB',2,88,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (223,'eng-GB',2,89,116,'Flower 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (224,'eng-GB',2,89,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Blue and green</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (225,'eng-GB',2,89,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (209,'eng-GB',2,84,4,'Forest',0,0);
INSERT INTO ezcontentobject_attribute VALUES (210,'eng-GB',2,84,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (211,'eng-GB',3,85,116,'Forest 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (212,'eng-GB',3,85,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>    <P>Blue berries</P>\n</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (213,'eng-GB',3,85,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (226,'eng-GB',2,90,116,'Forest 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (227,'eng-GB',2,90,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Natural shaped man</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (228,'eng-GB',2,90,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (229,'eng-GB',2,91,116,'Forest 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (230,'eng-GB',2,91,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Misty forest</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (231,'eng-GB',2,91,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (232,'eng-GB',2,92,116,'Forest 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (233,'eng-GB',2,92,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Reaching for the sky</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (234,'eng-GB',2,92,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (237,'eng-GB',3,94,116,'Water 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (238,'eng-GB',3,94,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>A wonderful view</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (239,'eng-GB',3,94,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (240,'eng-GB',2,95,116,'Water 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (241,'eng-GB',2,95,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Reaching the shore</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (242,'eng-GB',2,95,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (243,'eng-GB',2,96,116,'Water 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (244,'eng-GB',2,96,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Standing all alone</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (245,'eng-GB',2,96,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (246,'eng-GB',2,97,116,'Water 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (247,'eng-GB',2,97,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Can you hear the water?</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (248,'eng-GB',2,97,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (257,'eng-GB',2,101,116,'Animal 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (258,'eng-GB',2,101,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Not happy?</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (259,'eng-GB',2,101,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (260,'eng-GB',2,102,116,'Animal 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (261,'eng-GB',2,102,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Black and white beauty</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (262,'eng-GB',2,102,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (251,'eng-GB',2,99,116,'Animal 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (252,'eng-GB',2,99,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Balancing monkey</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (253,'eng-GB',2,99,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (254,'eng-GB',2,100,116,'Animal 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (255,'eng-GB',2,100,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Searching for something to eat</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (256,'eng-GB',2,100,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (209,'eng-GB',4,84,4,'Forest',0,0);
INSERT INTO ezcontentobject_attribute VALUES (210,'eng-GB',3,84,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (209,'eng-GB',3,84,4,'Forest',0,0);
INSERT INTO ezcontentobject_attribute VALUES (210,'eng-GB',4,84,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (209,'eng-GB',5,84,4,'Forest',0,0);
INSERT INTO ezcontentobject_attribute VALUES (210,'eng-GB',5,84,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (215,'eng-GB',5,86,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>White</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (214,'eng-GB',5,86,116,'Flower 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (216,'eng-GB',5,86,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (217,'eng-GB',3,87,116,'Flower 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (218,'eng-GB',3,87,117,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Yellow</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (219,'eng-GB',3,87,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (31,'eng-GB',4,15,4,'White box',0,0);
INSERT INTO ezcontentobject_attribute VALUES (32,'eng-GB',4,15,119,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Having difficulties getting away from it all? Have you forgotten the small thing is life? Remember the humming of a bee? The smell of pine? The beauty of a snowy mountain a cold clear winter day?</paragraph>\n  <paragraph>Here is a chance to escape from the stressful, noisy and hectic day you encounter every day. Get your well-deserved breathing space in this gallery where we salute the beauty of Mother Nature, and get away from it all. \nThrough White box you can dream away for a few minutes.\n  \nWith some much beauty surrounding us many people still forget the beauty right outside our window. Remember the sounds, the smells, the feelings and not to forget the sights?</paragraph>\n  <paragraph>Let your mind drift away!</paragraph>\n  <paragraph>White box presents the following galleries:</paragraph>\n  <paragraph>·	Water\n·	Forest\n·	Flowers\n·	Landscape\n·	Animals</paragraph>\n  <paragraph>?All that is gold does not glitter,\nNot all those who wander are lost\nThe old that is strong does not wither,\nDeep roots are not reached by frost?.\n(J. R. R. Tolkien &quot;Lord of the Rings&quot; )</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (31,'eng-GB',5,15,4,'White box',0,0);
INSERT INTO ezcontentobject_attribute VALUES (32,'eng-GB',5,15,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (206,'eng-GB',2,83,154,'Whitebox contemporary art gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (207,'eng-GB',2,83,155,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Having difficulties getting away from it all? Have you forgotten the small thing is life? Remember the humming of a bee? The smell of pine? The beauty of a snowy mountain a cold clear winter day?</paragraph>\n  <paragraph>Here is a chance to escape from the stressful, noisy and hectic day you encounter every day. Get your well-deserved breathing space in this gallery where we salute the beauty of Mother Nature, and get away from it all. \nThrough White box you can dream away for a few minutes.\n  \nWith some much beauty surrounding us many people still forget the beauty right outside our window. Remember the sounds, the smells, the feelings and not to forget the sights?</paragraph>\n  <paragraph>Let your mind drift away!</paragraph>\n  <paragraph>White box presents the following galleries:</paragraph>\n  <paragraph>·	Water\n·	Forest\n·	Flowers\n·	Landscape\n·	Animals</paragraph>\n  <paragraph>?All that is gold does not glitter,\nNot all those who wander are lost\nThe old that is strong does not wither,\nDeep roots are not reached by frost?.\n(J. R. R. Tolkien &quot;Lord of the Rings&quot; )</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (208,'eng-GB',2,83,156,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (206,'eng-GB',3,83,154,'Whitebox contemporary art gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (207,'eng-GB',3,83,155,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Having difficulties getting away from it all? Have you forgotten the small thing is life? Remember the humming of a bee? The smell of pine? The beauty of a snowy mountain a cold clear winter day?</paragraph>\n  <paragraph>Here is a chance to escape from the stressful, noisy and hectic day you encounter every day. Get your well-deserved breathing space in this gallery where we salute the beauty of Mother Nature, and get away from it all. \nThrough White box you can dream away for a few minutes.\n  \nWith some much beauty surrounding us many people still forget the beauty right outside our window. Remember the sounds, the smells, the feelings and not to forget the sights?</paragraph>\n  <paragraph>Let your mind drift away!</paragraph>\n  <paragraph>White box presents the following galleries:</paragraph>\n  <paragraph>·	Water\n·	Forest\n·	Flowers\n·	Landscape\n·	Animals</paragraph>\n  <paragraph>?All that is gold does not glitter,\nNot all those who wander are lost\nThe old that is strong does not wither,\nDeep roots are not reached by frost?.\n(J. R. R. Tolkien &quot;Lord of the Rings&quot; )</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (208,'eng-GB',3,83,156,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (277,'eng-GB',1,108,116,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (278,'eng-GB',1,108,117,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (279,'eng-GB',1,108,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (206,'eng-GB',4,83,154,'Whitebox contemporary art gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (207,'eng-GB',4,83,155,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Having difficulties getting away from it all? Have you forgotten the small thing is life? Remember the humming of a bee? The smell of pine? The beauty of a snowy mountain a cold clear winter day?</paragraph>\n  <paragraph>Here is a chance to escape from the stressful, noisy and hectic day you encounter every day. Get your well-deserved breathing space in this gallery where we salute the beauty of Mother Nature, and get away from it all. \nThrough White box you can dream away for a few minutes.\n  \nWith some much beauty surrounding us many people still forget the beauty right outside our window. Remember the sounds, the smells, the feelings and not to forget the sights?</paragraph>\n  <paragraph>Let your mind drift away!</paragraph>\n  <paragraph>White box presents the following galleries:</paragraph>\n  <paragraph>·	Water\n·	Forest\n·	Flowers\n·	Landscape\n·	Animals</paragraph>\n  <paragraph>?All that is gold does not glitter,\nNot all those who wander are lost\nThe old that is strong does not wither,\nDeep roots are not reached by frost?.\n(J. R. R. Tolkien &quot;Lord of the Rings&quot; )</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (208,'eng-GB',4,83,156,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (280,'eng-GB',1,109,1,'New article',0,0);
INSERT INTO ezcontentobject_attribute VALUES (281,'eng-GB',1,109,120,'<?xml version=\"1.0\"?>\n<section>  <paragraph>eZ systems is proud to announce that the company has been handpicked by The European Tech Tour Association as one of the most exciting companies in Norway. eZ systems was one of 26 companies that The European Tech Tour Association (ETT) think have the most promising future of companies in Norway.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (282,'eng-GB',1,109,121,'<?xml version=\"1.0\"?>\n<section>  <paragraph>In a 2 day event, widely covered by the international press, Tech Tour provided the most innovative and upcoming technology companies in Norway with a unique opportunity to meet and network with a selective group of 60 senior professionals from leading blue-chip technology corporations, venture capitalists, advisors, investment banks, and corporate advisors.</paragraph>\n  <paragraph>The objective was to identify the up-coming and leading top 20+ high-growth privately held technology companies in Norway and introduce them to key European, US and Asian investors and professionals who can assist in their global expansion. eZ systems was proudly picked as one of the 26 companies.</paragraph>\n  <paragraph>This shows another step in the always growing popularity of eZ systems and eZ publish. eZ publish has been a great success since the start and is by August 2002 among the worldwide leaders in content management software. With the expected release of the completely new eZ publish 3.0 later this year the future seams bright for present and new users of eZ publish.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (283,'eng-GB',1,109,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (284,'eng-GB',1,109,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (280,'eng-GB',2,109,1,'New article',0,0);
INSERT INTO ezcontentobject_attribute VALUES (281,'eng-GB',2,109,120,'<?xml version=\"1.0\"?>\n<section>  <paragraph>eZ systems is proud to announce that the company has been handpicked by The European Tech Tour Association as one of the most exciting companies in Norway. eZ systems was one of 26 companies that The European Tech Tour Association (ETT) think have the most promising future of companies in Norway.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (282,'eng-GB',2,109,121,'<?xml version=\"1.0\"?>\n<section>  <paragraph>In a 2 day event, widely covered by the international press, Tech Tour provided the most innovative and upcoming technology companies in Norway with a unique opportunity to meet and network with a selective group of 60 senior professionals from leading blue-chip technology corporations, venture capitalists, advisors, investment banks, and corporate advisors.</paragraph>\n  <paragraph>The objective was to identify the up-coming and leading top 20+ high-growth privately held technology companies in Norway and introduce them to key European, US and Asian investors and professionals who can assist in their global expansion. eZ systems was proudly picked as one of the 26 companies.</paragraph>\n  <paragraph>This shows another step in the always growing popularity of eZ systems and eZ publish. eZ publish has been a great success since the start and is by August 2002 among the worldwide leaders in content management software. With the expected release of the completely new eZ publish 3.0 later this year the future seams bright for present and new users of eZ publish.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (283,'eng-GB',2,109,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (284,'eng-GB',2,109,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (280,'eng-GB',3,109,1,'eZ systems earns award',0,0);
INSERT INTO ezcontentobject_attribute VALUES (281,'eng-GB',3,109,120,'<?xml version=\"1.0\"?>\n<section>  <paragraph>eZ systems is proud to announce that the company has been handpicked by The European Tech Tour Association as one of the most exciting companies in Norway. eZ systems was one of 26 companies that The European Tech Tour Association (ETT) think have the most promising future of companies in Norway.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (282,'eng-GB',3,109,121,'<?xml version=\"1.0\"?>\n<section>  <paragraph>In a 2 day event, widely covered by the international press, Tech Tour provided the most innovative and upcoming technology companies in Norway with a unique opportunity to meet and network with a selective group of 60 senior professionals from leading blue-chip technology corporations, venture capitalists, advisors, investment banks, and corporate advisors.</paragraph>\n  <paragraph>The objective was to identify the up-coming and leading top 20+ high-growth privately held technology companies in Norway and introduce them to key European, US and Asian investors and professionals who can assist in their global expansion. eZ systems was proudly picked as one of the 26 companies.</paragraph>\n  <paragraph>This shows another step in the always growing popularity of eZ systems and eZ publish. eZ publish has been a great success since the start and is by August 2002 among the worldwide leaders in content management software. With the expected release of the completely new eZ publish 3.0 later this year the future seams bright for present and new users of eZ publish.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (283,'eng-GB',3,109,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (284,'eng-GB',3,109,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (285,'eng-GB',1,110,4,'Action',0,0);
INSERT INTO ezcontentobject_attribute VALUES (286,'eng-GB',1,110,119,'<?xml version=\"1.0\"?>\n<section>  <paragraph>eZ systems travels</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (287,'eng-GB',1,111,1,'eZ systems travel company',0,0);
INSERT INTO ezcontentobject_attribute VALUES (288,'eng-GB',1,111,120,'<?xml version=\"1.0\"?>\n<section>  <paragraph>The Actiongroup from eZ systems are now in the middle of evaluating possible new markets. During the last few months they have focused on what part of the world that has the most beautiful sights. They have visited most corners of the world to come up with a winner.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (289,'eng-GB',1,111,121,'<?xml version=\"1.0\"?>\n<section>  <paragraph>The analysis will map the identity and beauty from the different places and show what the selected parts will have to offer the next time eZ systems go on a trip. Will the trip go to the mountains, the lake or perhaps to a deep and dark forest?</paragraph>\n  <paragraph>-Reports from World Tourism and Travel Council show that most members of the big eZ crew wants to go to Brasil and Mexico. Meanwhile the depth interviews showed that some of the members just wanted to stay at the office. A crew member, that wants to stay anonymous for his own safety, claims that he is afraid of leaving the office. Everyday a bunch of fans wait outside the company offices. ?They are like a mob? he says. It?s hard to be as hansom as me. I guess that is why the rest og the crew wants to travel. ?They are plain jealous?.</paragraph>\n  <paragraph>On the other hand he believes that the mountains would be a nice place to go. ?No fans there?. Investigations show that the eZ crew is ready to spend a lot of money, but only if the destination is interesting enough.  The language is no problem since one of the barriers for getting a job in eZ systems is that you speak 10-15 languages fluently. Information that has leaked out show that some might have interest in Sweden and India as well. We have not been able to confirm this yet.</paragraph>\n  <paragraph>A lot of work is still to be done before a decision can be done. We can only speculate on what the conclusions will be but we are sure when the crew leaves it will be interesting.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (290,'eng-GB',1,111,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (291,'eng-GB',1,111,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (292,'eng-GB',1,112,4,'Leisure',0,0);
INSERT INTO ezcontentobject_attribute VALUES (293,'eng-GB',1,112,119,'<?xml version=\"1.0\"?>\n<section />',0,0);
INSERT INTO ezcontentobject_attribute VALUES (294,'eng-GB',1,113,1,'Food for the soul',0,0);
INSERT INTO ezcontentobject_attribute VALUES (295,'eng-GB',1,113,120,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Soulfood.no is a result of a passionate interest for photography and people. This interesting site runs on eZ publish and is a very good example on what you can do with content and design on an eZ publish powered site.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (296,'eng-GB',1,113,121,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Christian Houge, b. 1972, is a freelance photographer educated in USA and have worked with advertising, portraits and travelling since 1994. \nOn his last travel, during the winter of 1999, Houge spend six months with the exile tibetanians in the South and North India as well as in Nepal. Many of his pictures are influenced by this visit. For long periods of this stay he lived in a tibetanian monastery as the only western representative and he got an insight in the daily life of the munks.</paragraph>\n  <paragraph>The design for this site is made by Sigurd Kristiansen Superstar.no and has been set up by one of eZ systems official partners Petraflux.com \neZ systems has assisted our partner with support. Automatical image import was created amongst other things.</paragraph>\n  <paragraph>Visit Soulfood</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (297,'eng-GB',1,113,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (298,'eng-GB',1,113,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (299,'eng-GB',1,114,1,'We did it again',0,0);
INSERT INTO ezcontentobject_attribute VALUES (300,'eng-GB',1,114,120,'<?xml version=\"1.0\"?>\n<section>  <paragraph>We did it again to beat Kings at Outfield which was earlier considered as one of our toughest match for the start of this season.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (301,'eng-GB',1,114,121,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Our persistence, desire and determination finally paid off when Doppers&apos;s 90th-minute and fifth League goal so far this season gave us not only the last laugh but also broad smiles all over the faces of Footballer team.</paragraph>\n  <paragraph>Yet to recover from the hangover in their rough &apos;Viking ride&apos; near the North Sea causing an early exit from Cup, Kings was rather stubborn to prove a point at Outfield. Their defence marshalled by Desy and Dill was like a &apos;Great Wall of China&apos; for Dopper and Hester to climb over it.</paragraph>\n  <paragraph>The first half seemed equally contested with couple of exchanges from both sides. Kjell and his staff must have injected more fuel to the belly of the players to increase their fire intensity. The team ran out from the tunnel in the second half with more desire to win, especially when Barton was introduced into the game 20 minutes before time.</paragraph>\n  <paragraph>Girro should have scored a spectacular goal in the 52nd minute when his 30-yard goal bounding shot beat Cudic but not the crossbar. Baros was unfortunate not to score for us when his surging run forward caused a lot of problems to their defence. It was only when Dippel came in to replace the tired Murphy that we ignited some sparks in our attack. Simply great to see another defence splitting pass from Dippel to Hester who was also unfortunate not to be in the scoresheet when Cudic made a fine save. Fortunately Dopper was so quick to follow up and slammed the ball into the back of the net.</paragraph>\n  <paragraph>Besides Stevenson, Kjell must have developed another defence splitting passer in Dippel to provide those &apos;special key&apos;s&apos; to strikers to unlock stubborn defences. The totally different reaction between Kjell and Roney when Doppel hit the back of the net said it all.</paragraph>\n  <paragraph>Let&apos;s hope to have a injury free international games break this week end to travel to Highend Road next Saturday. Come on Reds!</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (302,'eng-GB',1,114,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (303,'eng-GB',1,114,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (299,'eng-GB',2,114,1,'We did it again',0,0);
INSERT INTO ezcontentobject_attribute VALUES (300,'eng-GB',2,114,120,'<?xml version=\"1.0\"?>\n<section>  <paragraph>We did it again to beat Kings at Outfield which was earlier considered as one of our toughest match for the start of this season.</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (301,'eng-GB',2,114,121,'<?xml version=\"1.0\"?>\n<section>  <paragraph>Our persistence, desire and determination finally paid off when Doppers&apos;s 90th-minute and fifth League goal so far this season gave us not only the last laugh but also broad smiles all over the faces of Footballer team.</paragraph>\n  <paragraph>Yet to recover from the hangover in their rough &apos;Viking ride&apos; near the North Sea causing an early exit from Cup, Kings was rather stubborn to prove a point at Outfield. Their defence marshalled by Desy and Dill was like a &apos;Great Wall of China&apos; for Dopper and Hester to climb over it.</paragraph>\n  <paragraph>The first half seemed equally contested with couple of exchanges from both sides. Kjell and his staff must have injected more fuel to the belly of the players to increase their fire intensity. The team ran out from the tunnel in the second half with more desire to win, especially when Barton was introduced into the game 20 minutes before time.</paragraph>\n  <paragraph>Girro should have scored a spectacular goal in the 52nd minute when his 30-yard goal bounding shot beat Cudic but not the crossbar. Barton was unfortunate not to score for us when his surging run forward caused a lot of problems to their defence. It was only when Dippel came in to replace the tired Murphy that we ignited some sparks in our attack. Simply great to see another defence splitting pass from Dippel to Hester who was also unfortunate not to be in the scoresheet when Cudic made a fine save. Fortunately Dopper was so quick to follow up and slammed the ball into the back of the net.</paragraph>\n  <paragraph>Besides Stevenson, Kjell must have developed another defence splitting passer in Dippel to provide those &apos;special key&apos;s&apos; to strikers to unlock stubborn defences. The totally different reaction between Kjell and Roney when Doppel hit the back of the net said it all.</paragraph>\n  <paragraph>Let&apos;s hope to have a injury free international games break this week end to travel to Highend Road next Saturday. Come on Reds!</paragraph>\n</section>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (302,'eng-GB',2,114,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (303,'eng-GB',2,114,123,'',0,0);

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
  sort_order int(1) default '1',
  sort_field int(11) default '1',
  priority int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id),
  KEY ezcontentobject_tree_path (path_string),
  KEY ezcontentobject_tree_p_node_id (parent_node_id),
  KEY ezcontentobject_tree_co_id (contentobject_id),
  KEY ezcontentobject_tree_depth (depth)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject_tree'
#

INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,NULL,0,'/1/',NULL,1,16,NULL,1,1,0);
INSERT INTO ezcontentobject_tree VALUES (2,1,1,23,1,1360594808,1,'/1/2/','',2,7,'frontpage',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,NULL,NULL,1,'/1/5/',NULL,8,15,NULL,1,1,0);
INSERT INTO ezcontentobject_tree VALUES (16,2,15,5,1,-571349768,2,'/1/2/16/','',0,0,'frontpage20/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (11,5,10,1,1,-1609495635,2,'/1/5/11/','',0,0,'users/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (12,5,11,1,1,-1609495635,2,'/1/5/12/','',0,0,'users/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (13,5,12,1,1,-1609495635,2,'/1/5/13/','',0,0,'users/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (14,5,13,1,1,-1609495635,2,'/1/5/14/','',0,0,'users/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (15,13,14,1,1,934329528,3,'/1/5/13/15/','',0,0,'users/administrator_users/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (96,92,97,2,1,-1844174515,4,'/1/2/16/92/96/','',0,0,'frontpage20/white_box/water/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (18,16,17,4,1,643451801,3,'/1/2/16/18/','',0,0,'frontpage20/white_box/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (95,92,96,2,1,-1844174515,4,'/1/2/16/92/95/','',0,0,'frontpage20/white_box/water/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (24,2,23,1,1,-571349768,2,'/1/2/24/','',0,0,'frontpage20/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (26,24,25,3,1,-1284751361,3,'/1/2/24/26/','',0,0,'frontpage20/news/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (27,24,26,3,1,-1284751361,3,'/1/2/24/27/','',0,0,'frontpage20/news/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (92,16,93,1,1,643451801,3,'/1/2/16/92/','',0,0,'frontpage20/white_box/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (30,24,29,3,1,-1284751361,3,'/1/2/24/30/','',0,0,'frontpage20/news/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (31,2,30,1,1,-571349768,2,'/1/2/31/','',0,0,'frontpage20/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (32,31,31,1,1,-1289518249,3,'/1/2/31/32/','',0,0,'frontpage20/crossroads_forum/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (91,83,92,2,1,-465929533,4,'/1/2/16/83/91/','',0,0,'frontpage20/white_box/forrest/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (90,83,91,2,1,-465929533,4,'/1/2/16/83/90/','',0,0,'frontpage20/white_box/forrest/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (89,83,90,2,1,-465929533,4,'/1/2/16/83/89/','',0,0,'frontpage20/white_box/forrest/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (88,18,89,2,1,718233458,4,'/1/2/16/18/88/','',0,0,'frontpage20/white_box/gallery/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (55,2,57,1,1,-571349768,2,'/1/2/55/','',0,0,'frontpage20/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (93,92,94,3,1,-1844174515,4,'/1/2/16/92/93/','',0,0,'frontpage20/white_box/water/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (94,92,95,2,1,-1844174515,4,'/1/2/16/92/94/','',0,0,'frontpage20/white_box/water/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (60,2,62,1,1,-571349768,2,'/1/2/60/','',0,0,'frontpage20/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (61,60,63,1,1,-1241045258,3,'/1/2/60/61/','',0,0,'frontpage20/the_book_corner/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (62,60,64,1,1,-1241045258,3,'/1/2/60/62/','',0,0,'frontpage20/the_book_corner/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (63,60,65,1,1,-1241045258,3,'/1/2/60/63/','',0,0,'frontpage20/the_book_corner/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (64,60,66,1,1,-1241045258,3,'/1/2/60/64/','',0,0,'frontpage20/the_book_corner/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (65,60,67,1,1,-1241045258,3,'/1/2/60/65/','',0,0,'frontpage20/the_book_corner/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (86,18,87,3,1,718233458,4,'/1/2/16/18/86/','',0,0,'frontpage20/white_box/gallery/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (87,18,88,2,1,718233458,4,'/1/2/16/18/87/','',0,0,'frontpage20/white_box/gallery/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (82,16,83,4,1,643451801,3,'/1/2/16/82/','',0,0,'frontpage20/white_box/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (83,16,84,5,1,643451801,3,'/1/2/16/83/','',0,0,'frontpage20/white_box/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (84,83,85,3,1,-465929533,4,'/1/2/16/83/84/','',0,0,'frontpage20/white_box/forrest/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (85,18,86,5,1,718233458,4,'/1/2/16/18/85/','',0,0,'frontpage20/white_box/gallery/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (97,16,98,1,1,643451801,3,'/1/2/16/97/','',0,0,'frontpage20/white_box/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (98,97,99,2,1,342912952,4,'/1/2/16/97/98/','',0,0,'frontpage20/white_box/animals/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (99,97,100,2,1,342912952,4,'/1/2/16/97/99/','',0,0,'frontpage20/white_box/animals/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (100,97,101,2,1,342912952,4,'/1/2/16/97/100/','',0,0,'frontpage20/white_box/animals/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (101,97,102,2,1,342912952,4,'/1/2/16/97/101/','',0,0,'frontpage20/white_box/animals/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (102,16,103,1,1,643451801,3,'/1/2/16/102/','',0,0,'frontpage20/white_box/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (103,102,104,2,1,-415812034,4,'/1/2/16/102/103/','',0,0,'frontpage20/white_box/landscape/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (104,102,105,2,1,-415812034,4,'/1/2/16/102/104/','',0,0,'frontpage20/white_box/landscape/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (105,102,106,2,1,-415812034,4,'/1/2/16/102/105/','',0,0,'frontpage20/white_box/landscape/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (106,102,107,2,1,-415812034,4,'/1/2/16/102/106/','',0,0,'frontpage20/white_box/landscape/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (107,30,109,3,1,-232232099,4,'/1/2/24/30/107/','',0,0,'frontpage20/news/world_news/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (108,24,110,1,1,-1284751361,3,'/1/2/24/108/','',0,0,'frontpage20/news/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (109,108,111,1,1,1401579584,4,'/1/2/24/108/109/','',0,0,'frontpage20/news/action/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (110,24,112,1,1,-1284751361,3,'/1/2/24/110/','',0,0,'frontpage20/news/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (111,110,113,1,1,1117777345,4,'/1/2/24/110/111/','',0,0,'frontpage20/news/leisure/',1,1,0);
INSERT INTO ezcontentobject_tree VALUES (112,27,114,2,1,1615423658,4,'/1/2/24/27/112/','',0,0,'frontpage20/news/sport/',1,1,0);

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
INSERT INTO ezcontentobject_version VALUES (443,15,14,1,1033922609,1033922626,0,0,0);
INSERT INTO ezcontentobject_version VALUES (436,1,8,2,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version VALUES (438,10,8,1,1033920649,1033920665,0,0,0);
INSERT INTO ezcontentobject_version VALUES (439,11,8,1,1033920737,1033920746,0,0,0);
INSERT INTO ezcontentobject_version VALUES (440,12,8,1,1033920760,1033920775,0,0,0);
INSERT INTO ezcontentobject_version VALUES (441,13,8,1,1033920786,1033920794,0,0,0);
INSERT INTO ezcontentobject_version VALUES (442,14,8,1,1033920808,1033920830,0,0,0);
INSERT INTO ezcontentobject_version VALUES (546,92,14,1,1035886972,1035886989,0,0,0);
INSERT INTO ezcontentobject_version VALUES (445,17,14,1,1033923938,1033923953,0,0,0);
INSERT INTO ezcontentobject_version VALUES (545,91,14,1,1035886937,1035886955,0,0,0);
INSERT INTO ezcontentobject_version VALUES (521,17,14,2,1034327248,1034327257,0,0,0);
INSERT INTO ezcontentobject_version VALUES (547,93,14,1,1035887027,1035887037,0,0,0);
INSERT INTO ezcontentobject_version VALUES (455,15,14,2,1034085521,1034085521,0,0,0);
INSERT INTO ezcontentobject_version VALUES (456,15,14,3,1034165834,1034165834,0,0,0);
INSERT INTO ezcontentobject_version VALUES (457,23,14,1,1034174426,1034174464,0,0,0);
INSERT INTO ezcontentobject_version VALUES (507,72,14,1,1034260349,1034260496,0,0,0);
INSERT INTO ezcontentobject_version VALUES (459,25,14,1,1034175645,1034175666,0,0,0);
INSERT INTO ezcontentobject_version VALUES (460,26,14,1,1034175689,1034175704,0,0,0);
INSERT INTO ezcontentobject_version VALUES (539,87,14,1,1035886704,1035886716,0,0,0);
INSERT INTO ezcontentobject_version VALUES (463,29,14,1,1034175841,1034175855,0,0,0);
INSERT INTO ezcontentobject_version VALUES (464,30,14,1,1034181778,1034181792,0,0,0);
INSERT INTO ezcontentobject_version VALUES (465,31,14,1,1034181817,1034181825,0,0,0);
INSERT INTO ezcontentobject_version VALUES (540,88,14,1,1035886738,1035886750,0,0,0);
INSERT INTO ezcontentobject_version VALUES (541,89,14,1,1035886776,1035886785,0,0,0);
INSERT INTO ezcontentobject_version VALUES (538,86,14,1,1035886665,1035886676,0,0,0);
INSERT INTO ezcontentobject_version VALUES (536,85,14,1,1035883336,1035883390,0,0,0);
INSERT INTO ezcontentobject_version VALUES (543,85,14,2,1035886855,1035886880,0,0,0);
INSERT INTO ezcontentobject_version VALUES (473,39,10,1,1034185655,1034185655,0,0,0);
INSERT INTO ezcontentobject_version VALUES (542,17,14,4,1035886806,1035886818,0,0,0);
INSERT INTO ezcontentobject_version VALUES (477,43,10,1,1034186555,1034186575,0,0,0);
INSERT INTO ezcontentobject_version VALUES (479,45,10,1,1034186972,1034186992,0,0,0);
INSERT INTO ezcontentobject_version VALUES (480,46,10,1,1034187112,1034187189,0,0,0);
INSERT INTO ezcontentobject_version VALUES (481,47,10,1,1034187190,1034187441,0,0,0);
INSERT INTO ezcontentobject_version VALUES (528,26,14,3,1034334712,1034334718,0,0,0);
INSERT INTO ezcontentobject_version VALUES (527,26,14,2,1034334687,1034334704,0,0,0);
INSERT INTO ezcontentobject_version VALUES (529,29,14,2,1034334737,1034334754,0,0,0);
INSERT INTO ezcontentobject_version VALUES (491,57,14,1,1034190860,1034190865,0,0,0);
INSERT INTO ezcontentobject_version VALUES (496,62,14,1,1034251114,1034251246,0,0,0);
INSERT INTO ezcontentobject_version VALUES (497,63,14,1,1034252121,1034252134,0,0,0);
INSERT INTO ezcontentobject_version VALUES (498,64,14,1,1034252246,1034252256,0,0,0);
INSERT INTO ezcontentobject_version VALUES (499,65,14,1,1034252467,1034252479,0,0,0);
INSERT INTO ezcontentobject_version VALUES (500,66,14,1,1034252577,1034252585,0,0,0);
INSERT INTO ezcontentobject_version VALUES (501,67,14,1,1034253534,1034253541,0,0,0);
INSERT INTO ezcontentobject_version VALUES (535,84,14,1,1035883306,1035883322,0,0,0);
INSERT INTO ezcontentobject_version VALUES (534,83,14,1,1035882282,1035882318,0,0,0);
INSERT INTO ezcontentobject_version VALUES (504,70,14,1,1034259411,1034259505,0,0,0);
INSERT INTO ezcontentobject_version VALUES (526,25,14,3,1034334672,1034334677,0,0,0);
INSERT INTO ezcontentobject_version VALUES (525,25,14,2,1034334639,1034334649,0,0,0);
INSERT INTO ezcontentobject_version VALUES (508,72,14,2,1034261081,1034261100,0,0,0);
INSERT INTO ezcontentobject_version VALUES (544,90,14,1,1035886903,1035886921,0,0,0);
INSERT INTO ezcontentobject_version VALUES (530,29,14,3,1034334761,1034334767,0,0,0);
INSERT INTO ezcontentobject_version VALUES (511,72,14,3,1034263205,1034263215,0,0,0);
INSERT INTO ezcontentobject_version VALUES (512,72,14,4,1034263225,1034263232,0,0,0);
INSERT INTO ezcontentobject_version VALUES (513,72,14,5,1034263570,1034263585,0,0,0);
INSERT INTO ezcontentobject_version VALUES (514,72,14,6,1034264271,1034264283,0,0,0);
INSERT INTO ezcontentobject_version VALUES (515,72,14,7,1034264305,1034264315,0,0,0);
INSERT INTO ezcontentobject_version VALUES (516,72,14,8,1034264329,1034264345,0,0,0);
INSERT INTO ezcontentobject_version VALUES (517,72,14,9,1034264429,1034264438,0,0,0);
INSERT INTO ezcontentobject_version VALUES (537,17,14,3,1035886633,1035886654,0,0,0);
INSERT INTO ezcontentobject_version VALUES (548,94,14,1,1035887054,1035887067,0,0,0);
INSERT INTO ezcontentobject_version VALUES (549,94,14,2,1035887116,1035887130,0,0,0);
INSERT INTO ezcontentobject_version VALUES (550,95,14,1,1035887149,1035887163,0,0,0);
INSERT INTO ezcontentobject_version VALUES (551,96,14,1,1035887181,1035887194,0,0,0);
INSERT INTO ezcontentobject_version VALUES (552,97,14,1,1035887212,1035887226,0,0,0);
INSERT INTO ezcontentobject_version VALUES (553,98,14,1,1035887239,1035887250,0,0,0);
INSERT INTO ezcontentobject_version VALUES (554,99,14,1,1035887258,1035887271,0,0,0);
INSERT INTO ezcontentobject_version VALUES (555,100,14,1,1035887286,1035887300,0,0,0);
INSERT INTO ezcontentobject_version VALUES (556,101,14,1,1035887318,1035887347,0,0,0);
INSERT INTO ezcontentobject_version VALUES (557,102,14,1,1035887362,1035887382,0,0,0);
INSERT INTO ezcontentobject_version VALUES (558,103,14,1,1035887418,1035887800,0,0,0);
INSERT INTO ezcontentobject_version VALUES (559,104,14,1,1035887808,1035887825,0,0,0);
INSERT INTO ezcontentobject_version VALUES (560,105,14,1,1035887841,1035887862,0,0,0);
INSERT INTO ezcontentobject_version VALUES (561,106,14,1,1035887890,1035887919,0,0,0);
INSERT INTO ezcontentobject_version VALUES (562,107,14,1,1035887930,1035887954,0,0,0);
INSERT INTO ezcontentobject_version VALUES (563,104,14,2,1035888016,1035888035,0,0,0);
INSERT INTO ezcontentobject_version VALUES (564,105,14,2,1035888046,1035888065,0,0,0);
INSERT INTO ezcontentobject_version VALUES (565,106,14,2,1035888075,1035888094,0,0,0);
INSERT INTO ezcontentobject_version VALUES (566,107,14,2,1035888103,1035888131,0,0,0);
INSERT INTO ezcontentobject_version VALUES (567,17,14,5,1035888142,1035888142,0,0,0);
INSERT INTO ezcontentobject_version VALUES (568,17,14,6,1035888148,1035888148,0,0,0);
INSERT INTO ezcontentobject_version VALUES (569,86,14,2,1035888164,1035888164,0,0,0);
INSERT INTO ezcontentobject_version VALUES (570,86,14,3,1035888182,1035888182,0,0,0);
INSERT INTO ezcontentobject_version VALUES (571,86,14,4,1035888190,1035888214,0,0,0);
INSERT INTO ezcontentobject_version VALUES (572,87,14,2,1035888223,1035888242,0,0,0);
INSERT INTO ezcontentobject_version VALUES (573,88,14,2,1035888251,1035888271,0,0,0);
INSERT INTO ezcontentobject_version VALUES (574,89,14,2,1035888281,1035888302,0,0,0);
INSERT INTO ezcontentobject_version VALUES (575,84,14,2,1035888323,1035888332,0,0,0);
INSERT INTO ezcontentobject_version VALUES (576,85,14,3,1035888339,1035888358,0,0,0);
INSERT INTO ezcontentobject_version VALUES (577,90,14,2,1035888370,1035888387,0,0,0);
INSERT INTO ezcontentobject_version VALUES (578,91,14,2,1035888395,1035888410,0,0,0);
INSERT INTO ezcontentobject_version VALUES (579,92,14,2,1035888423,1035888444,0,0,0);
INSERT INTO ezcontentobject_version VALUES (580,94,14,3,1035888462,1035888486,0,0,0);
INSERT INTO ezcontentobject_version VALUES (581,95,14,2,1035888494,1035888527,0,0,0);
INSERT INTO ezcontentobject_version VALUES (582,96,14,2,1035888534,1035888554,0,0,0);
INSERT INTO ezcontentobject_version VALUES (583,97,14,2,1035888561,1035888616,0,0,0);
INSERT INTO ezcontentobject_version VALUES (584,101,14,2,1035888631,1035888654,0,0,0);
INSERT INTO ezcontentobject_version VALUES (585,102,14,2,1035888666,1035888685,0,0,0);
INSERT INTO ezcontentobject_version VALUES (586,99,14,2,1035888693,1035888720,0,0,0);
INSERT INTO ezcontentobject_version VALUES (587,100,14,2,1035888727,1035888750,0,0,0);
INSERT INTO ezcontentobject_version VALUES (589,84,14,3,1035892129,1035892129,0,0,0);
INSERT INTO ezcontentobject_version VALUES (590,84,14,4,1035892149,1035892149,0,0,0);
INSERT INTO ezcontentobject_version VALUES (591,84,14,5,1035892768,1035892777,0,0,0);
INSERT INTO ezcontentobject_version VALUES (593,86,14,5,1035892910,1035892919,0,0,0);
INSERT INTO ezcontentobject_version VALUES (594,87,14,3,1035892930,1035892937,0,0,0);
INSERT INTO ezcontentobject_version VALUES (596,15,14,4,1035893160,1035893191,0,0,0);
INSERT INTO ezcontentobject_version VALUES (597,15,14,5,1035893219,1035893229,0,0,0);
INSERT INTO ezcontentobject_version VALUES (598,83,14,2,1035893236,1035893387,0,0,0);
INSERT INTO ezcontentobject_version VALUES (599,83,14,3,1035904363,1035904363,0,0,0);
INSERT INTO ezcontentobject_version VALUES (600,108,14,1,1035904425,1035904425,0,0,0);
INSERT INTO ezcontentobject_version VALUES (601,83,14,4,1035904448,1035904493,0,0,0);
INSERT INTO ezcontentobject_version VALUES (602,109,14,1,1035905574,1035905620,0,0,0);
INSERT INTO ezcontentobject_version VALUES (603,109,14,2,1035905688,1035905688,0,0,0);
INSERT INTO ezcontentobject_version VALUES (604,109,14,3,1035905702,1035905739,0,0,0);
INSERT INTO ezcontentobject_version VALUES (605,110,14,1,1035905757,1035905815,0,0,0);
INSERT INTO ezcontentobject_version VALUES (606,111,14,1,1035905824,1035905861,0,0,0);
INSERT INTO ezcontentobject_version VALUES (607,112,14,1,1035905921,1035905944,0,0,0);
INSERT INTO ezcontentobject_version VALUES (608,113,14,1,1035905959,1035905996,0,0,0);
INSERT INTO ezcontentobject_version VALUES (609,114,14,1,1035906484,1035906797,0,0,0);
INSERT INTO ezcontentobject_version VALUES (610,114,14,2,1035906821,1035906867,0,0,0);

#
# Table structure for table 'ezenumobjectvalue'
#

CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumid int(11) NOT NULL default '0',
  enumelement varchar(255) default NULL,
  enumvalue varchar(255) default NULL,
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid)
) TYPE=MyISAM;

#
# Dumping data for table 'ezenumobjectvalue'
#

INSERT INTO ezenumobjectvalue VALUES (157,1,3,'5','5');

#
# Table structure for table 'ezenumvalue'
#

CREATE TABLE ezenumvalue (
  id int(11) NOT NULL auto_increment,
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) default NULL,
  enumvalue varchar(255) default NULL,
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version)
) TYPE=MyISAM;

#
# Dumping data for table 'ezenumvalue'
#

INSERT INTO ezenumvalue VALUES (3,150,0,'5','5',3);
INSERT INTO ezenumvalue VALUES (2,150,0,'3','3',2);
INSERT INTO ezenumvalue VALUES (1,150,0,'2','2',1);

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

INSERT INTO ezimage VALUES (228,1,'DSNsUt.jpg','Forest2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (231,1,'NiykNJ.jpg','Forest3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (169,1,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (213,2,'a72o2K.jpg','Forest1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (225,1,'0FYhVQ.jpg','Flower4.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (222,1,'xUQGvP.jpg','Flower3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (219,1,'whm1Qu.jpg','Flower2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (213,1,'RDYut1.jpg','Flower1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (216,1,'knuUKQ.jpg','Flower1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (169,2,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,3,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,4,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,5,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,6,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,7,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,8,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,9,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (234,1,'sPRLs6.jpg','Forest4.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (239,1,'sIRnN3.jpg','Water1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (239,2,'XUbmMp.jpg','Water2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (242,1,'oZw9Q4.jpg','Water3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (245,1,'Yc1rQr.jpg','Water4.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (248,1,'YwIaai.jpg','Water6.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (253,1,'NQspGB.jpg','Animal1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (256,1,'Kmq7Dt.jpg','Animal2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (259,1,'GmifVM.jpg','Animal3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (262,1,'uSWNNw.jpg','Animal5.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (267,1,'5Pn1q0.jpg','Landscape1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (270,1,'3OeOi3.jpg','Landscape2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (273,1,'UGwCqD.jpg','Landscape9.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (276,1,'JFFPeo.jpg','Landscape7.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (267,2,'5Pn1q0.jpg','Landscape1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (270,2,'3OeOi3.jpg','Landscape2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (273,2,'UGwCqD.jpg','Landscape9.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (276,2,'JFFPeo.jpg','Landscape7.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (216,2,'knuUKQ.jpg','Flower1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (216,3,'knuUKQ.jpg','Flower1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (216,4,'knuUKQ.jpg','Flower1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (219,2,'whm1Qu.jpg','Flower2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (222,2,'xUQGvP.jpg','Flower3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (225,2,'0FYhVQ.jpg','Flower4.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (213,3,'a72o2K.jpg','Forest1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (228,2,'DSNsUt.jpg','Forest2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (231,2,'NiykNJ.jpg','Forest3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (234,2,'sPRLs6.jpg','Forest4.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (239,3,'XUbmMp.jpg','Water2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (242,2,'oZw9Q4.jpg','Water3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (245,2,'Yc1rQr.jpg','Water4.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (248,2,'YwIaai.jpg','Water6.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (259,2,'GmifVM.jpg','Animal3.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (262,2,'uSWNNw.jpg','Animal5.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (253,2,'NQspGB.jpg','Animal1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (256,2,'Kmq7Dt.jpg','Animal2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (216,5,'knuUKQ.jpg','Flower1.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (219,3,'whm1Qu.jpg','Flower2.jpg','image/pjpeg');
INSERT INTO ezimage VALUES (208,4,'XROWkC.jpg','Water8.jpg','image/jpeg');
INSERT INTO ezimage VALUES (283,1,'8JCcrj.jpg','Artikkel1a.jpg','image/jpeg');
INSERT INTO ezimage VALUES (283,2,'8JCcrj.jpg','Artikkel1a.jpg','image/jpeg');
INSERT INTO ezimage VALUES (283,3,'8JCcrj.jpg','Artikkel1a.jpg','image/jpeg');
INSERT INTO ezimage VALUES (290,1,'XQ1T5V.jpg','Artikkel2a.jpg','image/jpeg');
INSERT INTO ezimage VALUES (297,1,'bqOnp1.jpg','Artikkel3a.jpg','image/jpeg');
INSERT INTO ezimage VALUES (302,1,'Mm158b.jpg','ball.jpg','image/jpeg');
INSERT INTO ezimage VALUES (302,2,'Mm158b.jpg','ball.jpg','image/jpeg');

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

INSERT INTO ezimagevariation VALUES (234,1,'sPRLs6_600x600_234.jpg','s/P/R/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (228,1,'DSNsUt_600x600_228.jpg','D/S/N/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (231,1,'NiykNJ_600x600_231.jpg','N/i/y/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (219,1,'whm1Qu_100x100_219.jpg','w/h/m/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (222,1,'xUQGvP_100x100_222.jpg','x/U/Q/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (213,2,'a72o2K_600x600_213.jpg','a/7/2/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (216,1,'knuUKQ_100x100_216.jpg','k/n/u/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (169,1,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,400,273);
INSERT INTO ezimagevariation VALUES (225,1,'0FYhVQ_150x150_225.jpg','0/F/Y/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (222,1,'xUQGvP_150x150_222.jpg','x/U/Q/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (219,1,'whm1Qu_150x150_219.jpg','w/h/m/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (169,1,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (216,1,'knuUKQ_150x150_216.jpg','k/n/u/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (225,1,'0FYhVQ_600x600_225.jpg','0/F/Y/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (213,1,'RDYut1_600x600_213.jpg','R/D/Y/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (213,1,'RDYut1_150x150_213.jpg','R/D/Y/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (213,1,'RDYut1_100x100_213.jpg','R/D/Y/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (216,1,'knuUKQ_600x600_216.jpg','k/n/u/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (219,1,'whm1Qu_600x600_219.jpg','w/h/m/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (222,1,'xUQGvP_600x600_222.jpg','x/U/Q/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (169,1,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,60,40);
INSERT INTO ezimagevariation VALUES (169,2,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,60,40);
INSERT INTO ezimagevariation VALUES (169,2,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,400,273);
INSERT INTO ezimagevariation VALUES (169,2,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (169,3,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,0,0);
INSERT INTO ezimagevariation VALUES (169,3,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,0,0);
INSERT INTO ezimagevariation VALUES (169,4,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,0,0);
INSERT INTO ezimagevariation VALUES (169,4,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,0,0);
INSERT INTO ezimagevariation VALUES (169,4,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (169,5,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,60,40);
INSERT INTO ezimagevariation VALUES (169,5,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,400,273);
INSERT INTO ezimagevariation VALUES (169,5,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (169,6,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,60,40);
INSERT INTO ezimagevariation VALUES (169,6,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (169,7,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,60,40);
INSERT INTO ezimagevariation VALUES (169,7,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,400,273);
INSERT INTO ezimagevariation VALUES (169,8,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,60,40);
INSERT INTO ezimagevariation VALUES (169,8,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,400,273);
INSERT INTO ezimagevariation VALUES (169,8,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (169,9,'sZd6o7_60x60_169.jpg','s/Z/d/',60,60,60,40);
INSERT INTO ezimagevariation VALUES (169,9,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,400,273);
INSERT INTO ezimagevariation VALUES (169,9,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (239,1,'sIRnN3_600x600_239.jpg','s/I/R/',600,600,400,194);
INSERT INTO ezimagevariation VALUES (225,1,'0FYhVQ_100x100_225.jpg','0/F/Y/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (239,1,'sIRnN3_150x150_239.jpg','s/I/R/',150,150,150,72);
INSERT INTO ezimagevariation VALUES (239,1,'sIRnN3_100x100_239.jpg','s/I/R/',100,100,100,48);
INSERT INTO ezimagevariation VALUES (213,2,'a72o2K_150x150_213.jpg','a/7/2/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (228,1,'DSNsUt_150x150_228.jpg','D/S/N/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (231,1,'NiykNJ_150x150_231.jpg','N/i/y/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (234,1,'sPRLs6_150x150_234.jpg','s/P/R/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (239,2,'XUbmMp_600x600_239.jpg','X/U/b/',600,600,400,259);
INSERT INTO ezimagevariation VALUES (239,2,'XUbmMp_150x150_239.jpg','X/U/b/',150,150,150,97);
INSERT INTO ezimagevariation VALUES (242,1,'oZw9Q4_150x150_242.jpg','o/Z/w/',150,150,150,99);
INSERT INTO ezimagevariation VALUES (242,1,'oZw9Q4_600x600_242.jpg','o/Z/w/',600,600,400,265);
INSERT INTO ezimagevariation VALUES (213,2,'a72o2K_100x100_213.jpg','a/7/2/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (228,1,'DSNsUt_100x100_228.jpg','D/S/N/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (231,1,'NiykNJ_100x100_231.jpg','N/i/y/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (234,1,'sPRLs6_100x100_234.jpg','s/P/R/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (245,1,'Yc1rQr_600x600_245.jpg','Y/c/1/',600,600,400,250);
INSERT INTO ezimagevariation VALUES (248,1,'YwIaai_600x600_248.jpg','Y/w/I/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (253,1,'NQspGB_600x600_253.jpg','N/Q/s/',600,600,400,299);
INSERT INTO ezimagevariation VALUES (245,1,'Yc1rQr_150x150_245.jpg','Y/c/1/',150,150,150,93);
INSERT INTO ezimagevariation VALUES (248,1,'YwIaai_150x150_248.jpg','Y/w/I/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (253,1,'NQspGB_150x150_253.jpg','N/Q/s/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (256,1,'Kmq7Dt_150x150_256.jpg','K/m/q/',150,150,150,97);
INSERT INTO ezimagevariation VALUES (256,1,'Kmq7Dt_600x600_256.jpg','K/m/q/',600,600,400,259);
INSERT INTO ezimagevariation VALUES (259,1,'GmifVM_600x600_259.jpg','G/m/i/',600,600,400,260);
INSERT INTO ezimagevariation VALUES (262,1,'uSWNNw_600x600_262.jpg','u/S/W/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (267,1,'5Pn1q0_600x600_267.jpg','5/P/n/',600,600,400,252);
INSERT INTO ezimagevariation VALUES (270,1,'3OeOi3_600x600_270.jpg','3/O/e/',600,600,400,258);
INSERT INTO ezimagevariation VALUES (267,1,'5Pn1q0_150x150_267.jpg','5/P/n/',150,150,150,94);
INSERT INTO ezimagevariation VALUES (270,1,'3OeOi3_150x150_270.jpg','3/O/e/',150,150,150,96);
INSERT INTO ezimagevariation VALUES (273,1,'UGwCqD_600x600_273.jpg','U/G/w/',600,600,400,253);
INSERT INTO ezimagevariation VALUES (273,1,'UGwCqD_150x150_273.jpg','U/G/w/',150,150,150,94);
INSERT INTO ezimagevariation VALUES (276,1,'JFFPeo_600x600_276.jpg','J/F/F/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (276,1,'JFFPeo_150x150_276.jpg','J/F/F/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (259,1,'GmifVM_150x150_259.jpg','G/m/i/',150,150,150,97);
INSERT INTO ezimagevariation VALUES (262,1,'uSWNNw_150x150_262.jpg','u/S/W/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (267,2,'5Pn1q0_100x100_267.jpg','5/P/n/',100,100,100,63);
INSERT INTO ezimagevariation VALUES (270,1,'3OeOi3_100x100_270.jpg','3/O/e/',100,100,100,64);
INSERT INTO ezimagevariation VALUES (267,2,'5Pn1q0_600x600_267.jpg','5/P/n/',600,600,400,252);
INSERT INTO ezimagevariation VALUES (273,1,'UGwCqD_100x100_273.jpg','U/G/w/',100,100,100,63);
INSERT INTO ezimagevariation VALUES (276,1,'JFFPeo_100x100_276.jpg','J/F/F/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (267,2,'5Pn1q0_150x150_267.jpg','5/P/n/',150,150,150,94);
INSERT INTO ezimagevariation VALUES (270,2,'3OeOi3_100x100_270.jpg','3/O/e/',100,100,100,64);
INSERT INTO ezimagevariation VALUES (270,2,'3OeOi3_600x600_270.jpg','3/O/e/',600,600,400,258);
INSERT INTO ezimagevariation VALUES (270,2,'3OeOi3_150x150_270.jpg','3/O/e/',150,150,150,96);
INSERT INTO ezimagevariation VALUES (273,2,'UGwCqD_100x100_273.jpg','U/G/w/',100,100,100,63);
INSERT INTO ezimagevariation VALUES (273,2,'UGwCqD_600x600_273.jpg','U/G/w/',600,600,400,253);
INSERT INTO ezimagevariation VALUES (276,2,'JFFPeo_100x100_276.jpg','J/F/F/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (273,2,'UGwCqD_150x150_273.jpg','U/G/w/',150,150,150,94);
INSERT INTO ezimagevariation VALUES (276,2,'JFFPeo_600x600_276.jpg','J/F/F/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (259,1,'GmifVM_100x100_259.jpg','G/m/i/',100,100,100,65);
INSERT INTO ezimagevariation VALUES (262,1,'uSWNNw_100x100_262.jpg','u/S/W/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (253,1,'NQspGB_100x100_253.jpg','N/Q/s/',100,100,100,74);
INSERT INTO ezimagevariation VALUES (256,1,'Kmq7Dt_100x100_256.jpg','K/m/q/',100,100,100,64);
INSERT INTO ezimagevariation VALUES (216,2,'knuUKQ_100x100_216.jpg','k/n/u/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (276,2,'JFFPeo_150x150_276.jpg','J/F/F/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (216,4,'knuUKQ_100x100_216.jpg','k/n/u/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (216,4,'knuUKQ_600x600_216.jpg','k/n/u/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (219,2,'whm1Qu_100x100_219.jpg','w/h/m/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (219,2,'whm1Qu_600x600_219.jpg','w/h/m/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (222,2,'xUQGvP_100x100_222.jpg','x/U/Q/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (222,2,'xUQGvP_600x600_222.jpg','x/U/Q/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (225,2,'0FYhVQ_100x100_225.jpg','0/F/Y/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (225,2,'0FYhVQ_600x600_225.jpg','0/F/Y/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (213,3,'a72o2K_100x100_213.jpg','a/7/2/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (213,3,'a72o2K_600x600_213.jpg','a/7/2/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (228,2,'DSNsUt_100x100_228.jpg','D/S/N/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (228,2,'DSNsUt_600x600_228.jpg','D/S/N/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (231,2,'NiykNJ_100x100_231.jpg','N/i/y/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (231,2,'NiykNJ_600x600_231.jpg','N/i/y/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (234,2,'sPRLs6_100x100_234.jpg','s/P/R/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (234,2,'sPRLs6_600x600_234.jpg','s/P/R/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (239,3,'XUbmMp_100x100_239.jpg','X/U/b/',100,100,100,64);
INSERT INTO ezimagevariation VALUES (239,3,'XUbmMp_600x600_239.jpg','X/U/b/',600,600,400,259);
INSERT INTO ezimagevariation VALUES (242,2,'oZw9Q4_100x100_242.jpg','o/Z/w/',100,100,100,66);
INSERT INTO ezimagevariation VALUES (242,2,'oZw9Q4_600x600_242.jpg','o/Z/w/',600,600,400,265);
INSERT INTO ezimagevariation VALUES (245,2,'Yc1rQr_100x100_245.jpg','Y/c/1/',100,100,100,62);
INSERT INTO ezimagevariation VALUES (245,2,'Yc1rQr_600x600_245.jpg','Y/c/1/',600,600,400,250);
INSERT INTO ezimagevariation VALUES (248,2,'YwIaai_100x100_248.jpg','Y/w/I/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (248,2,'YwIaai_600x600_248.jpg','Y/w/I/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (259,2,'GmifVM_100x100_259.jpg','G/m/i/',100,100,100,65);
INSERT INTO ezimagevariation VALUES (259,2,'GmifVM_600x600_259.jpg','G/m/i/',600,600,400,260);
INSERT INTO ezimagevariation VALUES (262,2,'uSWNNw_100x100_262.jpg','u/S/W/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (262,2,'uSWNNw_600x600_262.jpg','u/S/W/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (253,2,'NQspGB_100x100_253.jpg','N/Q/s/',100,100,100,74);
INSERT INTO ezimagevariation VALUES (253,2,'NQspGB_600x600_253.jpg','N/Q/s/',600,600,400,299);
INSERT INTO ezimagevariation VALUES (256,2,'Kmq7Dt_100x100_256.jpg','K/m/q/',100,100,100,64);
INSERT INTO ezimagevariation VALUES (256,2,'Kmq7Dt_600x600_256.jpg','K/m/q/',600,600,400,259);
INSERT INTO ezimagevariation VALUES (216,4,'knuUKQ_150x150_216.jpg','k/n/u/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (219,2,'whm1Qu_150x150_219.jpg','w/h/m/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (222,2,'xUQGvP_150x150_222.jpg','x/U/Q/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (225,2,'0FYhVQ_150x150_225.jpg','0/F/Y/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (213,3,'a72o2K_150x150_213.jpg','a/7/2/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (228,2,'DSNsUt_150x150_228.jpg','D/S/N/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (231,2,'NiykNJ_150x150_231.jpg','N/i/y/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (234,2,'sPRLs6_150x150_234.jpg','s/P/R/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (239,3,'XUbmMp_150x150_239.jpg','X/U/b/',150,150,150,97);
INSERT INTO ezimagevariation VALUES (242,2,'oZw9Q4_150x150_242.jpg','o/Z/w/',150,150,150,99);
INSERT INTO ezimagevariation VALUES (245,2,'Yc1rQr_150x150_245.jpg','Y/c/1/',150,150,150,93);
INSERT INTO ezimagevariation VALUES (248,2,'YwIaai_150x150_248.jpg','Y/w/I/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (259,2,'GmifVM_150x150_259.jpg','G/m/i/',150,150,150,97);
INSERT INTO ezimagevariation VALUES (262,2,'uSWNNw_150x150_262.jpg','u/S/W/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (253,2,'NQspGB_150x150_253.jpg','N/Q/s/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (256,2,'Kmq7Dt_150x150_256.jpg','K/m/q/',150,150,150,97);
INSERT INTO ezimagevariation VALUES (216,5,'knuUKQ_100x100_216.jpg','k/n/u/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (216,5,'knuUKQ_600x600_216.jpg','k/n/u/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (219,3,'whm1Qu_100x100_219.jpg','w/h/m/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (219,3,'whm1Qu_600x600_219.jpg','w/h/m/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (216,5,'knuUKQ_150x150_216.jpg','k/n/u/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (219,3,'whm1Qu_150x150_219.jpg','w/h/m/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (216,5,'knuUKQ_60x60_216.jpg','k/n/u/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (219,3,'whm1Qu_60x60_219.jpg','w/h/m/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (222,2,'xUQGvP_60x60_222.jpg','x/U/Q/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (225,2,'0FYhVQ_60x60_225.jpg','0/F/Y/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (213,3,'a72o2K_60x60_213.jpg','a/7/2/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (228,2,'DSNsUt_60x60_228.jpg','D/S/N/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (231,2,'NiykNJ_60x60_231.jpg','N/i/y/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (234,2,'sPRLs6_60x60_234.jpg','s/P/R/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (267,2,'5Pn1q0_60x60_267.jpg','5/P/n/',60,60,60,37);
INSERT INTO ezimagevariation VALUES (270,2,'3OeOi3_60x60_270.jpg','3/O/e/',60,60,60,38);
INSERT INTO ezimagevariation VALUES (273,2,'UGwCqD_60x60_273.jpg','U/G/w/',60,60,60,37);
INSERT INTO ezimagevariation VALUES (276,2,'JFFPeo_60x60_276.jpg','J/F/F/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (239,3,'XUbmMp_60x60_239.jpg','X/U/b/',60,60,60,38);
INSERT INTO ezimagevariation VALUES (242,2,'oZw9Q4_60x60_242.jpg','o/Z/w/',60,60,60,39);
INSERT INTO ezimagevariation VALUES (245,2,'Yc1rQr_60x60_245.jpg','Y/c/1/',60,60,60,37);
INSERT INTO ezimagevariation VALUES (248,2,'YwIaai_60x60_248.jpg','Y/w/I/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (259,2,'GmifVM_60x60_259.jpg','G/m/i/',60,60,60,39);
INSERT INTO ezimagevariation VALUES (262,2,'uSWNNw_60x60_262.jpg','u/S/W/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (253,2,'NQspGB_60x60_253.jpg','N/Q/s/',60,60,60,44);
INSERT INTO ezimagevariation VALUES (256,2,'Kmq7Dt_60x60_256.jpg','K/m/q/',60,60,60,38);
INSERT INTO ezimagevariation VALUES (208,4,'XROWkC_600x600_208.jpg','X/R/O/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (283,1,'8JCcrj_600x600_283.jpg','8/J/C/',600,600,150,113);
INSERT INTO ezimagevariation VALUES (283,2,'8JCcrj_60x60_283.jpg','8/J/C/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (283,3,'8JCcrj_60x60_283.jpg','8/J/C/',60,60,60,45);
INSERT INTO ezimagevariation VALUES (283,3,'8JCcrj_600x600_283.jpg','8/J/C/',600,600,150,113);
INSERT INTO ezimagevariation VALUES (290,1,'XQ1T5V_600x600_290.jpg','X/Q/1/',600,600,300,225);
INSERT INTO ezimagevariation VALUES (297,1,'bqOnp1_600x600_297.jpg','b/q/O/',600,600,368,300);
INSERT INTO ezimagevariation VALUES (290,1,'XQ1T5V_150x150_290.jpg','X/Q/1/',150,150,150,112);
INSERT INTO ezimagevariation VALUES (302,1,'Mm158b_600x600_302.jpg','M/m/1/',600,600,89,89);
INSERT INTO ezimagevariation VALUES (302,2,'Mm158b_60x60_302.jpg','M/m/1/',60,60,60,60);
INSERT INTO ezimagevariation VALUES (302,2,'Mm158b_600x600_302.jpg','M/m/1/',600,600,89,89);

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
# Table structure for table 'ezmessage'
#

CREATE TABLE ezmessage (
  id int(11) NOT NULL auto_increment,
  send_method varchar(50) NOT NULL default '',
  send_weekday varchar(50) NOT NULL default '',
  send_time varchar(50) NOT NULL default '',
  destination_address varchar(50) NOT NULL default '',
  title varchar(50) NOT NULL default '',
  body varchar(50) default NULL,
  is_sent int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezmessage'
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
  sort_order int(1) default '1',
  sort_field int(11) default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'eznode_assignment'
#

INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1);
INSERT INTO eznode_assignment VALUES (3,4,2,1,1,1,1);
INSERT INTO eznode_assignment VALUES (4,8,2,5,1,1,1);
INSERT INTO eznode_assignment VALUES (144,4,4,1,1,1,1);
INSERT INTO eznode_assignment VALUES (147,210,1,5,1,1,1);
INSERT INTO eznode_assignment VALUES (146,209,1,5,1,1,1);
INSERT INTO eznode_assignment VALUES (145,1,2,1,1,1,1);
INSERT INTO eznode_assignment VALUES (148,9,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (149,10,1,5,1,1,1);
INSERT INTO eznode_assignment VALUES (150,11,1,5,1,1,1);
INSERT INTO eznode_assignment VALUES (151,12,1,5,1,1,1);
INSERT INTO eznode_assignment VALUES (152,13,1,5,1,1,1);
INSERT INTO eznode_assignment VALUES (153,14,1,13,1,1,1);
INSERT INTO eznode_assignment VALUES (154,15,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (155,16,1,16,1,1,1);
INSERT INTO eznode_assignment VALUES (156,17,1,16,1,1,1);
INSERT INTO eznode_assignment VALUES (157,18,1,17,1,1,1);
INSERT INTO eznode_assignment VALUES (158,19,1,17,1,1,1);
INSERT INTO eznode_assignment VALUES (159,20,1,17,1,1,1);
INSERT INTO eznode_assignment VALUES (160,21,1,17,1,1,1);
INSERT INTO eznode_assignment VALUES (161,22,1,17,1,1,1);
INSERT INTO eznode_assignment VALUES (162,18,2,17,1,1,1);
INSERT INTO eznode_assignment VALUES (163,18,3,17,1,1,1);
INSERT INTO eznode_assignment VALUES (164,18,4,17,1,1,1);
INSERT INTO eznode_assignment VALUES (165,16,2,16,1,1,1);
INSERT INTO eznode_assignment VALUES (166,15,2,2,1,1,1);
INSERT INTO eznode_assignment VALUES (167,15,3,2,1,1,1);
INSERT INTO eznode_assignment VALUES (168,23,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (169,24,1,24,1,1,1);
INSERT INTO eznode_assignment VALUES (170,25,1,24,1,1,1);
INSERT INTO eznode_assignment VALUES (171,26,1,24,1,1,1);
INSERT INTO eznode_assignment VALUES (172,27,1,24,1,1,1);
INSERT INTO eznode_assignment VALUES (173,28,1,28,1,1,1);
INSERT INTO eznode_assignment VALUES (174,29,1,24,1,1,1);
INSERT INTO eznode_assignment VALUES (175,30,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (176,31,1,31,1,1,1);
INSERT INTO eznode_assignment VALUES (177,32,1,32,1,1,1);
INSERT INTO eznode_assignment VALUES (254,17,4,16,1,1,1);
INSERT INTO eznode_assignment VALUES (251,87,1,18,1,1,1);
INSERT INTO eznode_assignment VALUES (249,17,3,16,1,1,1);
INSERT INTO eznode_assignment VALUES (181,36,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (182,37,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (183,38,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (184,39,1,33,1,1,1);
INSERT INTO eznode_assignment VALUES (185,40,1,33,1,1,1);
INSERT INTO eznode_assignment VALUES (186,41,1,33,1,1,1);
INSERT INTO eznode_assignment VALUES (187,42,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (188,43,1,40,1,1,1);
INSERT INTO eznode_assignment VALUES (189,44,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (190,45,1,40,1,1,1);
INSERT INTO eznode_assignment VALUES (191,46,1,40,1,1,1);
INSERT INTO eznode_assignment VALUES (192,47,1,40,1,1,1);
INSERT INTO eznode_assignment VALUES (193,48,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (194,49,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (195,50,1,49,1,1,1);
INSERT INTO eznode_assignment VALUES (196,51,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (197,52,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (198,53,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (199,54,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (200,55,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (201,56,1,53,1,1,1);
INSERT INTO eznode_assignment VALUES (202,57,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (203,58,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (204,59,1,26,1,1,1);
INSERT INTO eznode_assignment VALUES (205,60,1,26,1,1,1);
INSERT INTO eznode_assignment VALUES (206,61,1,26,1,1,1);
INSERT INTO eznode_assignment VALUES (207,62,1,2,1,1,1);
INSERT INTO eznode_assignment VALUES (208,63,1,60,1,1,1);
INSERT INTO eznode_assignment VALUES (209,64,1,60,1,1,1);
INSERT INTO eznode_assignment VALUES (210,65,1,60,1,1,1);
INSERT INTO eznode_assignment VALUES (211,66,1,60,1,1,1);
INSERT INTO eznode_assignment VALUES (212,67,1,60,1,1,1);
INSERT INTO eznode_assignment VALUES (248,85,1,83,1,1,1);
INSERT INTO eznode_assignment VALUES (247,84,1,16,1,1,1);
INSERT INTO eznode_assignment VALUES (246,83,1,16,1,1,1);
INSERT INTO eznode_assignment VALUES (216,70,1,66,1,1,1);
INSERT INTO eznode_assignment VALUES (217,71,1,27,1,1,1);
INSERT INTO eznode_assignment VALUES (218,71,2,27,1,1,1);
INSERT INTO eznode_assignment VALUES (219,72,1,28,1,1,1);
INSERT INTO eznode_assignment VALUES (220,72,2,28,1,1,1);
INSERT INTO eznode_assignment VALUES (221,73,1,28,1,1,1);
INSERT INTO eznode_assignment VALUES (222,73,2,28,1,1,1);
INSERT INTO eznode_assignment VALUES (223,72,3,28,1,1,1);
INSERT INTO eznode_assignment VALUES (224,72,4,28,1,1,1);
INSERT INTO eznode_assignment VALUES (225,72,5,28,1,1,1);
INSERT INTO eznode_assignment VALUES (226,72,6,28,1,1,1);
INSERT INTO eznode_assignment VALUES (227,72,7,28,1,1,1);
INSERT INTO eznode_assignment VALUES (228,72,8,28,1,1,1);
INSERT INTO eznode_assignment VALUES (229,72,9,28,1,1,1);
INSERT INTO eznode_assignment VALUES (250,86,1,18,1,1,1);
INSERT INTO eznode_assignment VALUES (260,94,1,92,1,1,1);
INSERT INTO eznode_assignment VALUES (259,93,1,16,1,1,1);
INSERT INTO eznode_assignment VALUES (233,17,2,16,1,1,1);
INSERT INTO eznode_assignment VALUES (258,92,1,83,1,1,1);
INSERT INTO eznode_assignment VALUES (253,89,1,18,1,1,1);
INSERT INTO eznode_assignment VALUES (252,88,1,18,1,1,1);
INSERT INTO eznode_assignment VALUES (237,25,2,24,1,1,1);
INSERT INTO eznode_assignment VALUES (238,25,3,24,1,1,1);
INSERT INTO eznode_assignment VALUES (239,26,2,24,1,1,1);
INSERT INTO eznode_assignment VALUES (240,26,3,24,1,1,1);
INSERT INTO eznode_assignment VALUES (241,29,2,24,1,1,1);
INSERT INTO eznode_assignment VALUES (242,29,3,24,1,1,1);
INSERT INTO eznode_assignment VALUES (257,91,1,83,1,1,1);
INSERT INTO eznode_assignment VALUES (256,90,1,83,1,1,1);
INSERT INTO eznode_assignment VALUES (255,85,2,83,1,1,1);
INSERT INTO eznode_assignment VALUES (261,94,2,92,1,1,1);
INSERT INTO eznode_assignment VALUES (262,95,1,92,1,1,1);
INSERT INTO eznode_assignment VALUES (263,96,1,92,1,1,1);
INSERT INTO eznode_assignment VALUES (264,97,1,92,1,1,1);
INSERT INTO eznode_assignment VALUES (265,98,1,16,1,1,1);
INSERT INTO eznode_assignment VALUES (266,99,1,97,1,1,1);
INSERT INTO eznode_assignment VALUES (267,100,1,97,1,1,1);
INSERT INTO eznode_assignment VALUES (268,101,1,97,1,1,1);
INSERT INTO eznode_assignment VALUES (269,102,1,97,1,1,1);
INSERT INTO eznode_assignment VALUES (270,103,1,16,1,1,1);
INSERT INTO eznode_assignment VALUES (271,104,1,102,1,1,1);
INSERT INTO eznode_assignment VALUES (272,105,1,102,1,1,1);
INSERT INTO eznode_assignment VALUES (273,106,1,102,1,1,1);
INSERT INTO eznode_assignment VALUES (274,107,1,102,1,1,1);
INSERT INTO eznode_assignment VALUES (275,104,2,102,1,1,1);
INSERT INTO eznode_assignment VALUES (276,105,2,102,1,1,1);
INSERT INTO eznode_assignment VALUES (277,106,2,102,1,1,1);
INSERT INTO eznode_assignment VALUES (278,107,2,102,1,1,1);
INSERT INTO eznode_assignment VALUES (279,17,5,16,1,1,1);
INSERT INTO eznode_assignment VALUES (280,17,6,16,1,1,1);
INSERT INTO eznode_assignment VALUES (281,86,2,18,1,1,1);
INSERT INTO eznode_assignment VALUES (282,86,3,18,1,1,1);
INSERT INTO eznode_assignment VALUES (283,86,4,18,1,1,1);
INSERT INTO eznode_assignment VALUES (284,87,2,18,1,1,1);
INSERT INTO eznode_assignment VALUES (285,88,2,18,1,1,1);
INSERT INTO eznode_assignment VALUES (286,89,2,18,1,1,1);
INSERT INTO eznode_assignment VALUES (287,84,2,16,1,1,1);
INSERT INTO eznode_assignment VALUES (288,85,3,83,1,1,1);
INSERT INTO eznode_assignment VALUES (289,90,2,83,1,1,1);
INSERT INTO eznode_assignment VALUES (290,91,2,83,1,1,1);
INSERT INTO eznode_assignment VALUES (291,92,2,83,1,1,1);
INSERT INTO eznode_assignment VALUES (292,94,3,92,1,1,1);
INSERT INTO eznode_assignment VALUES (293,95,2,92,1,1,1);
INSERT INTO eznode_assignment VALUES (294,96,2,92,1,1,1);
INSERT INTO eznode_assignment VALUES (295,97,2,92,1,1,1);
INSERT INTO eznode_assignment VALUES (296,101,2,97,1,1,1);
INSERT INTO eznode_assignment VALUES (297,102,2,97,1,1,1);
INSERT INTO eznode_assignment VALUES (298,99,2,97,1,1,1);
INSERT INTO eznode_assignment VALUES (299,100,2,97,1,1,1);
INSERT INTO eznode_assignment VALUES (301,84,3,16,1,1,1);
INSERT INTO eznode_assignment VALUES (302,84,4,16,1,1,1);
INSERT INTO eznode_assignment VALUES (303,84,5,16,1,1,1);
INSERT INTO eznode_assignment VALUES (305,86,5,18,1,1,1);
INSERT INTO eznode_assignment VALUES (306,87,3,18,1,1,1);
INSERT INTO eznode_assignment VALUES (308,15,4,2,1,1,1);
INSERT INTO eznode_assignment VALUES (309,15,5,2,1,1,1);
INSERT INTO eznode_assignment VALUES (310,83,2,16,1,1,1);
INSERT INTO eznode_assignment VALUES (311,83,3,16,1,1,1);
INSERT INTO eznode_assignment VALUES (312,108,1,82,1,1,1);
INSERT INTO eznode_assignment VALUES (313,83,4,16,1,1,1);
INSERT INTO eznode_assignment VALUES (314,109,1,30,1,1,1);
INSERT INTO eznode_assignment VALUES (315,109,2,30,1,1,1);
INSERT INTO eznode_assignment VALUES (316,109,3,30,1,1,1);
INSERT INTO eznode_assignment VALUES (317,110,1,24,1,1,1);
INSERT INTO eznode_assignment VALUES (318,111,1,108,1,1,1);
INSERT INTO eznode_assignment VALUES (319,112,1,24,1,1,1);
INSERT INTO eznode_assignment VALUES (320,113,1,110,1,1,1);
INSERT INTO eznode_assignment VALUES (321,114,1,27,1,1,1);
INSERT INTO eznode_assignment VALUES (322,114,2,27,1,1,1);

#
# Table structure for table 'eznotification_rule'
#

CREATE TABLE eznotification_rule (
  id int(11) NOT NULL auto_increment,
  type varchar(250) NOT NULL default '',
  contentclass_name varchar(250) NOT NULL default '',
  path varchar(250) default NULL,
  keyword varchar(250) default NULL,
  has_constraint int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'eznotification_rule'
#


#
# Table structure for table 'eznotification_user_link'
#

CREATE TABLE eznotification_user_link (
  rule_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  send_method varchar(50) NOT NULL default '',
  send_weekday varchar(50) NOT NULL default '',
  send_time varchar(50) NOT NULL default '',
  destination_address varchar(50) NOT NULL default '',
  PRIMARY KEY  (rule_id,user_id)
) TYPE=MyISAM;

#
# Dumping data for table 'eznotification_user_link'
#


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

INSERT INTO ezpolicy VALUES (353,1,'create','content','');
INSERT INTO ezpolicy VALUES (314,3,'*','content','*');
INSERT INTO ezpolicy VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy VALUES (350,1,'read','content','');
INSERT INTO ezpolicy VALUES (354,1,'edit','content','');
INSERT INTO ezpolicy VALUES (352,1,'read','content','');
INSERT INTO ezpolicy VALUES (351,1,'read','content','');
INSERT INTO ezpolicy VALUES (355,1,'read','content','');
INSERT INTO ezpolicy VALUES (356,1,'create','content','');
INSERT INTO ezpolicy VALUES (357,1,'*','layout','*');

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

INSERT INTO ezpolicy_limitation VALUES (286,350,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (289,353,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (290,353,'ParentClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (291,354,'Assigned',0,'','');
INSERT INTO ezpolicy_limitation VALUES (288,352,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (287,351,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (292,355,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (293,356,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (294,356,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (295,356,'ParentClassID',0,'','');

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

INSERT INTO ezpolicy_limitation_value VALUES (465,290,6);
INSERT INTO ezpolicy_limitation_value VALUES (461,286,7);
INSERT INTO ezpolicy_limitation_value VALUES (463,288,6);
INSERT INTO ezpolicy_limitation_value VALUES (464,289,8);
INSERT INTO ezpolicy_limitation_value VALUES (466,291,1);
INSERT INTO ezpolicy_limitation_value VALUES (468,292,8);
INSERT INTO ezpolicy_limitation_value VALUES (467,292,6);
INSERT INTO ezpolicy_limitation_value VALUES (462,287,2);
INSERT INTO ezpolicy_limitation_value VALUES (460,286,1);
INSERT INTO ezpolicy_limitation_value VALUES (469,293,8);
INSERT INTO ezpolicy_limitation_value VALUES (470,294,4);
INSERT INTO ezpolicy_limitation_value VALUES (471,295,8);

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
INSERT INTO ezproductcollection VALUES (3);

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
  published int(11) NOT NULL default '0',
  section_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ezsearch_object_word_link_object (contentobject_id),
  KEY ezsearch_object_word_link_word (word_id),
  KEY ezsearch_object_word_link_frequency (frequency)
) TYPE=MyISAM;

#
# Dumping data for table 'ezsearch_object_word_link'
#

INSERT INTO ezsearch_object_word_link VALUES (3086,109,234,0,220,1174,182,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3085,109,1174,0,219,228,234,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3084,109,228,0,218,243,1174,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2644,83,1002,0,177,11,0,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2643,83,11,0,176,234,1002,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (6,16,5,0,0,0,6,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (7,16,6,0,1,5,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1948,78,177,0,0,0,178,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2105,17,759,0,0,0,0,1,4,1035886818,2);
INSERT INTO ezsearch_object_word_link VALUES (10,23,8,0,0,0,9,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (11,23,9,0,1,8,10,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (12,23,10,0,2,9,11,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (13,23,11,0,3,10,8,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (14,23,8,0,4,11,0,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (15,24,12,0,0,0,13,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (16,24,13,0,1,12,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (17,24,14,0,2,13,15,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (18,24,15,0,3,14,16,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (19,24,16,0,4,15,17,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (20,24,17,0,5,16,18,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (21,24,18,0,6,17,19,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (22,24,19,0,7,18,20,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (23,24,20,0,8,19,21,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (24,24,21,0,9,20,22,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (25,24,22,0,10,21,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (26,24,23,0,11,22,24,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (27,24,24,0,12,23,25,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (28,24,25,0,13,24,26,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (29,24,26,0,14,25,27,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (30,24,27,0,15,26,28,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (31,24,28,0,16,27,29,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (32,24,29,0,17,28,30,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (33,24,30,0,18,29,31,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (34,24,31,0,19,30,32,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (35,24,32,0,20,31,33,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (36,24,33,0,21,32,34,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (37,24,34,0,22,33,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (38,24,23,0,23,34,35,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (39,24,35,0,24,23,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (40,24,36,0,25,35,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (41,24,37,0,26,36,38,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (42,24,38,0,27,37,39,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (43,24,39,0,28,38,40,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (44,24,40,0,29,39,41,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (45,24,41,0,30,40,42,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (46,24,42,0,31,41,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (47,24,43,0,32,42,44,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (48,24,44,0,33,43,45,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (49,24,45,0,34,44,46,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (50,24,46,0,35,45,47,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (51,24,47,0,36,46,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (52,24,37,0,37,47,48,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (53,24,48,0,38,37,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (54,24,36,0,39,48,49,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (55,24,49,0,40,36,50,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (56,24,50,0,41,49,51,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (57,24,51,0,42,50,52,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (58,24,52,0,43,51,53,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (59,24,53,0,44,52,54,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (60,24,54,0,45,53,55,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (61,24,55,0,46,54,56,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (62,24,56,0,47,55,57,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (63,24,57,0,48,56,58,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (64,24,58,0,49,57,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (65,24,59,0,50,58,60,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (66,24,60,0,51,59,61,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (67,24,61,0,52,60,62,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (68,24,62,0,53,61,12,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (69,24,12,0,54,62,13,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (70,24,13,0,55,12,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (71,24,14,0,56,13,15,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (72,24,15,0,57,14,16,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (73,24,16,0,58,15,17,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (74,24,17,0,59,16,18,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (75,24,18,0,60,17,19,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (76,24,19,0,61,18,20,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (77,24,20,0,62,19,21,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (78,24,21,0,63,20,22,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (79,24,22,0,64,21,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (80,24,23,0,65,22,24,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (81,24,24,0,66,23,25,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (82,24,25,0,67,24,26,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (83,24,26,0,68,25,27,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (84,24,27,0,69,26,28,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (85,24,28,0,70,27,29,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (86,24,29,0,71,28,30,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (87,24,30,0,72,29,31,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (88,24,31,0,73,30,32,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (89,24,32,0,74,31,33,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (90,24,33,0,75,32,34,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (91,24,34,0,76,33,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (92,24,23,0,77,34,35,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (93,24,35,0,78,23,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (94,24,36,0,79,35,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (95,24,37,0,80,36,38,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (96,24,38,0,81,37,39,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (97,24,39,0,82,38,40,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (98,24,40,0,83,39,41,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (99,24,41,0,84,40,42,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (100,24,42,0,85,41,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (101,24,43,0,86,42,44,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (102,24,44,0,87,43,45,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (103,24,45,0,88,44,46,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (104,24,46,0,89,45,47,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (105,24,47,0,90,46,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (106,24,37,0,91,47,48,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (107,24,48,0,92,37,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (108,24,36,0,93,48,49,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (109,24,49,0,94,36,50,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (110,24,50,0,95,49,51,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (111,24,51,0,96,50,52,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (112,24,52,0,97,51,53,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (113,24,53,0,98,52,54,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (114,24,54,0,99,53,55,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (115,24,55,0,100,54,56,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (116,24,56,0,101,55,57,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (117,24,57,0,102,56,58,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (118,24,58,0,103,57,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (119,24,59,0,104,58,60,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (120,24,60,0,105,59,61,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (121,24,61,0,106,60,62,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (122,24,62,0,107,61,12,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (123,24,12,0,108,62,13,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (124,24,13,0,109,12,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (125,24,14,0,110,13,15,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (126,24,15,0,111,14,16,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (127,24,16,0,112,15,17,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (128,24,17,0,113,16,18,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (129,24,18,0,114,17,19,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (130,24,19,0,115,18,20,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (131,24,20,0,116,19,21,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (132,24,21,0,117,20,22,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (133,24,22,0,118,21,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (134,24,23,0,119,22,24,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (135,24,24,0,120,23,25,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (136,24,25,0,121,24,26,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (137,24,26,0,122,25,27,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (138,24,27,0,123,26,28,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (139,24,28,0,124,27,29,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (140,24,29,0,125,28,30,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (141,24,30,0,126,29,31,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (142,24,31,0,127,30,32,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (143,24,32,0,128,31,33,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (144,24,33,0,129,32,34,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (145,24,34,0,130,33,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (146,24,23,0,131,34,35,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (147,24,35,0,132,23,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (148,24,36,0,133,35,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (149,24,37,0,134,36,38,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (150,24,38,0,135,37,39,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (151,24,39,0,136,38,40,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (152,24,40,0,137,39,41,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (153,24,41,0,138,40,42,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (154,24,42,0,139,41,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (155,24,43,0,140,42,44,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (156,24,44,0,141,43,45,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (157,24,45,0,142,44,46,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (158,24,46,0,143,45,47,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (159,24,47,0,144,46,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (160,24,37,0,145,47,48,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (161,24,48,0,146,37,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (162,24,36,0,147,48,49,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (163,24,49,0,148,36,50,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (164,24,50,0,149,49,51,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (165,24,51,0,150,50,52,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (166,24,52,0,151,51,53,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (167,24,53,0,152,52,54,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (168,24,54,0,153,53,55,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (169,24,55,0,154,54,56,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (170,24,56,0,155,55,57,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (171,24,57,0,156,56,58,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (172,24,58,0,157,57,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (173,24,59,0,158,58,60,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (174,24,60,0,159,59,61,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (175,24,61,0,160,60,62,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (176,24,62,0,161,61,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (177,24,59,0,162,62,63,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (178,24,63,0,163,59,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (179,24,43,0,164,63,64,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (180,24,64,0,165,43,65,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (181,24,65,0,166,64,66,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (182,24,66,0,167,65,67,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (183,24,67,0,168,66,68,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (184,24,68,0,169,67,69,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (185,24,69,0,170,68,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (186,24,14,0,171,69,12,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (187,24,12,0,172,14,13,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (188,24,13,0,173,12,14,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (189,24,14,0,174,13,15,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (190,24,15,0,175,14,16,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (191,24,16,0,176,15,17,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (192,24,17,0,177,16,18,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (193,24,18,0,178,17,19,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (194,24,19,0,179,18,20,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (195,24,20,0,180,19,21,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (196,24,21,0,181,20,22,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (197,24,22,0,182,21,23,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (198,24,23,0,183,22,24,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (199,24,24,0,184,23,25,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (200,24,25,0,185,24,26,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (201,24,26,0,186,25,27,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (202,24,27,0,187,26,28,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (203,24,28,0,188,27,29,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (204,24,29,0,189,28,30,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (205,24,30,0,190,29,31,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (206,24,31,0,191,30,32,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (207,24,32,0,192,31,33,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (208,24,33,0,193,32,34,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (209,24,34,0,194,33,23,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (210,24,23,0,195,34,35,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (211,24,35,0,196,23,36,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (212,24,36,0,197,35,37,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (213,24,37,0,198,36,38,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (214,24,38,0,199,37,39,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (215,24,39,0,200,38,40,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (216,24,40,0,201,39,41,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (217,24,41,0,202,40,42,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (218,24,42,0,203,41,43,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (219,24,43,0,204,42,44,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (220,24,44,0,205,43,45,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (221,24,45,0,206,44,46,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (222,24,46,0,207,45,47,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (223,24,47,0,208,46,37,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (224,24,37,0,209,47,48,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (225,24,48,0,210,37,36,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (226,24,36,0,211,48,49,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (227,24,49,0,212,36,50,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (228,24,50,0,213,49,51,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (229,24,51,0,214,50,52,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (230,24,52,0,215,51,53,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (231,24,53,0,216,52,54,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (232,24,54,0,217,53,55,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (233,24,55,0,218,54,56,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (234,24,56,0,219,55,57,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (235,24,57,0,220,56,58,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (236,24,58,0,221,57,59,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (237,24,59,0,222,58,60,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (238,24,60,0,223,59,61,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (239,24,61,0,224,60,62,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (240,24,62,0,225,61,59,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (241,24,59,0,226,62,63,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (242,24,63,0,227,59,43,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (243,24,43,0,228,63,64,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (244,24,64,0,229,43,65,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (245,24,65,0,230,64,66,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (246,24,66,0,231,65,67,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (247,24,67,0,232,66,68,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (248,24,68,0,233,67,69,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (249,24,69,0,234,68,70,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (250,24,70,0,235,69,0,2,123,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1971,25,8,0,2,726,0,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1970,25,726,0,1,725,8,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1969,25,725,0,0,0,726,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1977,26,8,0,2,728,0,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1976,26,728,0,1,728,8,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1975,26,728,0,0,0,728,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (257,27,75,0,0,0,75,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (258,27,75,0,1,75,0,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (259,28,76,0,0,0,77,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (260,28,77,0,1,76,11,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (261,28,11,0,2,77,76,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (262,28,76,0,3,11,0,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1987,29,730,0,4,11,0,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1986,29,11,0,3,77,730,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1985,29,77,0,2,8,11,1,119,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1984,29,8,0,1,76,77,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (267,30,79,0,0,0,80,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (268,30,80,0,1,79,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (269,31,81,0,0,0,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (270,32,80,0,0,0,6,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (271,32,6,0,1,80,82,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (272,32,82,0,2,6,83,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (273,32,83,0,3,82,84,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (274,32,84,0,4,83,85,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (275,32,85,0,5,84,86,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (276,32,86,0,6,85,87,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (277,32,87,0,7,86,88,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (278,32,88,0,8,87,89,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (279,32,89,0,9,88,90,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (280,32,90,0,10,89,91,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (281,32,91,0,11,90,23,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (282,32,23,0,12,91,92,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (283,32,92,0,13,23,93,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (284,32,93,0,14,92,94,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (285,32,94,0,15,93,95,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (286,32,95,0,16,94,96,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (287,32,96,0,17,95,97,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (288,32,97,0,18,96,98,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (289,32,98,0,19,97,99,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (290,32,99,0,20,98,100,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (291,32,100,0,21,99,101,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (292,32,101,0,22,100,36,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (293,32,36,0,23,101,69,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (294,32,69,0,24,36,30,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (295,32,30,0,25,69,102,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (296,32,102,0,26,30,103,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (297,32,103,0,27,102,104,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (298,32,104,0,28,103,105,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (299,32,105,0,29,104,106,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (300,32,106,0,30,105,107,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (301,32,107,0,31,106,108,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (302,32,108,0,32,107,109,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (303,32,109,0,33,108,110,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (304,32,110,0,34,109,111,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (305,32,111,0,35,110,112,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (306,32,112,0,36,111,36,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (307,32,36,0,37,112,113,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (308,32,113,0,38,36,114,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (309,32,114,0,39,113,115,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (310,32,115,0,40,114,116,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (311,32,116,0,41,115,59,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (312,32,59,0,42,116,117,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (313,32,117,0,43,59,118,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (314,32,118,0,44,117,119,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (315,32,119,0,45,118,120,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (316,32,120,0,46,119,0,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (317,33,80,0,0,0,7,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (318,33,7,0,1,80,121,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (319,33,121,0,2,7,68,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (320,33,68,0,3,121,122,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (321,33,122,0,4,68,123,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (322,33,123,0,5,122,124,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (323,33,124,0,6,123,125,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (324,33,125,0,7,124,30,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (325,33,30,0,8,125,126,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (326,33,126,0,9,30,127,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (327,33,127,0,10,126,128,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (328,33,128,0,11,127,129,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (329,33,129,0,12,128,110,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (330,33,110,0,13,129,130,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (331,33,130,0,14,110,131,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (332,33,131,0,15,130,132,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (333,33,132,0,16,131,133,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (334,33,133,0,17,132,134,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (335,33,134,0,18,133,135,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (336,33,135,0,19,134,136,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (337,33,136,0,20,135,137,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (338,33,137,0,21,136,138,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (339,33,138,0,22,137,25,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (340,33,25,0,23,138,139,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (341,33,139,0,24,25,140,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (342,33,140,0,25,139,141,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (343,33,141,0,26,140,142,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (344,33,142,0,27,141,143,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (345,33,143,0,28,142,144,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (346,33,144,0,29,143,145,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (347,33,145,0,30,144,146,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (348,33,146,0,31,145,147,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (349,33,147,0,32,146,148,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (350,33,148,0,33,147,149,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (351,33,149,0,34,148,150,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (352,33,150,0,35,149,151,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (353,33,151,0,36,150,152,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (354,33,152,0,37,151,153,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (355,33,153,0,38,152,154,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (356,33,154,0,39,153,155,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (357,33,155,0,40,154,156,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (358,33,156,0,41,155,157,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (359,33,157,0,42,156,158,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (360,33,158,0,43,157,159,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (361,33,159,0,44,158,160,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (362,33,160,0,45,159,161,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (363,33,161,0,46,160,162,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (364,33,162,0,47,161,43,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (365,33,43,0,48,162,163,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (366,33,163,0,49,43,164,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (367,33,164,0,50,163,162,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (368,33,162,0,51,164,110,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (369,33,110,0,52,162,165,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (370,33,165,0,53,110,166,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (371,33,166,0,54,165,167,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (372,33,167,0,55,166,168,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (373,33,168,0,56,167,169,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (374,33,169,0,57,168,170,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (375,33,170,0,58,169,171,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (376,33,171,0,59,170,0,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (377,34,80,0,0,0,172,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (378,34,172,0,1,80,82,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (379,34,82,0,2,172,83,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (380,34,83,0,3,82,84,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (381,34,84,0,4,83,85,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (382,34,85,0,5,84,86,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (383,34,86,0,6,85,87,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (384,34,87,0,7,86,88,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (385,34,88,0,8,87,89,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (386,34,89,0,9,88,90,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (387,34,90,0,10,89,91,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (388,34,91,0,11,90,23,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (389,34,23,0,12,91,92,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (390,34,92,0,13,23,93,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (391,34,93,0,14,92,94,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (392,34,94,0,15,93,95,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (393,34,95,0,16,94,96,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (394,34,96,0,17,95,97,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (395,34,97,0,18,96,98,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (396,34,98,0,19,97,99,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (397,34,99,0,20,98,100,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (398,34,100,0,21,99,101,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (399,34,101,0,22,100,36,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (400,34,36,0,23,101,69,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (401,34,69,0,24,36,30,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (402,34,30,0,25,69,102,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (403,34,102,0,26,30,103,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (404,34,103,0,27,102,104,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (405,34,104,0,28,103,105,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (406,34,105,0,29,104,106,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (407,34,106,0,30,105,107,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (408,34,107,0,31,106,108,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (409,34,108,0,32,107,109,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (410,34,109,0,33,108,110,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (411,34,110,0,34,109,111,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (412,34,111,0,35,110,112,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (413,34,112,0,36,111,36,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (414,34,36,0,37,112,113,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (415,34,113,0,38,36,114,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (416,34,114,0,39,113,115,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (417,34,115,0,40,114,116,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (418,34,116,0,41,115,59,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (419,34,59,0,42,116,117,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (420,34,117,0,43,59,118,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (421,34,118,0,44,117,119,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (422,34,119,0,45,118,120,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (423,34,120,0,46,119,0,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (424,35,80,0,0,0,173,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (425,35,173,0,1,80,82,6,124,0,0);
INSERT INTO ezsearch_object_word_link VALUES (426,35,82,0,2,173,83,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (427,35,83,0,3,82,84,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (428,35,84,0,4,83,85,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (429,35,85,0,5,84,86,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (430,35,86,0,6,85,87,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (431,35,87,0,7,86,88,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (432,35,88,0,8,87,89,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (433,35,89,0,9,88,90,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (434,35,90,0,10,89,91,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (435,35,91,0,11,90,23,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (436,35,23,0,12,91,92,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (437,35,92,0,13,23,93,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (438,35,93,0,14,92,94,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (439,35,94,0,15,93,95,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (440,35,95,0,16,94,96,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (441,35,96,0,17,95,97,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (442,35,97,0,18,96,98,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (443,35,98,0,19,97,99,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (444,35,99,0,20,98,100,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (445,35,100,0,21,99,101,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (446,35,101,0,22,100,36,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (447,35,36,0,23,101,69,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (448,35,69,0,24,36,30,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (449,35,30,0,25,69,102,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (450,35,102,0,26,30,103,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (451,35,103,0,27,102,104,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (452,35,104,0,28,103,105,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (453,35,105,0,29,104,106,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (454,35,106,0,30,105,107,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (455,35,107,0,31,106,108,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (456,35,108,0,32,107,109,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (457,35,109,0,33,108,110,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (458,35,110,0,34,109,111,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (459,35,111,0,35,110,112,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (460,35,112,0,36,111,36,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (461,35,36,0,37,112,113,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (462,35,113,0,38,36,114,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (463,35,114,0,39,113,115,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (464,35,115,0,40,114,116,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (465,35,116,0,41,115,59,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (466,35,59,0,42,116,117,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (467,35,117,0,43,59,118,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (468,35,118,0,44,117,119,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (469,35,119,0,45,118,120,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (470,35,120,0,46,119,0,6,126,0,0);
INSERT INTO ezsearch_object_word_link VALUES (471,36,174,0,0,0,175,7,127,0,0);
INSERT INTO ezsearch_object_word_link VALUES (472,36,175,0,1,174,0,7,127,0,0);
INSERT INTO ezsearch_object_word_link VALUES (473,37,176,0,0,0,0,9,130,0,0);
INSERT INTO ezsearch_object_word_link VALUES (474,38,176,0,0,0,0,9,130,0,0);
INSERT INTO ezsearch_object_word_link VALUES (475,40,177,0,0,0,178,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (476,40,178,0,1,177,179,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (477,40,179,0,2,178,108,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (478,40,108,0,3,179,11,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (479,40,11,0,4,108,177,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (480,40,177,0,5,11,178,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (481,40,178,0,6,177,180,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (482,40,180,0,7,178,181,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (483,40,181,0,8,180,182,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (484,40,182,0,9,181,183,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (485,40,183,0,10,182,172,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (486,40,172,0,11,183,70,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (487,40,70,0,12,172,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (488,41,184,0,0,0,185,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (489,41,185,0,1,184,186,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (490,41,186,0,2,185,187,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (491,41,187,0,3,186,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (492,42,188,0,0,0,0,10,131,0,0);
INSERT INTO ezsearch_object_word_link VALUES (493,43,177,0,0,0,189,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (494,43,189,0,1,177,179,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (495,43,179,0,2,189,108,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (496,43,108,0,3,179,190,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (497,43,190,0,4,108,191,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (498,43,191,0,5,190,192,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (499,43,192,0,6,191,193,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (500,43,193,0,7,192,194,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (501,43,194,0,8,193,195,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (502,43,195,0,9,194,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (503,44,196,0,0,0,0,11,132,0,0);
INSERT INTO ezsearch_object_word_link VALUES (504,45,191,0,0,0,197,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (505,45,197,0,1,191,198,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (506,45,198,0,2,197,199,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (507,45,199,0,3,198,200,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (508,45,200,0,4,199,201,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (509,45,201,0,5,200,202,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (510,45,202,0,6,201,203,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (511,45,203,0,7,202,204,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (512,45,204,0,8,203,205,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (513,45,205,0,9,204,206,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (514,45,206,0,10,205,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (515,46,179,0,0,0,80,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (516,46,80,0,1,179,108,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (517,46,108,0,2,80,207,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (518,46,207,0,3,108,208,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (519,46,208,0,4,207,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (520,48,209,0,0,0,0,12,133,0,0);
INSERT INTO ezsearch_object_word_link VALUES (521,47,179,0,0,0,108,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (522,47,108,0,1,179,210,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (523,47,210,0,2,108,189,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (524,47,189,0,3,210,211,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (525,47,211,0,4,189,212,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (526,47,212,0,5,211,213,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (527,47,213,0,6,212,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (528,49,9,0,0,0,0,13,134,0,0);
INSERT INTO ezsearch_object_word_link VALUES (529,50,9,0,0,0,0,13,134,0,0);
INSERT INTO ezsearch_object_word_link VALUES (530,51,9,0,0,0,214,14,135,0,0);
INSERT INTO ezsearch_object_word_link VALUES (531,51,214,0,1,9,0,14,135,0,0);
INSERT INTO ezsearch_object_word_link VALUES (532,52,9,0,0,0,0,15,136,0,0);
INSERT INTO ezsearch_object_word_link VALUES (533,53,9,0,0,0,0,19,139,0,0);
INSERT INTO ezsearch_object_word_link VALUES (534,57,215,0,0,0,0,20,140,0,0);
INSERT INTO ezsearch_object_word_link VALUES (535,58,216,0,0,0,0,21,141,0,0);
INSERT INTO ezsearch_object_word_link VALUES (536,59,12,0,0,0,13,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (537,59,13,0,1,12,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (538,59,14,0,2,13,15,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (539,59,15,0,3,14,16,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (540,59,16,0,4,15,17,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (541,59,17,0,5,16,18,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (542,59,18,0,6,17,19,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (543,59,19,0,7,18,20,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (544,59,20,0,8,19,21,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (545,59,21,0,9,20,22,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (546,59,22,0,10,21,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (547,59,23,0,11,22,24,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (548,59,24,0,12,23,25,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (549,59,25,0,13,24,26,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (550,59,26,0,14,25,27,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (551,59,27,0,15,26,28,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (552,59,28,0,16,27,29,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (553,59,29,0,17,28,30,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (554,59,30,0,18,29,31,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (555,59,31,0,19,30,32,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (556,59,32,0,20,31,33,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (557,59,33,0,21,32,34,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (558,59,34,0,22,33,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (559,59,23,0,23,34,35,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (560,59,35,0,24,23,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (561,59,36,0,25,35,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (562,59,37,0,26,36,38,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (563,59,38,0,27,37,39,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (564,59,39,0,28,38,40,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (565,59,40,0,29,39,41,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (566,59,41,0,30,40,42,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (567,59,42,0,31,41,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (568,59,43,0,32,42,44,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (569,59,44,0,33,43,45,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (570,59,45,0,34,44,46,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (571,59,46,0,35,45,47,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (572,59,47,0,36,46,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (573,59,37,0,37,47,48,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (574,59,48,0,38,37,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (575,59,36,0,39,48,49,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (576,59,49,0,40,36,50,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (577,59,50,0,41,49,51,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (578,59,51,0,42,50,52,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (579,59,52,0,43,51,53,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (580,59,53,0,44,52,54,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (581,59,54,0,45,53,55,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (582,59,55,0,46,54,56,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (583,59,56,0,47,55,57,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (584,59,57,0,48,56,58,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (585,59,58,0,49,57,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (586,59,59,0,50,58,60,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (587,59,60,0,51,59,61,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (588,59,61,0,52,60,62,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (589,59,62,0,53,61,12,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (590,59,12,0,54,62,13,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (591,59,13,0,55,12,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (592,59,14,0,56,13,15,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (593,59,15,0,57,14,16,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (594,59,16,0,58,15,17,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (595,59,17,0,59,16,18,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (596,59,18,0,60,17,19,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (597,59,19,0,61,18,20,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (598,59,20,0,62,19,21,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (599,59,21,0,63,20,22,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (600,59,22,0,64,21,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (601,59,23,0,65,22,24,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (602,59,24,0,66,23,25,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (603,59,25,0,67,24,26,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (604,59,26,0,68,25,27,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (605,59,27,0,69,26,28,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (606,59,28,0,70,27,29,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (607,59,29,0,71,28,30,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (608,59,30,0,72,29,31,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (609,59,31,0,73,30,32,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (610,59,32,0,74,31,33,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (611,59,33,0,75,32,34,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (612,59,34,0,76,33,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (613,59,23,0,77,34,35,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (614,59,35,0,78,23,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (615,59,36,0,79,35,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (616,59,37,0,80,36,38,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (617,59,38,0,81,37,39,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (618,59,39,0,82,38,40,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (619,59,40,0,83,39,41,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (620,59,41,0,84,40,42,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (621,59,42,0,85,41,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (622,59,43,0,86,42,44,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (623,59,44,0,87,43,45,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (624,59,45,0,88,44,46,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (625,59,46,0,89,45,47,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (626,59,47,0,90,46,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (627,59,37,0,91,47,48,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (628,59,48,0,92,37,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (629,59,36,0,93,48,49,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (630,59,49,0,94,36,50,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (631,59,50,0,95,49,51,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (632,59,51,0,96,50,52,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (633,59,52,0,97,51,53,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (634,59,53,0,98,52,54,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (635,59,54,0,99,53,55,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (636,59,55,0,100,54,56,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (637,59,56,0,101,55,57,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (638,59,57,0,102,56,58,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (639,59,58,0,103,57,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (640,59,59,0,104,58,60,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (641,59,60,0,105,59,61,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (642,59,61,0,106,60,62,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (643,59,62,0,107,61,12,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (644,59,12,0,108,62,13,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (645,59,13,0,109,12,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (646,59,14,0,110,13,15,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (647,59,15,0,111,14,16,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (648,59,16,0,112,15,17,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (649,59,17,0,113,16,18,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (650,59,18,0,114,17,19,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (651,59,19,0,115,18,20,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (652,59,20,0,116,19,21,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (653,59,21,0,117,20,22,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (654,59,22,0,118,21,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (655,59,23,0,119,22,24,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (656,59,24,0,120,23,25,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (657,59,25,0,121,24,26,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (658,59,26,0,122,25,27,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (659,59,27,0,123,26,28,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (660,59,28,0,124,27,29,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (661,59,29,0,125,28,30,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (662,59,30,0,126,29,31,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (663,59,31,0,127,30,32,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (664,59,32,0,128,31,33,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (665,59,33,0,129,32,34,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (666,59,34,0,130,33,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (667,59,23,0,131,34,35,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (668,59,35,0,132,23,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (669,59,36,0,133,35,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (670,59,37,0,134,36,38,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (671,59,38,0,135,37,39,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (672,59,39,0,136,38,40,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (673,59,40,0,137,39,41,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (674,59,41,0,138,40,42,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (675,59,42,0,139,41,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (676,59,43,0,140,42,44,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (677,59,44,0,141,43,45,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (678,59,45,0,142,44,46,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (679,59,46,0,143,45,47,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (680,59,47,0,144,46,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (681,59,37,0,145,47,48,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (682,59,48,0,146,37,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (683,59,36,0,147,48,49,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (684,59,49,0,148,36,50,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (685,59,50,0,149,49,51,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (686,59,51,0,150,50,52,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (687,59,52,0,151,51,53,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (688,59,53,0,152,52,54,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (689,59,54,0,153,53,55,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (690,59,55,0,154,54,56,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (691,59,56,0,155,55,57,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (692,59,57,0,156,56,58,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (693,59,58,0,157,57,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (694,59,59,0,158,58,60,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (695,59,60,0,159,59,61,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (696,59,61,0,160,60,62,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (697,59,62,0,161,61,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (698,59,59,0,162,62,63,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (699,59,63,0,163,59,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (700,59,43,0,164,63,64,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (701,59,64,0,165,43,65,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (702,59,65,0,166,64,66,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (703,59,66,0,167,65,67,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (704,59,67,0,168,66,68,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (705,59,68,0,169,67,69,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (706,59,69,0,170,68,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (707,59,14,0,171,69,12,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (708,59,12,0,172,14,13,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (709,59,13,0,173,12,14,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (710,59,14,0,174,13,15,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (711,59,15,0,175,14,16,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (712,59,16,0,176,15,17,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (713,59,17,0,177,16,18,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (714,59,18,0,178,17,19,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (715,59,19,0,179,18,20,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (716,59,20,0,180,19,21,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (717,59,21,0,181,20,22,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (718,59,22,0,182,21,23,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (719,59,23,0,183,22,24,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (720,59,24,0,184,23,25,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (721,59,25,0,185,24,26,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (722,59,26,0,186,25,27,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (723,59,27,0,187,26,28,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (724,59,28,0,188,27,29,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (725,59,29,0,189,28,30,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (726,59,30,0,190,29,31,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (727,59,31,0,191,30,32,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (728,59,32,0,192,31,33,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (729,59,33,0,193,32,34,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (730,59,34,0,194,33,23,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (731,59,23,0,195,34,35,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (732,59,35,0,196,23,36,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (733,59,36,0,197,35,37,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (734,59,37,0,198,36,38,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (735,59,38,0,199,37,39,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (736,59,39,0,200,38,40,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (737,59,40,0,201,39,41,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (738,59,41,0,202,40,42,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (739,59,42,0,203,41,43,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (740,59,43,0,204,42,44,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (741,59,44,0,205,43,45,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (742,59,45,0,206,44,46,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (743,59,46,0,207,45,47,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (744,59,47,0,208,46,37,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (745,59,37,0,209,47,48,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (746,59,48,0,210,37,36,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (747,59,36,0,211,48,49,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (748,59,49,0,212,36,50,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (749,59,50,0,213,49,51,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (750,59,51,0,214,50,52,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (751,59,52,0,215,51,53,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (752,59,53,0,216,52,54,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (753,59,54,0,217,53,55,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (754,59,55,0,218,54,56,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (755,59,56,0,219,55,57,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (756,59,57,0,220,56,58,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (757,59,58,0,221,57,59,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (758,59,59,0,222,58,60,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (759,59,60,0,223,59,61,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (760,59,61,0,224,60,62,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (761,59,62,0,225,61,59,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (762,59,59,0,226,62,63,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (763,59,63,0,227,59,43,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (764,59,43,0,228,63,64,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (765,59,64,0,229,43,65,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (766,59,65,0,230,64,66,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (767,59,66,0,231,65,67,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (768,59,67,0,232,66,68,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (769,59,68,0,233,67,69,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (770,59,69,0,234,68,70,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (771,59,70,0,235,69,0,2,123,0,0);
INSERT INTO ezsearch_object_word_link VALUES (772,60,199,0,0,0,108,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (773,60,108,0,1,199,217,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (774,60,217,0,2,108,218,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (775,60,218,0,3,217,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (776,60,219,0,4,218,220,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (777,60,220,0,5,219,221,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (778,60,221,0,6,220,222,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (779,60,222,0,7,221,223,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (780,60,223,0,8,222,224,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (781,60,224,0,9,223,225,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (782,60,225,0,10,224,191,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (783,60,191,0,11,225,226,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (784,60,226,0,12,191,227,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (785,60,227,0,13,226,228,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (786,60,228,0,14,227,203,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (787,60,203,0,15,228,229,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (788,60,229,0,16,203,230,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (789,60,230,0,17,229,231,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (790,60,231,0,18,230,232,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (791,60,232,0,19,231,233,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (792,60,233,0,20,232,234,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (793,60,234,0,21,233,210,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (794,60,210,0,22,234,235,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (795,60,235,0,23,210,236,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (796,60,236,0,24,235,237,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (797,60,237,0,25,236,191,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (798,60,191,0,26,237,238,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (799,60,238,0,27,191,194,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (800,60,194,0,28,238,239,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (801,60,239,0,29,194,240,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (802,60,240,0,30,239,241,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (803,60,241,0,31,240,242,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (804,60,242,0,32,241,243,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (805,60,243,0,33,242,191,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (806,60,191,0,34,243,244,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (807,60,244,0,35,191,199,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (808,60,199,0,36,244,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (809,60,219,0,37,199,245,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (810,60,245,0,38,219,246,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (811,60,246,0,39,245,199,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (812,60,199,0,40,246,108,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (813,60,108,0,41,199,217,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (814,60,217,0,42,108,218,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (815,60,218,0,43,217,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (816,60,219,0,44,218,220,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (817,60,220,0,45,219,221,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (818,60,221,0,46,220,222,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (819,60,222,0,47,221,223,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (820,60,223,0,48,222,224,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (821,60,224,0,49,223,225,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (822,60,225,0,50,224,191,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (823,60,191,0,51,225,226,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (824,60,226,0,52,191,227,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (825,60,227,0,53,226,228,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (826,60,228,0,54,227,203,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (827,60,203,0,55,228,229,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (828,60,229,0,56,203,230,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (829,60,230,0,57,229,231,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (830,60,231,0,58,230,232,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (831,60,232,0,59,231,233,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (832,60,233,0,60,232,234,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (833,60,234,0,61,233,210,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (834,60,210,0,62,234,235,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (835,60,235,0,63,210,236,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (836,60,236,0,64,235,237,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (837,60,237,0,65,236,191,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (838,60,191,0,66,237,238,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (839,60,238,0,67,191,194,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (840,60,194,0,68,238,239,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (841,60,239,0,69,194,240,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (842,60,240,0,70,239,241,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (843,60,241,0,71,240,242,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (844,60,242,0,72,241,243,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (845,60,243,0,73,242,191,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (846,60,191,0,74,243,244,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (847,60,244,0,75,191,199,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (848,60,199,0,76,244,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (849,60,219,0,77,199,245,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (850,60,245,0,78,219,70,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (851,60,70,0,79,245,0,2,123,0,0);
INSERT INTO ezsearch_object_word_link VALUES (852,61,12,0,0,0,13,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (853,61,13,0,1,12,14,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (854,61,14,0,2,13,15,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (855,61,15,0,3,14,16,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (856,61,16,0,4,15,17,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (857,61,17,0,5,16,18,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (858,61,18,0,6,17,19,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (859,61,19,0,7,18,20,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (860,61,20,0,8,19,21,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (861,61,21,0,9,20,22,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (862,61,22,0,10,21,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (863,61,23,0,11,22,24,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (864,61,24,0,12,23,25,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (865,61,25,0,13,24,26,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (866,61,26,0,14,25,27,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (867,61,27,0,15,26,28,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (868,61,28,0,16,27,29,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (869,61,29,0,17,28,30,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (870,61,30,0,18,29,31,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (871,61,31,0,19,30,32,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (872,61,32,0,20,31,33,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (873,61,33,0,21,32,34,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (874,61,34,0,22,33,23,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (875,61,23,0,23,34,35,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (876,61,35,0,24,23,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (877,61,36,0,25,35,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (878,61,37,0,26,36,38,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (879,61,38,0,27,37,39,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (880,61,39,0,28,38,40,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (881,61,40,0,29,39,41,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (882,61,41,0,30,40,42,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (883,61,42,0,31,41,43,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (884,61,43,0,32,42,44,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (885,61,44,0,33,43,45,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (886,61,45,0,34,44,46,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (887,61,46,0,35,45,47,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (888,61,47,0,36,46,37,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (889,61,37,0,37,47,48,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (890,61,48,0,38,37,36,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (891,61,36,0,39,48,49,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (892,61,49,0,40,36,50,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (893,61,50,0,41,49,51,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (894,61,51,0,42,50,52,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (895,61,52,0,43,51,53,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (896,61,53,0,44,52,54,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (897,61,54,0,45,53,55,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (898,61,55,0,46,54,56,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (899,61,56,0,47,55,57,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (900,61,57,0,48,56,58,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (901,61,58,0,49,57,59,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (902,61,59,0,50,58,60,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (903,61,60,0,51,59,61,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (904,61,61,0,52,60,62,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (905,61,62,0,53,61,247,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (906,61,247,0,54,62,248,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (907,61,248,0,55,247,204,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (908,61,204,0,56,248,249,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (909,61,249,0,57,204,250,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (910,61,250,0,58,249,191,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (911,61,191,0,59,250,251,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (912,61,251,0,60,191,252,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (913,61,252,0,61,251,253,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (914,61,253,0,62,252,254,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (915,61,254,0,63,253,255,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (916,61,255,0,64,254,11,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (917,61,11,0,65,255,256,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (918,61,256,0,66,11,257,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (919,61,257,0,67,256,258,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (920,61,258,0,68,257,259,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (921,61,259,0,69,258,243,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (922,61,243,0,70,259,260,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (923,61,260,0,71,243,191,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (924,61,191,0,72,260,261,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (925,61,261,0,73,191,262,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (926,61,262,0,74,261,263,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (927,61,263,0,75,262,264,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (928,61,264,0,76,263,234,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (929,61,234,0,77,264,256,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (930,61,256,0,78,234,70,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (931,61,70,0,79,256,0,2,123,0,0);
INSERT INTO ezsearch_object_word_link VALUES (932,62,11,0,0,0,265,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (933,62,265,0,1,11,266,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (934,62,266,0,2,265,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (935,63,267,0,0,0,268,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (936,63,268,0,1,267,269,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (937,63,269,0,2,268,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (938,64,270,0,0,0,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (939,65,271,0,0,0,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (940,66,272,0,0,0,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (941,67,269,0,0,0,0,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (942,68,273,0,0,0,274,22,142,0,0);
INSERT INTO ezsearch_object_word_link VALUES (943,68,274,0,1,273,206,22,142,0,0);
INSERT INTO ezsearch_object_word_link VALUES (944,68,206,0,2,274,275,22,143,0,0);
INSERT INTO ezsearch_object_word_link VALUES (945,68,275,0,3,206,276,22,143,0,0);
INSERT INTO ezsearch_object_word_link VALUES (946,68,276,0,4,275,277,22,144,0,0);
INSERT INTO ezsearch_object_word_link VALUES (947,68,277,0,5,276,278,22,144,0,0);
INSERT INTO ezsearch_object_word_link VALUES (948,68,278,0,6,277,279,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (949,68,279,0,7,278,59,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (950,68,59,0,8,279,280,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (951,68,280,0,9,59,281,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (952,68,281,0,10,280,30,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (953,68,30,0,11,281,45,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (954,68,45,0,12,30,57,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (955,68,57,0,13,45,282,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (956,68,282,0,14,57,283,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (957,68,283,0,15,282,284,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (958,68,284,0,16,283,30,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (959,68,30,0,17,284,285,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (960,68,285,0,18,30,286,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (961,68,286,0,19,285,287,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (962,68,287,0,20,286,162,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (963,68,162,0,21,287,43,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (964,68,43,0,22,162,288,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (965,68,288,0,23,43,289,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (966,68,289,0,24,288,23,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (967,68,23,0,25,289,290,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (968,68,290,0,26,23,291,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (969,68,291,0,27,290,206,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (970,68,206,0,28,291,206,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (971,68,206,0,29,206,275,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (972,68,275,0,30,206,292,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (973,68,292,0,31,275,293,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (974,68,293,0,32,292,294,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (975,68,294,0,33,293,88,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (976,68,88,0,34,294,295,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (977,68,295,0,35,88,296,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (978,68,296,0,36,295,91,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (979,68,91,0,37,296,297,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (980,68,297,0,38,91,298,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (981,68,298,0,39,297,152,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (982,68,152,0,40,298,299,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (983,68,299,0,41,152,300,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (984,68,300,0,42,299,301,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (985,68,301,0,43,300,302,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (986,68,302,0,44,301,27,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (987,68,27,0,45,302,303,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (988,68,303,0,46,27,109,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (989,68,109,0,47,303,304,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (990,68,304,0,48,109,305,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (991,68,305,0,49,304,306,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (992,68,306,0,50,305,307,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (993,68,307,0,51,306,308,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (994,68,308,0,52,307,45,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (995,68,45,0,53,308,206,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (996,68,206,0,54,45,309,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (997,68,309,0,55,206,310,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (998,68,310,0,56,309,311,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (999,68,311,0,57,310,312,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1000,68,312,0,58,311,313,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1001,68,313,0,59,312,314,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1002,68,314,0,60,313,301,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1003,68,301,0,61,314,315,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1004,68,315,0,62,301,316,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1005,68,316,0,63,315,279,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1006,68,279,0,64,316,317,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1007,68,317,0,65,279,318,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1008,68,318,0,66,317,319,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1009,68,319,0,67,318,69,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1010,68,69,0,68,319,134,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1011,68,134,0,69,69,320,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1012,68,320,0,70,134,321,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1013,68,321,0,71,320,322,22,146,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1014,68,322,0,72,321,323,22,146,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1015,68,323,0,73,322,324,22,146,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1016,68,324,0,74,323,325,22,146,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1017,68,325,0,75,324,326,22,146,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1018,68,326,0,76,325,327,22,147,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1019,68,327,0,77,326,0,22,147,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1020,69,11,0,0,0,328,22,142,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1021,69,328,0,1,11,206,22,142,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1022,69,206,0,2,328,309,22,143,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1023,69,309,0,3,206,278,22,143,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1024,69,278,0,4,309,279,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1025,69,279,0,5,278,59,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1026,69,59,0,6,279,280,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1027,69,280,0,7,59,281,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1028,69,281,0,8,280,30,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1029,69,30,0,9,281,45,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1030,69,45,0,10,30,57,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1031,69,57,0,11,45,282,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1032,69,282,0,12,57,283,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1033,69,283,0,13,282,284,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1034,69,284,0,14,283,30,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1035,69,30,0,15,284,285,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1036,69,285,0,16,30,286,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1037,69,286,0,17,285,287,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1038,69,287,0,18,286,162,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1039,69,162,0,19,287,43,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1040,69,43,0,20,162,288,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1041,69,288,0,21,43,289,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1042,69,289,0,22,288,23,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1043,69,23,0,23,289,290,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1044,69,290,0,24,23,291,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1045,69,291,0,25,290,292,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1046,69,292,0,26,291,293,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1047,69,293,0,27,292,294,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1048,69,294,0,28,293,88,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1049,69,88,0,29,294,295,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1050,69,295,0,30,88,296,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1051,69,296,0,31,295,91,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1052,69,91,0,32,296,297,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1053,69,297,0,33,91,298,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1054,69,298,0,34,297,152,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1055,69,152,0,35,298,299,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1056,69,299,0,36,152,300,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1057,69,300,0,37,299,301,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1058,69,301,0,38,300,302,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1059,69,302,0,39,301,27,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1060,69,27,0,40,302,303,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1061,69,303,0,41,27,109,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1062,69,109,0,42,303,304,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1063,69,304,0,43,109,305,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1064,69,305,0,44,304,306,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1065,69,306,0,45,305,307,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1066,69,307,0,46,306,308,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1067,69,308,0,47,307,45,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1068,69,45,0,48,308,206,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1069,69,206,0,49,45,309,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1070,69,309,0,50,206,310,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1071,69,310,0,51,309,311,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1072,69,311,0,52,310,312,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1073,69,312,0,53,311,313,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1074,69,313,0,54,312,314,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1075,69,314,0,55,313,301,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1076,69,301,0,56,314,315,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1077,69,315,0,57,301,316,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1078,69,316,0,58,315,279,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1079,69,279,0,59,316,317,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1080,69,317,0,60,279,318,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1081,69,318,0,61,317,319,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1082,69,319,0,62,318,69,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1083,69,69,0,63,319,134,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1084,69,134,0,64,69,320,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1085,69,320,0,65,134,329,22,145,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1086,69,329,0,66,320,330,22,146,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1087,69,330,0,67,329,331,22,147,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1088,69,331,0,68,330,0,22,147,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1089,70,332,0,0,0,333,23,149,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1090,70,333,0,1,332,334,23,151,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1091,70,334,0,2,333,335,23,152,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1092,70,335,0,3,334,286,23,152,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1093,70,286,0,4,335,287,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1094,70,287,0,5,286,162,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1095,70,162,0,6,287,43,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1096,70,43,0,7,162,288,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1097,70,288,0,8,43,289,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1098,70,289,0,9,288,23,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1099,70,23,0,10,289,290,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1100,70,290,0,11,23,291,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1101,70,291,0,12,290,206,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1102,70,206,0,13,291,206,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1103,70,206,0,14,206,275,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1104,70,275,0,15,206,292,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1105,70,292,0,16,275,293,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1106,70,293,0,17,292,294,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1107,70,294,0,18,293,88,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1108,70,88,0,19,294,295,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1109,70,295,0,20,88,296,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1110,70,296,0,21,295,91,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1111,70,91,0,22,296,297,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1112,70,297,0,23,91,298,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1113,70,298,0,24,297,152,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1114,70,152,0,25,298,299,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1115,70,299,0,26,152,300,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1116,70,300,0,27,299,301,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1117,70,301,0,28,300,302,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1118,70,302,0,29,301,27,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1119,70,27,0,30,302,303,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1120,70,303,0,31,27,109,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1121,70,109,0,32,303,304,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1122,70,304,0,33,109,305,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1123,70,305,0,34,304,306,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1124,70,306,0,35,305,307,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1125,70,307,0,36,306,308,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1126,70,308,0,37,307,45,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1127,70,45,0,38,308,206,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1128,70,206,0,39,45,309,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1129,70,309,0,40,206,310,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1130,70,310,0,41,309,311,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1131,70,311,0,42,310,312,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1132,70,312,0,43,311,313,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1133,70,313,0,44,312,314,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1134,70,314,0,45,313,301,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1135,70,301,0,46,314,315,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1136,70,315,0,47,301,316,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1137,70,316,0,48,315,279,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1138,70,279,0,49,316,317,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1139,70,317,0,50,279,318,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1140,70,318,0,51,317,319,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1141,70,319,0,52,318,69,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1142,70,69,0,53,319,134,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1143,70,134,0,54,69,320,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1144,70,320,0,55,134,336,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1145,70,336,0,56,320,337,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1146,70,337,0,57,336,338,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1147,70,338,0,58,337,339,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1148,70,339,0,59,338,340,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1149,70,340,0,60,339,341,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1150,70,341,0,61,340,342,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1151,70,342,0,62,341,343,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1152,70,343,0,63,342,293,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1153,70,293,0,64,343,69,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1154,70,69,0,65,293,344,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1155,70,344,0,66,69,345,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1156,70,345,0,67,344,346,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1157,70,346,0,68,345,347,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1158,70,347,0,69,346,348,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1159,70,348,0,70,347,18,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1160,70,18,0,71,348,349,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1161,70,349,0,72,18,350,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1162,70,350,0,73,349,351,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1163,70,351,0,74,350,352,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1164,70,352,0,75,351,37,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1165,70,37,0,76,352,135,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1166,70,135,0,77,37,106,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1167,70,106,0,78,135,353,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1168,70,353,0,79,106,318,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1169,70,318,0,80,353,301,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1170,70,301,0,81,318,354,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1171,70,354,0,82,301,355,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1172,70,355,0,83,354,356,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1173,70,356,0,84,355,357,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1174,70,357,0,85,356,358,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1175,70,358,0,86,357,359,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1176,70,359,0,87,358,338,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1177,70,338,0,88,359,360,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1178,70,360,0,89,338,141,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1179,70,141,0,90,360,361,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1180,70,361,0,91,141,362,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1181,70,362,0,92,361,162,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1182,70,162,0,93,362,363,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1183,70,363,0,94,162,364,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1184,70,364,0,95,363,365,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1185,70,365,0,96,364,43,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1186,70,43,0,97,365,28,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1187,70,28,0,98,43,366,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1188,70,366,0,99,28,181,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1189,70,181,0,100,366,367,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1190,70,367,0,101,181,181,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1191,70,181,0,102,367,368,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1192,70,368,0,103,181,134,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1193,70,134,0,104,368,369,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1194,70,369,0,105,134,43,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1195,70,43,0,106,369,370,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1196,70,370,0,107,43,371,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1197,70,371,0,108,370,363,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1198,70,363,0,109,371,372,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1199,70,372,0,110,363,373,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1200,70,373,0,111,372,306,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1201,70,306,0,112,373,374,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1202,70,374,0,113,306,375,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1203,70,375,0,114,374,376,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1204,70,376,0,115,375,377,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1205,70,377,0,116,376,378,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1206,70,378,0,117,377,379,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1207,70,379,0,118,378,380,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1208,70,380,0,119,379,381,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1209,70,381,0,120,380,382,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1210,70,382,0,121,381,162,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1211,70,162,0,122,382,304,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1212,70,304,0,123,162,383,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1213,70,383,0,124,304,181,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1214,70,181,0,125,383,384,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1215,70,384,0,126,181,385,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1216,70,385,0,127,384,386,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1217,70,386,0,128,385,387,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1218,70,387,0,129,386,388,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1219,70,388,0,130,387,389,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1220,70,389,0,131,388,390,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1221,70,390,0,132,389,391,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1222,70,391,0,133,390,352,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1223,70,352,0,134,391,296,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1224,70,296,0,135,352,392,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1225,70,392,0,136,296,393,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1226,70,393,0,137,392,394,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1227,70,394,0,138,393,378,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1228,70,378,0,139,394,395,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1229,70,395,0,140,378,86,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1230,70,86,0,141,395,378,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1231,70,378,0,142,86,396,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1232,70,396,0,143,378,397,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1233,70,397,0,144,396,398,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1234,70,398,0,145,397,399,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1235,70,399,0,146,398,400,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1236,70,400,0,147,399,401,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1237,70,401,0,148,400,402,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1238,70,402,0,149,401,348,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1239,70,348,0,150,402,206,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1240,70,206,0,151,348,403,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1241,70,403,0,152,206,404,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1242,70,404,0,153,403,405,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1243,70,405,0,154,404,48,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1244,70,48,0,155,405,406,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1245,70,406,0,156,48,407,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1246,70,407,0,157,406,0,23,153,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2065,81,743,0,15,194,744,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2064,81,194,0,14,742,743,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2063,81,742,0,13,741,194,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2062,81,741,0,12,181,742,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2061,81,181,0,11,738,741,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2060,81,738,0,10,331,181,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2059,81,331,0,9,737,738,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2058,81,737,0,8,740,331,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2057,81,740,0,7,546,737,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2056,81,546,0,6,608,740,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2055,81,608,0,5,707,546,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2054,81,707,0,4,739,608,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2053,81,739,0,3,636,707,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2052,81,636,0,2,738,739,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2051,81,738,0,1,737,636,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2050,81,737,0,0,0,738,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2049,80,736,0,61,735,0,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2048,80,735,0,60,734,736,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2047,80,734,0,59,733,735,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2046,80,733,0,58,200,734,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2045,80,200,0,57,254,733,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2044,80,254,0,56,735,200,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2043,80,735,0,55,734,254,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2042,80,734,0,54,733,735,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2041,80,733,0,53,200,734,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2040,80,200,0,52,254,733,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2039,80,254,0,51,735,200,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2038,80,735,0,50,734,254,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2037,80,734,0,49,733,735,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2036,80,733,0,48,200,734,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2035,80,200,0,47,254,733,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2034,80,254,0,46,732,200,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2033,80,732,0,45,731,254,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2032,80,731,0,44,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2031,80,732,0,43,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2030,80,731,0,42,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2029,80,732,0,41,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2028,80,731,0,40,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2027,80,732,0,39,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2026,80,731,0,38,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2025,80,732,0,37,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2024,80,731,0,36,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2023,80,732,0,35,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2022,80,731,0,34,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2021,80,732,0,33,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2020,80,731,0,32,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2019,80,732,0,31,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2018,80,731,0,30,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2017,80,732,0,29,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2016,80,731,0,28,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2015,80,732,0,27,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2014,80,731,0,26,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2013,80,732,0,25,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2012,80,731,0,24,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2011,80,732,0,23,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2010,80,731,0,22,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2009,80,732,0,21,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2008,80,731,0,20,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2007,80,732,0,19,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2006,80,731,0,18,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2005,80,732,0,17,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2004,80,731,0,16,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2003,80,732,0,15,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2002,80,731,0,14,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2001,80,732,0,13,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2000,80,731,0,12,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1999,80,732,0,11,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1998,80,731,0,10,732,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1997,80,732,0,9,731,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1996,80,731,0,8,132,732,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1995,80,132,0,7,546,731,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1994,80,546,0,6,108,132,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1993,80,108,0,5,179,546,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1992,80,179,0,4,132,108,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1991,80,132,0,3,172,179,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1990,80,172,0,2,183,132,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1989,80,183,0,1,182,172,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1988,80,182,0,0,0,183,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1983,29,76,0,0,0,8,1,4,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1965,79,189,0,7,177,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1964,79,177,0,6,11,189,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1963,79,11,0,5,108,177,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1962,79,108,0,4,179,11,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1961,79,179,0,3,197,108,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1960,79,197,0,2,715,179,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1959,79,715,0,1,191,197,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1958,79,191,0,0,0,715,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1957,78,70,0,9,172,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1956,78,172,0,8,10,70,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1955,78,10,0,7,178,172,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1954,78,178,0,6,177,10,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1953,78,177,0,5,11,178,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1952,78,11,0,4,108,177,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1951,78,108,0,3,179,11,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1950,78,179,0,2,178,108,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1949,78,178,0,1,177,179,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1946,74,132,0,2,172,0,8,129,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1945,74,172,0,1,80,132,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1944,74,80,0,0,0,172,8,128,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1943,72,722,0,38,721,0,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1942,72,721,0,37,720,722,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1904,73,6,0,17,703,0,2,123,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1903,73,703,0,16,688,6,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1902,73,688,0,15,702,703,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1901,73,702,0,14,219,688,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1900,73,219,0,13,701,702,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1899,73,701,0,12,201,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1898,73,201,0,11,243,701,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1897,73,243,0,10,693,201,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1896,73,693,0,9,700,243,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1895,73,700,0,8,239,693,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1894,73,239,0,7,686,700,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1893,73,686,0,6,699,239,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1892,73,699,0,5,219,686,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1891,73,219,0,4,698,699,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1890,73,698,0,3,108,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1889,73,108,0,2,203,698,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1888,73,203,0,1,247,108,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1887,73,247,0,0,0,203,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1941,72,720,0,36,191,721,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1940,72,191,0,35,719,720,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1939,72,719,0,34,693,191,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1938,72,693,0,33,718,719,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1937,72,718,0,32,717,693,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1936,72,717,0,31,11,718,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1935,72,11,0,30,181,717,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1934,72,181,0,29,233,11,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1933,72,233,0,28,716,181,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1932,72,716,0,27,591,233,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1931,72,591,0,26,715,716,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1930,72,715,0,25,243,591,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1929,72,243,0,24,688,715,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1928,72,688,0,23,714,243,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1927,72,714,0,22,243,688,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1926,72,243,0,21,239,714,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1925,72,239,0,20,686,243,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1924,72,686,0,19,713,239,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1923,72,713,0,18,219,686,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1922,72,219,0,17,712,713,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1921,72,712,0,16,108,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1920,72,108,0,15,203,712,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1919,72,203,0,14,711,108,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1918,72,711,0,13,181,203,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1917,72,181,0,12,710,711,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1916,72,710,0,11,709,181,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1915,72,709,0,10,219,710,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1914,72,219,0,9,599,709,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1913,72,599,0,8,708,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1912,72,708,0,7,108,599,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1911,72,108,0,6,707,708,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1910,72,707,0,5,704,108,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1909,72,704,0,4,706,707,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1908,72,706,0,3,705,704,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1907,72,705,0,2,108,706,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1906,72,108,0,1,704,705,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1905,72,704,0,0,0,108,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1812,71,6,0,204,587,0,2,123,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1811,71,587,0,203,653,6,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1810,71,653,0,202,655,587,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1809,71,655,0,201,654,653,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1808,71,654,0,200,653,655,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1807,71,653,0,199,652,654,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1806,71,652,0,198,639,653,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1805,71,639,0,197,651,652,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1804,71,651,0,196,650,639,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1803,71,650,0,195,243,651,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1802,71,243,0,194,649,650,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1801,71,649,0,193,648,243,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1800,71,648,0,192,546,649,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1799,71,546,0,191,261,648,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1798,71,261,0,190,647,546,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1797,71,647,0,189,251,261,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1796,71,251,0,188,607,647,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1795,71,607,0,187,646,251,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1794,71,646,0,186,645,607,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1793,71,645,0,185,204,646,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1792,71,204,0,184,582,645,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1791,71,582,0,183,179,204,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1790,71,179,0,182,181,582,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1789,71,181,0,181,644,179,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1788,71,644,0,180,184,181,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1787,71,184,0,179,643,644,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1786,71,643,0,178,251,184,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1785,71,251,0,177,532,643,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1784,71,532,0,176,642,251,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1783,71,642,0,175,641,532,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1782,71,641,0,174,234,642,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1781,71,234,0,173,640,641,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1780,71,640,0,172,639,234,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1779,71,639,0,171,638,640,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1778,71,638,0,170,637,639,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1777,71,637,0,169,636,638,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1776,71,636,0,168,222,637,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1775,71,222,0,167,636,636,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1774,71,636,0,166,227,222,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1773,71,227,0,165,108,636,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1772,71,108,0,164,194,227,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1771,71,194,0,163,634,108,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1770,71,634,0,162,635,194,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1769,71,635,0,161,219,634,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1768,71,219,0,160,198,635,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1767,71,198,0,159,634,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1766,71,634,0,158,633,198,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1765,71,633,0,157,261,634,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1764,71,261,0,156,591,633,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1763,71,591,0,155,219,261,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1762,71,219,0,154,632,591,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1761,71,632,0,153,631,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1760,71,631,0,152,219,632,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1759,71,219,0,151,630,631,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1758,71,630,0,150,108,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1757,71,108,0,149,203,630,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1756,71,203,0,148,629,108,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1755,71,629,0,147,247,203,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1754,71,247,0,146,586,629,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1753,71,586,0,145,251,247,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1752,71,251,0,144,587,586,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1751,71,587,0,143,628,251,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1750,71,628,0,142,627,587,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1749,71,627,0,141,626,628,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1748,71,626,0,140,625,627,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1747,71,625,0,139,546,626,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1746,71,546,0,138,204,625,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1745,71,204,0,137,203,546,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1744,71,203,0,136,624,204,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1743,71,624,0,135,623,203,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1742,71,623,0,134,8,624,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1741,71,8,0,133,219,623,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1740,71,219,0,132,622,8,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1739,71,622,0,131,621,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1738,71,621,0,130,578,622,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1737,71,578,0,129,620,621,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1736,71,620,0,128,619,578,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1735,71,619,0,127,618,620,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1734,71,618,0,126,578,619,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1733,71,578,0,125,237,618,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1732,71,237,0,124,617,578,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1731,71,617,0,123,616,237,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1730,71,616,0,122,615,617,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1729,71,615,0,121,548,616,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1728,71,548,0,120,614,615,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1727,71,614,0,119,613,548,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1726,71,613,0,118,537,614,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1725,71,537,0,117,612,613,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1724,71,612,0,116,611,537,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1723,71,611,0,115,610,612,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1722,71,610,0,114,609,611,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1721,71,609,0,113,608,610,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1720,71,608,0,112,607,609,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1719,71,607,0,111,237,608,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1718,71,237,0,110,606,607,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1717,71,606,0,109,605,237,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1716,71,605,0,108,581,606,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1715,71,581,0,107,219,605,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1714,71,219,0,106,251,581,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1713,71,251,0,105,604,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1712,71,604,0,104,603,251,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1711,71,603,0,103,194,604,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1710,71,194,0,102,602,603,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1709,71,602,0,101,601,194,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1708,71,601,0,100,600,602,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1707,71,600,0,99,11,601,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1706,71,11,0,98,599,600,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1705,71,599,0,97,219,11,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1704,71,219,0,96,598,599,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1703,71,598,0,95,597,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1702,71,597,0,94,596,598,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1701,71,596,0,93,595,597,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1700,71,595,0,92,594,596,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1699,71,594,0,91,76,595,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1698,71,76,0,90,11,594,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1697,71,11,0,89,593,76,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1696,71,593,0,88,563,11,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1695,71,563,0,87,592,593,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1694,71,592,0,86,11,563,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1693,71,11,0,85,591,592,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1692,71,591,0,84,561,11,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1691,71,561,0,83,203,591,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1690,71,203,0,82,590,561,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1689,71,590,0,81,545,203,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1688,71,545,0,80,179,590,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1687,71,179,0,79,589,545,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1686,71,589,0,78,588,179,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1685,71,588,0,77,204,589,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1684,71,204,0,76,587,588,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1683,71,587,0,75,586,204,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1682,71,586,0,74,251,587,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1681,71,251,0,73,585,586,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1680,71,585,0,72,532,251,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1679,71,532,0,71,584,585,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1678,71,584,0,70,536,532,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1677,71,536,0,69,583,584,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1676,71,583,0,68,582,536,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1675,71,582,0,67,544,583,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1674,71,544,0,66,581,582,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1673,71,581,0,65,11,544,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1672,71,11,0,64,580,581,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1671,71,580,0,63,579,11,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1670,71,579,0,62,578,580,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1669,71,578,0,61,577,579,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1668,71,577,0,60,576,578,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1667,71,576,0,59,575,577,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1666,71,575,0,58,574,576,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1665,71,574,0,57,573,575,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1664,71,573,0,56,28,574,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1663,71,28,0,55,572,573,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1662,71,572,0,54,219,28,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1661,71,219,0,53,571,572,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1660,71,571,0,52,570,219,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1659,71,570,0,51,569,571,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1658,71,569,0,50,568,570,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1657,71,568,0,49,567,569,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1656,71,567,0,48,203,568,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1655,71,203,0,47,566,567,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1654,71,566,0,46,565,203,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1653,71,565,0,45,564,566,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1652,71,564,0,44,563,565,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1651,71,563,0,43,562,564,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1650,71,562,0,42,561,563,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1649,71,561,0,41,560,562,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1648,71,560,0,40,553,561,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1647,71,553,0,39,559,560,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1646,71,559,0,38,181,553,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1645,71,181,0,37,558,559,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1644,71,558,0,36,557,181,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1643,71,557,0,35,219,558,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1642,71,219,0,34,556,557,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1641,71,556,0,33,555,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1640,71,555,0,32,554,556,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1639,71,554,0,31,234,555,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1638,71,234,0,30,553,554,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1637,71,553,0,29,552,234,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1636,71,552,0,28,11,553,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1635,71,11,0,27,551,552,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1634,71,551,0,26,234,11,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1633,71,234,0,25,550,551,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1632,71,550,0,24,549,234,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1631,71,549,0,23,548,550,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1630,71,548,0,22,219,549,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1629,71,219,0,21,547,548,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1628,71,547,0,20,546,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1627,71,546,0,19,181,547,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1626,71,181,0,18,545,546,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1625,71,545,0,17,544,181,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1624,71,544,0,16,543,545,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1623,71,543,0,15,11,544,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1622,71,11,0,14,219,543,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1621,71,219,0,13,542,11,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1620,71,542,0,12,541,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1619,71,541,0,11,219,542,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1618,71,219,0,10,540,541,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1617,71,540,0,9,539,219,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1616,71,539,0,8,233,540,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1615,71,233,0,7,538,539,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1614,71,538,0,6,537,233,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1613,71,537,0,5,536,538,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1612,71,536,0,4,535,537,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1611,71,535,0,3,534,536,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1610,71,534,0,2,533,535,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1609,71,533,0,1,532,534,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (1608,71,532,0,0,0,533,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2066,81,744,0,16,743,745,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2067,81,745,0,17,744,247,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2068,81,247,0,18,745,746,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2069,81,746,0,19,247,201,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2070,81,201,0,20,746,747,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2071,81,747,0,21,201,636,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2072,81,636,0,22,747,739,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2073,81,739,0,23,636,220,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2074,81,220,0,24,739,203,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2075,81,203,0,25,220,598,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2076,81,598,0,26,203,544,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2077,81,544,0,27,598,748,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2078,81,748,0,28,544,77,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2079,81,77,0,29,748,0,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2080,82,721,0,0,0,749,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2081,82,749,0,1,721,750,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2082,82,750,0,2,749,10,2,1,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2083,82,10,0,3,750,230,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2084,82,230,0,4,10,751,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2085,82,751,0,5,230,198,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2086,82,198,0,6,751,591,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2087,82,591,0,7,198,752,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2088,82,752,0,8,591,753,2,120,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2089,82,753,0,9,752,201,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2090,82,201,0,10,753,200,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2091,82,200,0,11,201,754,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2092,82,754,0,12,200,230,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2093,82,230,0,13,754,755,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2094,82,755,0,14,230,751,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2095,82,751,0,15,755,0,2,121,0,0);
INSERT INTO ezsearch_object_word_link VALUES (2642,83,234,0,175,1001,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2641,83,1001,0,174,1000,234,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2640,83,1000,0,173,999,1001,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2639,83,999,0,172,999,1000,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2638,83,999,0,171,998,999,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2637,83,998,0,170,997,999,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2636,83,997,0,169,564,998,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2110,84,764,0,0,0,0,1,4,1035892777,2);
INSERT INTO ezsearch_object_word_link VALUES (2106,93,760,0,0,0,0,1,4,1035887037,2);
INSERT INTO ezsearch_object_word_link VALUES (2107,98,761,0,0,0,0,1,4,1035887250,2);
INSERT INTO ezsearch_object_word_link VALUES (2108,103,762,0,0,0,0,1,4,1035887800,2);
INSERT INTO ezsearch_object_word_link VALUES (2635,83,564,0,168,996,997,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2634,83,996,0,167,591,564,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2633,83,591,0,166,637,996,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2632,83,637,0,165,995,591,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2631,83,995,0,164,994,637,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2630,83,994,0,163,993,995,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2629,83,993,0,162,591,994,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2628,83,591,0,161,602,993,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2627,83,602,0,160,992,591,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2626,83,992,0,159,108,602,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2625,83,108,0,158,194,992,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2624,83,194,0,157,231,108,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2623,83,231,0,156,11,194,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2622,83,11,0,155,991,231,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2621,83,991,0,154,637,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2620,83,637,0,153,990,991,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2619,83,990,0,152,989,637,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2618,83,989,0,151,988,990,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2617,83,988,0,150,931,989,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2616,83,931,0,149,591,988,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2615,83,591,0,148,987,931,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2614,83,987,0,147,591,591,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2613,83,591,0,146,602,987,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2612,83,602,0,145,986,591,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2611,83,986,0,144,108,602,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2610,83,108,0,143,194,986,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2609,83,194,0,142,931,108,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2608,83,931,0,141,761,194,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2607,83,761,0,140,985,931,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2606,83,985,0,139,762,761,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2605,83,762,0,138,985,985,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2604,83,985,0,137,759,762,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2603,83,759,0,136,985,985,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2602,83,985,0,135,764,759,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2601,83,764,0,134,985,985,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2600,83,985,0,133,760,764,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2599,83,760,0,132,985,985,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2598,83,985,0,131,984,760,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2597,83,984,0,130,983,985,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2596,83,983,0,129,11,984,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2595,83,11,0,128,982,983,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2594,83,982,0,127,844,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2593,83,844,0,126,843,982,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2592,83,843,0,125,930,844,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2591,83,930,0,124,981,843,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2590,83,981,0,123,980,930,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2589,83,980,0,122,954,981,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2588,83,954,0,121,248,980,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2587,83,248,0,120,979,954,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2586,83,979,0,119,11,248,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2585,83,11,0,118,971,979,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2584,83,971,0,117,219,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2583,83,219,0,116,591,971,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2582,83,591,0,115,243,219,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2581,83,243,0,114,978,591,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2580,83,978,0,113,11,243,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2579,83,11,0,112,977,978,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2578,83,977,0,111,11,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2577,83,11,0,110,976,977,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2576,83,976,0,109,11,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2575,83,11,0,108,236,976,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2574,83,236,0,107,975,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2573,83,975,0,106,974,236,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2572,83,974,0,105,973,975,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2571,83,973,0,104,972,974,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2570,83,972,0,103,940,973,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2569,83,940,0,102,11,972,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2568,83,11,0,101,971,940,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2567,83,971,0,100,970,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2566,83,970,0,99,969,971,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2565,83,969,0,98,654,970,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2564,83,654,0,97,968,969,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2563,83,968,0,96,967,654,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2562,83,967,0,95,940,968,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2561,83,940,0,94,752,967,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2560,83,752,0,93,230,940,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2559,83,230,0,92,10,752,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2558,83,10,0,91,966,230,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2557,83,966,0,90,965,10,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2556,83,965,0,89,546,966,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2555,83,546,0,88,575,965,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2554,83,575,0,87,930,546,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2553,83,930,0,86,964,575,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2552,83,964,0,85,200,930,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2551,83,200,0,84,201,964,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2550,83,201,0,83,844,200,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2549,83,844,0,82,843,201,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2548,83,843,0,81,963,844,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2547,83,963,0,80,931,843,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2546,83,931,0,79,203,963,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2545,83,203,0,78,615,931,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2544,83,615,0,77,930,203,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2543,83,930,0,76,953,615,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2542,83,953,0,75,243,930,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2541,83,243,0,74,962,953,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2540,83,962,0,73,961,243,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2539,83,961,0,72,234,962,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2538,83,234,0,71,940,961,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2537,83,940,0,70,11,234,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2536,83,11,0,69,960,940,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2535,83,960,0,68,636,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2534,83,636,0,67,959,960,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2533,83,959,0,66,5,636,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2532,83,5,0,65,179,959,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2531,83,179,0,64,181,5,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2530,83,181,0,63,958,179,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2529,83,958,0,62,957,181,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2528,83,957,0,61,956,958,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2527,83,956,0,60,955,957,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2526,83,955,0,59,954,956,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2525,83,954,0,58,953,955,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2524,83,953,0,57,946,954,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2523,83,946,0,56,576,953,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2522,83,576,0,55,952,946,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2521,83,952,0,54,201,576,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2520,83,201,0,53,946,952,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2519,83,946,0,52,951,201,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2518,83,951,0,51,243,946,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2517,83,243,0,50,950,951,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2516,83,950,0,49,949,243,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2515,83,949,0,48,11,950,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2514,83,11,0,47,615,949,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2513,83,615,0,46,948,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2512,83,948,0,45,219,615,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2511,83,219,0,44,947,948,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2510,83,947,0,43,546,219,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2509,83,546,0,42,108,947,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2508,83,108,0,41,753,546,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2507,83,753,0,40,946,108,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2506,83,946,0,39,945,753,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2505,83,945,0,38,944,946,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2504,83,944,0,37,943,945,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2503,83,943,0,36,546,944,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2502,83,546,0,35,942,943,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2501,83,942,0,34,941,546,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2500,83,941,0,33,546,942,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2499,83,546,0,32,234,941,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2498,83,234,0,31,940,546,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2497,83,940,0,30,11,234,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2496,83,11,0,29,939,940,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2495,83,939,0,28,234,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2494,83,234,0,27,938,939,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2493,83,938,0,26,11,234,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2492,83,11,0,25,937,938,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2491,83,937,0,24,546,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2490,83,546,0,23,234,937,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2489,83,234,0,22,936,546,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2488,83,936,0,21,11,234,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2487,83,11,0,20,236,936,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2486,83,236,0,19,935,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2485,83,935,0,18,108,236,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2484,83,108,0,17,934,935,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2483,83,934,0,16,933,108,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2482,83,933,0,15,11,934,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2481,83,11,0,14,932,933,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2480,83,932,0,13,201,11,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2479,83,201,0,12,251,932,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2478,83,251,0,11,931,201,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2477,83,931,0,10,203,251,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2476,83,203,0,9,615,931,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2475,83,615,0,8,930,203,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2474,83,930,0,7,929,615,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2473,83,929,0,6,928,930,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2472,83,928,0,5,927,929,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2471,83,927,0,4,5,928,24,155,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2470,83,5,0,3,926,927,24,154,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2469,83,926,0,2,925,5,24,154,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2468,83,925,0,1,924,926,24,154,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2467,83,924,0,0,0,925,24,154,1035904493,2);
INSERT INTO ezsearch_object_word_link VALUES (2288,15,844,0,1,843,0,1,4,1035893229,2);
INSERT INTO ezsearch_object_word_link VALUES (2287,15,843,0,0,0,844,1,4,1035893229,2);
INSERT INTO ezsearch_object_word_link VALUES (3083,109,243,0,217,1173,228,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3082,109,1173,0,216,575,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3081,109,575,0,215,1172,1173,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3080,109,1172,0,214,1171,575,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3079,109,1171,0,213,1105,1172,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3078,109,1105,0,212,11,1171,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3077,109,11,0,211,582,1105,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3076,109,582,0,210,179,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3075,109,179,0,209,1170,582,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3074,109,1170,0,208,70,179,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3073,109,70,0,207,172,1170,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3072,109,172,0,206,183,70,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3071,109,183,0,205,182,172,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3070,109,182,0,204,228,183,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3069,109,228,0,203,1169,182,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3068,109,1169,0,202,11,228,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3067,109,11,0,201,234,1169,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3066,109,234,0,200,1168,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3065,109,1168,0,199,1167,234,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3064,109,1167,0,198,11,1168,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3063,109,11,0,197,10,1167,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3062,109,10,0,196,1166,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3061,109,1166,0,195,1165,10,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3060,109,1165,0,194,1164,1166,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3059,109,1164,0,193,181,1165,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3058,109,181,0,192,1163,1164,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3057,109,1163,0,191,1162,181,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3056,109,1162,0,190,11,1163,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3055,109,11,0,189,1161,1162,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3054,109,1161,0,188,1160,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3053,109,1160,0,187,1159,1161,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3052,109,1159,0,186,564,1160,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3051,109,564,0,185,108,1159,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3050,109,108,0,184,243,564,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3049,109,243,0,183,1158,108,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3048,109,1158,0,182,11,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3047,109,11,0,181,1157,1158,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3046,109,1157,0,180,1156,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3045,109,1156,0,179,1155,1157,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3044,109,1155,0,178,546,1156,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3043,109,546,0,177,586,1155,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3042,109,586,0,176,537,546,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3041,109,537,0,175,183,586,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3040,109,183,0,174,182,537,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3039,109,182,0,173,183,183,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3038,109,183,0,172,182,182,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3037,109,182,0,171,243,183,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3036,109,243,0,170,1088,182,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3035,109,1088,0,169,182,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3034,109,182,0,168,234,1088,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3033,109,234,0,167,1154,182,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3032,109,1154,0,166,1153,234,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3031,109,1153,0,165,1152,1154,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3030,109,1152,0,164,11,1153,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3029,109,11,0,163,181,1152,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3028,109,181,0,162,1151,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3027,109,1151,0,161,749,181,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3026,109,749,0,160,1150,1151,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3025,109,1150,0,159,179,749,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3024,109,179,0,158,1101,1150,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3023,109,1101,0,157,1102,179,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3022,109,1102,0,156,11,1101,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3021,109,11,0,155,234,1102,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3020,109,234,0,154,553,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3019,109,553,0,153,653,234,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3018,109,653,0,152,1149,553,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3017,109,1149,0,151,1148,653,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3016,109,1148,0,150,561,1149,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3015,109,561,0,149,1088,1148,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3014,109,1088,0,148,182,561,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3013,109,182,0,147,1147,1088,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3012,109,1147,0,146,1146,182,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3011,109,1146,0,145,549,1147,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3010,109,549,0,144,181,1146,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3009,109,181,0,143,1145,549,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3008,109,1145,0,142,200,181,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3007,109,200,0,141,989,1145,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3006,109,989,0,140,1123,200,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3005,109,1123,0,139,243,989,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3004,109,243,0,138,1144,1123,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3003,109,1144,0,137,1143,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3002,109,1143,0,136,243,1144,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3001,109,243,0,135,968,1143,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3000,109,968,0,134,1095,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2999,109,1095,0,133,1142,968,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2998,109,1142,0,132,219,1095,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2997,109,219,0,131,634,1142,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2996,109,634,0,130,1141,219,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2995,109,1141,0,129,243,634,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2994,109,243,0,128,711,1141,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2993,109,711,0,127,181,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2992,109,181,0,126,1101,711,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2991,109,1101,0,125,1114,181,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2990,109,1114,0,124,579,1101,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2989,109,579,0,123,1140,1114,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2988,109,1140,0,122,1139,579,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2987,109,1139,0,121,1138,1140,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2986,109,1138,0,120,1137,1139,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2985,109,1137,0,119,267,1138,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2984,109,267,0,118,1124,1137,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2983,109,1124,0,117,243,267,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2982,109,243,0,116,1136,1124,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2981,109,1136,0,115,630,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2980,109,630,0,114,11,1136,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2979,109,11,0,113,1135,630,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2978,109,1135,0,112,219,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2977,109,219,0,111,561,1135,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2976,109,561,0,110,1134,219,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2975,109,1134,0,109,11,561,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2974,109,11,0,108,1130,1134,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2973,109,1130,0,107,1133,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2972,109,1133,0,106,243,1130,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2971,109,243,0,105,1132,1133,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2970,109,1132,0,104,1131,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2969,109,1131,0,103,1130,1132,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2968,109,1130,0,102,1129,1131,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2967,109,1129,0,101,1128,1130,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2966,109,1128,0,100,1127,1129,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2965,109,1127,0,99,1114,1128,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2964,109,1114,0,98,1126,1127,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2963,109,1126,0,97,1125,1114,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2962,109,1125,0,96,1124,1126,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2961,109,1124,0,95,615,1125,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2960,109,615,0,94,1123,1124,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2959,109,1123,0,93,1122,615,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2958,109,1122,0,92,1121,1123,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2957,109,1121,0,91,234,1122,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2956,109,234,0,90,1120,1121,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2955,109,1120,0,89,1119,234,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2954,109,1119,0,88,546,1120,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2953,109,546,0,87,10,1119,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2952,109,10,0,86,1118,546,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2951,109,1118,0,85,243,10,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2950,109,243,0,84,1117,1118,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2949,109,1117,0,83,219,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2948,109,219,0,82,1116,1117,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2947,109,1116,0,81,1115,219,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2946,109,1115,0,80,546,1116,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2945,109,546,0,79,10,1115,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2944,109,10,0,78,711,546,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2943,109,711,0,77,181,10,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2942,109,181,0,76,1101,711,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2941,109,1101,0,75,1114,181,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2940,109,1114,0,74,1113,1101,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2939,109,1113,0,73,243,1114,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2938,109,243,0,72,1112,1113,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2937,109,1112,0,71,1099,243,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2936,109,1099,0,70,11,1112,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2935,109,11,0,69,1111,1099,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2934,109,1111,0,68,1097,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2933,109,1097,0,67,1096,1111,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2932,109,1096,0,66,1110,1097,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2931,109,1110,0,65,1109,1096,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2930,109,1109,0,64,11,1110,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2929,109,11,0,63,564,1109,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2928,109,564,0,62,1108,11,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2927,109,1108,0,61,1107,564,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2926,109,1107,0,60,1106,1108,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2925,109,1106,0,59,946,1107,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2924,109,946,0,58,7,1106,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2923,109,7,0,57,546,946,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2922,109,546,0,56,181,7,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2921,109,181,0,55,711,546,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2920,109,711,0,54,181,181,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2919,109,181,0,53,1101,711,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2918,109,1101,0,52,234,181,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2917,109,234,0,51,1105,1101,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2916,109,1105,0,50,1104,234,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2915,109,1104,0,49,1099,1105,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2914,109,1099,0,48,11,1104,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2913,109,11,0,47,251,1099,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2912,109,251,0,46,192,11,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2911,109,192,0,45,1103,251,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2910,109,1103,0,44,1098,192,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2909,109,1098,0,43,1097,1103,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2908,109,1097,0,42,1096,1098,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2907,109,1096,0,41,1095,1097,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2906,109,1095,0,40,11,1096,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2905,109,11,0,39,194,1095,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2904,109,194,0,38,1101,11,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2903,109,1101,0,37,1102,194,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2902,109,1102,0,36,234,1101,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2901,109,234,0,35,553,1102,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2900,109,553,0,34,561,234,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2899,109,561,0,33,1088,553,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2898,109,1088,0,32,182,561,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2897,109,182,0,31,711,1088,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2896,109,711,0,30,181,182,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2895,109,181,0,29,1101,711,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2894,109,1101,0,28,1100,181,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2893,109,1100,0,27,1099,1101,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2892,109,1099,0,26,11,1100,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2891,109,11,0,25,234,1099,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2890,109,234,0,24,553,11,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2889,109,553,0,23,653,234,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2888,109,653,0,22,1098,553,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2887,109,1098,0,21,1097,653,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2886,109,1097,0,20,1096,1098,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2885,109,1096,0,19,1095,1097,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2884,109,1095,0,18,11,1096,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2883,109,11,0,17,564,1095,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2882,109,564,0,16,1094,11,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2881,109,1094,0,15,586,564,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2880,109,586,0,14,537,1094,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2879,109,537,0,13,1093,586,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2878,109,1093,0,12,11,537,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2877,109,11,0,11,194,1093,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2876,109,194,0,10,1092,11,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2875,109,1092,0,9,219,194,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2874,109,219,0,8,1091,1092,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2873,109,1091,0,7,108,219,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2872,109,108,0,6,1088,1091,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2871,109,1088,0,5,182,108,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2870,109,182,0,4,1090,1088,2,120,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2869,109,1090,0,3,1089,182,2,1,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2868,109,1089,0,2,1088,1090,2,1,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2867,109,1088,0,1,182,1089,2,1,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (2866,109,182,0,0,0,1088,2,1,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3087,109,182,0,221,234,183,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3088,109,183,0,222,182,0,2,121,1035905739,3);
INSERT INTO ezsearch_object_word_link VALUES (3089,110,1175,0,0,0,182,1,4,1035905816,3);
INSERT INTO ezsearch_object_word_link VALUES (3090,110,182,0,1,1175,1088,1,119,1035905816,3);
INSERT INTO ezsearch_object_word_link VALUES (3091,110,1088,0,2,182,1176,1,119,1035905816,3);
INSERT INTO ezsearch_object_word_link VALUES (3092,110,1176,0,3,1088,0,1,119,1035905816,3);
INSERT INTO ezsearch_object_word_link VALUES (3093,111,182,0,0,0,1088,2,1,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3094,111,1088,0,1,182,1177,2,1,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3095,111,1177,0,2,1088,1093,2,1,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3096,111,1093,0,3,1177,11,2,1,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3097,111,11,0,4,1093,1178,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3098,111,1178,0,5,11,615,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3099,111,615,0,6,1178,182,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3100,111,182,0,7,615,1088,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3101,111,1088,0,8,182,637,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3102,111,637,0,9,1088,707,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3103,111,707,0,10,637,181,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3104,111,181,0,11,707,11,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3105,111,11,0,12,181,1179,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3106,111,1179,0,13,11,234,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3107,111,234,0,14,1179,1180,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3108,111,1180,0,15,234,1181,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3109,111,1181,0,16,1180,228,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3110,111,228,0,17,1181,1182,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3111,111,1182,0,18,228,1183,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3112,111,1183,0,19,1182,11,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3113,111,11,0,20,1183,1184,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3114,111,1184,0,21,11,965,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3115,111,965,0,22,1184,1185,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3116,111,1185,0,23,965,607,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3117,111,607,0,24,1185,251,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3118,111,251,0,25,607,1186,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3119,111,1186,0,26,251,718,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3120,111,718,0,27,1186,190,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3121,111,190,0,28,718,1187,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3122,111,1187,0,29,190,234,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3123,111,234,0,30,1187,11,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3124,111,11,0,31,234,76,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3125,111,76,0,32,11,194,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3126,111,194,0,33,76,537,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3127,111,537,0,34,194,11,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3128,111,11,0,35,537,1099,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3129,111,1099,0,36,11,1188,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3130,111,1188,0,37,1099,979,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3131,111,979,0,38,1188,607,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3132,111,607,0,39,979,251,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3133,111,251,0,40,607,1189,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3134,111,1189,0,41,251,1099,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3135,111,1099,0,42,1189,1190,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3136,111,1190,0,43,1099,234,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3137,111,234,0,44,1190,11,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3138,111,11,0,45,234,76,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3139,111,76,0,46,11,219,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3140,111,219,0,47,76,1191,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3141,111,1191,0,48,219,630,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3142,111,630,0,49,1191,10,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3143,111,10,0,50,630,546,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3144,111,546,0,51,10,1192,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3145,111,1192,0,52,546,11,2,120,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3146,111,11,0,53,1192,1193,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3147,111,1193,0,54,11,739,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3148,111,739,0,55,1193,1194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3149,111,1194,0,56,739,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3150,111,11,0,57,1194,1195,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3151,111,1195,0,58,11,243,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3152,111,243,0,59,1195,940,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3153,111,940,0,60,243,615,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3154,111,615,0,61,940,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3155,111,11,0,62,615,1196,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3156,111,1196,0,63,11,1197,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3157,111,1197,0,64,1196,243,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3158,111,243,0,65,1197,1198,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3159,111,1198,0,66,243,190,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3160,111,190,0,67,1198,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3161,111,11,0,68,190,1199,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3162,111,1199,0,69,11,1200,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3163,111,1200,0,70,1199,739,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3164,111,739,0,71,1200,251,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3165,111,251,0,72,739,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3166,111,219,0,73,251,1201,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3167,111,1201,0,74,219,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3168,111,11,0,75,1201,544,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3169,111,544,0,76,11,748,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3170,111,748,0,77,544,182,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3171,111,182,0,78,748,1088,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3172,111,1088,0,79,182,716,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3173,111,716,0,80,1088,718,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3174,111,718,0,81,716,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3175,111,546,0,82,718,1202,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3176,111,1202,0,83,546,739,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3177,111,739,0,84,1202,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3178,111,11,0,85,739,1202,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3179,111,1202,0,86,11,716,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3180,111,716,0,87,1202,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3181,111,219,0,88,716,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3182,111,11,0,89,219,1203,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3183,111,1203,0,90,11,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3184,111,11,0,91,1203,1204,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3185,111,1204,0,92,11,610,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3186,111,610,0,93,1204,1205,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3187,111,1205,0,94,610,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3188,111,219,0,95,1205,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3189,111,546,0,96,219,994,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3190,111,994,0,97,546,243,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3191,111,243,0,98,994,1206,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3192,111,1206,0,99,243,764,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3193,111,764,0,100,1206,1207,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3194,111,1207,0,101,764,615,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3195,111,615,0,102,1207,76,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3196,111,76,0,103,615,1208,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3197,111,1208,0,104,76,243,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3198,111,243,0,105,1208,1177,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3199,111,1177,0,106,243,1209,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3200,111,1209,0,107,1177,1198,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3201,111,1198,0,108,1209,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3202,111,194,0,109,1198,1099,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3203,111,1099,0,110,194,1210,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3204,111,1210,0,111,1099,234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3205,111,234,0,112,1210,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3206,111,11,0,113,234,1211,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3207,111,1211,0,114,11,182,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3208,111,182,0,115,1211,1212,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3209,111,1212,0,116,182,1213,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3210,111,1213,0,117,1212,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3211,111,219,0,118,1213,716,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3212,111,716,0,119,219,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3213,111,219,0,120,716,1214,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3214,111,1214,0,121,219,243,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3215,111,243,0,122,1214,1215,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3216,111,1215,0,123,243,1216,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3217,111,1216,0,124,1215,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3218,111,11,0,125,1216,1217,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3219,111,1217,0,126,11,1218,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3220,111,1218,0,127,1217,1219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3221,111,1219,0,128,1218,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3222,111,194,0,129,1219,230,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3223,111,230,0,130,194,234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3224,111,234,0,131,230,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3225,111,11,0,132,234,1210,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3226,111,1210,0,133,11,721,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3227,111,721,0,134,1210,1220,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3228,111,1220,0,135,721,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3229,111,219,0,136,1220,1221,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3230,111,1221,0,137,219,686,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3231,111,686,0,138,1221,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3232,111,11,0,139,686,1222,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3233,111,1222,0,140,11,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3234,111,546,0,141,1222,1212,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3235,111,1212,0,142,546,1223,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3236,111,1223,0,143,1212,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3237,111,194,0,144,1223,1213,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3238,111,1213,0,145,194,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3239,111,219,0,146,1213,1221,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3240,111,1221,0,147,219,1224,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3241,111,1224,0,148,1221,575,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3242,111,575,0,149,1224,616,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3243,111,616,0,150,575,1225,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3244,111,1225,0,151,616,1226,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3245,111,1226,0,152,1225,1227,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3246,111,1227,0,153,1226,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3247,111,194,0,154,1227,578,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3248,111,578,0,155,194,108,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3249,111,108,0,156,578,1228,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3250,111,1228,0,157,108,234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3251,111,234,0,158,1228,1229,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3252,111,1229,0,159,234,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3253,111,11,0,160,1229,1222,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3254,111,1222,0,161,11,1230,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3255,111,1230,0,162,1222,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3256,111,546,0,163,1230,1231,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3257,111,1231,0,164,546,234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3258,111,234,0,165,1231,1232,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3259,111,1232,0,166,234,1233,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3260,111,1233,0,167,1232,973,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3261,111,973,0,168,1233,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3262,111,11,0,169,973,1093,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3263,111,1093,0,170,11,1234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3264,111,1234,0,171,1093,607,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3265,111,607,0,172,1234,637,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3266,111,637,0,173,607,1235,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3267,111,1235,0,174,637,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3268,111,546,0,175,1235,1236,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3269,111,1236,0,176,546,578,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3270,111,578,0,177,1236,1237,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3271,111,1237,0,178,578,203,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3272,111,203,0,179,1237,204,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3273,111,204,0,180,203,746,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3274,111,746,0,181,204,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3275,111,219,0,182,746,734,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3276,111,734,0,183,219,653,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3277,111,653,0,184,734,1238,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3278,111,1238,0,185,653,653,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3279,111,653,0,186,1238,221,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3280,111,221,0,187,653,191,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3281,111,191,0,188,221,1239,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3282,111,1239,0,189,191,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3283,111,194,0,190,1239,108,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3284,111,108,0,191,194,1240,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3285,111,1240,0,192,108,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3286,111,11,0,193,1240,1241,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3287,111,1241,0,194,11,1242,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3288,111,1242,0,195,1241,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3289,111,11,0,196,1242,1212,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3290,111,1212,0,197,11,1213,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3291,111,1213,0,198,1212,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3292,111,219,0,199,1213,1177,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3293,111,1177,0,200,219,607,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3294,111,607,0,201,1177,637,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3295,111,637,0,202,607,1243,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3296,111,1243,0,203,637,1244,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3297,111,1244,0,204,1243,718,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3298,111,718,0,205,1244,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3299,111,11,0,206,718,631,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3300,111,631,0,207,11,1245,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3301,111,1245,0,208,631,578,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3302,111,578,0,209,1245,1246,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3303,111,1246,0,210,578,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3304,111,194,0,211,1246,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3305,111,11,0,212,194,1203,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3306,111,1203,0,213,11,567,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3307,111,567,0,214,1203,734,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3308,111,734,0,215,567,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3309,111,546,0,216,734,1247,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3310,111,1247,0,217,546,644,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3311,111,644,0,218,1247,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3312,111,219,0,219,644,716,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3313,111,716,0,220,219,174,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3314,111,174,0,221,716,1232,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3315,111,1232,0,222,174,1248,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3316,111,1248,0,223,1232,1249,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3317,111,1249,0,224,1248,1198,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3318,111,1198,0,225,1249,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3319,111,194,0,226,1198,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3320,111,11,0,227,194,182,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3321,111,182,0,228,11,1212,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3322,111,1212,0,229,182,108,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3323,111,108,0,230,1212,1250,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3324,111,1250,0,231,108,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3325,111,219,0,232,1250,1251,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3326,111,1251,0,233,219,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3327,111,546,0,234,1251,1252,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3328,111,1252,0,235,546,234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3329,111,234,0,236,1252,1253,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3330,111,1253,0,237,234,198,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3331,111,198,0,238,1253,261,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3332,111,261,0,239,198,1254,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3333,111,1254,0,240,261,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3334,111,11,0,241,1254,1255,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3335,111,1255,0,242,11,108,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3336,111,108,0,243,1255,755,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3337,111,755,0,244,108,1256,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3338,111,1256,0,245,755,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3339,111,11,0,246,1256,1257,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3340,111,1257,0,247,11,108,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3341,111,108,0,248,1257,174,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3342,111,174,0,249,108,1258,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3343,111,1258,0,250,174,1157,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3344,111,1157,0,251,1258,553,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3345,111,553,0,252,1157,234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3346,111,234,0,253,553,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3347,111,11,0,254,234,1259,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3348,111,1259,0,255,11,575,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3349,111,575,0,256,1259,929,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3350,111,929,0,257,575,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3351,111,546,0,258,929,1260,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3352,111,1260,0,259,546,181,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3353,111,181,0,260,1260,182,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3354,111,182,0,261,181,1088,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3355,111,1088,0,262,182,108,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3356,111,108,0,263,1088,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3357,111,194,0,264,108,201,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3358,111,201,0,265,194,1261,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3359,111,1261,0,266,201,329,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3360,111,329,0,267,1261,1262,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3361,111,1262,0,268,329,1263,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3362,111,1263,0,269,1262,1264,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3363,111,1264,0,270,1263,751,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3364,111,751,0,271,1264,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3365,111,194,0,272,751,537,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3366,111,537,0,273,194,1265,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3367,111,1265,0,274,537,233,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3368,111,233,0,275,1265,1198,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3369,111,1198,0,276,233,194,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3370,111,194,0,277,1198,230,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3371,111,230,0,278,194,1266,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3372,111,1266,0,279,230,251,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3373,111,251,0,280,1266,558,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3374,111,558,0,281,251,181,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3375,111,181,0,282,558,1267,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3376,111,1267,0,283,181,243,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3377,111,243,0,284,1267,1268,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3378,111,1268,0,285,243,653,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3379,111,653,0,286,1268,955,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3380,111,955,0,287,653,636,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3381,111,636,0,288,955,251,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3382,111,251,0,289,636,591,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3383,111,591,0,290,251,586,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3384,111,586,0,291,591,1269,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3385,111,1269,0,292,586,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3386,111,219,0,293,1269,357,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3387,111,357,0,294,219,179,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3388,111,179,0,295,357,1270,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3389,111,1270,0,296,179,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3390,111,546,0,297,1270,1252,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3391,111,1252,0,298,546,234,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3392,111,234,0,299,1252,597,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3393,111,597,0,300,234,108,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3394,111,108,0,301,597,970,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3395,111,970,0,302,108,219,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3396,111,219,0,303,970,734,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3397,111,734,0,304,219,1271,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3398,111,1271,0,305,734,1272,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3399,111,1272,0,306,1271,546,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3400,111,546,0,307,1272,1273,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3401,111,1273,0,308,546,200,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3402,111,200,0,309,1273,734,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3403,111,734,0,310,200,1271,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3404,111,1271,0,311,734,636,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3405,111,636,0,312,1271,200,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3406,111,200,0,313,636,261,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3407,111,261,0,314,200,1274,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3408,111,1274,0,315,261,718,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3409,111,718,0,316,1274,190,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3410,111,190,0,317,718,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3411,111,11,0,318,190,1275,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3412,111,1275,0,319,11,739,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3413,111,739,0,320,1275,734,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3414,111,734,0,321,739,198,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3415,111,198,0,322,734,636,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3416,111,636,0,323,198,637,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3417,111,637,0,324,636,1276,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3418,111,1276,0,325,637,237,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3419,111,237,0,326,1276,11,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3420,111,11,0,327,237,1212,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3421,111,1212,0,328,11,1277,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3422,111,1277,0,329,1212,203,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3423,111,203,0,330,1277,739,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3424,111,739,0,331,203,734,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3425,111,734,0,332,739,755,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3426,111,755,0,333,734,0,2,121,1035905861,3);
INSERT INTO ezsearch_object_word_link VALUES (3427,112,1278,0,0,0,0,1,4,1035905944,3);
INSERT INTO ezsearch_object_word_link VALUES (3428,113,1279,0,0,0,575,2,1,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3429,113,575,0,1,1279,11,2,1,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3430,113,11,0,2,575,1280,2,1,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3431,113,1280,0,3,11,1281,2,1,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3432,113,1281,0,4,1280,174,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3433,113,174,0,5,1281,108,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3434,113,108,0,6,174,546,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3435,113,546,0,7,108,1282,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3436,113,1282,0,8,546,234,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3437,113,234,0,9,1282,546,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3438,113,546,0,10,234,1283,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3439,113,1283,0,11,546,558,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3440,113,558,0,12,1283,575,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3441,113,575,0,13,558,1284,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3442,113,1284,0,14,575,243,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3443,113,243,0,15,1284,969,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3444,113,969,0,16,243,179,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3445,113,179,0,17,969,755,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3446,113,755,0,18,179,1285,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3447,113,1285,0,19,755,1286,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3448,113,1286,0,20,1285,718,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3449,113,718,0,21,1286,182,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3450,113,182,0,22,718,183,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3451,113,183,0,23,182,243,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3452,113,243,0,24,183,108,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3453,113,108,0,25,243,546,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3454,113,546,0,26,108,708,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3455,113,708,0,27,546,1287,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3456,113,1287,0,28,708,1288,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3457,113,1288,0,29,1287,718,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3458,113,718,0,30,1288,190,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3459,113,190,0,31,718,201,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3460,113,201,0,32,190,200,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3461,113,200,0,33,201,715,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3462,113,715,0,34,200,10,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3463,113,10,0,35,715,1164,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3464,113,1164,0,36,10,243,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3465,113,243,0,37,1164,1289,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3466,113,1289,0,38,243,718,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3467,113,718,0,39,1289,28,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3468,113,28,0,40,718,182,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3469,113,182,0,41,28,183,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3470,113,183,0,42,182,1290,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3471,113,1290,0,43,183,1285,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3472,113,1285,0,44,1290,1291,2,120,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3473,113,1291,0,45,1285,1292,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3474,113,1292,0,46,1291,195,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3475,113,195,0,47,1292,1293,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3476,113,1293,0,48,195,108,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3477,113,108,0,49,1293,546,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3478,113,546,0,50,108,1294,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3479,113,1294,0,51,546,1295,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3480,113,1295,0,52,1294,1296,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3481,113,1296,0,53,1295,181,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3482,113,181,0,54,1296,1297,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3483,113,1297,0,55,181,243,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3484,113,243,0,56,1297,251,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3485,113,251,0,57,243,1298,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3486,113,1298,0,58,251,10,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3487,113,10,0,59,1298,1299,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3488,113,1299,0,60,10,1300,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3489,113,1300,0,61,1299,243,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3490,113,243,0,62,1300,1301,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3491,113,1301,0,63,243,1157,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3492,113,1157,0,64,1301,1302,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3493,113,1302,0,65,1157,718,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3494,113,718,0,66,1302,616,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3495,113,616,0,67,718,1184,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3496,113,1184,0,68,616,1177,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3497,113,1177,0,69,1184,1183,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3498,113,1183,0,70,1177,11,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3499,113,11,0,71,1183,945,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3500,113,945,0,72,11,234,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3501,113,234,0,73,945,1303,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3502,113,1303,0,74,234,1292,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3503,113,1292,0,75,1303,1251,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3504,113,1251,0,76,1292,1304,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3505,113,1304,0,77,1251,1185,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3506,113,1185,0,78,1304,10,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3507,113,10,0,79,1185,11,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3508,113,11,0,80,10,1305,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3509,113,1305,0,81,11,1306,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3510,113,1306,0,82,1305,181,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3511,113,181,0,83,1306,11,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3512,113,11,0,84,181,1307,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3513,113,1307,0,85,11,243,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3514,113,243,0,86,1307,1308,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3515,113,1308,0,87,243,1268,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3516,113,1268,0,88,1308,653,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3517,113,653,0,89,1268,955,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3518,113,955,0,90,653,653,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3519,113,653,0,91,955,181,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3520,113,181,0,92,653,1309,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3521,113,1309,0,93,181,654,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3522,113,654,0,94,1309,234,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3523,113,234,0,95,654,616,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3524,113,616,0,96,234,1310,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3525,113,1310,0,97,616,637,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3526,113,637,0,98,1310,1311,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3527,113,1311,0,99,637,564,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3528,113,564,0,100,1311,179,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3529,113,179,0,101,564,1312,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3530,113,1312,0,102,179,575,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3531,113,575,0,103,1312,1313,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3532,113,1313,0,104,575,1314,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3533,113,1314,0,105,1313,234,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3534,113,234,0,106,1314,179,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3535,113,179,0,107,234,1221,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3536,113,1221,0,108,179,578,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3537,113,578,0,109,1221,1315,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3538,113,1315,0,110,578,181,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3539,113,181,0,111,1315,546,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3540,113,546,0,112,181,1316,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3541,113,1316,0,113,546,1317,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3542,113,1317,0,114,1316,653,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3543,113,653,0,115,1317,11,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3544,113,11,0,116,653,261,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3545,113,261,0,117,11,1318,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3546,113,1318,0,118,261,1319,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3547,113,1319,0,119,1318,243,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3548,113,243,0,120,1319,578,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3549,113,578,0,121,243,1320,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3550,113,1320,0,122,578,28,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3551,113,28,0,123,1320,1321,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3552,113,1321,0,124,28,181,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3553,113,181,0,125,1321,11,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3554,113,11,0,126,181,1322,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3555,113,1322,0,127,11,935,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3556,113,935,0,128,1322,234,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3557,113,234,0,129,935,11,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3558,113,11,0,130,234,1323,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3559,113,1323,0,131,11,11,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3560,113,11,0,132,1323,1289,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3561,113,1289,0,133,11,575,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3562,113,575,0,134,1289,179,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3563,113,179,0,135,575,1285,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3564,113,1285,0,136,179,108,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3565,113,108,0,137,1285,1324,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3566,113,1324,0,138,108,564,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3567,113,564,0,139,1324,1325,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3568,113,1325,0,140,564,1326,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3569,113,1326,0,141,1325,1327,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3570,113,1327,0,142,1326,174,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3571,113,174,0,143,1327,243,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3572,113,243,0,144,174,537,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3573,113,537,0,145,243,586,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3574,113,586,0,146,537,1328,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3575,113,1328,0,147,586,630,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3576,113,630,0,148,1328,564,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3577,113,564,0,149,630,553,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3578,113,553,0,150,564,234,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3579,113,234,0,151,553,182,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3580,113,182,0,152,234,1088,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3581,113,1088,0,153,182,1329,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3582,113,1329,0,154,1088,1330,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3583,113,1330,0,155,1329,1331,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3584,113,1331,0,156,1330,1332,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3585,113,1332,0,157,1331,182,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3586,113,182,0,158,1332,1088,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3587,113,1088,0,159,182,537,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3588,113,537,0,160,1088,1333,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3589,113,1333,0,161,537,974,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3590,113,974,0,162,1333,1334,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3591,113,1334,0,163,974,10,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3592,113,10,0,164,1334,1335,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3593,113,1335,0,165,10,1336,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3594,113,1336,0,166,1335,1337,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3595,113,1337,0,167,1336,1338,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3596,113,1338,0,168,1337,561,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3597,113,561,0,169,1338,1339,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3598,113,1339,0,170,561,1340,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3599,113,1340,0,171,1339,631,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3600,113,631,0,172,1340,1341,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3601,113,1341,0,173,631,1312,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3602,113,1312,0,174,1341,1281,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (3603,113,1281,0,175,1312,0,2,121,1035905997,3);
INSERT INTO ezsearch_object_word_link VALUES (4323,114,1608,0,359,718,0,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4322,114,718,0,358,1191,1608,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4321,114,1191,0,357,1607,718,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4320,114,1607,0,356,544,1191,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4319,114,544,0,355,1606,1607,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4318,114,1606,0,354,1605,544,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4317,114,1605,0,353,219,1606,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4316,114,219,0,352,1177,1605,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4315,114,1177,0,351,219,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4314,114,219,0,350,1604,1177,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4313,114,1604,0,349,1603,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4312,114,1603,0,348,179,1604,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4311,114,179,0,347,1602,1603,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4310,114,1602,0,346,1601,179,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4309,114,1601,0,345,1109,1602,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4308,114,1109,0,344,1600,1601,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4307,114,1600,0,343,1599,1109,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4306,114,1599,0,342,546,1600,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4305,114,546,0,341,251,1599,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4304,114,251,0,340,219,546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4303,114,219,0,339,1598,251,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4302,114,1598,0,338,204,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4301,114,204,0,337,248,1598,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4300,114,248,0,336,931,204,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4299,114,931,0,335,203,248,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4298,114,203,0,334,621,931,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4297,114,621,0,333,1584,203,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4296,114,1584,0,332,11,621,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4295,114,11,0,331,234,1584,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4294,114,234,0,330,1583,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4293,114,1583,0,329,11,234,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4292,114,11,0,328,1597,1583,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4291,114,1597,0,327,1596,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4290,114,1596,0,326,237,1597,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4289,114,237,0,325,1595,1596,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4288,114,1595,0,324,243,237,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4287,114,243,0,323,1533,1595,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4286,114,1533,0,322,1594,243,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4285,114,1594,0,321,627,1533,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4284,114,627,0,320,1196,1594,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4283,114,1196,0,319,1593,627,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4282,114,1593,0,318,11,1196,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4281,114,11,0,317,1592,1593,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4280,114,1592,0,316,1516,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4279,114,1516,0,315,1591,1592,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4278,114,1591,0,314,219,1516,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4277,114,219,0,313,1590,1591,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4276,114,1590,0,312,219,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4275,114,219,0,311,204,1590,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4274,114,204,0,310,1142,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4273,114,1142,0,309,1589,204,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4272,114,1589,0,308,988,1142,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4271,114,988,0,307,1588,1589,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4270,114,1588,0,306,219,988,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4269,114,219,0,305,1564,1588,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4268,114,1564,0,304,181,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4267,114,181,0,303,1587,1564,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4266,114,1587,0,302,1573,181,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4265,114,1573,0,301,1517,1587,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4264,114,1517,0,300,749,1573,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4263,114,749,0,299,1586,1517,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4262,114,1586,0,298,251,749,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4261,114,251,0,297,596,1586,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4260,114,596,0,296,1533,251,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4259,114,1533,0,295,1585,596,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4258,114,1585,0,294,224,1533,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4257,114,224,0,293,1584,1585,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4256,114,1584,0,292,11,224,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4255,114,11,0,291,234,1584,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4254,114,234,0,290,1583,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4253,114,1583,0,289,11,234,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4252,114,11,0,288,255,1583,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4251,114,255,0,287,1582,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4250,114,1582,0,286,11,255,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4249,114,11,0,285,1581,1582,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4248,114,1581,0,284,243,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4247,114,243,0,283,630,1581,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4246,114,630,0,282,1580,243,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4245,114,1580,0,281,219,630,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4244,114,219,0,280,1579,1580,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4243,114,1579,0,279,247,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4242,114,247,0,278,561,1579,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4241,114,561,0,277,1523,247,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4240,114,1523,0,276,1578,561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4239,114,1578,0,275,1577,1523,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4238,114,1577,0,274,1576,1578,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4237,114,1576,0,273,546,1577,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4236,114,546,0,272,1324,1576,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4235,114,1324,0,271,1556,546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4234,114,1556,0,270,237,1324,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4233,114,237,0,269,1575,1556,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4232,114,1575,0,268,11,237,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4231,114,11,0,267,181,1575,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4230,114,181,0,266,734,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4229,114,734,0,265,219,181,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4228,114,219,0,264,591,734,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4227,114,591,0,263,1558,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4226,114,1558,0,262,733,591,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4225,114,733,0,261,561,1558,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4224,114,561,0,260,989,733,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4223,114,989,0,259,1524,561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4222,114,1524,0,258,219,989,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4221,114,219,0,257,1564,1524,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4220,114,1564,0,256,615,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4219,114,615,0,255,1574,1564,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4218,114,1574,0,254,1573,615,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4217,114,1573,0,253,1517,1574,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4216,114,1517,0,252,749,1573,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4215,114,749,0,251,568,1517,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4214,114,568,0,250,219,749,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4213,114,219,0,249,1155,568,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4212,114,1155,0,248,1572,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4211,114,1572,0,247,1571,1155,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4210,114,1571,0,246,974,1572,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4209,114,974,0,245,181,1571,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4208,114,181,0,244,1570,974,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4207,114,1570,0,243,230,181,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4206,114,230,0,242,1569,1570,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4205,114,1569,0,241,636,230,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4204,114,636,0,240,194,1569,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4203,114,194,0,239,1568,636,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4202,114,1568,0,238,1567,194,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4201,114,1567,0,237,11,1568,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4200,114,11,0,236,1566,1567,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4199,114,1566,0,235,219,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4198,114,219,0,234,181,1566,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4197,114,181,0,233,1565,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4196,114,1565,0,232,1564,181,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4195,114,1564,0,231,237,1565,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4194,114,237,0,230,261,1564,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4193,114,261,0,229,561,237,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4192,114,561,0,228,203,261,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4191,114,203,0,227,1517,561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4190,114,1517,0,226,549,203,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4189,114,549,0,225,219,1517,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4188,114,219,0,224,1563,549,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4187,114,1563,0,223,234,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4186,114,234,0,222,1252,1563,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4185,114,1252,0,221,546,234,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4184,114,546,0,220,1562,1252,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4183,114,1562,0,219,563,546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4182,114,563,0,218,1561,1562,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4181,114,1561,0,217,1560,563,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4180,114,1560,0,216,616,1561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4179,114,616,0,215,237,1560,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4178,114,237,0,214,968,616,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4177,114,968,0,213,575,237,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4176,114,575,0,212,1559,968,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4175,114,1559,0,211,219,575,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4174,114,219,0,210,591,1559,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4173,114,591,0,209,1558,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4172,114,1558,0,208,561,591,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4171,114,561,0,207,1546,1558,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4170,114,1546,0,206,1557,561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4169,114,1557,0,205,11,1546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4168,114,11,0,204,591,1557,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4167,114,591,0,203,198,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4166,114,198,0,202,1556,591,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4165,114,1556,0,201,1478,198,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4164,114,1478,0,200,1555,1556,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4163,114,1555,0,199,1554,1478,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4162,114,1554,0,198,1497,1555,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4161,114,1497,0,197,1553,1554,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4160,114,1553,0,196,1552,1497,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4159,114,1552,0,195,616,1553,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4158,114,616,0,194,237,1552,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4157,114,237,0,193,1494,616,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4156,114,1494,0,192,1551,237,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4155,114,1551,0,191,11,1494,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4154,114,11,0,190,181,1551,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4153,114,181,0,189,1497,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4152,114,1497,0,188,1550,181,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4151,114,1550,0,187,546,1497,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4150,114,546,0,186,650,1550,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4149,114,650,0,185,251,546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4148,114,251,0,184,1549,650,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4147,114,1549,0,183,1548,251,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4146,114,1548,0,182,748,1549,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4145,114,748,0,181,1272,1548,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4144,114,1272,0,180,966,748,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4143,114,966,0,179,268,1272,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4142,114,268,0,178,740,966,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4141,114,740,0,177,11,268,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4140,114,11,0,176,255,740,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4139,114,255,0,175,1547,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4138,114,1547,0,174,561,255,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4137,114,561,0,173,1546,1547,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4136,114,1546,0,172,237,561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4135,114,237,0,171,1545,1546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4134,114,1545,0,170,1544,237,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4133,114,1544,0,169,219,1545,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4132,114,219,0,168,1487,1544,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4131,114,1487,0,167,638,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4130,114,638,0,166,10,1487,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4129,114,10,0,165,652,638,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4128,114,652,0,164,184,10,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4127,114,184,0,163,11,652,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4126,114,11,0,162,181,184,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4125,114,181,0,161,1543,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4124,114,1543,0,160,11,181,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4123,114,11,0,159,615,1543,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4122,114,615,0,158,233,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4121,114,233,0,157,1542,615,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4120,114,1542,0,156,585,233,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4119,114,585,0,155,11,1542,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4118,114,11,0,154,1541,585,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4117,114,1541,0,153,1540,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4116,114,1540,0,152,549,1541,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4115,114,549,0,151,1539,1540,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4114,114,1539,0,150,219,549,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4113,114,219,0,149,1538,1539,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4112,114,1538,0,148,11,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4111,114,11,0,147,234,1538,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4110,114,234,0,146,1537,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4109,114,1537,0,145,11,234,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4108,114,11,0,144,219,1537,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4107,114,219,0,143,1536,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4106,114,1536,0,142,638,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4105,114,638,0,141,1535,1536,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4104,114,1535,0,140,251,638,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4103,114,251,0,139,596,1535,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4102,114,596,0,138,1534,251,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4101,114,1534,0,137,616,596,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4100,114,616,0,136,243,1534,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4099,114,243,0,135,1533,616,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4098,114,1533,0,134,1532,243,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4097,114,1532,0,133,1531,1533,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4096,114,1531,0,132,615,1532,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4095,114,615,0,131,1530,1531,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4094,114,1530,0,130,234,615,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4093,114,234,0,129,1529,1530,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4092,114,1529,0,128,10,234,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4091,114,10,0,127,1528,1529,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4090,114,1528,0,126,1527,10,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4089,114,1527,0,125,1526,1528,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4088,114,1526,0,124,652,1527,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4087,114,652,0,123,177,1526,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4086,114,177,0,122,11,652,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4085,114,11,0,121,203,177,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4084,114,203,0,120,580,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4083,114,580,0,119,1525,203,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4082,114,1525,0,118,219,580,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4081,114,219,0,117,1524,1525,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4080,114,1524,0,116,243,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4079,114,243,0,115,1523,1524,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4078,114,1523,0,114,575,243,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4077,114,575,0,113,1522,1523,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4076,114,1522,0,112,234,575,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4075,114,234,0,111,1521,1522,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4074,114,1521,0,110,1155,234,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4073,114,1155,0,109,546,1521,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4072,114,546,0,108,1235,1155,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4071,114,1235,0,107,561,546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4070,114,561,0,106,1520,1235,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4069,114,1520,0,105,243,561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4068,114,243,0,104,1519,1520,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4067,114,1519,0,103,564,243,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4066,114,564,0,102,1518,1519,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4065,114,1518,0,101,1517,564,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4064,114,1517,0,100,549,1518,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4063,114,549,0,99,1480,1517,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4062,114,1480,0,98,686,549,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4061,114,686,0,97,577,1480,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4060,114,577,0,96,546,686,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4059,114,546,0,95,745,577,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4058,114,745,0,94,219,546,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4057,114,219,0,93,1516,745,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4056,114,1516,0,92,1515,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4055,114,1515,0,91,561,1516,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4054,114,561,0,90,1479,1515,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4053,114,1479,0,89,1514,561,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4052,114,1514,0,88,615,1479,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4051,114,615,0,87,1513,1514,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4050,114,1513,0,86,1512,615,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4049,114,1512,0,85,28,1513,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4048,114,28,0,84,1511,1512,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4047,114,1511,0,83,1510,28,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4046,114,1510,0,82,1308,1511,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4045,114,1308,0,81,11,1510,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4044,114,11,0,80,705,1308,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4043,114,705,0,79,1509,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4042,114,1509,0,78,1508,705,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4041,114,1508,0,77,1507,1509,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4040,114,1507,0,76,549,1508,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4039,114,549,0,75,181,1507,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4038,114,181,0,74,1506,549,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4037,114,1506,0,73,11,181,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4036,114,11,0,72,615,1506,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4035,114,615,0,71,1505,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4034,114,1505,0,70,219,615,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4033,114,219,0,69,1270,1505,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4032,114,1270,0,68,585,219,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4031,114,585,0,67,1504,1270,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4030,114,1504,0,66,234,585,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4029,114,234,0,65,1503,1504,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4028,114,1503,0,64,11,234,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4027,114,11,0,63,580,1503,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4026,114,580,0,62,931,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4025,114,931,0,61,1502,580,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4024,114,1502,0,60,1501,931,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4023,114,1501,0,59,733,1502,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4022,114,733,0,58,198,1501,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4021,114,198,0,57,1500,733,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4020,114,1500,0,56,1184,198,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4019,114,1184,0,55,11,1500,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4018,114,11,0,54,261,1184,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4017,114,261,0,53,591,11,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4016,114,591,0,52,968,261,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4015,114,968,0,51,1499,591,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4014,114,1499,0,50,545,968,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4013,114,545,0,49,179,1499,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4012,114,179,0,48,1498,545,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4011,114,1498,0,47,247,179,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4010,114,247,0,46,1497,1498,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4009,114,1497,0,45,1496,247,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4008,114,1496,0,44,1495,1497,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4007,114,1495,0,43,243,1496,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4006,114,243,0,42,1494,1495,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4005,114,1494,0,41,1493,243,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4004,114,1493,0,40,204,1494,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4003,114,204,0,39,1492,1493,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4002,114,1492,0,38,237,204,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4001,114,237,0,37,1491,1492,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (4000,114,1491,0,36,1490,237,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3999,114,1490,0,35,1489,1491,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3998,114,1489,0,34,1488,1490,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3997,114,1488,0,33,243,1489,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3996,114,243,0,32,1487,1488,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3995,114,1487,0,31,1486,243,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3994,114,1486,0,30,974,1487,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3993,114,974,0,29,545,1486,2,121,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3992,114,545,0,28,179,974,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3991,114,179,0,27,234,545,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3990,114,234,0,26,1158,179,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3989,114,1158,0,25,11,234,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3988,114,11,0,24,575,1158,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3987,114,575,0,23,1485,11,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3986,114,1485,0,22,1484,575,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3985,114,1484,0,21,974,1485,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3984,114,974,0,20,234,1484,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3983,114,234,0,19,553,974,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3982,114,553,0,18,653,234,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3981,114,653,0,17,1483,553,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3980,114,1483,0,16,1482,653,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3979,114,1482,0,15,561,1483,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3978,114,561,0,14,1481,1482,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3977,114,1481,0,13,1480,561,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3976,114,1480,0,12,686,1481,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3975,114,686,0,11,1479,1480,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3974,114,1479,0,10,1478,686,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3973,114,1478,0,9,219,1479,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3972,114,219,0,8,1477,1478,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3971,114,1477,0,7,203,219,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3970,114,203,0,6,1476,1477,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3969,114,1476,0,5,636,203,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3968,114,636,0,4,1477,1476,2,120,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3967,114,1477,0,3,203,636,2,1,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3966,114,203,0,2,1476,1477,2,1,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3965,114,1476,0,1,636,203,2,1,1035906867,3);
INSERT INTO ezsearch_object_word_link VALUES (3964,114,636,0,0,0,1476,2,1,1035906867,3);

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

INSERT INTO ezsearch_return_count VALUES (1,1,1033923459,0);
INSERT INTO ezsearch_return_count VALUES (2,2,1034001823,0);
INSERT INTO ezsearch_return_count VALUES (3,3,1034167060,0);
INSERT INTO ezsearch_return_count VALUES (4,4,1034167069,0);
INSERT INTO ezsearch_return_count VALUES (5,3,1034167175,0);
INSERT INTO ezsearch_return_count VALUES (6,5,1034234016,0);
INSERT INTO ezsearch_return_count VALUES (7,5,1034234030,0);
INSERT INTO ezsearch_return_count VALUES (8,6,1034237509,0);
INSERT INTO ezsearch_return_count VALUES (9,6,1034237586,0);
INSERT INTO ezsearch_return_count VALUES (10,6,1034237608,0);
INSERT INTO ezsearch_return_count VALUES (11,3,1034237623,0);
INSERT INTO ezsearch_return_count VALUES (12,7,1034237626,3);
INSERT INTO ezsearch_return_count VALUES (13,7,1034239847,3);
INSERT INTO ezsearch_return_count VALUES (14,8,1034253661,0);
INSERT INTO ezsearch_return_count VALUES (15,8,1034253721,0);
INSERT INTO ezsearch_return_count VALUES (16,8,1034253739,0);
INSERT INTO ezsearch_return_count VALUES (17,9,1034253765,0);
INSERT INTO ezsearch_return_count VALUES (18,10,1034260183,1);
INSERT INTO ezsearch_return_count VALUES (19,10,1034260197,1);
INSERT INTO ezsearch_return_count VALUES (20,11,1034261497,2);
INSERT INTO ezsearch_return_count VALUES (21,12,1034264195,0);
INSERT INTO ezsearch_return_count VALUES (22,13,1034264216,0);
INSERT INTO ezsearch_return_count VALUES (23,8,1034264229,0);
INSERT INTO ezsearch_return_count VALUES (24,8,1034264252,0);
INSERT INTO ezsearch_return_count VALUES (25,8,1034264257,0);
INSERT INTO ezsearch_return_count VALUES (26,14,1034264277,8);
INSERT INTO ezsearch_return_count VALUES (27,15,1034264355,0);
INSERT INTO ezsearch_return_count VALUES (28,3,1034264360,0);
INSERT INTO ezsearch_return_count VALUES (29,11,1034264451,2);

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

INSERT INTO ezsearch_search_phrase VALUES (1,'test');
INSERT INTO ezsearch_search_phrase VALUES (2,'Pond');
INSERT INTO ezsearch_search_phrase VALUES (3,'');
INSERT INTO ezsearch_search_phrase VALUES (4,'saab');
INSERT INTO ezsearch_search_phrase VALUES (5,'Indiatimes');
INSERT INTO ezsearch_search_phrase VALUES (6,'abc');
INSERT INTO ezsearch_search_phrase VALUES (7,'gallery');
INSERT INTO ezsearch_search_phrase VALUES (8,'Bush');
INSERT INTO ezsearch_search_phrase VALUES (9,'floyd\'s');
INSERT INTO ezsearch_search_phrase VALUES (10,'ferrari');
INSERT INTO ezsearch_search_phrase VALUES (11,'bear');
INSERT INTO ezsearch_search_phrase VALUES (12,'Typhoon');
INSERT INTO ezsearch_search_phrase VALUES (13,'norway');
INSERT INTO ezsearch_search_phrase VALUES (14,'quam');
INSERT INTO ezsearch_search_phrase VALUES (15,'kidding');

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

INSERT INTO ezsearch_word VALUES (988,'those',2);
INSERT INTO ezsearch_word VALUES (987,'glitter',1);
INSERT INTO ezsearch_word VALUES (5,'gallery',3);
INSERT INTO ezsearch_word VALUES (6,'1',4);
INSERT INTO ezsearch_word VALUES (7,'2',2);
INSERT INTO ezsearch_word VALUES (8,'news',6);
INSERT INTO ezsearch_word VALUES (9,'folder',6);
INSERT INTO ezsearch_word VALUES (10,'with',14);
INSERT INTO ezsearch_word VALUES (11,'the',106);
INSERT INTO ezsearch_word VALUES (12,'quo',9);
INSERT INTO ezsearch_word VALUES (13,'tuus',9);
INSERT INTO ezsearch_word VALUES (14,'consuasteris',11);
INSERT INTO ezsearch_word VALUES (15,'lin',9);
INSERT INTO ezsearch_word VALUES (16,'ditic',9);
INSERT INTO ezsearch_word VALUES (17,'mod',9);
INSERT INTO ezsearch_word VALUES (18,'res',10);
INSERT INTO ezsearch_word VALUES (19,'audem',9);
INSERT INTO ezsearch_word VALUES (20,'nius',9);
INSERT INTO ezsearch_word VALUES (21,'intiam',9);
INSERT INTO ezsearch_word VALUES (22,'noximus',9);
INSERT INTO ezsearch_word VALUES (23,'quam',24);
INSERT INTO ezsearch_word VALUES (24,'ex',9);
INSERT INTO ezsearch_word VALUES (25,'maximus',10);
INSERT INTO ezsearch_word VALUES (26,'egitam',9);
INSERT INTO ezsearch_word VALUES (27,'aus',12);
INSERT INTO ezsearch_word VALUES (28,'an',14);
INSERT INTO ezsearch_word VALUES (29,'averum',9);
INSERT INTO ezsearch_word VALUES (30,'te',17);
INSERT INTO ezsearch_word VALUES (31,'condiervium',9);
INSERT INTO ezsearch_word VALUES (32,'atum',9);
INSERT INTO ezsearch_word VALUES (33,'postien',9);
INSERT INTO ezsearch_word VALUES (34,'aceperit',9);
INSERT INTO ezsearch_word VALUES (35,'iaes',9);
INSERT INTO ezsearch_word VALUES (36,'o',24);
INSERT INTO ezsearch_word VALUES (37,'tus',19);
INSERT INTO ezsearch_word VALUES (38,'horur',9);
INSERT INTO ezsearch_word VALUES (39,'lium',9);
INSERT INTO ezsearch_word VALUES (40,'inc',9);
INSERT INTO ezsearch_word VALUES (41,'orei',9);
INSERT INTO ezsearch_word VALUES (42,'perenatusa',9);
INSERT INTO ezsearch_word VALUES (43,'l',19);
INSERT INTO ezsearch_word VALUES (44,'consum',9);
INSERT INTO ezsearch_word VALUES (45,'et',14);
INSERT INTO ezsearch_word VALUES (46,'orte',9);
INSERT INTO ezsearch_word VALUES (47,'nis',9);
INSERT INTO ezsearch_word VALUES (48,'senit',10);
INSERT INTO ezsearch_word VALUES (49,'egertea',9);
INSERT INTO ezsearch_word VALUES (50,'umus',9);
INSERT INTO ezsearch_word VALUES (51,'uterest',9);
INSERT INTO ezsearch_word VALUES (52,'nimis',9);
INSERT INTO ezsearch_word VALUES (53,'similiis',9);
INSERT INTO ezsearch_word VALUES (54,'inam',9);
INSERT INTO ezsearch_word VALUES (55,'nessimi',9);
INSERT INTO ezsearch_word VALUES (56,'icatium',9);
INSERT INTO ezsearch_word VALUES (57,'auctus',11);
INSERT INTO ezsearch_word VALUES (58,'conloc',9);
INSERT INTO ezsearch_word VALUES (59,'tum',18);
INSERT INTO ezsearch_word VALUES (60,'omnondin',9);
INSERT INTO ezsearch_word VALUES (61,'trionsulut',9);
INSERT INTO ezsearch_word VALUES (62,'ius',9);
INSERT INTO ezsearch_word VALUES (63,'occit',4);
INSERT INTO ezsearch_word VALUES (64,'essimis',4);
INSERT INTO ezsearch_word VALUES (65,'senare',4);
INSERT INTO ezsearch_word VALUES (66,'tusse',4);
INSERT INTO ezsearch_word VALUES (67,'consimmore',4);
INSERT INTO ezsearch_word VALUES (68,'addum',5);
INSERT INTO ezsearch_word VALUES (69,'iam',11);
INSERT INTO ezsearch_word VALUES (70,'0',7);
INSERT INTO ezsearch_word VALUES (726,'hot',1);
INSERT INTO ezsearch_word VALUES (725,'frontpage',1);
INSERT INTO ezsearch_word VALUES (731,'lorem',19);
INSERT INTO ezsearch_word VALUES (728,'sport',2);
INSERT INTO ezsearch_word VALUES (75,'wheather',2);
INSERT INTO ezsearch_word VALUES (76,'world',7);
INSERT INTO ezsearch_word VALUES (77,'around',3);
INSERT INTO ezsearch_word VALUES (730,'word',1);
INSERT INTO ezsearch_word VALUES (79,'crossroads',1);
INSERT INTO ezsearch_word VALUES (80,'forum',7);
INSERT INTO ezsearch_word VALUES (81,'forums',1);
INSERT INTO ezsearch_word VALUES (82,'itabemunum',3);
INSERT INTO ezsearch_word VALUES (83,'din',3);
INSERT INTO ezsearch_word VALUES (84,'pulin',3);
INSERT INTO ezsearch_word VALUES (85,'defat',3);
INSERT INTO ezsearch_word VALUES (86,'es',4);
INSERT INTO ezsearch_word VALUES (87,'aderis',3);
INSERT INTO ezsearch_word VALUES (88,'maio',6);
INSERT INTO ezsearch_word VALUES (89,'seni',3);
INSERT INTO ezsearch_word VALUES (90,'conc',3);
INSERT INTO ezsearch_word VALUES (91,'re',6);
INSERT INTO ezsearch_word VALUES (92,'sim',3);
INSERT INTO ezsearch_word VALUES (93,'pos',3);
INSERT INTO ezsearch_word VALUES (94,'simodius',3);
INSERT INTO ezsearch_word VALUES (95,'consupp',3);
INSERT INTO ezsearch_word VALUES (96,'iquast',3);
INSERT INTO ezsearch_word VALUES (97,'autes',3);
INSERT INTO ezsearch_word VALUES (98,'horae',3);
INSERT INTO ezsearch_word VALUES (99,'ignatiam',3);
INSERT INTO ezsearch_word VALUES (100,'utelium',3);
INSERT INTO ezsearch_word VALUES (101,'licaedes',3);
INSERT INTO ezsearch_word VALUES (102,'publiss',3);
INSERT INTO ezsearch_word VALUES (103,'gnatia',3);
INSERT INTO ezsearch_word VALUES (104,'vivitem',3);
INSERT INTO ezsearch_word VALUES (105,'ortum',3);
INSERT INTO ezsearch_word VALUES (106,'consus',4);
INSERT INTO ezsearch_word VALUES (107,'auc',3);
INSERT INTO ezsearch_word VALUES (108,'is',35);
INSERT INTO ezsearch_word VALUES (109,'la',6);
INSERT INTO ezsearch_word VALUES (110,'rei',5);
INSERT INTO ezsearch_word VALUES (111,'terem',3);
INSERT INTO ezsearch_word VALUES (112,'rehem',3);
INSERT INTO ezsearch_word VALUES (113,'ates',3);
INSERT INTO ezsearch_word VALUES (114,'publiis',3);
INSERT INTO ezsearch_word VALUES (115,'nostris',3);
INSERT INTO ezsearch_word VALUES (116,'eliculinam',3);
INSERT INTO ezsearch_word VALUES (117,'publintes',3);
INSERT INTO ezsearch_word VALUES (118,'consuli',3);
INSERT INTO ezsearch_word VALUES (119,'epote',3);
INSERT INTO ezsearch_word VALUES (120,'factum',3);
INSERT INTO ezsearch_word VALUES (121,'orumus',1);
INSERT INTO ezsearch_word VALUES (122,'hoctus',1);
INSERT INTO ezsearch_word VALUES (123,'inatem',1);
INSERT INTO ezsearch_word VALUES (124,'iden',1);
INSERT INTO ezsearch_word VALUES (125,'hili',1);
INSERT INTO ezsearch_word VALUES (126,'cota',1);
INSERT INTO ezsearch_word VALUES (127,'inver',1);
INSERT INTO ezsearch_word VALUES (128,'li',1);
INSERT INTO ezsearch_word VALUES (129,'spectus',1);
INSERT INTO ezsearch_word VALUES (130,'inatum',1);
INSERT INTO ezsearch_word VALUES (131,'rompror',1);
INSERT INTO ezsearch_word VALUES (132,'test',4);
INSERT INTO ezsearch_word VALUES (133,'quonsis',1);
INSERT INTO ezsearch_word VALUES (134,'que',5);
INSERT INTO ezsearch_word VALUES (135,'ad',2);
INSERT INTO ezsearch_word VALUES (136,'catum',1);
INSERT INTO ezsearch_word VALUES (137,'controx',1);
INSERT INTO ezsearch_word VALUES (138,'milintemquem',1);
INSERT INTO ezsearch_word VALUES (139,'ca',1);
INSERT INTO ezsearch_word VALUES (140,'resse',1);
INSERT INTO ezsearch_word VALUES (141,'tem',2);
INSERT INTO ezsearch_word VALUES (142,'dest',1);
INSERT INTO ezsearch_word VALUES (143,'quem',1);
INSERT INTO ezsearch_word VALUES (144,'huium',1);
INSERT INTO ezsearch_word VALUES (145,'amdit',1);
INSERT INTO ezsearch_word VALUES (146,'pes',1);
INSERT INTO ezsearch_word VALUES (147,'sul',1);
INSERT INTO ezsearch_word VALUES (148,'hostres',1);
INSERT INTO ezsearch_word VALUES (149,'nostrem',1);
INSERT INTO ezsearch_word VALUES (150,'romaximandiusquis',1);
INSERT INTO ezsearch_word VALUES (151,'horachus',1);
INSERT INTO ezsearch_word VALUES (152,'cupim',4);
INSERT INTO ezsearch_word VALUES (153,'etoreni',1);
INSERT INTO ezsearch_word VALUES (154,'ivera',1);
INSERT INTO ezsearch_word VALUES (155,'sena',1);
INSERT INTO ezsearch_word VALUES (156,'omaiorit',1);
INSERT INTO ezsearch_word VALUES (157,'horidientem',1);
INSERT INTO ezsearch_word VALUES (158,'fin',1);
INSERT INTO ezsearch_word VALUES (159,'deatiss',1);
INSERT INTO ezsearch_word VALUES (160,'licio',1);
INSERT INTO ezsearch_word VALUES (161,'consul',1);
INSERT INTO ezsearch_word VALUES (162,'ut',7);
INSERT INTO ezsearch_word VALUES (163,'viveris',1);
INSERT INTO ezsearch_word VALUES (164,'uerit',1);
INSERT INTO ezsearch_word VALUES (165,'ferem',1);
INSERT INTO ezsearch_word VALUES (166,'orteatus',1);
INSERT INTO ezsearch_word VALUES (167,'hoc',1);
INSERT INTO ezsearch_word VALUES (168,'revidem',1);
INSERT INTO ezsearch_word VALUES (169,'optiam',1);
INSERT INTO ezsearch_word VALUES (170,'veriven',1);
INSERT INTO ezsearch_word VALUES (171,'inteluterum',1);
INSERT INTO ezsearch_word VALUES (172,'3',6);
INSERT INTO ezsearch_word VALUES (173,'4',1);
INSERT INTO ezsearch_word VALUES (174,'no',5);
INSERT INTO ezsearch_word VALUES (175,'title',1);
INSERT INTO ezsearch_word VALUES (176,'default',2);
INSERT INTO ezsearch_word VALUES (177,'first',7);
INSERT INTO ezsearch_word VALUES (178,'post',4);
INSERT INTO ezsearch_word VALUES (179,'this',20);
INSERT INTO ezsearch_word VALUES (180,'ever',1);
INSERT INTO ezsearch_word VALUES (181,'in',34);
INSERT INTO ezsearch_word VALUES (182,'ez',22);
INSERT INTO ezsearch_word VALUES (183,'publish',8);
INSERT INTO ezsearch_word VALUES (184,'second',3);
INSERT INTO ezsearch_word VALUES (185,'topic',1);
INSERT INTO ezsearch_word VALUES (186,'testing',1);
INSERT INTO ezsearch_word VALUES (187,'brd',1);
INSERT INTO ezsearch_word VALUES (188,'rtyrh',1);
INSERT INTO ezsearch_word VALUES (189,'reply',3);
INSERT INTO ezsearch_word VALUES (190,'what',5);
INSERT INTO ezsearch_word VALUES (191,'i',13);
INSERT INTO ezsearch_word VALUES (192,'think',2);
INSERT INTO ezsearch_word VALUES (193,'about',1);
INSERT INTO ezsearch_word VALUES (194,'that',22);
INSERT INTO ezsearch_word VALUES (195,'b',2);
INSERT INTO ezsearch_word VALUES (196,'ethgheh',1);
INSERT INTO ezsearch_word VALUES (197,'agree',2);
INSERT INTO ezsearch_word VALUES (198,'but',7);
INSERT INTO ezsearch_word VALUES (199,'how',5);
INSERT INTO ezsearch_word VALUES (200,'can',10);
INSERT INTO ezsearch_word VALUES (201,'you',9);
INSERT INTO ezsearch_word VALUES (202,'know',1);
INSERT INTO ezsearch_word VALUES (203,'it',19);
INSERT INTO ezsearch_word VALUES (204,'s',9);
INSERT INTO ezsearch_word VALUES (205,'true',1);
INSERT INTO ezsearch_word VALUES (206,'c',11);
INSERT INTO ezsearch_word VALUES (207,'bad',1);
INSERT INTO ezsearch_word VALUES (208,'yeah',1);
INSERT INTO ezsearch_word VALUES (209,'76u4u5',1);
INSERT INTO ezsearch_word VALUES (210,'my',3);
INSERT INTO ezsearch_word VALUES (211,'foo',1);
INSERT INTO ezsearch_word VALUES (212,'bar',1);
INSERT INTO ezsearch_word VALUES (213,'d',1);
INSERT INTO ezsearch_word VALUES (214,'rfef',1);
INSERT INTO ezsearch_word VALUES (215,'rfdegrw3',1);
INSERT INTO ezsearch_word VALUES (216,'grwegwgw',1);
INSERT INTO ezsearch_word VALUES (217,'education',2);
INSERT INTO ezsearch_word VALUES (218,'suppose',2);
INSERT INTO ezsearch_word VALUES (219,'to',59);
INSERT INTO ezsearch_word VALUES (220,'make',3);
INSERT INTO ezsearch_word VALUES (221,'me',3);
INSERT INTO ezsearch_word VALUES (222,'feel',3);
INSERT INTO ezsearch_word VALUES (223,'smarter',2);
INSERT INTO ezsearch_word VALUES (224,'besides',3);
INSERT INTO ezsearch_word VALUES (225,'everytime',2);
INSERT INTO ezsearch_word VALUES (226,'learn',2);
INSERT INTO ezsearch_word VALUES (227,'something',3);
INSERT INTO ezsearch_word VALUES (228,'new',5);
INSERT INTO ezsearch_word VALUES (229,'pushes',2);
INSERT INTO ezsearch_word VALUES (230,'some',8);
INSERT INTO ezsearch_word VALUES (231,'old',3);
INSERT INTO ezsearch_word VALUES (232,'stuff',2);
INSERT INTO ezsearch_word VALUES (233,'out',6);
INSERT INTO ezsearch_word VALUES (234,'of',44);
INSERT INTO ezsearch_word VALUES (235,'brain',2);
INSERT INTO ezsearch_word VALUES (236,'remember',4);
INSERT INTO ezsearch_word VALUES (237,'when',12);
INSERT INTO ezsearch_word VALUES (238,'took',2);
INSERT INTO ezsearch_word VALUES (239,'home',4);
INSERT INTO ezsearch_word VALUES (240,'wine',2);
INSERT INTO ezsearch_word VALUES (241,'making',2);
INSERT INTO ezsearch_word VALUES (242,'course',2);
INSERT INTO ezsearch_word VALUES (243,'and',41);
INSERT INTO ezsearch_word VALUES (244,'forgot',2);
INSERT INTO ezsearch_word VALUES (245,'drive',2);
INSERT INTO ezsearch_word VALUES (246,'cool',1);
INSERT INTO ezsearch_word VALUES (247,'so',6);
INSERT INTO ezsearch_word VALUES (248,'let',3);
INSERT INTO ezsearch_word VALUES (249,'begin',1);
INSERT INTO ezsearch_word VALUES (250,'hi',1);
INSERT INTO ezsearch_word VALUES (251,'have',18);
INSERT INTO ezsearch_word VALUES (252,'added',1);
INSERT INTO ezsearch_word VALUES (253,'three',1);
INSERT INTO ezsearch_word VALUES (254,'tables',4);
INSERT INTO ezsearch_word VALUES (255,'into',3);
INSERT INTO ezsearch_word VALUES (256,'mysql',2);
INSERT INTO ezsearch_word VALUES (257,'database',1);
INSERT INTO ezsearch_word VALUES (258,'eznotification_rule',1);
INSERT INTO ezsearch_word VALUES (259,'eznotification_user_link',1);
INSERT INTO ezsearch_word VALUES (260,'ezmessage',1);
INSERT INTO ezsearch_word VALUES (261,'only',8);
INSERT INTO ezsearch_word VALUES (262,'changed',1);
INSERT INTO ezsearch_word VALUES (263,'kernel',1);
INSERT INTO ezsearch_word VALUES (264,'sql',1);
INSERT INTO ezsearch_word VALUES (265,'book',1);
INSERT INTO ezsearch_word VALUES (266,'corner',1);
INSERT INTO ezsearch_word VALUES (267,'top',2);
INSERT INTO ezsearch_word VALUES (268,'20',2);
INSERT INTO ezsearch_word VALUES (269,'books',2);
INSERT INTO ezsearch_word VALUES (270,'bestsellers',1);
INSERT INTO ezsearch_word VALUES (271,'recommendations',1);
INSERT INTO ezsearch_word VALUES (272,'authors',1);
INSERT INTO ezsearch_word VALUES (273,'wandering',1);
INSERT INTO ezsearch_word VALUES (274,'cow',1);
INSERT INTO ezsearch_word VALUES (275,'catro',3);
INSERT INTO ezsearch_word VALUES (276,'cappelen',1);
INSERT INTO ezsearch_word VALUES (277,'2000',1);
INSERT INTO ezsearch_word VALUES (278,'habefac',2);
INSERT INTO ezsearch_word VALUES (279,'tam',5);
INSERT INTO ezsearch_word VALUES (280,'am',2);
INSERT INTO ezsearch_word VALUES (281,'aucii',2);
INSERT INTO ezsearch_word VALUES (282,'vivehebemorum',2);
INSERT INTO ezsearch_word VALUES (283,'hocura',2);
INSERT INTO ezsearch_word VALUES (284,'name',2);
INSERT INTO ezsearch_word VALUES (285,'forbis',2);
INSERT INTO ezsearch_word VALUES (286,'habi',3);
INSERT INTO ezsearch_word VALUES (287,'senatum',3);
INSERT INTO ezsearch_word VALUES (288,'verfinemus',3);
INSERT INTO ezsearch_word VALUES (289,'opublicia',3);
INSERT INTO ezsearch_word VALUES (290,'poerfer',3);
INSERT INTO ezsearch_word VALUES (291,'icati',3);
INSERT INTO ezsearch_word VALUES (292,'quit',3);
INSERT INTO ezsearch_word VALUES (293,'fue',4);
INSERT INTO ezsearch_word VALUES (294,'tela',3);
INSERT INTO ezsearch_word VALUES (295,'esi',3);
INSERT INTO ezsearch_word VALUES (296,'intem',4);
INSERT INTO ezsearch_word VALUES (297,'di',3);
INSERT INTO ezsearch_word VALUES (298,'nestiu',3);
INSERT INTO ezsearch_word VALUES (299,'patruriam',3);
INSERT INTO ezsearch_word VALUES (300,'potiem',3);
INSERT INTO ezsearch_word VALUES (301,'se',7);
INSERT INTO ezsearch_word VALUES (302,'factuasdam',3);
INSERT INTO ezsearch_word VALUES (303,'auctum',3);
INSERT INTO ezsearch_word VALUES (304,'puli',4);
INSERT INTO ezsearch_word VALUES (305,'publia',3);
INSERT INTO ezsearch_word VALUES (306,'nos',4);
INSERT INTO ezsearch_word VALUES (307,'stretil',3);
INSERT INTO ezsearch_word VALUES (308,'erra',3);
INSERT INTO ezsearch_word VALUES (309,'graris',4);
INSERT INTO ezsearch_word VALUES (310,'hos',3);
INSERT INTO ezsearch_word VALUES (311,'hosuam',3);
INSERT INTO ezsearch_word VALUES (312,'p',3);
INSERT INTO ezsearch_word VALUES (313,'si',3);
INSERT INTO ezsearch_word VALUES (314,'ponfecrei',3);
INSERT INTO ezsearch_word VALUES (315,'casdactesine',3);
INSERT INTO ezsearch_word VALUES (316,'mac',3);
INSERT INTO ezsearch_word VALUES (317,'catili',3);
INSERT INTO ezsearch_word VALUES (318,'prae',4);
INSERT INTO ezsearch_word VALUES (319,'mantis',3);
INSERT INTO ezsearch_word VALUES (320,'interra',3);
INSERT INTO ezsearch_word VALUES (321,'usually',1);
INSERT INTO ezsearch_word VALUES (322,'dispatched',1);
INSERT INTO ezsearch_word VALUES (323,'within',1);
INSERT INTO ezsearch_word VALUES (324,'24',1);
INSERT INTO ezsearch_word VALUES (325,'hours',1);
INSERT INTO ezsearch_word VALUES (326,'8',1);
INSERT INTO ezsearch_word VALUES (327,'09',1);
INSERT INTO ezsearch_word VALUES (328,'drumseller',1);
INSERT INTO ezsearch_word VALUES (329,'10',2);
INSERT INTO ezsearch_word VALUES (330,'6',1);
INSERT INTO ezsearch_word VALUES (331,'5',2);
INSERT INTO ezsearch_word VALUES (332,'fantastic',1);
INSERT INTO ezsearch_word VALUES (333,'jezmondinio',1);
INSERT INTO ezsearch_word VALUES (334,'moscow',1);
INSERT INTO ezsearch_word VALUES (335,'russia',1);
INSERT INTO ezsearch_word VALUES (336,'pat',1);
INSERT INTO ezsearch_word VALUES (337,'fatique',1);
INSERT INTO ezsearch_word VALUES (338,'idem',2);
INSERT INTO ezsearch_word VALUES (339,'erfervi',1);
INSERT INTO ezsearch_word VALUES (340,'erit',1);
INSERT INTO ezsearch_word VALUES (341,'nore',1);
INSERT INTO ezsearch_word VALUES (342,'culicavenius',1);
INSERT INTO ezsearch_word VALUES (343,'horbitas',1);
INSERT INTO ezsearch_word VALUES (344,'quidefactus',1);
INSERT INTO ezsearch_word VALUES (345,'viliam',1);
INSERT INTO ezsearch_word VALUES (346,'roma',1);
INSERT INTO ezsearch_word VALUES (347,'constil',1);
INSERT INTO ezsearch_word VALUES (348,'host',2);
INSERT INTO ezsearch_word VALUES (349,'publii',1);
INSERT INTO ezsearch_word VALUES (350,'probses',1);
INSERT INTO ezsearch_word VALUES (351,'locciemoerum',1);
INSERT INTO ezsearch_word VALUES (352,'con',2);
INSERT INTO ezsearch_word VALUES (353,'dum',1);
INSERT INTO ezsearch_word VALUES (354,'conum',1);
INSERT INTO ezsearch_word VALUES (355,'vis',1);
INSERT INTO ezsearch_word VALUES (356,'ocre',1);
INSERT INTO ezsearch_word VALUES (357,'confirm',2);
INSERT INTO ezsearch_word VALUES (358,'hilicae',1);
INSERT INTO ezsearch_word VALUES (359,'icienteriam',1);
INSERT INTO ezsearch_word VALUES (360,'esil',1);
INSERT INTO ezsearch_word VALUES (361,'hacteri',1);
INSERT INTO ezsearch_word VALUES (362,'factoret',1);
INSERT INTO ezsearch_word VALUES (363,'nox',2);
INSERT INTO ezsearch_word VALUES (364,'nonimus',1);
INSERT INTO ezsearch_word VALUES (365,'cotabefacit',1);
INSERT INTO ezsearch_word VALUES (366,'defecut',1);
INSERT INTO ezsearch_word VALUES (367,'etris',1);
INSERT INTO ezsearch_word VALUES (368,'speri',1);
INSERT INTO ezsearch_word VALUES (369,'acioca',1);
INSERT INTO ezsearch_word VALUES (370,'maet',1);
INSERT INTO ezsearch_word VALUES (371,'cas',1);
INSERT INTO ezsearch_word VALUES (372,'nulinte',1);
INSERT INTO ezsearch_word VALUES (373,'renica',1);
INSERT INTO ezsearch_word VALUES (374,'constraeque',1);
INSERT INTO ezsearch_word VALUES (375,'probus',1);
INSERT INTO ezsearch_word VALUES (376,'reis',1);
INSERT INTO ezsearch_word VALUES (377,'publibuntia',1);
INSERT INTO ezsearch_word VALUES (378,'mo',3);
INSERT INTO ezsearch_word VALUES (379,'catqui',1);
INSERT INTO ezsearch_word VALUES (380,'pubissimis',1);
INSERT INTO ezsearch_word VALUES (381,'apere',1);
INSERT INTO ezsearch_word VALUES (382,'nor',1);
INSERT INTO ezsearch_word VALUES (383,'iaet',1);
INSERT INTO ezsearch_word VALUES (384,'italegero',1);
INSERT INTO ezsearch_word VALUES (385,'movente',1);
INSERT INTO ezsearch_word VALUES (386,'issimus',1);
INSERT INTO ezsearch_word VALUES (387,'niu',1);
INSERT INTO ezsearch_word VALUES (388,'consulintiu',1);
INSERT INTO ezsearch_word VALUES (389,'vitin',1);
INSERT INTO ezsearch_word VALUES (390,'dis',1);
INSERT INTO ezsearch_word VALUES (391,'opicae',1);
INSERT INTO ezsearch_word VALUES (392,'vivere',1);
INSERT INTO ezsearch_word VALUES (393,'porum',1);
INSERT INTO ezsearch_word VALUES (394,'spiordiem',1);
INSERT INTO ezsearch_word VALUES (395,'mactantenatu',1);
INSERT INTO ezsearch_word VALUES (396,'cat',1);
INSERT INTO ezsearch_word VALUES (397,'serenih',1);
INSERT INTO ezsearch_word VALUES (398,'libus',1);
INSERT INTO ezsearch_word VALUES (399,'sedo',1);
INSERT INTO ezsearch_word VALUES (400,'num',1);
INSERT INTO ezsearch_word VALUES (401,'inatium',1);
INSERT INTO ezsearch_word VALUES (402,'diem',1);
INSERT INTO ezsearch_word VALUES (403,'maiorei',1);
INSERT INTO ezsearch_word VALUES (404,'senam',1);
INSERT INTO ezsearch_word VALUES (405,'ora',1);
INSERT INTO ezsearch_word VALUES (406,'nonsult',1);
INSERT INTO ezsearch_word VALUES (407,'retis',1);
INSERT INTO ezsearch_word VALUES (633,'catch',1);
INSERT INTO ezsearch_word VALUES (632,'teams',1);
INSERT INTO ezsearch_word VALUES (631,'other',3);
INSERT INTO ezsearch_word VALUES (630,'up',5);
INSERT INTO ezsearch_word VALUES (629,'dominant',1);
INSERT INTO ezsearch_word VALUES (628,'because',1);
INSERT INTO ezsearch_word VALUES (627,'reaction',2);
INSERT INTO ezsearch_word VALUES (626,'jerk',1);
INSERT INTO ezsearch_word VALUES (625,'knee',1);
INSERT INTO ezsearch_word VALUES (624,'afp',1);
INSERT INTO ezsearch_word VALUES (623,'agency',1);
INSERT INTO ezsearch_word VALUES (622,'according',1);
INSERT INTO ezsearch_word VALUES (621,'said',2);
INSERT INTO ezsearch_word VALUES (620,'henman',1);
INSERT INTO ezsearch_word VALUES (619,'tim',1);
INSERT INTO ezsearch_word VALUES (618,'meets',1);
INSERT INTO ezsearch_word VALUES (617,'racket',1);
INSERT INTO ezsearch_word VALUES (616,'his',7);
INSERT INTO ezsearch_word VALUES (615,'from',13);
INSERT INTO ezsearch_word VALUES (614,'strings',1);
INSERT INTO ezsearch_word VALUES (613,'two',1);
INSERT INTO ezsearch_word VALUES (612,'sampras',1);
INSERT INTO ezsearch_word VALUES (611,'pete',1);
INSERT INTO ezsearch_word VALUES (610,'or',2);
INSERT INTO ezsearch_word VALUES (609,'chelsea',1);
INSERT INTO ezsearch_word VALUES (608,'play',2);
INSERT INTO ezsearch_word VALUES (607,'they',6);
INSERT INTO ezsearch_word VALUES (606,'men',1);
INSERT INTO ezsearch_word VALUES (605,'nine',1);
INSERT INTO ezsearch_word VALUES (604,'arsenal',1);
INSERT INTO ezsearch_word VALUES (603,'mean',1);
INSERT INTO ezsearch_word VALUES (602,'does',3);
INSERT INTO ezsearch_word VALUES (601,'themselves',1);
INSERT INTO ezsearch_word VALUES (600,'gap',1);
INSERT INTO ezsearch_word VALUES (599,'close',2);
INSERT INTO ezsearch_word VALUES (598,'harder',2);
INSERT INTO ezsearch_word VALUES (597,'work',2);
INSERT INTO ezsearch_word VALUES (596,'must',3);
INSERT INTO ezsearch_word VALUES (595,'rivals',1);
INSERT INTO ezsearch_word VALUES (594,'champions',1);
INSERT INTO ezsearch_word VALUES (593,'saying',1);
INSERT INTO ezsearch_word VALUES (592,'way',1);
INSERT INTO ezsearch_word VALUES (591,'not',14);
INSERT INTO ezsearch_word VALUES (590,'insisted',1);
INSERT INTO ezsearch_word VALUES (589,'challengers',1);
INSERT INTO ezsearch_word VALUES (588,'closest',1);
INSERT INTO ezsearch_word VALUES (587,'ferrari',3);
INSERT INTO ezsearch_word VALUES (586,'been',6);
INSERT INTO ezsearch_word VALUES (585,'team',3);
INSERT INTO ezsearch_word VALUES (584,'whose',1);
INSERT INTO ezsearch_word VALUES (583,'however',1);
INSERT INTO ezsearch_word VALUES (582,'year',3);
INSERT INTO ezsearch_word VALUES (581,'field',2);
INSERT INTO ezsearch_word VALUES (580,'over',3);
INSERT INTO ezsearch_word VALUES (579,'held',2);
INSERT INTO ezsearch_word VALUES (578,'he',8);
INSERT INTO ezsearch_word VALUES (577,'point',2);
INSERT INTO ezsearch_word VALUES (576,'every',2);
INSERT INTO ezsearch_word VALUES (575,'for',12);
INSERT INTO ezsearch_word VALUES (574,'kilogramme',1);
INSERT INTO ezsearch_word VALUES (573,'extra',1);
INSERT INTO ezsearch_word VALUES (572,'carry',1);
INSERT INTO ezsearch_word VALUES (571,'forced',1);
INSERT INTO ezsearch_word VALUES (570,'schumacher',1);
INSERT INTO ezsearch_word VALUES (569,'michael',1);
INSERT INTO ezsearch_word VALUES (568,'see',2);
INSERT INTO ezsearch_word VALUES (567,'would',2);
INSERT INTO ezsearch_word VALUES (566,'ecclestone',1);
INSERT INTO ezsearch_word VALUES (565,'bernie',1);
INSERT INTO ezsearch_word VALUES (564,'by',9);
INSERT INTO ezsearch_word VALUES (563,'forward',3);
INSERT INTO ezsearch_word VALUES (562,'put',1);
INSERT INTO ezsearch_word VALUES (561,'was',14);
INSERT INTO ezsearch_word VALUES (560,'racing',1);
INSERT INTO ezsearch_word VALUES (559,'formula',1);
INSERT INTO ezsearch_word VALUES (558,'interest',3);
INSERT INTO ezsearch_word VALUES (557,'boost',1);
INSERT INTO ezsearch_word VALUES (556,'suggested',1);
INSERT INTO ezsearch_word VALUES (555,'being',1);
INSERT INTO ezsearch_word VALUES (554,'several',1);
INSERT INTO ezsearch_word VALUES (553,'one',8);
INSERT INTO ezsearch_word VALUES (552,'idea',1);
INSERT INTO ezsearch_word VALUES (551,'domination',1);
INSERT INTO ezsearch_word VALUES (550,'level',1);
INSERT INTO ezsearch_word VALUES (549,'their',6);
INSERT INTO ezsearch_word VALUES (548,'cut',2);
INSERT INTO ezsearch_word VALUES (547,'bid',1);
INSERT INTO ezsearch_word VALUES (546,'a',36);
INSERT INTO ezsearch_word VALUES (545,'season',4);
INSERT INTO ezsearch_word VALUES (544,'next',5);
INSERT INTO ezsearch_word VALUES (543,'ferraris',1);
INSERT INTO ezsearch_word VALUES (542,'ballast',1);
INSERT INTO ezsearch_word VALUES (541,'add',1);
INSERT INTO ezsearch_word VALUES (540,'proposals',1);
INSERT INTO ezsearch_word VALUES (539,'against',1);
INSERT INTO ezsearch_word VALUES (538,'spoken',1);
INSERT INTO ezsearch_word VALUES (537,'has',8);
INSERT INTO ezsearch_word VALUES (536,'head',2);
INSERT INTO ezsearch_word VALUES (535,'patrick',1);
INSERT INTO ezsearch_word VALUES (534,'director',1);
INSERT INTO ezsearch_word VALUES (533,'technical',1);
INSERT INTO ezsearch_word VALUES (532,'williams',3);
INSERT INTO ezsearch_word VALUES (634,'them',3);
INSERT INTO ezsearch_word VALUES (635,'overtake',1);
INSERT INTO ezsearch_word VALUES (636,'we',11);
INSERT INTO ezsearch_word VALUES (637,'are',8);
INSERT INTO ezsearch_word VALUES (638,'more',3);
INSERT INTO ezsearch_word VALUES (639,'than',2);
INSERT INTO ezsearch_word VALUES (640,'capable',1);
INSERT INTO ezsearch_word VALUES (641,'doing',1);
INSERT INTO ezsearch_word VALUES (642,'though',1);
INSERT INTO ezsearch_word VALUES (643,'secured',1);
INSERT INTO ezsearch_word VALUES (644,'place',2);
INSERT INTO ezsearch_word VALUES (645,'constructors',1);
INSERT INTO ezsearch_word VALUES (646,'championship',1);
INSERT INTO ezsearch_word VALUES (647,'won',1);
INSERT INTO ezsearch_word VALUES (648,'single',1);
INSERT INTO ezsearch_word VALUES (649,'race',1);
INSERT INTO ezsearch_word VALUES (650,'scored',2);
INSERT INTO ezsearch_word VALUES (651,'less',1);
INSERT INTO ezsearch_word VALUES (652,'half',3);
INSERT INTO ezsearch_word VALUES (653,'as',11);
INSERT INTO ezsearch_word VALUES (654,'many',3);
INSERT INTO ezsearch_word VALUES (655,'points',1);
INSERT INTO ezsearch_word VALUES (720,'m',1);
INSERT INTO ezsearch_word VALUES (719,'evening',1);
INSERT INTO ezsearch_word VALUES (693,'friday',2);
INSERT INTO ezsearch_word VALUES (718,'on',10);
INSERT INTO ezsearch_word VALUES (717,'street',1);
INSERT INTO ezsearch_word VALUES (716,'go',5);
INSERT INTO ezsearch_word VALUES (715,'do',3);
INSERT INTO ezsearch_word VALUES (688,'bear',2);
INSERT INTO ezsearch_word VALUES (714,'drink',1);
INSERT INTO ezsearch_word VALUES (686,'at',5);
INSERT INTO ezsearch_word VALUES (713,'sit',1);
INSERT INTO ezsearch_word VALUES (712,'recomended',1);
INSERT INTO ezsearch_word VALUES (711,'norway',5);
INSERT INTO ezsearch_word VALUES (710,'town',1);
INSERT INTO ezsearch_word VALUES (709,'skien',1);
INSERT INTO ezsearch_word VALUES (708,'very',2);
INSERT INTO ezsearch_word VALUES (707,'now',3);
INSERT INTO ezsearch_word VALUES (706,'huge',1);
INSERT INTO ezsearch_word VALUES (705,'near',2);
INSERT INTO ezsearch_word VALUES (704,'typhoon',2);
INSERT INTO ezsearch_word VALUES (698,'better',1);
INSERT INTO ezsearch_word VALUES (699,'seet',1);
INSERT INTO ezsearch_word VALUES (700,'whole',1);
INSERT INTO ezsearch_word VALUES (701,'need',1);
INSERT INTO ezsearch_word VALUES (702,'buy',1);
INSERT INTO ezsearch_word VALUES (703,'today',1);
INSERT INTO ezsearch_word VALUES (721,'just',3);
INSERT INTO ezsearch_word VALUES (722,'kidding',1);
INSERT INTO ezsearch_word VALUES (732,'ipsum',19);
INSERT INTO ezsearch_word VALUES (733,'also',5);
INSERT INTO ezsearch_word VALUES (734,'be',10);
INSERT INTO ezsearch_word VALUES (735,'used',3);
INSERT INTO ezsearch_word VALUES (736,'beta',1);
INSERT INTO ezsearch_word VALUES (737,'find',2);
INSERT INTO ezsearch_word VALUES (738,'bugs',2);
INSERT INTO ezsearch_word VALUES (739,'will',7);
INSERT INTO ezsearch_word VALUES (740,'game',2);
INSERT INTO ezsearch_word VALUES (741,'beta1',1);
INSERT INTO ezsearch_word VALUES (742,'ok',1);
INSERT INTO ezsearch_word VALUES (743,'didn',1);
INSERT INTO ezsearch_word VALUES (744,'t',1);
INSERT INTO ezsearch_word VALUES (745,'prove',2);
INSERT INTO ezsearch_word VALUES (746,'hard',2);
INSERT INTO ezsearch_word VALUES (747,'say',1);
INSERT INTO ezsearch_word VALUES (748,'time',3);
INSERT INTO ezsearch_word VALUES (749,'another',4);
INSERT INTO ezsearch_word VALUES (750,'article',1);
INSERT INTO ezsearch_word VALUES (751,'information',3);
INSERT INTO ezsearch_word VALUES (752,'much',2);
INSERT INTO ezsearch_word VALUES (753,'here',2);
INSERT INTO ezsearch_word VALUES (754,'create',1);
INSERT INTO ezsearch_word VALUES (755,'interesting',4);
INSERT INTO ezsearch_word VALUES (986,'gold',1);
INSERT INTO ezsearch_word VALUES (764,'forest',3);
INSERT INTO ezsearch_word VALUES (759,'flowers',2);
INSERT INTO ezsearch_word VALUES (760,'water',2);
INSERT INTO ezsearch_word VALUES (761,'animals',2);
INSERT INTO ezsearch_word VALUES (762,'landscape',2);
INSERT INTO ezsearch_word VALUES (985,'',5);
INSERT INTO ezsearch_word VALUES (984,'galleries',1);
INSERT INTO ezsearch_word VALUES (983,'following',1);
INSERT INTO ezsearch_word VALUES (982,'presents',1);
INSERT INTO ezsearch_word VALUES (981,'drift',1);
INSERT INTO ezsearch_word VALUES (980,'mind',1);
INSERT INTO ezsearch_word VALUES (979,'sights',2);
INSERT INTO ezsearch_word VALUES (978,'feelings',1);
INSERT INTO ezsearch_word VALUES (977,'smells',1);
INSERT INTO ezsearch_word VALUES (976,'sounds',1);
INSERT INTO ezsearch_word VALUES (975,'window',1);
INSERT INTO ezsearch_word VALUES (974,'our',5);
INSERT INTO ezsearch_word VALUES (973,'outside',2);
INSERT INTO ezsearch_word VALUES (972,'right',1);
INSERT INTO ezsearch_word VALUES (971,'forget',2);
INSERT INTO ezsearch_word VALUES (970,'still',2);
INSERT INTO ezsearch_word VALUES (969,'people',2);
INSERT INTO ezsearch_word VALUES (968,'us',4);
INSERT INTO ezsearch_word VALUES (967,'surrounding',1);
INSERT INTO ezsearch_word VALUES (966,'minutes',2);
INSERT INTO ezsearch_word VALUES (965,'few',2);
INSERT INTO ezsearch_word VALUES (964,'dream',1);
INSERT INTO ezsearch_word VALUES (963,'through',1);
INSERT INTO ezsearch_word VALUES (962,'nature',1);
INSERT INTO ezsearch_word VALUES (961,'mother',1);
INSERT INTO ezsearch_word VALUES (960,'salute',1);
INSERT INTO ezsearch_word VALUES (959,'where',1);
INSERT INTO ezsearch_word VALUES (958,'space',1);
INSERT INTO ezsearch_word VALUES (957,'breathing',1);
INSERT INTO ezsearch_word VALUES (956,'deserved',1);
INSERT INTO ezsearch_word VALUES (955,'well',3);
INSERT INTO ezsearch_word VALUES (954,'your',2);
INSERT INTO ezsearch_word VALUES (953,'get',2);
INSERT INTO ezsearch_word VALUES (952,'encounter',1);
INSERT INTO ezsearch_word VALUES (951,'hectic',1);
INSERT INTO ezsearch_word VALUES (950,'noisy',1);
INSERT INTO ezsearch_word VALUES (949,'stressful',1);
INSERT INTO ezsearch_word VALUES (948,'escape',1);
INSERT INTO ezsearch_word VALUES (947,'chance',1);
INSERT INTO ezsearch_word VALUES (946,'day',4);
INSERT INTO ezsearch_word VALUES (945,'winter',2);
INSERT INTO ezsearch_word VALUES (944,'clear',1);
INSERT INTO ezsearch_word VALUES (943,'cold',1);
INSERT INTO ezsearch_word VALUES (942,'mountain',1);
INSERT INTO ezsearch_word VALUES (941,'snowy',1);
INSERT INTO ezsearch_word VALUES (940,'beauty',5);
INSERT INTO ezsearch_word VALUES (939,'pine',1);
INSERT INTO ezsearch_word VALUES (938,'smell',1);
INSERT INTO ezsearch_word VALUES (937,'bee',1);
INSERT INTO ezsearch_word VALUES (936,'humming',1);
INSERT INTO ezsearch_word VALUES (935,'life',2);
INSERT INTO ezsearch_word VALUES (934,'thing',1);
INSERT INTO ezsearch_word VALUES (933,'small',1);
INSERT INTO ezsearch_word VALUES (844,'box',3);
INSERT INTO ezsearch_word VALUES (843,'white',3);
INSERT INTO ezsearch_word VALUES (932,'forgotten',1);
INSERT INTO ezsearch_word VALUES (931,'all',6);
INSERT INTO ezsearch_word VALUES (930,'away',4);
INSERT INTO ezsearch_word VALUES (929,'getting',2);
INSERT INTO ezsearch_word VALUES (928,'difficulties',1);
INSERT INTO ezsearch_word VALUES (927,'having',1);
INSERT INTO ezsearch_word VALUES (926,'art',1);
INSERT INTO ezsearch_word VALUES (925,'contemporary',1);
INSERT INTO ezsearch_word VALUES (924,'whitebox',1);
INSERT INTO ezsearch_word VALUES (989,'who',3);
INSERT INTO ezsearch_word VALUES (990,'wander',1);
INSERT INTO ezsearch_word VALUES (991,'lost',1);
INSERT INTO ezsearch_word VALUES (992,'strong',1);
INSERT INTO ezsearch_word VALUES (993,'wither',1);
INSERT INTO ezsearch_word VALUES (994,'deep',2);
INSERT INTO ezsearch_word VALUES (995,'roots',1);
INSERT INTO ezsearch_word VALUES (996,'reached',1);
INSERT INTO ezsearch_word VALUES (997,'frost',1);
INSERT INTO ezsearch_word VALUES (998,'j',1);
INSERT INTO ezsearch_word VALUES (999,'r',2);
INSERT INTO ezsearch_word VALUES (1000,'tolkien',1);
INSERT INTO ezsearch_word VALUES (1001,'lord',1);
INSERT INTO ezsearch_word VALUES (1002,'rings',1);
INSERT INTO ezsearch_word VALUES (1153,'growing',1);
INSERT INTO ezsearch_word VALUES (1152,'always',1);
INSERT INTO ezsearch_word VALUES (1151,'step',1);
INSERT INTO ezsearch_word VALUES (1150,'shows',1);
INSERT INTO ezsearch_word VALUES (1149,'picked',1);
INSERT INTO ezsearch_word VALUES (1148,'proudly',1);
INSERT INTO ezsearch_word VALUES (1147,'expansion',1);
INSERT INTO ezsearch_word VALUES (1146,'global',1);
INSERT INTO ezsearch_word VALUES (1145,'assist',1);
INSERT INTO ezsearch_word VALUES (1144,'investors',1);
INSERT INTO ezsearch_word VALUES (1143,'asian',1);
INSERT INTO ezsearch_word VALUES (1142,'key',2);
INSERT INTO ezsearch_word VALUES (1141,'introduce',1);
INSERT INTO ezsearch_word VALUES (1140,'privately',1);
INSERT INTO ezsearch_word VALUES (1139,'growth',1);
INSERT INTO ezsearch_word VALUES (1138,'high',1);
INSERT INTO ezsearch_word VALUES (1137,'20+',1);
INSERT INTO ezsearch_word VALUES (1136,'coming',1);
INSERT INTO ezsearch_word VALUES (1135,'identify',1);
INSERT INTO ezsearch_word VALUES (1134,'objective',1);
INSERT INTO ezsearch_word VALUES (1133,'corporate',1);
INSERT INTO ezsearch_word VALUES (1132,'banks',1);
INSERT INTO ezsearch_word VALUES (1131,'investment',1);
INSERT INTO ezsearch_word VALUES (1130,'advisors',2);
INSERT INTO ezsearch_word VALUES (1129,'capitalists',1);
INSERT INTO ezsearch_word VALUES (1128,'venture',1);
INSERT INTO ezsearch_word VALUES (1127,'corporations',1);
INSERT INTO ezsearch_word VALUES (1126,'chip',1);
INSERT INTO ezsearch_word VALUES (1125,'blue',1);
INSERT INTO ezsearch_word VALUES (1124,'leading',2);
INSERT INTO ezsearch_word VALUES (1123,'professionals',2);
INSERT INTO ezsearch_word VALUES (1122,'senior',1);
INSERT INTO ezsearch_word VALUES (1121,'60',1);
INSERT INTO ezsearch_word VALUES (1120,'group',1);
INSERT INTO ezsearch_word VALUES (1119,'selective',1);
INSERT INTO ezsearch_word VALUES (1118,'network',1);
INSERT INTO ezsearch_word VALUES (1117,'meet',1);
INSERT INTO ezsearch_word VALUES (1116,'opportunity',1);
INSERT INTO ezsearch_word VALUES (1115,'unique',1);
INSERT INTO ezsearch_word VALUES (1114,'technology',3);
INSERT INTO ezsearch_word VALUES (1113,'upcoming',1);
INSERT INTO ezsearch_word VALUES (1112,'innovative',1);
INSERT INTO ezsearch_word VALUES (1111,'provided',1);
INSERT INTO ezsearch_word VALUES (1110,'press',1);
INSERT INTO ezsearch_word VALUES (1109,'international',2);
INSERT INTO ezsearch_word VALUES (1108,'covered',1);
INSERT INTO ezsearch_word VALUES (1107,'widely',1);
INSERT INTO ezsearch_word VALUES (1106,'event',1);
INSERT INTO ezsearch_word VALUES (1105,'future',2);
INSERT INTO ezsearch_word VALUES (1104,'promising',1);
INSERT INTO ezsearch_word VALUES (1103,'ett',1);
INSERT INTO ezsearch_word VALUES (1102,'26',2);
INSERT INTO ezsearch_word VALUES (1101,'companies',6);
INSERT INTO ezsearch_word VALUES (1100,'exciting',1);
INSERT INTO ezsearch_word VALUES (1099,'most',6);
INSERT INTO ezsearch_word VALUES (1098,'association',2);
INSERT INTO ezsearch_word VALUES (1097,'tour',3);
INSERT INTO ezsearch_word VALUES (1096,'tech',3);
INSERT INTO ezsearch_word VALUES (1095,'european',3);
INSERT INTO ezsearch_word VALUES (1094,'handpicked',1);
INSERT INTO ezsearch_word VALUES (1093,'company',3);
INSERT INTO ezsearch_word VALUES (1092,'announce',1);
INSERT INTO ezsearch_word VALUES (1091,'proud',1);
INSERT INTO ezsearch_word VALUES (1090,'award',1);
INSERT INTO ezsearch_word VALUES (1089,'earns',1);
INSERT INTO ezsearch_word VALUES (1088,'systems',12);
INSERT INTO ezsearch_word VALUES (1154,'popularity',1);
INSERT INTO ezsearch_word VALUES (1155,'great',3);
INSERT INTO ezsearch_word VALUES (1156,'success',1);
INSERT INTO ezsearch_word VALUES (1157,'since',3);
INSERT INTO ezsearch_word VALUES (1158,'start',2);
INSERT INTO ezsearch_word VALUES (1159,'august',1);
INSERT INTO ezsearch_word VALUES (1160,'2002',1);
INSERT INTO ezsearch_word VALUES (1161,'among',1);
INSERT INTO ezsearch_word VALUES (1162,'worldwide',1);
INSERT INTO ezsearch_word VALUES (1163,'leaders',1);
INSERT INTO ezsearch_word VALUES (1164,'content',2);
INSERT INTO ezsearch_word VALUES (1165,'management',1);
INSERT INTO ezsearch_word VALUES (1166,'software',1);
INSERT INTO ezsearch_word VALUES (1167,'expected',1);
INSERT INTO ezsearch_word VALUES (1168,'release',1);
INSERT INTO ezsearch_word VALUES (1169,'completely',1);
INSERT INTO ezsearch_word VALUES (1170,'later',1);
INSERT INTO ezsearch_word VALUES (1171,'seams',1);
INSERT INTO ezsearch_word VALUES (1172,'bright',1);
INSERT INTO ezsearch_word VALUES (1173,'present',1);
INSERT INTO ezsearch_word VALUES (1174,'users',1);
INSERT INTO ezsearch_word VALUES (1175,'action',1);
INSERT INTO ezsearch_word VALUES (1176,'travels',1);
INSERT INTO ezsearch_word VALUES (1177,'travel',5);
INSERT INTO ezsearch_word VALUES (1178,'actiongroup',1);
INSERT INTO ezsearch_word VALUES (1179,'middle',1);
INSERT INTO ezsearch_word VALUES (1180,'evaluating',1);
INSERT INTO ezsearch_word VALUES (1181,'possible',1);
INSERT INTO ezsearch_word VALUES (1182,'markets',1);
INSERT INTO ezsearch_word VALUES (1183,'during',2);
INSERT INTO ezsearch_word VALUES (1184,'last',3);
INSERT INTO ezsearch_word VALUES (1185,'months',2);
INSERT INTO ezsearch_word VALUES (1186,'focused',1);
INSERT INTO ezsearch_word VALUES (1187,'part',1);
INSERT INTO ezsearch_word VALUES (1188,'beautiful',1);
INSERT INTO ezsearch_word VALUES (1189,'visited',1);
INSERT INTO ezsearch_word VALUES (1190,'corners',1);
INSERT INTO ezsearch_word VALUES (1191,'come',2);
INSERT INTO ezsearch_word VALUES (1192,'winner',1);
INSERT INTO ezsearch_word VALUES (1193,'analysis',1);
INSERT INTO ezsearch_word VALUES (1194,'map',1);
INSERT INTO ezsearch_word VALUES (1195,'identity',1);
INSERT INTO ezsearch_word VALUES (1196,'different',2);
INSERT INTO ezsearch_word VALUES (1197,'places',1);
INSERT INTO ezsearch_word VALUES (1198,'show',4);
INSERT INTO ezsearch_word VALUES (1199,'selected',1);
INSERT INTO ezsearch_word VALUES (1200,'parts',1);
INSERT INTO ezsearch_word VALUES (1201,'offer',1);
INSERT INTO ezsearch_word VALUES (1202,'trip',2);
INSERT INTO ezsearch_word VALUES (1203,'mountains',2);
INSERT INTO ezsearch_word VALUES (1204,'lake',1);
INSERT INTO ezsearch_word VALUES (1205,'perhaps',1);
INSERT INTO ezsearch_word VALUES (1206,'dark',1);
INSERT INTO ezsearch_word VALUES (1207,'reports',1);
INSERT INTO ezsearch_word VALUES (1208,'tourism',1);
INSERT INTO ezsearch_word VALUES (1209,'council',1);
INSERT INTO ezsearch_word VALUES (1210,'members',2);
INSERT INTO ezsearch_word VALUES (1211,'big',1);
INSERT INTO ezsearch_word VALUES (1212,'crew',5);
INSERT INTO ezsearch_word VALUES (1213,'wants',3);
INSERT INTO ezsearch_word VALUES (1214,'brasil',1);
INSERT INTO ezsearch_word VALUES (1215,'mexico',1);
INSERT INTO ezsearch_word VALUES (1216,'meanwhile',1);
INSERT INTO ezsearch_word VALUES (1217,'depth',1);
INSERT INTO ezsearch_word VALUES (1218,'interviews',1);
INSERT INTO ezsearch_word VALUES (1219,'showed',1);
INSERT INTO ezsearch_word VALUES (1220,'wanted',1);
INSERT INTO ezsearch_word VALUES (1221,'stay',3);
INSERT INTO ezsearch_word VALUES (1222,'office',2);
INSERT INTO ezsearch_word VALUES (1223,'member',1);
INSERT INTO ezsearch_word VALUES (1224,'anonymous',1);
INSERT INTO ezsearch_word VALUES (1225,'own',1);
INSERT INTO ezsearch_word VALUES (1226,'safety',1);
INSERT INTO ezsearch_word VALUES (1227,'claims',1);
INSERT INTO ezsearch_word VALUES (1228,'afraid',1);
INSERT INTO ezsearch_word VALUES (1229,'leaving',1);
INSERT INTO ezsearch_word VALUES (1230,'everyday',1);
INSERT INTO ezsearch_word VALUES (1231,'bunch',1);
INSERT INTO ezsearch_word VALUES (1232,'fans',2);
INSERT INTO ezsearch_word VALUES (1233,'wait',1);
INSERT INTO ezsearch_word VALUES (1234,'offices',1);
INSERT INTO ezsearch_word VALUES (1235,'like',2);
INSERT INTO ezsearch_word VALUES (1236,'mob',1);
INSERT INTO ezsearch_word VALUES (1237,'says',1);
INSERT INTO ezsearch_word VALUES (1238,'hansom',1);
INSERT INTO ezsearch_word VALUES (1239,'guess',1);
INSERT INTO ezsearch_word VALUES (1240,'why',1);
INSERT INTO ezsearch_word VALUES (1241,'rest',1);
INSERT INTO ezsearch_word VALUES (1242,'og',1);
INSERT INTO ezsearch_word VALUES (1243,'plain',1);
INSERT INTO ezsearch_word VALUES (1244,'jealous',1);
INSERT INTO ezsearch_word VALUES (1245,'hand',1);
INSERT INTO ezsearch_word VALUES (1246,'believes',1);
INSERT INTO ezsearch_word VALUES (1247,'nice',1);
INSERT INTO ezsearch_word VALUES (1248,'there',1);
INSERT INTO ezsearch_word VALUES (1249,'investigations',1);
INSERT INTO ezsearch_word VALUES (1250,'ready',1);
INSERT INTO ezsearch_word VALUES (1251,'spend',2);
INSERT INTO ezsearch_word VALUES (1252,'lot',3);
INSERT INTO ezsearch_word VALUES (1253,'money',1);
INSERT INTO ezsearch_word VALUES (1254,'if',1);
INSERT INTO ezsearch_word VALUES (1255,'destination',1);
INSERT INTO ezsearch_word VALUES (1256,'enough',1);
INSERT INTO ezsearch_word VALUES (1257,'language',1);
INSERT INTO ezsearch_word VALUES (1258,'problem',1);
INSERT INTO ezsearch_word VALUES (1259,'barriers',1);
INSERT INTO ezsearch_word VALUES (1260,'job',1);
INSERT INTO ezsearch_word VALUES (1261,'speak',1);
INSERT INTO ezsearch_word VALUES (1262,'15',1);
INSERT INTO ezsearch_word VALUES (1263,'languages',1);
INSERT INTO ezsearch_word VALUES (1264,'fluently',1);
INSERT INTO ezsearch_word VALUES (1265,'leaked',1);
INSERT INTO ezsearch_word VALUES (1266,'might',1);
INSERT INTO ezsearch_word VALUES (1267,'sweden',1);
INSERT INTO ezsearch_word VALUES (1268,'india',2);
INSERT INTO ezsearch_word VALUES (1269,'able',1);
INSERT INTO ezsearch_word VALUES (1270,'yet',2);
INSERT INTO ezsearch_word VALUES (1271,'done',2);
INSERT INTO ezsearch_word VALUES (1272,'before',2);
INSERT INTO ezsearch_word VALUES (1273,'decision',1);
INSERT INTO ezsearch_word VALUES (1274,'speculate',1);
INSERT INTO ezsearch_word VALUES (1275,'conclusions',1);
INSERT INTO ezsearch_word VALUES (1276,'sure',1);
INSERT INTO ezsearch_word VALUES (1277,'leaves',1);
INSERT INTO ezsearch_word VALUES (1278,'leisure',1);
INSERT INTO ezsearch_word VALUES (1279,'food',1);
INSERT INTO ezsearch_word VALUES (1280,'soul',1);
INSERT INTO ezsearch_word VALUES (1281,'soulfood',2);
INSERT INTO ezsearch_word VALUES (1282,'result',1);
INSERT INTO ezsearch_word VALUES (1283,'passionate',1);
INSERT INTO ezsearch_word VALUES (1284,'photography',1);
INSERT INTO ezsearch_word VALUES (1285,'site',3);
INSERT INTO ezsearch_word VALUES (1286,'runs',1);
INSERT INTO ezsearch_word VALUES (1287,'good',1);
INSERT INTO ezsearch_word VALUES (1288,'example',1);
INSERT INTO ezsearch_word VALUES (1289,'design',2);
INSERT INTO ezsearch_word VALUES (1290,'powered',1);
INSERT INTO ezsearch_word VALUES (1291,'christian',1);
INSERT INTO ezsearch_word VALUES (1292,'houge',2);
INSERT INTO ezsearch_word VALUES (1293,'1972',1);
INSERT INTO ezsearch_word VALUES (1294,'freelance',1);
INSERT INTO ezsearch_word VALUES (1295,'photographer',1);
INSERT INTO ezsearch_word VALUES (1296,'educated',1);
INSERT INTO ezsearch_word VALUES (1297,'usa',1);
INSERT INTO ezsearch_word VALUES (1298,'worked',1);
INSERT INTO ezsearch_word VALUES (1299,'advertising',1);
INSERT INTO ezsearch_word VALUES (1300,'portraits',1);
INSERT INTO ezsearch_word VALUES (1301,'travelling',1);
INSERT INTO ezsearch_word VALUES (1302,'1994',1);
INSERT INTO ezsearch_word VALUES (1303,'1999',1);
INSERT INTO ezsearch_word VALUES (1304,'six',1);
INSERT INTO ezsearch_word VALUES (1305,'exile',1);
INSERT INTO ezsearch_word VALUES (1306,'tibetanians',1);
INSERT INTO ezsearch_word VALUES (1307,'south',1);
INSERT INTO ezsearch_word VALUES (1308,'north',2);
INSERT INTO ezsearch_word VALUES (1309,'nepal',1);
INSERT INTO ezsearch_word VALUES (1310,'pictures',1);
INSERT INTO ezsearch_word VALUES (1311,'influenced',1);
INSERT INTO ezsearch_word VALUES (1312,'visit',2);
INSERT INTO ezsearch_word VALUES (1313,'long',1);
INSERT INTO ezsearch_word VALUES (1314,'periods',1);
INSERT INTO ezsearch_word VALUES (1315,'lived',1);
INSERT INTO ezsearch_word VALUES (1316,'tibetanian',1);
INSERT INTO ezsearch_word VALUES (1317,'monastery',1);
INSERT INTO ezsearch_word VALUES (1318,'western',1);
INSERT INTO ezsearch_word VALUES (1319,'representative',1);
INSERT INTO ezsearch_word VALUES (1320,'got',1);
INSERT INTO ezsearch_word VALUES (1321,'insight',1);
INSERT INTO ezsearch_word VALUES (1322,'daily',1);
INSERT INTO ezsearch_word VALUES (1323,'munks',1);
INSERT INTO ezsearch_word VALUES (1324,'made',2);
INSERT INTO ezsearch_word VALUES (1325,'sigurd',1);
INSERT INTO ezsearch_word VALUES (1326,'kristiansen',1);
INSERT INTO ezsearch_word VALUES (1327,'superstar',1);
INSERT INTO ezsearch_word VALUES (1328,'set',1);
INSERT INTO ezsearch_word VALUES (1329,'official',1);
INSERT INTO ezsearch_word VALUES (1330,'partners',1);
INSERT INTO ezsearch_word VALUES (1331,'petraflux',1);
INSERT INTO ezsearch_word VALUES (1332,'com',1);
INSERT INTO ezsearch_word VALUES (1333,'assisted',1);
INSERT INTO ezsearch_word VALUES (1334,'partner',1);
INSERT INTO ezsearch_word VALUES (1335,'support',1);
INSERT INTO ezsearch_word VALUES (1336,'automatical',1);
INSERT INTO ezsearch_word VALUES (1337,'image',1);
INSERT INTO ezsearch_word VALUES (1338,'import',1);
INSERT INTO ezsearch_word VALUES (1339,'created',1);
INSERT INTO ezsearch_word VALUES (1340,'amongst',1);
INSERT INTO ezsearch_word VALUES (1341,'things',1);
INSERT INTO ezsearch_word VALUES (1581,'slammed',1);
INSERT INTO ezsearch_word VALUES (1580,'follow',1);
INSERT INTO ezsearch_word VALUES (1579,'quick',1);
INSERT INTO ezsearch_word VALUES (1578,'fortunately',1);
INSERT INTO ezsearch_word VALUES (1577,'save',1);
INSERT INTO ezsearch_word VALUES (1576,'fine',1);
INSERT INTO ezsearch_word VALUES (1575,'scoresheet',1);
INSERT INTO ezsearch_word VALUES (1574,'pass',1);
INSERT INTO ezsearch_word VALUES (1573,'splitting',2);
INSERT INTO ezsearch_word VALUES (1572,'simply',1);
INSERT INTO ezsearch_word VALUES (1571,'attack',1);
INSERT INTO ezsearch_word VALUES (1570,'sparks',1);
INSERT INTO ezsearch_word VALUES (1569,'ignited',1);
INSERT INTO ezsearch_word VALUES (1568,'murphy',1);
INSERT INTO ezsearch_word VALUES (1567,'tired',1);
INSERT INTO ezsearch_word VALUES (1566,'replace',1);
INSERT INTO ezsearch_word VALUES (1565,'came',1);
INSERT INTO ezsearch_word VALUES (1564,'dippel',3);
INSERT INTO ezsearch_word VALUES (1563,'problems',1);
INSERT INTO ezsearch_word VALUES (1562,'caused',1);
INSERT INTO ezsearch_word VALUES (1561,'run',1);
INSERT INTO ezsearch_word VALUES (1560,'surging',1);
INSERT INTO ezsearch_word VALUES (1559,'score',1);
INSERT INTO ezsearch_word VALUES (1558,'unfortunate',2);
INSERT INTO ezsearch_word VALUES (1557,'crossbar',1);
INSERT INTO ezsearch_word VALUES (1556,'cudic',2);
INSERT INTO ezsearch_word VALUES (1555,'shot',1);
INSERT INTO ezsearch_word VALUES (1554,'bounding',1);
INSERT INTO ezsearch_word VALUES (1553,'yard',1);
INSERT INTO ezsearch_word VALUES (1552,'30',1);
INSERT INTO ezsearch_word VALUES (1551,'52nd',1);
INSERT INTO ezsearch_word VALUES (1550,'spectacular',1);
INSERT INTO ezsearch_word VALUES (1549,'should',1);
INSERT INTO ezsearch_word VALUES (1548,'girro',1);
INSERT INTO ezsearch_word VALUES (1547,'introduced',1);
INSERT INTO ezsearch_word VALUES (1546,'barton',2);
INSERT INTO ezsearch_word VALUES (1545,'especially',1);
INSERT INTO ezsearch_word VALUES (1544,'win',1);
INSERT INTO ezsearch_word VALUES (1543,'tunnel',1);
INSERT INTO ezsearch_word VALUES (1542,'ran',1);
INSERT INTO ezsearch_word VALUES (1541,'intensity',1);
INSERT INTO ezsearch_word VALUES (1540,'fire',1);
INSERT INTO ezsearch_word VALUES (1539,'increase',1);
INSERT INTO ezsearch_word VALUES (1538,'players',1);
INSERT INTO ezsearch_word VALUES (1537,'belly',1);
INSERT INTO ezsearch_word VALUES (1536,'fuel',1);
INSERT INTO ezsearch_word VALUES (1535,'injected',1);
INSERT INTO ezsearch_word VALUES (1534,'staff',1);
INSERT INTO ezsearch_word VALUES (1533,'kjell',3);
INSERT INTO ezsearch_word VALUES (1532,'sides',1);
INSERT INTO ezsearch_word VALUES (1531,'both',1);
INSERT INTO ezsearch_word VALUES (1530,'exchanges',1);
INSERT INTO ezsearch_word VALUES (1529,'couple',1);
INSERT INTO ezsearch_word VALUES (1528,'contested',1);
INSERT INTO ezsearch_word VALUES (1527,'equally',1);
INSERT INTO ezsearch_word VALUES (1526,'seemed',1);
INSERT INTO ezsearch_word VALUES (1525,'climb',1);
INSERT INTO ezsearch_word VALUES (1524,'hester',2);
INSERT INTO ezsearch_word VALUES (1523,'dopper',2);
INSERT INTO ezsearch_word VALUES (1522,'china',1);
INSERT INTO ezsearch_word VALUES (1521,'wall',1);
INSERT INTO ezsearch_word VALUES (1520,'dill',1);
INSERT INTO ezsearch_word VALUES (1519,'desy',1);
INSERT INTO ezsearch_word VALUES (1518,'marshalled',1);
INSERT INTO ezsearch_word VALUES (1517,'defence',4);
INSERT INTO ezsearch_word VALUES (1516,'stubborn',2);
INSERT INTO ezsearch_word VALUES (1515,'rather',1);
INSERT INTO ezsearch_word VALUES (1514,'cup',1);
INSERT INTO ezsearch_word VALUES (1513,'exit',1);
INSERT INTO ezsearch_word VALUES (1512,'early',1);
INSERT INTO ezsearch_word VALUES (1511,'causing',1);
INSERT INTO ezsearch_word VALUES (1510,'sea',1);
INSERT INTO ezsearch_word VALUES (1509,'ride',1);
INSERT INTO ezsearch_word VALUES (1508,'viking',1);
INSERT INTO ezsearch_word VALUES (1507,'rough',1);
INSERT INTO ezsearch_word VALUES (1506,'hangover',1);
INSERT INTO ezsearch_word VALUES (1505,'recover',1);
INSERT INTO ezsearch_word VALUES (1504,'footballer',1);
INSERT INTO ezsearch_word VALUES (1503,'faces',1);
INSERT INTO ezsearch_word VALUES (1502,'smiles',1);
INSERT INTO ezsearch_word VALUES (1501,'broad',1);
INSERT INTO ezsearch_word VALUES (1500,'laugh',1);
INSERT INTO ezsearch_word VALUES (1499,'gave',1);
INSERT INTO ezsearch_word VALUES (1498,'far',1);
INSERT INTO ezsearch_word VALUES (1497,'goal',3);
INSERT INTO ezsearch_word VALUES (1496,'league',1);
INSERT INTO ezsearch_word VALUES (1495,'fifth',1);
INSERT INTO ezsearch_word VALUES (1494,'minute',2);
INSERT INTO ezsearch_word VALUES (1493,'90th',1);
INSERT INTO ezsearch_word VALUES (1492,'doppers',1);
INSERT INTO ezsearch_word VALUES (1491,'off',1);
INSERT INTO ezsearch_word VALUES (1490,'paid',1);
INSERT INTO ezsearch_word VALUES (1489,'finally',1);
INSERT INTO ezsearch_word VALUES (1488,'determination',1);
INSERT INTO ezsearch_word VALUES (1487,'desire',2);
INSERT INTO ezsearch_word VALUES (1486,'persistence',1);
INSERT INTO ezsearch_word VALUES (1485,'match',1);
INSERT INTO ezsearch_word VALUES (1484,'toughest',1);
INSERT INTO ezsearch_word VALUES (1483,'considered',1);
INSERT INTO ezsearch_word VALUES (1482,'earlier',1);
INSERT INTO ezsearch_word VALUES (1481,'which',1);
INSERT INTO ezsearch_word VALUES (1480,'outfield',2);
INSERT INTO ezsearch_word VALUES (1479,'kings',2);
INSERT INTO ezsearch_word VALUES (1478,'beat',2);
INSERT INTO ezsearch_word VALUES (1477,'again',2);
INSERT INTO ezsearch_word VALUES (1476,'did',2);
INSERT INTO ezsearch_word VALUES (1582,'ball',1);
INSERT INTO ezsearch_word VALUES (1583,'back',2);
INSERT INTO ezsearch_word VALUES (1584,'net',2);
INSERT INTO ezsearch_word VALUES (1585,'stevenson',1);
INSERT INTO ezsearch_word VALUES (1586,'developed',1);
INSERT INTO ezsearch_word VALUES (1587,'passer',1);
INSERT INTO ezsearch_word VALUES (1588,'provide',1);
INSERT INTO ezsearch_word VALUES (1589,'special',1);
INSERT INTO ezsearch_word VALUES (1590,'strikers',1);
INSERT INTO ezsearch_word VALUES (1591,'unlock',1);
INSERT INTO ezsearch_word VALUES (1592,'defences',1);
INSERT INTO ezsearch_word VALUES (1593,'totally',1);
INSERT INTO ezsearch_word VALUES (1594,'between',1);
INSERT INTO ezsearch_word VALUES (1595,'roney',1);
INSERT INTO ezsearch_word VALUES (1596,'doppel',1);
INSERT INTO ezsearch_word VALUES (1597,'hit',1);
INSERT INTO ezsearch_word VALUES (1598,'hope',1);
INSERT INTO ezsearch_word VALUES (1599,'injury',1);
INSERT INTO ezsearch_word VALUES (1600,'free',1);
INSERT INTO ezsearch_word VALUES (1601,'games',1);
INSERT INTO ezsearch_word VALUES (1602,'break',1);
INSERT INTO ezsearch_word VALUES (1603,'week',1);
INSERT INTO ezsearch_word VALUES (1604,'end',1);
INSERT INTO ezsearch_word VALUES (1605,'highend',1);
INSERT INTO ezsearch_word VALUES (1606,'road',1);
INSERT INTO ezsearch_word VALUES (1607,'saturday',1);
INSERT INTO ezsearch_word VALUES (1608,'reds',1);

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
INSERT INTO ezsection VALUES (2,'White box','');
INSERT INTO ezsection VALUES (3,'News section','nor-NO');
INSERT INTO ezsection VALUES (4,'Crossroards forum','');
INSERT INTO ezsection VALUES (5,'The book corner','');

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

INSERT INTO ezsession VALUES ('beea3def7581c9fac9a3959546d91691',1034587368,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:16:\"/workflow/edit/1\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"edit\";}}BrowseFromPage|s:18:\"/section/assign/3/\";BrowseActionName|s:13:\"AssignSection\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('c427b1ec51dfaf03fb7675aea7769c85',1034594229,'eZExecutionStack|a:0:{}BrowseFromPage|s:18:\"/section/assign/2/\";BrowseActionName|s:13:\"AssignSection\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('e66ed7e483d28d41737353c598287aa5',1034522642,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('99d098b736fb61db5c333e7d1a56b110',1034238746,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('94d1f9d4cd565343b21ff10ce34de43d',1034239641,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('8e2554ea6bfe168fea529dc7f07135c8',1034446448,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('b918bfe045dec5d0907760bf0db2b534',1034521240,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";BrowseFromPage|s:18:\"/content/edit/68/1\";BrowseActionName|s:17:\"AddNodeAssignment\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('0ccdc70a53550781158f228da99791f4',1034447434,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('36f9ce37384034da344b7e72ea15683c',1034344414,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('e0c0ef8c2c7260390d5c275419614e5f',1034333040,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('c1e4057be6d6d87912d522aac0f7ee5e',1034331650,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('48f368c974b611e6c02d511e248ce3fa',1034345997,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('87d35623ea70a5dd6dee5cafd4a2ef02',1034337177,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('cbbcf2c0ad7e8b3278b0591f1b626346',1034343736,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('ae347ef56223eb6a9e25234b029300f3',1034427990,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('8eecfba7db10e336fc74c16628a11f87',1034417202,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('358f425b469b1d2e82dd20b426280302',1034426253,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('c3e7f52ac5da96d0fef286f7e8c185b1',1034429040,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('2746fba4e3afcb929a557dc529006c14',1034434068,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('e0bc670f80404de376ad97027c205099',1034592532,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('f735df229eacdc0b998104ebb298f1b5',1034593973,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";BrowseFromPage|s:18:\"/section/assign/4/\";BrowseActionName|s:13:\"AssignSection\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('5115b8e4a3a02aae9251c1fefb42203c',1034491213,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('d7ee037544659a173afa44758a7b8821',1034450534,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('c456a62418c896b01a19891c291da709',1034499935,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('6cc60c4cc0cbd2d3881dfbd8eeda2926',1034500521,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('05521fac5ddd35dfb6d0a97ffbef4afe',1034504896,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('d32dfd9e73424f3eaea57e42b0e4dd3d',1034507595,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('5f0733745e3631400146ad76b7a1fae8',1034507690,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('6ff5aab7013a7cef5acdb6628e9bf625',1034507741,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('43d2fbf94cda78496db021fc5bde0671',1034524700,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('cdd7e91fb890f84533998dec0eb6569d',1034523708,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('cdb8566a979b98a649140e298541c6fd',1034525939,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('592c294778368d74296d4361d91eae0f',1034528766,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('d3802b39b6395a29bbf6ce103955a896',1034581976,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('513f511897b3a7bf58533f8a8d0996fd',1036165958,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"3\";}eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('9f69cb65ba8a7394527b9fe4bc9d0d6e',1036142663,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"4\";}eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('88f41e27ed36a4c60b4f711480eadffb',1036166245,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"2\";}eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('b8fe62ad6be08482cd8381ee02d1a698',1036163567,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"2\";}eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('13a1fbce0b1bba491c2ac80fe3a8395b',1036166520,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('b8003761a93c3d8874e703187fd9f3b0',1036162506,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"2\";}eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('e49707a2980e212dfd7d449b836f34eb',1036166077,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"3\";}eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"14\";');
INSERT INTO ezsession VALUES ('090269585ff6a391178434dfeec72302',1036153733,'eZGlobalSection|a:1:{s:2:\"id\";s:1:\"2\";}eZExecutionStack|a:0:{}eZUserLoggedInID|N;');

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
# Table structure for table 'ezurl'
#

CREATE TABLE ezurl (
  id int(11) NOT NULL auto_increment,
  url varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezurl'
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

INSERT INTO ezuser_setting VALUES (10,1,NULL);
INSERT INTO ezuser_setting VALUES (14,1,NULL);

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

INSERT INTO ezwishlist VALUES (1,14,3);

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
INSERT INTO ezworkflow VALUES (1,1,1,'group_ezserial','Sp\'s forkflow',8,14,1031927869,1034172372);

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
INSERT INTO ezworkflow_event VALUES (18,1,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO ezworkflow_event VALUES (20,1,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);

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


