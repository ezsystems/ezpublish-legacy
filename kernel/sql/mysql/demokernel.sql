# MySQL dump 8.13
#
# Host: localhost    Database: bf
#--------------------------------------------------------
# Server version	3.23.36-log

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

INSERT INTO ezbasket VALUES (1,'7aa2cf9537602fe690dde6d52d7e5bbf',17);

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

INSERT INTO ezbinaryfile VALUES (270,1,'LvLRKN.bin','myphp.php','text/plain');
INSERT INTO ezbinaryfile VALUES (270,2,'uLD7N3.bin','ellen1.JPG','image/jpeg');
INSERT INTO ezbinaryfile VALUES (270,3,'LvLRKN.bin','myphp.php','text/plain');
INSERT INTO ezbinaryfile VALUES (447,1,'C2sOPo.mp3','Throw The First Stone.mp3','audio/x-mp3');
INSERT INTO ezbinaryfile VALUES (444,1,'3IOWKF.mp3','Like A Rainbow Coloured Diamond.mp3','audio/x-mp3');

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

INSERT INTO ezcart VALUES (2,'1822a8c48c3c26c2c12883826a72a9ab',3);
INSERT INTO ezcart VALUES (8,'edbf0ffa83eec495352eb91bffbfc8da',9);
INSERT INTO ezcart VALUES (4,'b12170da25d040a306423039163ea168',5);
INSERT INTO ezcart VALUES (13,'7aa2cf9537602fe690dde6d52d7e5bbf',14);
INSERT INTO ezcart VALUES (14,'43d0bdde48e183f2b480cbebad5f53d2',15);

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

INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',-1,8,1024392098,1031484841);
INSERT INTO ezcontentclass VALUES (2,0,'Article','article','<title>',-1,8,1024392098,1031484987);
INSERT INTO ezcontentclass VALUES (3,0,'User group','user_group','<name>',-1,1,1024392098,1031224194);
INSERT INTO ezcontentclass VALUES (4,0,'User','user','<first_name> <last_name>',-1,1,1024392098,1031223207);
INSERT INTO ezcontentclass VALUES (5,0,'Simple Product','simple_product','<name>',8,8,1031228120,1031228251);
INSERT INTO ezcontentclass VALUES (4,1,'User','user','<first_name> <last_name>',-1,30,1024392098,1031837195);
INSERT INTO ezcontentclass VALUES (7,0,'Image','','<name>',8,8,1031484992,1031485055);
INSERT INTO ezcontentclass VALUES (13,1,'','','',0,8,0,1031597762);
INSERT INTO ezcontentclass VALUES (16,0,'Bil','','<navn>',8,8,1031742661,1031742925);
INSERT INTO ezcontentclass VALUES (12,0,'Comment','','<title>',8,8,1031567722,1031911475);
INSERT INTO ezcontentclass VALUES (17,0,'Review','','<title>',8,8,1031743932,1031915840);
INSERT INTO ezcontentclass VALUES (15,0,'DVD','','<name>',8,8,1031727318,1031913860);
INSERT INTO ezcontentclass VALUES (19,0,'Message','','<topic>',8,8,1031921705,1031921760);
INSERT INTO ezcontentclass VALUES (20,0,'Mp3 file','','<name>',8,8,1032009759,1032009823);
INSERT INTO ezcontentclass VALUES (17,1,'Review','','<title>',8,8,1031743932,1032011655);

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

