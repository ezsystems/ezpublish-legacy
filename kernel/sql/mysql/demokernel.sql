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
INSERT INTO ezcontentclass VALUES (5,0,'Image','','<name>',8,14,1031484992,1033921948);
INSERT INTO ezcontentclass VALUES (6,0,'Forum','','<name>',14,14,1034181899,1034182029);
INSERT INTO ezcontentclass VALUES (21,0,'New Class','','<title>',14,14,1034191051,1034191079);
INSERT INTO ezcontentclass VALUES (8,0,'Forum message','','<topic>',14,14,1034185241,1034185314);
INSERT INTO ezcontentclass VALUES (22,0,'Book','book','<title>',14,14,1034251361,1034258912);
INSERT INTO ezcontentclass VALUES (23,0,'Book Review','book_review','<title>',14,14,1034258928,1034259321);

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
INSERT INTO ezcontentclass_attribute VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (126,0,6,'description','Description','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (125,0,6,'icon','Icon','ezimage',1,0,2,1,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (117,0,5,'caption','Caption','ezxmltext',0,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',0,0,1,150,0,0,0,0,0,0,0,'','','','');
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
INSERT INTO ezcontentobject VALUES (15,14,0,16,2,1,'White box',1,0,1,1033922626,1033922626);
INSERT INTO ezcontentobject VALUES (10,8,0,11,0,4,'Anonymous User',1,0,1,1033920665,1033920665);
INSERT INTO ezcontentobject VALUES (11,8,0,12,0,3,'Guest accounts',1,0,1,1033920746,1033920746);
INSERT INTO ezcontentobject VALUES (12,8,0,13,0,3,'Administrator users',1,0,1,1033920775,1033920775);
INSERT INTO ezcontentobject VALUES (13,8,0,14,0,3,'Editors',1,0,1,1033920794,1033920794);
INSERT INTO ezcontentobject VALUES (14,8,0,15,0,4,'Administrator User',1,0,1,1033920830,1033920830);
INSERT INTO ezcontentobject VALUES (75,14,0,74,2,5,'Camel',1,0,1,1034327200,1034327200);
INSERT INTO ezcontentobject VALUES (17,14,0,18,2,1,'Gallery 2',2,0,1,1034327257,1034327257);
INSERT INTO ezcontentobject VALUES (77,14,0,76,2,5,'India',1,0,1,1034327284,1034327284);
INSERT INTO ezcontentobject VALUES (76,14,0,75,2,5,'Cow',1,0,1,1034327230,1034327230);
INSERT INTO ezcontentobject VALUES (23,14,0,24,3,1,'News',1,0,1,1034174464,1034174464);
INSERT INTO ezcontentobject VALUES (72,14,0,71,3,2,'Typhoon is near',9,0,1,1034264438,1034264438);
INSERT INTO ezcontentobject VALUES (25,14,0,26,3,1,'Frontpage',3,0,1,1034334677,1034334677);
INSERT INTO ezcontentobject VALUES (26,14,0,27,3,1,'Sport',3,0,1,1034334718,1034334718);
INSERT INTO ezcontentobject VALUES (79,14,0,78,4,8,'I do agree !',1,0,1,1034327610,1034327610);
INSERT INTO ezcontentobject VALUES (29,14,0,30,3,1,'World news',3,0,1,1034334767,1034334767);
INSERT INTO ezcontentobject VALUES (30,14,0,31,4,1,'Crossroads forum',1,0,1,1034181792,1034181792);
INSERT INTO ezcontentobject VALUES (31,14,0,32,4,1,'Forums',1,0,1,1034181825,1034181825);
INSERT INTO ezcontentobject VALUES (78,14,0,77,4,8,'First post!',1,0,1,1034327582,1034327582);
INSERT INTO ezcontentobject VALUES (33,14,0,34,4,6,'Forum 2',1,0,1,1034183870,1034183870);
INSERT INTO ezcontentobject VALUES (34,14,0,35,4,6,'Forum 3',1,0,1,1034183902,1034183902);
INSERT INTO ezcontentobject VALUES (35,14,0,36,4,6,'Forum 4',1,0,1,1034183935,1034183935);
INSERT INTO ezcontentobject VALUES (39,10,0,0,4,8,'New Forum message',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (43,10,0,43,4,8,'First reply',1,0,1,1034186575,1034186575);
INSERT INTO ezcontentobject VALUES (45,10,0,45,4,8,'I agree !',1,0,1,1034186992,1034186992);
INSERT INTO ezcontentobject VALUES (46,10,0,46,4,8,'This forum is bad!!!!!!!!!!',1,0,1,1034187189,1034187189);
INSERT INTO ezcontentobject VALUES (47,10,0,48,4,8,'This is my reply',1,0,1,1034187441,1034187441);
INSERT INTO ezcontentobject VALUES (82,14,0,81,3,2,'Just another article',1,0,1,1034335023,1034335023);
INSERT INTO ezcontentobject VALUES (57,14,0,55,1,20,'',1,0,1,1034190865,1034190865);
INSERT INTO ezcontentobject VALUES (81,14,0,80,3,2,'Find bugs',1,0,1,1034334960,1034334960);
INSERT INTO ezcontentobject VALUES (62,14,0,60,5,1,'The Book Corner',1,0,1,1034251246,1034251246);
INSERT INTO ezcontentobject VALUES (63,14,0,61,5,1,'Top 20 Books',1,0,1,1034252134,1034252134);
INSERT INTO ezcontentobject VALUES (64,14,0,62,5,1,'Bestsellers',1,0,1,1034252256,1034252256);
INSERT INTO ezcontentobject VALUES (65,14,0,63,5,1,'Recommendations',1,0,1,1034252479,1034252479);
INSERT INTO ezcontentobject VALUES (66,14,0,64,5,1,'Authors',1,0,1,1034252585,1034252585);
INSERT INTO ezcontentobject VALUES (67,14,0,65,5,1,'Books',1,0,1,1034253542,1034253542);
INSERT INTO ezcontentobject VALUES (68,14,0,66,5,22,'Wandering Cow',1,0,1,1034254610,1034254610);
INSERT INTO ezcontentobject VALUES (69,14,0,68,5,22,'The Drumseller',1,0,1,1034254673,1034254673);
INSERT INTO ezcontentobject VALUES (70,14,0,69,5,23,'Fantastic',1,0,1,1034259506,1034259506);
INSERT INTO ezcontentobject VALUES (80,14,0,79,3,2,'eZ publish 3 test',1,0,1,1034334878,1034334878);
INSERT INTO ezcontentobject VALUES (74,14,0,73,4,8,'Forum 3',1,0,1,1034270062,1034270062);

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
INSERT INTO ezcontentobject_attribute VALUES (181,'eng-GB',1,76,116,'Cow',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',1,17,4,'Gallery 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',1,17,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (182,'eng-GB',1,76,117,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Cow</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (183,'eng-GB',1,76,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (35,'eng-GB',2,17,4,'Gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (36,'eng-GB',2,17,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (184,'eng-GB',1,77,116,'India',0,0);
INSERT INTO ezcontentobject_attribute VALUES (185,'eng-GB',1,77,117,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>India</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (186,'eng-GB',1,77,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (180,'eng-GB',1,75,118,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (178,'eng-GB',1,75,116,'Camel',0,0);
INSERT INTO ezcontentobject_attribute VALUES (179,'eng-GB',1,75,117,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Camel in India.</paragraph>\n</section>',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (189,'eng-GB',1,79,128,'I do agree !',0,0);
INSERT INTO ezcontentobject_attribute VALUES (190,'eng-GB',1,79,129,'This is the first reply.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'eng-GB',1,29,4,'world',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'eng-GB',1,29,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>around the word</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'eng-GB',1,30,4,'Crossroads forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'eng-GB',1,30,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'eng-GB',1,31,4,'Forums',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'eng-GB',1,31,119,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (187,'eng-GB',1,78,128,'First post!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (188,'eng-GB',1,78,129,'This is the first post with 3.0',0,0);
INSERT INTO ezcontentobject_attribute VALUES (76,'eng-GB',1,33,124,'Forum 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (77,'eng-GB',1,33,125,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (78,'eng-GB',1,33,126,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>orumus, addum hoctus inatem iden hili te cota inver li spectus rei inatum Rompror test? Quonsis, que ad Catum controx milintemquem maximus ca resse, tem dest? quem huium amdit pes sul hostres nostrem Romaximandiusquis horachus cupim etoreni ivera, sena, omaiorit; horidientem fin deatiss licio, consul ut L. Viveris uerit, ut rei ferem orteatus; hoc revidem optiam. Veriven inteluterum,</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (79,'eng-GB',1,34,124,'Forum 3',0,0);
INSERT INTO ezcontentobject_attribute VALUES (80,'eng-GB',1,34,125,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (81,'eng-GB',1,34,126,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Itabemunum din pulin defat es aderis maio, seni conc re quam sim pos simodius consupp iquast autes horae ignatiam, utelium licaedes o iam te publiss gnatia vivitem ortum consus auc is la rei terem rehem, o ates publiis, nostris eliculinam tum, publintes consuli epote factum</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (82,'eng-GB',1,35,124,'Forum 4',0,0);
INSERT INTO ezcontentobject_attribute VALUES (83,'eng-GB',1,35,125,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (84,'eng-GB',1,35,126,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Itabemunum din pulin defat es aderis maio, seni conc re quam sim pos simodius consupp iquast autes horae ignatiam, utelium licaedes o iam te publiss gnatia vivitem ortum consus auc is la rei terem rehem, o ates publiis, nostris eliculinam tum, publintes consuli epote factum</paragraph>\n</section>',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (142,'eng-GB',1,68,142,'Wandering Cow',0,0);
INSERT INTO ezcontentobject_attribute VALUES (143,'eng-GB',1,68,143,'C. Catro',0,0);
INSERT INTO ezcontentobject_attribute VALUES (144,'eng-GB',1,68,144,'Cappelen 2000',0,0);
INSERT INTO ezcontentobject_attribute VALUES (145,'eng-GB',1,68,145,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Habefac tam. Tum am aucii te et auctus. Vivehebemorum hocura? Name te, forbis. Habi senatum ut L. Verfinemus opublicia? Quam poerfer icati, C. C. Catro, quit, fue tela maio esi intem re di, nestiu cupim patruriam potiem se factuasdam aus auctum la puli publia nos stretil erra, et, C. Graris hos hosuam P. Si ponfecrei se, Casdactesine mac tam. Catili prae mantis iam que interra?</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (146,'eng-GB',1,68,146,'usually dispatched within 24 hours.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (147,'eng-GB',1,68,147,'',0,8.09);
INSERT INTO ezcontentobject_attribute VALUES (148,'eng-GB',1,68,148,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (149,'eng-GB',1,69,142,'The Drumseller',0,0);
INSERT INTO ezcontentobject_attribute VALUES (150,'eng-GB',1,69,143,'C. Graris',0,0);
INSERT INTO ezcontentobject_attribute VALUES (151,'eng-GB',1,69,144,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (152,'eng-GB',1,69,145,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Habefac tam. Tum am aucii te et auctus. Vivehebemorum hocura? Name te, forbis. Habi senatum ut L. Verfinemus opublicia? Quam poerfer icati, quit, fue tela maio esi intem re di, nestiu cupim patruriam potiem se factuasdam aus auctum la puli publia nos stretil erra, et, C. Graris hos hosuam P. Si ponfecrei se, Casdactesine mac tam. Catili prae mantis iam que interra?</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (153,'eng-GB',1,69,146,'10',0,0);
INSERT INTO ezcontentobject_attribute VALUES (154,'eng-GB',1,69,147,'',0,6.5);
INSERT INTO ezcontentobject_attribute VALUES (155,'eng-GB',1,69,148,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (156,'eng-GB',1,70,149,'Fantastic',0,0);
INSERT INTO ezcontentobject_attribute VALUES (157,'eng-GB',1,70,150,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (158,'eng-GB',1,70,151,'jezmondinio',0,0);
INSERT INTO ezcontentobject_attribute VALUES (159,'eng-GB',1,70,152,'Moscow, Russia',0,0);
INSERT INTO ezcontentobject_attribute VALUES (160,'eng-GB',1,70,153,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Habi senatum ut L. Verfinemus opublicia? Quam poerfer icati, C. C. Catro, quit, fue tela maio esi intem re di, nestiu cupim patruriam potiem se factuasdam aus auctum la puli publia nos stretil erra, et, C. Graris hos hosuam P. Si ponfecrei se, Casdactesine mac tam. Catili prae mantis iam que interra? Pat, fatique idem erfervi erit, nore culicavenius horbitas fue iam, quidefactus viliam Roma, constil host res publii probses locciemoerum con tus ad consus dum prae, se conum vis ocre confirm hilicae icienteriam idem esil tem hacteri factoret, ut nox nonimus, cotabefacit L. An defecut in Etris; in speri, que acioca L. Maet; Cas nox nulinte renica; nos, constraeque probus reis publibuntia mo Catqui pubissimis apere nor ut puli iaet in Italegero movente issimus niu consulintiu vitin dis. Opicae con intem, vivere porum spiordiem mo mactantenatu es mo Cat. Serenih libus sedo, num inatium diem host C. maiorei senam ora, senit; nonsult retis.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (196,'eng-GB',1,81,1,'Find bugs',0,0);
INSERT INTO ezcontentobject_attribute VALUES (197,'eng-GB',1,81,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>We will now play a game; find 5 bugs in beta1.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (198,'eng-GB',1,81,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Ok, that didn\'t prove so hard you say? We will make it harder next time around ;)</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (199,'eng-GB',1,81,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (200,'eng-GB',1,81,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (201,'eng-GB',1,82,1,'Just another article',0,0);
INSERT INTO ezcontentobject_attribute VALUES (202,'eng-GB',1,82,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>With some information... but not much.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (203,'eng-GB',1,82,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Here you can create some <bold>interesting</bold> information..</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (204,'eng-GB',1,82,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (205,'eng-GB',1,82,123,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (191,'eng-GB',1,80,1,'eZ publish 3 test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (192,'eng-GB',1,80,120,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><header level=\'1\'>This is a test</header><paragraph>Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum.</paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (193,'eng-GB',1,80,121,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph><table>\n<tr>\n   <td>\nTables can also be used..\n  </td>\n  <td>\nTables can also be used..\n  </td>\n  <td>\nTables can also be used..\n  </td>\n</tr>\n</table></paragraph>\n<paragraph><bold>Beta</bold></paragraph>\n</section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (194,'eng-GB',1,80,122,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (195,'eng-GB',1,80,123,'',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (176,'eng-GB',1,74,128,'Forum 3 ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (177,'eng-GB',1,74,129,'test',0,0);

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
INSERT INTO ezcontentobject_tree VALUES (16,2,15,1,1,-571349768,2,'/1/2/16/','',0,0,'frontpage20/');
INSERT INTO ezcontentobject_tree VALUES (11,5,10,1,1,-1609495635,2,'/1/5/11/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (12,5,11,1,1,-1609495635,2,'/1/5/12/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (13,5,12,1,1,-1609495635,2,'/1/5/13/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (14,5,13,1,1,-1609495635,2,'/1/5/14/','',0,0,'users/');
INSERT INTO ezcontentobject_tree VALUES (15,13,14,1,1,934329528,3,'/1/5/13/15/','',0,0,'users/administrator_users/');
INSERT INTO ezcontentobject_tree VALUES (74,18,75,1,1,-1252757927,4,'/1/2/16/18/74/','',0,0,'frontpage20/white_box/gallery_2/');
INSERT INTO ezcontentobject_tree VALUES (18,16,17,2,1,643451801,3,'/1/2/16/18/','',0,0,'frontpage20/white_box/');
INSERT INTO ezcontentobject_tree VALUES (76,18,77,1,1,-1252757927,4,'/1/2/16/18/76/','',0,0,'frontpage20/white_box/gallery_2/');
INSERT INTO ezcontentobject_tree VALUES (75,18,76,1,1,-1252757927,4,'/1/2/16/18/75/','',0,0,'frontpage20/white_box/gallery_2/');
INSERT INTO ezcontentobject_tree VALUES (24,2,23,1,1,-571349768,2,'/1/2/24/','',0,0,'frontpage20/');
INSERT INTO ezcontentobject_tree VALUES (26,24,25,3,1,-1284751361,3,'/1/2/24/26/','',0,0,'frontpage20/news/');
INSERT INTO ezcontentobject_tree VALUES (27,24,26,3,1,-1284751361,3,'/1/2/24/27/','',0,0,'frontpage20/news/');
INSERT INTO ezcontentobject_tree VALUES (78,77,79,1,1,847005437,6,'/1/2/31/32/34/77/78/','',0,0,'frontpage20/crossroads_forum/forums/forum_2/first_post/');
INSERT INTO ezcontentobject_tree VALUES (30,24,29,3,1,-1284751361,3,'/1/2/24/30/','',0,0,'frontpage20/news/');
INSERT INTO ezcontentobject_tree VALUES (31,2,30,1,1,-571349768,2,'/1/2/31/','',0,0,'frontpage20/');
INSERT INTO ezcontentobject_tree VALUES (32,31,31,1,1,-1289518249,3,'/1/2/31/32/','',0,0,'frontpage20/crossroads_forum/');
INSERT INTO ezcontentobject_tree VALUES (77,34,78,1,1,-1014445356,5,'/1/2/31/32/34/77/','',0,0,'frontpage20/crossroads_forum/forums/forum_2/');
INSERT INTO ezcontentobject_tree VALUES (34,32,33,1,1,1265087199,4,'/1/2/31/32/34/','',0,0,'frontpage20/crossroads_forum/forums/');
INSERT INTO ezcontentobject_tree VALUES (35,32,34,1,1,1265087199,4,'/1/2/31/32/35/','',0,0,'frontpage20/crossroads_forum/forums/');
INSERT INTO ezcontentobject_tree VALUES (36,32,35,1,1,1265087199,4,'/1/2/31/32/36/','',0,0,'frontpage20/crossroads_forum/forums/');
INSERT INTO ezcontentobject_tree VALUES (55,2,57,1,1,-571349768,2,'/1/2/55/','',0,0,'frontpage20/');
INSERT INTO ezcontentobject_tree VALUES (81,30,82,1,1,-232232099,4,'/1/2/24/30/81/','',0,0,'frontpage20/news/world_news/');
INSERT INTO ezcontentobject_tree VALUES (80,27,81,1,1,1615423658,4,'/1/2/24/27/80/','',0,0,'frontpage20/news/sport/');
INSERT INTO ezcontentobject_tree VALUES (60,2,62,1,1,-571349768,2,'/1/2/60/','',0,0,'frontpage20/');
INSERT INTO ezcontentobject_tree VALUES (61,60,63,1,1,-1241045258,3,'/1/2/60/61/','',0,0,'frontpage20/the_book_corner/');
INSERT INTO ezcontentobject_tree VALUES (62,60,64,1,1,-1241045258,3,'/1/2/60/62/','',0,0,'frontpage20/the_book_corner/');
INSERT INTO ezcontentobject_tree VALUES (63,60,65,1,1,-1241045258,3,'/1/2/60/63/','',0,0,'frontpage20/the_book_corner/');
INSERT INTO ezcontentobject_tree VALUES (64,60,66,1,1,-1241045258,3,'/1/2/60/64/','',0,0,'frontpage20/the_book_corner/');
INSERT INTO ezcontentobject_tree VALUES (65,60,67,1,1,-1241045258,3,'/1/2/60/65/','',0,0,'frontpage20/the_book_corner/');
INSERT INTO ezcontentobject_tree VALUES (66,65,68,1,1,-1227448019,4,'/1/2/60/65/66/','',0,0,'frontpage20/the_book_corner/books/');
INSERT INTO ezcontentobject_tree VALUES (67,63,68,1,1,2031707565,4,'/1/2/60/63/67/','',0,0,'frontpage20/the_book_corner/recommendations/');
INSERT INTO ezcontentobject_tree VALUES (68,65,69,1,1,-1227448019,4,'/1/2/60/65/68/','',0,0,'frontpage20/the_book_corner/books/');
INSERT INTO ezcontentobject_tree VALUES (69,66,70,1,1,-285054158,5,'/1/2/60/65/66/69/','',0,0,'frontpage20/the_book_corner/books/wandering_cow/');
INSERT INTO ezcontentobject_tree VALUES (79,26,80,1,1,-1588983293,4,'/1/2/24/26/79/','',0,0,'frontpage20/news/frontpage/');
INSERT INTO ezcontentobject_tree VALUES (73,35,74,1,1,-627836011,5,'/1/2/31/32/35/73/','',0,0,'frontpage20/crossroads_forum/forums/forum_3/');

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
INSERT INTO ezcontentobject_version VALUES (520,76,14,1,1034327214,1034327230,0,0,0);
INSERT INTO ezcontentobject_version VALUES (445,17,14,1,1033923938,1033923953,0,0,0);
INSERT INTO ezcontentobject_version VALUES (522,77,14,1,1034327267,1034327284,0,0,0);
INSERT INTO ezcontentobject_version VALUES (521,17,14,2,1034327248,1034327257,0,0,0);
INSERT INTO ezcontentobject_version VALUES (519,75,14,1,1034327153,1034327199,0,0,0);
INSERT INTO ezcontentobject_version VALUES (455,15,14,2,1034085521,1034085521,0,0,0);
INSERT INTO ezcontentobject_version VALUES (456,15,14,3,1034165834,1034165834,0,0,0);
INSERT INTO ezcontentobject_version VALUES (457,23,14,1,1034174426,1034174464,0,0,0);
INSERT INTO ezcontentobject_version VALUES (507,72,14,1,1034260349,1034260496,0,0,0);
INSERT INTO ezcontentobject_version VALUES (459,25,14,1,1034175645,1034175666,0,0,0);
INSERT INTO ezcontentobject_version VALUES (460,26,14,1,1034175689,1034175704,0,0,0);
INSERT INTO ezcontentobject_version VALUES (524,79,14,1,1034327596,1034327610,0,0,0);
INSERT INTO ezcontentobject_version VALUES (463,29,14,1,1034175841,1034175855,0,0,0);
INSERT INTO ezcontentobject_version VALUES (464,30,14,1,1034181778,1034181792,0,0,0);
INSERT INTO ezcontentobject_version VALUES (465,31,14,1,1034181817,1034181825,0,0,0);
INSERT INTO ezcontentobject_version VALUES (523,78,14,1,1034327521,1034327582,0,0,0);
INSERT INTO ezcontentobject_version VALUES (467,33,14,1,1034183846,1034183870,0,0,0);
INSERT INTO ezcontentobject_version VALUES (468,34,14,1,1034183885,1034183902,0,0,0);
INSERT INTO ezcontentobject_version VALUES (469,35,14,1,1034183917,1034183935,0,0,0);
INSERT INTO ezcontentobject_version VALUES (532,81,14,1,1034334912,1034334960,0,0,0);
INSERT INTO ezcontentobject_version VALUES (473,39,10,1,1034185655,1034185655,0,0,0);
INSERT INTO ezcontentobject_version VALUES (533,82,14,1,1034334979,1034335023,0,0,0);
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
INSERT INTO ezcontentobject_version VALUES (502,68,14,1,1034254447,1034254610,0,0,0);
INSERT INTO ezcontentobject_version VALUES (503,69,14,1,1034254623,1034254673,0,0,0);
INSERT INTO ezcontentobject_version VALUES (504,70,14,1,1034259411,1034259505,0,0,0);
INSERT INTO ezcontentobject_version VALUES (526,25,14,3,1034334672,1034334677,0,0,0);
INSERT INTO ezcontentobject_version VALUES (525,25,14,2,1034334639,1034334649,0,0,0);
INSERT INTO ezcontentobject_version VALUES (508,72,14,2,1034261081,1034261100,0,0,0);
INSERT INTO ezcontentobject_version VALUES (531,80,14,1,1034334796,1034334878,0,0,0);
INSERT INTO ezcontentobject_version VALUES (530,29,14,3,1034334761,1034334767,0,0,0);
INSERT INTO ezcontentobject_version VALUES (511,72,14,3,1034263205,1034263215,0,0,0);
INSERT INTO ezcontentobject_version VALUES (512,72,14,4,1034263225,1034263232,0,0,0);
INSERT INTO ezcontentobject_version VALUES (513,72,14,5,1034263570,1034263585,0,0,0);
INSERT INTO ezcontentobject_version VALUES (514,72,14,6,1034264271,1034264283,0,0,0);
INSERT INTO ezcontentobject_version VALUES (515,72,14,7,1034264305,1034264315,0,0,0);
INSERT INTO ezcontentobject_version VALUES (516,72,14,8,1034264329,1034264345,0,0,0);
INSERT INTO ezcontentobject_version VALUES (517,72,14,9,1034264429,1034264438,0,0,0);
INSERT INTO ezcontentobject_version VALUES (518,74,14,1,1034270049,1034270062,0,0,0);

#
# Table structure for table 'ezenumobjectvalue'
#

CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumid int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
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
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
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

INSERT INTO ezimage VALUES (186,1,'BXNvdq.gif','india1.gif','image/gif');
INSERT INTO ezimage VALUES (180,1,'8QQUsb.jpg','camel.jpg','image/jpeg');
INSERT INTO ezimage VALUES (183,1,'yV6NyT.jpg','cow.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,1,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (77,1,'4ZzJ3Q.gif','skisse2.gif','image/gif');
INSERT INTO ezimage VALUES (80,1,'XFnLkz.gif','skisse3.gif','image/gif');
INSERT INTO ezimage VALUES (83,1,'qnZ6Po.gif','skisse4.gif','image/gif');
INSERT INTO ezimage VALUES (204,1,'HGN1IW.jpg','india4.jpg','image/jpeg');
INSERT INTO ezimage VALUES (148,1,'hrM4M5.jpg','cow.jpg','image/jpeg');
INSERT INTO ezimage VALUES (155,1,'H3n35L.gif','india3.gif','image/gif');
INSERT INTO ezimage VALUES (199,1,'WyHi1C.jpg','Winter.jpg','image/jpeg');
INSERT INTO ezimage VALUES (194,1,'DXBRM2.gif','india3.gif','image/gif');
INSERT INTO ezimage VALUES (169,2,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,3,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,4,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,5,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,6,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,7,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,8,'sZd6o7.jpg','typhoon.jpg','image/jpeg');
INSERT INTO ezimage VALUES (169,9,'sZd6o7.jpg','typhoon.jpg','image/jpeg');

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

INSERT INTO ezimagevariation VALUES (180,1,'8QQUsb_600x600_180.jpg','8/Q/Q/',600,600,400,261);
INSERT INTO ezimagevariation VALUES (183,1,'yV6NyT_600x600_183.jpg','y/V/6/',600,600,120,163);
INSERT INTO ezimagevariation VALUES (183,1,'yV6NyT_150x150_183.jpg','y/V/6/',150,150,110,150);
INSERT INTO ezimagevariation VALUES (186,1,'BXNvdq_150x150_186.gif','B/X/N/',150,150,100,143);
INSERT INTO ezimagevariation VALUES (180,1,'8QQUsb_60x60_180.jpg','8/Q/Q/',60,60,60,39);
INSERT INTO ezimagevariation VALUES (183,1,'yV6NyT_60x60_183.jpg','y/V/6/',60,60,44,60);
INSERT INTO ezimagevariation VALUES (186,1,'BXNvdq_60x60_186.gif','B/X/N/',60,60,41,60);
INSERT INTO ezimagevariation VALUES (186,1,'BXNvdq_600x600_186.gif','B/X/N/',600,600,100,143);
INSERT INTO ezimagevariation VALUES (180,1,'8QQUsb_150x150_180.jpg','8/Q/Q/',150,150,150,97);
INSERT INTO ezimagevariation VALUES (169,1,'sZd6o7_600x600_169.jpg','s/Z/d/',600,600,400,273);
INSERT INTO ezimagevariation VALUES (77,1,'4ZzJ3Q_600x600_77.gif','4/Z/z/',600,600,127,137);
INSERT INTO ezimagevariation VALUES (80,1,'XFnLkz_600x600_80.gif','X/F/n/',600,600,127,137);
INSERT INTO ezimagevariation VALUES (83,1,'qnZ6Po_600x600_83.gif','q/n/Z/',600,600,127,137);
INSERT INTO ezimagevariation VALUES (169,1,'sZd6o7_150x150_169.jpg','s/Z/d/',150,150,150,102);
INSERT INTO ezimagevariation VALUES (148,1,'hrM4M5_60x60_148.jpg','h/r/M/',60,60,44,60);
INSERT INTO ezimagevariation VALUES (148,1,'hrM4M5_600x600_148.jpg','h/r/M/',600,600,120,163);
INSERT INTO ezimagevariation VALUES (155,1,'H3n35L_600x600_155.gif','H/3/n/',600,600,100,133);
INSERT INTO ezimagevariation VALUES (155,1,'H3n35L_60x60_155.gif','H/3/n/',60,60,45,60);
INSERT INTO ezimagevariation VALUES (148,1,'hrM4M5_150x150_148.jpg','h/r/M/',150,150,110,150);
INSERT INTO ezimagevariation VALUES (155,1,'H3n35L_150x150_155.gif','H/3/n/',150,150,100,133);
INSERT INTO ezimagevariation VALUES (199,1,'WyHi1C_600x600_199.jpg','W/y/H/',600,600,150,118);
INSERT INTO ezimagevariation VALUES (204,1,'HGN1IW_600x600_204.jpg','H/G/N/',600,600,105,144);
INSERT INTO ezimagevariation VALUES (194,1,'DXBRM2_60x60_194.gif','D/X/B/',60,60,45,60);
INSERT INTO ezimagevariation VALUES (194,1,'DXBRM2_600x600_194.gif','D/X/B/',600,600,100,133);
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
INSERT INTO eznode_assignment VALUES (154,15,1,2,1);
INSERT INTO eznode_assignment VALUES (155,16,1,16,1);
INSERT INTO eznode_assignment VALUES (156,17,1,16,1);
INSERT INTO eznode_assignment VALUES (157,18,1,17,1);
INSERT INTO eznode_assignment VALUES (158,19,1,17,1);
INSERT INTO eznode_assignment VALUES (159,20,1,17,1);
INSERT INTO eznode_assignment VALUES (160,21,1,17,1);
INSERT INTO eznode_assignment VALUES (161,22,1,17,1);
INSERT INTO eznode_assignment VALUES (162,18,2,17,1);
INSERT INTO eznode_assignment VALUES (163,18,3,17,1);
INSERT INTO eznode_assignment VALUES (164,18,4,17,1);
INSERT INTO eznode_assignment VALUES (165,16,2,16,1);
INSERT INTO eznode_assignment VALUES (166,15,2,2,1);
INSERT INTO eznode_assignment VALUES (167,15,3,2,1);
INSERT INTO eznode_assignment VALUES (168,23,1,2,1);
INSERT INTO eznode_assignment VALUES (169,24,1,24,1);
INSERT INTO eznode_assignment VALUES (170,25,1,24,1);
INSERT INTO eznode_assignment VALUES (171,26,1,24,1);
INSERT INTO eznode_assignment VALUES (172,27,1,24,1);
INSERT INTO eznode_assignment VALUES (173,28,1,28,1);
INSERT INTO eznode_assignment VALUES (174,29,1,24,1);
INSERT INTO eznode_assignment VALUES (175,30,1,2,1);
INSERT INTO eznode_assignment VALUES (176,31,1,31,1);
INSERT INTO eznode_assignment VALUES (177,32,1,32,1);
INSERT INTO eznode_assignment VALUES (178,33,1,32,1);
INSERT INTO eznode_assignment VALUES (179,34,1,32,1);
INSERT INTO eznode_assignment VALUES (180,35,1,32,1);
INSERT INTO eznode_assignment VALUES (181,36,1,2,1);
INSERT INTO eznode_assignment VALUES (182,37,1,2,1);
INSERT INTO eznode_assignment VALUES (183,38,1,2,1);
INSERT INTO eznode_assignment VALUES (184,39,1,33,1);
INSERT INTO eznode_assignment VALUES (185,40,1,33,1);
INSERT INTO eznode_assignment VALUES (186,41,1,33,1);
INSERT INTO eznode_assignment VALUES (187,42,1,2,1);
INSERT INTO eznode_assignment VALUES (188,43,1,40,1);
INSERT INTO eznode_assignment VALUES (189,44,1,2,1);
INSERT INTO eznode_assignment VALUES (190,45,1,40,1);
INSERT INTO eznode_assignment VALUES (191,46,1,40,1);
INSERT INTO eznode_assignment VALUES (192,47,1,40,1);
INSERT INTO eznode_assignment VALUES (193,48,1,2,1);
INSERT INTO eznode_assignment VALUES (194,49,1,2,1);
INSERT INTO eznode_assignment VALUES (195,50,1,49,1);
INSERT INTO eznode_assignment VALUES (196,51,1,2,1);
INSERT INTO eznode_assignment VALUES (197,52,1,2,1);
INSERT INTO eznode_assignment VALUES (198,53,1,2,1);
INSERT INTO eznode_assignment VALUES (199,54,1,2,1);
INSERT INTO eznode_assignment VALUES (200,55,1,2,1);
INSERT INTO eznode_assignment VALUES (201,56,1,53,1);
INSERT INTO eznode_assignment VALUES (202,57,1,2,1);
INSERT INTO eznode_assignment VALUES (203,58,1,2,1);
INSERT INTO eznode_assignment VALUES (204,59,1,26,1);
INSERT INTO eznode_assignment VALUES (205,60,1,26,1);
INSERT INTO eznode_assignment VALUES (206,61,1,26,1);
INSERT INTO eznode_assignment VALUES (207,62,1,2,1);
INSERT INTO eznode_assignment VALUES (208,63,1,60,1);
INSERT INTO eznode_assignment VALUES (209,64,1,60,1);
INSERT INTO eznode_assignment VALUES (210,65,1,60,1);
INSERT INTO eznode_assignment VALUES (211,66,1,60,1);
INSERT INTO eznode_assignment VALUES (212,67,1,60,1);
INSERT INTO eznode_assignment VALUES (213,68,1,65,1);
INSERT INTO eznode_assignment VALUES (214,68,1,63,0);
INSERT INTO eznode_assignment VALUES (215,69,1,65,1);
INSERT INTO eznode_assignment VALUES (216,70,1,66,1);
INSERT INTO eznode_assignment VALUES (217,71,1,27,1);
INSERT INTO eznode_assignment VALUES (218,71,2,27,1);
INSERT INTO eznode_assignment VALUES (219,72,1,28,1);
INSERT INTO eznode_assignment VALUES (220,72,2,28,1);
INSERT INTO eznode_assignment VALUES (221,73,1,28,1);
INSERT INTO eznode_assignment VALUES (222,73,2,28,1);
INSERT INTO eznode_assignment VALUES (223,72,3,28,1);
INSERT INTO eznode_assignment VALUES (224,72,4,28,1);
INSERT INTO eznode_assignment VALUES (225,72,5,28,1);
INSERT INTO eznode_assignment VALUES (226,72,6,28,1);
INSERT INTO eznode_assignment VALUES (227,72,7,28,1);
INSERT INTO eznode_assignment VALUES (228,72,8,28,1);
INSERT INTO eznode_assignment VALUES (229,72,9,28,1);
INSERT INTO eznode_assignment VALUES (230,74,1,35,1);
INSERT INTO eznode_assignment VALUES (231,75,1,18,1);
INSERT INTO eznode_assignment VALUES (232,76,1,18,1);
INSERT INTO eznode_assignment VALUES (233,17,2,16,1);
INSERT INTO eznode_assignment VALUES (234,77,1,18,1);
INSERT INTO eznode_assignment VALUES (235,78,1,34,1);
INSERT INTO eznode_assignment VALUES (236,79,1,77,1);
INSERT INTO eznode_assignment VALUES (237,25,2,24,1);
INSERT INTO eznode_assignment VALUES (238,25,3,24,1);
INSERT INTO eznode_assignment VALUES (239,26,2,24,1);
INSERT INTO eznode_assignment VALUES (240,26,3,24,1);
INSERT INTO eznode_assignment VALUES (241,29,2,24,1);
INSERT INTO eznode_assignment VALUES (242,29,3,24,1);
INSERT INTO eznode_assignment VALUES (243,80,1,26,1);
INSERT INTO eznode_assignment VALUES (244,81,1,27,1);
INSERT INTO eznode_assignment VALUES (245,82,1,30,1);

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

INSERT INTO ezsearch_object_word_link VALUES (1,15,1,0,0,0,2,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2,15,2,0,1,1,3,1,4);
INSERT INTO ezsearch_object_word_link VALUES (3,15,3,0,2,2,4,1,119);
INSERT INTO ezsearch_object_word_link VALUES (4,15,4,0,3,3,5,1,119);
INSERT INTO ezsearch_object_word_link VALUES (5,15,5,0,4,4,0,1,119);
INSERT INTO ezsearch_object_word_link VALUES (6,16,5,0,0,0,6,1,4);
INSERT INTO ezsearch_object_word_link VALUES (7,16,6,0,1,5,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1948,78,177,0,0,0,178,8,128);
INSERT INTO ezsearch_object_word_link VALUES (1947,17,5,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (10,23,8,0,0,0,9,1,4);
INSERT INTO ezsearch_object_word_link VALUES (11,23,9,0,1,8,10,1,119);
INSERT INTO ezsearch_object_word_link VALUES (12,23,10,0,2,9,11,1,119);
INSERT INTO ezsearch_object_word_link VALUES (13,23,11,0,3,10,8,1,119);
INSERT INTO ezsearch_object_word_link VALUES (14,23,8,0,4,11,0,1,119);
INSERT INTO ezsearch_object_word_link VALUES (15,24,12,0,0,0,13,2,120);
INSERT INTO ezsearch_object_word_link VALUES (16,24,13,0,1,12,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (17,24,14,0,2,13,15,2,120);
INSERT INTO ezsearch_object_word_link VALUES (18,24,15,0,3,14,16,2,120);
INSERT INTO ezsearch_object_word_link VALUES (19,24,16,0,4,15,17,2,120);
INSERT INTO ezsearch_object_word_link VALUES (20,24,17,0,5,16,18,2,120);
INSERT INTO ezsearch_object_word_link VALUES (21,24,18,0,6,17,19,2,120);
INSERT INTO ezsearch_object_word_link VALUES (22,24,19,0,7,18,20,2,120);
INSERT INTO ezsearch_object_word_link VALUES (23,24,20,0,8,19,21,2,120);
INSERT INTO ezsearch_object_word_link VALUES (24,24,21,0,9,20,22,2,120);
INSERT INTO ezsearch_object_word_link VALUES (25,24,22,0,10,21,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (26,24,23,0,11,22,24,2,120);
INSERT INTO ezsearch_object_word_link VALUES (27,24,24,0,12,23,25,2,120);
INSERT INTO ezsearch_object_word_link VALUES (28,24,25,0,13,24,26,2,120);
INSERT INTO ezsearch_object_word_link VALUES (29,24,26,0,14,25,27,2,120);
INSERT INTO ezsearch_object_word_link VALUES (30,24,27,0,15,26,28,2,120);
INSERT INTO ezsearch_object_word_link VALUES (31,24,28,0,16,27,29,2,120);
INSERT INTO ezsearch_object_word_link VALUES (32,24,29,0,17,28,30,2,120);
INSERT INTO ezsearch_object_word_link VALUES (33,24,30,0,18,29,31,2,120);
INSERT INTO ezsearch_object_word_link VALUES (34,24,31,0,19,30,32,2,120);
INSERT INTO ezsearch_object_word_link VALUES (35,24,32,0,20,31,33,2,120);
INSERT INTO ezsearch_object_word_link VALUES (36,24,33,0,21,32,34,2,120);
INSERT INTO ezsearch_object_word_link VALUES (37,24,34,0,22,33,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (38,24,23,0,23,34,35,2,120);
INSERT INTO ezsearch_object_word_link VALUES (39,24,35,0,24,23,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (40,24,36,0,25,35,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (41,24,37,0,26,36,38,2,120);
INSERT INTO ezsearch_object_word_link VALUES (42,24,38,0,27,37,39,2,120);
INSERT INTO ezsearch_object_word_link VALUES (43,24,39,0,28,38,40,2,120);
INSERT INTO ezsearch_object_word_link VALUES (44,24,40,0,29,39,41,2,120);
INSERT INTO ezsearch_object_word_link VALUES (45,24,41,0,30,40,42,2,120);
INSERT INTO ezsearch_object_word_link VALUES (46,24,42,0,31,41,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (47,24,43,0,32,42,44,2,120);
INSERT INTO ezsearch_object_word_link VALUES (48,24,44,0,33,43,45,2,120);
INSERT INTO ezsearch_object_word_link VALUES (49,24,45,0,34,44,46,2,120);
INSERT INTO ezsearch_object_word_link VALUES (50,24,46,0,35,45,47,2,120);
INSERT INTO ezsearch_object_word_link VALUES (51,24,47,0,36,46,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (52,24,37,0,37,47,48,2,120);
INSERT INTO ezsearch_object_word_link VALUES (53,24,48,0,38,37,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (54,24,36,0,39,48,49,2,120);
INSERT INTO ezsearch_object_word_link VALUES (55,24,49,0,40,36,50,2,120);
INSERT INTO ezsearch_object_word_link VALUES (56,24,50,0,41,49,51,2,120);
INSERT INTO ezsearch_object_word_link VALUES (57,24,51,0,42,50,52,2,120);
INSERT INTO ezsearch_object_word_link VALUES (58,24,52,0,43,51,53,2,120);
INSERT INTO ezsearch_object_word_link VALUES (59,24,53,0,44,52,54,2,120);
INSERT INTO ezsearch_object_word_link VALUES (60,24,54,0,45,53,55,2,120);
INSERT INTO ezsearch_object_word_link VALUES (61,24,55,0,46,54,56,2,120);
INSERT INTO ezsearch_object_word_link VALUES (62,24,56,0,47,55,57,2,120);
INSERT INTO ezsearch_object_word_link VALUES (63,24,57,0,48,56,58,2,120);
INSERT INTO ezsearch_object_word_link VALUES (64,24,58,0,49,57,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (65,24,59,0,50,58,60,2,120);
INSERT INTO ezsearch_object_word_link VALUES (66,24,60,0,51,59,61,2,120);
INSERT INTO ezsearch_object_word_link VALUES (67,24,61,0,52,60,62,2,120);
INSERT INTO ezsearch_object_word_link VALUES (68,24,62,0,53,61,12,2,120);
INSERT INTO ezsearch_object_word_link VALUES (69,24,12,0,54,62,13,2,120);
INSERT INTO ezsearch_object_word_link VALUES (70,24,13,0,55,12,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (71,24,14,0,56,13,15,2,120);
INSERT INTO ezsearch_object_word_link VALUES (72,24,15,0,57,14,16,2,120);
INSERT INTO ezsearch_object_word_link VALUES (73,24,16,0,58,15,17,2,120);
INSERT INTO ezsearch_object_word_link VALUES (74,24,17,0,59,16,18,2,120);
INSERT INTO ezsearch_object_word_link VALUES (75,24,18,0,60,17,19,2,120);
INSERT INTO ezsearch_object_word_link VALUES (76,24,19,0,61,18,20,2,120);
INSERT INTO ezsearch_object_word_link VALUES (77,24,20,0,62,19,21,2,120);
INSERT INTO ezsearch_object_word_link VALUES (78,24,21,0,63,20,22,2,120);
INSERT INTO ezsearch_object_word_link VALUES (79,24,22,0,64,21,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (80,24,23,0,65,22,24,2,120);
INSERT INTO ezsearch_object_word_link VALUES (81,24,24,0,66,23,25,2,120);
INSERT INTO ezsearch_object_word_link VALUES (82,24,25,0,67,24,26,2,120);
INSERT INTO ezsearch_object_word_link VALUES (83,24,26,0,68,25,27,2,120);
INSERT INTO ezsearch_object_word_link VALUES (84,24,27,0,69,26,28,2,120);
INSERT INTO ezsearch_object_word_link VALUES (85,24,28,0,70,27,29,2,120);
INSERT INTO ezsearch_object_word_link VALUES (86,24,29,0,71,28,30,2,120);
INSERT INTO ezsearch_object_word_link VALUES (87,24,30,0,72,29,31,2,120);
INSERT INTO ezsearch_object_word_link VALUES (88,24,31,0,73,30,32,2,120);
INSERT INTO ezsearch_object_word_link VALUES (89,24,32,0,74,31,33,2,120);
INSERT INTO ezsearch_object_word_link VALUES (90,24,33,0,75,32,34,2,120);
INSERT INTO ezsearch_object_word_link VALUES (91,24,34,0,76,33,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (92,24,23,0,77,34,35,2,120);
INSERT INTO ezsearch_object_word_link VALUES (93,24,35,0,78,23,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (94,24,36,0,79,35,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (95,24,37,0,80,36,38,2,120);
INSERT INTO ezsearch_object_word_link VALUES (96,24,38,0,81,37,39,2,120);
INSERT INTO ezsearch_object_word_link VALUES (97,24,39,0,82,38,40,2,120);
INSERT INTO ezsearch_object_word_link VALUES (98,24,40,0,83,39,41,2,120);
INSERT INTO ezsearch_object_word_link VALUES (99,24,41,0,84,40,42,2,120);
INSERT INTO ezsearch_object_word_link VALUES (100,24,42,0,85,41,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (101,24,43,0,86,42,44,2,120);
INSERT INTO ezsearch_object_word_link VALUES (102,24,44,0,87,43,45,2,120);
INSERT INTO ezsearch_object_word_link VALUES (103,24,45,0,88,44,46,2,120);
INSERT INTO ezsearch_object_word_link VALUES (104,24,46,0,89,45,47,2,120);
INSERT INTO ezsearch_object_word_link VALUES (105,24,47,0,90,46,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (106,24,37,0,91,47,48,2,120);
INSERT INTO ezsearch_object_word_link VALUES (107,24,48,0,92,37,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (108,24,36,0,93,48,49,2,120);
INSERT INTO ezsearch_object_word_link VALUES (109,24,49,0,94,36,50,2,120);
INSERT INTO ezsearch_object_word_link VALUES (110,24,50,0,95,49,51,2,120);
INSERT INTO ezsearch_object_word_link VALUES (111,24,51,0,96,50,52,2,120);
INSERT INTO ezsearch_object_word_link VALUES (112,24,52,0,97,51,53,2,120);
INSERT INTO ezsearch_object_word_link VALUES (113,24,53,0,98,52,54,2,120);
INSERT INTO ezsearch_object_word_link VALUES (114,24,54,0,99,53,55,2,120);
INSERT INTO ezsearch_object_word_link VALUES (115,24,55,0,100,54,56,2,120);
INSERT INTO ezsearch_object_word_link VALUES (116,24,56,0,101,55,57,2,120);
INSERT INTO ezsearch_object_word_link VALUES (117,24,57,0,102,56,58,2,120);
INSERT INTO ezsearch_object_word_link VALUES (118,24,58,0,103,57,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (119,24,59,0,104,58,60,2,120);
INSERT INTO ezsearch_object_word_link VALUES (120,24,60,0,105,59,61,2,120);
INSERT INTO ezsearch_object_word_link VALUES (121,24,61,0,106,60,62,2,120);
INSERT INTO ezsearch_object_word_link VALUES (122,24,62,0,107,61,12,2,120);
INSERT INTO ezsearch_object_word_link VALUES (123,24,12,0,108,62,13,2,120);
INSERT INTO ezsearch_object_word_link VALUES (124,24,13,0,109,12,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (125,24,14,0,110,13,15,2,120);
INSERT INTO ezsearch_object_word_link VALUES (126,24,15,0,111,14,16,2,120);
INSERT INTO ezsearch_object_word_link VALUES (127,24,16,0,112,15,17,2,120);
INSERT INTO ezsearch_object_word_link VALUES (128,24,17,0,113,16,18,2,120);
INSERT INTO ezsearch_object_word_link VALUES (129,24,18,0,114,17,19,2,120);
INSERT INTO ezsearch_object_word_link VALUES (130,24,19,0,115,18,20,2,120);
INSERT INTO ezsearch_object_word_link VALUES (131,24,20,0,116,19,21,2,120);
INSERT INTO ezsearch_object_word_link VALUES (132,24,21,0,117,20,22,2,120);
INSERT INTO ezsearch_object_word_link VALUES (133,24,22,0,118,21,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (134,24,23,0,119,22,24,2,120);
INSERT INTO ezsearch_object_word_link VALUES (135,24,24,0,120,23,25,2,120);
INSERT INTO ezsearch_object_word_link VALUES (136,24,25,0,121,24,26,2,120);
INSERT INTO ezsearch_object_word_link VALUES (137,24,26,0,122,25,27,2,120);
INSERT INTO ezsearch_object_word_link VALUES (138,24,27,0,123,26,28,2,120);
INSERT INTO ezsearch_object_word_link VALUES (139,24,28,0,124,27,29,2,120);
INSERT INTO ezsearch_object_word_link VALUES (140,24,29,0,125,28,30,2,120);
INSERT INTO ezsearch_object_word_link VALUES (141,24,30,0,126,29,31,2,120);
INSERT INTO ezsearch_object_word_link VALUES (142,24,31,0,127,30,32,2,120);
INSERT INTO ezsearch_object_word_link VALUES (143,24,32,0,128,31,33,2,120);
INSERT INTO ezsearch_object_word_link VALUES (144,24,33,0,129,32,34,2,120);
INSERT INTO ezsearch_object_word_link VALUES (145,24,34,0,130,33,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (146,24,23,0,131,34,35,2,120);
INSERT INTO ezsearch_object_word_link VALUES (147,24,35,0,132,23,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (148,24,36,0,133,35,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (149,24,37,0,134,36,38,2,120);
INSERT INTO ezsearch_object_word_link VALUES (150,24,38,0,135,37,39,2,120);
INSERT INTO ezsearch_object_word_link VALUES (151,24,39,0,136,38,40,2,120);
INSERT INTO ezsearch_object_word_link VALUES (152,24,40,0,137,39,41,2,120);
INSERT INTO ezsearch_object_word_link VALUES (153,24,41,0,138,40,42,2,120);
INSERT INTO ezsearch_object_word_link VALUES (154,24,42,0,139,41,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (155,24,43,0,140,42,44,2,120);
INSERT INTO ezsearch_object_word_link VALUES (156,24,44,0,141,43,45,2,120);
INSERT INTO ezsearch_object_word_link VALUES (157,24,45,0,142,44,46,2,120);
INSERT INTO ezsearch_object_word_link VALUES (158,24,46,0,143,45,47,2,120);
INSERT INTO ezsearch_object_word_link VALUES (159,24,47,0,144,46,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (160,24,37,0,145,47,48,2,120);
INSERT INTO ezsearch_object_word_link VALUES (161,24,48,0,146,37,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (162,24,36,0,147,48,49,2,120);
INSERT INTO ezsearch_object_word_link VALUES (163,24,49,0,148,36,50,2,120);
INSERT INTO ezsearch_object_word_link VALUES (164,24,50,0,149,49,51,2,120);
INSERT INTO ezsearch_object_word_link VALUES (165,24,51,0,150,50,52,2,120);
INSERT INTO ezsearch_object_word_link VALUES (166,24,52,0,151,51,53,2,120);
INSERT INTO ezsearch_object_word_link VALUES (167,24,53,0,152,52,54,2,120);
INSERT INTO ezsearch_object_word_link VALUES (168,24,54,0,153,53,55,2,120);
INSERT INTO ezsearch_object_word_link VALUES (169,24,55,0,154,54,56,2,120);
INSERT INTO ezsearch_object_word_link VALUES (170,24,56,0,155,55,57,2,120);
INSERT INTO ezsearch_object_word_link VALUES (171,24,57,0,156,56,58,2,120);
INSERT INTO ezsearch_object_word_link VALUES (172,24,58,0,157,57,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (173,24,59,0,158,58,60,2,120);
INSERT INTO ezsearch_object_word_link VALUES (174,24,60,0,159,59,61,2,120);
INSERT INTO ezsearch_object_word_link VALUES (175,24,61,0,160,60,62,2,120);
INSERT INTO ezsearch_object_word_link VALUES (176,24,62,0,161,61,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (177,24,59,0,162,62,63,2,120);
INSERT INTO ezsearch_object_word_link VALUES (178,24,63,0,163,59,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (179,24,43,0,164,63,64,2,120);
INSERT INTO ezsearch_object_word_link VALUES (180,24,64,0,165,43,65,2,120);
INSERT INTO ezsearch_object_word_link VALUES (181,24,65,0,166,64,66,2,120);
INSERT INTO ezsearch_object_word_link VALUES (182,24,66,0,167,65,67,2,120);
INSERT INTO ezsearch_object_word_link VALUES (183,24,67,0,168,66,68,2,120);
INSERT INTO ezsearch_object_word_link VALUES (184,24,68,0,169,67,69,2,120);
INSERT INTO ezsearch_object_word_link VALUES (185,24,69,0,170,68,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (186,24,14,0,171,69,12,2,121);
INSERT INTO ezsearch_object_word_link VALUES (187,24,12,0,172,14,13,2,121);
INSERT INTO ezsearch_object_word_link VALUES (188,24,13,0,173,12,14,2,121);
INSERT INTO ezsearch_object_word_link VALUES (189,24,14,0,174,13,15,2,121);
INSERT INTO ezsearch_object_word_link VALUES (190,24,15,0,175,14,16,2,121);
INSERT INTO ezsearch_object_word_link VALUES (191,24,16,0,176,15,17,2,121);
INSERT INTO ezsearch_object_word_link VALUES (192,24,17,0,177,16,18,2,121);
INSERT INTO ezsearch_object_word_link VALUES (193,24,18,0,178,17,19,2,121);
INSERT INTO ezsearch_object_word_link VALUES (194,24,19,0,179,18,20,2,121);
INSERT INTO ezsearch_object_word_link VALUES (195,24,20,0,180,19,21,2,121);
INSERT INTO ezsearch_object_word_link VALUES (196,24,21,0,181,20,22,2,121);
INSERT INTO ezsearch_object_word_link VALUES (197,24,22,0,182,21,23,2,121);
INSERT INTO ezsearch_object_word_link VALUES (198,24,23,0,183,22,24,2,121);
INSERT INTO ezsearch_object_word_link VALUES (199,24,24,0,184,23,25,2,121);
INSERT INTO ezsearch_object_word_link VALUES (200,24,25,0,185,24,26,2,121);
INSERT INTO ezsearch_object_word_link VALUES (201,24,26,0,186,25,27,2,121);
INSERT INTO ezsearch_object_word_link VALUES (202,24,27,0,187,26,28,2,121);
INSERT INTO ezsearch_object_word_link VALUES (203,24,28,0,188,27,29,2,121);
INSERT INTO ezsearch_object_word_link VALUES (204,24,29,0,189,28,30,2,121);
INSERT INTO ezsearch_object_word_link VALUES (205,24,30,0,190,29,31,2,121);
INSERT INTO ezsearch_object_word_link VALUES (206,24,31,0,191,30,32,2,121);
INSERT INTO ezsearch_object_word_link VALUES (207,24,32,0,192,31,33,2,121);
INSERT INTO ezsearch_object_word_link VALUES (208,24,33,0,193,32,34,2,121);
INSERT INTO ezsearch_object_word_link VALUES (209,24,34,0,194,33,23,2,121);
INSERT INTO ezsearch_object_word_link VALUES (210,24,23,0,195,34,35,2,121);
INSERT INTO ezsearch_object_word_link VALUES (211,24,35,0,196,23,36,2,121);
INSERT INTO ezsearch_object_word_link VALUES (212,24,36,0,197,35,37,2,121);
INSERT INTO ezsearch_object_word_link VALUES (213,24,37,0,198,36,38,2,121);
INSERT INTO ezsearch_object_word_link VALUES (214,24,38,0,199,37,39,2,121);
INSERT INTO ezsearch_object_word_link VALUES (215,24,39,0,200,38,40,2,121);
INSERT INTO ezsearch_object_word_link VALUES (216,24,40,0,201,39,41,2,121);
INSERT INTO ezsearch_object_word_link VALUES (217,24,41,0,202,40,42,2,121);
INSERT INTO ezsearch_object_word_link VALUES (218,24,42,0,203,41,43,2,121);
INSERT INTO ezsearch_object_word_link VALUES (219,24,43,0,204,42,44,2,121);
INSERT INTO ezsearch_object_word_link VALUES (220,24,44,0,205,43,45,2,121);
INSERT INTO ezsearch_object_word_link VALUES (221,24,45,0,206,44,46,2,121);
INSERT INTO ezsearch_object_word_link VALUES (222,24,46,0,207,45,47,2,121);
INSERT INTO ezsearch_object_word_link VALUES (223,24,47,0,208,46,37,2,121);
INSERT INTO ezsearch_object_word_link VALUES (224,24,37,0,209,47,48,2,121);
INSERT INTO ezsearch_object_word_link VALUES (225,24,48,0,210,37,36,2,121);
INSERT INTO ezsearch_object_word_link VALUES (226,24,36,0,211,48,49,2,121);
INSERT INTO ezsearch_object_word_link VALUES (227,24,49,0,212,36,50,2,121);
INSERT INTO ezsearch_object_word_link VALUES (228,24,50,0,213,49,51,2,121);
INSERT INTO ezsearch_object_word_link VALUES (229,24,51,0,214,50,52,2,121);
INSERT INTO ezsearch_object_word_link VALUES (230,24,52,0,215,51,53,2,121);
INSERT INTO ezsearch_object_word_link VALUES (231,24,53,0,216,52,54,2,121);
INSERT INTO ezsearch_object_word_link VALUES (232,24,54,0,217,53,55,2,121);
INSERT INTO ezsearch_object_word_link VALUES (233,24,55,0,218,54,56,2,121);
INSERT INTO ezsearch_object_word_link VALUES (234,24,56,0,219,55,57,2,121);
INSERT INTO ezsearch_object_word_link VALUES (235,24,57,0,220,56,58,2,121);
INSERT INTO ezsearch_object_word_link VALUES (236,24,58,0,221,57,59,2,121);
INSERT INTO ezsearch_object_word_link VALUES (237,24,59,0,222,58,60,2,121);
INSERT INTO ezsearch_object_word_link VALUES (238,24,60,0,223,59,61,2,121);
INSERT INTO ezsearch_object_word_link VALUES (239,24,61,0,224,60,62,2,121);
INSERT INTO ezsearch_object_word_link VALUES (240,24,62,0,225,61,59,2,121);
INSERT INTO ezsearch_object_word_link VALUES (241,24,59,0,226,62,63,2,121);
INSERT INTO ezsearch_object_word_link VALUES (242,24,63,0,227,59,43,2,121);
INSERT INTO ezsearch_object_word_link VALUES (243,24,43,0,228,63,64,2,121);
INSERT INTO ezsearch_object_word_link VALUES (244,24,64,0,229,43,65,2,121);
INSERT INTO ezsearch_object_word_link VALUES (245,24,65,0,230,64,66,2,121);
INSERT INTO ezsearch_object_word_link VALUES (246,24,66,0,231,65,67,2,121);
INSERT INTO ezsearch_object_word_link VALUES (247,24,67,0,232,66,68,2,121);
INSERT INTO ezsearch_object_word_link VALUES (248,24,68,0,233,67,69,2,121);
INSERT INTO ezsearch_object_word_link VALUES (249,24,69,0,234,68,70,2,121);
INSERT INTO ezsearch_object_word_link VALUES (250,24,70,0,235,69,0,2,123);
INSERT INTO ezsearch_object_word_link VALUES (1971,25,8,0,2,726,0,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1970,25,726,0,1,725,8,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1969,25,725,0,0,0,726,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1977,26,8,0,2,728,0,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1976,26,728,0,1,728,8,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1975,26,728,0,0,0,728,1,4);
INSERT INTO ezsearch_object_word_link VALUES (257,27,75,0,0,0,75,1,4);
INSERT INTO ezsearch_object_word_link VALUES (258,27,75,0,1,75,0,1,119);
INSERT INTO ezsearch_object_word_link VALUES (259,28,76,0,0,0,77,1,4);
INSERT INTO ezsearch_object_word_link VALUES (260,28,77,0,1,76,11,1,119);
INSERT INTO ezsearch_object_word_link VALUES (261,28,11,0,2,77,76,1,119);
INSERT INTO ezsearch_object_word_link VALUES (262,28,76,0,3,11,0,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1987,29,730,0,4,11,0,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1986,29,11,0,3,77,730,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1985,29,77,0,2,8,11,1,119);
INSERT INTO ezsearch_object_word_link VALUES (1984,29,8,0,1,76,77,1,4);
INSERT INTO ezsearch_object_word_link VALUES (267,30,79,0,0,0,80,1,4);
INSERT INTO ezsearch_object_word_link VALUES (268,30,80,0,1,79,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (269,31,81,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (270,32,80,0,0,0,6,6,124);
INSERT INTO ezsearch_object_word_link VALUES (271,32,6,0,1,80,82,6,124);
INSERT INTO ezsearch_object_word_link VALUES (272,32,82,0,2,6,83,6,126);
INSERT INTO ezsearch_object_word_link VALUES (273,32,83,0,3,82,84,6,126);
INSERT INTO ezsearch_object_word_link VALUES (274,32,84,0,4,83,85,6,126);
INSERT INTO ezsearch_object_word_link VALUES (275,32,85,0,5,84,86,6,126);
INSERT INTO ezsearch_object_word_link VALUES (276,32,86,0,6,85,87,6,126);
INSERT INTO ezsearch_object_word_link VALUES (277,32,87,0,7,86,88,6,126);
INSERT INTO ezsearch_object_word_link VALUES (278,32,88,0,8,87,89,6,126);
INSERT INTO ezsearch_object_word_link VALUES (279,32,89,0,9,88,90,6,126);
INSERT INTO ezsearch_object_word_link VALUES (280,32,90,0,10,89,91,6,126);
INSERT INTO ezsearch_object_word_link VALUES (281,32,91,0,11,90,23,6,126);
INSERT INTO ezsearch_object_word_link VALUES (282,32,23,0,12,91,92,6,126);
INSERT INTO ezsearch_object_word_link VALUES (283,32,92,0,13,23,93,6,126);
INSERT INTO ezsearch_object_word_link VALUES (284,32,93,0,14,92,94,6,126);
INSERT INTO ezsearch_object_word_link VALUES (285,32,94,0,15,93,95,6,126);
INSERT INTO ezsearch_object_word_link VALUES (286,32,95,0,16,94,96,6,126);
INSERT INTO ezsearch_object_word_link VALUES (287,32,96,0,17,95,97,6,126);
INSERT INTO ezsearch_object_word_link VALUES (288,32,97,0,18,96,98,6,126);
INSERT INTO ezsearch_object_word_link VALUES (289,32,98,0,19,97,99,6,126);
INSERT INTO ezsearch_object_word_link VALUES (290,32,99,0,20,98,100,6,126);
INSERT INTO ezsearch_object_word_link VALUES (291,32,100,0,21,99,101,6,126);
INSERT INTO ezsearch_object_word_link VALUES (292,32,101,0,22,100,36,6,126);
INSERT INTO ezsearch_object_word_link VALUES (293,32,36,0,23,101,69,6,126);
INSERT INTO ezsearch_object_word_link VALUES (294,32,69,0,24,36,30,6,126);
INSERT INTO ezsearch_object_word_link VALUES (295,32,30,0,25,69,102,6,126);
INSERT INTO ezsearch_object_word_link VALUES (296,32,102,0,26,30,103,6,126);
INSERT INTO ezsearch_object_word_link VALUES (297,32,103,0,27,102,104,6,126);
INSERT INTO ezsearch_object_word_link VALUES (298,32,104,0,28,103,105,6,126);
INSERT INTO ezsearch_object_word_link VALUES (299,32,105,0,29,104,106,6,126);
INSERT INTO ezsearch_object_word_link VALUES (300,32,106,0,30,105,107,6,126);
INSERT INTO ezsearch_object_word_link VALUES (301,32,107,0,31,106,108,6,126);
INSERT INTO ezsearch_object_word_link VALUES (302,32,108,0,32,107,109,6,126);
INSERT INTO ezsearch_object_word_link VALUES (303,32,109,0,33,108,110,6,126);
INSERT INTO ezsearch_object_word_link VALUES (304,32,110,0,34,109,111,6,126);
INSERT INTO ezsearch_object_word_link VALUES (305,32,111,0,35,110,112,6,126);
INSERT INTO ezsearch_object_word_link VALUES (306,32,112,0,36,111,36,6,126);
INSERT INTO ezsearch_object_word_link VALUES (307,32,36,0,37,112,113,6,126);
INSERT INTO ezsearch_object_word_link VALUES (308,32,113,0,38,36,114,6,126);
INSERT INTO ezsearch_object_word_link VALUES (309,32,114,0,39,113,115,6,126);
INSERT INTO ezsearch_object_word_link VALUES (310,32,115,0,40,114,116,6,126);
INSERT INTO ezsearch_object_word_link VALUES (311,32,116,0,41,115,59,6,126);
INSERT INTO ezsearch_object_word_link VALUES (312,32,59,0,42,116,117,6,126);
INSERT INTO ezsearch_object_word_link VALUES (313,32,117,0,43,59,118,6,126);
INSERT INTO ezsearch_object_word_link VALUES (314,32,118,0,44,117,119,6,126);
INSERT INTO ezsearch_object_word_link VALUES (315,32,119,0,45,118,120,6,126);
INSERT INTO ezsearch_object_word_link VALUES (316,32,120,0,46,119,0,6,126);
INSERT INTO ezsearch_object_word_link VALUES (317,33,80,0,0,0,7,6,124);
INSERT INTO ezsearch_object_word_link VALUES (318,33,7,0,1,80,121,6,124);
INSERT INTO ezsearch_object_word_link VALUES (319,33,121,0,2,7,68,6,126);
INSERT INTO ezsearch_object_word_link VALUES (320,33,68,0,3,121,122,6,126);
INSERT INTO ezsearch_object_word_link VALUES (321,33,122,0,4,68,123,6,126);
INSERT INTO ezsearch_object_word_link VALUES (322,33,123,0,5,122,124,6,126);
INSERT INTO ezsearch_object_word_link VALUES (323,33,124,0,6,123,125,6,126);
INSERT INTO ezsearch_object_word_link VALUES (324,33,125,0,7,124,30,6,126);
INSERT INTO ezsearch_object_word_link VALUES (325,33,30,0,8,125,126,6,126);
INSERT INTO ezsearch_object_word_link VALUES (326,33,126,0,9,30,127,6,126);
INSERT INTO ezsearch_object_word_link VALUES (327,33,127,0,10,126,128,6,126);
INSERT INTO ezsearch_object_word_link VALUES (328,33,128,0,11,127,129,6,126);
INSERT INTO ezsearch_object_word_link VALUES (329,33,129,0,12,128,110,6,126);
INSERT INTO ezsearch_object_word_link VALUES (330,33,110,0,13,129,130,6,126);
INSERT INTO ezsearch_object_word_link VALUES (331,33,130,0,14,110,131,6,126);
INSERT INTO ezsearch_object_word_link VALUES (332,33,131,0,15,130,132,6,126);
INSERT INTO ezsearch_object_word_link VALUES (333,33,132,0,16,131,133,6,126);
INSERT INTO ezsearch_object_word_link VALUES (334,33,133,0,17,132,134,6,126);
INSERT INTO ezsearch_object_word_link VALUES (335,33,134,0,18,133,135,6,126);
INSERT INTO ezsearch_object_word_link VALUES (336,33,135,0,19,134,136,6,126);
INSERT INTO ezsearch_object_word_link VALUES (337,33,136,0,20,135,137,6,126);
INSERT INTO ezsearch_object_word_link VALUES (338,33,137,0,21,136,138,6,126);
INSERT INTO ezsearch_object_word_link VALUES (339,33,138,0,22,137,25,6,126);
INSERT INTO ezsearch_object_word_link VALUES (340,33,25,0,23,138,139,6,126);
INSERT INTO ezsearch_object_word_link VALUES (341,33,139,0,24,25,140,6,126);
INSERT INTO ezsearch_object_word_link VALUES (342,33,140,0,25,139,141,6,126);
INSERT INTO ezsearch_object_word_link VALUES (343,33,141,0,26,140,142,6,126);
INSERT INTO ezsearch_object_word_link VALUES (344,33,142,0,27,141,143,6,126);
INSERT INTO ezsearch_object_word_link VALUES (345,33,143,0,28,142,144,6,126);
INSERT INTO ezsearch_object_word_link VALUES (346,33,144,0,29,143,145,6,126);
INSERT INTO ezsearch_object_word_link VALUES (347,33,145,0,30,144,146,6,126);
INSERT INTO ezsearch_object_word_link VALUES (348,33,146,0,31,145,147,6,126);
INSERT INTO ezsearch_object_word_link VALUES (349,33,147,0,32,146,148,6,126);
INSERT INTO ezsearch_object_word_link VALUES (350,33,148,0,33,147,149,6,126);
INSERT INTO ezsearch_object_word_link VALUES (351,33,149,0,34,148,150,6,126);
INSERT INTO ezsearch_object_word_link VALUES (352,33,150,0,35,149,151,6,126);
INSERT INTO ezsearch_object_word_link VALUES (353,33,151,0,36,150,152,6,126);
INSERT INTO ezsearch_object_word_link VALUES (354,33,152,0,37,151,153,6,126);
INSERT INTO ezsearch_object_word_link VALUES (355,33,153,0,38,152,154,6,126);
INSERT INTO ezsearch_object_word_link VALUES (356,33,154,0,39,153,155,6,126);
INSERT INTO ezsearch_object_word_link VALUES (357,33,155,0,40,154,156,6,126);
INSERT INTO ezsearch_object_word_link VALUES (358,33,156,0,41,155,157,6,126);
INSERT INTO ezsearch_object_word_link VALUES (359,33,157,0,42,156,158,6,126);
INSERT INTO ezsearch_object_word_link VALUES (360,33,158,0,43,157,159,6,126);
INSERT INTO ezsearch_object_word_link VALUES (361,33,159,0,44,158,160,6,126);
INSERT INTO ezsearch_object_word_link VALUES (362,33,160,0,45,159,161,6,126);
INSERT INTO ezsearch_object_word_link VALUES (363,33,161,0,46,160,162,6,126);
INSERT INTO ezsearch_object_word_link VALUES (364,33,162,0,47,161,43,6,126);
INSERT INTO ezsearch_object_word_link VALUES (365,33,43,0,48,162,163,6,126);
INSERT INTO ezsearch_object_word_link VALUES (366,33,163,0,49,43,164,6,126);
INSERT INTO ezsearch_object_word_link VALUES (367,33,164,0,50,163,162,6,126);
INSERT INTO ezsearch_object_word_link VALUES (368,33,162,0,51,164,110,6,126);
INSERT INTO ezsearch_object_word_link VALUES (369,33,110,0,52,162,165,6,126);
INSERT INTO ezsearch_object_word_link VALUES (370,33,165,0,53,110,166,6,126);
INSERT INTO ezsearch_object_word_link VALUES (371,33,166,0,54,165,167,6,126);
INSERT INTO ezsearch_object_word_link VALUES (372,33,167,0,55,166,168,6,126);
INSERT INTO ezsearch_object_word_link VALUES (373,33,168,0,56,167,169,6,126);
INSERT INTO ezsearch_object_word_link VALUES (374,33,169,0,57,168,170,6,126);
INSERT INTO ezsearch_object_word_link VALUES (375,33,170,0,58,169,171,6,126);
INSERT INTO ezsearch_object_word_link VALUES (376,33,171,0,59,170,0,6,126);
INSERT INTO ezsearch_object_word_link VALUES (377,34,80,0,0,0,172,6,124);
INSERT INTO ezsearch_object_word_link VALUES (378,34,172,0,1,80,82,6,124);
INSERT INTO ezsearch_object_word_link VALUES (379,34,82,0,2,172,83,6,126);
INSERT INTO ezsearch_object_word_link VALUES (380,34,83,0,3,82,84,6,126);
INSERT INTO ezsearch_object_word_link VALUES (381,34,84,0,4,83,85,6,126);
INSERT INTO ezsearch_object_word_link VALUES (382,34,85,0,5,84,86,6,126);
INSERT INTO ezsearch_object_word_link VALUES (383,34,86,0,6,85,87,6,126);
INSERT INTO ezsearch_object_word_link VALUES (384,34,87,0,7,86,88,6,126);
INSERT INTO ezsearch_object_word_link VALUES (385,34,88,0,8,87,89,6,126);
INSERT INTO ezsearch_object_word_link VALUES (386,34,89,0,9,88,90,6,126);
INSERT INTO ezsearch_object_word_link VALUES (387,34,90,0,10,89,91,6,126);
INSERT INTO ezsearch_object_word_link VALUES (388,34,91,0,11,90,23,6,126);
INSERT INTO ezsearch_object_word_link VALUES (389,34,23,0,12,91,92,6,126);
INSERT INTO ezsearch_object_word_link VALUES (390,34,92,0,13,23,93,6,126);
INSERT INTO ezsearch_object_word_link VALUES (391,34,93,0,14,92,94,6,126);
INSERT INTO ezsearch_object_word_link VALUES (392,34,94,0,15,93,95,6,126);
INSERT INTO ezsearch_object_word_link VALUES (393,34,95,0,16,94,96,6,126);
INSERT INTO ezsearch_object_word_link VALUES (394,34,96,0,17,95,97,6,126);
INSERT INTO ezsearch_object_word_link VALUES (395,34,97,0,18,96,98,6,126);
INSERT INTO ezsearch_object_word_link VALUES (396,34,98,0,19,97,99,6,126);
INSERT INTO ezsearch_object_word_link VALUES (397,34,99,0,20,98,100,6,126);
INSERT INTO ezsearch_object_word_link VALUES (398,34,100,0,21,99,101,6,126);
INSERT INTO ezsearch_object_word_link VALUES (399,34,101,0,22,100,36,6,126);
INSERT INTO ezsearch_object_word_link VALUES (400,34,36,0,23,101,69,6,126);
INSERT INTO ezsearch_object_word_link VALUES (401,34,69,0,24,36,30,6,126);
INSERT INTO ezsearch_object_word_link VALUES (402,34,30,0,25,69,102,6,126);
INSERT INTO ezsearch_object_word_link VALUES (403,34,102,0,26,30,103,6,126);
INSERT INTO ezsearch_object_word_link VALUES (404,34,103,0,27,102,104,6,126);
INSERT INTO ezsearch_object_word_link VALUES (405,34,104,0,28,103,105,6,126);
INSERT INTO ezsearch_object_word_link VALUES (406,34,105,0,29,104,106,6,126);
INSERT INTO ezsearch_object_word_link VALUES (407,34,106,0,30,105,107,6,126);
INSERT INTO ezsearch_object_word_link VALUES (408,34,107,0,31,106,108,6,126);
INSERT INTO ezsearch_object_word_link VALUES (409,34,108,0,32,107,109,6,126);
INSERT INTO ezsearch_object_word_link VALUES (410,34,109,0,33,108,110,6,126);
INSERT INTO ezsearch_object_word_link VALUES (411,34,110,0,34,109,111,6,126);
INSERT INTO ezsearch_object_word_link VALUES (412,34,111,0,35,110,112,6,126);
INSERT INTO ezsearch_object_word_link VALUES (413,34,112,0,36,111,36,6,126);
INSERT INTO ezsearch_object_word_link VALUES (414,34,36,0,37,112,113,6,126);
INSERT INTO ezsearch_object_word_link VALUES (415,34,113,0,38,36,114,6,126);
INSERT INTO ezsearch_object_word_link VALUES (416,34,114,0,39,113,115,6,126);
INSERT INTO ezsearch_object_word_link VALUES (417,34,115,0,40,114,116,6,126);
INSERT INTO ezsearch_object_word_link VALUES (418,34,116,0,41,115,59,6,126);
INSERT INTO ezsearch_object_word_link VALUES (419,34,59,0,42,116,117,6,126);
INSERT INTO ezsearch_object_word_link VALUES (420,34,117,0,43,59,118,6,126);
INSERT INTO ezsearch_object_word_link VALUES (421,34,118,0,44,117,119,6,126);
INSERT INTO ezsearch_object_word_link VALUES (422,34,119,0,45,118,120,6,126);
INSERT INTO ezsearch_object_word_link VALUES (423,34,120,0,46,119,0,6,126);
INSERT INTO ezsearch_object_word_link VALUES (424,35,80,0,0,0,173,6,124);
INSERT INTO ezsearch_object_word_link VALUES (425,35,173,0,1,80,82,6,124);
INSERT INTO ezsearch_object_word_link VALUES (426,35,82,0,2,173,83,6,126);
INSERT INTO ezsearch_object_word_link VALUES (427,35,83,0,3,82,84,6,126);
INSERT INTO ezsearch_object_word_link VALUES (428,35,84,0,4,83,85,6,126);
INSERT INTO ezsearch_object_word_link VALUES (429,35,85,0,5,84,86,6,126);
INSERT INTO ezsearch_object_word_link VALUES (430,35,86,0,6,85,87,6,126);
INSERT INTO ezsearch_object_word_link VALUES (431,35,87,0,7,86,88,6,126);
INSERT INTO ezsearch_object_word_link VALUES (432,35,88,0,8,87,89,6,126);
INSERT INTO ezsearch_object_word_link VALUES (433,35,89,0,9,88,90,6,126);
INSERT INTO ezsearch_object_word_link VALUES (434,35,90,0,10,89,91,6,126);
INSERT INTO ezsearch_object_word_link VALUES (435,35,91,0,11,90,23,6,126);
INSERT INTO ezsearch_object_word_link VALUES (436,35,23,0,12,91,92,6,126);
INSERT INTO ezsearch_object_word_link VALUES (437,35,92,0,13,23,93,6,126);
INSERT INTO ezsearch_object_word_link VALUES (438,35,93,0,14,92,94,6,126);
INSERT INTO ezsearch_object_word_link VALUES (439,35,94,0,15,93,95,6,126);
INSERT INTO ezsearch_object_word_link VALUES (440,35,95,0,16,94,96,6,126);
INSERT INTO ezsearch_object_word_link VALUES (441,35,96,0,17,95,97,6,126);
INSERT INTO ezsearch_object_word_link VALUES (442,35,97,0,18,96,98,6,126);
INSERT INTO ezsearch_object_word_link VALUES (443,35,98,0,19,97,99,6,126);
INSERT INTO ezsearch_object_word_link VALUES (444,35,99,0,20,98,100,6,126);
INSERT INTO ezsearch_object_word_link VALUES (445,35,100,0,21,99,101,6,126);
INSERT INTO ezsearch_object_word_link VALUES (446,35,101,0,22,100,36,6,126);
INSERT INTO ezsearch_object_word_link VALUES (447,35,36,0,23,101,69,6,126);
INSERT INTO ezsearch_object_word_link VALUES (448,35,69,0,24,36,30,6,126);
INSERT INTO ezsearch_object_word_link VALUES (449,35,30,0,25,69,102,6,126);
INSERT INTO ezsearch_object_word_link VALUES (450,35,102,0,26,30,103,6,126);
INSERT INTO ezsearch_object_word_link VALUES (451,35,103,0,27,102,104,6,126);
INSERT INTO ezsearch_object_word_link VALUES (452,35,104,0,28,103,105,6,126);
INSERT INTO ezsearch_object_word_link VALUES (453,35,105,0,29,104,106,6,126);
INSERT INTO ezsearch_object_word_link VALUES (454,35,106,0,30,105,107,6,126);
INSERT INTO ezsearch_object_word_link VALUES (455,35,107,0,31,106,108,6,126);
INSERT INTO ezsearch_object_word_link VALUES (456,35,108,0,32,107,109,6,126);
INSERT INTO ezsearch_object_word_link VALUES (457,35,109,0,33,108,110,6,126);
INSERT INTO ezsearch_object_word_link VALUES (458,35,110,0,34,109,111,6,126);
INSERT INTO ezsearch_object_word_link VALUES (459,35,111,0,35,110,112,6,126);
INSERT INTO ezsearch_object_word_link VALUES (460,35,112,0,36,111,36,6,126);
INSERT INTO ezsearch_object_word_link VALUES (461,35,36,0,37,112,113,6,126);
INSERT INTO ezsearch_object_word_link VALUES (462,35,113,0,38,36,114,6,126);
INSERT INTO ezsearch_object_word_link VALUES (463,35,114,0,39,113,115,6,126);
INSERT INTO ezsearch_object_word_link VALUES (464,35,115,0,40,114,116,6,126);
INSERT INTO ezsearch_object_word_link VALUES (465,35,116,0,41,115,59,6,126);
INSERT INTO ezsearch_object_word_link VALUES (466,35,59,0,42,116,117,6,126);
INSERT INTO ezsearch_object_word_link VALUES (467,35,117,0,43,59,118,6,126);
INSERT INTO ezsearch_object_word_link VALUES (468,35,118,0,44,117,119,6,126);
INSERT INTO ezsearch_object_word_link VALUES (469,35,119,0,45,118,120,6,126);
INSERT INTO ezsearch_object_word_link VALUES (470,35,120,0,46,119,0,6,126);
INSERT INTO ezsearch_object_word_link VALUES (471,36,174,0,0,0,175,7,127);
INSERT INTO ezsearch_object_word_link VALUES (472,36,175,0,1,174,0,7,127);
INSERT INTO ezsearch_object_word_link VALUES (473,37,176,0,0,0,0,9,130);
INSERT INTO ezsearch_object_word_link VALUES (474,38,176,0,0,0,0,9,130);
INSERT INTO ezsearch_object_word_link VALUES (475,40,177,0,0,0,178,8,128);
INSERT INTO ezsearch_object_word_link VALUES (476,40,178,0,1,177,179,8,128);
INSERT INTO ezsearch_object_word_link VALUES (477,40,179,0,2,178,108,8,129);
INSERT INTO ezsearch_object_word_link VALUES (478,40,108,0,3,179,11,8,129);
INSERT INTO ezsearch_object_word_link VALUES (479,40,11,0,4,108,177,8,129);
INSERT INTO ezsearch_object_word_link VALUES (480,40,177,0,5,11,178,8,129);
INSERT INTO ezsearch_object_word_link VALUES (481,40,178,0,6,177,180,8,129);
INSERT INTO ezsearch_object_word_link VALUES (482,40,180,0,7,178,181,8,129);
INSERT INTO ezsearch_object_word_link VALUES (483,40,181,0,8,180,182,8,129);
INSERT INTO ezsearch_object_word_link VALUES (484,40,182,0,9,181,183,8,129);
INSERT INTO ezsearch_object_word_link VALUES (485,40,183,0,10,182,172,8,129);
INSERT INTO ezsearch_object_word_link VALUES (486,40,172,0,11,183,70,8,129);
INSERT INTO ezsearch_object_word_link VALUES (487,40,70,0,12,172,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (488,41,184,0,0,0,185,8,128);
INSERT INTO ezsearch_object_word_link VALUES (489,41,185,0,1,184,186,8,128);
INSERT INTO ezsearch_object_word_link VALUES (490,41,186,0,2,185,187,8,129);
INSERT INTO ezsearch_object_word_link VALUES (491,41,187,0,3,186,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (492,42,188,0,0,0,0,10,131);
INSERT INTO ezsearch_object_word_link VALUES (493,43,177,0,0,0,189,8,128);
INSERT INTO ezsearch_object_word_link VALUES (494,43,189,0,1,177,179,8,128);
INSERT INTO ezsearch_object_word_link VALUES (495,43,179,0,2,189,108,8,129);
INSERT INTO ezsearch_object_word_link VALUES (496,43,108,0,3,179,190,8,129);
INSERT INTO ezsearch_object_word_link VALUES (497,43,190,0,4,108,191,8,129);
INSERT INTO ezsearch_object_word_link VALUES (498,43,191,0,5,190,192,8,129);
INSERT INTO ezsearch_object_word_link VALUES (499,43,192,0,6,191,193,8,129);
INSERT INTO ezsearch_object_word_link VALUES (500,43,193,0,7,192,194,8,129);
INSERT INTO ezsearch_object_word_link VALUES (501,43,194,0,8,193,195,8,129);
INSERT INTO ezsearch_object_word_link VALUES (502,43,195,0,9,194,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (503,44,196,0,0,0,0,11,132);
INSERT INTO ezsearch_object_word_link VALUES (504,45,191,0,0,0,197,8,128);
INSERT INTO ezsearch_object_word_link VALUES (505,45,197,0,1,191,198,8,128);
INSERT INTO ezsearch_object_word_link VALUES (506,45,198,0,2,197,199,8,129);
INSERT INTO ezsearch_object_word_link VALUES (507,45,199,0,3,198,200,8,129);
INSERT INTO ezsearch_object_word_link VALUES (508,45,200,0,4,199,201,8,129);
INSERT INTO ezsearch_object_word_link VALUES (509,45,201,0,5,200,202,8,129);
INSERT INTO ezsearch_object_word_link VALUES (510,45,202,0,6,201,203,8,129);
INSERT INTO ezsearch_object_word_link VALUES (511,45,203,0,7,202,204,8,129);
INSERT INTO ezsearch_object_word_link VALUES (512,45,204,0,8,203,205,8,129);
INSERT INTO ezsearch_object_word_link VALUES (513,45,205,0,9,204,206,8,129);
INSERT INTO ezsearch_object_word_link VALUES (514,45,206,0,10,205,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (515,46,179,0,0,0,80,8,128);
INSERT INTO ezsearch_object_word_link VALUES (516,46,80,0,1,179,108,8,128);
INSERT INTO ezsearch_object_word_link VALUES (517,46,108,0,2,80,207,8,128);
INSERT INTO ezsearch_object_word_link VALUES (518,46,207,0,3,108,208,8,128);
INSERT INTO ezsearch_object_word_link VALUES (519,46,208,0,4,207,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (520,48,209,0,0,0,0,12,133);
INSERT INTO ezsearch_object_word_link VALUES (521,47,179,0,0,0,108,8,128);
INSERT INTO ezsearch_object_word_link VALUES (522,47,108,0,1,179,210,8,128);
INSERT INTO ezsearch_object_word_link VALUES (523,47,210,0,2,108,189,8,128);
INSERT INTO ezsearch_object_word_link VALUES (524,47,189,0,3,210,211,8,128);
INSERT INTO ezsearch_object_word_link VALUES (525,47,211,0,4,189,212,8,129);
INSERT INTO ezsearch_object_word_link VALUES (526,47,212,0,5,211,213,8,129);
INSERT INTO ezsearch_object_word_link VALUES (527,47,213,0,6,212,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (528,49,9,0,0,0,0,13,134);
INSERT INTO ezsearch_object_word_link VALUES (529,50,9,0,0,0,0,13,134);
INSERT INTO ezsearch_object_word_link VALUES (530,51,9,0,0,0,214,14,135);
INSERT INTO ezsearch_object_word_link VALUES (531,51,214,0,1,9,0,14,135);
INSERT INTO ezsearch_object_word_link VALUES (532,52,9,0,0,0,0,15,136);
INSERT INTO ezsearch_object_word_link VALUES (533,53,9,0,0,0,0,19,139);
INSERT INTO ezsearch_object_word_link VALUES (534,57,215,0,0,0,0,20,140);
INSERT INTO ezsearch_object_word_link VALUES (535,58,216,0,0,0,0,21,141);
INSERT INTO ezsearch_object_word_link VALUES (536,59,12,0,0,0,13,2,120);
INSERT INTO ezsearch_object_word_link VALUES (537,59,13,0,1,12,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (538,59,14,0,2,13,15,2,120);
INSERT INTO ezsearch_object_word_link VALUES (539,59,15,0,3,14,16,2,120);
INSERT INTO ezsearch_object_word_link VALUES (540,59,16,0,4,15,17,2,120);
INSERT INTO ezsearch_object_word_link VALUES (541,59,17,0,5,16,18,2,120);
INSERT INTO ezsearch_object_word_link VALUES (542,59,18,0,6,17,19,2,120);
INSERT INTO ezsearch_object_word_link VALUES (543,59,19,0,7,18,20,2,120);
INSERT INTO ezsearch_object_word_link VALUES (544,59,20,0,8,19,21,2,120);
INSERT INTO ezsearch_object_word_link VALUES (545,59,21,0,9,20,22,2,120);
INSERT INTO ezsearch_object_word_link VALUES (546,59,22,0,10,21,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (547,59,23,0,11,22,24,2,120);
INSERT INTO ezsearch_object_word_link VALUES (548,59,24,0,12,23,25,2,120);
INSERT INTO ezsearch_object_word_link VALUES (549,59,25,0,13,24,26,2,120);
INSERT INTO ezsearch_object_word_link VALUES (550,59,26,0,14,25,27,2,120);
INSERT INTO ezsearch_object_word_link VALUES (551,59,27,0,15,26,28,2,120);
INSERT INTO ezsearch_object_word_link VALUES (552,59,28,0,16,27,29,2,120);
INSERT INTO ezsearch_object_word_link VALUES (553,59,29,0,17,28,30,2,120);
INSERT INTO ezsearch_object_word_link VALUES (554,59,30,0,18,29,31,2,120);
INSERT INTO ezsearch_object_word_link VALUES (555,59,31,0,19,30,32,2,120);
INSERT INTO ezsearch_object_word_link VALUES (556,59,32,0,20,31,33,2,120);
INSERT INTO ezsearch_object_word_link VALUES (557,59,33,0,21,32,34,2,120);
INSERT INTO ezsearch_object_word_link VALUES (558,59,34,0,22,33,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (559,59,23,0,23,34,35,2,120);
INSERT INTO ezsearch_object_word_link VALUES (560,59,35,0,24,23,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (561,59,36,0,25,35,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (562,59,37,0,26,36,38,2,120);
INSERT INTO ezsearch_object_word_link VALUES (563,59,38,0,27,37,39,2,120);
INSERT INTO ezsearch_object_word_link VALUES (564,59,39,0,28,38,40,2,120);
INSERT INTO ezsearch_object_word_link VALUES (565,59,40,0,29,39,41,2,120);
INSERT INTO ezsearch_object_word_link VALUES (566,59,41,0,30,40,42,2,120);
INSERT INTO ezsearch_object_word_link VALUES (567,59,42,0,31,41,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (568,59,43,0,32,42,44,2,120);
INSERT INTO ezsearch_object_word_link VALUES (569,59,44,0,33,43,45,2,120);
INSERT INTO ezsearch_object_word_link VALUES (570,59,45,0,34,44,46,2,120);
INSERT INTO ezsearch_object_word_link VALUES (571,59,46,0,35,45,47,2,120);
INSERT INTO ezsearch_object_word_link VALUES (572,59,47,0,36,46,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (573,59,37,0,37,47,48,2,120);
INSERT INTO ezsearch_object_word_link VALUES (574,59,48,0,38,37,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (575,59,36,0,39,48,49,2,120);
INSERT INTO ezsearch_object_word_link VALUES (576,59,49,0,40,36,50,2,120);
INSERT INTO ezsearch_object_word_link VALUES (577,59,50,0,41,49,51,2,120);
INSERT INTO ezsearch_object_word_link VALUES (578,59,51,0,42,50,52,2,120);
INSERT INTO ezsearch_object_word_link VALUES (579,59,52,0,43,51,53,2,120);
INSERT INTO ezsearch_object_word_link VALUES (580,59,53,0,44,52,54,2,120);
INSERT INTO ezsearch_object_word_link VALUES (581,59,54,0,45,53,55,2,120);
INSERT INTO ezsearch_object_word_link VALUES (582,59,55,0,46,54,56,2,120);
INSERT INTO ezsearch_object_word_link VALUES (583,59,56,0,47,55,57,2,120);
INSERT INTO ezsearch_object_word_link VALUES (584,59,57,0,48,56,58,2,120);
INSERT INTO ezsearch_object_word_link VALUES (585,59,58,0,49,57,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (586,59,59,0,50,58,60,2,120);
INSERT INTO ezsearch_object_word_link VALUES (587,59,60,0,51,59,61,2,120);
INSERT INTO ezsearch_object_word_link VALUES (588,59,61,0,52,60,62,2,120);
INSERT INTO ezsearch_object_word_link VALUES (589,59,62,0,53,61,12,2,120);
INSERT INTO ezsearch_object_word_link VALUES (590,59,12,0,54,62,13,2,120);
INSERT INTO ezsearch_object_word_link VALUES (591,59,13,0,55,12,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (592,59,14,0,56,13,15,2,120);
INSERT INTO ezsearch_object_word_link VALUES (593,59,15,0,57,14,16,2,120);
INSERT INTO ezsearch_object_word_link VALUES (594,59,16,0,58,15,17,2,120);
INSERT INTO ezsearch_object_word_link VALUES (595,59,17,0,59,16,18,2,120);
INSERT INTO ezsearch_object_word_link VALUES (596,59,18,0,60,17,19,2,120);
INSERT INTO ezsearch_object_word_link VALUES (597,59,19,0,61,18,20,2,120);
INSERT INTO ezsearch_object_word_link VALUES (598,59,20,0,62,19,21,2,120);
INSERT INTO ezsearch_object_word_link VALUES (599,59,21,0,63,20,22,2,120);
INSERT INTO ezsearch_object_word_link VALUES (600,59,22,0,64,21,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (601,59,23,0,65,22,24,2,120);
INSERT INTO ezsearch_object_word_link VALUES (602,59,24,0,66,23,25,2,120);
INSERT INTO ezsearch_object_word_link VALUES (603,59,25,0,67,24,26,2,120);
INSERT INTO ezsearch_object_word_link VALUES (604,59,26,0,68,25,27,2,120);
INSERT INTO ezsearch_object_word_link VALUES (605,59,27,0,69,26,28,2,120);
INSERT INTO ezsearch_object_word_link VALUES (606,59,28,0,70,27,29,2,120);
INSERT INTO ezsearch_object_word_link VALUES (607,59,29,0,71,28,30,2,120);
INSERT INTO ezsearch_object_word_link VALUES (608,59,30,0,72,29,31,2,120);
INSERT INTO ezsearch_object_word_link VALUES (609,59,31,0,73,30,32,2,120);
INSERT INTO ezsearch_object_word_link VALUES (610,59,32,0,74,31,33,2,120);
INSERT INTO ezsearch_object_word_link VALUES (611,59,33,0,75,32,34,2,120);
INSERT INTO ezsearch_object_word_link VALUES (612,59,34,0,76,33,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (613,59,23,0,77,34,35,2,120);
INSERT INTO ezsearch_object_word_link VALUES (614,59,35,0,78,23,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (615,59,36,0,79,35,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (616,59,37,0,80,36,38,2,120);
INSERT INTO ezsearch_object_word_link VALUES (617,59,38,0,81,37,39,2,120);
INSERT INTO ezsearch_object_word_link VALUES (618,59,39,0,82,38,40,2,120);
INSERT INTO ezsearch_object_word_link VALUES (619,59,40,0,83,39,41,2,120);
INSERT INTO ezsearch_object_word_link VALUES (620,59,41,0,84,40,42,2,120);
INSERT INTO ezsearch_object_word_link VALUES (621,59,42,0,85,41,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (622,59,43,0,86,42,44,2,120);
INSERT INTO ezsearch_object_word_link VALUES (623,59,44,0,87,43,45,2,120);
INSERT INTO ezsearch_object_word_link VALUES (624,59,45,0,88,44,46,2,120);
INSERT INTO ezsearch_object_word_link VALUES (625,59,46,0,89,45,47,2,120);
INSERT INTO ezsearch_object_word_link VALUES (626,59,47,0,90,46,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (627,59,37,0,91,47,48,2,120);
INSERT INTO ezsearch_object_word_link VALUES (628,59,48,0,92,37,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (629,59,36,0,93,48,49,2,120);
INSERT INTO ezsearch_object_word_link VALUES (630,59,49,0,94,36,50,2,120);
INSERT INTO ezsearch_object_word_link VALUES (631,59,50,0,95,49,51,2,120);
INSERT INTO ezsearch_object_word_link VALUES (632,59,51,0,96,50,52,2,120);
INSERT INTO ezsearch_object_word_link VALUES (633,59,52,0,97,51,53,2,120);
INSERT INTO ezsearch_object_word_link VALUES (634,59,53,0,98,52,54,2,120);
INSERT INTO ezsearch_object_word_link VALUES (635,59,54,0,99,53,55,2,120);
INSERT INTO ezsearch_object_word_link VALUES (636,59,55,0,100,54,56,2,120);
INSERT INTO ezsearch_object_word_link VALUES (637,59,56,0,101,55,57,2,120);
INSERT INTO ezsearch_object_word_link VALUES (638,59,57,0,102,56,58,2,120);
INSERT INTO ezsearch_object_word_link VALUES (639,59,58,0,103,57,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (640,59,59,0,104,58,60,2,120);
INSERT INTO ezsearch_object_word_link VALUES (641,59,60,0,105,59,61,2,120);
INSERT INTO ezsearch_object_word_link VALUES (642,59,61,0,106,60,62,2,120);
INSERT INTO ezsearch_object_word_link VALUES (643,59,62,0,107,61,12,2,120);
INSERT INTO ezsearch_object_word_link VALUES (644,59,12,0,108,62,13,2,120);
INSERT INTO ezsearch_object_word_link VALUES (645,59,13,0,109,12,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (646,59,14,0,110,13,15,2,120);
INSERT INTO ezsearch_object_word_link VALUES (647,59,15,0,111,14,16,2,120);
INSERT INTO ezsearch_object_word_link VALUES (648,59,16,0,112,15,17,2,120);
INSERT INTO ezsearch_object_word_link VALUES (649,59,17,0,113,16,18,2,120);
INSERT INTO ezsearch_object_word_link VALUES (650,59,18,0,114,17,19,2,120);
INSERT INTO ezsearch_object_word_link VALUES (651,59,19,0,115,18,20,2,120);
INSERT INTO ezsearch_object_word_link VALUES (652,59,20,0,116,19,21,2,120);
INSERT INTO ezsearch_object_word_link VALUES (653,59,21,0,117,20,22,2,120);
INSERT INTO ezsearch_object_word_link VALUES (654,59,22,0,118,21,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (655,59,23,0,119,22,24,2,120);
INSERT INTO ezsearch_object_word_link VALUES (656,59,24,0,120,23,25,2,120);
INSERT INTO ezsearch_object_word_link VALUES (657,59,25,0,121,24,26,2,120);
INSERT INTO ezsearch_object_word_link VALUES (658,59,26,0,122,25,27,2,120);
INSERT INTO ezsearch_object_word_link VALUES (659,59,27,0,123,26,28,2,120);
INSERT INTO ezsearch_object_word_link VALUES (660,59,28,0,124,27,29,2,120);
INSERT INTO ezsearch_object_word_link VALUES (661,59,29,0,125,28,30,2,120);
INSERT INTO ezsearch_object_word_link VALUES (662,59,30,0,126,29,31,2,120);
INSERT INTO ezsearch_object_word_link VALUES (663,59,31,0,127,30,32,2,120);
INSERT INTO ezsearch_object_word_link VALUES (664,59,32,0,128,31,33,2,120);
INSERT INTO ezsearch_object_word_link VALUES (665,59,33,0,129,32,34,2,120);
INSERT INTO ezsearch_object_word_link VALUES (666,59,34,0,130,33,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (667,59,23,0,131,34,35,2,120);
INSERT INTO ezsearch_object_word_link VALUES (668,59,35,0,132,23,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (669,59,36,0,133,35,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (670,59,37,0,134,36,38,2,120);
INSERT INTO ezsearch_object_word_link VALUES (671,59,38,0,135,37,39,2,120);
INSERT INTO ezsearch_object_word_link VALUES (672,59,39,0,136,38,40,2,120);
INSERT INTO ezsearch_object_word_link VALUES (673,59,40,0,137,39,41,2,120);
INSERT INTO ezsearch_object_word_link VALUES (674,59,41,0,138,40,42,2,120);
INSERT INTO ezsearch_object_word_link VALUES (675,59,42,0,139,41,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (676,59,43,0,140,42,44,2,120);
INSERT INTO ezsearch_object_word_link VALUES (677,59,44,0,141,43,45,2,120);
INSERT INTO ezsearch_object_word_link VALUES (678,59,45,0,142,44,46,2,120);
INSERT INTO ezsearch_object_word_link VALUES (679,59,46,0,143,45,47,2,120);
INSERT INTO ezsearch_object_word_link VALUES (680,59,47,0,144,46,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (681,59,37,0,145,47,48,2,120);
INSERT INTO ezsearch_object_word_link VALUES (682,59,48,0,146,37,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (683,59,36,0,147,48,49,2,120);
INSERT INTO ezsearch_object_word_link VALUES (684,59,49,0,148,36,50,2,120);
INSERT INTO ezsearch_object_word_link VALUES (685,59,50,0,149,49,51,2,120);
INSERT INTO ezsearch_object_word_link VALUES (686,59,51,0,150,50,52,2,120);
INSERT INTO ezsearch_object_word_link VALUES (687,59,52,0,151,51,53,2,120);
INSERT INTO ezsearch_object_word_link VALUES (688,59,53,0,152,52,54,2,120);
INSERT INTO ezsearch_object_word_link VALUES (689,59,54,0,153,53,55,2,120);
INSERT INTO ezsearch_object_word_link VALUES (690,59,55,0,154,54,56,2,120);
INSERT INTO ezsearch_object_word_link VALUES (691,59,56,0,155,55,57,2,120);
INSERT INTO ezsearch_object_word_link VALUES (692,59,57,0,156,56,58,2,120);
INSERT INTO ezsearch_object_word_link VALUES (693,59,58,0,157,57,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (694,59,59,0,158,58,60,2,120);
INSERT INTO ezsearch_object_word_link VALUES (695,59,60,0,159,59,61,2,120);
INSERT INTO ezsearch_object_word_link VALUES (696,59,61,0,160,60,62,2,120);
INSERT INTO ezsearch_object_word_link VALUES (697,59,62,0,161,61,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (698,59,59,0,162,62,63,2,120);
INSERT INTO ezsearch_object_word_link VALUES (699,59,63,0,163,59,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (700,59,43,0,164,63,64,2,120);
INSERT INTO ezsearch_object_word_link VALUES (701,59,64,0,165,43,65,2,120);
INSERT INTO ezsearch_object_word_link VALUES (702,59,65,0,166,64,66,2,120);
INSERT INTO ezsearch_object_word_link VALUES (703,59,66,0,167,65,67,2,120);
INSERT INTO ezsearch_object_word_link VALUES (704,59,67,0,168,66,68,2,120);
INSERT INTO ezsearch_object_word_link VALUES (705,59,68,0,169,67,69,2,120);
INSERT INTO ezsearch_object_word_link VALUES (706,59,69,0,170,68,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (707,59,14,0,171,69,12,2,121);
INSERT INTO ezsearch_object_word_link VALUES (708,59,12,0,172,14,13,2,121);
INSERT INTO ezsearch_object_word_link VALUES (709,59,13,0,173,12,14,2,121);
INSERT INTO ezsearch_object_word_link VALUES (710,59,14,0,174,13,15,2,121);
INSERT INTO ezsearch_object_word_link VALUES (711,59,15,0,175,14,16,2,121);
INSERT INTO ezsearch_object_word_link VALUES (712,59,16,0,176,15,17,2,121);
INSERT INTO ezsearch_object_word_link VALUES (713,59,17,0,177,16,18,2,121);
INSERT INTO ezsearch_object_word_link VALUES (714,59,18,0,178,17,19,2,121);
INSERT INTO ezsearch_object_word_link VALUES (715,59,19,0,179,18,20,2,121);
INSERT INTO ezsearch_object_word_link VALUES (716,59,20,0,180,19,21,2,121);
INSERT INTO ezsearch_object_word_link VALUES (717,59,21,0,181,20,22,2,121);
INSERT INTO ezsearch_object_word_link VALUES (718,59,22,0,182,21,23,2,121);
INSERT INTO ezsearch_object_word_link VALUES (719,59,23,0,183,22,24,2,121);
INSERT INTO ezsearch_object_word_link VALUES (720,59,24,0,184,23,25,2,121);
INSERT INTO ezsearch_object_word_link VALUES (721,59,25,0,185,24,26,2,121);
INSERT INTO ezsearch_object_word_link VALUES (722,59,26,0,186,25,27,2,121);
INSERT INTO ezsearch_object_word_link VALUES (723,59,27,0,187,26,28,2,121);
INSERT INTO ezsearch_object_word_link VALUES (724,59,28,0,188,27,29,2,121);
INSERT INTO ezsearch_object_word_link VALUES (725,59,29,0,189,28,30,2,121);
INSERT INTO ezsearch_object_word_link VALUES (726,59,30,0,190,29,31,2,121);
INSERT INTO ezsearch_object_word_link VALUES (727,59,31,0,191,30,32,2,121);
INSERT INTO ezsearch_object_word_link VALUES (728,59,32,0,192,31,33,2,121);
INSERT INTO ezsearch_object_word_link VALUES (729,59,33,0,193,32,34,2,121);
INSERT INTO ezsearch_object_word_link VALUES (730,59,34,0,194,33,23,2,121);
INSERT INTO ezsearch_object_word_link VALUES (731,59,23,0,195,34,35,2,121);
INSERT INTO ezsearch_object_word_link VALUES (732,59,35,0,196,23,36,2,121);
INSERT INTO ezsearch_object_word_link VALUES (733,59,36,0,197,35,37,2,121);
INSERT INTO ezsearch_object_word_link VALUES (734,59,37,0,198,36,38,2,121);
INSERT INTO ezsearch_object_word_link VALUES (735,59,38,0,199,37,39,2,121);
INSERT INTO ezsearch_object_word_link VALUES (736,59,39,0,200,38,40,2,121);
INSERT INTO ezsearch_object_word_link VALUES (737,59,40,0,201,39,41,2,121);
INSERT INTO ezsearch_object_word_link VALUES (738,59,41,0,202,40,42,2,121);
INSERT INTO ezsearch_object_word_link VALUES (739,59,42,0,203,41,43,2,121);
INSERT INTO ezsearch_object_word_link VALUES (740,59,43,0,204,42,44,2,121);
INSERT INTO ezsearch_object_word_link VALUES (741,59,44,0,205,43,45,2,121);
INSERT INTO ezsearch_object_word_link VALUES (742,59,45,0,206,44,46,2,121);
INSERT INTO ezsearch_object_word_link VALUES (743,59,46,0,207,45,47,2,121);
INSERT INTO ezsearch_object_word_link VALUES (744,59,47,0,208,46,37,2,121);
INSERT INTO ezsearch_object_word_link VALUES (745,59,37,0,209,47,48,2,121);
INSERT INTO ezsearch_object_word_link VALUES (746,59,48,0,210,37,36,2,121);
INSERT INTO ezsearch_object_word_link VALUES (747,59,36,0,211,48,49,2,121);
INSERT INTO ezsearch_object_word_link VALUES (748,59,49,0,212,36,50,2,121);
INSERT INTO ezsearch_object_word_link VALUES (749,59,50,0,213,49,51,2,121);
INSERT INTO ezsearch_object_word_link VALUES (750,59,51,0,214,50,52,2,121);
INSERT INTO ezsearch_object_word_link VALUES (751,59,52,0,215,51,53,2,121);
INSERT INTO ezsearch_object_word_link VALUES (752,59,53,0,216,52,54,2,121);
INSERT INTO ezsearch_object_word_link VALUES (753,59,54,0,217,53,55,2,121);
INSERT INTO ezsearch_object_word_link VALUES (754,59,55,0,218,54,56,2,121);
INSERT INTO ezsearch_object_word_link VALUES (755,59,56,0,219,55,57,2,121);
INSERT INTO ezsearch_object_word_link VALUES (756,59,57,0,220,56,58,2,121);
INSERT INTO ezsearch_object_word_link VALUES (757,59,58,0,221,57,59,2,121);
INSERT INTO ezsearch_object_word_link VALUES (758,59,59,0,222,58,60,2,121);
INSERT INTO ezsearch_object_word_link VALUES (759,59,60,0,223,59,61,2,121);
INSERT INTO ezsearch_object_word_link VALUES (760,59,61,0,224,60,62,2,121);
INSERT INTO ezsearch_object_word_link VALUES (761,59,62,0,225,61,59,2,121);
INSERT INTO ezsearch_object_word_link VALUES (762,59,59,0,226,62,63,2,121);
INSERT INTO ezsearch_object_word_link VALUES (763,59,63,0,227,59,43,2,121);
INSERT INTO ezsearch_object_word_link VALUES (764,59,43,0,228,63,64,2,121);
INSERT INTO ezsearch_object_word_link VALUES (765,59,64,0,229,43,65,2,121);
INSERT INTO ezsearch_object_word_link VALUES (766,59,65,0,230,64,66,2,121);
INSERT INTO ezsearch_object_word_link VALUES (767,59,66,0,231,65,67,2,121);
INSERT INTO ezsearch_object_word_link VALUES (768,59,67,0,232,66,68,2,121);
INSERT INTO ezsearch_object_word_link VALUES (769,59,68,0,233,67,69,2,121);
INSERT INTO ezsearch_object_word_link VALUES (770,59,69,0,234,68,70,2,121);
INSERT INTO ezsearch_object_word_link VALUES (771,59,70,0,235,69,0,2,123);
INSERT INTO ezsearch_object_word_link VALUES (772,60,199,0,0,0,108,2,120);
INSERT INTO ezsearch_object_word_link VALUES (773,60,108,0,1,199,217,2,120);
INSERT INTO ezsearch_object_word_link VALUES (774,60,217,0,2,108,218,2,120);
INSERT INTO ezsearch_object_word_link VALUES (775,60,218,0,3,217,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (776,60,219,0,4,218,220,2,120);
INSERT INTO ezsearch_object_word_link VALUES (777,60,220,0,5,219,221,2,120);
INSERT INTO ezsearch_object_word_link VALUES (778,60,221,0,6,220,222,2,120);
INSERT INTO ezsearch_object_word_link VALUES (779,60,222,0,7,221,223,2,120);
INSERT INTO ezsearch_object_word_link VALUES (780,60,223,0,8,222,224,2,120);
INSERT INTO ezsearch_object_word_link VALUES (781,60,224,0,9,223,225,2,120);
INSERT INTO ezsearch_object_word_link VALUES (782,60,225,0,10,224,191,2,120);
INSERT INTO ezsearch_object_word_link VALUES (783,60,191,0,11,225,226,2,120);
INSERT INTO ezsearch_object_word_link VALUES (784,60,226,0,12,191,227,2,120);
INSERT INTO ezsearch_object_word_link VALUES (785,60,227,0,13,226,228,2,120);
INSERT INTO ezsearch_object_word_link VALUES (786,60,228,0,14,227,203,2,120);
INSERT INTO ezsearch_object_word_link VALUES (787,60,203,0,15,228,229,2,120);
INSERT INTO ezsearch_object_word_link VALUES (788,60,229,0,16,203,230,2,120);
INSERT INTO ezsearch_object_word_link VALUES (789,60,230,0,17,229,231,2,120);
INSERT INTO ezsearch_object_word_link VALUES (790,60,231,0,18,230,232,2,120);
INSERT INTO ezsearch_object_word_link VALUES (791,60,232,0,19,231,233,2,120);
INSERT INTO ezsearch_object_word_link VALUES (792,60,233,0,20,232,234,2,120);
INSERT INTO ezsearch_object_word_link VALUES (793,60,234,0,21,233,210,2,120);
INSERT INTO ezsearch_object_word_link VALUES (794,60,210,0,22,234,235,2,120);
INSERT INTO ezsearch_object_word_link VALUES (795,60,235,0,23,210,236,2,120);
INSERT INTO ezsearch_object_word_link VALUES (796,60,236,0,24,235,237,2,120);
INSERT INTO ezsearch_object_word_link VALUES (797,60,237,0,25,236,191,2,120);
INSERT INTO ezsearch_object_word_link VALUES (798,60,191,0,26,237,238,2,120);
INSERT INTO ezsearch_object_word_link VALUES (799,60,238,0,27,191,194,2,120);
INSERT INTO ezsearch_object_word_link VALUES (800,60,194,0,28,238,239,2,120);
INSERT INTO ezsearch_object_word_link VALUES (801,60,239,0,29,194,240,2,120);
INSERT INTO ezsearch_object_word_link VALUES (802,60,240,0,30,239,241,2,120);
INSERT INTO ezsearch_object_word_link VALUES (803,60,241,0,31,240,242,2,120);
INSERT INTO ezsearch_object_word_link VALUES (804,60,242,0,32,241,243,2,120);
INSERT INTO ezsearch_object_word_link VALUES (805,60,243,0,33,242,191,2,120);
INSERT INTO ezsearch_object_word_link VALUES (806,60,191,0,34,243,244,2,120);
INSERT INTO ezsearch_object_word_link VALUES (807,60,244,0,35,191,199,2,120);
INSERT INTO ezsearch_object_word_link VALUES (808,60,199,0,36,244,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (809,60,219,0,37,199,245,2,120);
INSERT INTO ezsearch_object_word_link VALUES (810,60,245,0,38,219,246,2,120);
INSERT INTO ezsearch_object_word_link VALUES (811,60,246,0,39,245,199,2,121);
INSERT INTO ezsearch_object_word_link VALUES (812,60,199,0,40,246,108,2,121);
INSERT INTO ezsearch_object_word_link VALUES (813,60,108,0,41,199,217,2,121);
INSERT INTO ezsearch_object_word_link VALUES (814,60,217,0,42,108,218,2,121);
INSERT INTO ezsearch_object_word_link VALUES (815,60,218,0,43,217,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (816,60,219,0,44,218,220,2,121);
INSERT INTO ezsearch_object_word_link VALUES (817,60,220,0,45,219,221,2,121);
INSERT INTO ezsearch_object_word_link VALUES (818,60,221,0,46,220,222,2,121);
INSERT INTO ezsearch_object_word_link VALUES (819,60,222,0,47,221,223,2,121);
INSERT INTO ezsearch_object_word_link VALUES (820,60,223,0,48,222,224,2,121);
INSERT INTO ezsearch_object_word_link VALUES (821,60,224,0,49,223,225,2,121);
INSERT INTO ezsearch_object_word_link VALUES (822,60,225,0,50,224,191,2,121);
INSERT INTO ezsearch_object_word_link VALUES (823,60,191,0,51,225,226,2,121);
INSERT INTO ezsearch_object_word_link VALUES (824,60,226,0,52,191,227,2,121);
INSERT INTO ezsearch_object_word_link VALUES (825,60,227,0,53,226,228,2,121);
INSERT INTO ezsearch_object_word_link VALUES (826,60,228,0,54,227,203,2,121);
INSERT INTO ezsearch_object_word_link VALUES (827,60,203,0,55,228,229,2,121);
INSERT INTO ezsearch_object_word_link VALUES (828,60,229,0,56,203,230,2,121);
INSERT INTO ezsearch_object_word_link VALUES (829,60,230,0,57,229,231,2,121);
INSERT INTO ezsearch_object_word_link VALUES (830,60,231,0,58,230,232,2,121);
INSERT INTO ezsearch_object_word_link VALUES (831,60,232,0,59,231,233,2,121);
INSERT INTO ezsearch_object_word_link VALUES (832,60,233,0,60,232,234,2,121);
INSERT INTO ezsearch_object_word_link VALUES (833,60,234,0,61,233,210,2,121);
INSERT INTO ezsearch_object_word_link VALUES (834,60,210,0,62,234,235,2,121);
INSERT INTO ezsearch_object_word_link VALUES (835,60,235,0,63,210,236,2,121);
INSERT INTO ezsearch_object_word_link VALUES (836,60,236,0,64,235,237,2,121);
INSERT INTO ezsearch_object_word_link VALUES (837,60,237,0,65,236,191,2,121);
INSERT INTO ezsearch_object_word_link VALUES (838,60,191,0,66,237,238,2,121);
INSERT INTO ezsearch_object_word_link VALUES (839,60,238,0,67,191,194,2,121);
INSERT INTO ezsearch_object_word_link VALUES (840,60,194,0,68,238,239,2,121);
INSERT INTO ezsearch_object_word_link VALUES (841,60,239,0,69,194,240,2,121);
INSERT INTO ezsearch_object_word_link VALUES (842,60,240,0,70,239,241,2,121);
INSERT INTO ezsearch_object_word_link VALUES (843,60,241,0,71,240,242,2,121);
INSERT INTO ezsearch_object_word_link VALUES (844,60,242,0,72,241,243,2,121);
INSERT INTO ezsearch_object_word_link VALUES (845,60,243,0,73,242,191,2,121);
INSERT INTO ezsearch_object_word_link VALUES (846,60,191,0,74,243,244,2,121);
INSERT INTO ezsearch_object_word_link VALUES (847,60,244,0,75,191,199,2,121);
INSERT INTO ezsearch_object_word_link VALUES (848,60,199,0,76,244,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (849,60,219,0,77,199,245,2,121);
INSERT INTO ezsearch_object_word_link VALUES (850,60,245,0,78,219,70,2,121);
INSERT INTO ezsearch_object_word_link VALUES (851,60,70,0,79,245,0,2,123);
INSERT INTO ezsearch_object_word_link VALUES (852,61,12,0,0,0,13,2,120);
INSERT INTO ezsearch_object_word_link VALUES (853,61,13,0,1,12,14,2,120);
INSERT INTO ezsearch_object_word_link VALUES (854,61,14,0,2,13,15,2,120);
INSERT INTO ezsearch_object_word_link VALUES (855,61,15,0,3,14,16,2,120);
INSERT INTO ezsearch_object_word_link VALUES (856,61,16,0,4,15,17,2,120);
INSERT INTO ezsearch_object_word_link VALUES (857,61,17,0,5,16,18,2,120);
INSERT INTO ezsearch_object_word_link VALUES (858,61,18,0,6,17,19,2,120);
INSERT INTO ezsearch_object_word_link VALUES (859,61,19,0,7,18,20,2,120);
INSERT INTO ezsearch_object_word_link VALUES (860,61,20,0,8,19,21,2,120);
INSERT INTO ezsearch_object_word_link VALUES (861,61,21,0,9,20,22,2,120);
INSERT INTO ezsearch_object_word_link VALUES (862,61,22,0,10,21,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (863,61,23,0,11,22,24,2,120);
INSERT INTO ezsearch_object_word_link VALUES (864,61,24,0,12,23,25,2,120);
INSERT INTO ezsearch_object_word_link VALUES (865,61,25,0,13,24,26,2,120);
INSERT INTO ezsearch_object_word_link VALUES (866,61,26,0,14,25,27,2,120);
INSERT INTO ezsearch_object_word_link VALUES (867,61,27,0,15,26,28,2,120);
INSERT INTO ezsearch_object_word_link VALUES (868,61,28,0,16,27,29,2,120);
INSERT INTO ezsearch_object_word_link VALUES (869,61,29,0,17,28,30,2,120);
INSERT INTO ezsearch_object_word_link VALUES (870,61,30,0,18,29,31,2,120);
INSERT INTO ezsearch_object_word_link VALUES (871,61,31,0,19,30,32,2,120);
INSERT INTO ezsearch_object_word_link VALUES (872,61,32,0,20,31,33,2,120);
INSERT INTO ezsearch_object_word_link VALUES (873,61,33,0,21,32,34,2,120);
INSERT INTO ezsearch_object_word_link VALUES (874,61,34,0,22,33,23,2,120);
INSERT INTO ezsearch_object_word_link VALUES (875,61,23,0,23,34,35,2,120);
INSERT INTO ezsearch_object_word_link VALUES (876,61,35,0,24,23,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (877,61,36,0,25,35,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (878,61,37,0,26,36,38,2,120);
INSERT INTO ezsearch_object_word_link VALUES (879,61,38,0,27,37,39,2,120);
INSERT INTO ezsearch_object_word_link VALUES (880,61,39,0,28,38,40,2,120);
INSERT INTO ezsearch_object_word_link VALUES (881,61,40,0,29,39,41,2,120);
INSERT INTO ezsearch_object_word_link VALUES (882,61,41,0,30,40,42,2,120);
INSERT INTO ezsearch_object_word_link VALUES (883,61,42,0,31,41,43,2,120);
INSERT INTO ezsearch_object_word_link VALUES (884,61,43,0,32,42,44,2,120);
INSERT INTO ezsearch_object_word_link VALUES (885,61,44,0,33,43,45,2,120);
INSERT INTO ezsearch_object_word_link VALUES (886,61,45,0,34,44,46,2,120);
INSERT INTO ezsearch_object_word_link VALUES (887,61,46,0,35,45,47,2,120);
INSERT INTO ezsearch_object_word_link VALUES (888,61,47,0,36,46,37,2,120);
INSERT INTO ezsearch_object_word_link VALUES (889,61,37,0,37,47,48,2,120);
INSERT INTO ezsearch_object_word_link VALUES (890,61,48,0,38,37,36,2,120);
INSERT INTO ezsearch_object_word_link VALUES (891,61,36,0,39,48,49,2,120);
INSERT INTO ezsearch_object_word_link VALUES (892,61,49,0,40,36,50,2,120);
INSERT INTO ezsearch_object_word_link VALUES (893,61,50,0,41,49,51,2,120);
INSERT INTO ezsearch_object_word_link VALUES (894,61,51,0,42,50,52,2,120);
INSERT INTO ezsearch_object_word_link VALUES (895,61,52,0,43,51,53,2,120);
INSERT INTO ezsearch_object_word_link VALUES (896,61,53,0,44,52,54,2,120);
INSERT INTO ezsearch_object_word_link VALUES (897,61,54,0,45,53,55,2,120);
INSERT INTO ezsearch_object_word_link VALUES (898,61,55,0,46,54,56,2,120);
INSERT INTO ezsearch_object_word_link VALUES (899,61,56,0,47,55,57,2,120);
INSERT INTO ezsearch_object_word_link VALUES (900,61,57,0,48,56,58,2,120);
INSERT INTO ezsearch_object_word_link VALUES (901,61,58,0,49,57,59,2,120);
INSERT INTO ezsearch_object_word_link VALUES (902,61,59,0,50,58,60,2,120);
INSERT INTO ezsearch_object_word_link VALUES (903,61,60,0,51,59,61,2,120);
INSERT INTO ezsearch_object_word_link VALUES (904,61,61,0,52,60,62,2,120);
INSERT INTO ezsearch_object_word_link VALUES (905,61,62,0,53,61,247,2,120);
INSERT INTO ezsearch_object_word_link VALUES (906,61,247,0,54,62,248,2,121);
INSERT INTO ezsearch_object_word_link VALUES (907,61,248,0,55,247,204,2,121);
INSERT INTO ezsearch_object_word_link VALUES (908,61,204,0,56,248,249,2,121);
INSERT INTO ezsearch_object_word_link VALUES (909,61,249,0,57,204,250,2,121);
INSERT INTO ezsearch_object_word_link VALUES (910,61,250,0,58,249,191,2,121);
INSERT INTO ezsearch_object_word_link VALUES (911,61,191,0,59,250,251,2,121);
INSERT INTO ezsearch_object_word_link VALUES (912,61,251,0,60,191,252,2,121);
INSERT INTO ezsearch_object_word_link VALUES (913,61,252,0,61,251,253,2,121);
INSERT INTO ezsearch_object_word_link VALUES (914,61,253,0,62,252,254,2,121);
INSERT INTO ezsearch_object_word_link VALUES (915,61,254,0,63,253,255,2,121);
INSERT INTO ezsearch_object_word_link VALUES (916,61,255,0,64,254,11,2,121);
INSERT INTO ezsearch_object_word_link VALUES (917,61,11,0,65,255,256,2,121);
INSERT INTO ezsearch_object_word_link VALUES (918,61,256,0,66,11,257,2,121);
INSERT INTO ezsearch_object_word_link VALUES (919,61,257,0,67,256,258,2,121);
INSERT INTO ezsearch_object_word_link VALUES (920,61,258,0,68,257,259,2,121);
INSERT INTO ezsearch_object_word_link VALUES (921,61,259,0,69,258,243,2,121);
INSERT INTO ezsearch_object_word_link VALUES (922,61,243,0,70,259,260,2,121);
INSERT INTO ezsearch_object_word_link VALUES (923,61,260,0,71,243,191,2,121);
INSERT INTO ezsearch_object_word_link VALUES (924,61,191,0,72,260,261,2,121);
INSERT INTO ezsearch_object_word_link VALUES (925,61,261,0,73,191,262,2,121);
INSERT INTO ezsearch_object_word_link VALUES (926,61,262,0,74,261,263,2,121);
INSERT INTO ezsearch_object_word_link VALUES (927,61,263,0,75,262,264,2,121);
INSERT INTO ezsearch_object_word_link VALUES (928,61,264,0,76,263,234,2,121);
INSERT INTO ezsearch_object_word_link VALUES (929,61,234,0,77,264,256,2,121);
INSERT INTO ezsearch_object_word_link VALUES (930,61,256,0,78,234,70,2,121);
INSERT INTO ezsearch_object_word_link VALUES (931,61,70,0,79,256,0,2,123);
INSERT INTO ezsearch_object_word_link VALUES (932,62,11,0,0,0,265,1,4);
INSERT INTO ezsearch_object_word_link VALUES (933,62,265,0,1,11,266,1,4);
INSERT INTO ezsearch_object_word_link VALUES (934,62,266,0,2,265,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (935,63,267,0,0,0,268,1,4);
INSERT INTO ezsearch_object_word_link VALUES (936,63,268,0,1,267,269,1,4);
INSERT INTO ezsearch_object_word_link VALUES (937,63,269,0,2,268,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (938,64,270,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (939,65,271,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (940,66,272,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (941,67,269,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (942,68,273,0,0,0,274,22,142);
INSERT INTO ezsearch_object_word_link VALUES (943,68,274,0,1,273,206,22,142);
INSERT INTO ezsearch_object_word_link VALUES (944,68,206,0,2,274,275,22,143);
INSERT INTO ezsearch_object_word_link VALUES (945,68,275,0,3,206,276,22,143);
INSERT INTO ezsearch_object_word_link VALUES (946,68,276,0,4,275,277,22,144);
INSERT INTO ezsearch_object_word_link VALUES (947,68,277,0,5,276,278,22,144);
INSERT INTO ezsearch_object_word_link VALUES (948,68,278,0,6,277,279,22,145);
INSERT INTO ezsearch_object_word_link VALUES (949,68,279,0,7,278,59,22,145);
INSERT INTO ezsearch_object_word_link VALUES (950,68,59,0,8,279,280,22,145);
INSERT INTO ezsearch_object_word_link VALUES (951,68,280,0,9,59,281,22,145);
INSERT INTO ezsearch_object_word_link VALUES (952,68,281,0,10,280,30,22,145);
INSERT INTO ezsearch_object_word_link VALUES (953,68,30,0,11,281,45,22,145);
INSERT INTO ezsearch_object_word_link VALUES (954,68,45,0,12,30,57,22,145);
INSERT INTO ezsearch_object_word_link VALUES (955,68,57,0,13,45,282,22,145);
INSERT INTO ezsearch_object_word_link VALUES (956,68,282,0,14,57,283,22,145);
INSERT INTO ezsearch_object_word_link VALUES (957,68,283,0,15,282,284,22,145);
INSERT INTO ezsearch_object_word_link VALUES (958,68,284,0,16,283,30,22,145);
INSERT INTO ezsearch_object_word_link VALUES (959,68,30,0,17,284,285,22,145);
INSERT INTO ezsearch_object_word_link VALUES (960,68,285,0,18,30,286,22,145);
INSERT INTO ezsearch_object_word_link VALUES (961,68,286,0,19,285,287,22,145);
INSERT INTO ezsearch_object_word_link VALUES (962,68,287,0,20,286,162,22,145);
INSERT INTO ezsearch_object_word_link VALUES (963,68,162,0,21,287,43,22,145);
INSERT INTO ezsearch_object_word_link VALUES (964,68,43,0,22,162,288,22,145);
INSERT INTO ezsearch_object_word_link VALUES (965,68,288,0,23,43,289,22,145);
INSERT INTO ezsearch_object_word_link VALUES (966,68,289,0,24,288,23,22,145);
INSERT INTO ezsearch_object_word_link VALUES (967,68,23,0,25,289,290,22,145);
INSERT INTO ezsearch_object_word_link VALUES (968,68,290,0,26,23,291,22,145);
INSERT INTO ezsearch_object_word_link VALUES (969,68,291,0,27,290,206,22,145);
INSERT INTO ezsearch_object_word_link VALUES (970,68,206,0,28,291,206,22,145);
INSERT INTO ezsearch_object_word_link VALUES (971,68,206,0,29,206,275,22,145);
INSERT INTO ezsearch_object_word_link VALUES (972,68,275,0,30,206,292,22,145);
INSERT INTO ezsearch_object_word_link VALUES (973,68,292,0,31,275,293,22,145);
INSERT INTO ezsearch_object_word_link VALUES (974,68,293,0,32,292,294,22,145);
INSERT INTO ezsearch_object_word_link VALUES (975,68,294,0,33,293,88,22,145);
INSERT INTO ezsearch_object_word_link VALUES (976,68,88,0,34,294,295,22,145);
INSERT INTO ezsearch_object_word_link VALUES (977,68,295,0,35,88,296,22,145);
INSERT INTO ezsearch_object_word_link VALUES (978,68,296,0,36,295,91,22,145);
INSERT INTO ezsearch_object_word_link VALUES (979,68,91,0,37,296,297,22,145);
INSERT INTO ezsearch_object_word_link VALUES (980,68,297,0,38,91,298,22,145);
INSERT INTO ezsearch_object_word_link VALUES (981,68,298,0,39,297,152,22,145);
INSERT INTO ezsearch_object_word_link VALUES (982,68,152,0,40,298,299,22,145);
INSERT INTO ezsearch_object_word_link VALUES (983,68,299,0,41,152,300,22,145);
INSERT INTO ezsearch_object_word_link VALUES (984,68,300,0,42,299,301,22,145);
INSERT INTO ezsearch_object_word_link VALUES (985,68,301,0,43,300,302,22,145);
INSERT INTO ezsearch_object_word_link VALUES (986,68,302,0,44,301,27,22,145);
INSERT INTO ezsearch_object_word_link VALUES (987,68,27,0,45,302,303,22,145);
INSERT INTO ezsearch_object_word_link VALUES (988,68,303,0,46,27,109,22,145);
INSERT INTO ezsearch_object_word_link VALUES (989,68,109,0,47,303,304,22,145);
INSERT INTO ezsearch_object_word_link VALUES (990,68,304,0,48,109,305,22,145);
INSERT INTO ezsearch_object_word_link VALUES (991,68,305,0,49,304,306,22,145);
INSERT INTO ezsearch_object_word_link VALUES (992,68,306,0,50,305,307,22,145);
INSERT INTO ezsearch_object_word_link VALUES (993,68,307,0,51,306,308,22,145);
INSERT INTO ezsearch_object_word_link VALUES (994,68,308,0,52,307,45,22,145);
INSERT INTO ezsearch_object_word_link VALUES (995,68,45,0,53,308,206,22,145);
INSERT INTO ezsearch_object_word_link VALUES (996,68,206,0,54,45,309,22,145);
INSERT INTO ezsearch_object_word_link VALUES (997,68,309,0,55,206,310,22,145);
INSERT INTO ezsearch_object_word_link VALUES (998,68,310,0,56,309,311,22,145);
INSERT INTO ezsearch_object_word_link VALUES (999,68,311,0,57,310,312,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1000,68,312,0,58,311,313,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1001,68,313,0,59,312,314,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1002,68,314,0,60,313,301,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1003,68,301,0,61,314,315,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1004,68,315,0,62,301,316,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1005,68,316,0,63,315,279,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1006,68,279,0,64,316,317,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1007,68,317,0,65,279,318,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1008,68,318,0,66,317,319,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1009,68,319,0,67,318,69,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1010,68,69,0,68,319,134,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1011,68,134,0,69,69,320,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1012,68,320,0,70,134,321,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1013,68,321,0,71,320,322,22,146);
INSERT INTO ezsearch_object_word_link VALUES (1014,68,322,0,72,321,323,22,146);
INSERT INTO ezsearch_object_word_link VALUES (1015,68,323,0,73,322,324,22,146);
INSERT INTO ezsearch_object_word_link VALUES (1016,68,324,0,74,323,325,22,146);
INSERT INTO ezsearch_object_word_link VALUES (1017,68,325,0,75,324,326,22,146);
INSERT INTO ezsearch_object_word_link VALUES (1018,68,326,0,76,325,327,22,147);
INSERT INTO ezsearch_object_word_link VALUES (1019,68,327,0,77,326,0,22,147);
INSERT INTO ezsearch_object_word_link VALUES (1020,69,11,0,0,0,328,22,142);
INSERT INTO ezsearch_object_word_link VALUES (1021,69,328,0,1,11,206,22,142);
INSERT INTO ezsearch_object_word_link VALUES (1022,69,206,0,2,328,309,22,143);
INSERT INTO ezsearch_object_word_link VALUES (1023,69,309,0,3,206,278,22,143);
INSERT INTO ezsearch_object_word_link VALUES (1024,69,278,0,4,309,279,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1025,69,279,0,5,278,59,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1026,69,59,0,6,279,280,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1027,69,280,0,7,59,281,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1028,69,281,0,8,280,30,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1029,69,30,0,9,281,45,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1030,69,45,0,10,30,57,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1031,69,57,0,11,45,282,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1032,69,282,0,12,57,283,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1033,69,283,0,13,282,284,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1034,69,284,0,14,283,30,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1035,69,30,0,15,284,285,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1036,69,285,0,16,30,286,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1037,69,286,0,17,285,287,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1038,69,287,0,18,286,162,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1039,69,162,0,19,287,43,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1040,69,43,0,20,162,288,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1041,69,288,0,21,43,289,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1042,69,289,0,22,288,23,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1043,69,23,0,23,289,290,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1044,69,290,0,24,23,291,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1045,69,291,0,25,290,292,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1046,69,292,0,26,291,293,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1047,69,293,0,27,292,294,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1048,69,294,0,28,293,88,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1049,69,88,0,29,294,295,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1050,69,295,0,30,88,296,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1051,69,296,0,31,295,91,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1052,69,91,0,32,296,297,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1053,69,297,0,33,91,298,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1054,69,298,0,34,297,152,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1055,69,152,0,35,298,299,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1056,69,299,0,36,152,300,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1057,69,300,0,37,299,301,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1058,69,301,0,38,300,302,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1059,69,302,0,39,301,27,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1060,69,27,0,40,302,303,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1061,69,303,0,41,27,109,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1062,69,109,0,42,303,304,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1063,69,304,0,43,109,305,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1064,69,305,0,44,304,306,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1065,69,306,0,45,305,307,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1066,69,307,0,46,306,308,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1067,69,308,0,47,307,45,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1068,69,45,0,48,308,206,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1069,69,206,0,49,45,309,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1070,69,309,0,50,206,310,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1071,69,310,0,51,309,311,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1072,69,311,0,52,310,312,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1073,69,312,0,53,311,313,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1074,69,313,0,54,312,314,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1075,69,314,0,55,313,301,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1076,69,301,0,56,314,315,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1077,69,315,0,57,301,316,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1078,69,316,0,58,315,279,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1079,69,279,0,59,316,317,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1080,69,317,0,60,279,318,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1081,69,318,0,61,317,319,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1082,69,319,0,62,318,69,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1083,69,69,0,63,319,134,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1084,69,134,0,64,69,320,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1085,69,320,0,65,134,329,22,145);
INSERT INTO ezsearch_object_word_link VALUES (1086,69,329,0,66,320,330,22,146);
INSERT INTO ezsearch_object_word_link VALUES (1087,69,330,0,67,329,331,22,147);
INSERT INTO ezsearch_object_word_link VALUES (1088,69,331,0,68,330,0,22,147);
INSERT INTO ezsearch_object_word_link VALUES (1089,70,332,0,0,0,333,23,149);
INSERT INTO ezsearch_object_word_link VALUES (1090,70,333,0,1,332,334,23,151);
INSERT INTO ezsearch_object_word_link VALUES (1091,70,334,0,2,333,335,23,152);
INSERT INTO ezsearch_object_word_link VALUES (1092,70,335,0,3,334,286,23,152);
INSERT INTO ezsearch_object_word_link VALUES (1093,70,286,0,4,335,287,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1094,70,287,0,5,286,162,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1095,70,162,0,6,287,43,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1096,70,43,0,7,162,288,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1097,70,288,0,8,43,289,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1098,70,289,0,9,288,23,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1099,70,23,0,10,289,290,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1100,70,290,0,11,23,291,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1101,70,291,0,12,290,206,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1102,70,206,0,13,291,206,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1103,70,206,0,14,206,275,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1104,70,275,0,15,206,292,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1105,70,292,0,16,275,293,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1106,70,293,0,17,292,294,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1107,70,294,0,18,293,88,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1108,70,88,0,19,294,295,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1109,70,295,0,20,88,296,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1110,70,296,0,21,295,91,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1111,70,91,0,22,296,297,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1112,70,297,0,23,91,298,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1113,70,298,0,24,297,152,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1114,70,152,0,25,298,299,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1115,70,299,0,26,152,300,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1116,70,300,0,27,299,301,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1117,70,301,0,28,300,302,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1118,70,302,0,29,301,27,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1119,70,27,0,30,302,303,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1120,70,303,0,31,27,109,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1121,70,109,0,32,303,304,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1122,70,304,0,33,109,305,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1123,70,305,0,34,304,306,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1124,70,306,0,35,305,307,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1125,70,307,0,36,306,308,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1126,70,308,0,37,307,45,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1127,70,45,0,38,308,206,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1128,70,206,0,39,45,309,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1129,70,309,0,40,206,310,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1130,70,310,0,41,309,311,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1131,70,311,0,42,310,312,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1132,70,312,0,43,311,313,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1133,70,313,0,44,312,314,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1134,70,314,0,45,313,301,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1135,70,301,0,46,314,315,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1136,70,315,0,47,301,316,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1137,70,316,0,48,315,279,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1138,70,279,0,49,316,317,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1139,70,317,0,50,279,318,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1140,70,318,0,51,317,319,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1141,70,319,0,52,318,69,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1142,70,69,0,53,319,134,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1143,70,134,0,54,69,320,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1144,70,320,0,55,134,336,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1145,70,336,0,56,320,337,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1146,70,337,0,57,336,338,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1147,70,338,0,58,337,339,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1148,70,339,0,59,338,340,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1149,70,340,0,60,339,341,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1150,70,341,0,61,340,342,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1151,70,342,0,62,341,343,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1152,70,343,0,63,342,293,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1153,70,293,0,64,343,69,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1154,70,69,0,65,293,344,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1155,70,344,0,66,69,345,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1156,70,345,0,67,344,346,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1157,70,346,0,68,345,347,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1158,70,347,0,69,346,348,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1159,70,348,0,70,347,18,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1160,70,18,0,71,348,349,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1161,70,349,0,72,18,350,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1162,70,350,0,73,349,351,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1163,70,351,0,74,350,352,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1164,70,352,0,75,351,37,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1165,70,37,0,76,352,135,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1166,70,135,0,77,37,106,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1167,70,106,0,78,135,353,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1168,70,353,0,79,106,318,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1169,70,318,0,80,353,301,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1170,70,301,0,81,318,354,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1171,70,354,0,82,301,355,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1172,70,355,0,83,354,356,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1173,70,356,0,84,355,357,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1174,70,357,0,85,356,358,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1175,70,358,0,86,357,359,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1176,70,359,0,87,358,338,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1177,70,338,0,88,359,360,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1178,70,360,0,89,338,141,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1179,70,141,0,90,360,361,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1180,70,361,0,91,141,362,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1181,70,362,0,92,361,162,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1182,70,162,0,93,362,363,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1183,70,363,0,94,162,364,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1184,70,364,0,95,363,365,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1185,70,365,0,96,364,43,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1186,70,43,0,97,365,28,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1187,70,28,0,98,43,366,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1188,70,366,0,99,28,181,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1189,70,181,0,100,366,367,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1190,70,367,0,101,181,181,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1191,70,181,0,102,367,368,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1192,70,368,0,103,181,134,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1193,70,134,0,104,368,369,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1194,70,369,0,105,134,43,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1195,70,43,0,106,369,370,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1196,70,370,0,107,43,371,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1197,70,371,0,108,370,363,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1198,70,363,0,109,371,372,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1199,70,372,0,110,363,373,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1200,70,373,0,111,372,306,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1201,70,306,0,112,373,374,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1202,70,374,0,113,306,375,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1203,70,375,0,114,374,376,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1204,70,376,0,115,375,377,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1205,70,377,0,116,376,378,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1206,70,378,0,117,377,379,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1207,70,379,0,118,378,380,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1208,70,380,0,119,379,381,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1209,70,381,0,120,380,382,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1210,70,382,0,121,381,162,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1211,70,162,0,122,382,304,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1212,70,304,0,123,162,383,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1213,70,383,0,124,304,181,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1214,70,181,0,125,383,384,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1215,70,384,0,126,181,385,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1216,70,385,0,127,384,386,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1217,70,386,0,128,385,387,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1218,70,387,0,129,386,388,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1219,70,388,0,130,387,389,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1220,70,389,0,131,388,390,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1221,70,390,0,132,389,391,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1222,70,391,0,133,390,352,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1223,70,352,0,134,391,296,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1224,70,296,0,135,352,392,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1225,70,392,0,136,296,393,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1226,70,393,0,137,392,394,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1227,70,394,0,138,393,378,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1228,70,378,0,139,394,395,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1229,70,395,0,140,378,86,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1230,70,86,0,141,395,378,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1231,70,378,0,142,86,396,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1232,70,396,0,143,378,397,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1233,70,397,0,144,396,398,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1234,70,398,0,145,397,399,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1235,70,399,0,146,398,400,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1236,70,400,0,147,399,401,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1237,70,401,0,148,400,402,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1238,70,402,0,149,401,348,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1239,70,348,0,150,402,206,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1240,70,206,0,151,348,403,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1241,70,403,0,152,206,404,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1242,70,404,0,153,403,405,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1243,70,405,0,154,404,48,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1244,70,48,0,155,405,406,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1245,70,406,0,156,48,407,23,153);
INSERT INTO ezsearch_object_word_link VALUES (1246,70,407,0,157,406,0,23,153);
INSERT INTO ezsearch_object_word_link VALUES (2065,81,743,0,15,194,744,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2064,81,194,0,14,742,743,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2063,81,742,0,13,741,194,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2062,81,741,0,12,181,742,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2061,81,181,0,11,738,741,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2060,81,738,0,10,331,181,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2059,81,331,0,9,737,738,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2058,81,737,0,8,740,331,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2057,81,740,0,7,546,737,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2056,81,546,0,6,608,740,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2055,81,608,0,5,707,546,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2054,81,707,0,4,739,608,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2053,81,739,0,3,636,707,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2052,81,636,0,2,738,739,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2051,81,738,0,1,737,636,2,1);
INSERT INTO ezsearch_object_word_link VALUES (2050,81,737,0,0,0,738,2,1);
INSERT INTO ezsearch_object_word_link VALUES (2049,80,736,0,61,735,0,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2048,80,735,0,60,734,736,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2047,80,734,0,59,733,735,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2046,80,733,0,58,200,734,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2045,80,200,0,57,254,733,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2044,80,254,0,56,735,200,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2043,80,735,0,55,734,254,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2042,80,734,0,54,733,735,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2041,80,733,0,53,200,734,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2040,80,200,0,52,254,733,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2039,80,254,0,51,735,200,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2038,80,735,0,50,734,254,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2037,80,734,0,49,733,735,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2036,80,733,0,48,200,734,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2035,80,200,0,47,254,733,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2034,80,254,0,46,732,200,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2033,80,732,0,45,731,254,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2032,80,731,0,44,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2031,80,732,0,43,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2030,80,731,0,42,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2029,80,732,0,41,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2028,80,731,0,40,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2027,80,732,0,39,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2026,80,731,0,38,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2025,80,732,0,37,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2024,80,731,0,36,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2023,80,732,0,35,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2022,80,731,0,34,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2021,80,732,0,33,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2020,80,731,0,32,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2019,80,732,0,31,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2018,80,731,0,30,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2017,80,732,0,29,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2016,80,731,0,28,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2015,80,732,0,27,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2014,80,731,0,26,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2013,80,732,0,25,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2012,80,731,0,24,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2011,80,732,0,23,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2010,80,731,0,22,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2009,80,732,0,21,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2008,80,731,0,20,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2007,80,732,0,19,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2006,80,731,0,18,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2005,80,732,0,17,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2004,80,731,0,16,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2003,80,732,0,15,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2002,80,731,0,14,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2001,80,732,0,13,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2000,80,731,0,12,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1999,80,732,0,11,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1998,80,731,0,10,732,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1997,80,732,0,9,731,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1996,80,731,0,8,132,732,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1995,80,132,0,7,546,731,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1994,80,546,0,6,108,132,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1993,80,108,0,5,179,546,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1992,80,179,0,4,132,108,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1991,80,132,0,3,172,179,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1990,80,172,0,2,183,132,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1989,80,183,0,1,182,172,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1988,80,182,0,0,0,183,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1983,29,76,0,0,0,8,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1965,79,189,0,7,177,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1964,79,177,0,6,11,189,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1963,79,11,0,5,108,177,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1962,79,108,0,4,179,11,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1961,79,179,0,3,197,108,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1960,79,197,0,2,715,179,8,128);
INSERT INTO ezsearch_object_word_link VALUES (1959,79,715,0,1,191,197,8,128);
INSERT INTO ezsearch_object_word_link VALUES (1958,79,191,0,0,0,715,8,128);
INSERT INTO ezsearch_object_word_link VALUES (1957,78,70,0,9,172,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1956,78,172,0,8,10,70,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1955,78,10,0,7,178,172,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1954,78,178,0,6,177,10,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1953,78,177,0,5,11,178,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1952,78,11,0,4,108,177,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1951,78,108,0,3,179,11,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1950,78,179,0,2,178,108,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1949,78,178,0,1,177,179,8,128);
INSERT INTO ezsearch_object_word_link VALUES (1946,74,132,0,2,172,0,8,129);
INSERT INTO ezsearch_object_word_link VALUES (1945,74,172,0,1,80,132,8,128);
INSERT INTO ezsearch_object_word_link VALUES (1944,74,80,0,0,0,172,8,128);
INSERT INTO ezsearch_object_word_link VALUES (1943,72,722,0,38,721,0,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1942,72,721,0,37,720,722,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1904,73,6,0,17,703,0,2,123);
INSERT INTO ezsearch_object_word_link VALUES (1903,73,703,0,16,688,6,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1902,73,688,0,15,702,703,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1901,73,702,0,14,219,688,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1900,73,219,0,13,701,702,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1899,73,701,0,12,201,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1898,73,201,0,11,243,701,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1897,73,243,0,10,693,201,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1896,73,693,0,9,700,243,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1895,73,700,0,8,239,693,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1894,73,239,0,7,686,700,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1893,73,686,0,6,699,239,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1892,73,699,0,5,219,686,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1891,73,219,0,4,698,699,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1890,73,698,0,3,108,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1889,73,108,0,2,203,698,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1888,73,203,0,1,247,108,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1887,73,247,0,0,0,203,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1941,72,720,0,36,191,721,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1940,72,191,0,35,719,720,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1939,72,719,0,34,693,191,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1938,72,693,0,33,718,719,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1937,72,718,0,32,717,693,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1936,72,717,0,31,11,718,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1935,72,11,0,30,181,717,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1934,72,181,0,29,233,11,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1933,72,233,0,28,716,181,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1932,72,716,0,27,591,233,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1931,72,591,0,26,715,716,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1930,72,715,0,25,243,591,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1929,72,243,0,24,688,715,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1928,72,688,0,23,714,243,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1927,72,714,0,22,243,688,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1926,72,243,0,21,239,714,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1925,72,239,0,20,686,243,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1924,72,686,0,19,713,239,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1923,72,713,0,18,219,686,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1922,72,219,0,17,712,713,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1921,72,712,0,16,108,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1920,72,108,0,15,203,712,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1919,72,203,0,14,711,108,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1918,72,711,0,13,181,203,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1917,72,181,0,12,710,711,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1916,72,710,0,11,709,181,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1915,72,709,0,10,219,710,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1914,72,219,0,9,599,709,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1913,72,599,0,8,708,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1912,72,708,0,7,108,599,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1911,72,108,0,6,707,708,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1910,72,707,0,5,704,108,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1909,72,704,0,4,706,707,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1908,72,706,0,3,705,704,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1907,72,705,0,2,108,706,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1906,72,108,0,1,704,705,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1905,72,704,0,0,0,108,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1812,71,6,0,204,587,0,2,123);
INSERT INTO ezsearch_object_word_link VALUES (1811,71,587,0,203,653,6,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1810,71,653,0,202,655,587,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1809,71,655,0,201,654,653,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1808,71,654,0,200,653,655,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1807,71,653,0,199,652,654,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1806,71,652,0,198,639,653,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1805,71,639,0,197,651,652,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1804,71,651,0,196,650,639,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1803,71,650,0,195,243,651,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1802,71,243,0,194,649,650,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1801,71,649,0,193,648,243,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1800,71,648,0,192,546,649,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1799,71,546,0,191,261,648,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1798,71,261,0,190,647,546,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1797,71,647,0,189,251,261,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1796,71,251,0,188,607,647,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1795,71,607,0,187,646,251,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1794,71,646,0,186,645,607,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1793,71,645,0,185,204,646,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1792,71,204,0,184,582,645,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1791,71,582,0,183,179,204,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1790,71,179,0,182,181,582,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1789,71,181,0,181,644,179,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1788,71,644,0,180,184,181,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1787,71,184,0,179,643,644,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1786,71,643,0,178,251,184,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1785,71,251,0,177,532,643,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1784,71,532,0,176,642,251,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1783,71,642,0,175,641,532,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1782,71,641,0,174,234,642,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1781,71,234,0,173,640,641,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1780,71,640,0,172,639,234,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1779,71,639,0,171,638,640,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1778,71,638,0,170,637,639,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1777,71,637,0,169,636,638,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1776,71,636,0,168,222,637,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1775,71,222,0,167,636,636,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1774,71,636,0,166,227,222,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1773,71,227,0,165,108,636,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1772,71,108,0,164,194,227,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1771,71,194,0,163,634,108,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1770,71,634,0,162,635,194,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1769,71,635,0,161,219,634,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1768,71,219,0,160,198,635,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1767,71,198,0,159,634,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1766,71,634,0,158,633,198,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1765,71,633,0,157,261,634,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1764,71,261,0,156,591,633,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1763,71,591,0,155,219,261,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1762,71,219,0,154,632,591,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1761,71,632,0,153,631,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1760,71,631,0,152,219,632,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1759,71,219,0,151,630,631,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1758,71,630,0,150,108,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1757,71,108,0,149,203,630,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1756,71,203,0,148,629,108,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1755,71,629,0,147,247,203,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1754,71,247,0,146,586,629,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1753,71,586,0,145,251,247,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1752,71,251,0,144,587,586,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1751,71,587,0,143,628,251,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1750,71,628,0,142,627,587,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1749,71,627,0,141,626,628,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1748,71,626,0,140,625,627,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1747,71,625,0,139,546,626,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1746,71,546,0,138,204,625,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1745,71,204,0,137,203,546,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1744,71,203,0,136,624,204,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1743,71,624,0,135,623,203,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1742,71,623,0,134,8,624,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1741,71,8,0,133,219,623,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1740,71,219,0,132,622,8,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1739,71,622,0,131,621,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1738,71,621,0,130,578,622,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1737,71,578,0,129,620,621,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1736,71,620,0,128,619,578,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1735,71,619,0,127,618,620,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1734,71,618,0,126,578,619,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1733,71,578,0,125,237,618,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1732,71,237,0,124,617,578,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1731,71,617,0,123,616,237,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1730,71,616,0,122,615,617,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1729,71,615,0,121,548,616,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1728,71,548,0,120,614,615,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1727,71,614,0,119,613,548,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1726,71,613,0,118,537,614,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1725,71,537,0,117,612,613,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1724,71,612,0,116,611,537,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1723,71,611,0,115,610,612,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1722,71,610,0,114,609,611,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1721,71,609,0,113,608,610,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1720,71,608,0,112,607,609,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1719,71,607,0,111,237,608,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1718,71,237,0,110,606,607,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1717,71,606,0,109,605,237,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1716,71,605,0,108,581,606,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1715,71,581,0,107,219,605,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1714,71,219,0,106,251,581,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1713,71,251,0,105,604,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1712,71,604,0,104,603,251,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1711,71,603,0,103,194,604,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1710,71,194,0,102,602,603,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1709,71,602,0,101,601,194,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1708,71,601,0,100,600,602,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1707,71,600,0,99,11,601,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1706,71,11,0,98,599,600,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1705,71,599,0,97,219,11,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1704,71,219,0,96,598,599,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1703,71,598,0,95,597,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1702,71,597,0,94,596,598,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1701,71,596,0,93,595,597,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1700,71,595,0,92,594,596,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1699,71,594,0,91,76,595,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1698,71,76,0,90,11,594,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1697,71,11,0,89,593,76,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1696,71,593,0,88,563,11,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1695,71,563,0,87,592,593,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1694,71,592,0,86,11,563,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1693,71,11,0,85,591,592,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1692,71,591,0,84,561,11,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1691,71,561,0,83,203,591,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1690,71,203,0,82,590,561,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1689,71,590,0,81,545,203,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1688,71,545,0,80,179,590,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1687,71,179,0,79,589,545,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1686,71,589,0,78,588,179,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1685,71,588,0,77,204,589,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1684,71,204,0,76,587,588,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1683,71,587,0,75,586,204,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1682,71,586,0,74,251,587,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1681,71,251,0,73,585,586,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1680,71,585,0,72,532,251,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1679,71,532,0,71,584,585,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1678,71,584,0,70,536,532,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1677,71,536,0,69,583,584,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1676,71,583,0,68,582,536,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1675,71,582,0,67,544,583,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1674,71,544,0,66,581,582,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1673,71,581,0,65,11,544,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1672,71,11,0,64,580,581,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1671,71,580,0,63,579,11,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1670,71,579,0,62,578,580,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1669,71,578,0,61,577,579,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1668,71,577,0,60,576,578,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1667,71,576,0,59,575,577,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1666,71,575,0,58,574,576,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1665,71,574,0,57,573,575,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1664,71,573,0,56,28,574,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1663,71,28,0,55,572,573,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1662,71,572,0,54,219,28,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1661,71,219,0,53,571,572,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1660,71,571,0,52,570,219,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1659,71,570,0,51,569,571,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1658,71,569,0,50,568,570,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1657,71,568,0,49,567,569,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1656,71,567,0,48,203,568,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1655,71,203,0,47,566,567,2,121);
INSERT INTO ezsearch_object_word_link VALUES (1654,71,566,0,46,565,203,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1653,71,565,0,45,564,566,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1652,71,564,0,44,563,565,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1651,71,563,0,43,562,564,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1650,71,562,0,42,561,563,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1649,71,561,0,41,560,562,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1648,71,560,0,40,553,561,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1647,71,553,0,39,559,560,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1646,71,559,0,38,181,553,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1645,71,181,0,37,558,559,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1644,71,558,0,36,557,181,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1643,71,557,0,35,219,558,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1642,71,219,0,34,556,557,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1641,71,556,0,33,555,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1640,71,555,0,32,554,556,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1639,71,554,0,31,234,555,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1638,71,234,0,30,553,554,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1637,71,553,0,29,552,234,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1636,71,552,0,28,11,553,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1635,71,11,0,27,551,552,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1634,71,551,0,26,234,11,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1633,71,234,0,25,550,551,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1632,71,550,0,24,549,234,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1631,71,549,0,23,548,550,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1630,71,548,0,22,219,549,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1629,71,219,0,21,547,548,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1628,71,547,0,20,546,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1627,71,546,0,19,181,547,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1626,71,181,0,18,545,546,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1625,71,545,0,17,544,181,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1624,71,544,0,16,543,545,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1623,71,543,0,15,11,544,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1622,71,11,0,14,219,543,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1621,71,219,0,13,542,11,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1620,71,542,0,12,541,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1619,71,541,0,11,219,542,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1618,71,219,0,10,540,541,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1617,71,540,0,9,539,219,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1616,71,539,0,8,233,540,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1615,71,233,0,7,538,539,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1614,71,538,0,6,537,233,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1613,71,537,0,5,536,538,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1612,71,536,0,4,535,537,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1611,71,535,0,3,534,536,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1610,71,534,0,2,533,535,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1609,71,533,0,1,532,534,2,120);
INSERT INTO ezsearch_object_word_link VALUES (1608,71,532,0,0,0,533,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2066,81,744,0,16,743,745,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2067,81,745,0,17,744,247,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2068,81,247,0,18,745,746,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2069,81,746,0,19,247,201,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2070,81,201,0,20,746,747,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2071,81,747,0,21,201,636,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2072,81,636,0,22,747,739,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2073,81,739,0,23,636,220,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2074,81,220,0,24,739,203,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2075,81,203,0,25,220,598,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2076,81,598,0,26,203,544,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2077,81,544,0,27,598,748,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2078,81,748,0,28,544,77,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2079,81,77,0,29,748,0,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2080,82,721,0,0,0,749,2,1);
INSERT INTO ezsearch_object_word_link VALUES (2081,82,749,0,1,721,750,2,1);
INSERT INTO ezsearch_object_word_link VALUES (2082,82,750,0,2,749,10,2,1);
INSERT INTO ezsearch_object_word_link VALUES (2083,82,10,0,3,750,230,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2084,82,230,0,4,10,751,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2085,82,751,0,5,230,198,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2086,82,198,0,6,751,591,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2087,82,591,0,7,198,752,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2088,82,752,0,8,591,753,2,120);
INSERT INTO ezsearch_object_word_link VALUES (2089,82,753,0,9,752,201,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2090,82,201,0,10,753,200,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2091,82,200,0,11,201,754,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2092,82,754,0,12,200,230,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2093,82,230,0,13,754,755,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2094,82,755,0,14,230,751,2,121);
INSERT INTO ezsearch_object_word_link VALUES (2095,82,751,0,15,755,0,2,121);

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

INSERT INTO ezsearch_word VALUES (1,'white',1);
INSERT INTO ezsearch_word VALUES (2,'box',1);
INSERT INTO ezsearch_word VALUES (3,'contemporary',1);
INSERT INTO ezsearch_word VALUES (4,'art',1);
INSERT INTO ezsearch_word VALUES (5,'gallery',3);
INSERT INTO ezsearch_word VALUES (6,'1',4);
INSERT INTO ezsearch_word VALUES (7,'2',1);
INSERT INTO ezsearch_word VALUES (8,'news',6);
INSERT INTO ezsearch_word VALUES (9,'folder',6);
INSERT INTO ezsearch_word VALUES (10,'with',3);
INSERT INTO ezsearch_word VALUES (11,'the',16);
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
INSERT INTO ezsearch_word VALUES (28,'an',11);
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
INSERT INTO ezsearch_word VALUES (70,'0',6);
INSERT INTO ezsearch_word VALUES (726,'hot',1);
INSERT INTO ezsearch_word VALUES (725,'frontpage',1);
INSERT INTO ezsearch_word VALUES (731,'lorem',19);
INSERT INTO ezsearch_word VALUES (728,'sport',2);
INSERT INTO ezsearch_word VALUES (75,'wheather',2);
INSERT INTO ezsearch_word VALUES (76,'world',4);
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
INSERT INTO ezsearch_word VALUES (108,'is',18);
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
INSERT INTO ezsearch_word VALUES (172,'3',5);
INSERT INTO ezsearch_word VALUES (173,'4',1);
INSERT INTO ezsearch_word VALUES (174,'no',1);
INSERT INTO ezsearch_word VALUES (175,'title',1);
INSERT INTO ezsearch_word VALUES (176,'default',2);
INSERT INTO ezsearch_word VALUES (177,'first',6);
INSERT INTO ezsearch_word VALUES (178,'post',4);
INSERT INTO ezsearch_word VALUES (179,'this',9);
INSERT INTO ezsearch_word VALUES (180,'ever',1);
INSERT INTO ezsearch_word VALUES (181,'in',10);
INSERT INTO ezsearch_word VALUES (182,'ez',2);
INSERT INTO ezsearch_word VALUES (183,'publish',2);
INSERT INTO ezsearch_word VALUES (184,'second',2);
INSERT INTO ezsearch_word VALUES (185,'topic',1);
INSERT INTO ezsearch_word VALUES (186,'testing',1);
INSERT INTO ezsearch_word VALUES (187,'brd',1);
INSERT INTO ezsearch_word VALUES (188,'rtyrh',1);
INSERT INTO ezsearch_word VALUES (189,'reply',3);
INSERT INTO ezsearch_word VALUES (190,'what',1);
INSERT INTO ezsearch_word VALUES (191,'i',12);
INSERT INTO ezsearch_word VALUES (192,'think',1);
INSERT INTO ezsearch_word VALUES (193,'about',1);
INSERT INTO ezsearch_word VALUES (194,'that',6);
INSERT INTO ezsearch_word VALUES (195,'b',1);
INSERT INTO ezsearch_word VALUES (196,'ethgheh',1);
INSERT INTO ezsearch_word VALUES (197,'agree',2);
INSERT INTO ezsearch_word VALUES (198,'but',3);
INSERT INTO ezsearch_word VALUES (199,'how',5);
INSERT INTO ezsearch_word VALUES (200,'can',5);
INSERT INTO ezsearch_word VALUES (201,'you',4);
INSERT INTO ezsearch_word VALUES (202,'know',1);
INSERT INTO ezsearch_word VALUES (203,'it',10);
INSERT INTO ezsearch_word VALUES (204,'s',5);
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
INSERT INTO ezsearch_word VALUES (219,'to',19);
INSERT INTO ezsearch_word VALUES (220,'make',3);
INSERT INTO ezsearch_word VALUES (221,'me',2);
INSERT INTO ezsearch_word VALUES (222,'feel',3);
INSERT INTO ezsearch_word VALUES (223,'smarter',2);
INSERT INTO ezsearch_word VALUES (224,'besides',2);
INSERT INTO ezsearch_word VALUES (225,'everytime',2);
INSERT INTO ezsearch_word VALUES (226,'learn',2);
INSERT INTO ezsearch_word VALUES (227,'something',3);
INSERT INTO ezsearch_word VALUES (228,'new',2);
INSERT INTO ezsearch_word VALUES (229,'pushes',2);
INSERT INTO ezsearch_word VALUES (230,'some',4);
INSERT INTO ezsearch_word VALUES (231,'old',2);
INSERT INTO ezsearch_word VALUES (232,'stuff',2);
INSERT INTO ezsearch_word VALUES (233,'out',4);
INSERT INTO ezsearch_word VALUES (234,'of',6);
INSERT INTO ezsearch_word VALUES (235,'brain',2);
INSERT INTO ezsearch_word VALUES (236,'remember',2);
INSERT INTO ezsearch_word VALUES (237,'when',4);
INSERT INTO ezsearch_word VALUES (238,'took',2);
INSERT INTO ezsearch_word VALUES (239,'home',4);
INSERT INTO ezsearch_word VALUES (240,'wine',2);
INSERT INTO ezsearch_word VALUES (241,'making',2);
INSERT INTO ezsearch_word VALUES (242,'course',2);
INSERT INTO ezsearch_word VALUES (243,'and',7);
INSERT INTO ezsearch_word VALUES (244,'forgot',2);
INSERT INTO ezsearch_word VALUES (245,'drive',2);
INSERT INTO ezsearch_word VALUES (246,'cool',1);
INSERT INTO ezsearch_word VALUES (247,'so',4);
INSERT INTO ezsearch_word VALUES (248,'let',1);
INSERT INTO ezsearch_word VALUES (249,'begin',1);
INSERT INTO ezsearch_word VALUES (250,'hi',1);
INSERT INTO ezsearch_word VALUES (251,'have',6);
INSERT INTO ezsearch_word VALUES (252,'added',1);
INSERT INTO ezsearch_word VALUES (253,'three',1);
INSERT INTO ezsearch_word VALUES (254,'tables',4);
INSERT INTO ezsearch_word VALUES (255,'into',1);
INSERT INTO ezsearch_word VALUES (256,'mysql',2);
INSERT INTO ezsearch_word VALUES (257,'database',1);
INSERT INTO ezsearch_word VALUES (258,'eznotification_rule',1);
INSERT INTO ezsearch_word VALUES (259,'eznotification_user_link',1);
INSERT INTO ezsearch_word VALUES (260,'ezmessage',1);
INSERT INTO ezsearch_word VALUES (261,'only',3);
INSERT INTO ezsearch_word VALUES (262,'changed',1);
INSERT INTO ezsearch_word VALUES (263,'kernel',1);
INSERT INTO ezsearch_word VALUES (264,'sql',1);
INSERT INTO ezsearch_word VALUES (265,'book',1);
INSERT INTO ezsearch_word VALUES (266,'corner',1);
INSERT INTO ezsearch_word VALUES (267,'top',1);
INSERT INTO ezsearch_word VALUES (268,'20',1);
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
INSERT INTO ezsearch_word VALUES (329,'10',1);
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
INSERT INTO ezsearch_word VALUES (357,'confirm',1);
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
INSERT INTO ezsearch_word VALUES (631,'other',1);
INSERT INTO ezsearch_word VALUES (630,'up',1);
INSERT INTO ezsearch_word VALUES (629,'dominant',1);
INSERT INTO ezsearch_word VALUES (628,'because',1);
INSERT INTO ezsearch_word VALUES (627,'reaction',1);
INSERT INTO ezsearch_word VALUES (626,'jerk',1);
INSERT INTO ezsearch_word VALUES (625,'knee',1);
INSERT INTO ezsearch_word VALUES (624,'afp',1);
INSERT INTO ezsearch_word VALUES (623,'agency',1);
INSERT INTO ezsearch_word VALUES (622,'according',1);
INSERT INTO ezsearch_word VALUES (621,'said',1);
INSERT INTO ezsearch_word VALUES (620,'henman',1);
INSERT INTO ezsearch_word VALUES (619,'tim',1);
INSERT INTO ezsearch_word VALUES (618,'meets',1);
INSERT INTO ezsearch_word VALUES (617,'racket',1);
INSERT INTO ezsearch_word VALUES (616,'his',1);
INSERT INTO ezsearch_word VALUES (615,'from',1);
INSERT INTO ezsearch_word VALUES (614,'strings',1);
INSERT INTO ezsearch_word VALUES (613,'two',1);
INSERT INTO ezsearch_word VALUES (612,'sampras',1);
INSERT INTO ezsearch_word VALUES (611,'pete',1);
INSERT INTO ezsearch_word VALUES (610,'or',1);
INSERT INTO ezsearch_word VALUES (609,'chelsea',1);
INSERT INTO ezsearch_word VALUES (608,'play',2);
INSERT INTO ezsearch_word VALUES (607,'they',2);
INSERT INTO ezsearch_word VALUES (606,'men',1);
INSERT INTO ezsearch_word VALUES (605,'nine',1);
INSERT INTO ezsearch_word VALUES (604,'arsenal',1);
INSERT INTO ezsearch_word VALUES (603,'mean',1);
INSERT INTO ezsearch_word VALUES (602,'does',1);
INSERT INTO ezsearch_word VALUES (601,'themselves',1);
INSERT INTO ezsearch_word VALUES (600,'gap',1);
INSERT INTO ezsearch_word VALUES (599,'close',2);
INSERT INTO ezsearch_word VALUES (598,'harder',2);
INSERT INTO ezsearch_word VALUES (597,'work',1);
INSERT INTO ezsearch_word VALUES (596,'must',1);
INSERT INTO ezsearch_word VALUES (595,'rivals',1);
INSERT INTO ezsearch_word VALUES (594,'champions',1);
INSERT INTO ezsearch_word VALUES (593,'saying',1);
INSERT INTO ezsearch_word VALUES (592,'way',1);
INSERT INTO ezsearch_word VALUES (591,'not',4);
INSERT INTO ezsearch_word VALUES (590,'insisted',1);
INSERT INTO ezsearch_word VALUES (589,'challengers',1);
INSERT INTO ezsearch_word VALUES (588,'closest',1);
INSERT INTO ezsearch_word VALUES (587,'ferrari',3);
INSERT INTO ezsearch_word VALUES (586,'been',2);
INSERT INTO ezsearch_word VALUES (585,'team',1);
INSERT INTO ezsearch_word VALUES (584,'whose',1);
INSERT INTO ezsearch_word VALUES (583,'however',1);
INSERT INTO ezsearch_word VALUES (582,'year',2);
INSERT INTO ezsearch_word VALUES (581,'field',2);
INSERT INTO ezsearch_word VALUES (580,'over',1);
INSERT INTO ezsearch_word VALUES (579,'held',1);
INSERT INTO ezsearch_word VALUES (578,'he',3);
INSERT INTO ezsearch_word VALUES (577,'point',1);
INSERT INTO ezsearch_word VALUES (576,'every',1);
INSERT INTO ezsearch_word VALUES (575,'for',1);
INSERT INTO ezsearch_word VALUES (574,'kilogramme',1);
INSERT INTO ezsearch_word VALUES (573,'extra',1);
INSERT INTO ezsearch_word VALUES (572,'carry',1);
INSERT INTO ezsearch_word VALUES (571,'forced',1);
INSERT INTO ezsearch_word VALUES (570,'schumacher',1);
INSERT INTO ezsearch_word VALUES (569,'michael',1);
INSERT INTO ezsearch_word VALUES (568,'see',1);
INSERT INTO ezsearch_word VALUES (567,'would',1);
INSERT INTO ezsearch_word VALUES (566,'ecclestone',1);
INSERT INTO ezsearch_word VALUES (565,'bernie',1);
INSERT INTO ezsearch_word VALUES (564,'by',1);
INSERT INTO ezsearch_word VALUES (563,'forward',2);
INSERT INTO ezsearch_word VALUES (562,'put',1);
INSERT INTO ezsearch_word VALUES (561,'was',2);
INSERT INTO ezsearch_word VALUES (560,'racing',1);
INSERT INTO ezsearch_word VALUES (559,'formula',1);
INSERT INTO ezsearch_word VALUES (558,'interest',1);
INSERT INTO ezsearch_word VALUES (557,'boost',1);
INSERT INTO ezsearch_word VALUES (556,'suggested',1);
INSERT INTO ezsearch_word VALUES (555,'being',1);
INSERT INTO ezsearch_word VALUES (554,'several',1);
INSERT INTO ezsearch_word VALUES (553,'one',2);
INSERT INTO ezsearch_word VALUES (552,'idea',1);
INSERT INTO ezsearch_word VALUES (551,'domination',1);
INSERT INTO ezsearch_word VALUES (550,'level',1);
INSERT INTO ezsearch_word VALUES (549,'their',1);
INSERT INTO ezsearch_word VALUES (548,'cut',2);
INSERT INTO ezsearch_word VALUES (547,'bid',1);
INSERT INTO ezsearch_word VALUES (546,'a',5);
INSERT INTO ezsearch_word VALUES (545,'season',2);
INSERT INTO ezsearch_word VALUES (544,'next',3);
INSERT INTO ezsearch_word VALUES (543,'ferraris',1);
INSERT INTO ezsearch_word VALUES (542,'ballast',1);
INSERT INTO ezsearch_word VALUES (541,'add',1);
INSERT INTO ezsearch_word VALUES (540,'proposals',1);
INSERT INTO ezsearch_word VALUES (539,'against',1);
INSERT INTO ezsearch_word VALUES (538,'spoken',1);
INSERT INTO ezsearch_word VALUES (537,'has',2);
INSERT INTO ezsearch_word VALUES (536,'head',2);
INSERT INTO ezsearch_word VALUES (535,'patrick',1);
INSERT INTO ezsearch_word VALUES (534,'director',1);
INSERT INTO ezsearch_word VALUES (533,'technical',1);
INSERT INTO ezsearch_word VALUES (532,'williams',3);
INSERT INTO ezsearch_word VALUES (634,'them',2);
INSERT INTO ezsearch_word VALUES (635,'overtake',1);
INSERT INTO ezsearch_word VALUES (636,'we',4);
INSERT INTO ezsearch_word VALUES (637,'are',1);
INSERT INTO ezsearch_word VALUES (638,'more',1);
INSERT INTO ezsearch_word VALUES (639,'than',2);
INSERT INTO ezsearch_word VALUES (640,'capable',1);
INSERT INTO ezsearch_word VALUES (641,'doing',1);
INSERT INTO ezsearch_word VALUES (642,'though',1);
INSERT INTO ezsearch_word VALUES (643,'secured',1);
INSERT INTO ezsearch_word VALUES (644,'place',1);
INSERT INTO ezsearch_word VALUES (645,'constructors',1);
INSERT INTO ezsearch_word VALUES (646,'championship',1);
INSERT INTO ezsearch_word VALUES (647,'won',1);
INSERT INTO ezsearch_word VALUES (648,'single',1);
INSERT INTO ezsearch_word VALUES (649,'race',1);
INSERT INTO ezsearch_word VALUES (650,'scored',1);
INSERT INTO ezsearch_word VALUES (651,'less',1);
INSERT INTO ezsearch_word VALUES (652,'half',1);
INSERT INTO ezsearch_word VALUES (653,'as',2);
INSERT INTO ezsearch_word VALUES (654,'many',1);
INSERT INTO ezsearch_word VALUES (655,'points',1);
INSERT INTO ezsearch_word VALUES (720,'m',1);
INSERT INTO ezsearch_word VALUES (719,'evening',1);
INSERT INTO ezsearch_word VALUES (693,'friday',2);
INSERT INTO ezsearch_word VALUES (718,'on',1);
INSERT INTO ezsearch_word VALUES (717,'street',1);
INSERT INTO ezsearch_word VALUES (716,'go',1);
INSERT INTO ezsearch_word VALUES (715,'do',2);
INSERT INTO ezsearch_word VALUES (688,'bear',2);
INSERT INTO ezsearch_word VALUES (714,'drink',1);
INSERT INTO ezsearch_word VALUES (686,'at',2);
INSERT INTO ezsearch_word VALUES (713,'sit',1);
INSERT INTO ezsearch_word VALUES (712,'recomended',1);
INSERT INTO ezsearch_word VALUES (711,'norway',1);
INSERT INTO ezsearch_word VALUES (710,'town',1);
INSERT INTO ezsearch_word VALUES (709,'skien',1);
INSERT INTO ezsearch_word VALUES (708,'very',1);
INSERT INTO ezsearch_word VALUES (707,'now',2);
INSERT INTO ezsearch_word VALUES (706,'huge',1);
INSERT INTO ezsearch_word VALUES (705,'near',1);
INSERT INTO ezsearch_word VALUES (704,'typhoon',2);
INSERT INTO ezsearch_word VALUES (698,'better',1);
INSERT INTO ezsearch_word VALUES (699,'seet',1);
INSERT INTO ezsearch_word VALUES (700,'whole',1);
INSERT INTO ezsearch_word VALUES (701,'need',1);
INSERT INTO ezsearch_word VALUES (702,'buy',1);
INSERT INTO ezsearch_word VALUES (703,'today',1);
INSERT INTO ezsearch_word VALUES (721,'just',2);
INSERT INTO ezsearch_word VALUES (722,'kidding',1);
INSERT INTO ezsearch_word VALUES (732,'ipsum',19);
INSERT INTO ezsearch_word VALUES (733,'also',3);
INSERT INTO ezsearch_word VALUES (734,'be',3);
INSERT INTO ezsearch_word VALUES (735,'used',3);
INSERT INTO ezsearch_word VALUES (736,'beta',1);
INSERT INTO ezsearch_word VALUES (737,'find',2);
INSERT INTO ezsearch_word VALUES (738,'bugs',2);
INSERT INTO ezsearch_word VALUES (739,'will',2);
INSERT INTO ezsearch_word VALUES (740,'game',1);
INSERT INTO ezsearch_word VALUES (741,'beta1',1);
INSERT INTO ezsearch_word VALUES (742,'ok',1);
INSERT INTO ezsearch_word VALUES (743,'didn',1);
INSERT INTO ezsearch_word VALUES (744,'t',1);
INSERT INTO ezsearch_word VALUES (745,'prove',1);
INSERT INTO ezsearch_word VALUES (746,'hard',1);
INSERT INTO ezsearch_word VALUES (747,'say',1);
INSERT INTO ezsearch_word VALUES (748,'time',1);
INSERT INTO ezsearch_word VALUES (749,'another',1);
INSERT INTO ezsearch_word VALUES (750,'article',1);
INSERT INTO ezsearch_word VALUES (751,'information',2);
INSERT INTO ezsearch_word VALUES (752,'much',1);
INSERT INTO ezsearch_word VALUES (753,'here',1);
INSERT INTO ezsearch_word VALUES (754,'create',1);
INSERT INTO ezsearch_word VALUES (755,'interesting',1);

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