INSERT INTO ezcontentclass_attribute VALUES (8,0,4,'first_name','First name','ezstring',1,0,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (9,0,4,'last_name','Last name','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (12,0,4,'user_account','User account','ezuser',0,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (26,0,7,'name','Name','ezstring',1,0,1,50,0,0,0,0,0,0,0,'Nytt bilde','','','');
INSERT INTO ezcontentclass_attribute VALUES (6,0,3,'name','Name','ezstring',1,0,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (27,0,7,'caption','Caption','ezstring',1,0,2,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (15,0,5,'description','description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'About','','','');
INSERT INTO ezcontentclass_attribute VALUES (14,0,5,'name','Name','ezstring',1,0,1,255,0,0,0,0,0,0,0,'Product','','','');
INSERT INTO ezcontentclass_attribute VALUES (16,0,5,'price','Price','ezprice',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (23,0,2,'intro','Intro','ezxmltext',1,0,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (24,0,2,'body','Body','ezxmltext',1,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (5,0,1,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (4,0,1,'name','Name','ezstring',1,0,1,255,0,0,0,0,0,0,0,'Folder','','','');
INSERT INTO ezcontentclass_attribute VALUES (25,0,2,'thumbnail_image','Thumbnail image','ezimage',1,0,6,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (21,0,2,'subtitle','Subtitle','ezstring',1,0,2,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (22,0,2,'author','Author','ezstring',1,0,3,50,0,0,0,0,0,0,0,'Redaksjonen','','','');
INSERT INTO ezcontentclass_attribute VALUES (1,0,2,'title','Title','ezstring',1,0,1,255,0,0,0,0,0,0,0,'New article','','','');
INSERT INTO ezcontentclass_attribute VALUES (28,0,7,'image','Image','ezimage',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (47,0,12,'title','title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (48,0,12,'message','Message','eztext',1,1,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (65,0,15,'price','Price','ezprice',1,0,8,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (64,0,15,'cover_image','Cover image','ezimage',1,0,7,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (63,0,15,'category','Category','ezstring',1,0,6,50,0,0,0,0,0,0,0,'Action','','','');
INSERT INTO ezcontentclass_attribute VALUES (62,0,15,'actors','Actors','ezstring',1,0,5,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (61,0,15,'release_date','Release date','ezstring',1,0,4,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (60,0,15,'sone','Sone','ezstring',1,0,3,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (57,0,15,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (59,0,15,'description','Description','eztext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (69,0,16,'bilde','Bilde','ezimage',1,0,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (70,0,16,'motor','Motor','ezstring',1,1,5,200,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (68,0,16,'beskrivelse','Beskrivelse','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (67,0,16,'modell','Modell','ezstring',1,1,2,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (66,0,16,'navn','Navn','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (73,1,17,'rating','Rating','ezenum',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (72,1,17,'review','review','eztext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (71,1,17,'title','Title','ezstring',1,0,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (79,0,20,'file','File','ezbinaryfile',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (77,0,20,'name','name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (78,0,20,'artist','Artist','ezstring',1,0,2,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (75,0,19,'author','Author','ezstring',1,1,2,80,0,0,0,0,0,0,0,'Anonymous','','','');
INSERT INTO ezcontentclass_attribute VALUES (76,0,19,'message','Message','eztext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (74,0,19,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','');
INSERT INTO ezcontentclass_attribute VALUES (73,0,17,'rating','Rating','ezenum',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (71,0,17,'title','Title','ezstring',1,0,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (72,0,17,'review','review','eztext',1,0,2,0,0,0,0,0,0,0,0,'','','','');

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
INSERT INTO ezcontentclass_classgroup VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (4,1,2,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (17,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (5,0,3,'Products');
INSERT INTO ezcontentclass_classgroup VALUES (16,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (12,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (15,0,3,'Products');
INSERT INTO ezcontentclass_classgroup VALUES (17,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (19,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (20,0,4,'Media');

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

INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,1,1031216928,1031216937);
INSERT INTO ezcontentclassgroup VALUES (2,'Users',1,1,1031216941,1031216949);
INSERT INTO ezcontentclassgroup VALUES (3,'Products',8,8,1031227910,1031227919);
INSERT INTO ezcontentclassgroup VALUES (4,'Media',8,8,1032009743,1032009749);

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

INSERT INTO ezcontentobject VALUES (1,0,0,2,1,1,'Frontpage',14,0,1,0,0);
INSERT INTO ezcontentobject VALUES (4,0,0,5,0,3,'This is the master users',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (8,1,1,6,0,4,'Sergey Pushchin',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (10,1,1,11,0,3,'Other users',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (11,1,1,12,0,4,'Floyd Floyd',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (107,8,1,112,4,7,'Green banch',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (33,8,1,34,5,1,'Media',5,0,1,0,0);
INSERT INTO ezcontentobject VALUES (30,8,1,31,0,4,'John Doe',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (21,1,1,22,0,4,'Amos',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (41,8,1,43,0,3,'Editors',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (23,21,1,24,0,4,'Test user',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (106,8,1,111,4,7,'Purple flower',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (32,8,1,33,3,1,'Shop',9,0,1,1031667228,1031667228);
INSERT INTO ezcontentobject VALUES (31,8,0,32,1,1,'News',9,0,1,0,0);
INSERT INTO ezcontentobject VALUES (42,8,1,44,0,4,'First  Editor',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (43,8,1,45,0,3,'Advanced editors',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (44,8,1,46,0,4,'Chief Editor',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (59,8,1,62,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (52,8,1,55,0,4,'test test',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (53,8,1,56,0,4,'t1 t1',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (67,8,1,70,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (72,8,1,75,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (120,8,1,125,7,1,'Support forum',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (101,8,1,105,4,1,'Image gallery',3,0,1,0,0);
INSERT INTO ezcontentobject VALUES (102,8,1,106,4,7,'Speed',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (111,8,1,116,3,15,'The Lord of the Rings: The Fellowship of the Ring',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (112,8,1,117,3,17,'Bloody Brilliant!',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (83,8,1,86,1,11,'New MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (119,8,1,124,6,1,'Open Forum',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (85,8,1,88,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (108,8,1,113,3,1,'DVD',5,0,1,0,0);
INSERT INTO ezcontentobject VALUES (109,8,1,114,3,1,'CD',3,0,1,0,0);
INSERT INTO ezcontentobject VALUES (97,8,1,100,1,2,'eZ publish 3.0',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (110,8,1,115,3,1,'Software',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (98,8,1,101,1,12,'eZ publish 3.0 rules !!',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (118,8,1,123,6,1,'Forum',3,0,1,0,0);
INSERT INTO ezcontentobject VALUES (117,8,1,122,3,17,'Ok movie',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (121,8,1,126,6,19,'The first topic',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (122,8,1,127,3,17,'Good movie thumbs up!',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (123,30,1,128,1,12,'Kewl product!',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (124,8,1,129,4,7,'Night shot',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (125,8,1,130,4,7,'Yellow branch',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (126,8,1,131,4,7,'Fjord by night',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (127,8,1,132,6,19,'I do not agree with this!',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (128,8,1,133,6,19,'I agree',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (129,8,1,134,7,19,'How do I do this ?',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (130,8,1,135,7,19,'Publish',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (131,8,1,136,5,1,'Music',3,0,1,0,0);
INSERT INTO ezcontentobject VALUES (132,8,1,137,5,20,'Like A Rainbow Coloured Diamond',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (133,8,1,138,5,20,'Threw The First Stone',1,0,1,0,0);

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

INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',1,1,4,'My folder',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',1,1,5,'This folder contains some information about...',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',4,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',4,31,5,'Hovedkategori for nyheter ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (7,'en_GB',1,4,5,'Main group',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (8,'en_GB',1,4,6,'This is the master users',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (15,'en_GB',1,8,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (13,'en_GB',1,8,8,'Sergey',0,0);
INSERT INTO ezcontentobject_attribute VALUES (14,'en_GB',1,8,9,'Pushchin',0,0);
INSERT INTO ezcontentobject_attribute VALUES (13,'en_GB',2,8,8,'Sergey',0,0);
INSERT INTO ezcontentobject_attribute VALUES (14,'en_GB',2,8,9,'Pushchin',0,0);
INSERT INTO ezcontentobject_attribute VALUES (15,'en_GB',2,8,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (7,'en_GB',2,4,5,'Main group',0,0);
INSERT INTO ezcontentobject_attribute VALUES (8,'en_GB',2,4,6,'This is the master users',0,0);
INSERT INTO ezcontentobject_attribute VALUES (405,'en_GB',1,118,4,'Forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (22,'en_GB',1,11,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (21,'en_GB',1,11,9,'Floyd',0,0);
INSERT INTO ezcontentobject_attribute VALUES (20,'en_GB',1,11,8,'Floyd',0,0);
INSERT INTO ezcontentobject_attribute VALUES (19,'en_GB',1,10,7,'Other users',0,0);
INSERT INTO ezcontentobject_attribute VALUES (18,'en_GB',1,10,6,'Other users',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',3,31,5,'Hovedkategori for nyheter ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',3,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (420,'en_GB',1,124,27,'skien by night',0,0);
INSERT INTO ezcontentobject_attribute VALUES (419,'en_GB',1,124,26,'Night shot',0,0);
INSERT INTO ezcontentobject_attribute VALUES (418,'en_GB',1,123,48,'Download I say...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',2,1,4,'My folder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',2,1,5,'This folder contains some information about...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'en_GB',1,33,5,'Her ligger bildene lagret',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'en_GB',1,33,4,'Bilder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',6,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',6,1,4,'Forsiden',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',5,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',5,1,4,'Hovedkategori',0,0);
INSERT INTO ezcontentobject_attribute VALUES (417,'en_GB',1,123,47,'Kewl product!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'en_GB',2,33,5,'Her ligger bildene lagret',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'en_GB',2,33,4,'Bilder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',4,32,5,'Hovedkategori for eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',4,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',4,1,4,'Hovedkategori',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',4,32,4,'Eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',3,32,5,'Hovedkategori for eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',3,32,4,'Eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',2,31,5,'Hovedkategori for nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',2,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',2,32,4,'Eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',2,32,5,'Hovedkategori for eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (406,'en_GB',1,118,5,'Discussion forums',0,0);
INSERT INTO ezcontentobject_attribute VALUES (65,'en_GB',1,30,9,'Doe',0,0);
INSERT INTO ezcontentobject_attribute VALUES (66,'en_GB',1,30,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',1,32,5,'Hovedkategori for eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',1,32,4,'Eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (64,'en_GB',1,30,8,'John',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',1,31,5,'Hovedkategori for nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',1,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',3,1,5,'Dette er hovednoden i content treet.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',3,1,4,'Hovedkategori',0,0);
INSERT INTO ezcontentobject_attribute VALUES (404,'en_GB',1,117,73,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (403,'en_GB',1,117,72,'This movie is so so, not very good. But if you have three hours to spare, this is an ok choice.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (402,'en_GB',1,117,71,'Ok movie',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',11,1,5,'Frontpage..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',11,1,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute VALUES (378,'en_GB',1,110,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (377,'en_GB',1,110,4,'Software',0,0);
INSERT INTO ezcontentobject_attribute VALUES (373,'en_GB',3,108,4,'DVD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (374,'en_GB',3,108,5,'DVD discs',0,0);
INSERT INTO ezcontentobject_attribute VALUES (101,'en_GB',1,41,6,'Editors',0,0);
INSERT INTO ezcontentobject_attribute VALUES (102,'en_GB',1,41,7,'Editors user group',0,0);
INSERT INTO ezcontentobject_attribute VALUES (103,'en_GB',1,42,8,'First ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (104,'en_GB',1,42,9,'Editor',0,0);
INSERT INTO ezcontentobject_attribute VALUES (105,'en_GB',1,42,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (106,'en_GB',1,43,6,'Advanced editors',0,0);
INSERT INTO ezcontentobject_attribute VALUES (107,'en_GB',1,43,7,'Here we will store advanced editors acounts',0,0);
INSERT INTO ezcontentobject_attribute VALUES (108,'en_GB',1,44,8,'Chief',0,0);
INSERT INTO ezcontentobject_attribute VALUES (109,'en_GB',1,44,9,'Editor',0,0);
INSERT INTO ezcontentobject_attribute VALUES (110,'en_GB',1,44,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (343,'en_GB',1,97,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (344,'en_GB',1,97,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>eZ publish 2.2.7 is now officially released. New versions of eZ publish desktop edition is also available for all supported platforms. New versions of the eZ publish installers will also be released shortly. This release fixes several problems in eZ publish and eZ publish desktop edition.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (345,'en_GB',1,97,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>eZ publish is dual licenced, between GPL, giving you eZ publish open source and free, and the professional licence where you get the right to use the source code for making your own commercial software.</paragraph><paragraph><header>Why use eZ publish?</header>\neZ publish is module based and is platform and database independent. Today there are 26 different modules in eZ publish with the Article maker and the Trade function as the most projecting modules. Read more about all the modules in the eZ publish white paper.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (346,'en_GB',1,97,25,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (373,'en_GB',4,108,4,'DVD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (380,'en_GB',1,111,59,'A marvellously sympathetic yet spectacularly cinematic treatment of the first part of Tolkien\'s trilogy, Peter Jackson\'s The Lord of the Rings: The Fellowship of the Ring is the film that finally showed how extraordinary digital effects could be used to support story and characters, not simply overwhelm them. Both long-time fantasy fans and newcomers alike were simultaneously amazed, astonished and left agog for parts two and three. ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (381,'en_GB',1,111,60,'Sone 1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (382,'en_GB',1,111,61,'August 2002',0,0);
INSERT INTO ezcontentobject_attribute VALUES (383,'en_GB',1,111,62,'Elijah Wood, Ian McKellen, et al.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (384,'en_GB',1,111,63,'Adventure',0,0);
INSERT INTO ezcontentobject_attribute VALUES (385,'en_GB',1,111,64,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (386,'en_GB',1,111,65,'',0,250);
INSERT INTO ezcontentobject_attribute VALUES (202,'en_GB',1,72,50,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (203,'en_GB',1,72,51,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>what</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (204,'en_GB',1,72,52,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (205,'en_GB',1,72,54,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (201,'en_GB',1,72,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"sp\"  email=\"sp@ez.no\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (200,'en_GB',1,72,43,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (190,'en_GB',1,67,49,'<?xml version=\"1.0\"?>\n<ezoption>  <name>image size</name>\n  <options>    <option id=\"0\" >big</option>\n    <option id=\"1\" >small</option>\n    <option id=\"2\" >medium</option>\n</options>\n</ezoption>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (189,'en_GB',1,67,46,'',0,7);
INSERT INTO ezcontentobject_attribute VALUES (186,'en_GB',1,67,43,'qqqqqqqqqqqqqqqq',0,0);
INSERT INTO ezcontentobject_attribute VALUES (187,'en_GB',1,67,44,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (188,'en_GB',1,67,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"dds\"  email=\"sa@ez.no\"  />    <author id=\"1\"  name=\"dvsw\"  email=\"dd@ez.no\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (167,'en_GB',1,59,43,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (168,'en_GB',1,59,44,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (169,'en_GB',1,59,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"ww\"  email=\"w@ez.no\"  />    <author id=\"1\"  name=\"rwgw\"  email=\"wgee@w.com\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (170,'en_GB',1,59,46,'',0,8.9);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',7,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',7,1,4,'Forsiden',0,0);
INSERT INTO ezcontentobject_attribute VALUES (145,'en_GB',1,52,8,'test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (146,'en_GB',1,52,9,'test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (147,'en_GB',1,52,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (148,'en_GB',1,53,8,'t1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (149,'en_GB',1,53,9,'t1',0,0);
INSERT INTO ezcontentobject_attribute VALUES (150,'en_GB',1,53,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',8,32,5,'This is the shop',0,0);
INSERT INTO ezcontentobject_attribute VALUES (373,'en_GB',2,108,4,'DVD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',5,32,4,'Eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',5,32,5,'Hovedkategori for eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (445,'en_GB',1,133,77,'Threw The First Stone',0,0);
INSERT INTO ezcontentobject_attribute VALUES (446,'en_GB',1,133,78,'The Marble Kings',0,0);
INSERT INTO ezcontentobject_attribute VALUES (447,'en_GB',1,133,79,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',9,31,4,'News',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',9,31,5,'Main category for news',0,0);
INSERT INTO ezcontentobject_attribute VALUES (373,'en_GB',5,108,4,'DVD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (374,'en_GB',5,108,5,'DVD discs',0,0);
INSERT INTO ezcontentobject_attribute VALUES (375,'en_GB',3,109,4,'CD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (376,'en_GB',3,109,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (377,'en_GB',2,110,4,'Software',0,0);
INSERT INTO ezcontentobject_attribute VALUES (378,'en_GB',2,110,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (379,'en_GB',1,111,57,'The Lord of the Rings: The Fellowship of the Ring',0,0);
INSERT INTO ezcontentobject_attribute VALUES (347,'en_GB',1,98,47,'eZ publish 3.0 rules !!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (348,'en_GB',1,98,48,'This is an awsome piece of software. No-one above no-one on the side. Simply pretty ok.\n\n--john doe',0,0);
INSERT INTO ezcontentobject_attribute VALUES (387,'en_GB',1,112,71,'Bloody Brilliant!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (388,'en_GB',1,112,72,'The most amazing film i have yet seen, it fully deserves my five star rating!\nbringing the world of Middle-Earth to life in an array of awe-inspireing sets, great speacial effects, and startling characters, LOTR is a masterpeice. i\'m not joking! \nok, it does have a few flaws, such as the ommision of Tom Bombadil and Goldberry, and the heightening of Arwin and Sarumans parts, but really, unless you want a film thats 50 hours long, they can\'t get everything right.\nElijah Woods portrayal of Frodo, going from happy-go-lucky adventurer to tradgedy courting hero is brilliant, Ian Mckellen is wonderful as the wizard, Gandalf, and Viggo Mortenson exeles as Aragorn, Ranger of the North. But i think the two that really stand tall are Orlando Bloom as the Elf, Legolas, Prince of Mirkwood, and Sean Bean as Boromir of Gondor. Bloom is absolutly perfect for the part, the finest choice possible, and Bean dazzles as a strong, military minded man, who is eventually consumed by the Ring of Power.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (389,'en_GB',1,112,73,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (376,'en_GB',2,109,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (375,'en_GB',2,109,4,'CD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (376,'en_GB',1,109,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (375,'en_GB',1,109,4,'CD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (374,'en_GB',4,108,5,'DVD discs',0,0);
INSERT INTO ezcontentobject_attribute VALUES (374,'en_GB',2,108,5,'DVD discs',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',8,32,4,'Shop',0,0);
INSERT INTO ezcontentobject_attribute VALUES (374,'en_GB',1,108,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (367,'en_GB',1,106,26,'Purple flower',0,0);
INSERT INTO ezcontentobject_attribute VALUES (368,'en_GB',1,106,27,'... it\'s probably got a name as well. Lorem Ipsum.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (369,'en_GB',1,106,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (370,'en_GB',1,107,26,'Green banch',0,0);
INSERT INTO ezcontentobject_attribute VALUES (371,'en_GB',1,107,27,'From the green forrest',0,0);
INSERT INTO ezcontentobject_attribute VALUES (372,'en_GB',1,107,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (353,'en_GB',2,101,4,'Image gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (354,'en_GB',2,101,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',7,32,4,'Shop',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',7,32,5,'This is the shop',0,0);
INSERT INTO ezcontentobject_attribute VALUES (373,'en_GB',1,108,4,'Folder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (261,'en_GB',1,83,43,'Datatype test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (262,'en_GB',1,83,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"sp\"  email=\"sp@ez.no\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (263,'en_GB',1,83,50,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (264,'en_GB',1,83,51,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (265,'en_GB',1,83,52,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (266,'en_GB',1,83,54,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (267,'en_GB',1,83,55,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (443,'en_GB',1,132,78,'The Marble Kings ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (444,'en_GB',1,132,79,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (275,'en_GB',1,85,43,'Datatype test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (276,'en_GB',1,85,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"sp\"  email=\"sp@ez.no\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (277,'en_GB',1,85,50,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (278,'en_GB',1,85,51,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph></paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (279,'en_GB',1,85,52,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (280,'en_GB',1,85,54,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (281,'en_GB',1,85,55,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (282,'en_GB',1,85,56,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',5,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',5,31,5,'Hovedkategori for nyheter ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (283,'no_NO',5,31,4,'Privet russian!!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (284,'no_NO',5,31,5,'Tjobinggg.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (275,'en_GB',2,85,43,'Datatype test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (276,'en_GB',2,85,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"sp\"  email=\"sp@ez.no\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (277,'en_GB',2,85,50,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (278,'en_GB',2,85,51,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph></paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (279,'en_GB',2,85,52,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (280,'en_GB',2,85,54,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (281,'en_GB',2,85,55,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (282,'en_GB',2,85,56,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (416,'en_GB',1,122,73,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (415,'en_GB',1,122,72,'Not the last star though. It was too short.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (411,'en_GB',1,121,74,'The first topic',0,0);
INSERT INTO ezcontentobject_attribute VALUES (412,'en_GB',1,121,75,'Bård',0,0);
INSERT INTO ezcontentobject_attribute VALUES (413,'en_GB',1,121,76,'This is the first topic.. Cool !',0,0);
INSERT INTO ezcontentobject_attribute VALUES (414,'en_GB',1,122,71,'Good movie thumbs up!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (442,'en_GB',1,132,77,'Like A Rainbow Coloured Diamond',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',6,32,5,'Hovedkategori for DVD filmer',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',6,32,4,'DVD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (342,'en_GB',1,97,21,'The next generation content management systems',0,0);
INSERT INTO ezcontentobject_attribute VALUES (341,'en_GB',1,97,1,'eZ publish 3.0',0,0);
INSERT INTO ezcontentobject_attribute VALUES (406,'en_GB',2,118,5,'Discussion forums',0,0);
INSERT INTO ezcontentobject_attribute VALUES (405,'en_GB',2,118,4,'Forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (410,'en_GB',2,120,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (409,'en_GB',2,120,4,'Support forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (408,'en_GB',2,119,5,'Discuss openly here',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',6,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',6,31,5,'Hovedkategori for nyheter ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (441,'en_GB',3,131,5,'This is the place for music..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (440,'en_GB',3,131,4,'Music',0,0);
INSERT INTO ezcontentobject_attribute VALUES (354,'en_GB',3,101,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',13,1,5,'Frontpage..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',8,31,4,'News',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',8,31,5,'Main category for news',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',14,1,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',14,1,5,'Frontpage..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (405,'en_GB',3,118,4,'Forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (406,'en_GB',3,118,5,'Discussion forums',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',9,32,4,'Shop',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',9,32,5,'This is the shop',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'en_GB',5,33,4,'Media',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'en_GB',5,33,5,'Diverse media',0,0);
INSERT INTO ezcontentobject_attribute VALUES (353,'en_GB',3,101,4,'Image gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (434,'en_GB',1,129,74,'How do I do this ?',0,0);
INSERT INTO ezcontentobject_attribute VALUES (435,'en_GB',1,129,75,'John Doe',0,0);
INSERT INTO ezcontentobject_attribute VALUES (436,'en_GB',1,129,76,'I want to publish and such. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. v',0,0);
INSERT INTO ezcontentobject_attribute VALUES (437,'en_GB',1,130,74,'Publish',0,0);
INSERT INTO ezcontentobject_attribute VALUES (438,'en_GB',1,130,75,'Helper',0,0);
INSERT INTO ezcontentobject_attribute VALUES (439,'en_GB',1,130,76,'You can just do it like this: lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. lorem ipsum. ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (440,'en_GB',1,131,4,'Music',0,0);
INSERT INTO ezcontentobject_attribute VALUES (441,'en_GB',1,131,5,'This is the place for music..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (440,'en_GB',2,131,4,'Music',0,0);
INSERT INTO ezcontentobject_attribute VALUES (441,'en_GB',2,131,5,'This is the place for music..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',12,1,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',12,1,5,'Frontpage..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',13,1,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute VALUES (433,'en_GB',1,128,76,'Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (428,'en_GB',1,127,74,'I do not agree with this!',0,0);
INSERT INTO ezcontentobject_attribute VALUES (429,'en_GB',1,127,75,'Anonymous',0,0);
INSERT INTO ezcontentobject_attribute VALUES (430,'en_GB',1,127,76,'This is not true at all. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. \n\nLorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. Lorem ipsum. ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (431,'en_GB',1,128,74,'I agree',0,0);
INSERT INTO ezcontentobject_attribute VALUES (432,'en_GB',1,128,75,'Kjell Bekkelund',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',8,1,4,'Forsiden',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',8,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',7,31,4,'News',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',7,31,5,'Main category for news',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',9,1,4,'Forsiden',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',9,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'en_GB',3,33,4,'Media',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'en_GB',3,33,5,'Diverse media',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',10,1,4,'Forsiden',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',10,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'en_GB',4,33,4,'Media',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'en_GB',4,33,5,'Diverse media',0,0);
INSERT INTO ezcontentobject_attribute VALUES (407,'en_GB',2,119,4,'Open Forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (410,'en_GB',1,120,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (407,'en_GB',1,119,4,'Open Forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (408,'en_GB',1,119,5,'Discuss openly here',0,0);
INSERT INTO ezcontentobject_attribute VALUES (409,'en_GB',1,120,4,'Support forum',0,0);
INSERT INTO ezcontentobject_attribute VALUES (353,'en_GB',1,101,4,'Image gallery',0,0);
INSERT INTO ezcontentobject_attribute VALUES (354,'en_GB',1,101,5,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (355,'en_GB',1,102,26,'Speed',0,0);
INSERT INTO ezcontentobject_attribute VALUES (356,'en_GB',1,102,27,'Speed',0,0);
INSERT INTO ezcontentobject_attribute VALUES (357,'en_GB',1,102,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (421,'en_GB',1,124,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (422,'en_GB',1,125,26,'Yellow branch',0,0);
INSERT INTO ezcontentobject_attribute VALUES (423,'en_GB',1,125,27,'A part of a yellow tree, what it is I don\'t know.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (424,'en_GB',1,125,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (425,'en_GB',1,126,26,'Fjord by night',0,0);
INSERT INTO ezcontentobject_attribute VALUES (426,'en_GB',1,126,27,'Norwegian fjord by night.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (427,'en_GB',1,126,28,'',0,0);

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
INSERT INTO ezcontentobject_tree VALUES (2,1,1,1,0,1360594808,1,'/1/2/','',2,7,'frontpage');
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,NULL,NULL,1,'/1/5/',NULL,8,15,NULL);
INSERT INTO ezcontentobject_tree VALUES (9,5,8,NULL,NULL,NULL,2,'/1/5/9/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (11,5,10,NULL,NULL,NULL,2,'/1/5/11/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (12,11,11,NULL,NULL,NULL,3,'/1/5/11/12/',NULL,16,17,NULL);
INSERT INTO ezcontentobject_tree VALUES (34,2,33,0,0,-33009793,2,'/1/2/34/','',7,8,'frontpage/media');
INSERT INTO ezcontentobject_tree VALUES (115,33,110,0,0,-1791959995,3,'/1/2/33/115/','',0,0,'frontpage/shop/software');
INSERT INTO ezcontentobject_tree VALUES (31,5,30,NULL,NULL,NULL,2,'/1/5/31/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (22,5,21,NULL,NULL,NULL,2,'/1/5/22/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (24,5,23,NULL,NULL,NULL,2,'/1/5/24/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (32,2,31,0,0,1560702681,2,'/1/2/32/','',7,8,'frontpage/news');
INSERT INTO ezcontentobject_tree VALUES (33,2,32,0,0,-322979029,2,'/1/2/33/','',7,8,'frontpage/shop');
INSERT INTO ezcontentobject_tree VALUES (111,105,106,0,0,-1118588199,4,'/1/2/34/105/111/','',0,0,'hovedkategori/media/image_gallery/nytt_bilde');
INSERT INTO ezcontentobject_tree VALUES (112,105,107,0,0,-1118588199,4,'/1/2/34/105/112/','',0,0,'hovedkategori/media/image_gallery/nytt_bilde');
INSERT INTO ezcontentobject_tree VALUES (113,33,108,0,0,-777495213,3,'/1/2/33/113/','',0,0,'frontpage/shop/dvd');
INSERT INTO ezcontentobject_tree VALUES (114,33,109,0,0,-1599589015,3,'/1/2/33/114/','',0,0,'frontpage/shop/cd');
INSERT INTO ezcontentobject_tree VALUES (43,5,41,0,0,961378214,2,'/1/5/43/','',15,16,'this_is_the_master_users/editors');
INSERT INTO ezcontentobject_tree VALUES (44,43,42,0,0,-1498193455,3,'/1/5/43/44/','',16,17,'this_is_the_master_users/editors/first_editor');
INSERT INTO ezcontentobject_tree VALUES (45,5,43,0,0,1649068118,2,'/1/5/45/','',15,16,'this_is_the_master_users/advanced_editors');
INSERT INTO ezcontentobject_tree VALUES (46,45,44,0,0,-1245754999,3,'/1/5/45/46/','',16,17,'this_is_the_master_users/advanced_editors/chief_editor');
INSERT INTO ezcontentobject_tree VALUES (55,5,52,0,0,214034150,2,'/1/5/55/','',15,16,'this_is_the_master_users/test_test');
INSERT INTO ezcontentobject_tree VALUES (56,5,53,0,0,-1469971828,2,'/1/5/56/','',15,16,'this_is_the_master_users/t1_t1');
INSERT INTO ezcontentobject_tree VALUES (127,116,122,0,0,-1022380077,5,'/1/2/33/113/116/127/','',0,0,'forsiden/shop/dvd/the_lord_of_the_rings_the_fellowship_of_the_ring/new_review');
INSERT INTO ezcontentobject_tree VALUES (126,124,121,0,0,1343898638,4,'/1/2/123/124/126/','',0,0,'forsiden/forum/open_forum/new_message');
INSERT INTO ezcontentobject_tree VALUES (122,116,117,0,0,-1022380077,5,'/1/2/33/113/116/122/','',0,0,'forsiden/shop/dvd/the_lord_of_the_rings_the_fellowship_of_the_ring/new_review');
INSERT INTO ezcontentobject_tree VALUES (123,2,118,0,0,286216382,2,'/1/2/123/','',0,0,'frontpage/forum');
INSERT INTO ezcontentobject_tree VALUES (100,32,97,0,0,1806721877,3,'/1/2/32/100/','',0,0,'hovedkategori/nyheter/ez_publish_30');
INSERT INTO ezcontentobject_tree VALUES (101,100,98,0,0,149847021,4,'/1/2/32/100/101/','',0,0,'hovedkategori/nyheter/ez_publish_30/new_comment');
INSERT INTO ezcontentobject_tree VALUES (124,123,119,0,0,781033951,3,'/1/2/123/124/','',0,0,'forsiden/forum/open_forum');
INSERT INTO ezcontentobject_tree VALUES (105,34,101,0,0,1824236852,3,'/1/2/34/105/','',0,0,'frontpage/media/image_gallery');
INSERT INTO ezcontentobject_tree VALUES (106,105,102,0,0,-1118588199,4,'/1/2/34/105/106/','',0,0,'hovedkategori/media/image_gallery/nytt_bilde');
INSERT INTO ezcontentobject_tree VALUES (125,123,120,0,0,-1106952055,3,'/1/2/123/125/','',0,0,'forsiden/forum/support_forum');
INSERT INTO ezcontentobject_tree VALUES (116,113,111,0,0,2110495847,4,'/1/2/33/113/116/','',0,0,'hovedkategori/shop/dvd/the_lord_of_the_rings_the_fellowship_of_the_ring');
INSERT INTO ezcontentobject_tree VALUES (117,116,112,0,0,-1290802833,5,'/1/2/33/113/116/117/','',0,0,'hovedkategori/shop/dvd/the_lord_of_the_rings_the_fellowship_of_the_ring/new_review');
INSERT INTO ezcontentobject_tree VALUES (128,100,123,0,0,1646971205,4,'/1/2/32/100/128/','',0,0,'forsiden/nyheter/ez_publish_30/new_comment');
INSERT INTO ezcontentobject_tree VALUES (129,105,124,0,0,-322990546,4,'/1/2/34/105/129/','',0,0,'forsiden/media/image_gallery/nytt_bilde');
INSERT INTO ezcontentobject_tree VALUES (130,105,125,0,0,-322990546,4,'/1/2/34/105/130/','',0,0,'forsiden/media/image_gallery/nytt_bilde');
INSERT INTO ezcontentobject_tree VALUES (131,105,126,0,0,-322990546,4,'/1/2/34/105/131/','',0,0,'forsiden/media/image_gallery/nytt_bilde');
INSERT INTO ezcontentobject_tree VALUES (132,126,127,0,0,-1174886450,5,'/1/2/123/124/126/132/','',0,0,'forsiden/forum/open_forum/the_first_topic/new_message');
INSERT INTO ezcontentobject_tree VALUES (133,126,128,0,0,-1174886450,5,'/1/2/123/124/126/133/','',0,0,'forsiden/forum/open_forum/the_first_topic/new_message');
INSERT INTO ezcontentobject_tree VALUES (134,125,129,0,0,1520487502,4,'/1/2/123/125/134/','',0,0,'forsiden/forum/support_forum/new_message');
INSERT INTO ezcontentobject_tree VALUES (135,134,130,0,0,927903767,5,'/1/2/123/125/134/135/','',0,0,'forsiden/forum/support_forum/how_do_i_do_this_/new_message');
INSERT INTO ezcontentobject_tree VALUES (136,34,131,0,0,376554926,3,'/1/2/34/136/','',0,0,'frontpage/media/music');
INSERT INTO ezcontentobject_tree VALUES (137,136,132,0,0,-1165317681,4,'/1/2/34/136/137/','',0,0,'frontpage/media/music/like_a_rainbow_coloured_diamond');
INSERT INTO ezcontentobject_tree VALUES (138,136,133,0,0,-990028302,4,'/1/2/34/136/138/','',0,0,'frontpage/media/music/new_mp3_file');

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
INSERT INTO ezcontentobject_version VALUES (20,1,8,2,1031225239,1031225244,1,1,0);
INSERT INTO ezcontentobject_version VALUES (62,33,8,1,1031486294,1031486307,0,0,0);
INSERT INTO ezcontentobject_version VALUES (4,4,0,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version VALUES (8,8,1,1,1031223286,1031223570,0,0,0);
INSERT INTO ezcontentobject_version VALUES (9,8,1,2,1031223658,1031223663,0,0,0);
INSERT INTO ezcontentobject_version VALUES (10,4,1,2,1031223678,1031223910,1,1,0);
INSERT INTO ezcontentobject_version VALUES (64,31,8,3,1031486682,1031486697,0,0,0);
INSERT INTO ezcontentobject_version VALUES (16,11,1,1,1031224473,1031224508,0,0,0);
INSERT INTO ezcontentobject_version VALUES (15,10,1,1,1031224414,1031224439,0,0,0);
INSERT INTO ezcontentobject_version VALUES (61,1,8,6,1031486204,1031486209,1,1,0);
INSERT INTO ezcontentobject_version VALUES (60,1,8,5,1031486165,1031486167,1,1,0);
INSERT INTO ezcontentobject_version VALUES (59,1,8,4,1031486137,1031486155,1,1,0);
INSERT INTO ezcontentobject_version VALUES (57,31,8,2,1031484854,1031484860,0,0,0);
INSERT INTO ezcontentobject_version VALUES (207,109,8,1,1031913318,1031913324,0,0,0);
INSERT INTO ezcontentobject_version VALUES (56,32,8,2,1031484812,1031484812,0,0,0);
INSERT INTO ezcontentobject_version VALUES (217,117,8,1,1031915817,1031915897,0,0,0);
INSERT INTO ezcontentobject_version VALUES (28,21,1,1,1031307079,1031307079,0,0,0);
INSERT INTO ezcontentobject_version VALUES (69,33,8,2,1031486893,1031486897,0,0,0);
INSERT INTO ezcontentobject_version VALUES (30,23,21,1,1031309318,1031309318,0,0,0);
INSERT INTO ezcontentobject_version VALUES (68,32,8,4,1031486821,1031486827,0,0,0);
INSERT INTO ezcontentobject_version VALUES (67,31,8,4,1031486809,1031486814,0,0,0);
INSERT INTO ezcontentobject_version VALUES (58,32,8,3,1031484867,1031484873,0,0,0);
INSERT INTO ezcontentobject_version VALUES (205,108,8,3,1031913231,1031913235,0,0,0);
INSERT INTO ezcontentobject_version VALUES (206,108,8,4,1031913256,1031913262,0,0,0);
INSERT INTO ezcontentobject_version VALUES (55,32,8,1,1031484747,1031484770,0,0,0);
INSERT INTO ezcontentobject_version VALUES (52,30,8,1,1031482159,1031482181,0,0,0);
INSERT INTO ezcontentobject_version VALUES (218,118,8,1,1031921464,1031921476,0,0,0);
INSERT INTO ezcontentobject_version VALUES (54,31,8,1,1031484715,1031484728,0,0,0);
INSERT INTO ezcontentobject_version VALUES (53,1,8,3,1031484658,1031484701,1,1,0);
INSERT INTO ezcontentobject_version VALUES (204,108,8,2,1031913212,1031913220,0,0,0);
INSERT INTO ezcontentobject_version VALUES (203,32,8,8,1031913198,1031913204,0,0,0);
INSERT INTO ezcontentobject_version VALUES (202,108,8,1,1031913174,1031913174,0,0,0);
INSERT INTO ezcontentobject_version VALUES (198,106,8,1,1031912858,1031912893,0,0,0);
INSERT INTO ezcontentobject_version VALUES (78,41,8,1,1031488556,1031488576,0,0,0);
INSERT INTO ezcontentobject_version VALUES (79,42,8,1,1031488585,1031488617,0,0,0);
INSERT INTO ezcontentobject_version VALUES (80,43,8,1,1031488920,1031488980,0,0,0);
INSERT INTO ezcontentobject_version VALUES (81,44,8,1,1031488986,1031489022,0,0,0);
INSERT INTO ezcontentobject_version VALUES (185,33,8,3,1031912279,1031912300,0,0,0);
INSERT INTO ezcontentobject_version VALUES (210,111,8,1,1031913580,1031913672,0,0,0);
INSERT INTO ezcontentobject_version VALUES (186,1,8,10,1031912307,1031912307,1,1,0);
INSERT INTO ezcontentobject_version VALUES (105,59,8,1,1031567534,1031569130,0,0,0);
INSERT INTO ezcontentobject_version VALUES (102,1,8,7,1031504607,1031504607,1,1,0);
INSERT INTO ezcontentobject_version VALUES (96,52,8,1,1031501235,1031501252,0,0,0);
INSERT INTO ezcontentobject_version VALUES (97,53,8,1,1031501320,1031501337,0,0,0);
INSERT INTO ezcontentobject_version VALUES (199,107,8,1,1031912909,1031912935,0,0,0);
INSERT INTO ezcontentobject_version VALUES (113,67,8,1,1031569597,1031584186,0,0,0);
INSERT INTO ezcontentobject_version VALUES (183,31,8,7,1031912246,1031912257,0,0,0);
INSERT INTO ezcontentobject_version VALUES (119,72,8,1,1031584755,1031585662,0,0,0);
INSERT INTO ezcontentobject_version VALUES (122,32,8,5,1031586337,1031586337,0,0,0);
INSERT INTO ezcontentobject_version VALUES (219,119,8,1,1031921481,1031921575,0,0,0);
INSERT INTO ezcontentobject_version VALUES (212,1,8,11,1031914419,1031914427,1,1,0);
INSERT INTO ezcontentobject_version VALUES (192,101,8,1,1031912526,1031912535,0,0,0);
INSERT INTO ezcontentobject_version VALUES (220,120,8,1,1031921633,1031921640,0,0,0);
INSERT INTO ezcontentobject_version VALUES (177,31,30,6,1031835342,1031835342,0,0,0);
INSERT INTO ezcontentobject_version VALUES (211,112,8,1,1031913959,1031913991,0,0,0);
INSERT INTO ezcontentobject_version VALUES (221,119,8,2,1031921873,1031921880,0,0,0);
INSERT INTO ezcontentobject_version VALUES (193,102,8,1,1031912559,1031912612,0,0,0);
INSERT INTO ezcontentobject_version VALUES (201,32,8,7,1031913143,1031913161,0,0,0);
INSERT INTO ezcontentobject_version VALUES (181,98,8,1,1031912088,1031912138,0,0,0);
INSERT INTO ezcontentobject_version VALUES (200,101,8,2,1031913011,1031913015,0,0,0);
INSERT INTO ezcontentobject_version VALUES (144,83,8,1,1031645657,1031645657,0,0,0);
INSERT INTO ezcontentobject_version VALUES (184,1,8,9,1031912265,1031912265,1,1,0);
INSERT INTO ezcontentobject_version VALUES (180,97,8,1,1031911903,1031912050,0,0,0);
INSERT INTO ezcontentobject_version VALUES (209,110,8,1,1031913362,1031913369,0,0,0);
INSERT INTO ezcontentobject_version VALUES (187,33,8,4,1031912328,1031912336,0,0,0);
INSERT INTO ezcontentobject_version VALUES (154,85,8,1,1031654786,1031659883,0,0,0);
INSERT INTO ezcontentobject_version VALUES (155,31,8,5,1031655110,1031655163,0,0,0);
INSERT INTO ezcontentobject_version VALUES (182,1,8,8,1031912216,1031912216,1,1,0);
INSERT INTO ezcontentobject_version VALUES (157,85,8,2,1031659895,1031660679,0,0,0);
INSERT INTO ezcontentobject_version VALUES (208,109,8,2,1031913339,1031913343,0,0,0);
INSERT INTO ezcontentobject_version VALUES (162,32,8,6,1031667215,1031667228,0,0,0);
INSERT INTO ezcontentobject_version VALUES (222,120,8,2,1031921887,1031921893,0,0,0);
INSERT INTO ezcontentobject_version VALUES (223,118,8,2,1031921901,1031921905,0,0,0);
INSERT INTO ezcontentobject_version VALUES (224,121,8,1,1031922106,1031922128,0,0,0);
INSERT INTO ezcontentobject_version VALUES (225,122,8,1,1031926346,1031926371,0,0,0);
INSERT INTO ezcontentobject_version VALUES (226,123,30,1,1031926611,1031926623,0,0,0);
INSERT INTO ezcontentobject_version VALUES (227,124,8,1,1031928276,1031928313,0,0,0);
INSERT INTO ezcontentobject_version VALUES (228,125,8,1,1031928322,1031928367,0,0,0);
INSERT INTO ezcontentobject_version VALUES (229,126,8,1,1031928378,1031928410,0,0,0);
INSERT INTO ezcontentobject_version VALUES (230,127,8,1,1032002514,1032002539,0,0,0);
INSERT INTO ezcontentobject_version VALUES (231,128,8,1,1032003784,1032003802,0,0,0);
INSERT INTO ezcontentobject_version VALUES (232,129,8,1,1032003840,1032004065,0,0,0);
INSERT INTO ezcontentobject_version VALUES (233,130,8,1,1032004075,1032004101,0,0,0);
INSERT INTO ezcontentobject_version VALUES (234,131,8,1,1032004784,1032004797,0,0,0);
INSERT INTO ezcontentobject_version VALUES (235,131,8,2,1032009445,1032009452,0,0,0);
INSERT INTO ezcontentobject_version VALUES (236,1,8,12,1032009470,1032009480,1,1,0);
INSERT INTO ezcontentobject_version VALUES (237,1,8,13,1032009495,1032009499,1,1,0);
INSERT INTO ezcontentobject_version VALUES (238,31,8,8,1032009530,1032009537,0,0,0);
INSERT INTO ezcontentobject_version VALUES (239,1,8,14,1032009554,1032009560,1,1,0);
INSERT INTO ezcontentobject_version VALUES (240,118,8,3,1032009582,1032009586,0,0,0);
INSERT INTO ezcontentobject_version VALUES (241,32,8,9,1032009602,1032009607,0,0,0);
INSERT INTO ezcontentobject_version VALUES (242,33,8,5,1032009620,1032009624,0,0,0);
INSERT INTO ezcontentobject_version VALUES (243,101,8,3,1032009639,1032009643,0,0,0);
INSERT INTO ezcontentobject_version VALUES (244,131,8,3,1032009654,1032009660,0,0,0);
INSERT INTO ezcontentobject_version VALUES (245,132,8,1,1032009837,1032010976,0,0,0);
INSERT INTO ezcontentobject_version VALUES (246,133,8,1,1032011726,1032011769,0,0,0);
INSERT INTO ezcontentobject_version VALUES (247,31,8,9,1032012444,1032012450,0,0,0);
INSERT INTO ezcontentobject_version VALUES (248,108,8,5,1032012487,1032012492,0,0,0);
INSERT INTO ezcontentobject_version VALUES (249,109,8,3,1032012499,1032012505,0,0,0);
INSERT INTO ezcontentobject_version VALUES (250,110,8,2,1032012512,1032012516,0,0,0);

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

INSERT INTO ezenumobjectvalue VALUES (254,1,4,'red','1');
INSERT INTO ezenumobjectvalue VALUES (273,1,5,'black','2');
INSERT INTO ezenumobjectvalue VALUES (274,1,9,'medium','3');
INSERT INTO ezenumobjectvalue VALUES (274,1,8,'bad','2');
INSERT INTO ezenumobjectvalue VALUES (274,1,7,'good','1');
INSERT INTO ezenumobjectvalue VALUES (254,2,4,'red','1');
INSERT INTO ezenumobjectvalue VALUES (273,2,5,'black','2');
INSERT INTO ezenumobjectvalue VALUES (274,2,9,'medium','3');
INSERT INTO ezenumobjectvalue VALUES (274,2,8,'bad','2');
INSERT INTO ezenumobjectvalue VALUES (274,2,7,'good','1');
INSERT INTO ezenumobjectvalue VALUES (273,3,5,'black','2');
INSERT INTO ezenumobjectvalue VALUES (274,3,8,'bad','2');
INSERT INTO ezenumobjectvalue VALUES (274,3,7,'good','1');
INSERT INTO ezenumobjectvalue VALUES (274,3,9,'medium','3');
INSERT INTO ezenumobjectvalue VALUES (280,1,4,'red','1');
INSERT INTO ezenumobjectvalue VALUES (281,1,8,'bad','2');
INSERT INTO ezenumobjectvalue VALUES (281,1,7,'good','1');
INSERT INTO ezenumobjectvalue VALUES (280,2,4,'red','1');
INSERT INTO ezenumobjectvalue VALUES (281,2,8,'bad','2');
INSERT INTO ezenumobjectvalue VALUES (281,2,7,'good','1');
INSERT INTO ezenumobjectvalue VALUES (295,1,4,'red','1');
INSERT INTO ezenumobjectvalue VALUES (296,1,7,'good','1');
INSERT INTO ezenumobjectvalue VALUES (296,1,8,'bad','2');
INSERT INTO ezenumobjectvalue VALUES (389,1,14,'5','5');
INSERT INTO ezenumobjectvalue VALUES (404,1,11,'2','2');
INSERT INTO ezenumobjectvalue VALUES (416,1,13,'4','4');

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

INSERT INTO ezenumvalue VALUES (1,38,1,'Skien','',1);
INSERT INTO ezenumvalue VALUES (2,38,1,'Porsgrunn','',2);
INSERT INTO ezenumvalue VALUES (3,38,1,'dsfgsdfg','',3);
INSERT INTO ezenumvalue VALUES (3,38,0,'dsfgsdfg','',3);
INSERT INTO ezenumvalue VALUES (2,38,0,'Porsgrunn','',2);
INSERT INTO ezenumvalue VALUES (1,38,0,'Skien','',1);
INSERT INTO ezenumvalue VALUES (4,54,1,'red','1',1);
INSERT INTO ezenumvalue VALUES (5,54,1,'black','2',2);
INSERT INTO ezenumvalue VALUES (6,54,1,'blue','3',3);
INSERT INTO ezenumvalue VALUES (8,55,1,'bad','2',2);
INSERT INTO ezenumvalue VALUES (7,55,1,'good','1',1);
INSERT INTO ezenumvalue VALUES (9,55,1,'medium','3',3);
INSERT INTO ezenumvalue VALUES (7,55,0,'good','1',1);
INSERT INTO ezenumvalue VALUES (9,55,0,'medium','3',3);
INSERT INTO ezenumvalue VALUES (8,55,0,'bad','2',2);
INSERT INTO ezenumvalue VALUES (4,54,0,'red','1',1);
INSERT INTO ezenumvalue VALUES (5,54,0,'black','2',2);
INSERT INTO ezenumvalue VALUES (6,54,0,'blue','3',3);
INSERT INTO ezenumvalue VALUES (10,73,1,'1','1',1);
INSERT INTO ezenumvalue VALUES (11,73,1,'2','2',2);
INSERT INTO ezenumvalue VALUES (13,73,1,'4','4',4);
INSERT INTO ezenumvalue VALUES (12,73,1,'3','3',3);
INSERT INTO ezenumvalue VALUES (14,73,1,'5','5',5);

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

INSERT INTO ezimage VALUES (369,1,'4OTWev.jpg','dscn3253.jpg','image/jpeg');
INSERT INTO ezimage VALUES (372,1,'7trCzL.jpg','dscn3123.jpg','image/jpeg');
INSERT INTO ezimage VALUES (385,1,'36QNU3.jpg','B00005RDP8.02.LZZZZZZZ.jpg','image/jpeg');
INSERT INTO ezimage VALUES (421,1,'xoAxaW.jpg','dscn1587.jpg','image/jpeg');
INSERT INTO ezimage VALUES (424,1,'np6asV.jpg','dscn1742.jpg','image/jpeg');
INSERT INTO ezimage VALUES (427,1,'v7vog4.jpg','dscn3357.jpg','image/jpeg');
INSERT INTO ezimage VALUES (282,1,'RVpV7a.jpg','ellen1.JPG','image/jpeg');
INSERT INTO ezimage VALUES (357,1,'03mKIC.jpg','dscn1333.jpg','image/jpeg');
INSERT INTO ezimage VALUES (282,2,'RVpV7a.jpg','ellen1.JPG','image/jpeg');
INSERT INTO ezimage VALUES (297,1,'66Khra.jpg','DSC00023.JPG','image/jpeg');
INSERT INTO ezimage VALUES (346,1,'exV6BW.jpg','528-100x100.jpg','image/jpeg');

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

INSERT INTO ezimagevariation VALUES (427,1,'v7vog4_600x600_427.jpg','v/7/v/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (372,1,'7trCzL_100x100_372.jpg','7/t/r/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (369,1,'4OTWev_600x600_369.jpg','4/O/T/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (369,1,'4OTWev_100x100_369.jpg','4/O/T/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (385,1,'36QNU3_100x100_385.jpg','3/6/Q/',100,100,70,100);
INSERT INTO ezimagevariation VALUES (385,1,'36QNU3_600x600_385.jpg','3/6/Q/',600,600,210,300);
INSERT INTO ezimagevariation VALUES (421,1,'xoAxaW_100x100_421.jpg','x/o/A/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (421,1,'xoAxaW_600x600_421.jpg','x/o/A/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (372,1,'7trCzL_600x600_372.jpg','7/t/r/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (424,1,'np6asV_100x100_424.jpg','n/p/6/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (424,1,'np6asV_600x600_424.jpg','n/p/6/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (427,1,'v7vog4_100x100_427.jpg','v/7/v/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (282,1,'RVpV7a_100x100_282.jpg','R/V/p/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (357,1,'03mKIC_100x100_357.jpg','0/3/m/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (282,1,'RVpV7a_600x600_282.jpg','R/V/p/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (282,2,'RVpV7a_100x100_282.jpg','R/V/p/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (297,1,'66Khra_100x100_297.jpg','6/6/K/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (346,1,'exV6BW_600x600_346.jpg','e/x/V/',600,600,69,100);
INSERT INTO ezimagevariation VALUES (346,1,'exV6BW_100x100_346.jpg','e/x/V/',100,100,69,100);
INSERT INTO ezimagevariation VALUES (357,1,'03mKIC_600x600_357.jpg','0/3/m/',600,600,400,300);

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

INSERT INTO ezorder VALUES (1,8,2,1031228716);
INSERT INTO ezorder VALUES (2,8,4,1031658533);
INSERT INTO ezorder VALUES (3,8,6,1031729064);
INSERT INTO ezorder VALUES (4,8,7,1031730330);
INSERT INTO ezorder VALUES (5,8,8,1031732606);
INSERT INTO ezorder VALUES (6,8,10,1031820138);
INSERT INTO ezorder VALUES (7,8,11,1031822695);
INSERT INTO ezorder VALUES (8,8,12,1031822821);
INSERT INTO ezorder VALUES (9,8,13,1031822825);

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

INSERT INTO ezpolicy VALUES (91,3,'*','search','*');
INSERT INTO ezpolicy VALUES (92,3,'read','content','*');
INSERT INTO ezpolicy VALUES (113,22,'edit','content','');
INSERT INTO ezpolicy VALUES (47,2,'*','*','*');
INSERT INTO ezpolicy VALUES (233,1,'create','content','');
INSERT INTO ezpolicy VALUES (114,22,'create','content','');
INSERT INTO ezpolicy VALUES (93,3,'remove','content','');
INSERT INTO ezpolicy VALUES (88,4,'*','content','*');
INSERT INTO ezpolicy VALUES (112,22,'read','content','');
INSERT INTO ezpolicy VALUES (90,4,'*','search','*');
INSERT INTO ezpolicy VALUES (94,3,'create','content','');
INSERT INTO ezpolicy VALUES (95,3,'edit','content','');
INSERT INTO ezpolicy VALUES (96,13,'*','shop','*');
INSERT INTO ezpolicy VALUES (97,13,'read','content','');
INSERT INTO ezpolicy VALUES (111,16,'create','content','');
INSERT INTO ezpolicy VALUES (109,16,'read','content','');
INSERT INTO ezpolicy VALUES (110,16,'edit','content','');
INSERT INTO ezpolicy VALUES (232,1,'read','content','');
INSERT INTO ezpolicy VALUES (231,1,'*','shop','*');
INSERT INTO ezpolicy VALUES (203,46,'*','*','*');
INSERT INTO ezpolicy VALUES (234,1,'edit','content','');
INSERT INTO ezpolicy VALUES (235,1,'read','content','');
INSERT INTO ezpolicy VALUES (236,1,'create','content','');
INSERT INTO ezpolicy VALUES (237,1,'create','content','');

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

INSERT INTO ezpolicy_limitation VALUES (63,112,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (64,113,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (65,114,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (66,114,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (30,93,'Assigned',0,'','');
INSERT INTO ezpolicy_limitation VALUES (31,94,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (32,94,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (33,94,'ParentClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (34,95,'Assigned',0,'','');
INSERT INTO ezpolicy_limitation VALUES (35,97,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (36,97,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (61,111,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (60,111,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (58,109,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (59,110,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (62,111,'ParentClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (67,114,'ParentClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (162,233,'ParentClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (160,232,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (161,233,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (163,234,'Assigned',0,'','');
INSERT INTO ezpolicy_limitation VALUES (164,235,'SectionID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (165,236,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (166,236,'ParentClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (167,237,'ClassID',0,'','');
INSERT INTO ezpolicy_limitation VALUES (168,237,'ParentClassID',0,'','');

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

INSERT INTO ezpolicy_limitation_value VALUES (132,64,5);
INSERT INTO ezpolicy_limitation_value VALUES (131,63,9);
INSERT INTO ezpolicy_limitation_value VALUES (126,63,1);
INSERT INTO ezpolicy_limitation_value VALUES (130,63,8);
INSERT INTO ezpolicy_limitation_value VALUES (129,63,7);
INSERT INTO ezpolicy_limitation_value VALUES (128,63,5);
INSERT INTO ezpolicy_limitation_value VALUES (127,63,2);
INSERT INTO ezpolicy_limitation_value VALUES (133,65,7);
INSERT INTO ezpolicy_limitation_value VALUES (134,65,8);
INSERT INTO ezpolicy_limitation_value VALUES (135,65,9);
INSERT INTO ezpolicy_limitation_value VALUES (59,30,1);
INSERT INTO ezpolicy_limitation_value VALUES (60,31,1);
INSERT INTO ezpolicy_limitation_value VALUES (61,31,2);
INSERT INTO ezpolicy_limitation_value VALUES (62,31,5);
INSERT INTO ezpolicy_limitation_value VALUES (63,31,7);
INSERT INTO ezpolicy_limitation_value VALUES (64,31,8);
INSERT INTO ezpolicy_limitation_value VALUES (65,32,4);
INSERT INTO ezpolicy_limitation_value VALUES (66,32,5);
INSERT INTO ezpolicy_limitation_value VALUES (67,33,1);
INSERT INTO ezpolicy_limitation_value VALUES (68,34,1);
INSERT INTO ezpolicy_limitation_value VALUES (69,35,1);
INSERT INTO ezpolicy_limitation_value VALUES (70,35,2);
INSERT INTO ezpolicy_limitation_value VALUES (71,36,2);
INSERT INTO ezpolicy_limitation_value VALUES (116,58,7);
INSERT INTO ezpolicy_limitation_value VALUES (115,58,5);
INSERT INTO ezpolicy_limitation_value VALUES (114,58,2);
INSERT INTO ezpolicy_limitation_value VALUES (113,58,1);
INSERT INTO ezpolicy_limitation_value VALUES (120,60,7);
INSERT INTO ezpolicy_limitation_value VALUES (121,60,8);
INSERT INTO ezpolicy_limitation_value VALUES (117,58,8);
INSERT INTO ezpolicy_limitation_value VALUES (118,58,9);
INSERT INTO ezpolicy_limitation_value VALUES (119,59,5);
INSERT INTO ezpolicy_limitation_value VALUES (283,164,2);
INSERT INTO ezpolicy_limitation_value VALUES (138,67,1);
INSERT INTO ezpolicy_limitation_value VALUES (137,66,5);
INSERT INTO ezpolicy_limitation_value VALUES (136,66,4);
INSERT INTO ezpolicy_limitation_value VALUES (125,62,1);
INSERT INTO ezpolicy_limitation_value VALUES (124,61,5);
INSERT INTO ezpolicy_limitation_value VALUES (123,61,4);
INSERT INTO ezpolicy_limitation_value VALUES (122,60,9);
INSERT INTO ezpolicy_limitation_value VALUES (282,164,1);
INSERT INTO ezpolicy_limitation_value VALUES (278,160,1);
INSERT INTO ezpolicy_limitation_value VALUES (279,161,12);
INSERT INTO ezpolicy_limitation_value VALUES (280,162,2);
INSERT INTO ezpolicy_limitation_value VALUES (284,164,3);
INSERT INTO ezpolicy_limitation_value VALUES (285,164,4);
INSERT INTO ezpolicy_limitation_value VALUES (281,163,1);
INSERT INTO ezpolicy_limitation_value VALUES (286,164,5);
INSERT INTO ezpolicy_limitation_value VALUES (287,164,6);
INSERT INTO ezpolicy_limitation_value VALUES (288,165,17);
INSERT INTO ezpolicy_limitation_value VALUES (289,166,15);
INSERT INTO ezpolicy_limitation_value VALUES (290,167,19);
INSERT INTO ezpolicy_limitation_value VALUES (291,168,1);
INSERT INTO ezpolicy_limitation_value VALUES (292,168,19);

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
INSERT INTO ezproductcollection VALUES (4);
INSERT INTO ezproductcollection VALUES (5);
INSERT INTO ezproductcollection VALUES (6);
INSERT INTO ezproductcollection VALUES (7);
INSERT INTO ezproductcollection VALUES (8);
INSERT INTO ezproductcollection VALUES (9);
INSERT INTO ezproductcollection VALUES (10);
INSERT INTO ezproductcollection VALUES (11);
INSERT INTO ezproductcollection VALUES (12);
INSERT INTO ezproductcollection VALUES (13);
INSERT INTO ezproductcollection VALUES (14);
INSERT INTO ezproductcollection VALUES (15);
INSERT INTO ezproductcollection VALUES (16);
INSERT INTO ezproductcollection VALUES (17);

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

INSERT INTO ezproductcollection_item VALUES (5,4,71,1,0,435);
INSERT INTO ezproductcollection_item VALUES (2,2,16,1,0,500);
INSERT INTO ezproductcollection_item VALUES (10,6,91,1,0,478);
INSERT INTO ezproductcollection_item VALUES (7,4,71,1,0,435);
INSERT INTO ezproductcollection_item VALUES (9,6,89,1,0,398);
INSERT INTO ezproductcollection_item VALUES (11,7,91,1,0,478);
INSERT INTO ezproductcollection_item VALUES (19,11,89,1,0,199);
INSERT INTO ezproductcollection_item VALUES (16,9,91,1,0,239);
INSERT INTO ezproductcollection_item VALUES (15,8,91,1,0,239);
INSERT INTO ezproductcollection_item VALUES (18,10,89,1,0,199);
INSERT INTO ezproductcollection_item VALUES (20,12,89,1,0,199);
INSERT INTO ezproductcollection_item VALUES (21,14,89,1,0,199);
INSERT INTO ezproductcollection_item VALUES (27,17,111,1,0,250);

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
INSERT INTO ezrole VALUES (4,0,'advanced editor','');
INSERT INTO ezrole VALUES (13,0,'Meglerpakke',NULL);
INSERT INTO ezrole VALUES (14,0,'New Role',NULL);
INSERT INTO ezrole VALUES (22,16,'he can upload images',NULL);
INSERT INTO ezrole VALUES (16,0,'he can upload images',NULL);
INSERT INTO ezrole VALUES (46,2,'Admin',NULL);

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

INSERT INTO ezsearch_object_word_link VALUES (4,8,4,0,1,3,0,4,9);
INSERT INTO ezsearch_object_word_link VALUES (3,8,3,0,0,0,4,4,8);
INSERT INTO ezsearch_object_word_link VALUES (5,4,5,0,0,0,6,3,5);
INSERT INTO ezsearch_object_word_link VALUES (6,4,6,0,1,5,7,3,5);
INSERT INTO ezsearch_object_word_link VALUES (7,4,7,0,2,6,8,3,6);
INSERT INTO ezsearch_object_word_link VALUES (8,4,8,0,3,7,9,3,6);
INSERT INTO ezsearch_object_word_link VALUES (9,4,9,0,4,8,10,3,6);
INSERT INTO ezsearch_object_word_link VALUES (10,4,10,0,5,9,11,3,6);
INSERT INTO ezsearch_object_word_link VALUES (11,4,11,0,6,10,0,3,6);
INSERT INTO ezsearch_object_word_link VALUES (23,9,11,0,3,14,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (22,9,14,0,2,11,11,1,5);
INSERT INTO ezsearch_object_word_link VALUES (21,9,11,0,1,14,14,1,4);
INSERT INTO ezsearch_object_word_link VALUES (20,9,14,0,0,0,11,1,4);
INSERT INTO ezsearch_object_word_link VALUES (24,10,14,0,0,0,11,3,6);
INSERT INTO ezsearch_object_word_link VALUES (25,10,11,0,1,14,14,3,6);
INSERT INTO ezsearch_object_word_link VALUES (26,10,14,0,2,11,11,3,7);
INSERT INTO ezsearch_object_word_link VALUES (27,10,11,0,3,14,0,3,7);
INSERT INTO ezsearch_object_word_link VALUES (28,11,15,0,0,0,15,4,8);
INSERT INTO ezsearch_object_word_link VALUES (29,11,15,0,1,15,0,4,9);
INSERT INTO ezsearch_object_word_link VALUES (30,12,16,0,0,0,17,2,1);
INSERT INTO ezsearch_object_word_link VALUES (31,12,17,0,1,16,18,2,1);
INSERT INTO ezsearch_object_word_link VALUES (32,12,18,0,2,17,18,2,2);
INSERT INTO ezsearch_object_word_link VALUES (33,12,18,0,3,18,18,2,2);
INSERT INTO ezsearch_object_word_link VALUES (34,12,18,0,4,18,18,2,2);
INSERT INTO ezsearch_object_word_link VALUES (35,12,18,0,5,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (36,12,18,0,6,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (37,12,18,0,7,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (38,12,18,0,8,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (39,12,18,0,9,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (40,12,18,0,10,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (41,12,18,0,11,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (42,12,18,0,12,18,19,2,13);
INSERT INTO ezsearch_object_word_link VALUES (43,12,19,0,13,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (44,12,18,0,14,19,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (45,12,18,0,15,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (46,12,18,0,16,18,20,2,13);
INSERT INTO ezsearch_object_word_link VALUES (47,12,20,0,17,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (48,12,18,0,18,20,21,2,13);
INSERT INTO ezsearch_object_word_link VALUES (49,12,21,0,19,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (50,12,18,0,20,21,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (51,12,18,0,21,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (52,12,18,0,22,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (53,12,18,0,23,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (54,12,18,0,24,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (55,12,18,0,25,18,18,2,13);
INSERT INTO ezsearch_object_word_link VALUES (56,12,18,0,26,18,0,2,13);
INSERT INTO ezsearch_object_word_link VALUES (57,13,22,0,0,0,17,2,1);
INSERT INTO ezsearch_object_word_link VALUES (58,13,17,0,1,22,7,2,1);
INSERT INTO ezsearch_object_word_link VALUES (59,13,7,0,2,17,8,2,2);
INSERT INTO ezsearch_object_word_link VALUES (60,13,8,0,3,7,9,2,2);
INSERT INTO ezsearch_object_word_link VALUES (61,13,9,0,4,8,22,2,2);
INSERT INTO ezsearch_object_word_link VALUES (62,13,22,0,5,9,17,2,2);
INSERT INTO ezsearch_object_word_link VALUES (63,13,17,0,6,22,23,2,2);
INSERT INTO ezsearch_object_word_link VALUES (64,13,23,0,7,17,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (65,13,23,0,8,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (66,13,23,0,9,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (67,13,23,0,10,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (68,13,23,0,11,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (69,13,23,0,12,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (70,13,23,0,13,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (71,13,23,0,14,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (72,13,23,0,15,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (73,13,23,0,16,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (74,13,23,0,17,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (75,13,23,0,18,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (76,13,23,0,19,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (77,13,23,0,20,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (78,13,23,0,21,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (79,13,23,0,22,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (80,13,23,0,23,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (81,13,23,0,24,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (82,13,23,0,25,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (83,13,23,0,26,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (84,13,23,0,27,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (85,13,23,0,28,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (86,13,23,0,29,23,23,2,13);
INSERT INTO ezsearch_object_word_link VALUES (87,13,23,0,30,23,0,2,13);
INSERT INTO ezsearch_object_word_link VALUES (1709,99,689,0,1,688,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1708,99,688,0,0,0,689,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2058,117,870,0,3,7,8,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2057,117,7,0,2,870,870,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2056,117,870,0,1,677,7,17,71);
INSERT INTO ezsearch_object_word_link VALUES (2055,117,677,0,0,0,870,17,71);
INSERT INTO ezsearch_object_word_link VALUES (2439,1,922,0,1,922,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2438,1,922,0,0,0,922,1,4);
INSERT INTO ezsearch_object_word_link VALUES (96,14,30,0,0,0,31,1,4);
INSERT INTO ezsearch_object_word_link VALUES (97,14,31,0,1,30,32,1,5);
INSERT INTO ezsearch_object_word_link VALUES (98,14,32,0,2,31,30,1,5);
INSERT INTO ezsearch_object_word_link VALUES (99,14,30,0,3,32,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (100,15,33,0,0,0,34,5,14);
INSERT INTO ezsearch_object_word_link VALUES (101,15,34,0,1,33,35,5,14);
INSERT INTO ezsearch_object_word_link VALUES (102,15,35,0,2,34,36,5,14);
INSERT INTO ezsearch_object_word_link VALUES (103,15,36,0,3,35,33,5,14);
INSERT INTO ezsearch_object_word_link VALUES (104,15,33,0,4,36,34,5,15);
INSERT INTO ezsearch_object_word_link VALUES (105,15,34,0,5,33,35,5,15);
INSERT INTO ezsearch_object_word_link VALUES (106,15,35,0,6,34,36,5,15);
INSERT INTO ezsearch_object_word_link VALUES (107,15,36,0,7,35,37,5,15);
INSERT INTO ezsearch_object_word_link VALUES (108,15,37,0,8,36,38,5,15);
INSERT INTO ezsearch_object_word_link VALUES (109,15,38,0,9,37,39,5,15);
INSERT INTO ezsearch_object_word_link VALUES (110,15,39,0,10,38,40,5,15);
INSERT INTO ezsearch_object_word_link VALUES (111,15,40,0,11,39,41,5,15);
INSERT INTO ezsearch_object_word_link VALUES (112,15,41,0,12,40,0,5,16);
INSERT INTO ezsearch_object_word_link VALUES (113,16,33,0,0,0,34,5,14);
INSERT INTO ezsearch_object_word_link VALUES (114,16,34,0,1,33,42,5,14);
INSERT INTO ezsearch_object_word_link VALUES (115,16,42,0,2,34,43,5,14);
INSERT INTO ezsearch_object_word_link VALUES (116,16,43,0,3,42,44,5,15);
INSERT INTO ezsearch_object_word_link VALUES (117,16,44,0,4,43,45,5,15);
INSERT INTO ezsearch_object_word_link VALUES (118,16,45,0,5,44,46,5,15);
INSERT INTO ezsearch_object_word_link VALUES (119,16,46,0,6,45,47,5,15);
INSERT INTO ezsearch_object_word_link VALUES (120,16,47,0,7,46,48,5,15);
INSERT INTO ezsearch_object_word_link VALUES (121,16,48,0,8,47,9,5,15);
INSERT INTO ezsearch_object_word_link VALUES (122,16,9,0,9,48,33,5,15);
INSERT INTO ezsearch_object_word_link VALUES (123,16,33,0,10,9,34,5,15);
INSERT INTO ezsearch_object_word_link VALUES (124,16,34,0,11,33,35,5,15);
INSERT INTO ezsearch_object_word_link VALUES (125,16,35,0,12,34,36,5,15);
INSERT INTO ezsearch_object_word_link VALUES (126,16,36,0,13,35,49,5,15);
INSERT INTO ezsearch_object_word_link VALUES (127,16,49,0,14,36,0,5,16);
INSERT INTO ezsearch_object_word_link VALUES (128,24,50,0,0,0,17,2,1);
INSERT INTO ezsearch_object_word_link VALUES (129,24,17,0,1,50,51,2,1);
INSERT INTO ezsearch_object_word_link VALUES (130,24,51,0,2,17,52,2,2);
INSERT INTO ezsearch_object_word_link VALUES (131,24,52,0,3,51,0,2,13);
INSERT INTO ezsearch_object_word_link VALUES (132,22,53,0,0,0,54,6,17);
INSERT INTO ezsearch_object_word_link VALUES (133,22,54,0,1,53,55,6,18);
INSERT INTO ezsearch_object_word_link VALUES (134,22,55,0,2,54,56,6,18);
INSERT INTO ezsearch_object_word_link VALUES (135,22,56,0,3,55,0,6,18);
INSERT INTO ezsearch_object_word_link VALUES (139,28,60,0,1,59,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (138,28,59,0,0,0,60,1,4);
INSERT INTO ezsearch_object_word_link VALUES (140,29,61,0,0,0,62,1,4);
INSERT INTO ezsearch_object_word_link VALUES (141,29,62,0,1,61,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (165,30,84,0,0,0,85,4,8);
INSERT INTO ezsearch_object_word_link VALUES (164,25,83,0,3,82,0,2,13);
INSERT INTO ezsearch_object_word_link VALUES (163,25,82,0,2,17,83,2,2);
INSERT INTO ezsearch_object_word_link VALUES (162,25,17,0,1,81,82,2,1);
INSERT INTO ezsearch_object_word_link VALUES (161,25,81,0,0,0,17,2,1);
INSERT INTO ezsearch_object_word_link VALUES (166,30,85,0,1,84,0,4,9);
INSERT INTO ezsearch_object_word_link VALUES (2471,31,934,0,4,88,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2470,31,88,0,3,935,934,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2469,31,935,0,2,5,88,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2468,31,5,0,1,934,935,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2447,32,925,0,4,9,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2446,32,9,0,3,8,925,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2445,32,8,0,2,7,9,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2444,32,7,0,1,925,8,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2450,33,926,0,2,927,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2449,33,927,0,1,926,926,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2448,33,926,0,0,0,927,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1707,34,687,0,4,103,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1706,34,103,0,3,686,687,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1705,34,686,0,2,685,103,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1704,34,685,0,1,684,686,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1703,34,684,0,0,0,685,7,26);
INSERT INTO ezsearch_object_word_link VALUES (238,35,128,0,1,128,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (237,35,128,0,0,0,128,7,26);
INSERT INTO ezsearch_object_word_link VALUES (240,36,129,0,1,129,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (239,36,129,0,0,0,129,7,26);
INSERT INTO ezsearch_object_word_link VALUES (790,37,374,0,1,373,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (789,37,373,0,0,0,374,7,26);
INSERT INTO ezsearch_object_word_link VALUES (245,38,134,0,0,0,134,7,26);
INSERT INTO ezsearch_object_word_link VALUES (246,38,134,0,1,134,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (927,39,378,0,126,377,0,2,24);
INSERT INTO ezsearch_object_word_link VALUES (926,39,377,0,125,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (925,39,378,0,124,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (924,39,377,0,123,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (923,39,378,0,122,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (922,39,377,0,121,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (921,39,378,0,120,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (920,39,377,0,119,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (919,39,378,0,118,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (918,39,377,0,117,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (917,39,378,0,116,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (916,39,377,0,115,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (915,39,378,0,114,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (914,39,377,0,113,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (913,39,378,0,112,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (912,39,377,0,111,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (911,39,378,0,110,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (910,39,377,0,109,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (909,39,378,0,108,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (908,39,377,0,107,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (907,39,378,0,106,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (906,39,377,0,105,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (905,39,378,0,104,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (904,39,377,0,103,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (903,39,378,0,102,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (902,39,377,0,101,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (901,39,378,0,100,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (900,39,377,0,99,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (899,39,378,0,98,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (898,39,377,0,97,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (897,39,378,0,96,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (896,39,377,0,95,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (895,39,378,0,94,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (894,39,377,0,93,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (893,39,378,0,92,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (892,39,377,0,91,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (891,39,378,0,90,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (890,39,377,0,89,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (889,39,378,0,88,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (888,39,377,0,87,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (887,39,378,0,86,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (886,39,377,0,85,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (885,39,378,0,84,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (884,39,377,0,83,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (883,39,378,0,82,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (882,39,377,0,81,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (881,39,378,0,80,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (880,39,377,0,79,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (879,39,378,0,78,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (878,39,377,0,77,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (877,39,378,0,76,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (876,39,377,0,75,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (875,39,378,0,74,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (874,39,377,0,73,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (873,39,378,0,72,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (872,39,377,0,71,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (871,39,378,0,70,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (870,39,377,0,69,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (869,39,378,0,68,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (868,39,377,0,67,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (867,39,378,0,66,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (866,39,377,0,65,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (865,39,378,0,64,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (864,39,377,0,63,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (863,39,378,0,62,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (862,39,377,0,61,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (861,39,378,0,60,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (860,39,377,0,59,378,378,2,24);
INSERT INTO ezsearch_object_word_link VALUES (859,39,378,0,58,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (858,39,377,0,57,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (857,39,378,0,56,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (856,39,377,0,55,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (855,39,378,0,54,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (854,39,377,0,53,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (853,39,378,0,52,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (852,39,377,0,51,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (851,39,378,0,50,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (850,39,377,0,49,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (849,39,378,0,48,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (848,39,377,0,47,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (847,39,378,0,46,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (846,39,377,0,45,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (845,39,378,0,44,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (844,39,377,0,43,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (843,39,378,0,42,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (842,39,377,0,41,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (841,39,378,0,40,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (840,39,377,0,39,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (839,39,378,0,38,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (838,39,377,0,37,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (837,39,378,0,36,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (836,39,377,0,35,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (835,39,378,0,34,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (834,39,377,0,33,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (833,39,378,0,32,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (832,39,377,0,31,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (831,39,378,0,30,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (830,39,377,0,29,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (829,39,378,0,28,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (828,39,377,0,27,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (827,39,378,0,26,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (826,39,377,0,25,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (825,39,378,0,24,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (824,39,377,0,23,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (823,39,378,0,22,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (822,39,377,0,21,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (821,39,378,0,20,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (820,39,377,0,19,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (819,39,378,0,18,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (818,39,377,0,17,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (817,39,378,0,16,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (816,39,377,0,15,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (815,39,378,0,14,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (814,39,377,0,13,378,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (813,39,378,0,12,377,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (812,39,377,0,11,138,378,2,23);
INSERT INTO ezsearch_object_word_link VALUES (811,39,138,0,10,137,377,2,22);
INSERT INTO ezsearch_object_word_link VALUES (810,39,137,0,9,376,138,2,22);
INSERT INTO ezsearch_object_word_link VALUES (809,39,376,0,8,50,137,2,21);
INSERT INTO ezsearch_object_word_link VALUES (808,39,50,0,7,376,376,2,21);
INSERT INTO ezsearch_object_word_link VALUES (807,39,376,0,6,50,50,2,21);
INSERT INTO ezsearch_object_word_link VALUES (806,39,50,0,5,376,376,2,21);
INSERT INTO ezsearch_object_word_link VALUES (805,39,376,0,4,34,50,2,21);
INSERT INTO ezsearch_object_word_link VALUES (804,39,34,0,3,33,376,2,1);
INSERT INTO ezsearch_object_word_link VALUES (803,39,33,0,2,136,34,2,1);
INSERT INTO ezsearch_object_word_link VALUES (802,39,136,0,1,376,33,2,1);
INSERT INTO ezsearch_object_word_link VALUES (801,39,376,0,0,0,136,2,1);
INSERT INTO ezsearch_object_word_link VALUES (374,40,141,0,0,0,142,8,29);
INSERT INTO ezsearch_object_word_link VALUES (375,40,142,0,1,141,143,8,29);
INSERT INTO ezsearch_object_word_link VALUES (376,40,143,0,2,142,144,8,29);
INSERT INTO ezsearch_object_word_link VALUES (377,40,144,0,3,143,145,8,29);
INSERT INTO ezsearch_object_word_link VALUES (378,40,145,0,4,144,146,8,29);
INSERT INTO ezsearch_object_word_link VALUES (379,40,146,0,5,145,147,8,30);
INSERT INTO ezsearch_object_word_link VALUES (380,40,147,0,6,146,148,8,30);
INSERT INTO ezsearch_object_word_link VALUES (381,40,148,0,7,147,149,8,31);
INSERT INTO ezsearch_object_word_link VALUES (382,40,149,0,8,148,150,8,31);
INSERT INTO ezsearch_object_word_link VALUES (383,40,150,0,9,149,151,8,31);
INSERT INTO ezsearch_object_word_link VALUES (384,40,151,0,10,150,152,8,31);
INSERT INTO ezsearch_object_word_link VALUES (385,40,152,0,11,151,153,8,31);
INSERT INTO ezsearch_object_word_link VALUES (386,40,153,0,12,152,154,8,32);
INSERT INTO ezsearch_object_word_link VALUES (387,40,154,0,13,153,142,8,34);
INSERT INTO ezsearch_object_word_link VALUES (388,40,142,0,14,154,143,8,34);
INSERT INTO ezsearch_object_word_link VALUES (389,40,143,0,15,142,144,8,34);
INSERT INTO ezsearch_object_word_link VALUES (390,40,144,0,16,143,155,8,34);
INSERT INTO ezsearch_object_word_link VALUES (391,40,155,0,17,144,156,8,34);
INSERT INTO ezsearch_object_word_link VALUES (392,40,156,0,18,155,157,8,34);
INSERT INTO ezsearch_object_word_link VALUES (393,40,157,0,19,156,158,8,34);
INSERT INTO ezsearch_object_word_link VALUES (394,40,158,0,20,157,159,8,34);
INSERT INTO ezsearch_object_word_link VALUES (395,40,159,0,21,158,160,8,34);
INSERT INTO ezsearch_object_word_link VALUES (396,40,160,0,22,159,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (397,40,161,0,23,160,162,8,34);
INSERT INTO ezsearch_object_word_link VALUES (398,40,162,0,24,161,163,8,34);
INSERT INTO ezsearch_object_word_link VALUES (399,40,163,0,25,162,164,8,34);
INSERT INTO ezsearch_object_word_link VALUES (400,40,164,0,26,163,165,8,34);
INSERT INTO ezsearch_object_word_link VALUES (401,40,165,0,27,164,166,8,34);
INSERT INTO ezsearch_object_word_link VALUES (402,40,166,0,28,165,167,8,34);
INSERT INTO ezsearch_object_word_link VALUES (403,40,167,0,29,166,156,8,34);
INSERT INTO ezsearch_object_word_link VALUES (404,40,156,0,30,167,159,8,34);
INSERT INTO ezsearch_object_word_link VALUES (405,40,159,0,31,156,168,8,34);
INSERT INTO ezsearch_object_word_link VALUES (406,40,168,0,32,159,103,8,34);
INSERT INTO ezsearch_object_word_link VALUES (407,40,103,0,33,168,144,8,34);
INSERT INTO ezsearch_object_word_link VALUES (408,40,144,0,34,103,169,8,34);
INSERT INTO ezsearch_object_word_link VALUES (409,40,169,0,35,144,170,8,34);
INSERT INTO ezsearch_object_word_link VALUES (410,40,170,0,36,169,171,8,34);
INSERT INTO ezsearch_object_word_link VALUES (411,40,171,0,37,170,172,8,34);
INSERT INTO ezsearch_object_word_link VALUES (412,40,172,0,38,171,173,8,34);
INSERT INTO ezsearch_object_word_link VALUES (413,40,173,0,39,172,174,8,34);
INSERT INTO ezsearch_object_word_link VALUES (414,40,174,0,40,173,175,8,34);
INSERT INTO ezsearch_object_word_link VALUES (415,40,175,0,41,174,176,8,34);
INSERT INTO ezsearch_object_word_link VALUES (416,40,176,0,42,175,177,8,34);
INSERT INTO ezsearch_object_word_link VALUES (417,40,177,0,43,176,178,8,34);
INSERT INTO ezsearch_object_word_link VALUES (418,40,178,0,44,177,136,8,34);
INSERT INTO ezsearch_object_word_link VALUES (419,40,136,0,45,178,179,8,34);
INSERT INTO ezsearch_object_word_link VALUES (420,40,179,0,46,136,136,8,34);
INSERT INTO ezsearch_object_word_link VALUES (421,40,136,0,47,179,180,8,34);
INSERT INTO ezsearch_object_word_link VALUES (422,40,180,0,48,136,176,8,34);
INSERT INTO ezsearch_object_word_link VALUES (423,40,176,0,49,180,175,8,34);
INSERT INTO ezsearch_object_word_link VALUES (424,40,175,0,50,176,181,8,34);
INSERT INTO ezsearch_object_word_link VALUES (425,40,181,0,51,175,182,8,34);
INSERT INTO ezsearch_object_word_link VALUES (426,40,182,0,52,181,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (427,40,161,0,53,182,183,8,34);
INSERT INTO ezsearch_object_word_link VALUES (428,40,183,0,54,161,184,8,34);
INSERT INTO ezsearch_object_word_link VALUES (429,40,184,0,55,183,185,8,34);
INSERT INTO ezsearch_object_word_link VALUES (430,40,185,0,56,184,186,8,34);
INSERT INTO ezsearch_object_word_link VALUES (431,40,186,0,57,185,156,8,34);
INSERT INTO ezsearch_object_word_link VALUES (432,40,156,0,58,186,187,8,34);
INSERT INTO ezsearch_object_word_link VALUES (433,40,187,0,59,156,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (434,40,161,0,60,187,188,8,34);
INSERT INTO ezsearch_object_word_link VALUES (435,40,188,0,61,161,189,8,34);
INSERT INTO ezsearch_object_word_link VALUES (436,40,189,0,62,188,103,8,34);
INSERT INTO ezsearch_object_word_link VALUES (437,40,103,0,63,189,190,8,34);
INSERT INTO ezsearch_object_word_link VALUES (438,40,190,0,64,103,191,8,34);
INSERT INTO ezsearch_object_word_link VALUES (439,40,191,0,65,190,175,8,34);
INSERT INTO ezsearch_object_word_link VALUES (440,40,175,0,66,191,176,8,34);
INSERT INTO ezsearch_object_word_link VALUES (441,40,176,0,67,175,192,8,34);
INSERT INTO ezsearch_object_word_link VALUES (442,40,192,0,68,176,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (443,40,161,0,69,192,193,8,34);
INSERT INTO ezsearch_object_word_link VALUES (444,40,193,0,70,161,194,8,34);
INSERT INTO ezsearch_object_word_link VALUES (445,40,194,0,71,193,195,8,34);
INSERT INTO ezsearch_object_word_link VALUES (446,40,195,0,72,194,196,8,34);
INSERT INTO ezsearch_object_word_link VALUES (447,40,196,0,73,195,197,8,34);
INSERT INTO ezsearch_object_word_link VALUES (448,40,197,0,74,196,198,8,34);
INSERT INTO ezsearch_object_word_link VALUES (449,40,198,0,75,197,199,8,34);
INSERT INTO ezsearch_object_word_link VALUES (450,40,199,0,76,198,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (451,40,161,0,77,199,200,8,34);
INSERT INTO ezsearch_object_word_link VALUES (452,40,200,0,78,161,201,8,34);
INSERT INTO ezsearch_object_word_link VALUES (453,40,201,0,79,200,202,8,34);
INSERT INTO ezsearch_object_word_link VALUES (454,40,202,0,80,201,156,8,34);
INSERT INTO ezsearch_object_word_link VALUES (455,40,156,0,81,202,203,8,34);
INSERT INTO ezsearch_object_word_link VALUES (456,40,203,0,82,156,204,8,34);
INSERT INTO ezsearch_object_word_link VALUES (457,40,204,0,83,203,118,8,35);
INSERT INTO ezsearch_object_word_link VALUES (458,40,118,0,84,204,205,8,35);
INSERT INTO ezsearch_object_word_link VALUES (459,40,205,0,85,118,206,8,35);
INSERT INTO ezsearch_object_word_link VALUES (460,40,206,0,86,205,207,8,35);
INSERT INTO ezsearch_object_word_link VALUES (461,40,207,0,87,206,208,8,35);
INSERT INTO ezsearch_object_word_link VALUES (462,40,208,0,88,207,207,8,35);
INSERT INTO ezsearch_object_word_link VALUES (463,40,207,0,89,208,208,8,35);
INSERT INTO ezsearch_object_word_link VALUES (464,40,208,0,90,207,209,8,35);
INSERT INTO ezsearch_object_word_link VALUES (465,40,209,0,91,208,210,8,35);
INSERT INTO ezsearch_object_word_link VALUES (466,40,210,0,92,209,211,8,35);
INSERT INTO ezsearch_object_word_link VALUES (467,40,211,0,93,210,212,8,35);
INSERT INTO ezsearch_object_word_link VALUES (468,40,212,0,94,211,213,8,35);
INSERT INTO ezsearch_object_word_link VALUES (469,40,213,0,95,212,214,8,35);
INSERT INTO ezsearch_object_word_link VALUES (470,40,214,0,96,213,215,8,35);
INSERT INTO ezsearch_object_word_link VALUES (471,40,215,0,97,214,216,8,35);
INSERT INTO ezsearch_object_word_link VALUES (472,40,216,0,98,215,217,8,35);
INSERT INTO ezsearch_object_word_link VALUES (473,40,217,0,99,216,0,8,35);
INSERT INTO ezsearch_object_word_link VALUES (474,41,218,0,0,0,218,3,6);
INSERT INTO ezsearch_object_word_link VALUES (475,41,218,0,1,218,219,3,7);
INSERT INTO ezsearch_object_word_link VALUES (476,41,219,0,2,218,6,3,7);
INSERT INTO ezsearch_object_word_link VALUES (477,41,6,0,3,219,0,3,7);
INSERT INTO ezsearch_object_word_link VALUES (478,42,16,0,0,0,220,4,8);
INSERT INTO ezsearch_object_word_link VALUES (479,42,220,0,1,16,0,4,9);
INSERT INTO ezsearch_object_word_link VALUES (480,43,221,0,0,0,218,3,6);
INSERT INTO ezsearch_object_word_link VALUES (481,43,218,0,1,221,31,3,6);
INSERT INTO ezsearch_object_word_link VALUES (482,43,31,0,2,218,222,3,7);
INSERT INTO ezsearch_object_word_link VALUES (483,43,222,0,3,31,223,3,7);
INSERT INTO ezsearch_object_word_link VALUES (484,43,223,0,4,222,224,3,7);
INSERT INTO ezsearch_object_word_link VALUES (485,43,224,0,5,223,221,3,7);
INSERT INTO ezsearch_object_word_link VALUES (486,43,221,0,6,224,218,3,7);
INSERT INTO ezsearch_object_word_link VALUES (487,43,218,0,7,221,225,3,7);
INSERT INTO ezsearch_object_word_link VALUES (488,43,225,0,8,218,0,3,7);
INSERT INTO ezsearch_object_word_link VALUES (489,44,226,0,0,0,220,4,8);
INSERT INTO ezsearch_object_word_link VALUES (490,44,220,0,1,226,0,4,9);
INSERT INTO ezsearch_object_word_link VALUES (491,45,227,0,0,0,228,8,29);
INSERT INTO ezsearch_object_word_link VALUES (492,45,228,0,1,227,229,8,29);
INSERT INTO ezsearch_object_word_link VALUES (493,45,229,0,2,228,230,8,29);
INSERT INTO ezsearch_object_word_link VALUES (494,45,230,0,3,229,231,8,29);
INSERT INTO ezsearch_object_word_link VALUES (495,45,231,0,4,230,232,8,29);
INSERT INTO ezsearch_object_word_link VALUES (496,45,232,0,5,231,233,8,29);
INSERT INTO ezsearch_object_word_link VALUES (497,45,233,0,6,232,147,8,30);
INSERT INTO ezsearch_object_word_link VALUES (498,45,147,0,7,233,148,8,30);
INSERT INTO ezsearch_object_word_link VALUES (499,45,148,0,8,147,234,8,31);
INSERT INTO ezsearch_object_word_link VALUES (500,45,234,0,9,148,150,8,31);
INSERT INTO ezsearch_object_word_link VALUES (501,45,150,0,10,234,151,8,31);
INSERT INTO ezsearch_object_word_link VALUES (502,45,151,0,11,150,152,8,31);
INSERT INTO ezsearch_object_word_link VALUES (503,45,152,0,12,151,235,8,31);
INSERT INTO ezsearch_object_word_link VALUES (504,45,235,0,13,152,236,8,32);
INSERT INTO ezsearch_object_word_link VALUES (505,45,236,0,14,235,237,8,34);
INSERT INTO ezsearch_object_word_link VALUES (506,45,237,0,15,236,238,8,34);
INSERT INTO ezsearch_object_word_link VALUES (507,45,238,0,16,237,239,8,34);
INSERT INTO ezsearch_object_word_link VALUES (508,45,239,0,17,238,174,8,34);
INSERT INTO ezsearch_object_word_link VALUES (509,45,174,0,18,239,144,8,34);
INSERT INTO ezsearch_object_word_link VALUES (510,45,144,0,19,174,240,8,34);
INSERT INTO ezsearch_object_word_link VALUES (511,45,240,0,20,144,144,8,34);
INSERT INTO ezsearch_object_word_link VALUES (512,45,144,0,21,240,241,8,34);
INSERT INTO ezsearch_object_word_link VALUES (513,45,241,0,22,144,242,8,34);
INSERT INTO ezsearch_object_word_link VALUES (514,45,242,0,23,241,243,8,34);
INSERT INTO ezsearch_object_word_link VALUES (515,45,243,0,24,242,244,8,34);
INSERT INTO ezsearch_object_word_link VALUES (516,45,244,0,25,243,245,8,34);
INSERT INTO ezsearch_object_word_link VALUES (517,45,245,0,26,244,246,8,34);
INSERT INTO ezsearch_object_word_link VALUES (518,45,246,0,27,245,229,8,34);
INSERT INTO ezsearch_object_word_link VALUES (519,45,229,0,28,246,247,8,34);
INSERT INTO ezsearch_object_word_link VALUES (520,45,247,0,29,229,162,8,34);
INSERT INTO ezsearch_object_word_link VALUES (521,45,162,0,30,247,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (522,45,161,0,31,162,184,8,34);
INSERT INTO ezsearch_object_word_link VALUES (523,45,184,0,32,161,248,8,34);
INSERT INTO ezsearch_object_word_link VALUES (524,45,248,0,33,184,249,8,34);
INSERT INTO ezsearch_object_word_link VALUES (525,45,249,0,34,248,250,8,34);
INSERT INTO ezsearch_object_word_link VALUES (526,45,250,0,35,249,165,8,34);
INSERT INTO ezsearch_object_word_link VALUES (527,45,165,0,36,250,251,8,34);
INSERT INTO ezsearch_object_word_link VALUES (528,45,251,0,37,165,252,8,34);
INSERT INTO ezsearch_object_word_link VALUES (529,45,252,0,38,251,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (530,45,161,0,39,252,176,8,34);
INSERT INTO ezsearch_object_word_link VALUES (531,45,176,0,40,161,103,8,34);
INSERT INTO ezsearch_object_word_link VALUES (532,45,103,0,41,176,44,8,34);
INSERT INTO ezsearch_object_word_link VALUES (533,45,44,0,42,103,145,8,34);
INSERT INTO ezsearch_object_word_link VALUES (534,45,145,0,43,44,243,8,34);
INSERT INTO ezsearch_object_word_link VALUES (535,45,243,0,44,145,253,8,34);
INSERT INTO ezsearch_object_word_link VALUES (536,45,253,0,45,243,254,8,34);
INSERT INTO ezsearch_object_word_link VALUES (537,45,254,0,46,253,198,8,34);
INSERT INTO ezsearch_object_word_link VALUES (538,45,198,0,47,254,255,8,34);
INSERT INTO ezsearch_object_word_link VALUES (539,45,255,0,48,198,256,8,34);
INSERT INTO ezsearch_object_word_link VALUES (540,45,256,0,49,255,196,8,34);
INSERT INTO ezsearch_object_word_link VALUES (541,45,196,0,50,256,197,8,34);
INSERT INTO ezsearch_object_word_link VALUES (542,45,197,0,51,196,198,8,34);
INSERT INTO ezsearch_object_word_link VALUES (543,45,198,0,52,197,257,8,34);
INSERT INTO ezsearch_object_word_link VALUES (544,45,257,0,53,198,161,8,34);
INSERT INTO ezsearch_object_word_link VALUES (545,45,161,0,54,257,258,8,34);
INSERT INTO ezsearch_object_word_link VALUES (546,45,258,0,55,161,204,8,34);
INSERT INTO ezsearch_object_word_link VALUES (547,45,204,0,56,258,118,8,35);
INSERT INTO ezsearch_object_word_link VALUES (548,45,118,0,57,204,259,8,35);
INSERT INTO ezsearch_object_word_link VALUES (549,45,259,0,58,118,260,8,35);
INSERT INTO ezsearch_object_word_link VALUES (550,45,260,0,59,259,261,8,35);
INSERT INTO ezsearch_object_word_link VALUES (551,45,261,0,60,260,262,8,35);
INSERT INTO ezsearch_object_word_link VALUES (552,45,262,0,61,261,263,8,35);
INSERT INTO ezsearch_object_word_link VALUES (553,45,263,0,62,262,210,8,35);
INSERT INTO ezsearch_object_word_link VALUES (554,45,210,0,63,263,264,8,35);
INSERT INTO ezsearch_object_word_link VALUES (555,45,264,0,64,210,212,8,35);
INSERT INTO ezsearch_object_word_link VALUES (556,45,212,0,65,264,265,8,35);
INSERT INTO ezsearch_object_word_link VALUES (557,45,265,0,66,212,215,8,35);
INSERT INTO ezsearch_object_word_link VALUES (558,45,215,0,67,265,266,8,35);
INSERT INTO ezsearch_object_word_link VALUES (559,45,266,0,68,215,217,8,35);
INSERT INTO ezsearch_object_word_link VALUES (560,45,217,0,69,266,0,8,35);
INSERT INTO ezsearch_object_word_link VALUES (662,46,294,0,50,293,0,2,24);
INSERT INTO ezsearch_object_word_link VALUES (661,46,293,0,49,292,294,2,24);
INSERT INTO ezsearch_object_word_link VALUES (660,46,292,0,48,291,293,2,24);
INSERT INTO ezsearch_object_word_link VALUES (659,46,291,0,47,290,292,2,24);
INSERT INTO ezsearch_object_word_link VALUES (658,46,290,0,46,289,291,2,24);
INSERT INTO ezsearch_object_word_link VALUES (657,46,289,0,45,176,290,2,24);
INSERT INTO ezsearch_object_word_link VALUES (656,46,176,0,44,282,289,2,24);
INSERT INTO ezsearch_object_word_link VALUES (655,46,282,0,43,288,176,2,24);
INSERT INTO ezsearch_object_word_link VALUES (654,46,288,0,42,176,282,2,24);
INSERT INTO ezsearch_object_word_link VALUES (653,46,176,0,41,120,288,2,24);
INSERT INTO ezsearch_object_word_link VALUES (652,46,120,0,40,120,176,2,24);
INSERT INTO ezsearch_object_word_link VALUES (651,46,120,0,39,287,120,2,23);
INSERT INTO ezsearch_object_word_link VALUES (650,46,287,0,38,286,120,2,23);
INSERT INTO ezsearch_object_word_link VALUES (649,46,286,0,37,281,287,2,23);
INSERT INTO ezsearch_object_word_link VALUES (648,46,281,0,36,159,286,2,23);
INSERT INTO ezsearch_object_word_link VALUES (647,46,159,0,35,285,281,2,23);
INSERT INTO ezsearch_object_word_link VALUES (646,46,285,0,34,198,159,2,23);
INSERT INTO ezsearch_object_word_link VALUES (645,46,198,0,33,284,285,2,23);
INSERT INTO ezsearch_object_word_link VALUES (644,46,284,0,32,120,198,2,23);
INSERT INTO ezsearch_object_word_link VALUES (643,46,120,0,31,287,284,2,23);
INSERT INTO ezsearch_object_word_link VALUES (642,46,287,0,30,286,120,2,23);
INSERT INTO ezsearch_object_word_link VALUES (641,46,286,0,29,281,287,2,23);
INSERT INTO ezsearch_object_word_link VALUES (640,46,281,0,28,159,286,2,23);
INSERT INTO ezsearch_object_word_link VALUES (639,46,159,0,27,285,281,2,23);
INSERT INTO ezsearch_object_word_link VALUES (638,46,285,0,26,198,159,2,23);
INSERT INTO ezsearch_object_word_link VALUES (637,46,198,0,25,284,285,2,23);
INSERT INTO ezsearch_object_word_link VALUES (636,46,284,0,24,120,198,2,23);
INSERT INTO ezsearch_object_word_link VALUES (635,46,120,0,23,287,284,2,23);
INSERT INTO ezsearch_object_word_link VALUES (634,46,287,0,22,286,120,2,23);
INSERT INTO ezsearch_object_word_link VALUES (633,46,286,0,21,281,287,2,23);
INSERT INTO ezsearch_object_word_link VALUES (632,46,281,0,20,159,286,2,23);
INSERT INTO ezsearch_object_word_link VALUES (631,46,159,0,19,285,281,2,23);
INSERT INTO ezsearch_object_word_link VALUES (630,46,285,0,18,198,159,2,23);
INSERT INTO ezsearch_object_word_link VALUES (629,46,198,0,17,284,285,2,23);
INSERT INTO ezsearch_object_word_link VALUES (628,46,284,0,16,120,198,2,23);
INSERT INTO ezsearch_object_word_link VALUES (627,46,120,0,15,287,284,2,23);
INSERT INTO ezsearch_object_word_link VALUES (626,46,287,0,14,286,120,2,23);
INSERT INTO ezsearch_object_word_link VALUES (625,46,286,0,13,281,287,2,23);
INSERT INTO ezsearch_object_word_link VALUES (624,46,281,0,12,159,286,2,23);
INSERT INTO ezsearch_object_word_link VALUES (623,46,159,0,11,285,281,2,23);
INSERT INTO ezsearch_object_word_link VALUES (622,46,285,0,10,198,159,2,23);
INSERT INTO ezsearch_object_word_link VALUES (621,46,198,0,9,284,285,2,23);
INSERT INTO ezsearch_object_word_link VALUES (620,46,284,0,8,138,198,2,23);
INSERT INTO ezsearch_object_word_link VALUES (619,46,138,0,7,137,284,2,22);
INSERT INTO ezsearch_object_word_link VALUES (618,46,137,0,6,170,138,2,22);
INSERT INTO ezsearch_object_word_link VALUES (617,46,170,0,5,283,137,2,21);
INSERT INTO ezsearch_object_word_link VALUES (616,46,283,0,4,176,170,2,21);
INSERT INTO ezsearch_object_word_link VALUES (615,46,176,0,3,282,283,2,21);
INSERT INTO ezsearch_object_word_link VALUES (614,46,282,0,2,144,176,2,21);
INSERT INTO ezsearch_object_word_link VALUES (613,46,144,0,1,281,282,2,1);
INSERT INTO ezsearch_object_word_link VALUES (612,46,281,0,0,0,144,2,1);
INSERT INTO ezsearch_object_word_link VALUES (674,47,299,0,5,302,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (673,47,302,0,4,301,299,1,5);
INSERT INTO ezsearch_object_word_link VALUES (672,47,301,0,3,300,302,1,5);
INSERT INTO ezsearch_object_word_link VALUES (671,47,300,0,2,120,301,1,5);
INSERT INTO ezsearch_object_word_link VALUES (670,47,120,0,1,299,300,1,5);
INSERT INTO ezsearch_object_word_link VALUES (669,47,299,0,0,0,120,1,4);
INSERT INTO ezsearch_object_word_link VALUES (675,49,303,0,0,0,304,9,36);
INSERT INTO ezsearch_object_word_link VALUES (676,49,304,0,1,303,0,9,36);
INSERT INTO ezsearch_object_word_link VALUES (677,50,220,0,0,0,305,1,4);
INSERT INTO ezsearch_object_word_link VALUES (678,50,305,0,1,220,220,1,4);
INSERT INTO ezsearch_object_word_link VALUES (679,50,220,0,2,305,305,1,5);
INSERT INTO ezsearch_object_word_link VALUES (680,50,305,0,3,220,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (681,51,306,0,0,0,307,2,1);
INSERT INTO ezsearch_object_word_link VALUES (682,51,307,0,1,306,308,2,21);
INSERT INTO ezsearch_object_word_link VALUES (683,51,308,0,2,307,309,2,22);
INSERT INTO ezsearch_object_word_link VALUES (684,51,309,0,3,308,310,2,23);
INSERT INTO ezsearch_object_word_link VALUES (685,51,310,0,4,309,0,2,24);
INSERT INTO ezsearch_object_word_link VALUES (686,52,50,0,0,0,50,4,8);
INSERT INTO ezsearch_object_word_link VALUES (687,52,50,0,1,50,0,4,9);
INSERT INTO ezsearch_object_word_link VALUES (688,53,311,0,0,0,311,4,8);
INSERT INTO ezsearch_object_word_link VALUES (689,53,311,0,1,311,0,4,9);
INSERT INTO ezsearch_object_word_link VALUES (690,54,303,0,0,0,304,9,36);
INSERT INTO ezsearch_object_word_link VALUES (691,54,304,0,1,303,0,9,36);
INSERT INTO ezsearch_object_word_link VALUES (692,55,303,0,0,0,304,9,36);
INSERT INTO ezsearch_object_word_link VALUES (693,55,304,0,1,303,312,9,36);
INSERT INTO ezsearch_object_word_link VALUES (694,55,312,0,2,304,313,9,39);
INSERT INTO ezsearch_object_word_link VALUES (695,55,313,0,3,312,314,9,39);
INSERT INTO ezsearch_object_word_link VALUES (696,55,314,0,4,313,315,9,39);
INSERT INTO ezsearch_object_word_link VALUES (697,55,315,0,5,314,316,9,39);
INSERT INTO ezsearch_object_word_link VALUES (698,55,316,0,6,315,317,9,39);
INSERT INTO ezsearch_object_word_link VALUES (699,55,317,0,7,316,0,9,39);
INSERT INTO ezsearch_object_word_link VALUES (700,56,318,0,0,0,319,10,40);
INSERT INTO ezsearch_object_word_link VALUES (701,56,319,0,1,318,320,10,41);
INSERT INTO ezsearch_object_word_link VALUES (702,56,320,0,2,319,321,10,42);
INSERT INTO ezsearch_object_word_link VALUES (703,56,321,0,3,320,322,10,42);
INSERT INTO ezsearch_object_word_link VALUES (704,56,322,0,4,321,323,10,42);
INSERT INTO ezsearch_object_word_link VALUES (705,56,323,0,5,322,324,10,42);
INSERT INTO ezsearch_object_word_link VALUES (706,56,324,0,6,323,325,10,42);
INSERT INTO ezsearch_object_word_link VALUES (707,56,325,0,7,324,320,10,42);
INSERT INTO ezsearch_object_word_link VALUES (708,56,320,0,8,325,324,10,42);
INSERT INTO ezsearch_object_word_link VALUES (709,56,324,0,9,320,326,10,42);
INSERT INTO ezsearch_object_word_link VALUES (710,56,326,0,10,324,321,10,42);
INSERT INTO ezsearch_object_word_link VALUES (711,56,321,0,11,326,322,10,42);
INSERT INTO ezsearch_object_word_link VALUES (712,56,322,0,12,321,327,10,42);
INSERT INTO ezsearch_object_word_link VALUES (713,56,327,0,13,322,324,10,42);
INSERT INTO ezsearch_object_word_link VALUES (714,56,324,0,14,327,328,10,42);
INSERT INTO ezsearch_object_word_link VALUES (715,56,328,0,15,324,321,10,42);
INSERT INTO ezsearch_object_word_link VALUES (716,56,321,0,16,328,329,10,42);
INSERT INTO ezsearch_object_word_link VALUES (717,56,329,0,17,321,330,10,42);
INSERT INTO ezsearch_object_word_link VALUES (718,56,330,0,18,329,324,10,42);
INSERT INTO ezsearch_object_word_link VALUES (719,56,324,0,19,330,331,10,42);
INSERT INTO ezsearch_object_word_link VALUES (720,56,331,0,20,324,328,10,42);
INSERT INTO ezsearch_object_word_link VALUES (721,56,328,0,21,331,324,10,42);
INSERT INTO ezsearch_object_word_link VALUES (722,56,324,0,22,328,332,10,42);
INSERT INTO ezsearch_object_word_link VALUES (723,56,332,0,23,324,333,10,42);
INSERT INTO ezsearch_object_word_link VALUES (724,56,333,0,24,332,324,10,42);
INSERT INTO ezsearch_object_word_link VALUES (725,56,324,0,25,333,334,10,42);
INSERT INTO ezsearch_object_word_link VALUES (726,56,334,0,26,324,326,10,42);
INSERT INTO ezsearch_object_word_link VALUES (727,56,326,0,27,334,324,10,42);
INSERT INTO ezsearch_object_word_link VALUES (728,56,324,0,28,326,0,10,42);
INSERT INTO ezsearch_object_word_link VALUES (729,58,335,0,0,0,336,7,26);
INSERT INTO ezsearch_object_word_link VALUES (730,58,336,0,1,335,50,7,26);
INSERT INTO ezsearch_object_word_link VALUES (731,58,50,0,2,336,337,7,26);
INSERT INTO ezsearch_object_word_link VALUES (732,58,337,0,3,50,338,7,26);
INSERT INTO ezsearch_object_word_link VALUES (733,58,338,0,4,337,82,7,26);
INSERT INTO ezsearch_object_word_link VALUES (734,58,82,0,5,338,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (735,60,7,0,0,0,339,12,47);
INSERT INTO ezsearch_object_word_link VALUES (736,60,339,0,1,7,340,12,47);
INSERT INTO ezsearch_object_word_link VALUES (737,60,340,0,2,339,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (743,68,344,0,2,343,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (742,68,343,0,1,81,344,12,47);
INSERT INTO ezsearch_object_word_link VALUES (741,68,81,0,0,0,343,12,47);
INSERT INTO ezsearch_object_word_link VALUES (744,69,81,0,0,0,343,12,47);
INSERT INTO ezsearch_object_word_link VALUES (745,69,343,0,1,81,345,12,47);
INSERT INTO ezsearch_object_word_link VALUES (746,69,345,0,2,343,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (747,70,346,0,0,0,347,12,47);
INSERT INTO ezsearch_object_word_link VALUES (748,70,347,0,1,346,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (749,71,348,0,0,0,349,5,14);
INSERT INTO ezsearch_object_word_link VALUES (750,71,349,0,1,348,350,5,15);
INSERT INTO ezsearch_object_word_link VALUES (751,71,350,0,2,349,0,5,16);
INSERT INTO ezsearch_object_word_link VALUES (752,76,351,0,0,0,352,1,4);
INSERT INTO ezsearch_object_word_link VALUES (753,76,352,0,1,351,353,1,5);
INSERT INTO ezsearch_object_word_link VALUES (754,76,353,0,2,352,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (772,77,365,0,8,364,0,2,24);
INSERT INTO ezsearch_object_word_link VALUES (771,77,364,0,7,363,365,2,24);
INSERT INTO ezsearch_object_word_link VALUES (770,77,363,0,6,254,364,2,23);
INSERT INTO ezsearch_object_word_link VALUES (769,77,254,0,5,362,363,2,22);
INSERT INTO ezsearch_object_word_link VALUES (768,77,362,0,4,361,254,2,22);
INSERT INTO ezsearch_object_word_link VALUES (767,77,361,0,3,50,362,2,22);
INSERT INTO ezsearch_object_word_link VALUES (766,77,50,0,2,338,361,2,1);
INSERT INTO ezsearch_object_word_link VALUES (765,77,338,0,1,360,50,2,1);
INSERT INTO ezsearch_object_word_link VALUES (764,77,360,0,0,0,338,2,1);
INSERT INTO ezsearch_object_word_link VALUES (773,78,366,0,0,0,367,1,4);
INSERT INTO ezsearch_object_word_link VALUES (774,78,367,0,1,366,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (786,79,50,0,2,50,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (785,79,50,0,1,371,50,1,4);
INSERT INTO ezsearch_object_word_link VALUES (784,79,371,0,0,0,50,1,4);
INSERT INTO ezsearch_object_word_link VALUES (787,80,372,0,0,0,367,1,4);
INSERT INTO ezsearch_object_word_link VALUES (788,80,367,0,1,372,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (795,86,81,0,0,0,17,2,1);
INSERT INTO ezsearch_object_word_link VALUES (796,86,17,0,1,81,366,2,1);
INSERT INTO ezsearch_object_word_link VALUES (797,86,366,0,2,17,361,2,21);
INSERT INTO ezsearch_object_word_link VALUES (798,86,361,0,3,366,82,2,22);
INSERT INTO ezsearch_object_word_link VALUES (799,86,82,0,4,361,82,2,23);
INSERT INTO ezsearch_object_word_link VALUES (800,86,82,0,5,82,0,2,24);
INSERT INTO ezsearch_object_word_link VALUES (2443,32,925,0,0,0,7,1,4);
INSERT INTO ezsearch_object_word_link VALUES (933,88,33,0,0,0,34,2,1);
INSERT INTO ezsearch_object_word_link VALUES (934,88,34,0,1,33,381,2,1);
INSERT INTO ezsearch_object_word_link VALUES (935,88,381,0,2,34,376,2,1);
INSERT INTO ezsearch_object_word_link VALUES (936,88,376,0,3,381,285,2,1);
INSERT INTO ezsearch_object_word_link VALUES (937,88,285,0,4,376,382,2,21);
INSERT INTO ezsearch_object_word_link VALUES (938,88,382,0,5,285,50,2,21);
INSERT INTO ezsearch_object_word_link VALUES (939,88,50,0,6,382,136,2,21);
INSERT INTO ezsearch_object_word_link VALUES (940,88,136,0,7,50,383,2,21);
INSERT INTO ezsearch_object_word_link VALUES (941,88,383,0,8,136,137,2,21);
INSERT INTO ezsearch_object_word_link VALUES (942,88,137,0,9,383,138,2,22);
INSERT INTO ezsearch_object_word_link VALUES (943,88,138,0,10,137,282,2,22);
INSERT INTO ezsearch_object_word_link VALUES (944,88,282,0,11,138,176,2,23);
INSERT INTO ezsearch_object_word_link VALUES (945,88,176,0,12,282,285,2,23);
INSERT INTO ezsearch_object_word_link VALUES (946,88,285,0,13,176,382,2,23);
INSERT INTO ezsearch_object_word_link VALUES (947,88,382,0,14,285,50,2,23);
INSERT INTO ezsearch_object_word_link VALUES (948,88,50,0,15,382,136,2,23);
INSERT INTO ezsearch_object_word_link VALUES (949,88,136,0,16,50,383,2,23);
INSERT INTO ezsearch_object_word_link VALUES (950,88,383,0,17,136,103,2,23);
INSERT INTO ezsearch_object_word_link VALUES (951,88,103,0,18,383,33,2,23);
INSERT INTO ezsearch_object_word_link VALUES (952,88,33,0,19,103,34,2,23);
INSERT INTO ezsearch_object_word_link VALUES (953,88,34,0,20,33,35,2,23);
INSERT INTO ezsearch_object_word_link VALUES (954,88,35,0,21,34,36,2,23);
INSERT INTO ezsearch_object_word_link VALUES (955,88,36,0,22,35,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (956,88,377,0,23,36,384,2,23);
INSERT INTO ezsearch_object_word_link VALUES (957,88,384,0,24,377,385,2,23);
INSERT INTO ezsearch_object_word_link VALUES (958,88,385,0,25,384,386,2,23);
INSERT INTO ezsearch_object_word_link VALUES (959,88,386,0,26,385,387,2,23);
INSERT INTO ezsearch_object_word_link VALUES (960,88,387,0,27,386,388,2,23);
INSERT INTO ezsearch_object_word_link VALUES (961,88,388,0,28,387,389,2,23);
INSERT INTO ezsearch_object_word_link VALUES (962,88,389,0,29,388,390,2,23);
INSERT INTO ezsearch_object_word_link VALUES (963,88,390,0,30,389,391,2,23);
INSERT INTO ezsearch_object_word_link VALUES (964,88,391,0,31,390,392,2,23);
INSERT INTO ezsearch_object_word_link VALUES (965,88,392,0,32,391,393,2,23);
INSERT INTO ezsearch_object_word_link VALUES (966,88,393,0,33,392,394,2,23);
INSERT INTO ezsearch_object_word_link VALUES (967,88,394,0,34,393,395,2,23);
INSERT INTO ezsearch_object_word_link VALUES (968,88,395,0,35,394,396,2,23);
INSERT INTO ezsearch_object_word_link VALUES (969,88,396,0,36,395,397,2,23);
INSERT INTO ezsearch_object_word_link VALUES (970,88,397,0,37,396,398,2,23);
INSERT INTO ezsearch_object_word_link VALUES (971,88,398,0,38,397,399,2,23);
INSERT INTO ezsearch_object_word_link VALUES (972,88,399,0,39,398,400,2,23);
INSERT INTO ezsearch_object_word_link VALUES (973,88,400,0,40,399,401,2,23);
INSERT INTO ezsearch_object_word_link VALUES (974,88,401,0,41,400,402,2,23);
INSERT INTO ezsearch_object_word_link VALUES (975,88,402,0,42,401,403,2,23);
INSERT INTO ezsearch_object_word_link VALUES (976,88,403,0,43,402,397,2,23);
INSERT INTO ezsearch_object_word_link VALUES (977,88,397,0,44,403,404,2,23);
INSERT INTO ezsearch_object_word_link VALUES (978,88,404,0,45,397,405,2,23);
INSERT INTO ezsearch_object_word_link VALUES (979,88,405,0,46,404,406,2,23);
INSERT INTO ezsearch_object_word_link VALUES (980,88,406,0,47,405,407,2,23);
INSERT INTO ezsearch_object_word_link VALUES (981,88,407,0,48,406,408,2,23);
INSERT INTO ezsearch_object_word_link VALUES (982,88,408,0,49,407,409,2,23);
INSERT INTO ezsearch_object_word_link VALUES (983,88,409,0,50,408,410,2,23);
INSERT INTO ezsearch_object_word_link VALUES (984,88,410,0,51,409,411,2,23);
INSERT INTO ezsearch_object_word_link VALUES (985,88,411,0,52,410,412,2,23);
INSERT INTO ezsearch_object_word_link VALUES (986,88,412,0,53,411,413,2,23);
INSERT INTO ezsearch_object_word_link VALUES (987,88,413,0,54,412,414,2,23);
INSERT INTO ezsearch_object_word_link VALUES (988,88,414,0,55,413,415,2,23);
INSERT INTO ezsearch_object_word_link VALUES (989,88,415,0,56,414,416,2,23);
INSERT INTO ezsearch_object_word_link VALUES (990,88,416,0,57,415,397,2,23);
INSERT INTO ezsearch_object_word_link VALUES (991,88,397,0,58,416,417,2,23);
INSERT INTO ezsearch_object_word_link VALUES (992,88,417,0,59,397,418,2,23);
INSERT INTO ezsearch_object_word_link VALUES (993,88,418,0,60,417,419,2,23);
INSERT INTO ezsearch_object_word_link VALUES (994,88,419,0,61,418,420,2,23);
INSERT INTO ezsearch_object_word_link VALUES (995,88,420,0,62,419,421,2,23);
INSERT INTO ezsearch_object_word_link VALUES (996,88,421,0,63,420,422,2,23);
INSERT INTO ezsearch_object_word_link VALUES (997,88,422,0,64,421,423,2,23);
INSERT INTO ezsearch_object_word_link VALUES (998,88,423,0,65,422,424,2,23);
INSERT INTO ezsearch_object_word_link VALUES (999,88,424,0,66,423,425,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1000,88,425,0,67,424,426,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1001,88,426,0,68,425,385,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1002,88,385,0,69,426,427,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1003,88,427,0,70,385,428,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1004,88,428,0,71,427,427,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1005,88,427,0,72,428,429,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1006,88,429,0,73,427,430,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1007,88,430,0,74,429,431,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1008,88,431,0,75,430,432,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1009,88,432,0,76,431,421,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1010,88,421,0,77,432,424,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1011,88,424,0,78,421,433,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1012,88,433,0,79,424,399,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1013,88,399,0,80,433,434,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1014,88,434,0,81,399,435,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1015,88,435,0,82,434,436,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1016,88,436,0,83,435,437,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1017,88,437,0,84,436,438,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1018,88,438,0,85,437,439,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1019,88,439,0,86,438,440,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1020,88,440,0,87,439,289,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1021,88,289,0,88,440,441,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1022,88,441,0,89,289,289,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1023,88,289,0,90,441,442,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1024,88,442,0,91,289,443,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1025,88,443,0,92,442,444,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1026,88,444,0,93,443,445,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1027,88,445,0,94,444,446,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1028,88,446,0,95,445,447,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1029,88,447,0,96,446,448,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1030,88,448,0,97,447,449,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1031,88,449,0,98,448,450,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1032,88,450,0,99,449,377,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1033,88,377,0,100,450,384,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1034,88,384,0,101,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1035,88,377,0,102,384,384,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1036,88,384,0,103,377,385,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1037,88,385,0,104,384,386,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1038,88,386,0,105,385,387,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1039,88,387,0,106,386,388,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1040,88,388,0,107,387,389,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1041,88,389,0,108,388,390,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1042,88,390,0,109,389,391,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1043,88,391,0,110,390,392,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1044,88,392,0,111,391,393,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1045,88,393,0,112,392,394,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1046,88,394,0,113,393,395,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1047,88,395,0,114,394,396,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1048,88,396,0,115,395,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1049,88,397,0,116,396,398,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1050,88,398,0,117,397,399,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1051,88,399,0,118,398,400,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1052,88,400,0,119,399,401,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1053,88,401,0,120,400,402,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1054,88,402,0,121,401,403,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1055,88,403,0,122,402,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1056,88,397,0,123,403,404,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1057,88,404,0,124,397,405,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1058,88,405,0,125,404,406,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1059,88,406,0,126,405,407,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1060,88,407,0,127,406,408,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1061,88,408,0,128,407,409,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1062,88,409,0,129,408,410,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1063,88,410,0,130,409,411,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1064,88,411,0,131,410,412,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1065,88,412,0,132,411,413,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1066,88,413,0,133,412,414,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1067,88,414,0,134,413,415,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1068,88,415,0,135,414,416,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1069,88,416,0,136,415,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1070,88,397,0,137,416,417,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1071,88,417,0,138,397,418,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1072,88,418,0,139,417,419,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1073,88,419,0,140,418,420,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1074,88,420,0,141,419,421,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1075,88,421,0,142,420,422,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1076,88,422,0,143,421,423,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1077,88,423,0,144,422,424,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1078,88,424,0,145,423,425,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1079,88,425,0,146,424,426,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1080,88,426,0,147,425,385,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1081,88,385,0,148,426,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1082,88,427,0,149,385,428,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1083,88,428,0,150,427,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1084,88,427,0,151,428,429,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1085,88,429,0,152,427,430,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1086,88,430,0,153,429,431,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1087,88,431,0,154,430,432,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1088,88,432,0,155,431,421,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1089,88,421,0,156,432,424,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1090,88,424,0,157,421,433,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1091,88,433,0,158,424,399,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1092,88,399,0,159,433,434,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1093,88,434,0,160,399,435,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1094,88,435,0,161,434,436,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1095,88,436,0,162,435,437,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1096,88,437,0,163,436,438,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1097,88,438,0,164,437,439,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1098,88,439,0,165,438,440,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1099,88,440,0,166,439,289,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1100,88,289,0,167,440,441,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1101,88,441,0,168,289,289,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1102,88,289,0,169,441,442,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1103,88,442,0,170,289,443,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1104,88,443,0,171,442,444,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1105,88,444,0,172,443,445,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1106,88,445,0,173,444,446,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1107,88,446,0,174,445,447,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1108,88,447,0,175,446,448,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1109,88,448,0,176,447,449,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1110,88,449,0,177,448,450,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1111,88,450,0,178,449,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1112,88,377,0,179,450,384,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1113,88,384,0,180,377,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1114,88,377,0,181,384,384,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1115,88,384,0,182,377,385,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1116,88,385,0,183,384,386,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1117,88,386,0,184,385,387,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1118,88,387,0,185,386,388,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1119,88,388,0,186,387,389,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1120,88,389,0,187,388,390,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1121,88,390,0,188,389,391,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1122,88,391,0,189,390,392,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1123,88,392,0,190,391,393,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1124,88,393,0,191,392,394,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1125,88,394,0,192,393,395,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1126,88,395,0,193,394,396,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1127,88,396,0,194,395,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1128,88,397,0,195,396,398,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1129,88,398,0,196,397,399,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1130,88,399,0,197,398,400,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1131,88,400,0,198,399,401,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1132,88,401,0,199,400,402,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1133,88,402,0,200,401,403,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1134,88,403,0,201,402,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1135,88,397,0,202,403,404,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1136,88,404,0,203,397,405,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1137,88,405,0,204,404,406,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1138,88,406,0,205,405,407,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1139,88,407,0,206,406,408,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1140,88,408,0,207,407,409,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1141,88,409,0,208,408,410,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1142,88,410,0,209,409,411,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1143,88,411,0,210,410,412,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1144,88,412,0,211,411,413,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1145,88,413,0,212,412,414,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1146,88,414,0,213,413,415,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1147,88,415,0,214,414,416,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1148,88,416,0,215,415,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1149,88,397,0,216,416,417,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1150,88,417,0,217,397,418,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1151,88,418,0,218,417,419,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1152,88,419,0,219,418,420,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1153,88,420,0,220,419,421,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1154,88,421,0,221,420,422,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1155,88,422,0,222,421,423,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1156,88,423,0,223,422,424,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1157,88,424,0,224,423,425,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1158,88,425,0,225,424,426,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1159,88,426,0,226,425,385,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1160,88,385,0,227,426,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1161,88,427,0,228,385,428,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1162,88,428,0,229,427,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1163,88,427,0,230,428,429,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1164,88,429,0,231,427,430,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1165,88,430,0,232,429,431,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1166,88,431,0,233,430,432,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1167,88,432,0,234,431,421,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1168,88,421,0,235,432,424,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1169,88,424,0,236,421,433,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1170,88,433,0,237,424,399,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1171,88,399,0,238,433,434,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1172,88,434,0,239,399,435,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1173,88,435,0,240,434,436,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1174,88,436,0,241,435,437,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1175,88,437,0,242,436,438,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1176,88,438,0,243,437,439,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1177,88,439,0,244,438,440,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1178,88,440,0,245,439,289,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1179,88,289,0,246,440,441,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1180,88,441,0,247,289,289,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1181,88,289,0,248,441,442,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1182,88,442,0,249,289,443,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1183,88,443,0,250,442,444,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1184,88,444,0,251,443,445,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1185,88,445,0,252,444,446,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1186,88,446,0,253,445,447,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1187,88,447,0,254,446,448,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1188,88,448,0,255,447,449,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1189,88,449,0,256,448,450,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1190,88,450,0,257,449,393,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1191,88,393,0,258,450,394,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1192,88,394,0,259,393,377,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1193,88,377,0,260,394,384,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1194,88,384,0,261,377,385,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1195,88,385,0,262,384,386,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1196,88,386,0,263,385,387,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1197,88,387,0,264,386,388,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1198,88,388,0,265,387,389,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1199,88,389,0,266,388,390,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1200,88,390,0,267,389,391,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1201,88,391,0,268,390,392,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1202,88,392,0,269,391,393,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1203,88,393,0,270,392,394,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1204,88,394,0,271,393,395,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1205,88,395,0,272,394,396,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1206,88,396,0,273,395,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1207,88,397,0,274,396,398,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1208,88,398,0,275,397,399,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1209,88,399,0,276,398,400,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1210,88,400,0,277,399,401,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1211,88,401,0,278,400,402,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1212,88,402,0,279,401,403,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1213,88,403,0,280,402,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1214,88,397,0,281,403,404,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1215,88,404,0,282,397,405,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1216,88,405,0,283,404,406,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1217,88,406,0,284,405,407,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1218,88,407,0,285,406,408,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1219,88,408,0,286,407,409,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1220,88,409,0,287,408,410,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1221,88,410,0,288,409,411,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1222,88,411,0,289,410,412,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1223,88,412,0,290,411,413,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1224,88,413,0,291,412,414,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1225,88,414,0,292,413,415,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1226,88,415,0,293,414,416,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1227,88,416,0,294,415,397,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1228,88,397,0,295,416,417,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1229,88,417,0,296,397,418,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1230,88,418,0,297,417,419,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1231,88,419,0,298,418,420,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1232,88,420,0,299,419,421,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1233,88,421,0,300,420,422,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1234,88,422,0,301,421,423,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1235,88,423,0,302,422,424,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1236,88,424,0,303,423,425,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1237,88,425,0,304,424,426,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1238,88,426,0,305,425,385,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1239,88,385,0,306,426,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1240,88,427,0,307,385,428,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1241,88,428,0,308,427,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1242,88,427,0,309,428,429,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1243,88,429,0,310,427,430,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1244,88,430,0,311,429,431,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1245,88,431,0,312,430,432,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1246,88,432,0,313,431,421,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1247,88,421,0,314,432,424,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1248,88,424,0,315,421,433,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1249,88,433,0,316,424,399,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1250,88,399,0,317,433,434,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1251,88,434,0,318,399,435,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1252,88,435,0,319,434,436,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1253,88,436,0,320,435,437,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1254,88,437,0,321,436,438,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1255,88,438,0,322,437,439,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1256,88,439,0,323,438,440,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1257,88,440,0,324,439,289,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1258,88,289,0,325,440,441,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1259,88,441,0,326,289,289,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1260,88,289,0,327,441,442,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1261,88,442,0,328,289,443,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1262,88,443,0,329,442,444,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1263,88,444,0,330,443,445,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1264,88,445,0,331,444,446,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1265,88,446,0,332,445,447,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1266,88,447,0,333,446,448,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1267,88,448,0,334,447,449,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1268,88,449,0,335,448,450,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1269,88,450,0,336,449,0,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1270,89,451,0,0,0,9,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1271,89,9,0,1,451,452,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1272,89,452,0,2,9,453,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1273,89,453,0,3,452,454,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1274,89,454,0,4,453,455,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1275,89,455,0,5,454,456,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1276,89,456,0,6,455,453,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1277,89,453,0,7,456,144,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1278,89,144,0,8,453,457,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1279,89,457,0,9,144,176,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1280,89,176,0,10,457,143,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1281,89,143,0,11,176,458,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1282,89,458,0,12,143,459,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1283,89,459,0,13,458,460,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1284,89,460,0,14,459,461,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1285,89,461,0,15,460,165,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1286,89,165,0,16,461,462,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1287,89,462,0,17,165,463,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1288,89,463,0,18,462,464,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1289,89,464,0,19,463,246,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1290,89,246,0,20,464,465,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1291,89,465,0,21,246,466,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1292,89,466,0,22,465,103,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1293,89,103,0,23,466,289,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1294,89,289,0,24,103,467,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1295,89,467,0,25,289,468,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1296,89,468,0,26,467,469,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1297,89,469,0,27,468,470,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1298,89,470,0,28,469,471,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1299,89,471,0,29,470,338,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1300,89,338,0,30,471,472,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1301,89,472,0,31,338,473,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1302,89,473,0,32,472,161,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1303,89,161,0,33,473,474,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1304,89,474,0,34,161,475,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1305,89,475,0,35,474,476,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1306,89,476,0,36,475,477,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1307,89,477,0,37,476,478,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1308,89,478,0,38,477,136,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1309,89,136,0,39,478,479,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1310,89,479,0,40,136,198,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1311,89,198,0,41,479,480,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1312,89,480,0,42,198,481,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1313,89,481,0,43,480,482,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1314,89,482,0,44,481,292,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1315,89,292,0,45,482,285,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1316,89,285,0,46,292,483,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1317,89,483,0,47,285,484,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1318,89,484,0,48,483,485,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1319,89,485,0,49,484,486,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1320,89,486,0,50,485,463,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1321,89,463,0,51,486,487,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1322,89,487,0,52,463,488,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1323,89,488,0,53,487,161,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1324,89,161,0,54,488,489,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1325,89,489,0,55,161,459,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1326,89,459,0,56,489,490,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1327,89,490,0,57,459,491,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1328,89,491,0,58,490,492,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1329,89,492,0,59,491,493,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1330,89,493,0,60,492,485,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1331,89,485,0,61,493,494,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1332,89,494,0,62,485,285,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1333,89,285,0,63,494,495,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1334,89,495,0,64,285,496,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1335,89,496,0,65,495,497,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1336,89,497,0,66,496,254,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1337,89,254,0,67,497,498,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1338,89,498,0,68,254,499,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1339,89,499,0,69,498,500,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1340,89,500,0,70,499,161,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1341,89,161,0,71,500,501,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1342,89,501,0,72,161,502,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1343,89,502,0,73,501,254,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1344,89,254,0,74,502,503,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1345,89,503,0,75,254,493,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1346,89,493,0,76,503,453,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1347,89,453,0,77,493,504,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1348,89,504,0,78,453,505,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1349,89,505,0,79,504,176,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1350,89,176,0,80,505,506,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1351,89,506,0,81,176,507,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1352,89,507,0,82,506,508,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1353,89,508,0,83,507,509,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1354,89,509,0,84,508,510,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1355,89,510,0,85,509,511,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1356,89,511,0,86,510,338,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1357,89,338,0,87,511,512,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1358,89,512,0,88,338,497,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1359,89,497,0,89,512,459,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1360,89,459,0,90,497,513,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1361,89,513,0,91,459,103,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1362,89,103,0,92,513,514,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1363,89,514,0,93,103,485,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1364,89,485,0,94,514,515,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1365,89,515,0,95,485,516,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1366,89,516,0,96,515,458,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1367,89,458,0,97,516,509,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1368,89,509,0,98,458,517,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1369,89,517,0,99,509,338,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1370,89,338,0,100,517,518,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1371,89,518,0,101,338,136,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1372,89,136,0,102,518,175,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1373,89,175,0,103,136,519,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1374,89,519,0,104,175,499,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1375,89,499,0,105,519,520,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1376,89,520,0,106,499,103,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1377,89,103,0,107,520,497,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1378,89,497,0,108,103,521,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1379,89,521,0,109,497,144,15,60);
INSERT INTO ezsearch_object_word_link VALUES (1380,89,144,0,110,521,522,15,60);
INSERT INTO ezsearch_object_word_link VALUES (1381,89,522,0,111,144,523,15,61);
INSERT INTO ezsearch_object_word_link VALUES (1382,89,523,0,112,522,524,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1383,89,524,0,113,523,525,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1384,89,525,0,114,524,526,15,63);
INSERT INTO ezsearch_object_word_link VALUES (1385,89,526,0,115,525,0,15,65);
INSERT INTO ezsearch_object_word_link VALUES (1386,90,282,0,0,0,176,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1387,90,176,0,1,282,285,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1388,90,285,0,2,176,382,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1389,90,382,0,3,285,527,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1390,90,527,0,4,382,136,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1391,90,136,0,5,527,528,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1392,90,528,0,6,136,529,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1393,90,529,0,7,528,282,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1394,90,282,0,8,529,530,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1395,90,530,0,9,282,531,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1396,90,531,0,10,530,18,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1397,90,18,0,11,531,18,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1398,90,18,0,12,18,18,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1399,90,18,0,13,18,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1400,91,532,0,0,0,533,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1401,91,533,0,1,532,534,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1402,91,534,0,2,533,535,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1403,91,535,0,3,534,536,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1404,91,536,0,4,535,537,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1405,91,537,0,5,536,285,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1406,91,285,0,6,537,538,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1407,91,538,0,7,285,539,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1408,91,539,0,8,538,540,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1409,91,540,0,9,539,460,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1410,91,460,0,10,540,541,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1411,91,541,0,11,460,542,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1412,91,542,0,12,541,543,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1413,91,543,0,13,542,161,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1414,91,161,0,14,543,544,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1415,91,544,0,15,161,545,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1416,91,545,0,16,544,475,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1417,91,475,0,17,545,438,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1418,91,438,0,18,475,546,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1419,91,546,0,19,438,547,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1420,91,547,0,20,546,548,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1421,91,548,0,21,547,549,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1422,91,549,0,22,548,382,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1423,91,382,0,23,549,546,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1424,91,546,0,24,382,176,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1425,91,176,0,25,546,285,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1426,91,285,0,26,176,550,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1427,91,550,0,27,285,551,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1428,91,551,0,28,550,552,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1429,91,552,0,29,551,553,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1430,91,553,0,30,552,554,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1431,91,554,0,31,553,161,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1432,91,161,0,32,554,156,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1433,91,156,0,33,161,382,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1434,91,382,0,34,156,555,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1435,91,555,0,35,382,540,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1436,91,540,0,36,555,556,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1437,91,556,0,37,540,557,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1438,91,557,0,38,556,558,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1439,91,558,0,39,557,546,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1440,91,546,0,40,558,498,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1441,91,498,0,41,546,165,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1442,91,165,0,42,498,559,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1443,91,559,0,43,165,560,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1444,91,560,0,44,559,103,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1445,91,103,0,45,560,561,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1446,91,561,0,46,103,562,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1447,91,562,0,47,561,563,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1448,91,563,0,48,562,564,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1449,91,564,0,49,563,546,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1450,91,546,0,50,564,565,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1451,91,565,0,51,546,566,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1452,91,566,0,52,565,292,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1453,91,292,0,53,566,567,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1454,91,567,0,54,292,568,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1455,91,568,0,55,567,156,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1456,91,156,0,56,568,569,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1457,91,569,0,57,156,570,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1458,91,570,0,58,569,571,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1459,91,571,0,59,570,572,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1460,91,572,0,60,571,292,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1461,91,292,0,61,572,453,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1462,91,453,0,62,292,573,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1463,91,573,0,63,453,574,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1464,91,574,0,64,573,575,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1465,91,575,0,65,574,571,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1466,91,571,0,66,575,521,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1467,91,521,0,67,571,144,15,60);
INSERT INTO ezsearch_object_word_link VALUES (1468,91,144,0,68,521,576,15,60);
INSERT INTO ezsearch_object_word_link VALUES (1469,91,576,0,69,144,577,15,60);
INSERT INTO ezsearch_object_word_link VALUES (1470,91,577,0,70,576,534,15,61);
INSERT INTO ezsearch_object_word_link VALUES (1471,91,534,0,71,577,578,15,61);
INSERT INTO ezsearch_object_word_link VALUES (1472,91,578,0,72,534,579,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1473,91,579,0,73,578,580,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1474,91,580,0,74,579,581,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1475,91,581,0,75,580,582,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1476,91,582,0,76,581,583,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1477,91,583,0,77,582,525,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1478,91,525,0,78,583,584,15,63);
INSERT INTO ezsearch_object_word_link VALUES (1479,91,584,0,79,525,0,15,65);
INSERT INTO ezsearch_object_word_link VALUES (1493,92,586,0,6,176,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1492,92,176,0,5,282,586,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1491,92,282,0,4,527,176,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1490,92,527,0,3,285,282,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1489,92,285,0,2,176,527,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1488,92,176,0,1,282,285,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1487,92,282,0,0,0,176,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1494,93,587,0,0,0,588,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1495,93,588,0,1,587,50,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1496,93,50,0,2,588,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (1497,94,589,0,0,0,532,16,66);
INSERT INTO ezsearch_object_word_link VALUES (1498,94,532,0,1,589,50,16,67);
INSERT INTO ezsearch_object_word_link VALUES (1499,94,50,0,2,532,144,16,68);
INSERT INTO ezsearch_object_word_link VALUES (1500,94,144,0,3,50,36,16,70);
INSERT INTO ezsearch_object_word_link VALUES (1501,94,36,0,4,144,590,16,70);
INSERT INTO ezsearch_object_word_link VALUES (1502,94,590,0,5,36,591,16,70);
INSERT INTO ezsearch_object_word_link VALUES (1503,94,591,0,6,590,592,16,70);
INSERT INTO ezsearch_object_word_link VALUES (1504,94,592,0,7,591,0,16,70);
INSERT INTO ezsearch_object_word_link VALUES (1510,95,50,0,0,0,282,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1511,95,282,0,1,50,176,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1512,95,176,0,2,282,175,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1513,95,175,0,3,176,531,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1514,95,531,0,4,175,530,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1515,95,530,0,5,531,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1516,96,282,0,0,0,597,17,71);
INSERT INTO ezsearch_object_word_link VALUES (1517,96,597,0,1,282,598,17,71);
INSERT INTO ezsearch_object_word_link VALUES (1518,96,598,0,2,597,18,17,71);
INSERT INTO ezsearch_object_word_link VALUES (1519,96,18,0,3,598,599,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1520,96,599,0,4,18,600,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1521,96,600,0,5,599,601,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1522,96,601,0,6,600,0,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1523,97,33,0,0,0,34,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1524,97,34,0,1,33,35,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1525,97,35,0,2,34,36,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1526,97,36,0,3,35,9,2,1);
INSERT INTO ezsearch_object_word_link VALUES (1527,97,9,0,4,36,602,2,21);
INSERT INTO ezsearch_object_word_link VALUES (1528,97,602,0,5,9,603,2,21);
INSERT INTO ezsearch_object_word_link VALUES (1529,97,603,0,6,602,47,2,21);
INSERT INTO ezsearch_object_word_link VALUES (1530,97,47,0,7,603,604,2,21);
INSERT INTO ezsearch_object_word_link VALUES (1531,97,604,0,8,47,605,2,21);
INSERT INTO ezsearch_object_word_link VALUES (1532,97,605,0,9,604,137,2,21);
INSERT INTO ezsearch_object_word_link VALUES (1533,97,137,0,10,605,138,2,22);
INSERT INTO ezsearch_object_word_link VALUES (1534,97,138,0,11,137,33,2,22);
INSERT INTO ezsearch_object_word_link VALUES (1535,97,33,0,12,138,34,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1536,97,34,0,13,33,144,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1537,97,144,0,14,34,144,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1538,97,144,0,15,144,606,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1539,97,606,0,16,144,8,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1540,97,8,0,17,606,607,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1541,97,607,0,18,8,608,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1542,97,608,0,19,607,609,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1543,97,609,0,20,608,81,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1544,97,81,0,21,609,610,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1545,97,610,0,22,81,611,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1546,97,611,0,23,610,33,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1547,97,33,0,24,611,34,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1548,97,34,0,25,33,612,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1549,97,612,0,26,34,613,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1550,97,613,0,27,612,8,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1551,97,8,0,28,613,614,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1552,97,614,0,29,8,615,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1553,97,615,0,30,614,88,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1554,97,88,0,31,615,616,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1555,97,616,0,32,88,617,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1556,97,617,0,33,616,618,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1557,97,618,0,34,617,81,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1558,97,81,0,35,618,610,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1559,97,610,0,36,81,611,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1560,97,611,0,37,610,9,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1561,97,9,0,38,611,33,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1562,97,33,0,39,9,34,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1563,97,34,0,40,33,619,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1564,97,619,0,41,34,223,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1565,97,223,0,42,619,614,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1566,97,614,0,43,223,620,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1567,97,620,0,44,614,609,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1568,97,609,0,45,620,621,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1569,97,621,0,46,609,7,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1570,97,7,0,47,621,622,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1571,97,622,0,48,7,623,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1572,97,623,0,49,622,624,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1573,97,624,0,50,623,625,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1574,97,625,0,51,624,427,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1575,97,427,0,52,625,33,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1576,97,33,0,53,427,34,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1577,97,34,0,54,33,626,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1578,97,626,0,55,34,33,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1579,97,33,0,56,626,34,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1580,97,34,0,57,33,612,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1581,97,612,0,58,34,613,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1582,97,613,0,59,612,33,2,23);
INSERT INTO ezsearch_object_word_link VALUES (1583,97,33,0,60,613,34,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1584,97,34,0,61,33,8,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1585,97,8,0,62,34,627,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1586,97,627,0,63,8,628,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1587,97,628,0,64,627,629,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1588,97,629,0,65,628,630,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1589,97,630,0,66,629,631,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1590,97,631,0,67,630,632,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1591,97,632,0,68,631,33,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1592,97,33,0,69,632,34,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1593,97,34,0,70,33,633,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1594,97,633,0,71,34,634,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1595,97,634,0,72,633,626,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1596,97,626,0,73,634,635,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1597,97,635,0,74,626,626,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1598,97,626,0,75,635,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1599,97,9,0,76,626,636,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1600,97,636,0,77,9,637,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1601,97,637,0,78,636,638,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1602,97,638,0,79,637,632,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1603,97,632,0,80,638,639,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1604,97,639,0,81,632,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1605,97,9,0,82,639,640,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1606,97,640,0,83,9,44,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1607,97,44,0,84,640,641,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1608,97,641,0,85,44,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1609,97,9,0,86,641,634,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1610,97,634,0,87,9,642,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1611,97,642,0,88,634,88,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1612,97,88,0,89,642,643,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1613,97,643,0,90,88,46,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1614,97,46,0,91,643,644,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1615,97,644,0,92,46,645,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1616,97,645,0,93,644,646,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1617,97,646,0,94,645,647,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1618,97,647,0,95,646,641,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1619,97,641,0,96,647,33,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1620,97,33,0,97,641,34,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1621,97,34,0,98,33,33,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1622,97,33,0,99,34,34,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1623,97,34,0,100,33,8,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1624,97,8,0,101,34,648,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1625,97,648,0,102,8,649,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1626,97,649,0,103,648,626,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1627,97,626,0,104,649,8,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1628,97,8,0,105,626,650,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1629,97,650,0,106,8,626,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1630,97,626,0,107,650,651,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1631,97,651,0,108,626,652,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1632,97,652,0,109,651,653,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1633,97,653,0,110,652,654,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1634,97,654,0,111,653,32,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1635,97,32,0,112,654,655,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1636,97,655,0,113,32,656,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1637,97,656,0,114,655,657,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1638,97,657,0,115,656,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1639,97,427,0,116,657,33,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1640,97,33,0,117,427,34,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1641,97,34,0,118,33,48,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1642,97,48,0,119,34,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1643,97,9,0,120,48,17,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1644,97,17,0,121,9,658,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1645,97,658,0,122,17,626,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1646,97,626,0,123,658,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1647,97,9,0,124,626,659,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1648,97,659,0,125,9,660,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1649,97,660,0,126,659,206,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1650,97,206,0,127,660,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1651,97,9,0,128,206,661,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1652,97,661,0,129,9,662,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1653,97,662,0,130,661,657,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1654,97,657,0,131,662,663,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1655,97,663,0,132,657,664,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1656,97,664,0,133,663,349,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1657,97,349,0,134,664,616,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1658,97,616,0,135,349,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1659,97,9,0,136,616,657,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1660,97,657,0,137,9,427,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1661,97,427,0,138,657,9,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1662,97,9,0,139,427,33,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1663,97,33,0,140,9,34,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1664,97,34,0,141,33,665,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1665,97,665,0,142,34,666,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1666,97,666,0,143,665,0,2,24);
INSERT INTO ezsearch_object_word_link VALUES (1667,98,33,0,0,0,34,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1668,98,34,0,1,33,35,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1669,98,35,0,2,34,36,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1670,98,36,0,3,35,667,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1671,98,667,0,4,36,7,12,47);
INSERT INTO ezsearch_object_word_link VALUES (1672,98,7,0,5,667,8,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1673,98,8,0,6,7,668,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1674,98,668,0,7,8,669,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1675,98,669,0,8,668,670,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1676,98,670,0,9,669,611,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1677,98,611,0,10,670,646,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1678,98,646,0,11,611,217,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1679,98,217,0,12,646,671,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1680,98,671,0,13,217,672,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1681,98,672,0,14,671,217,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1682,98,217,0,15,672,671,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1683,98,671,0,16,217,673,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1684,98,673,0,17,671,9,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1685,98,9,0,18,673,674,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1686,98,674,0,19,9,675,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1687,98,675,0,20,674,676,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1688,98,676,0,21,675,677,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1689,98,677,0,22,676,84,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1690,98,84,0,23,677,85,12,48);
INSERT INTO ezsearch_object_word_link VALUES (1691,98,85,0,24,84,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (2467,31,934,0,0,0,5,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1710,100,690,0,0,0,690,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1711,100,690,0,1,690,691,1,5);
INSERT INTO ezsearch_object_word_link VALUES (1712,100,691,0,2,690,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2452,101,689,0,1,688,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2451,101,688,0,0,0,689,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1715,102,128,0,0,0,128,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1716,102,128,0,1,128,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1741,103,707,0,3,706,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1740,103,706,0,2,707,707,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1739,103,707,0,1,706,706,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1738,103,706,0,0,0,707,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1721,104,684,0,0,0,694,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1722,104,694,0,1,684,695,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1723,104,695,0,2,694,696,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1724,104,696,0,3,695,697,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1725,104,697,0,4,696,327,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1726,104,327,0,5,697,698,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1727,104,698,0,6,327,699,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1728,104,699,0,7,698,700,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1729,104,700,0,8,699,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1730,105,701,0,0,0,697,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1731,105,697,0,1,701,702,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1732,105,702,0,2,697,701,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1733,105,701,0,3,702,703,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1734,105,703,0,4,701,9,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1735,105,9,0,5,703,704,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1736,105,704,0,6,9,705,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1737,105,705,0,7,704,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1742,106,684,0,0,0,685,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1743,106,685,0,1,684,708,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1744,106,708,0,2,685,709,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1745,106,709,0,3,708,695,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1746,106,695,0,4,709,696,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1747,106,696,0,5,695,697,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1748,106,697,0,6,696,327,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1749,106,327,0,7,697,206,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1750,106,206,0,8,327,710,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1751,106,710,0,9,206,377,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1752,106,377,0,10,710,384,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1753,106,384,0,11,377,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1754,107,702,0,0,0,711,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1755,107,711,0,1,702,703,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1756,107,703,0,2,711,9,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1757,107,9,0,3,703,702,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1758,107,702,0,4,9,705,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1759,107,705,0,5,702,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2474,108,937,0,2,936,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2473,108,936,0,1,936,937,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2472,108,936,0,0,0,936,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2475,109,938,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2476,110,646,0,0,0,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1784,111,9,0,0,0,722,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1785,111,722,0,1,9,611,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1786,111,611,0,2,722,9,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1787,111,9,0,3,611,723,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1788,111,723,0,4,9,9,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1789,111,9,0,5,723,724,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1790,111,724,0,6,9,611,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1791,111,611,0,7,724,9,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1792,111,9,0,8,611,725,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1793,111,725,0,9,9,697,15,57);
INSERT INTO ezsearch_object_word_link VALUES (1794,111,697,0,10,725,726,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1795,111,726,0,11,697,727,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1796,111,727,0,12,726,728,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1797,111,728,0,13,727,729,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1798,111,729,0,14,728,730,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1799,111,730,0,15,729,731,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1800,111,731,0,16,730,611,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1801,111,611,0,17,731,9,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1802,111,9,0,18,611,16,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1803,111,16,0,19,9,732,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1804,111,732,0,20,16,611,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1805,111,611,0,21,732,733,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1806,111,733,0,22,611,709,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1807,111,709,0,23,733,734,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1808,111,734,0,24,709,535,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1809,111,535,0,25,734,735,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1810,111,735,0,26,535,709,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1811,111,709,0,27,735,9,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1812,111,9,0,28,709,722,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1813,111,722,0,29,9,611,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1814,111,611,0,30,722,9,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1815,111,9,0,31,611,723,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1816,111,723,0,32,9,9,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1817,111,9,0,33,723,724,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1818,111,724,0,34,9,611,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1819,111,611,0,35,724,9,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1820,111,9,0,36,611,725,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1821,111,725,0,37,9,8,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1822,111,8,0,38,725,9,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1823,111,9,0,39,8,736,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1824,111,736,0,40,9,737,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1825,111,737,0,41,736,738,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1826,111,738,0,42,737,739,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1827,111,739,0,43,738,43,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1828,111,43,0,44,739,740,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1829,111,740,0,45,43,741,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1830,111,741,0,46,740,742,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1831,111,742,0,47,741,743,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1832,111,743,0,48,742,620,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1833,111,620,0,49,743,744,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1834,111,744,0,50,620,44,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1835,111,44,0,51,744,745,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1836,111,745,0,52,44,746,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1837,111,746,0,53,745,626,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1838,111,626,0,54,746,747,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1839,111,747,0,55,626,748,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1840,111,748,0,56,747,675,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1841,111,675,0,57,748,749,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1842,111,749,0,58,675,750,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1843,111,750,0,59,749,751,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1844,111,751,0,60,750,752,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1845,111,752,0,61,751,753,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1846,111,753,0,62,752,754,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1847,111,754,0,63,753,755,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1848,111,755,0,64,754,626,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1849,111,626,0,65,755,756,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1850,111,756,0,66,626,757,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1851,111,757,0,67,756,758,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1852,111,758,0,68,757,759,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1853,111,759,0,69,758,760,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1854,111,760,0,70,759,761,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1855,111,761,0,71,760,626,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1856,111,626,0,72,761,762,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1857,111,762,0,73,626,763,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1858,111,763,0,74,762,88,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1859,111,88,0,75,763,764,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1860,111,764,0,76,88,765,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1861,111,765,0,77,764,626,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1862,111,626,0,78,765,766,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1863,111,766,0,79,626,521,15,59);
INSERT INTO ezsearch_object_word_link VALUES (1864,111,521,0,80,766,767,15,60);
INSERT INTO ezsearch_object_word_link VALUES (1865,111,767,0,81,521,768,15,60);
INSERT INTO ezsearch_object_word_link VALUES (1866,111,768,0,82,767,534,15,61);
INSERT INTO ezsearch_object_word_link VALUES (1867,111,534,0,83,768,769,15,61);
INSERT INTO ezsearch_object_word_link VALUES (1868,111,769,0,84,534,770,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1869,111,770,0,85,769,771,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1870,111,771,0,86,770,772,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1871,111,772,0,87,771,289,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1872,111,289,0,88,772,773,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1873,111,773,0,89,289,774,15,62);
INSERT INTO ezsearch_object_word_link VALUES (1874,111,774,0,90,773,775,15,63);
INSERT INTO ezsearch_object_word_link VALUES (1875,111,775,0,91,774,0,15,65);
INSERT INTO ezsearch_object_word_link VALUES (1876,112,776,0,0,0,777,17,71);
INSERT INTO ezsearch_object_word_link VALUES (1877,112,777,0,1,776,9,17,71);
INSERT INTO ezsearch_object_word_link VALUES (1878,112,9,0,2,777,661,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1879,112,661,0,3,9,778,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1880,112,778,0,4,661,736,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1881,112,736,0,5,778,103,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1882,112,103,0,6,736,779,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1883,112,779,0,7,103,728,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1884,112,728,0,8,779,780,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1885,112,780,0,9,728,708,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1886,112,708,0,10,780,781,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1887,112,781,0,11,708,782,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1888,112,782,0,12,781,783,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1889,112,783,0,13,782,784,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1890,112,784,0,14,783,785,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1891,112,785,0,15,784,786,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1892,112,786,0,16,785,787,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1893,112,787,0,17,786,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1894,112,9,0,18,787,788,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1895,112,788,0,19,9,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1896,112,611,0,20,788,789,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1897,112,789,0,21,611,790,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1898,112,790,0,22,789,44,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1899,112,44,0,23,790,791,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1900,112,791,0,24,44,427,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1901,112,427,0,25,791,668,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1902,112,668,0,26,427,792,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1903,112,792,0,27,668,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1904,112,611,0,28,792,793,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1905,112,793,0,29,611,794,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1906,112,794,0,30,793,795,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1907,112,795,0,31,794,796,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1908,112,796,0,32,795,797,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1909,112,797,0,33,796,742,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1910,112,742,0,34,797,626,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1911,112,626,0,35,742,798,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1912,112,798,0,36,626,747,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1913,112,747,0,37,798,799,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1914,112,799,0,38,747,8,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1915,112,8,0,39,799,697,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1916,112,697,0,40,8,800,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1917,112,800,0,41,697,103,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1918,112,103,0,42,800,254,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1919,112,254,0,43,103,748,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1920,112,748,0,44,254,801,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1921,112,801,0,45,748,677,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1922,112,677,0,46,801,708,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1923,112,708,0,47,677,802,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1924,112,802,0,48,708,779,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1925,112,779,0,49,802,697,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1926,112,697,0,50,779,803,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1927,112,803,0,51,697,804,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1928,112,804,0,52,803,805,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1929,112,805,0,53,804,206,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1930,112,206,0,54,805,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1931,112,9,0,55,206,806,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1932,112,806,0,56,9,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1933,112,611,0,57,806,807,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1934,112,807,0,58,611,808,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1935,112,808,0,59,807,626,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1936,112,626,0,60,808,809,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1937,112,809,0,61,626,626,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1938,112,626,0,62,809,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1939,112,9,0,63,626,810,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1940,112,810,0,64,9,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1941,112,611,0,65,810,811,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1942,112,811,0,66,611,626,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1943,112,626,0,67,811,812,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1944,112,812,0,68,626,764,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1945,112,764,0,69,812,698,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1946,112,698,0,70,764,813,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1947,112,813,0,71,698,814,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1948,112,814,0,72,813,632,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1949,112,632,0,73,814,815,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1950,112,815,0,74,632,697,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1951,112,697,0,75,815,736,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1952,112,736,0,76,697,816,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1953,112,816,0,77,736,817,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1954,112,817,0,78,816,818,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1955,112,818,0,79,817,752,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1956,112,752,0,80,818,819,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1957,112,819,0,81,752,820,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1958,112,820,0,82,819,821,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1959,112,821,0,83,820,639,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1960,112,639,0,84,821,822,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1961,112,822,0,85,639,640,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1962,112,640,0,86,822,769,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1963,112,769,0,87,640,823,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1964,112,823,0,88,769,824,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1965,112,824,0,89,823,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1966,112,611,0,90,824,825,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1967,112,825,0,91,611,826,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1968,112,826,0,92,825,703,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1969,112,703,0,93,826,827,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1970,112,827,0,94,703,828,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1971,112,828,0,95,827,829,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1972,112,829,0,96,828,830,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1973,112,830,0,97,829,44,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1974,112,44,0,98,830,831,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1975,112,831,0,99,44,832,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1976,112,832,0,100,831,833,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1977,112,833,0,101,832,8,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1978,112,8,0,102,833,777,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1979,112,777,0,103,8,771,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1980,112,771,0,104,777,772,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1981,112,772,0,105,771,8,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1982,112,8,0,106,772,834,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1983,112,834,0,107,8,206,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1984,112,206,0,108,834,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1985,112,9,0,109,206,835,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1986,112,835,0,110,9,836,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1987,112,836,0,111,835,626,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1988,112,626,0,112,836,837,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1989,112,837,0,113,626,838,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1990,112,838,0,114,837,839,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1991,112,839,0,115,838,206,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1992,112,206,0,116,839,840,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1993,112,840,0,117,206,841,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1994,112,841,0,118,840,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1995,112,611,0,119,841,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1996,112,9,0,120,611,842,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1997,112,842,0,121,9,698,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1998,112,698,0,122,842,103,17,72);
INSERT INTO ezsearch_object_word_link VALUES (1999,112,103,0,123,698,843,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2000,112,843,0,124,103,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2001,112,9,0,125,843,765,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2002,112,765,0,126,9,737,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2003,112,737,0,127,765,813,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2004,112,813,0,128,737,844,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2005,112,844,0,129,813,845,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2006,112,845,0,130,844,32,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2007,112,32,0,131,845,846,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2008,112,846,0,132,32,847,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2009,112,847,0,133,846,206,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2010,112,206,0,134,847,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2011,112,9,0,135,206,848,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2012,112,848,0,136,9,849,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2013,112,849,0,137,848,850,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2014,112,850,0,138,849,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2015,112,611,0,139,850,851,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2016,112,851,0,140,611,626,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2017,112,626,0,141,851,852,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2018,112,852,0,142,626,853,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2019,112,853,0,143,852,206,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2020,112,206,0,144,853,854,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2021,112,854,0,145,206,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2022,112,611,0,146,854,855,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2023,112,855,0,147,611,847,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2024,112,847,0,148,855,8,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2025,112,8,0,149,847,856,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2026,112,856,0,150,8,857,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2027,112,857,0,151,856,88,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2028,112,88,0,152,857,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2029,112,9,0,153,88,732,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2030,112,732,0,154,9,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2031,112,9,0,155,732,858,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2032,112,858,0,156,9,859,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2033,112,859,0,157,858,860,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2034,112,860,0,158,859,626,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2035,112,626,0,159,860,853,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2036,112,853,0,160,626,861,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2037,112,861,0,161,853,206,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2038,112,206,0,162,861,697,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2039,112,697,0,163,206,862,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2040,112,862,0,164,697,863,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2041,112,863,0,165,862,864,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2042,112,864,0,166,863,533,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2043,112,533,0,167,864,699,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2044,112,699,0,168,533,8,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2045,112,8,0,169,699,865,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2046,112,865,0,170,8,866,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2047,112,866,0,171,865,867,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2048,112,867,0,172,866,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2049,112,9,0,173,867,725,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2050,112,725,0,174,9,611,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2051,112,611,0,175,725,868,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2052,112,868,0,176,611,0,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2059,117,8,0,4,870,871,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2060,117,871,0,5,8,871,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2061,117,871,0,6,871,748,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2062,117,748,0,7,871,872,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2063,117,872,0,8,748,873,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2064,117,873,0,9,872,698,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2065,117,698,0,10,873,874,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2066,117,874,0,11,698,632,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2067,117,632,0,12,874,779,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2068,117,779,0,13,632,766,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2069,117,766,0,14,779,818,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2070,117,818,0,15,766,44,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2071,117,44,0,16,818,875,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2072,117,875,0,17,44,7,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2073,117,7,0,18,875,8,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2074,117,8,0,19,7,668,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2075,117,668,0,20,8,677,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2076,117,677,0,21,668,859,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2077,117,859,0,22,677,0,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2442,118,924,0,2,923,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2441,118,923,0,1,876,924,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2440,118,876,0,0,0,923,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2092,119,31,0,4,882,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2091,119,882,0,3,881,31,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2090,119,881,0,2,876,882,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2089,119,876,0,1,633,881,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2088,119,633,0,0,0,876,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2094,120,876,0,1,745,0,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2093,120,745,0,0,0,876,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2098,121,9,0,0,0,16,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2099,121,16,0,1,9,343,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2100,121,343,0,2,16,137,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2101,121,137,0,3,343,7,19,75);
INSERT INTO ezsearch_object_word_link VALUES (2102,121,7,0,4,137,8,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2103,121,8,0,5,7,9,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2104,121,9,0,6,8,16,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2105,121,16,0,7,9,343,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2106,121,343,0,8,16,885,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2107,121,885,0,9,343,0,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2108,122,873,0,0,0,870,17,71);
INSERT INTO ezsearch_object_word_link VALUES (2109,122,870,0,1,873,886,17,71);
INSERT INTO ezsearch_object_word_link VALUES (2110,122,886,0,2,870,887,17,71);
INSERT INTO ezsearch_object_word_link VALUES (2111,122,887,0,3,886,748,17,71);
INSERT INTO ezsearch_object_word_link VALUES (2112,122,748,0,4,887,9,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2113,122,9,0,5,748,888,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2114,122,888,0,6,9,785,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2115,122,785,0,7,888,889,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2116,122,889,0,8,785,708,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2117,122,708,0,9,889,890,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2118,122,890,0,10,708,891,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2119,122,891,0,11,890,892,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2120,122,892,0,12,891,0,17,72);
INSERT INTO ezsearch_object_word_link VALUES (2121,123,893,0,0,0,348,12,47);
INSERT INTO ezsearch_object_word_link VALUES (2122,123,348,0,1,893,894,12,47);
INSERT INTO ezsearch_object_word_link VALUES (2123,123,894,0,2,348,103,12,48);
INSERT INTO ezsearch_object_word_link VALUES (2124,123,103,0,3,894,895,12,48);
INSERT INTO ezsearch_object_word_link VALUES (2125,123,895,0,4,103,0,12,48);
INSERT INTO ezsearch_object_word_link VALUES (2126,124,896,0,0,0,897,7,26);
INSERT INTO ezsearch_object_word_link VALUES (2127,124,897,0,1,896,898,7,26);
INSERT INTO ezsearch_object_word_link VALUES (2128,124,898,0,2,897,867,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2129,124,867,0,3,898,896,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2130,124,896,0,4,867,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2131,125,899,0,0,0,701,7,26);
INSERT INTO ezsearch_object_word_link VALUES (2132,125,701,0,1,899,697,7,26);
INSERT INTO ezsearch_object_word_link VALUES (2133,125,697,0,2,701,732,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2134,125,732,0,3,697,611,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2135,125,611,0,4,732,697,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2136,125,697,0,5,611,899,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2137,125,899,0,6,697,900,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2138,125,900,0,7,899,901,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2139,125,901,0,8,900,708,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2140,125,708,0,9,901,8,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2141,125,8,0,10,708,103,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2142,125,103,0,11,8,902,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2143,125,902,0,12,103,821,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2144,125,821,0,13,902,903,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2145,125,903,0,14,821,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2146,126,904,0,0,0,867,7,26);
INSERT INTO ezsearch_object_word_link VALUES (2147,126,867,0,1,904,896,7,26);
INSERT INTO ezsearch_object_word_link VALUES (2148,126,896,0,2,867,704,7,26);
INSERT INTO ezsearch_object_word_link VALUES (2149,126,704,0,3,896,904,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2150,126,904,0,4,704,867,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2151,126,867,0,5,904,896,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2152,126,896,0,6,867,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (2153,127,103,0,0,0,905,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2154,127,905,0,1,103,748,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2155,127,748,0,2,905,906,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2156,127,906,0,3,748,48,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2157,127,48,0,4,906,7,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2158,127,7,0,5,48,907,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2159,127,907,0,6,7,7,19,75);
INSERT INTO ezsearch_object_word_link VALUES (2160,127,7,0,7,907,8,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2161,127,8,0,8,7,748,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2162,127,748,0,9,8,908,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2163,127,908,0,10,748,438,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2164,127,438,0,11,908,616,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2165,127,616,0,12,438,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2166,127,377,0,13,616,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2167,127,384,0,14,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2168,127,377,0,15,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2169,127,384,0,16,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2170,127,377,0,17,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2171,127,384,0,18,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2172,127,377,0,19,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2173,127,384,0,20,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2174,127,377,0,21,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2175,127,384,0,22,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2176,127,377,0,23,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2177,127,384,0,24,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2178,127,377,0,25,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2179,127,384,0,26,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2180,127,377,0,27,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2181,127,384,0,28,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2182,127,377,0,29,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2183,127,384,0,30,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2184,127,377,0,31,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2185,127,384,0,32,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2186,127,377,0,33,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2187,127,384,0,34,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2188,127,377,0,35,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2189,127,384,0,36,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2190,127,377,0,37,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2191,127,384,0,38,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2192,127,377,0,39,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2193,127,384,0,40,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2194,127,377,0,41,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2195,127,384,0,42,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2196,127,377,0,43,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2197,127,384,0,44,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2198,127,377,0,45,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2199,127,384,0,46,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2200,127,377,0,47,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2201,127,384,0,48,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2202,127,377,0,49,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2203,127,384,0,50,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2204,127,377,0,51,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2205,127,384,0,52,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2206,127,377,0,53,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2207,127,384,0,54,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2208,127,377,0,55,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2209,127,384,0,56,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2210,127,377,0,57,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2211,127,384,0,58,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2212,127,377,0,59,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2213,127,384,0,60,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2214,127,377,0,61,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2215,127,384,0,62,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2216,127,377,0,63,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2217,127,384,0,64,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2218,127,377,0,65,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2219,127,384,0,66,377,0,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2220,128,103,0,0,0,906,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2221,128,906,0,1,103,909,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2222,128,909,0,2,906,910,19,75);
INSERT INTO ezsearch_object_word_link VALUES (2223,128,910,0,3,909,377,19,75);
INSERT INTO ezsearch_object_word_link VALUES (2224,128,377,0,4,910,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2225,128,384,0,5,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2226,128,377,0,6,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2227,128,384,0,7,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2228,128,377,0,8,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2229,128,384,0,9,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2230,128,377,0,10,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2231,128,384,0,11,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2232,128,377,0,12,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2233,128,384,0,13,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2234,128,377,0,14,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2235,128,384,0,15,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2236,128,377,0,16,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2237,128,384,0,17,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2238,128,377,0,18,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2239,128,384,0,19,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2240,128,377,0,20,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2241,128,384,0,21,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2242,128,377,0,22,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2243,128,384,0,23,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2244,128,377,0,24,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2245,128,384,0,25,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2246,128,377,0,26,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2247,128,384,0,27,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2248,128,377,0,28,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2249,128,384,0,29,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2250,128,377,0,30,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2251,128,384,0,31,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2252,128,377,0,32,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2253,128,384,0,33,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2254,128,377,0,34,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2255,128,384,0,35,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2256,128,377,0,36,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2257,128,384,0,37,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2258,128,377,0,38,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2259,128,384,0,39,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2260,128,377,0,40,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2261,128,384,0,41,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2262,128,377,0,42,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2263,128,384,0,43,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2264,128,377,0,44,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2265,128,384,0,45,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2266,128,377,0,46,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2267,128,384,0,47,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2268,128,377,0,48,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2269,128,384,0,49,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2270,128,377,0,50,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2271,128,384,0,51,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2272,128,377,0,52,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2273,128,384,0,53,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2274,128,377,0,54,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2275,128,384,0,55,377,0,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2276,129,43,0,0,0,905,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2277,129,905,0,1,43,103,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2278,129,103,0,2,905,905,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2279,129,905,0,3,103,7,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2280,129,7,0,4,905,84,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2281,129,84,0,5,7,85,19,75);
INSERT INTO ezsearch_object_word_link VALUES (2282,129,85,0,6,84,103,19,75);
INSERT INTO ezsearch_object_word_link VALUES (2283,129,103,0,7,85,815,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2284,129,815,0,8,103,44,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2285,129,44,0,9,815,34,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2286,129,34,0,10,44,626,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2287,129,626,0,11,34,805,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2288,129,805,0,12,626,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2289,129,377,0,13,805,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2290,129,384,0,14,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2291,129,377,0,15,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2292,129,384,0,16,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2293,129,377,0,17,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2294,129,384,0,18,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2295,129,377,0,19,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2296,129,384,0,20,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2297,129,377,0,21,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2298,129,384,0,22,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2299,129,377,0,23,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2300,129,384,0,24,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2301,129,377,0,25,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2302,129,384,0,26,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2303,129,377,0,27,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2304,129,384,0,28,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2305,129,377,0,29,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2306,129,384,0,30,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2307,129,377,0,31,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2308,129,384,0,32,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2309,129,377,0,33,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2310,129,384,0,34,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2311,129,377,0,35,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2312,129,384,0,36,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2313,129,377,0,37,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2314,129,384,0,38,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2315,129,377,0,39,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2316,129,384,0,40,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2317,129,377,0,41,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2318,129,384,0,42,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2319,129,377,0,43,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2320,129,384,0,44,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2321,129,377,0,45,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2322,129,384,0,46,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2323,129,377,0,47,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2324,129,384,0,48,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2325,129,377,0,49,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2326,129,384,0,50,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2327,129,377,0,51,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2328,129,384,0,52,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2329,129,377,0,53,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2330,129,384,0,54,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2331,129,377,0,55,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2332,129,384,0,56,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2333,129,377,0,57,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2334,129,384,0,58,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2335,129,377,0,59,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2336,129,384,0,60,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2337,129,377,0,61,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2338,129,384,0,62,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2339,129,377,0,63,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2340,129,384,0,64,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2341,129,377,0,65,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2342,129,384,0,66,377,911,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2343,129,911,0,67,384,0,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2344,130,34,0,0,0,912,19,74);
INSERT INTO ezsearch_object_word_link VALUES (2345,130,912,0,1,34,632,19,75);
INSERT INTO ezsearch_object_word_link VALUES (2346,130,632,0,2,912,820,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2347,130,820,0,3,632,913,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2348,130,913,0,4,820,905,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2349,130,905,0,5,913,708,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2350,130,708,0,6,905,194,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2351,130,194,0,7,708,7,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2352,130,7,0,8,194,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2353,130,377,0,9,7,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2354,130,384,0,10,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2355,130,377,0,11,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2356,130,384,0,12,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2357,130,377,0,13,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2358,130,384,0,14,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2359,130,377,0,15,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2360,130,384,0,16,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2361,130,377,0,17,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2362,130,384,0,18,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2363,130,377,0,19,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2364,130,384,0,20,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2365,130,377,0,21,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2366,130,384,0,22,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2367,130,377,0,23,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2368,130,384,0,24,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2369,130,377,0,25,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2370,130,384,0,26,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2371,130,377,0,27,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2372,130,384,0,28,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2373,130,377,0,29,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2374,130,384,0,30,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2375,130,377,0,31,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2376,130,384,0,32,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2377,130,377,0,33,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2378,130,384,0,34,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2379,130,377,0,35,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2380,130,384,0,36,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2381,130,377,0,37,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2382,130,384,0,38,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2383,130,377,0,39,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2384,130,384,0,40,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2385,130,377,0,41,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2386,130,384,0,42,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2387,130,377,0,43,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2388,130,384,0,44,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2389,130,377,0,45,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2390,130,384,0,46,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2391,130,377,0,47,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2392,130,384,0,48,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2393,130,377,0,49,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2394,130,384,0,50,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2395,130,377,0,51,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2396,130,384,0,52,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2397,130,377,0,53,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2398,130,384,0,54,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2399,130,377,0,55,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2400,130,384,0,56,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2401,130,377,0,57,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2402,130,384,0,58,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2403,130,377,0,59,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2404,130,384,0,60,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2405,130,377,0,61,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2406,130,384,0,62,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2407,130,377,0,63,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2408,130,384,0,64,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2409,130,377,0,65,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2410,130,384,0,66,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2411,130,377,0,67,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2412,130,384,0,68,377,377,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2413,130,377,0,69,384,384,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2414,130,384,0,70,377,0,19,76);
INSERT INTO ezsearch_object_word_link VALUES (2459,131,928,0,6,88,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2458,131,88,0,5,929,928,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2457,131,929,0,4,9,88,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2456,131,9,0,3,8,929,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2455,131,8,0,2,7,9,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2454,131,7,0,1,928,8,1,5);
INSERT INTO ezsearch_object_word_link VALUES (2453,131,928,0,0,0,7,1,4);
INSERT INTO ezsearch_object_word_link VALUES (2460,133,930,0,0,0,9,20,77);
INSERT INTO ezsearch_object_word_link VALUES (2461,133,9,0,1,930,16,20,77);
INSERT INTO ezsearch_object_word_link VALUES (2462,133,16,0,2,9,931,20,77);
INSERT INTO ezsearch_object_word_link VALUES (2463,133,931,0,3,16,9,20,77);
INSERT INTO ezsearch_object_word_link VALUES (2464,133,9,0,4,931,932,20,78);
INSERT INTO ezsearch_object_word_link VALUES (2465,133,932,0,5,9,933,20,78);
INSERT INTO ezsearch_object_word_link VALUES (2466,133,933,0,6,932,0,20,78);

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

INSERT INTO ezsearch_return_count VALUES (1,1,1031484736,1);
INSERT INTO ezsearch_return_count VALUES (2,2,1031485837,8);
INSERT INTO ezsearch_return_count VALUES (3,2,1031485865,8);
INSERT INTO ezsearch_return_count VALUES (4,2,1031485904,8);
INSERT INTO ezsearch_return_count VALUES (5,2,1031485929,8);
INSERT INTO ezsearch_return_count VALUES (6,2,1031485936,8);
INSERT INTO ezsearch_return_count VALUES (7,3,1031488509,1);
INSERT INTO ezsearch_return_count VALUES (8,4,1031488514,1);
INSERT INTO ezsearch_return_count VALUES (9,2,1031488518,16);
INSERT INTO ezsearch_return_count VALUES (10,5,1031488526,1);
INSERT INTO ezsearch_return_count VALUES (11,5,1031488532,1);
INSERT INTO ezsearch_return_count VALUES (12,6,1031489022,1);
INSERT INTO ezsearch_return_count VALUES (13,7,1031489292,2);
INSERT INTO ezsearch_return_count VALUES (14,8,1031489298,2);
INSERT INTO ezsearch_return_count VALUES (15,9,1031489305,1);
INSERT INTO ezsearch_return_count VALUES (16,10,1031490949,3);
INSERT INTO ezsearch_return_count VALUES (17,3,1031490962,3);
INSERT INTO ezsearch_return_count VALUES (18,2,1031490977,22);
INSERT INTO ezsearch_return_count VALUES (19,3,1031490987,2);
INSERT INTO ezsearch_return_count VALUES (20,3,1031490993,3);
INSERT INTO ezsearch_return_count VALUES (21,3,1031491000,2);
INSERT INTO ezsearch_return_count VALUES (22,5,1031491011,1);
INSERT INTO ezsearch_return_count VALUES (23,11,1031491026,3);
INSERT INTO ezsearch_return_count VALUES (24,2,1031491032,22);
INSERT INTO ezsearch_return_count VALUES (25,2,1031491065,2);
INSERT INTO ezsearch_return_count VALUES (26,12,1031491103,2);
INSERT INTO ezsearch_return_count VALUES (27,9,1031491484,1);
INSERT INTO ezsearch_return_count VALUES (28,2,1031492716,24);
INSERT INTO ezsearch_return_count VALUES (29,3,1031492743,3);
INSERT INTO ezsearch_return_count VALUES (30,13,1031497262,1);
INSERT INTO ezsearch_return_count VALUES (31,2,1031503794,31);
INSERT INTO ezsearch_return_count VALUES (32,14,1031504444,3);
INSERT INTO ezsearch_return_count VALUES (33,15,1031504452,1);
INSERT INTO ezsearch_return_count VALUES (34,2,1031504478,28);
INSERT INTO ezsearch_return_count VALUES (35,2,1031504481,2);
INSERT INTO ezsearch_return_count VALUES (36,16,1031557257,1);
INSERT INTO ezsearch_return_count VALUES (37,17,1031557345,1);
INSERT INTO ezsearch_return_count VALUES (38,18,1031560729,1);
INSERT INTO ezsearch_return_count VALUES (39,19,1031567133,0);
INSERT INTO ezsearch_return_count VALUES (40,20,1031567141,1);
INSERT INTO ezsearch_return_count VALUES (41,21,1031567407,0);
INSERT INTO ezsearch_return_count VALUES (42,6,1031568093,1);
INSERT INTO ezsearch_return_count VALUES (43,13,1031571323,3);
INSERT INTO ezsearch_return_count VALUES (44,13,1031571712,3);
INSERT INTO ezsearch_return_count VALUES (45,13,1031571767,3);
INSERT INTO ezsearch_return_count VALUES (46,6,1031571770,1);
INSERT INTO ezsearch_return_count VALUES (47,22,1031572430,0);
INSERT INTO ezsearch_return_count VALUES (48,23,1031639258,0);
INSERT INTO ezsearch_return_count VALUES (49,24,1031639417,0);
INSERT INTO ezsearch_return_count VALUES (50,24,1031639425,0);
INSERT INTO ezsearch_return_count VALUES (51,24,1031639428,0);
INSERT INTO ezsearch_return_count VALUES (52,25,1031640288,1);
INSERT INTO ezsearch_return_count VALUES (53,9,1031641902,1);
INSERT INTO ezsearch_return_count VALUES (54,26,1031655208,0);
INSERT INTO ezsearch_return_count VALUES (55,27,1031655213,0);
INSERT INTO ezsearch_return_count VALUES (56,28,1031658601,0);
INSERT INTO ezsearch_return_count VALUES (57,6,1031661073,1);
INSERT INTO ezsearch_return_count VALUES (58,2,1031665086,40);
INSERT INTO ezsearch_return_count VALUES (59,29,1031665091,0);
INSERT INTO ezsearch_return_count VALUES (60,30,1031669527,0);
INSERT INTO ezsearch_return_count VALUES (61,31,1031728454,1);
INSERT INTO ezsearch_return_count VALUES (62,32,1031728823,2);
INSERT INTO ezsearch_return_count VALUES (63,33,1031729497,1);
INSERT INTO ezsearch_return_count VALUES (64,2,1031729682,25);
INSERT INTO ezsearch_return_count VALUES (65,34,1031730051,0);
INSERT INTO ezsearch_return_count VALUES (66,35,1031730054,0);
INSERT INTO ezsearch_return_count VALUES (67,9,1031730056,1);
INSERT INTO ezsearch_return_count VALUES (68,2,1031730068,25);
INSERT INTO ezsearch_return_count VALUES (69,2,1031730242,25);
INSERT INTO ezsearch_return_count VALUES (70,36,1031730277,0);
INSERT INTO ezsearch_return_count VALUES (71,37,1031730286,1);
INSERT INTO ezsearch_return_count VALUES (72,38,1031731328,1);
INSERT INTO ezsearch_return_count VALUES (73,39,1031731335,1);
INSERT INTO ezsearch_return_count VALUES (74,40,1031735793,0);
INSERT INTO ezsearch_return_count VALUES (75,41,1031735796,1);
INSERT INTO ezsearch_return_count VALUES (76,16,1031736634,1);
INSERT INTO ezsearch_return_count VALUES (77,42,1031743435,1);
INSERT INTO ezsearch_return_count VALUES (78,43,1031743450,1);
INSERT INTO ezsearch_return_count VALUES (79,44,1031743457,0);
INSERT INTO ezsearch_return_count VALUES (80,43,1031743463,1);
INSERT INTO ezsearch_return_count VALUES (81,16,1031743618,1);
INSERT INTO ezsearch_return_count VALUES (82,16,1031745793,1);
INSERT INTO ezsearch_return_count VALUES (83,2,1031746044,29);
INSERT INTO ezsearch_return_count VALUES (84,2,1031746072,29);
INSERT INTO ezsearch_return_count VALUES (85,2,1031746095,29);
INSERT INTO ezsearch_return_count VALUES (86,45,1031753956,1);
INSERT INTO ezsearch_return_count VALUES (87,13,1031842153,5);
INSERT INTO ezsearch_return_count VALUES (88,2,1031901299,29);
INSERT INTO ezsearch_return_count VALUES (89,45,1031901303,1);
INSERT INTO ezsearch_return_count VALUES (90,45,1031901310,1);
INSERT INTO ezsearch_return_count VALUES (91,46,1031903853,1);
INSERT INTO ezsearch_return_count VALUES (92,2,1031904224,29);
INSERT INTO ezsearch_return_count VALUES (93,13,1031904228,5);
INSERT INTO ezsearch_return_count VALUES (94,13,1031904250,5);
INSERT INTO ezsearch_return_count VALUES (95,13,1031904977,5);
INSERT INTO ezsearch_return_count VALUES (96,46,1031905003,1);
INSERT INTO ezsearch_return_count VALUES (97,46,1031905025,1);
INSERT INTO ezsearch_return_count VALUES (98,46,1031905082,1);
INSERT INTO ezsearch_return_count VALUES (99,46,1031905492,0);
INSERT INTO ezsearch_return_count VALUES (100,46,1031905529,0);
INSERT INTO ezsearch_return_count VALUES (101,46,1031905583,1);
INSERT INTO ezsearch_return_count VALUES (102,46,1031905630,1);
INSERT INTO ezsearch_return_count VALUES (103,16,1031905634,1);
INSERT INTO ezsearch_return_count VALUES (104,16,1031905640,1);
INSERT INTO ezsearch_return_count VALUES (105,16,1031905648,1);
INSERT INTO ezsearch_return_count VALUES (106,2,1031905665,29);
INSERT INTO ezsearch_return_count VALUES (107,16,1031905668,1);
INSERT INTO ezsearch_return_count VALUES (108,47,1031905722,1);
INSERT INTO ezsearch_return_count VALUES (109,47,1031905750,1);
INSERT INTO ezsearch_return_count VALUES (110,47,1031905800,1);
INSERT INTO ezsearch_return_count VALUES (111,47,1031905839,1);
INSERT INTO ezsearch_return_count VALUES (112,47,1031905845,1);
INSERT INTO ezsearch_return_count VALUES (113,16,1031905850,1);
INSERT INTO ezsearch_return_count VALUES (114,16,1031911387,1);
INSERT INTO ezsearch_return_count VALUES (115,2,1031913904,25);
INSERT INTO ezsearch_return_count VALUES (116,2,1031913908,1);
INSERT INTO ezsearch_return_count VALUES (117,48,1031913912,1);
INSERT INTO ezsearch_return_count VALUES (118,48,1031915060,1);
INSERT INTO ezsearch_return_count VALUES (119,28,1031915075,2);
INSERT INTO ezsearch_return_count VALUES (120,16,1031926238,0);
INSERT INTO ezsearch_return_count VALUES (121,49,1031926243,1);
INSERT INTO ezsearch_return_count VALUES (122,2,1031926252,31);
INSERT INTO ezsearch_return_count VALUES (123,16,1031926260,0);
INSERT INTO ezsearch_return_count VALUES (124,49,1031926264,1);
INSERT INTO ezsearch_return_count VALUES (125,28,1031926734,2);
INSERT INTO ezsearch_return_count VALUES (126,28,1031926761,2);
INSERT INTO ezsearch_return_count VALUES (127,41,1031928201,1);
INSERT INTO ezsearch_return_count VALUES (128,41,1031928237,1);
INSERT INTO ezsearch_return_count VALUES (129,50,1031928442,1);
INSERT INTO ezsearch_return_count VALUES (130,51,1031928445,3);
INSERT INTO ezsearch_return_count VALUES (131,51,1031928484,3);
INSERT INTO ezsearch_return_count VALUES (132,51,1031928489,3);
INSERT INTO ezsearch_return_count VALUES (133,51,1031928492,3);
INSERT INTO ezsearch_return_count VALUES (134,51,1031928496,3);
INSERT INTO ezsearch_return_count VALUES (135,51,1031928552,3);
INSERT INTO ezsearch_return_count VALUES (136,52,1031928565,1);
INSERT INTO ezsearch_return_count VALUES (137,52,1031928943,1);
INSERT INTO ezsearch_return_count VALUES (138,53,1031928948,1);
INSERT INTO ezsearch_return_count VALUES (139,54,1031928951,1);
INSERT INTO ezsearch_return_count VALUES (140,41,1031928959,1);
INSERT INTO ezsearch_return_count VALUES (141,51,1031928966,3);
INSERT INTO ezsearch_return_count VALUES (142,55,1031929024,1);
INSERT INTO ezsearch_return_count VALUES (143,56,1031929030,1);
INSERT INTO ezsearch_return_count VALUES (144,46,1032004372,2);
INSERT INTO ezsearch_return_count VALUES (145,17,1032004376,5);
INSERT INTO ezsearch_return_count VALUES (146,46,1032004387,2);
INSERT INTO ezsearch_return_count VALUES (147,57,1032004607,10);
INSERT INTO ezsearch_return_count VALUES (148,58,1032004882,1);
INSERT INTO ezsearch_return_count VALUES (149,59,1032004889,1);
INSERT INTO ezsearch_return_count VALUES (150,60,1032004892,10);
INSERT INTO ezsearch_return_count VALUES (151,60,1032004921,10);
INSERT INTO ezsearch_return_count VALUES (152,2,1032004928,41);
INSERT INTO ezsearch_return_count VALUES (153,60,1032004934,1);
INSERT INTO ezsearch_return_count VALUES (154,60,1032004944,1);
INSERT INTO ezsearch_return_count VALUES (155,60,1032004949,2);
INSERT INTO ezsearch_return_count VALUES (156,60,1032004953,1);
INSERT INTO ezsearch_return_count VALUES (157,61,1032009236,0);
INSERT INTO ezsearch_return_count VALUES (158,46,1032009268,2);
INSERT INTO ezsearch_return_count VALUES (159,46,1032009349,2);
INSERT INTO ezsearch_return_count VALUES (160,46,1032009363,2);
INSERT INTO ezsearch_return_count VALUES (161,62,1032011791,1);
INSERT INTO ezsearch_return_count VALUES (162,62,1032011805,1);
INSERT INTO ezsearch_return_count VALUES (163,60,1032011808,11);
INSERT INTO ezsearch_return_count VALUES (164,63,1032011812,0);

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

INSERT INTO ezsearch_search_phrase VALUES (1,'hovedkategori');
INSERT INTO ezsearch_search_phrase VALUES (2,'');
INSERT INTO ezsearch_search_phrase VALUES (3,'2');
INSERT INTO ezsearch_search_phrase VALUES (4,'rekkehus');
INSERT INTO ezsearch_search_phrase VALUES (5,'1970');
INSERT INTO ezsearch_search_phrase VALUES (6,'demo');
INSERT INTO ezsearch_search_phrase VALUES (7,'etasjer');
INSERT INTO ezsearch_search_phrase VALUES (8,'notar');
INSERT INTO ezsearch_search_phrase VALUES (9,'katte');
INSERT INTO ezsearch_search_phrase VALUES (10,'eiendom');
INSERT INTO ezsearch_search_phrase VALUES (11,'over 2');
INSERT INTO ezsearch_search_phrase VALUES (12,'kr');
INSERT INTO ezsearch_search_phrase VALUES (13,'test');
INSERT INTO ezsearch_search_phrase VALUES (14,'node i content');
INSERT INTO ezsearch_search_phrase VALUES (15,'\"node i content\"');
INSERT INTO ezsearch_search_phrase VALUES (16,'lilla');
INSERT INTO ezsearch_search_phrase VALUES (17,'lorem');
INSERT INTO ezsearch_search_phrase VALUES (18,'154');
INSERT INTO ezsearch_search_phrase VALUES (19,'1965');
INSERT INTO ezsearch_search_phrase VALUES (20,'1956');
INSERT INTO ezsearch_search_phrase VALUES (21,'ez<b>test</b>');
INSERT INTO ezsearch_search_phrase VALUES (22,'teset');
INSERT INTO ezsearch_search_phrase VALUES (23,'æøå');
INSERT INTO ezsearch_search_phrase VALUES (24,'søk');
INSERT INTO ezsearch_search_phrase VALUES (25,'statue');
INSERT INTO ezsearch_search_phrase VALUES (26,'privet');
INSERT INTO ezsearch_search_phrase VALUES (27,'russian');
INSERT INTO ezsearch_search_phrase VALUES (28,'3');
INSERT INTO ezsearch_search_phrase VALUES (29,'sten');
INSERT INTO ezsearch_search_phrase VALUES (30,'nyttår');
INSERT INTO ezsearch_search_phrase VALUES (31,'nicole');
INSERT INTO ezsearch_search_phrase VALUES (32,'the others');
INSERT INTO ezsearch_search_phrase VALUES (33,'duis');
INSERT INTO ezsearch_search_phrase VALUES (34,'kat');
INSERT INTO ezsearch_search_phrase VALUES (35,'kat*');
INSERT INTO ezsearch_search_phrase VALUES (36,'admin');
INSERT INTO ezsearch_search_phrase VALUES (37,'john');
INSERT INTO ezsearch_search_phrase VALUES (38,'spider');
INSERT INTO ezsearch_search_phrase VALUES (39,'2002');
INSERT INTO ezsearch_search_phrase VALUES (40,'speeed');
INSERT INTO ezsearch_search_phrase VALUES (41,'speed');
INSERT INTO ezsearch_search_phrase VALUES (42,'twin');
INSERT INTO ezsearch_search_phrase VALUES (43,'\"twin spark\"');
INSERT INTO ezsearch_search_phrase VALUES (44,'\"spark twin\"');
INSERT INTO ezsearch_search_phrase VALUES (45,'others');
INSERT INTO ezsearch_search_phrase VALUES (46,'ez');
INSERT INTO ezsearch_search_phrase VALUES (47,'nyheter');
INSERT INTO ezsearch_search_phrase VALUES (48,'lord');
INSERT INTO ezsearch_search_phrase VALUES (49,'purple');
INSERT INTO ezsearch_search_phrase VALUES (50,'fjord');
INSERT INTO ezsearch_search_phrase VALUES (51,'by');
INSERT INTO ezsearch_search_phrase VALUES (52,'branch');
INSERT INTO ezsearch_search_phrase VALUES (53,'yellow');
INSERT INTO ezsearch_search_phrase VALUES (54,'tree');
INSERT INTO ezsearch_search_phrase VALUES (55,'bloody');
INSERT INTO ezsearch_search_phrase VALUES (56,'lotr');
INSERT INTO ezsearch_search_phrase VALUES (57,'is');
INSERT INTO ezsearch_search_phrase VALUES (58,'image');
INSERT INTO ezsearch_search_phrase VALUES (59,'gallery');
INSERT INTO ezsearch_search_phrase VALUES (60,'the');
INSERT INTO ezsearch_search_phrase VALUES (61,'bård');
INSERT INTO ezsearch_search_phrase VALUES (62,'marble');
INSERT INTO ezsearch_search_phrase VALUES (63,'mp3');

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

INSERT INTO ezsearch_word VALUES (4,'pushchin',1);
INSERT INTO ezsearch_word VALUES (3,'sergey',1);
INSERT INTO ezsearch_word VALUES (5,'main',2);
INSERT INTO ezsearch_word VALUES (6,'group',2);
INSERT INTO ezsearch_word VALUES (7,'this',14);
INSERT INTO ezsearch_word VALUES (8,'is',21);
INSERT INTO ezsearch_word VALUES (9,'the',45);
INSERT INTO ezsearch_word VALUES (10,'master',1);
INSERT INTO ezsearch_word VALUES (11,'users',5);
INSERT INTO ezsearch_word VALUES (14,'other',4);
INSERT INTO ezsearch_word VALUES (15,'floyd',2);
INSERT INTO ezsearch_word VALUES (16,'first',6);
INSERT INTO ezsearch_word VALUES (17,'article',7);
INSERT INTO ezsearch_word VALUES (18,'bla',26);
INSERT INTO ezsearch_word VALUES (19,'blabla',1);
INSERT INTO ezsearch_word VALUES (20,'blbla',1);
INSERT INTO ezsearch_word VALUES (21,'blaa',1);
INSERT INTO ezsearch_word VALUES (22,'second',2);
INSERT INTO ezsearch_word VALUES (23,'foo',24);
INSERT INTO ezsearch_word VALUES (872,'very',1);
INSERT INTO ezsearch_word VALUES (103,'i',18);
INSERT INTO ezsearch_word VALUES (871,'so',2);
INSERT INTO ezsearch_word VALUES (870,'movie',3);
INSERT INTO ezsearch_word VALUES (922,'frontpage',2);
INSERT INTO ezsearch_word VALUES (30,'products',2);
INSERT INTO ezsearch_word VALUES (31,'here',3);
INSERT INTO ezsearch_word VALUES (32,'are',3);
INSERT INTO ezsearch_word VALUES (33,'ez',20);
INSERT INTO ezsearch_word VALUES (34,'publish',22);
INSERT INTO ezsearch_word VALUES (35,'3',6);
INSERT INTO ezsearch_word VALUES (36,'0',7);
INSERT INTO ezsearch_word VALUES (37,'(the',1);
INSERT INTO ezsearch_word VALUES (38,'best',1);
INSERT INTO ezsearch_word VALUES (39,'cms',1);
INSERT INTO ezsearch_word VALUES (40,'ever)',1);
INSERT INTO ezsearch_word VALUES (41,'10000',1);
INSERT INTO ezsearch_word VALUES (42,'book',1);
INSERT INTO ezsearch_word VALUES (43,'how',3);
INSERT INTO ezsearch_word VALUES (44,'to',8);
INSERT INTO ezsearch_word VALUES (45,'manage',1);
INSERT INTO ezsearch_word VALUES (46,'your',2);
INSERT INTO ezsearch_word VALUES (47,'content',2);
INSERT INTO ezsearch_word VALUES (48,'with',3);
INSERT INTO ezsearch_word VALUES (49,'500',1);
INSERT INTO ezsearch_word VALUES (50,'test',14);
INSERT INTO ezsearch_word VALUES (51,'abc',1);
INSERT INTO ezsearch_word VALUES (52,'def',1);
INSERT INTO ezsearch_word VALUES (53,'ewgsweg',1);
INSERT INTO ezsearch_word VALUES (54,'11',1);
INSERT INTO ezsearch_word VALUES (55,'13',1);
INSERT INTO ezsearch_word VALUES (56,'1988',1);
INSERT INTO ezsearch_word VALUES (60,'345345345',1);
INSERT INTO ezsearch_word VALUES (59,'34534',1);
INSERT INTO ezsearch_word VALUES (61,'345',1);
INSERT INTO ezsearch_word VALUES (62,'dfgdsfgdsfg',1);
INSERT INTO ezsearch_word VALUES (85,'doe',3);
INSERT INTO ezsearch_word VALUES (84,'john',3);
INSERT INTO ezsearch_word VALUES (83,'setsetsetset',1);
INSERT INTO ezsearch_word VALUES (82,'setset',4);
INSERT INTO ezsearch_word VALUES (81,'new',6);
INSERT INTO ezsearch_word VALUES (935,'category',1);
INSERT INTO ezsearch_word VALUES (937,'discs',1);
INSERT INTO ezsearch_word VALUES (88,'for',6);
INSERT INTO ezsearch_word VALUES (118,'eiendom',2);
INSERT INTO ezsearch_word VALUES (688,'image',2);
INSERT INTO ezsearch_word VALUES (120,'her',6);
INSERT INTO ezsearch_word VALUES (927,'diverse',1);
INSERT INTO ezsearch_word VALUES (687,'naturen',1);
INSERT INTO ezsearch_word VALUES (686,'ute',1);
INSERT INTO ezsearch_word VALUES (128,'speed',4);
INSERT INTO ezsearch_word VALUES (129,'nyttr',2);
INSERT INTO ezsearch_word VALUES (926,'media',2);
INSERT INTO ezsearch_word VALUES (685,'flower',2);
INSERT INTO ezsearch_word VALUES (374,'pus',1);
INSERT INTO ezsearch_word VALUES (373,'katte',1);
INSERT INTO ezsearch_word VALUES (134,'statue',2);
INSERT INTO ezsearch_word VALUES (378,'ipsolum',58);
INSERT INTO ezsearch_word VALUES (136,'p',8);
INSERT INTO ezsearch_word VALUES (137,'brd',5);
INSERT INTO ezsearch_word VALUES (138,'farstad',4);
INSERT INTO ezsearch_word VALUES (377,'lorem',176);
INSERT INTO ezsearch_word VALUES (376,'demo',5);
INSERT INTO ezsearch_word VALUES (141,'leilighet/b/l',1);
INSERT INTO ezsearch_word VALUES (142,'rekkehus',2);
INSERT INTO ezsearch_word VALUES (143,'over',3);
INSERT INTO ezsearch_word VALUES (144,'2',12);
INSERT INTO ezsearch_word VALUES (145,'etasjer',2);
INSERT INTO ezsearch_word VALUES (146,'117',1);
INSERT INTO ezsearch_word VALUES (147,'kvm',2);
INSERT INTO ezsearch_word VALUES (148,'kr',2);
INSERT INTO ezsearch_word VALUES (149,'990',1);
INSERT INTO ezsearch_word VALUES (150,'000',2);
INSERT INTO ezsearch_word VALUES (151,'+',2);
INSERT INTO ezsearch_word VALUES (152,'omk',2);
INSERT INTO ezsearch_word VALUES (153,'1970',1);
INSERT INTO ezsearch_word VALUES (154,'trivelig',1);
INSERT INTO ezsearch_word VALUES (155,'plan',1);
INSERT INTO ezsearch_word VALUES (156,'med',6);
INSERT INTO ezsearch_word VALUES (157,'gode',1);
INSERT INTO ezsearch_word VALUES (158,'kvaliter',1);
INSERT INTO ezsearch_word VALUES (159,'stor',6);
INSERT INTO ezsearch_word VALUES (160,'usjenert',1);
INSERT INTO ezsearch_word VALUES (161,'og',13);
INSERT INTO ezsearch_word VALUES (162,'solrik',2);
INSERT INTO ezsearch_word VALUES (163,'terasse',1);
INSERT INTO ezsearch_word VALUES (164,'leiligheten',1);
INSERT INTO ezsearch_word VALUES (165,'har',4);
INSERT INTO ezsearch_word VALUES (166,'god',1);
INSERT INTO ezsearch_word VALUES (167,'planlsning',1);
INSERT INTO ezsearch_word VALUES (168,'stue',1);
INSERT INTO ezsearch_word VALUES (169,'etasje',1);
INSERT INTO ezsearch_word VALUES (170,'nytt',2);
INSERT INTO ezsearch_word VALUES (171,'eikekjkken',1);
INSERT INTO ezsearch_word VALUES (172,'stort',1);
INSERT INTO ezsearch_word VALUES (173,'flislagt',1);
INSERT INTO ezsearch_word VALUES (174,'bad',2);
INSERT INTO ezsearch_word VALUES (175,'det',5);
INSERT INTO ezsearch_word VALUES (176,'er',15);
INSERT INTO ezsearch_word VALUES (177,'lagt',1);
INSERT INTO ezsearch_word VALUES (178,'laminat/linoleum',1);
INSERT INTO ezsearch_word VALUES (179,'gulvene',1);
INSERT INTO ezsearch_word VALUES (180,'vegger',1);
INSERT INTO ezsearch_word VALUES (181,'malt',1);
INSERT INTO ezsearch_word VALUES (182,'strie',1);
INSERT INTO ezsearch_word VALUES (183,'panel',1);
INSERT INTO ezsearch_word VALUES (184,'svrt',2);
INSERT INTO ezsearch_word VALUES (185,'barnevennlig',1);
INSERT INTO ezsearch_word VALUES (186,'omgivelser',1);
INSERT INTO ezsearch_word VALUES (187,'lekeplasser',1);
INSERT INTO ezsearch_word VALUES (188,'flotte',1);
INSERT INTO ezsearch_word VALUES (189,'turomrder',1);
INSERT INTO ezsearch_word VALUES (190,'umiddelbar',1);
INSERT INTO ezsearch_word VALUES (191,'nrhet',1);
INSERT INTO ezsearch_word VALUES (192,'skole',1);
INSERT INTO ezsearch_word VALUES (193,'barnehage',1);
INSERT INTO ezsearch_word VALUES (194,'like',2);
INSERT INTO ezsearch_word VALUES (195,'ved',1);
INSERT INTO ezsearch_word VALUES (196,'kort',2);
INSERT INTO ezsearch_word VALUES (197,'vei',2);
INSERT INTO ezsearch_word VALUES (198,'til',8);
INSERT INTO ezsearch_word VALUES (199,'nrbutikk',1);
INSERT INTO ezsearch_word VALUES (200,'buss',1);
INSERT INTO ezsearch_word VALUES (201,'garasje',1);
INSERT INTO ezsearch_word VALUES (202,'flger',1);
INSERT INTO ezsearch_word VALUES (203,'boligen',1);
INSERT INTO ezsearch_word VALUES (204,'notar',2);
INSERT INTO ezsearch_word VALUES (205,'bergen',1);
INSERT INTO ezsearch_word VALUES (206,'as',9);
INSERT INTO ezsearch_word VALUES (207,'bryggen',2);
INSERT INTO ezsearch_word VALUES (208,'15',2);
INSERT INTO ezsearch_word VALUES (209,'tlf',1);
INSERT INTO ezsearch_word VALUES (210,':',2);
INSERT INTO ezsearch_word VALUES (211,'55559750',1);
INSERT INTO ezsearch_word VALUES (212,'fax:',2);
INSERT INTO ezsearch_word VALUES (213,'55559797',1);
INSERT INTO ezsearch_word VALUES (214,'epost:',1);
INSERT INTO ezsearch_word VALUES (215,'kontakt',2);
INSERT INTO ezsearch_word VALUES (216,'bergen@notar',1);
INSERT INTO ezsearch_word VALUES (217,'no',4);
INSERT INTO ezsearch_word VALUES (218,'editors',4);
INSERT INTO ezsearch_word VALUES (219,'user',1);
INSERT INTO ezsearch_word VALUES (220,'editor',4);
INSERT INTO ezsearch_word VALUES (221,'advanced',2);
INSERT INTO ezsearch_word VALUES (222,'we',1);
INSERT INTO ezsearch_word VALUES (223,'will',2);
INSERT INTO ezsearch_word VALUES (224,'store',1);
INSERT INTO ezsearch_word VALUES (225,'acounts',1);
INSERT INTO ezsearch_word VALUES (226,'chief',1);
INSERT INTO ezsearch_word VALUES (227,'fritidseiendom',1);
INSERT INTO ezsearch_word VALUES (228,'-',1);
INSERT INTO ezsearch_word VALUES (229,'brandbu',2);
INSERT INTO ezsearch_word VALUES (230,'gran',1);
INSERT INTO ezsearch_word VALUES (231,'hornslinna',1);
INSERT INTO ezsearch_word VALUES (232,'154',1);
INSERT INTO ezsearch_word VALUES (233,'55',1);
INSERT INTO ezsearch_word VALUES (234,'490',1);
INSERT INTO ezsearch_word VALUES (235,'1956',1);
INSERT INTO ezsearch_word VALUES (236,'innhold:',1);
INSERT INTO ezsearch_word VALUES (237,'gang',1);
INSERT INTO ezsearch_word VALUES (238,'kjkken',1);
INSERT INTO ezsearch_word VALUES (239,'wc',1);
INSERT INTO ezsearch_word VALUES (240,'soverom',1);
INSERT INTO ezsearch_word VALUES (241,'stuer',1);
INSERT INTO ezsearch_word VALUES (242,'fritidsbolig',1);
INSERT INTO ezsearch_word VALUES (243,'ca',2);
INSERT INTO ezsearch_word VALUES (244,'9',1);
INSERT INTO ezsearch_word VALUES (245,'km',1);
INSERT INTO ezsearch_word VALUES (246,'fra',2);
INSERT INTO ezsearch_word VALUES (247,'sentrum',1);
INSERT INTO ezsearch_word VALUES (248,'fredelig',1);
INSERT INTO ezsearch_word VALUES (249,'omrde',1);
INSERT INTO ezsearch_word VALUES (250,'fritidsboligen',1);
INSERT INTO ezsearch_word VALUES (251,'innlagt',1);
INSERT INTO ezsearch_word VALUES (252,'strm',1);
INSERT INTO ezsearch_word VALUES (253,'40',1);
INSERT INTO ezsearch_word VALUES (254,'m',5);
INSERT INTO ezsearch_word VALUES (255,'randsfjordens',1);
INSERT INTO ezsearch_word VALUES (256,'strandlinje',1);
INSERT INTO ezsearch_word VALUES (257,'skog',1);
INSERT INTO ezsearch_word VALUES (258,'mark',1);
INSERT INTO ezsearch_word VALUES (259,'lillestrm',1);
INSERT INTO ezsearch_word VALUES (260,'asjernbanegata',1);
INSERT INTO ezsearch_word VALUES (261,'8',1);
INSERT INTO ezsearch_word VALUES (262,'jernbanegata',1);
INSERT INTO ezsearch_word VALUES (263,'8tlf',1);
INSERT INTO ezsearch_word VALUES (264,'63805300',1);
INSERT INTO ezsearch_word VALUES (265,'63805301epost:',1);
INSERT INTO ezsearch_word VALUES (266,'lillestrom@notar',1);
INSERT INTO ezsearch_word VALUES (291,'objekt',1);
INSERT INTO ezsearch_word VALUES (290,'inkludert',1);
INSERT INTO ezsearch_word VALUES (289,'et',11);
INSERT INTO ezsearch_word VALUES (288,'innholdet',1);
INSERT INTO ezsearch_word VALUES (287,'interessant',4);
INSERT INTO ezsearch_word VALUES (286,'masse',4);
INSERT INTO ezsearch_word VALUES (285,'en',12);
INSERT INTO ezsearch_word VALUES (284,'introduksjon',4);
INSERT INTO ezsearch_word VALUES (283,'siste',1);
INSERT INTO ezsearch_word VALUES (282,'dette',9);
INSERT INTO ezsearch_word VALUES (281,'nyhet',5);
INSERT INTO ezsearch_word VALUES (292,'av',4);
INSERT INTO ezsearch_word VALUES (293,'typen',1);
INSERT INTO ezsearch_word VALUES (294,'bilde:',1);
INSERT INTO ezsearch_word VALUES (301,'du',1);
INSERT INTO ezsearch_word VALUES (300,'finner',1);
INSERT INTO ezsearch_word VALUES (299,'presentasjoner',2);
INSERT INTO ezsearch_word VALUES (302,'powerpoint',1);
INSERT INTO ezsearch_word VALUES (303,'ny',3);
INSERT INTO ezsearch_word VALUES (304,'presentasjon',3);
INSERT INTO ezsearch_word VALUES (305,'folder',2);
INSERT INTO ezsearch_word VALUES (306,'fooooooo',1);
INSERT INTO ezsearch_word VALUES (307,'foooooooo',1);
INSERT INTO ezsearch_word VALUES (308,'fooooooooo',1);
INSERT INTO ezsearch_word VALUES (309,'fffffffffff',1);
INSERT INTO ezsearch_word VALUES (310,'ffffffffffffff',1);
INSERT INTO ezsearch_word VALUES (311,'t1',2);
INSERT INTO ezsearch_word VALUES (312,'colour',1);
INSERT INTO ezsearch_word VALUES (313,'gfj',1);
INSERT INTO ezsearch_word VALUES (314,'fgjfgj',1);
INSERT INTO ezsearch_word VALUES (315,'gfjgfhj',1);
INSERT INTO ezsearch_word VALUES (316,'dfhdfghgh',1);
INSERT INTO ezsearch_word VALUES (317,'ghkghkghj',1);
INSERT INTO ezsearch_word VALUES (318,'jalmar',1);
INSERT INTO ezsearch_word VALUES (319,'56',1);
INSERT INTO ezsearch_word VALUES (320,'$root',2);
INSERT INTO ezsearch_word VALUES (321,'=&',3);
INSERT INTO ezsearch_word VALUES (322,'$doc->createelementnode(',2);
INSERT INTO ezsearch_word VALUES (323,'ezoption',1);
INSERT INTO ezsearch_word VALUES (324,');',7);
INSERT INTO ezsearch_word VALUES (325,'$doc->setroot(',1);
INSERT INTO ezsearch_word VALUES (326,'$name',2);
INSERT INTO ezsearch_word VALUES (327,'name',3);
INSERT INTO ezsearch_word VALUES (328,'$namevalue',2);
INSERT INTO ezsearch_word VALUES (329,'$doc->createtextnode(',1);
INSERT INTO ezsearch_word VALUES (330,'$this->name',1);
INSERT INTO ezsearch_word VALUES (331,'$name->appendchild(',1);
INSERT INTO ezsearch_word VALUES (332,'$name->setcontent(',1);
INSERT INTO ezsearch_word VALUES (333,'$this->name()',1);
INSERT INTO ezsearch_word VALUES (334,'$root->appendchild(',1);
INSERT INTO ezsearch_word VALUES (335,'bunad',1);
INSERT INTO ezsearch_word VALUES (336,'spenne',1);
INSERT INTO ezsearch_word VALUES (337,'!!!!!!',1);
INSERT INTO ezsearch_word VALUES (338,'',5);
INSERT INTO ezsearch_word VALUES (339,'sucs',1);
INSERT INTO ezsearch_word VALUES (340,'tes',1);
INSERT INTO ezsearch_word VALUES (344,'drydsryt',1);
INSERT INTO ezsearch_word VALUES (343,'topic',4);
INSERT INTO ezsearch_word VALUES (345,'sdfgsdfgsdfgsdf',1);
INSERT INTO ezsearch_word VALUES (346,'sdfgsdfg',1);
INSERT INTO ezsearch_word VALUES (347,'sdfgsdfgsdfgsdfg',1);
INSERT INTO ezsearch_word VALUES (348,'product',2);
INSERT INTO ezsearch_word VALUES (349,'about',2);
INSERT INTO ezsearch_word VALUES (350,'435',1);
INSERT INTO ezsearch_word VALUES (351,'sketest',1);
INSERT INTO ezsearch_word VALUES (352,'sk',1);
INSERT INTO ezsearch_word VALUES (353,'213',1);
INSERT INTO ezsearch_word VALUES (364,'9234',1);
INSERT INTO ezsearch_word VALUES (363,'windows2000',1);
INSERT INTO ezsearch_word VALUES (362,'ogeller123m',1);
INSERT INTO ezsearch_word VALUES (361,'redaksjonen',2);
INSERT INTO ezsearch_word VALUES (360,'search',1);
INSERT INTO ezsearch_word VALUES (365,'tjallabeis',1);
INSERT INTO ezsearch_word VALUES (366,'set',2);
INSERT INTO ezsearch_word VALUES (367,'setsetset',2);
INSERT INTO ezsearch_word VALUES (371,'translate',1);
INSERT INTO ezsearch_word VALUES (372,'teset',1);
INSERT INTO ezsearch_word VALUES (936,'dvd',2);
INSERT INTO ezsearch_word VALUES (925,'shop',2);
INSERT INTO ezsearch_word VALUES (381,'nyhets',1);
INSERT INTO ezsearch_word VALUES (382,'liten',5);
INSERT INTO ezsearch_word VALUES (383,'nyhetspublisering',2);
INSERT INTO ezsearch_word VALUES (384,'ipsum',118);
INSERT INTO ezsearch_word VALUES (385,'dolor',8);
INSERT INTO ezsearch_word VALUES (386,'sit',4);
INSERT INTO ezsearch_word VALUES (387,'amet',4);
INSERT INTO ezsearch_word VALUES (388,'consectetuer',4);
INSERT INTO ezsearch_word VALUES (389,'adipiscing',4);
INSERT INTO ezsearch_word VALUES (390,'elit',4);
INSERT INTO ezsearch_word VALUES (391,'sed',4);
INSERT INTO ezsearch_word VALUES (392,'diam',4);
INSERT INTO ezsearch_word VALUES (393,'nonummy',5);
INSERT INTO ezsearch_word VALUES (394,'nibh',5);
INSERT INTO ezsearch_word VALUES (395,'euismod',4);
INSERT INTO ezsearch_word VALUES (396,'tincidunt',4);
INSERT INTO ezsearch_word VALUES (397,'ut',12);
INSERT INTO ezsearch_word VALUES (398,'laoreet',4);
INSERT INTO ezsearch_word VALUES (399,'dolore',8);
INSERT INTO ezsearch_word VALUES (400,'magna',4);
INSERT INTO ezsearch_word VALUES (401,'aliquam',4);
INSERT INTO ezsearch_word VALUES (402,'erat',4);
INSERT INTO ezsearch_word VALUES (403,'volutpat',4);
INSERT INTO ezsearch_word VALUES (404,'wisi',4);
INSERT INTO ezsearch_word VALUES (405,'enim',4);
INSERT INTO ezsearch_word VALUES (406,'ad',4);
INSERT INTO ezsearch_word VALUES (407,'minim',4);
INSERT INTO ezsearch_word VALUES (408,'veniam',4);
INSERT INTO ezsearch_word VALUES (409,'quis',4);
INSERT INTO ezsearch_word VALUES (410,'nostrud',4);
INSERT INTO ezsearch_word VALUES (411,'exerci',4);
INSERT INTO ezsearch_word VALUES (412,'tation',4);
INSERT INTO ezsearch_word VALUES (413,'ullamcorper',4);
INSERT INTO ezsearch_word VALUES (414,'suscipit',4);
INSERT INTO ezsearch_word VALUES (415,'lobortis',4);
INSERT INTO ezsearch_word VALUES (416,'nisl',4);
INSERT INTO ezsearch_word VALUES (417,'aliquip',4);
INSERT INTO ezsearch_word VALUES (418,'ex',4);
INSERT INTO ezsearch_word VALUES (419,'ea',4);
INSERT INTO ezsearch_word VALUES (420,'commodo',4);
INSERT INTO ezsearch_word VALUES (421,'consequat',8);
INSERT INTO ezsearch_word VALUES (422,'duis',4);
INSERT INTO ezsearch_word VALUES (423,'autem',4);
INSERT INTO ezsearch_word VALUES (424,'vel',8);
INSERT INTO ezsearch_word VALUES (425,'eum',4);
INSERT INTO ezsearch_word VALUES (426,'iriure',4);
INSERT INTO ezsearch_word VALUES (427,'in',12);
INSERT INTO ezsearch_word VALUES (428,'hendrerit',4);
INSERT INTO ezsearch_word VALUES (429,'vulputate',4);
INSERT INTO ezsearch_word VALUES (430,'velit',4);
INSERT INTO ezsearch_word VALUES (431,'esse',4);
INSERT INTO ezsearch_word VALUES (432,'molestie',4);
INSERT INTO ezsearch_word VALUES (433,'illum',4);
INSERT INTO ezsearch_word VALUES (434,'eu',4);
INSERT INTO ezsearch_word VALUES (435,'feugiat',4);
INSERT INTO ezsearch_word VALUES (436,'nulla',4);
INSERT INTO ezsearch_word VALUES (437,'facilisis',4);
INSERT INTO ezsearch_word VALUES (438,'at',6);
INSERT INTO ezsearch_word VALUES (439,'vero',4);
INSERT INTO ezsearch_word VALUES (440,'eros',4);
INSERT INTO ezsearch_word VALUES (441,'accumsan',4);
INSERT INTO ezsearch_word VALUES (442,'iusto',4);
INSERT INTO ezsearch_word VALUES (443,'odio',4);
INSERT INTO ezsearch_word VALUES (444,'dignissim',4);
INSERT INTO ezsearch_word VALUES (445,'qui',4);
INSERT INTO ezsearch_word VALUES (446,'blandit',4);
INSERT INTO ezsearch_word VALUES (447,'praesent',4);
INSERT INTO ezsearch_word VALUES (448,'luptatum',4);
INSERT INTO ezsearch_word VALUES (449,'zzril',4);
INSERT INTO ezsearch_word VALUES (450,'delenit',4);
INSERT INTO ezsearch_word VALUES (451,'others',1);
INSERT INTO ezsearch_word VALUES (452,'ersey',1);
INSERT INTO ezsearch_word VALUES (453,'den',4);
INSERT INTO ezsearch_word VALUES (454,'engelske',1);
INSERT INTO ezsearch_word VALUES (455,'kanal',1);
INSERT INTO ezsearch_word VALUES (456,'1945',1);
INSERT INTO ezsearch_word VALUES (457,'verdenskrig',1);
INSERT INTO ezsearch_word VALUES (458,'men',2);
INSERT INTO ezsearch_word VALUES (459,'grace',3);
INSERT INTO ezsearch_word VALUES (460,'sin',2);
INSERT INTO ezsearch_word VALUES (461,'ektemann',1);
INSERT INTO ezsearch_word VALUES (462,'enda',1);
INSERT INTO ezsearch_word VALUES (463,'ikke',2);
INSERT INTO ezsearch_word VALUES (464,'returnert',1);
INSERT INTO ezsearch_word VALUES (465,'fronten',1);
INSERT INTO ezsearch_word VALUES (466,'alene',1);
INSERT INTO ezsearch_word VALUES (467,'avsidesliggende',1);
INSERT INTO ezsearch_word VALUES (468,'viktoriansk',1);
INSERT INTO ezsearch_word VALUES (469,'herskapshus',1);
INSERT INTO ezsearch_word VALUES (470,'prver',1);
INSERT INTO ezsearch_word VALUES (471,'hun',1);
INSERT INTO ezsearch_word VALUES (472,'oppdra',1);
INSERT INTO ezsearch_word VALUES (473,'datteren',1);
INSERT INTO ezsearch_word VALUES (474,'snnen',1);
INSERT INTO ezsearch_word VALUES (475,'etter',2);
INSERT INTO ezsearch_word VALUES (476,'strenge',1);
INSERT INTO ezsearch_word VALUES (477,'religise',1);
INSERT INTO ezsearch_word VALUES (478,'prinsipper',1);
INSERT INTO ezsearch_word VALUES (479,'grensen',1);
INSERT INTO ezsearch_word VALUES (480,'galskap',1);
INSERT INTO ezsearch_word VALUES (481,'barna',1);
INSERT INTO ezsearch_word VALUES (482,'lider',1);
INSERT INTO ezsearch_word VALUES (483,'merkelig',1);
INSERT INTO ezsearch_word VALUES (484,'sykdom',1);
INSERT INTO ezsearch_word VALUES (485,'de',3);
INSERT INTO ezsearch_word VALUES (486,'tler',1);
INSERT INTO ezsearch_word VALUES (487,'direkte',1);
INSERT INTO ezsearch_word VALUES (488,'dagslys',1);
INSERT INTO ezsearch_word VALUES (489,'nr',1);
INSERT INTO ezsearch_word VALUES (490,'ansetter',1);
INSERT INTO ezsearch_word VALUES (491,'tre',1);
INSERT INTO ezsearch_word VALUES (492,'tjenestefolk',1);
INSERT INTO ezsearch_word VALUES (493,'fr',2);
INSERT INTO ezsearch_word VALUES (494,'lre',1);
INSERT INTO ezsearch_word VALUES (495,'vital',1);
INSERT INTO ezsearch_word VALUES (496,'regel',1);
INSERT INTO ezsearch_word VALUES (497,'huset',3);
INSERT INTO ezsearch_word VALUES (498,'alltid',2);
INSERT INTO ezsearch_word VALUES (499,'vre',2);
INSERT INTO ezsearch_word VALUES (500,'mrklagt',1);
INSERT INTO ezsearch_word VALUES (501,'ingen',1);
INSERT INTO ezsearch_word VALUES (502,'dr',1);
INSERT INTO ezsearch_word VALUES (503,'pnes',1);
INSERT INTO ezsearch_word VALUES (504,'forrige',1);
INSERT INTO ezsearch_word VALUES (505,'dren',1);
INSERT INTO ezsearch_word VALUES (506,'lukket',1);
INSERT INTO ezsearch_word VALUES (507,'mystiske',1);
INSERT INTO ezsearch_word VALUES (508,'lyder',1);
INSERT INTO ezsearch_word VALUES (509,'begynner',2);
INSERT INTO ezsearch_word VALUES (510,'uten',1);
INSERT INTO ezsearch_word VALUES (511,'forklaring',1);
INSERT INTO ezsearch_word VALUES (512,'fylle',1);
INSERT INTO ezsearch_word VALUES (513,'anklager',1);
INSERT INTO ezsearch_word VALUES (514,'begynnelsen',1);
INSERT INTO ezsearch_word VALUES (515,'nye',1);
INSERT INTO ezsearch_word VALUES (516,'tjenestefolkene',1);
INSERT INTO ezsearch_word VALUES (517,'snart',1);
INSERT INTO ezsearch_word VALUES (518,'lure',1);
INSERT INTO ezsearch_word VALUES (519,'kan',1);
INSERT INTO ezsearch_word VALUES (520,'andre',1);
INSERT INTO ezsearch_word VALUES (521,'sone',3);
INSERT INTO ezsearch_word VALUES (522,'2001',1);
INSERT INTO ezsearch_word VALUES (523,'nicole',1);
INSERT INTO ezsearch_word VALUES (524,'kidman',1);
INSERT INTO ezsearch_word VALUES (525,'action',2);
INSERT INTO ezsearch_word VALUES (526,'199',1);
INSERT INTO ezsearch_word VALUES (527,'kommentar',2);
INSERT INTO ezsearch_word VALUES (528,'denne',1);
INSERT INTO ezsearch_word VALUES (529,'nyheten',1);
INSERT INTO ezsearch_word VALUES (530,'mener',2);
INSERT INTO ezsearch_word VALUES (531,'jeg',2);
INSERT INTO ezsearch_word VALUES (532,'spider',2);
INSERT INTO ezsearch_word VALUES (533,'man',2);
INSERT INTO ezsearch_word VALUES (534,'2002',3);
INSERT INTO ezsearch_word VALUES (535,'peter',2);
INSERT INTO ezsearch_word VALUES (536,'parker',1);
INSERT INTO ezsearch_word VALUES (537,'lever',1);
INSERT INTO ezsearch_word VALUES (538,'anonym',1);
INSERT INTO ezsearch_word VALUES (539,'tilvrelse',1);
INSERT INTO ezsearch_word VALUES (540,'hos',2);
INSERT INTO ezsearch_word VALUES (541,'kjre',1);
INSERT INTO ezsearch_word VALUES (542,'tante',1);
INSERT INTO ezsearch_word VALUES (543,'may',1);
INSERT INTO ezsearch_word VALUES (544,'onkel',1);
INSERT INTO ezsearch_word VALUES (545,'ben',1);
INSERT INTO ezsearch_word VALUES (546,'han',4);
INSERT INTO ezsearch_word VALUES (547,'ble',1);
INSERT INTO ezsearch_word VALUES (548,'foreldrels',1);
INSERT INTO ezsearch_word VALUES (549,'som',1);
INSERT INTO ezsearch_word VALUES (550,'helt',1);
INSERT INTO ezsearch_word VALUES (551,'normal',1);
INSERT INTO ezsearch_word VALUES (552,'student',1);
INSERT INTO ezsearch_word VALUES (553,'litt',1);
INSERT INTO ezsearch_word VALUES (554,'nerdete',1);
INSERT INTO ezsearch_word VALUES (555,'lykke',1);
INSERT INTO ezsearch_word VALUES (556,'jentene',1);
INSERT INTO ezsearch_word VALUES (557,'spesielt',1);
INSERT INTO ezsearch_word VALUES (558,'jenta',1);
INSERT INTO ezsearch_word VALUES (559,'vrt',1);
INSERT INTO ezsearch_word VALUES (560,'forelsket',1);
INSERT INTO ezsearch_word VALUES (561,'nabopiken',1);
INSERT INTO ezsearch_word VALUES (562,'mary',1);
INSERT INTO ezsearch_word VALUES (563,'jane',1);
INSERT INTO ezsearch_word VALUES (564,'watson',1);
INSERT INTO ezsearch_word VALUES (565,'bruker',1);
INSERT INTO ezsearch_word VALUES (566,'mye',1);
INSERT INTO ezsearch_word VALUES (567,'fritiden',1);
INSERT INTO ezsearch_word VALUES (568,'sammen',1);
INSERT INTO ezsearch_word VALUES (569,'bestevennen',1);
INSERT INTO ezsearch_word VALUES (570,'harry',1);
INSERT INTO ezsearch_word VALUES (571,'osborn',2);
INSERT INTO ezsearch_word VALUES (572,'snn',1);
INSERT INTO ezsearch_word VALUES (573,'styrtrike',1);
INSERT INTO ezsearch_word VALUES (574,'industrimagnaten',1);
INSERT INTO ezsearch_word VALUES (575,'norman',1);
INSERT INTO ezsearch_word VALUES (576,'pal',1);
INSERT INTO ezsearch_word VALUES (577,'november',1);
INSERT INTO ezsearch_word VALUES (578,'tobey',1);
INSERT INTO ezsearch_word VALUES (579,'maguire',1);
INSERT INTO ezsearch_word VALUES (580,'willem',1);
INSERT INTO ezsearch_word VALUES (581,'dafoe',1);
INSERT INTO ezsearch_word VALUES (582,'kirsten',1);
INSERT INTO ezsearch_word VALUES (583,'dunst',1);
INSERT INTO ezsearch_word VALUES (584,'239',1);
INSERT INTO ezsearch_word VALUES (586,'kommentaren',1);
INSERT INTO ezsearch_word VALUES (587,'min',1);
INSERT INTO ezsearch_word VALUES (588,'kategori',1);
INSERT INTO ezsearch_word VALUES (589,'alfa',1);
INSERT INTO ezsearch_word VALUES (590,'liter',1);
INSERT INTO ezsearch_word VALUES (591,'twin',1);
INSERT INTO ezsearch_word VALUES (592,'spark',1);
INSERT INTO ezsearch_word VALUES (684,'purple',3);
INSERT INTO ezsearch_word VALUES (597,'var',1);
INSERT INTO ezsearch_word VALUES (598,'bra',1);
INSERT INTO ezsearch_word VALUES (599,'blalb',1);
INSERT INTO ezsearch_word VALUES (600,'alb',1);
INSERT INTO ezsearch_word VALUES (601,'la',1);
INSERT INTO ezsearch_word VALUES (602,'next',1);
INSERT INTO ezsearch_word VALUES (603,'generation',1);
INSERT INTO ezsearch_word VALUES (604,'management',1);
INSERT INTO ezsearch_word VALUES (605,'systems',1);
INSERT INTO ezsearch_word VALUES (606,'7',1);
INSERT INTO ezsearch_word VALUES (607,'now',1);
INSERT INTO ezsearch_word VALUES (608,'officially',1);
INSERT INTO ezsearch_word VALUES (609,'released',2);
INSERT INTO ezsearch_word VALUES (610,'versions',2);
INSERT INTO ezsearch_word VALUES (611,'of',19);
INSERT INTO ezsearch_word VALUES (612,'desktop',2);
INSERT INTO ezsearch_word VALUES (613,'edition',2);
INSERT INTO ezsearch_word VALUES (614,'also',2);
INSERT INTO ezsearch_word VALUES (615,'available',1);
INSERT INTO ezsearch_word VALUES (616,'all',3);
INSERT INTO ezsearch_word VALUES (617,'supported',1);
INSERT INTO ezsearch_word VALUES (618,'platforms',1);
INSERT INTO ezsearch_word VALUES (619,'installers',1);
INSERT INTO ezsearch_word VALUES (620,'be',2);
INSERT INTO ezsearch_word VALUES (621,'shortly',1);
INSERT INTO ezsearch_word VALUES (622,'release',1);
INSERT INTO ezsearch_word VALUES (623,'fixes',1);
INSERT INTO ezsearch_word VALUES (624,'several',1);
INSERT INTO ezsearch_word VALUES (625,'problems',1);
INSERT INTO ezsearch_word VALUES (626,'and',18);
INSERT INTO ezsearch_word VALUES (627,'dual',1);
INSERT INTO ezsearch_word VALUES (628,'licenced',1);
INSERT INTO ezsearch_word VALUES (629,'between',1);
INSERT INTO ezsearch_word VALUES (630,'gpl',1);
INSERT INTO ezsearch_word VALUES (631,'giving',1);
INSERT INTO ezsearch_word VALUES (632,'you',5);
INSERT INTO ezsearch_word VALUES (633,'open',2);
INSERT INTO ezsearch_word VALUES (634,'source',2);
INSERT INTO ezsearch_word VALUES (635,'free',1);
INSERT INTO ezsearch_word VALUES (636,'professional',1);
INSERT INTO ezsearch_word VALUES (637,'licence',1);
INSERT INTO ezsearch_word VALUES (638,'where',1);
INSERT INTO ezsearch_word VALUES (639,'get',2);
INSERT INTO ezsearch_word VALUES (640,'right',2);
INSERT INTO ezsearch_word VALUES (641,'use',2);
INSERT INTO ezsearch_word VALUES (642,'code',1);
INSERT INTO ezsearch_word VALUES (643,'making',1);
INSERT INTO ezsearch_word VALUES (644,'own',1);
INSERT INTO ezsearch_word VALUES (645,'commercial',1);
INSERT INTO ezsearch_word VALUES (646,'software',3);
INSERT INTO ezsearch_word VALUES (647,'why',1);
INSERT INTO ezsearch_word VALUES (648,'module',1);
INSERT INTO ezsearch_word VALUES (649,'based',1);
INSERT INTO ezsearch_word VALUES (650,'platform',1);
INSERT INTO ezsearch_word VALUES (651,'database',1);
INSERT INTO ezsearch_word VALUES (652,'independent',1);
INSERT INTO ezsearch_word VALUES (653,'today',1);
INSERT INTO ezsearch_word VALUES (654,'there',1);
INSERT INTO ezsearch_word VALUES (655,'26',1);
INSERT INTO ezsearch_word VALUES (656,'different',1);
INSERT INTO ezsearch_word VALUES (657,'modules',3);
INSERT INTO ezsearch_word VALUES (658,'maker',1);
INSERT INTO ezsearch_word VALUES (659,'trade',1);
INSERT INTO ezsearch_word VALUES (660,'function',1);
INSERT INTO ezsearch_word VALUES (661,'most',2);
INSERT INTO ezsearch_word VALUES (662,'projecting',1);
INSERT INTO ezsearch_word VALUES (663,'read',1);
INSERT INTO ezsearch_word VALUES (664,'more',1);
INSERT INTO ezsearch_word VALUES (665,'white',1);
INSERT INTO ezsearch_word VALUES (666,'paper',1);
INSERT INTO ezsearch_word VALUES (667,'rules',1);
INSERT INTO ezsearch_word VALUES (668,'an',3);
INSERT INTO ezsearch_word VALUES (669,'awsome',1);
INSERT INTO ezsearch_word VALUES (670,'piece',1);
INSERT INTO ezsearch_word VALUES (671,'one',2);
INSERT INTO ezsearch_word VALUES (672,'above',1);
INSERT INTO ezsearch_word VALUES (673,'on',1);
INSERT INTO ezsearch_word VALUES (674,'side',1);
INSERT INTO ezsearch_word VALUES (675,'simply',2);
INSERT INTO ezsearch_word VALUES (676,'pretty',1);
INSERT INTO ezsearch_word VALUES (677,'ok',4);
INSERT INTO ezsearch_word VALUES (934,'news',2);
INSERT INTO ezsearch_word VALUES (689,'gallery',2);
INSERT INTO ezsearch_word VALUES (690,'nature',2);
INSERT INTO ezsearch_word VALUES (691,'pictures',1);
INSERT INTO ezsearch_word VALUES (707,'light',2);
INSERT INTO ezsearch_word VALUES (706,'candle',2);
INSERT INTO ezsearch_word VALUES (694,'flowers',1);
INSERT INTO ezsearch_word VALUES (695,'probably',2);
INSERT INTO ezsearch_word VALUES (696,'got',2);
INSERT INTO ezsearch_word VALUES (697,'a',10);
INSERT INTO ezsearch_word VALUES (698,'but',4);
INSERT INTO ezsearch_word VALUES (699,'who',2);
INSERT INTO ezsearch_word VALUES (700,'knows',1);
INSERT INTO ezsearch_word VALUES (701,'branch',3);
INSERT INTO ezsearch_word VALUES (702,'green',3);
INSERT INTO ezsearch_word VALUES (703,'from',3);
INSERT INTO ezsearch_word VALUES (704,'norwegian',2);
INSERT INTO ezsearch_word VALUES (705,'forrest',2);
INSERT INTO ezsearch_word VALUES (708,'it',6);
INSERT INTO ezsearch_word VALUES (709,'s',3);
INSERT INTO ezsearch_word VALUES (710,'well',1);
INSERT INTO ezsearch_word VALUES (711,'banch',1);
INSERT INTO ezsearch_word VALUES (938,'cd',1);
INSERT INTO ezsearch_word VALUES (722,'lord',2);
INSERT INTO ezsearch_word VALUES (723,'rings',2);
INSERT INTO ezsearch_word VALUES (724,'fellowship',2);
INSERT INTO ezsearch_word VALUES (725,'ring',3);
INSERT INTO ezsearch_word VALUES (726,'marvellously',1);
INSERT INTO ezsearch_word VALUES (727,'sympathetic',1);
INSERT INTO ezsearch_word VALUES (728,'yet',2);
INSERT INTO ezsearch_word VALUES (729,'spectacularly',1);
INSERT INTO ezsearch_word VALUES (730,'cinematic',1);
INSERT INTO ezsearch_word VALUES (731,'treatment',1);
INSERT INTO ezsearch_word VALUES (732,'part',3);
INSERT INTO ezsearch_word VALUES (733,'tolkien',1);
INSERT INTO ezsearch_word VALUES (734,'trilogy',1);
INSERT INTO ezsearch_word VALUES (735,'jackson',1);
INSERT INTO ezsearch_word VALUES (736,'film',3);
INSERT INTO ezsearch_word VALUES (737,'that',2);
INSERT INTO ezsearch_word VALUES (738,'finally',1);
INSERT INTO ezsearch_word VALUES (739,'showed',1);
INSERT INTO ezsearch_word VALUES (740,'extraordinary',1);
INSERT INTO ezsearch_word VALUES (741,'digital',1);
INSERT INTO ezsearch_word VALUES (742,'effects',2);
INSERT INTO ezsearch_word VALUES (743,'could',1);
INSERT INTO ezsearch_word VALUES (744,'used',1);
INSERT INTO ezsearch_word VALUES (745,'support',2);
INSERT INTO ezsearch_word VALUES (746,'story',1);
INSERT INTO ezsearch_word VALUES (747,'characters',2);
INSERT INTO ezsearch_word VALUES (748,'not',6);
INSERT INTO ezsearch_word VALUES (749,'overwhelm',1);
INSERT INTO ezsearch_word VALUES (750,'them',1);
INSERT INTO ezsearch_word VALUES (751,'both',1);
INSERT INTO ezsearch_word VALUES (752,'long',2);
INSERT INTO ezsearch_word VALUES (753,'time',1);
INSERT INTO ezsearch_word VALUES (754,'fantasy',1);
INSERT INTO ezsearch_word VALUES (755,'fans',1);
INSERT INTO ezsearch_word VALUES (756,'newcomers',1);
INSERT INTO ezsearch_word VALUES (757,'alike',1);
INSERT INTO ezsearch_word VALUES (758,'were',1);
INSERT INTO ezsearch_word VALUES (759,'simultaneously',1);
INSERT INTO ezsearch_word VALUES (760,'amazed',1);
INSERT INTO ezsearch_word VALUES (761,'astonished',1);
INSERT INTO ezsearch_word VALUES (762,'left',1);
INSERT INTO ezsearch_word VALUES (763,'agog',1);
INSERT INTO ezsearch_word VALUES (764,'parts',2);
INSERT INTO ezsearch_word VALUES (765,'two',2);
INSERT INTO ezsearch_word VALUES (766,'three',2);
INSERT INTO ezsearch_word VALUES (767,'1',1);
INSERT INTO ezsearch_word VALUES (768,'august',1);
INSERT INTO ezsearch_word VALUES (769,'elijah',2);
INSERT INTO ezsearch_word VALUES (770,'wood',1);
INSERT INTO ezsearch_word VALUES (771,'ian',2);
INSERT INTO ezsearch_word VALUES (772,'mckellen',2);
INSERT INTO ezsearch_word VALUES (773,'al',1);
INSERT INTO ezsearch_word VALUES (774,'adventure',1);
INSERT INTO ezsearch_word VALUES (775,'250',1);
INSERT INTO ezsearch_word VALUES (776,'bloody',1);
INSERT INTO ezsearch_word VALUES (777,'brilliant',2);
INSERT INTO ezsearch_word VALUES (778,'amazing',1);
INSERT INTO ezsearch_word VALUES (779,'have',3);
INSERT INTO ezsearch_word VALUES (780,'seen',1);
INSERT INTO ezsearch_word VALUES (781,'fully',1);
INSERT INTO ezsearch_word VALUES (782,'deserves',1);
INSERT INTO ezsearch_word VALUES (783,'my',1);
INSERT INTO ezsearch_word VALUES (784,'five',1);
INSERT INTO ezsearch_word VALUES (785,'star',2);
INSERT INTO ezsearch_word VALUES (786,'rating',1);
INSERT INTO ezsearch_word VALUES (787,'bringing',1);
INSERT INTO ezsearch_word VALUES (788,'world',1);
INSERT INTO ezsearch_word VALUES (789,'middle',1);
INSERT INTO ezsearch_word VALUES (790,'earth',1);
INSERT INTO ezsearch_word VALUES (791,'life',1);
INSERT INTO ezsearch_word VALUES (792,'array',1);
INSERT INTO ezsearch_word VALUES (793,'awe',1);
INSERT INTO ezsearch_word VALUES (794,'inspireing',1);
INSERT INTO ezsearch_word VALUES (795,'sets',1);
INSERT INTO ezsearch_word VALUES (796,'great',1);
INSERT INTO ezsearch_word VALUES (797,'speacial',1);
INSERT INTO ezsearch_word VALUES (798,'startling',1);
INSERT INTO ezsearch_word VALUES (799,'lotr',1);
INSERT INTO ezsearch_word VALUES (800,'masterpeice',1);
INSERT INTO ezsearch_word VALUES (801,'joking',1);
INSERT INTO ezsearch_word VALUES (802,'does',1);
INSERT INTO ezsearch_word VALUES (803,'few',1);
INSERT INTO ezsearch_word VALUES (804,'flaws',1);
INSERT INTO ezsearch_word VALUES (805,'such',2);
INSERT INTO ezsearch_word VALUES (806,'ommision',1);
INSERT INTO ezsearch_word VALUES (807,'tom',1);
INSERT INTO ezsearch_word VALUES (808,'bombadil',1);
INSERT INTO ezsearch_word VALUES (809,'goldberry',1);
INSERT INTO ezsearch_word VALUES (810,'heightening',1);
INSERT INTO ezsearch_word VALUES (811,'arwin',1);
INSERT INTO ezsearch_word VALUES (812,'sarumans',1);
INSERT INTO ezsearch_word VALUES (813,'really',2);
INSERT INTO ezsearch_word VALUES (814,'unless',1);
INSERT INTO ezsearch_word VALUES (815,'want',2);
INSERT INTO ezsearch_word VALUES (816,'thats',1);
INSERT INTO ezsearch_word VALUES (817,'50',1);
INSERT INTO ezsearch_word VALUES (818,'hours',2);
INSERT INTO ezsearch_word VALUES (819,'they',1);
INSERT INTO ezsearch_word VALUES (820,'can',2);
INSERT INTO ezsearch_word VALUES (821,'t',2);
INSERT INTO ezsearch_word VALUES (822,'everything',1);
INSERT INTO ezsearch_word VALUES (823,'woods',1);
INSERT INTO ezsearch_word VALUES (824,'portrayal',1);
INSERT INTO ezsearch_word VALUES (825,'frodo',1);
INSERT INTO ezsearch_word VALUES (826,'going',1);
INSERT INTO ezsearch_word VALUES (827,'happy',1);
INSERT INTO ezsearch_word VALUES (828,'go',1);
INSERT INTO ezsearch_word VALUES (829,'lucky',1);
INSERT INTO ezsearch_word VALUES (830,'adventurer',1);
INSERT INTO ezsearch_word VALUES (831,'tradgedy',1);
INSERT INTO ezsearch_word VALUES (832,'courting',1);
INSERT INTO ezsearch_word VALUES (833,'hero',1);
INSERT INTO ezsearch_word VALUES (834,'wonderful',1);
INSERT INTO ezsearch_word VALUES (835,'wizard',1);
INSERT INTO ezsearch_word VALUES (836,'gandalf',1);
INSERT INTO ezsearch_word VALUES (837,'viggo',1);
INSERT INTO ezsearch_word VALUES (838,'mortenson',1);
INSERT INTO ezsearch_word VALUES (839,'exeles',1);
INSERT INTO ezsearch_word VALUES (840,'aragorn',1);
INSERT INTO ezsearch_word VALUES (841,'ranger',1);
INSERT INTO ezsearch_word VALUES (842,'north',1);
INSERT INTO ezsearch_word VALUES (843,'think',1);
INSERT INTO ezsearch_word VALUES (844,'stand',1);
INSERT INTO ezsearch_word VALUES (845,'tall',1);
INSERT INTO ezsearch_word VALUES (846,'orlando',1);
INSERT INTO ezsearch_word VALUES (847,'bloom',2);
INSERT INTO ezsearch_word VALUES (848,'elf',1);
INSERT INTO ezsearch_word VALUES (849,'legolas',1);
INSERT INTO ezsearch_word VALUES (850,'prince',1);
INSERT INTO ezsearch_word VALUES (851,'mirkwood',1);
INSERT INTO ezsearch_word VALUES (852,'sean',1);
INSERT INTO ezsearch_word VALUES (853,'bean',2);
INSERT INTO ezsearch_word VALUES (854,'boromir',1);
INSERT INTO ezsearch_word VALUES (855,'gondor',1);
INSERT INTO ezsearch_word VALUES (856,'absolutly',1);
INSERT INTO ezsearch_word VALUES (857,'perfect',1);
INSERT INTO ezsearch_word VALUES (858,'finest',1);
INSERT INTO ezsearch_word VALUES (859,'choice',2);
INSERT INTO ezsearch_word VALUES (860,'possible',1);
INSERT INTO ezsearch_word VALUES (861,'dazzles',1);
INSERT INTO ezsearch_word VALUES (862,'strong',1);
INSERT INTO ezsearch_word VALUES (863,'military',1);
INSERT INTO ezsearch_word VALUES (864,'minded',1);
INSERT INTO ezsearch_word VALUES (865,'eventually',1);
INSERT INTO ezsearch_word VALUES (866,'consumed',1);
INSERT INTO ezsearch_word VALUES (867,'by',4);
INSERT INTO ezsearch_word VALUES (868,'power',1);
INSERT INTO ezsearch_word VALUES (873,'good',2);
INSERT INTO ezsearch_word VALUES (874,'if',1);
INSERT INTO ezsearch_word VALUES (875,'spare',1);
INSERT INTO ezsearch_word VALUES (876,'forum',3);
INSERT INTO ezsearch_word VALUES (924,'forums',1);
INSERT INTO ezsearch_word VALUES (881,'discuss',1);
INSERT INTO ezsearch_word VALUES (882,'openly',1);
INSERT INTO ezsearch_word VALUES (923,'discussion',1);
INSERT INTO ezsearch_word VALUES (885,'cool',1);
INSERT INTO ezsearch_word VALUES (886,'thumbs',1);
INSERT INTO ezsearch_word VALUES (887,'up',1);
INSERT INTO ezsearch_word VALUES (888,'last',1);
INSERT INTO ezsearch_word VALUES (889,'though',1);
INSERT INTO ezsearch_word VALUES (890,'was',1);
INSERT INTO ezsearch_word VALUES (891,'too',1);
INSERT INTO ezsearch_word VALUES (892,'short',1);
INSERT INTO ezsearch_word VALUES (893,'kewl',1);
INSERT INTO ezsearch_word VALUES (894,'download',1);
INSERT INTO ezsearch_word VALUES (895,'say',1);
INSERT INTO ezsearch_word VALUES (896,'night',4);
INSERT INTO ezsearch_word VALUES (897,'shot',1);
INSERT INTO ezsearch_word VALUES (898,'skien',1);
INSERT INTO ezsearch_word VALUES (899,'yellow',2);
INSERT INTO ezsearch_word VALUES (900,'tree',1);
INSERT INTO ezsearch_word VALUES (901,'what',1);
INSERT INTO ezsearch_word VALUES (902,'don',1);
INSERT INTO ezsearch_word VALUES (903,'know',1);
INSERT INTO ezsearch_word VALUES (904,'fjord',2);
INSERT INTO ezsearch_word VALUES (905,'do',4);
INSERT INTO ezsearch_word VALUES (906,'agree',2);
INSERT INTO ezsearch_word VALUES (907,'anonymous',1);
INSERT INTO ezsearch_word VALUES (908,'true',1);
INSERT INTO ezsearch_word VALUES (909,'kjell',1);
INSERT INTO ezsearch_word VALUES (910,'bekkelund',1);
INSERT INTO ezsearch_word VALUES (911,'v',1);
INSERT INTO ezsearch_word VALUES (912,'helper',1);
INSERT INTO ezsearch_word VALUES (913,'just',1);
INSERT INTO ezsearch_word VALUES (929,'place',1);
INSERT INTO ezsearch_word VALUES (928,'music',2);
INSERT INTO ezsearch_word VALUES (930,'threw',1);
INSERT INTO ezsearch_word VALUES (931,'stone',1);
INSERT INTO ezsearch_word VALUES (932,'marble',1);
INSERT INTO ezsearch_word VALUES (933,'kings',1);

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

INSERT INTO ezsection VALUES (1,'News','nor-NO');
INSERT INTO ezsection VALUES (2,'Sport','en-US');
INSERT INTO ezsection VALUES (3,'Shop','nor-No');
INSERT INTO ezsection VALUES (4,'Image gallery','en-US');
INSERT INTO ezsection VALUES (5,'Media','en-US');
INSERT INTO ezsection VALUES (6,'Forum','');
INSERT INTO ezsection VALUES (7,'Closed forum','');

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

INSERT INTO ezsession VALUES ('1822a8c48c3c26c2c12883826a72a9ab',1031488029,'eZExecutionStack|a:0:{}BrowseFromPage|s:18:\"/section/assign/3/\";BrowseActionName|s:13:\"AssignSection\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('ccb6df4f9af45fa78089d91652760b7d',1031764878,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|s:1:\"8\";BrowseFromPage|s:19:\"/content/edit/37/2/\";BrowseActionName|s:17:\"AddNodeAssignment\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('bdfcd792c4bb2977fee4ea43b2a9ef08',1031501648,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('35c24d12b46b469126bb887ce3854a7f',1031502522,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('dd7292781e3bd2c70de3e360f03b101a',1031559157,'');
INSERT INTO ezsession VALUES ('7e11293968adb13223cb223a72515a88',1031581817,'eZExecutionStack|a:0:{}BrowseFromPage|s:13:\"/task/edit/19\";BrowseActionName|s:18:\"SelectTaskReceiver\";BrowseReturnType|s:15:\"ContentObjectID\";CustomActionButton|N;eZUserLoggedInID|s:2:\"23\";');
INSERT INTO ezsession VALUES ('99f8d011fdc0bbb9e4a653e0df1e170a',1031559160,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('b12170da25d040a306423039163ea168',1031919880,'eZExecutionStack|a:0:{}BrowseFromPage|s:19:\"/content/edit/32/5/\";BrowseActionName|s:17:\"AddNodeAssignment\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|s:23:\"204_set_object_relation\";eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('19d79c940f2841f5add6f45a59de8eac',1031570780,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('bbb1be56a84d4847b4e48679954a2164',1031569105,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('93ef579fee86c07eeb7cc3d77d93fe08',1031572879,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('4dacda826a8ca23f5a623fe41c148c6f',1032002493,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}BrowseFromPage|s:14:\"/role/view/16/\";BrowseActionName|s:10:\"AssignRole\";BrowseReturnType|s:8:\"ObjectID\";CustomActionButton|N;eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('27874ed829d7f9e36b699c0fa64d35c4',1031757392,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|s:1:\"8\";BrowseFromPage|s:19:\"/content/edit/40/5/\";BrowseActionName|s:17:\"AddNodeAssignment\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('311e6e5faa61a394b77a4bba5cb60cac',1031758788,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('ec6cebd7b059900af815fecd746f47fb',1031757309,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('a14af21a55467e4367bee661bc7aa164',1031757333,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('3ead7835ca4ec589ce7fa9d769008c2d',1031758926,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('7aa2cf9537602fe690dde6d52d7e5bbf',1032271726,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";N;s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}BrowseFromPage|s:18:\"/section/assign/7/\";BrowseActionName|s:13:\"AssignSection\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('000feed9b01423c2ee39b858daff497d',1031817341,'');
INSERT INTO ezsession VALUES ('b16231dcaff15450315427ebe8c64af7',1031845170,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:2:\"21\";BrowseFromPage|s:13:\"/role/view/1/\";BrowseActionName|s:10:\"AssignRole\";BrowseReturnType|s:8:\"ObjectID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('683a9c169dd18ceabdb0cfb07ca133db',1031865745,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('8de13039926d3fdfa12f8e34e07127c7',1031856998,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('e9cf397063340435de3c1184634ebc07',1031823080,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('82bb5282b6c39b23a911299400f0a740',1031829642,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('83cd40eb717191fc91e7c526c5c6ee52',1031920952,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('2946440bc14ac8f4c534f3a3c7160a3f',1031906946,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('3d73aa81d373a55b72cdb68e866e9f98',1031904719,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('5fff29b8a61df59a8a4c3e8858349f2b',1031925667,'eZUserLoggedInID|s:2:\"21\";eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}BrowseFromPage|s:19:\"/content/edit/39/6/\";BrowseActionName|s:17:\"AddNodeAssignment\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('2fb3e11b2c3d617f8bcb1aea5e030720',1031925943,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('edbf0ffa83eec495352eb91bffbfc8da',1032005623,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('d8ffa7930a05ce762e4d45d43c528cfc',1031989814,'eZExecutionStack|a:2:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}i:1;a:3:{s:3:\"uri\";s:16:\"/workflow/edit/3\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"edit\";}}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('54246b22d54ac34c19f88422ee37bddf',1031992678,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('61e449399f28b0062c3031dae16b6f4e',1032005377,'eZExecutionStack|a:2:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}i:1;a:3:{s:3:\"uri\";s:16:\"/workflow/edit/3\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"edit\";}}eZUserLoggedInID|s:1:\"8\";BrowseFromPage|s:19:\"/content/edit/34/4/\";BrowseActionName|s:16:\"AddRelatedObject\";BrowseReturnType|s:8:\"ObjectID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('56ccfb56f0ac4f94b7e1a489ef7194a1',1031994807,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('3282b4f91ff54106e7e135bd45b8ae0b',1032006507,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('574090517f241357bf4cc65ab609e3a0',1032012566,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('43d0bdde48e183f2b480cbebad5f53d2',1032097762,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('d284f9c191eacc5b480a87b31f59dc61',1032187044,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('150e052e0b1a3f20da0603ad7620e310',1032099003,'eZExecutionStack|a:2:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}i:1;a:3:{s:3:\"uri\";s:16:\"/workflow/edit/1\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"edit\";}}eZUserLoggedInID|N;');
INSERT INTO ezsession VALUES ('6d728b7e84f0b5ad68bc775bb67a4a77',1032270432,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');

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

INSERT INTO eztask VALUES (3,1,1,1,'',1,0,0,0,0,0,0,1031303672,1031303672);
INSERT INTO eztask VALUES (15,1,2,1,'',21,23,0,0,0,0,0,1031309397,1031309404);
INSERT INTO eztask VALUES (5,1,2,1,'',1,8,0,0,0,0,0,1031307192,1031307250);
INSERT INTO eztask VALUES (6,2,2,1,'',1,8,0,0,0,0,12,1031307315,1031307350);
INSERT INTO eztask VALUES (14,1,2,1,'',21,8,0,0,0,0,0,1031309272,1031309300);
INSERT INTO eztask VALUES (16,1,2,1,'',23,21,0,0,0,0,0,1031309432,1031309439);
INSERT INTO eztask VALUES (17,2,3,1,'',23,21,0,0,1,1,14,1031309442,1031309461);
INSERT INTO eztask VALUES (18,1,2,1,'',21,23,1,17,0,0,0,1031310178,1031310195);
INSERT INTO eztask VALUES (19,1,2,1,'',23,8,0,0,0,0,0,1031320325,1031320335);
INSERT INTO eztask VALUES (20,2,2,1,'',8,8,0,0,1,1,31,1031657958,1031658005);

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

INSERT INTO eztask_message VALUES (1,16,24,1031214781,1);

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

INSERT INTO ezuser VALUES (1,'anonymous','anon@anon.com',5,'mofser');
INSERT INTO ezuser VALUES (8,'sp','sp@ez.no',3,'077194387c925d3dc9e6e6777ad685e4');
INSERT INTO ezuser VALUES (11,'floyd','floyd@floyd',5,'mofser');
INSERT INTO ezuser VALUES (21,'amos','jb@ez.no',3,'2846c21ac4c2ee44b4f92301edba77bb');
INSERT INTO ezuser VALUES (22,'','',0,'');
INSERT INTO ezuser VALUES (26,'','',0,'');
INSERT INTO ezuser VALUES (23,'test','test@test',3,'2e5a06b474d65f44370672594242fdab');
INSERT INTO ezuser VALUES (27,'','',0,'');
INSERT INTO ezuser VALUES (30,'test','test',3,'f57fdf7dd1c8b9ef62413ac11ce69da1');
INSERT INTO ezuser VALUES (42,'fe','fe@fe',3,'c809cf635b318c74c7968963a94f4e5a');
INSERT INTO ezuser VALUES (44,'ce','ce@ce',3,'3a5c14a99b4d9f59d79ecffbeea0ed2d');
INSERT INTO ezuser VALUES (52,'test','w@w',3,'f57fdf7dd1c8b9ef62413ac11ce69da1');
INSERT INTO ezuser VALUES (53,'t1','t1@tq',3,'c8ccf3b6f18afd08a4ed900a93b5bebd');
INSERT INTO ezuser VALUES (57,'','',0,'');
INSERT INTO ezuser VALUES (59,'','',0,'');
INSERT INTO ezuser VALUES (67,'qw','wy@ez.no',3,'f70773b0e3cc42736e8e69c638a5b116');

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

INSERT INTO ezuser_role VALUES (18,16,53);
INSERT INTO ezuser_role VALUES (13,2,8);
INSERT INTO ezuser_role VALUES (12,1,4);
INSERT INTO ezuser_role VALUES (15,3,41);
INSERT INTO ezuser_role VALUES (16,4,43);

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

INSERT INTO ezwishlist VALUES (1,8,1);
INSERT INTO ezwishlist VALUES (2,30,16);

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
INSERT INTO ezworkflow VALUES (5,0,1,'group_ezserial','check out',8,8,1031665923,1031665958);
INSERT INTO ezworkflow VALUES (3,1,1,'group_ezserial','Advanced approval',-1,8,1024392098,1031746148);
INSERT INTO ezworkflow VALUES (1,1,1,'group_ezserial','Publish',-1,30,1024392098,1031839803);

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
INSERT INTO ezworkflow_event VALUES (1,1,1,'event_ezpublish','Publish object',0,0,0,0,'','','','',1);
INSERT INTO ezworkflow_event VALUES (8,1,3,'event_ezpublish','Publish',1,0,0,0,'','','','',5);
INSERT INTO ezworkflow_event VALUES (7,1,3,'event_ezmessage','Send second message',0,0,0,0,'Some text','','','',4);
INSERT INTO ezworkflow_event VALUES (6,1,3,'event_ezpublish','Unpublish',0,0,0,0,'','','','',3);
INSERT INTO ezworkflow_event VALUES (5,1,3,'event_ezapprove','Approve by editor',0,0,0,0,'','','','',2);
INSERT INTO ezworkflow_event VALUES (4,1,3,'event_ezmessage','Send first message',0,0,0,0,'First test message from event','','','',1);

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
INSERT INTO ezworkflow_group VALUES (3,'New WorkflowGroup',8,8,1031665539,1031665542);
INSERT INTO ezworkflow_group VALUES (4,'My group',8,30,1031665981,1031835183);

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


