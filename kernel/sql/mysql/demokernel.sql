# MySQL dump 8.13
#
# Host: localhost    Database: bf
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

INSERT INTO ezbinaryfile VALUES (270,1,'LvLRKN.bin','myphp.php','text/plain');
INSERT INTO ezbinaryfile VALUES (270,2,'uLD7N3.bin','ellen1.JPG','image/jpeg');
INSERT INTO ezbinaryfile VALUES (270,3,'LvLRKN.bin','myphp.php','text/plain');

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
INSERT INTO ezcontentclass VALUES (8,0,'Appartment','','<name>',8,8,1031485062,1031485170);
INSERT INTO ezcontentclass VALUES (14,1,'New Class','','',8,8,1031597797,1031597798);
INSERT INTO ezcontentclass VALUES (13,1,'','','',0,8,0,1031597762);
INSERT INTO ezcontentclass VALUES (9,0,'Presentasjon','','<navn>',8,8,1031492055,1031502380);
INSERT INTO ezcontentclass VALUES (16,0,'Bil','','<navn>',8,8,1031742661,1031742925);
INSERT INTO ezcontentclass VALUES (10,0,'Fish','','<navn>',8,8,1031502832,1031503049);
INSERT INTO ezcontentclass VALUES (11,0,'MyClass','','MyClass',8,8,1031561052,1031661666);
INSERT INTO ezcontentclass VALUES (12,0,'Message','','<title>',8,8,1031567722,1031567757);
INSERT INTO ezcontentclass VALUES (17,0,'Review','','<title>',8,8,1031743932,1031744028);
INSERT INTO ezcontentclass VALUES (15,0,'DVD','','<name>',8,8,1031727318,1031727487);
INSERT INTO ezcontentclass VALUES (7,1,'Image','','<name>',8,30,1031484992,1031837426);
INSERT INTO ezcontentclass VALUES (15,1,'DVD','','<name>',8,30,1031727318,1031832615);
INSERT INTO ezcontentclass VALUES (10,1,'Fish','','<navn>',8,30,1031502832,1031833401);
INSERT INTO ezcontentclass VALUES (8,1,'Appartment','','<name>',8,30,1031485062,1031833408);
INSERT INTO ezcontentclass VALUES (2,1,'Article','article','<title>',-1,30,1024392098,1031838573);
INSERT INTO ezcontentclass VALUES (18,1,'New Class','','',30,30,1031837707,1031837709);
INSERT INTO ezcontentclass VALUES (1,1,'Folder','folder','<name>',-1,30,1024392098,1031838070);

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
INSERT INTO ezcontentclass_attribute VALUES (34,0,8,'description','Description','ezxmltext',1,0,6,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (33,0,8,'main_image','Main image','ezimage',1,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (32,0,8,'built','Built','ezstring',1,0,4,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (31,0,8,'price','Price','ezstring',1,0,3,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (30,0,8,'size','Size','ezstring',1,0,2,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (29,0,8,'name','Name','ezstring',1,0,1,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (35,0,8,'broker_information','Broker information','ezxmltext',1,0,7,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (56,0,11,'image','image','ezimage',1,1,7,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (55,0,11,'credit','credit','ezenum',1,1,6,1,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (51,0,11,'xml','xml','ezxmltext',1,1,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (54,0,11,'new_attribute6','colour','ezenum',1,1,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (43,0,11,'new_attribute1','title','ezstring',1,0,1,0,0,0,0,0,0,0,0,'Datatype test','','','');
INSERT INTO ezcontentclass_attribute VALUES (38,0,9,'region','region','ezenum',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (73,0,17,'new_attribute3','Rating','ezenum',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (72,0,17,'review','review','eztext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (71,0,17,'title','Title','ezstring',1,0,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (47,0,12,'title','title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'New topic','','','');
INSERT INTO ezcontentclass_attribute VALUES (39,0,9,'valg','Valg','ezoption',1,0,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (48,0,12,'message','Message','eztext',1,1,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (37,0,9,'fil','Fil','ezbinaryfile',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (36,0,9,'navn','Navn','ezstring',1,0,1,50,0,0,0,0,0,0,0,'Ny presentasjon','','','');
INSERT INTO ezcontentclass_attribute VALUES (45,0,11,'author','author','ezauthor',1,1,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (42,0,10,'beskrivelse','Beskrivelse','eztext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (41,0,10,'alder','Alder','ezinteger',1,0,2,1,100,0,3,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (40,0,10,'navn','Navn','ezstring',1,0,1,150,0,0,0,0,0,0,0,'En ny fisk...','','','');
INSERT INTO ezcontentclass_attribute VALUES (59,1,15,'description','Description','eztext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (50,0,11,'binary','binary','ezbinaryfile',1,1,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (57,1,15,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (63,0,15,'kategori','Kategori','ezstring',1,0,6,50,0,0,0,0,0,0,0,'Action','','','');
INSERT INTO ezcontentclass_attribute VALUES (62,0,15,'skuespillere','Skuespillere','ezstring',1,0,5,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (61,0,15,'utgivelsesr','Utgivelsesår','ezstring',1,0,4,15,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (60,0,15,'sone','Sone','ezstring',1,0,3,15,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (59,0,15,'description','Description','eztext',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (57,0,15,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (64,0,15,'cover','Cover','ezimage',1,0,7,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (65,0,15,'price','Price','ezprice',1,0,8,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (69,0,16,'bilde','Bilde','ezimage',1,0,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (70,0,16,'motor','Motor','ezstring',1,1,5,200,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (68,0,16,'beskrivelse','Beskrivelse','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (67,0,16,'modell','Modell','ezstring',1,1,2,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (66,0,16,'navn','Navn','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (27,1,7,'caption','Caption','ezstring',1,0,2,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (28,1,7,'image','Image','ezimage',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (26,1,7,'name','Name','ezstring',1,0,1,50,0,0,0,0,0,0,0,'Nytt bilde','','','');
INSERT INTO ezcontentclass_attribute VALUES (60,1,15,'sone','Sone','ezstring',1,0,3,15,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (61,1,15,'utgivelsesr','Utgivelsesår','ezstring',1,0,4,15,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (62,1,15,'skuespillere','Skuespillere','ezstring',1,0,5,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (63,1,15,'kategori','Kategori','ezstring',1,0,6,50,0,0,0,0,0,0,0,'Action','','','');
INSERT INTO ezcontentclass_attribute VALUES (64,1,15,'cover','Cover','ezimage',1,0,7,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (65,1,15,'price','Price','ezprice',1,0,8,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (40,1,10,'navn','Navn','ezstring',1,0,1,150,0,0,0,0,0,0,0,'En ny fisk...','','','');
INSERT INTO ezcontentclass_attribute VALUES (41,1,10,'alder','Alder','ezinteger',1,0,2,1,100,0,3,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (42,1,10,'beskrivelse','Beskrivelse','eztext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (29,1,8,'name','Name','ezstring',1,0,1,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (30,1,8,'size','Size','ezstring',1,0,2,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (31,1,8,'price','Price','ezstring',1,0,3,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (32,1,8,'built','Built','ezstring',1,0,4,50,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (33,1,8,'main_image','Main image','ezimage',1,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (34,1,8,'description','Description','ezxmltext',1,0,6,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (35,1,8,'broker_information','Broker information','ezxmltext',1,0,7,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (1,1,2,'title','Title','ezstring',1,0,1,255,0,0,0,0,0,0,0,'New article','','','');
INSERT INTO ezcontentclass_attribute VALUES (21,1,2,'subtitle','Subtitle','ezstring',1,0,2,100,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (22,1,2,'author','Author','ezstring',1,0,3,50,0,0,0,0,0,0,0,'Redaksjonen','','','');
INSERT INTO ezcontentclass_attribute VALUES (23,1,2,'intro','Intro','ezxmltext',1,0,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (24,1,2,'body','Body','ezxmltext',1,0,5,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (25,1,2,'thumbnail_image','Thumbnail image','ezimage',1,0,6,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (4,1,1,'name','Name','ezstring',1,0,1,255,0,0,0,0,0,0,0,'Folder','','','');
INSERT INTO ezcontentclass_attribute VALUES (5,1,1,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','');

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
INSERT INTO ezcontentclass_classgroup VALUES (14,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (4,1,2,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (15,1,3,'Products');
INSERT INTO ezcontentclass_classgroup VALUES (5,0,3,'Products');
INSERT INTO ezcontentclass_classgroup VALUES (16,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (9,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (10,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (11,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (12,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (7,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (15,0,3,'Products');
INSERT INTO ezcontentclass_classgroup VALUES (17,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (10,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (2,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (18,1,1,'Content');
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

INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,1,1031216928,1031216937);
INSERT INTO ezcontentclassgroup VALUES (2,'Users',1,1,1031216941,1031216949);
INSERT INTO ezcontentclassgroup VALUES (3,'Products',8,8,1031227910,1031227919);

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

INSERT INTO ezcontentobject VALUES (1,0,0,2,1,1,'Hovedkategori',6,0,1,0,0);
INSERT INTO ezcontentobject VALUES (4,0,0,5,0,3,'This is the master users',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (8,1,1,6,0,4,'Sergey Pushchin',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (10,1,1,11,0,3,'Other users',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (11,1,1,12,0,4,'Floyd Floyd',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (38,8,1,40,5,7,'Statue',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (36,8,1,37,5,7,'Nyttår',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (33,8,1,34,5,1,'Bilder',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (34,8,1,35,5,7,'Lilla blomster',4,0,1,1031743756,1031743756);
INSERT INTO ezcontentobject VALUES (30,8,1,31,0,4,'John Doe',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (21,1,1,22,0,4,'Amos',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (41,8,1,43,0,3,'Editors',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (23,21,1,24,0,4,'Test user',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (37,8,0,39,5,7,'Katte',3,0,1,0,0);
INSERT INTO ezcontentobject VALUES (32,8,1,33,4,1,'DVD',6,0,1,1031667228,1031667228);
INSERT INTO ezcontentobject VALUES (31,8,0,32,1,1,'Nyheter',5,0,1,0,0);
INSERT INTO ezcontentobject VALUES (35,8,1,36,5,7,'Speed',2,0,1,0,0);
INSERT INTO ezcontentobject VALUES (42,8,1,44,0,4,'First  Editor',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (43,8,1,45,0,3,'Advanced editors',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (44,8,1,46,0,4,'Chief Editor',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (89,8,1,92,4,15,'Others, The',1,0,1,1031727604,1031727604);
INSERT INTO ezcontentobject VALUES (59,8,1,62,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (52,8,1,55,0,4,'test test',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (53,8,1,56,0,4,'t1 t1',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (67,8,1,70,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (72,8,1,75,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (92,30,1,95,1,12,'Dette er en kommentar',2,0,1,1031729583,1031729583);
INSERT INTO ezcontentobject VALUES (90,30,1,93,1,12,'Dette er en liten kommentar på denne nyheten..',1,0,1,1031728583,1031728583);
INSERT INTO ezcontentobject VALUES (91,8,1,94,4,15,'Spider-Man (2002)',1,0,1,1031729019,1031729019);
INSERT INTO ezcontentobject VALUES (83,8,1,86,1,11,'New MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (88,8,1,91,1,2,'eZ publish nyhets demo',1,0,1,1031727042,1031727042);
INSERT INTO ezcontentobject VALUES (85,8,1,88,1,11,'MyClass',1,0,1,0,0);
INSERT INTO ezcontentobject VALUES (93,8,1,96,5,1,'Min kategori',1,0,1,1031736335,1031736335);
INSERT INTO ezcontentobject VALUES (94,8,0,97,1,16,'Alfa',2,0,1,1031743303,1031743303);
INSERT INTO ezcontentobject VALUES (95,30,1,98,1,12,'Test',1,0,1,1031743890,1031743890);
INSERT INTO ezcontentobject VALUES (96,30,1,99,1,17,'Dette var bra...',1,0,1,1031744146,1031744146);

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
INSERT INTO ezcontentobject_attribute VALUES (81,'en_GB',1,36,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (80,'en_GB',1,36,27,'Nyttår',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (79,'en_GB',1,36,26,'Nyttår',0,0);
INSERT INTO ezcontentobject_attribute VALUES (22,'en_GB',1,11,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (21,'en_GB',1,11,9,'Floyd',0,0);
INSERT INTO ezcontentobject_attribute VALUES (20,'en_GB',1,11,8,'Floyd',0,0);
INSERT INTO ezcontentobject_attribute VALUES (19,'en_GB',1,10,7,'Other users',0,0);
INSERT INTO ezcontentobject_attribute VALUES (18,'en_GB',1,10,6,'Other users',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',3,31,5,'Hovedkategori for nyheter ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',3,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (75,'en_GB',1,34,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (73,'en_GB',1,34,26,'Lilla blomster',0,0);
INSERT INTO ezcontentobject_attribute VALUES (74,'en_GB',1,34,27,'Ute i naturen...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',2,1,4,'My folder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',2,1,5,'This folder contains some information about...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (72,'en_GB',1,33,5,'Her ligger bildene lagret',0,0);
INSERT INTO ezcontentobject_attribute VALUES (71,'en_GB',1,33,4,'Bilder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',6,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',6,1,4,'Forsiden',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',5,1,5,'Topp node i content treet',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',5,1,4,'Hovedkategori',0,0);
INSERT INTO ezcontentobject_attribute VALUES (74,'en_GB',2,34,27,'Ute i naturen...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (73,'en_GB',2,34,26,'Lilla blomster',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (78,'en_GB',1,35,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (76,'en_GB',1,35,26,'Speed',0,0);
INSERT INTO ezcontentobject_attribute VALUES (77,'en_GB',1,35,27,'Speed',0,0);
INSERT INTO ezcontentobject_attribute VALUES (65,'en_GB',1,30,9,'Doe',0,0);
INSERT INTO ezcontentobject_attribute VALUES (66,'en_GB',1,30,12,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',1,32,5,'Hovedkategori for eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',1,32,4,'Eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (64,'en_GB',1,30,8,'John',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',1,31,5,'Hovedkategori for nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',1,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'en_GB',3,1,5,'Dette er hovednoden i content treet.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (1,'en_GB',3,1,4,'Hovedkategori',0,0);
INSERT INTO ezcontentobject_attribute VALUES (75,'en_GB',2,34,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (76,'en_GB',2,35,26,'Speed',0,0);
INSERT INTO ezcontentobject_attribute VALUES (77,'en_GB',2,35,27,'Speed',0,0);
INSERT INTO ezcontentobject_attribute VALUES (78,'en_GB',2,35,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (79,'en_GB',2,36,26,'Nyttår',0,0);
INSERT INTO ezcontentobject_attribute VALUES (80,'en_GB',2,36,27,'Nyttår',0,0);
INSERT INTO ezcontentobject_attribute VALUES (81,'en_GB',2,36,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (82,'en_GB',1,37,26,'Katte',0,0);
INSERT INTO ezcontentobject_attribute VALUES (83,'en_GB',1,37,27,'Pus',0,0);
INSERT INTO ezcontentobject_attribute VALUES (84,'en_GB',1,37,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (82,'en_GB',2,37,26,'Katte',0,0);
INSERT INTO ezcontentobject_attribute VALUES (83,'en_GB',2,37,27,'Pus',0,0);
INSERT INTO ezcontentobject_attribute VALUES (84,'en_GB',2,37,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (85,'en_GB',1,38,26,'Statue',0,0);
INSERT INTO ezcontentobject_attribute VALUES (86,'en_GB',1,38,27,'Statue',0,0);
INSERT INTO ezcontentobject_attribute VALUES (87,'en_GB',1,38,28,'',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (333,'no_NO',2,94,68,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>test\r\n23ertert\r\ndfgh\r\ndf\r\ngh</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (329,'en_GB',2,94,69,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (330,'en_GB',2,94,70,'2.0 liter twin spark.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (331,'no_NO',2,94,66,'norsk tekst',0,0);
INSERT INTO ezcontentobject_attribute VALUES (332,'no_NO',2,94,67,'teset',0,0);
INSERT INTO ezcontentobject_attribute VALUES (326,'en_GB',2,94,66,'Alfa',0,0);
INSERT INTO ezcontentobject_attribute VALUES (327,'en_GB',2,94,67,'Spider',0,0);
INSERT INTO ezcontentobject_attribute VALUES (328,'en_GB',2,94,68,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>test</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (330,'en_GB',1,94,70,'2.0 liter twin spark.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (329,'en_GB',1,94,69,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (323,'en_GB',2,92,48,'Dette er kommentaren.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (324,'en_GB',1,93,4,'Min kategori',0,0);
INSERT INTO ezcontentobject_attribute VALUES (325,'en_GB',1,93,5,'Test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (326,'en_GB',1,94,66,'Alfa',0,0);
INSERT INTO ezcontentobject_attribute VALUES (327,'en_GB',1,94,67,'Spider',0,0);
INSERT INTO ezcontentobject_attribute VALUES (328,'en_GB',1,94,68,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>test</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (322,'en_GB',1,92,47,'Dette er en kommentar',0,0);
INSERT INTO ezcontentobject_attribute VALUES (323,'en_GB',1,92,48,'Dette er kommentaren.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (322,'en_GB',2,92,47,'Dette er en kommentar',0,0);
INSERT INTO ezcontentobject_attribute VALUES (317,'en_GB',1,91,61,'November 2002 ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (318,'en_GB',1,91,62,'Tobey Maguire, Willem Dafoe, Kirsten Dunst',0,0);
INSERT INTO ezcontentobject_attribute VALUES (319,'en_GB',1,91,63,'Action',0,0);
INSERT INTO ezcontentobject_attribute VALUES (320,'en_GB',1,91,64,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (321,'en_GB',1,91,65,'',0,239);
INSERT INTO ezcontentobject_attribute VALUES (316,'en_GB',1,91,60,'Sone 2 PAL',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (85,'en_GB',2,38,26,'Statue',0,0);
INSERT INTO ezcontentobject_attribute VALUES (86,'en_GB',2,38,27,'Statue',0,0);
INSERT INTO ezcontentobject_attribute VALUES (87,'en_GB',2,38,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',5,32,4,'Eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',5,32,5,'Hovedkategori for eiendom',0,0);
INSERT INTO ezcontentobject_attribute VALUES (299,'en_GB',2,88,21,'En liten test på nyhetspublisering',0,0);
INSERT INTO ezcontentobject_attribute VALUES (300,'en_GB',2,88,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (298,'en_GB',2,88,1,'eZ publish nyhets demo',0,0);
INSERT INTO ezcontentobject_attribute VALUES (302,'en_GB',2,88,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Nonummy nibh </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (315,'en_GB',1,91,59,'Peter Parker lever en anonym tilværelse hos sin kjære tante May og onkel Ben etter at han ble foreldreløs som liten. Han er en helt normal student, litt nerdete og med liten lykke hos jentene. Spesielt jenta han alltid har vært forelsket i, nabopiken Mary Jane Watson. Han bruker mye av fritiden sammen med bestevennen Harry Osborn, sønn av den styrtrike industrimagnaten Norman Osborn. ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (314,'en_GB',1,91,57,'Spider-Man (2002)',0,0);
INSERT INTO ezcontentobject_attribute VALUES (312,'en_GB',1,90,47,'Dette er en liten kommentar på denne nyheten..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (313,'en_GB',1,90,48,'Dette mener jeg.. bla bla bla..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (310,'en_GB',1,89,64,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (311,'en_GB',1,89,65,'',0,199);
INSERT INTO ezcontentobject_attribute VALUES (305,'en_GB',1,89,59,'ersey, den engelske kanal, 1945. \nDen 2. verdenskrig er over, men Grace sin ektemann har enda ikke returnert fra fronten. Alene i et avsidesliggende viktoriansk herskapshus prøver hun å oppdra datteren og sønnen etter strenge religiøse prinsipper på grensen til galskap. \n\nBarna lider av en merkelig sykdom; de tåler ikke direkte dagslys og når Grace ansetter tre tjenestefolk får de lære en vital regel: Huset må alltid være mørklagt og ingen dør må åpnes før den forrige døren er lukket. Mystiske lyder begynner uten forklaring å fylle huset. Grace anklager i begynnelsen de nye tjenestefolkene, men begynner snart å lure på det kan være andre i huset... ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (306,'en_GB',1,89,60,'Sone 2 ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (307,'en_GB',1,89,61,'2001 ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (308,'en_GB',1,89,62,'Nicole Kidman ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (309,'en_GB',1,89,63,'Action',0,0);
INSERT INTO ezcontentobject_attribute VALUES (304,'en_GB',1,89,57,'Others, The',0,0);
INSERT INTO ezcontentobject_attribute VALUES (82,'en_GB',3,37,26,'Katte',0,0);
INSERT INTO ezcontentobject_attribute VALUES (83,'en_GB',3,37,27,'Pus',0,0);
INSERT INTO ezcontentobject_attribute VALUES (84,'en_GB',3,37,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (246,'no_NO',3,37,26,'Kattepus',0,0);
INSERT INTO ezcontentobject_attribute VALUES (247,'no_NO',3,37,27,'pus pus pus',0,0);
INSERT INTO ezcontentobject_attribute VALUES (248,'no_NO',3,37,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (261,'en_GB',1,83,43,'Datatype test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (262,'en_GB',1,83,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"sp\"  email=\"sp@ez.no\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (263,'en_GB',1,83,50,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (264,'en_GB',1,83,51,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (265,'en_GB',1,83,52,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (266,'en_GB',1,83,54,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (267,'en_GB',1,83,55,'',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (301,'en_GB',2,88,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Dette er en <bold>liten</bold> test på nyhetspublisering i eZ publish 3.0. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (275,'en_GB',2,85,43,'Datatype test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (276,'en_GB',2,85,45,'<?xml version=\"1.0\"?>\n<ezauthor>  <authors>    <author id=\"0\"  name=\"sp\"  email=\"sp@ez.no\"  /></authors>\n</ezauthor>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (277,'en_GB',2,85,50,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (278,'en_GB',2,85,51,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph></paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (279,'en_GB',2,85,52,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (280,'en_GB',2,85,54,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (281,'en_GB',2,85,55,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (282,'en_GB',2,85,56,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (73,'en_GB',3,34,26,'Lilla blomster',0,0);
INSERT INTO ezcontentobject_attribute VALUES (74,'en_GB',3,34,27,'Ute i naturen...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (75,'en_GB',3,34,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (303,'en_GB',1,88,25,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (300,'en_GB',1,88,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (301,'en_GB',1,88,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Dette er en <bold>liten</bold> test på nyhetspublisering i eZ publish 3.0. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (302,'en_GB',1,88,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Nonummy nibh </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (299,'en_GB',1,88,21,'En liten test på nyhetspublisering',0,0);
INSERT INTO ezcontentobject_attribute VALUES (298,'en_GB',1,88,1,'eZ publish nyhets demo',0,0);
INSERT INTO ezcontentobject_attribute VALUES (70,'en_GB',6,32,5,'Hovedkategori for DVD filmer',0,0);
INSERT INTO ezcontentobject_attribute VALUES (69,'en_GB',6,32,4,'DVD',0,0);
INSERT INTO ezcontentobject_attribute VALUES (303,'en_GB',2,88,25,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (334,'no_NO',2,94,69,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (335,'no_NO',2,94,70,'3.0 liter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (73,'en_GB',4,34,26,'Lilla blomster',0,0);
INSERT INTO ezcontentobject_attribute VALUES (74,'en_GB',4,34,27,'Ute i naturen...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (75,'en_GB',4,34,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (73,'en_GB',5,34,26,'Lilla blomster',0,0);
INSERT INTO ezcontentobject_attribute VALUES (74,'en_GB',5,34,27,'Ute i naturen...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (75,'en_GB',5,34,28,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (336,'en_GB',1,95,47,'Test',0,0);
INSERT INTO ezcontentobject_attribute VALUES (337,'en_GB',1,95,48,'Dette er det jeg mener....',0,0);
INSERT INTO ezcontentobject_attribute VALUES (338,'en_GB',1,96,71,'Dette var bra...',0,0);
INSERT INTO ezcontentobject_attribute VALUES (339,'en_GB',1,96,72,'bla blalb alb la',0,0);
INSERT INTO ezcontentobject_attribute VALUES (340,'en_GB',1,96,73,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (67,'en_GB',6,31,4,'Nyheter',0,0);
INSERT INTO ezcontentobject_attribute VALUES (68,'en_GB',6,31,5,'Hovedkategori for nyheter ',0,0);
INSERT INTO ezcontentobject_attribute VALUES (298,'en_GB',3,88,1,'eZ publish nyhets demo',0,0);
INSERT INTO ezcontentobject_attribute VALUES (299,'en_GB',3,88,21,'En liten test på nyhetspublisering',0,0);
INSERT INTO ezcontentobject_attribute VALUES (300,'en_GB',3,88,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (301,'en_GB',3,88,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Dette er en <bold>liten</bold> test på nyhetspublisering i eZ publish 3.0. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (302,'en_GB',3,88,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Nonummy nibh </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (303,'en_GB',3,88,25,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (298,'en_GB',4,88,1,'eZ publish nyhets demo',0,0);
INSERT INTO ezcontentobject_attribute VALUES (299,'en_GB',4,88,21,'En liten test på nyhetspublisering',0,0);
INSERT INTO ezcontentobject_attribute VALUES (300,'en_GB',4,88,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (301,'en_GB',4,88,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Dette er en <bold>liten</bold> test på nyhetspublisering i eZ publish 3.0. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (302,'en_GB',4,88,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Lorem ipsum </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph><paragraph><header>Nonummy nibh </header>\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (303,'en_GB',4,88,25,'',0,0);

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
INSERT INTO ezcontentobject_tree VALUES (2,1,1,1,NULL,NULL,1,'/1/2/',NULL,2,7,NULL);
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,NULL,NULL,1,'/1/5/',NULL,8,15,NULL);
INSERT INTO ezcontentobject_tree VALUES (9,5,8,NULL,NULL,NULL,2,'/1/5/9/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (11,5,10,NULL,NULL,NULL,2,'/1/5/11/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (12,11,11,NULL,NULL,NULL,3,'/1/5/11/12/',NULL,16,17,NULL);
INSERT INTO ezcontentobject_tree VALUES (34,2,33,0,0,1193589247,2,'/1/2/34/','',7,8,'hovedkategori/bilder');
INSERT INTO ezcontentobject_tree VALUES (35,34,34,0,0,-1488979615,3,'/1/2/34/35/','',8,9,'hovedkategori/bilder/lilla_blomster');
INSERT INTO ezcontentobject_tree VALUES (31,5,30,NULL,NULL,NULL,2,'/1/5/31/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (22,5,21,NULL,NULL,NULL,2,'/1/5/22/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (24,5,23,NULL,NULL,NULL,2,'/1/5/24/',NULL,15,16,NULL);
INSERT INTO ezcontentobject_tree VALUES (32,2,31,0,0,-798896078,2,'/1/2/32/','',7,8,'hovedkategori/nyheter');
INSERT INTO ezcontentobject_tree VALUES (33,2,32,0,0,-641550520,2,'/1/2/33/','',7,8,'hovedkategori/dvd');
INSERT INTO ezcontentobject_tree VALUES (36,34,35,0,0,766147886,3,'/1/2/34/36/','',8,9,'hovedkategori/bilder/speed');
INSERT INTO ezcontentobject_tree VALUES (37,34,36,0,0,1916485265,3,'/1/2/34/37/','',8,9,'hovedkategori/bilder/nyttr');
INSERT INTO ezcontentobject_tree VALUES (40,34,38,0,0,134088232,3,'/1/2/34/40/','',8,9,'hovedkategori/bilder/statue');
INSERT INTO ezcontentobject_tree VALUES (39,34,37,0,0,-1398453418,3,'/1/2/34/39/','',8,9,'hovedkategori/bilder/katte');
INSERT INTO ezcontentobject_tree VALUES (43,5,41,0,0,961378214,2,'/1/5/43/','',15,16,'this_is_the_master_users/editors');
INSERT INTO ezcontentobject_tree VALUES (44,43,42,0,0,-1498193455,3,'/1/5/43/44/','',16,17,'this_is_the_master_users/editors/first_editor');
INSERT INTO ezcontentobject_tree VALUES (45,5,43,0,0,1649068118,2,'/1/5/45/','',15,16,'this_is_the_master_users/advanced_editors');
INSERT INTO ezcontentobject_tree VALUES (46,45,44,0,0,-1245754999,3,'/1/5/45/46/','',16,17,'this_is_the_master_users/advanced_editors/chief_editor');
INSERT INTO ezcontentobject_tree VALUES (92,33,89,0,0,250637395,3,'/1/2/33/92/','',0,0,'hovedkategori/dvd/others_the');
INSERT INTO ezcontentobject_tree VALUES (55,5,52,0,0,214034150,2,'/1/5/55/','',15,16,'this_is_the_master_users/test_test');
INSERT INTO ezcontentobject_tree VALUES (56,5,53,0,0,-1469971828,2,'/1/5/56/','',15,16,'this_is_the_master_users/t1_t1');
INSERT INTO ezcontentobject_tree VALUES (97,32,94,0,0,2070824562,3,'/1/2/32/97/','',0,0,'hovedkategori/nyheter/alfa');
INSERT INTO ezcontentobject_tree VALUES (98,91,95,0,0,797808870,4,'/1/2/32/91/98/','',0,0,'hovedkategori/nyheter/ez_publish_nyhets_demo/test');
INSERT INTO ezcontentobject_tree VALUES (99,91,96,0,0,2067147203,4,'/1/2/32/91/99/','',0,0,'hovedkategori/nyheter/ez_publish_nyhets_demo/dette_var_bra');
INSERT INTO ezcontentobject_tree VALUES (96,34,93,0,0,1466787163,3,'/1/2/34/96/','',0,0,'hovedkategori/bilder/min_kategori');
INSERT INTO ezcontentobject_tree VALUES (95,91,92,0,0,-1142542837,4,'/1/2/32/91/95/','',0,0,'hovedkategori/nyheter/ez_publish_nyhets_demo/dette_er_en_kommentar');
INSERT INTO ezcontentobject_tree VALUES (93,91,90,0,0,-1197366779,4,'/1/2/32/91/93/','',0,0,'hovedkategori/nyheter/ez_publish_nyhets_demo/dette_er_en_liten_kommentar_p_denne_nyheten');
INSERT INTO ezcontentobject_tree VALUES (94,33,91,0,0,-386445922,3,'/1/2/33/94/','',0,0,'hovedkategori/dvd/spiderman_2002');
INSERT INTO ezcontentobject_tree VALUES (91,32,88,0,0,-1274026707,3,'/1/2/32/91/','',0,0,'hovedkategori/nyheter/ez_publish_nyhets_demo');

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
INSERT INTO ezcontentobject_version VALUES (71,35,8,2,1031486978,1031486982,0,0,0);
INSERT INTO ezcontentobject_version VALUES (56,32,8,2,1031484812,1031484812,0,0,0);
INSERT INTO ezcontentobject_version VALUES (70,34,8,2,1031486963,1031486968,0,0,0);
INSERT INTO ezcontentobject_version VALUES (28,21,1,1,1031307079,1031307079,0,0,0);
INSERT INTO ezcontentobject_version VALUES (69,33,8,2,1031486893,1031486897,0,0,0);
INSERT INTO ezcontentobject_version VALUES (30,23,21,1,1031309318,1031309318,0,0,0);
INSERT INTO ezcontentobject_version VALUES (68,32,8,4,1031486821,1031486827,0,0,0);
INSERT INTO ezcontentobject_version VALUES (67,31,8,4,1031486809,1031486814,0,0,0);
INSERT INTO ezcontentobject_version VALUES (58,32,8,3,1031484867,1031484873,0,0,0);
INSERT INTO ezcontentobject_version VALUES (66,36,8,1,1031486742,1031486763,0,0,0);
INSERT INTO ezcontentobject_version VALUES (65,35,8,1,1031486708,1031486731,0,0,0);
INSERT INTO ezcontentobject_version VALUES (55,32,8,1,1031484747,1031484770,0,0,0);
INSERT INTO ezcontentobject_version VALUES (52,30,8,1,1031482159,1031482181,0,0,0);
INSERT INTO ezcontentobject_version VALUES (63,34,8,1,1031486315,1031486378,0,0,0);
INSERT INTO ezcontentobject_version VALUES (54,31,8,1,1031484715,1031484728,0,0,0);
INSERT INTO ezcontentobject_version VALUES (53,1,8,3,1031484658,1031484701,1,1,0);
INSERT INTO ezcontentobject_version VALUES (72,36,8,2,1031486992,1031486997,0,0,0);
INSERT INTO ezcontentobject_version VALUES (73,37,8,1,1031487482,1031487498,0,0,0);
INSERT INTO ezcontentobject_version VALUES (74,37,8,2,1031487518,1031487552,0,0,0);
INSERT INTO ezcontentobject_version VALUES (75,38,8,1,1031487572,1031487593,0,0,0);
INSERT INTO ezcontentobject_version VALUES (78,41,8,1,1031488556,1031488576,0,0,0);
INSERT INTO ezcontentobject_version VALUES (79,42,8,1,1031488585,1031488617,0,0,0);
INSERT INTO ezcontentobject_version VALUES (80,43,8,1,1031488920,1031488980,0,0,0);
INSERT INTO ezcontentobject_version VALUES (81,44,8,1,1031488986,1031489022,0,0,0);
INSERT INTO ezcontentobject_version VALUES (168,92,30,1,1031729514,1031729563,0,0,0);
INSERT INTO ezcontentobject_version VALUES (167,91,8,1,1031728944,1031729019,0,0,0);
INSERT INTO ezcontentobject_version VALUES (166,90,30,1,1031728562,1031728583,0,0,0);
INSERT INTO ezcontentobject_version VALUES (105,59,8,1,1031567534,1031569130,0,0,0);
INSERT INTO ezcontentobject_version VALUES (102,1,8,7,1031504607,1031504607,1,1,0);
INSERT INTO ezcontentobject_version VALUES (96,52,8,1,1031501235,1031501252,0,0,0);
INSERT INTO ezcontentobject_version VALUES (97,53,8,1,1031501320,1031501337,0,0,0);
INSERT INTO ezcontentobject_version VALUES (98,38,8,2,1031502085,1031502085,0,0,0);
INSERT INTO ezcontentobject_version VALUES (113,67,8,1,1031569597,1031584186,0,0,0);
INSERT INTO ezcontentobject_version VALUES (175,95,30,1,1031743862,1031743890,0,0,0);
INSERT INTO ezcontentobject_version VALUES (119,72,8,1,1031584755,1031585662,0,0,0);
INSERT INTO ezcontentobject_version VALUES (122,32,8,5,1031586337,1031586337,0,0,0);
INSERT INTO ezcontentobject_version VALUES (173,34,8,4,1031743705,1031743756,0,0,0);
INSERT INTO ezcontentobject_version VALUES (174,34,8,5,1031743831,1031743831,0,0,0);
INSERT INTO ezcontentobject_version VALUES (179,88,30,4,1031838999,1031838999,0,0,0);
INSERT INTO ezcontentobject_version VALUES (178,88,30,3,1031838515,1031838515,0,0,0);
INSERT INTO ezcontentobject_version VALUES (177,31,30,6,1031835342,1031835342,0,0,0);
INSERT INTO ezcontentobject_version VALUES (165,89,8,1,1031727500,1031727604,0,0,0);
INSERT INTO ezcontentobject_version VALUES (141,37,8,3,1031641970,1031642007,0,0,0);
INSERT INTO ezcontentobject_version VALUES (172,94,8,2,1031743244,1031743303,0,0,0);
INSERT INTO ezcontentobject_version VALUES (170,93,8,1,1031736312,1031736335,0,0,0);
INSERT INTO ezcontentobject_version VALUES (144,83,8,1,1031645657,1031645657,0,0,0);
INSERT INTO ezcontentobject_version VALUES (169,92,30,2,1031729574,1031729583,0,0,0);
INSERT INTO ezcontentobject_version VALUES (171,94,8,1,1031743091,1031743225,0,0,0);
INSERT INTO ezcontentobject_version VALUES (164,88,8,2,1031727067,1031727067,0,0,0);
INSERT INTO ezcontentobject_version VALUES (163,88,8,1,1031726843,1031727042,0,0,0);
INSERT INTO ezcontentobject_version VALUES (154,85,8,1,1031654786,1031659883,0,0,0);
INSERT INTO ezcontentobject_version VALUES (155,31,8,5,1031655110,1031655163,0,0,0);
INSERT INTO ezcontentobject_version VALUES (176,96,30,1,1031744122,1031744146,0,0,0);
INSERT INTO ezcontentobject_version VALUES (157,85,8,2,1031659895,1031660679,0,0,0);
INSERT INTO ezcontentobject_version VALUES (158,34,8,3,1031660003,1031660003,0,0,0);
INSERT INTO ezcontentobject_version VALUES (162,32,8,6,1031667215,1031667228,0,0,0);

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
INSERT INTO ezenumobjectvalue VALUES (340,1,11,'Bad','2');

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
INSERT INTO ezenumvalue VALUES (10,73,1,'Good','1',1);
INSERT INTO ezenumvalue VALUES (11,73,1,'Bad','2',2);
INSERT INTO ezenumvalue VALUES (10,73,0,'Good','1',1);
INSERT INTO ezenumvalue VALUES (11,73,0,'Bad','2',2);

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

INSERT INTO ezimage VALUES (75,1,'34adMJ.jpg','dscn3253.jpg','image/jpeg');
INSERT INTO ezimage VALUES (78,1,'YZCbpG.jpg','dscn1333.jpg','image/jpeg');
INSERT INTO ezimage VALUES (81,1,'7w7L4U.jpg','dscn1356.jpg','image/jpeg');
INSERT INTO ezimage VALUES (75,2,'34adMJ.jpg','dscn3253.jpg','image/jpeg');
INSERT INTO ezimage VALUES (78,2,'YZCbpG.jpg','dscn1333.jpg','image/jpeg');
INSERT INTO ezimage VALUES (81,2,'7w7L4U.jpg','dscn1356.jpg','image/jpeg');
INSERT INTO ezimage VALUES (84,1,'G8cMbY.jpg','dscn1760.jpg','image/jpeg');
INSERT INTO ezimage VALUES (84,2,'G8cMbY.jpg','dscn1760.jpg','image/jpeg');
INSERT INTO ezimage VALUES (87,1,'U1Gxxl.jpg','dscn1534.jpg','image/jpeg');
INSERT INTO ezimage VALUES (75,4,'34adMJ.jpg','dscn3253.jpg','image/jpeg');
INSERT INTO ezimage VALUES (320,1,'RCiLDb.jpg','38250-100x150.jpg','image/jpeg');
INSERT INTO ezimage VALUES (87,2,'U1Gxxl.jpg','dscn1534.jpg','image/jpeg');
INSERT INTO ezimage VALUES (75,5,'34adMJ.jpg','dscn3253.jpg','image/jpeg');
INSERT INTO ezimage VALUES (303,2,'Df3BHl.jpg','dscn1722.jpg','image/jpeg');
INSERT INTO ezimage VALUES (310,1,'XAID8g.jpg','38858-100x150.jpg','image/jpeg');
INSERT INTO ezimage VALUES (84,3,'G8cMbY.jpg','dscn1760.jpg','image/jpeg');
INSERT INTO ezimage VALUES (282,1,'RVpV7a.jpg','ellen1.JPG','image/jpeg');
INSERT INTO ezimage VALUES (303,1,'Df3BHl.jpg','dscn1722.jpg','image/jpeg');
INSERT INTO ezimage VALUES (282,2,'RVpV7a.jpg','ellen1.JPG','image/jpeg');
INSERT INTO ezimage VALUES (75,3,'34adMJ.jpg','dscn3253.jpg','image/jpeg');
INSERT INTO ezimage VALUES (297,1,'66Khra.jpg','DSC00023.JPG','image/jpeg');
INSERT INTO ezimage VALUES (303,3,'Df3BHl.jpg','dscn1722.jpg','image/jpeg');
INSERT INTO ezimage VALUES (303,4,'Df3BHl.jpg','dscn1722.jpg','image/jpeg');

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

INSERT INTO ezimagevariation VALUES (78,1,'YZCbpG_100x100_78.jpg','Y/Z/C/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (75,1,'34adMJ_600x600_75.jpg','3/4/a/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (75,1,'34adMJ_100x100_75.jpg','3/4/a/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (78,1,'YZCbpG_600x600_78.jpg','Y/Z/C/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (81,1,'7w7L4U_100x100_81.jpg','7/w/7/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (81,1,'7w7L4U_600x600_81.jpg','7/w/7/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (75,2,'34adMJ_100x100_75.jpg','3/4/a/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (75,2,'34adMJ_600x600_75.jpg','3/4/a/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (78,2,'YZCbpG_100x100_78.jpg','Y/Z/C/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (78,2,'YZCbpG_600x600_78.jpg','Y/Z/C/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (81,2,'7w7L4U_100x100_81.jpg','7/w/7/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (81,2,'7w7L4U_600x600_81.jpg','7/w/7/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (84,1,'G8cMbY_600x600_84.jpg','G/8/c/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (84,2,'G8cMbY_100x100_84.jpg','G/8/c/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (84,2,'G8cMbY_600x600_84.jpg','G/8/c/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (87,1,'U1Gxxl_100x100_87.jpg','U/1/G/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (87,1,'U1Gxxl_600x600_87.jpg','U/1/G/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (75,5,'34adMJ_100x100_75.jpg','3/4/a/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (75,4,'34adMJ_600x600_75.jpg','3/4/a/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (75,4,'34adMJ_100x100_75.jpg','3/4/a/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (320,1,'RCiLDb_600x600_320.jpg','R/C/i/',600,600,100,144);
INSERT INTO ezimagevariation VALUES (320,1,'RCiLDb_100x100_320.jpg','R/C/i/',100,100,69,100);
INSERT INTO ezimagevariation VALUES (87,2,'U1Gxxl_100x100_87.jpg','U/1/G/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (303,2,'Df3BHl_100x100_303.jpg','D/f/3/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (310,1,'XAID8g_600x600_310.jpg','X/A/I/',600,600,100,141);
INSERT INTO ezimagevariation VALUES (310,1,'XAID8g_100x100_310.jpg','X/A/I/',100,100,70,100);
INSERT INTO ezimagevariation VALUES (84,3,'G8cMbY_100x100_84.jpg','G/8/c/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (84,3,'G8cMbY_600x600_84.jpg','G/8/c/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (282,1,'RVpV7a_100x100_282.jpg','R/V/p/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (303,1,'Df3BHl_100x100_303.jpg','D/f/3/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (303,1,'Df3BHl_600x600_303.jpg','D/f/3/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (282,1,'RVpV7a_600x600_282.jpg','R/V/p/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (282,2,'RVpV7a_100x100_282.jpg','R/V/p/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (75,3,'34adMJ_100x100_75.jpg','3/4/a/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (297,1,'66Khra_100x100_297.jpg','6/6/K/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (303,3,'Df3BHl_100x100_303.jpg','D/f/3/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (303,4,'Df3BHl_100x100_303.jpg','D/f/3/',100,100,100,75);

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
INSERT INTO ezpolicy VALUES (203,46,'*','*','*');
INSERT INTO ezpolicy VALUES (216,1,'*','*','*');
INSERT INTO ezpolicy VALUES (215,1,'*','shop','*');

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
INSERT INTO ezpolicy_limitation_value VALUES (138,67,1);
INSERT INTO ezpolicy_limitation_value VALUES (137,66,5);
INSERT INTO ezpolicy_limitation_value VALUES (136,66,4);
INSERT INTO ezpolicy_limitation_value VALUES (125,62,1);
INSERT INTO ezpolicy_limitation_value VALUES (124,61,5);
INSERT INTO ezpolicy_limitation_value VALUES (123,61,4);
INSERT INTO ezpolicy_limitation_value VALUES (122,60,9);

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
INSERT INTO ezproductcollection_item VALUES (4,1,71,1,0,435);
INSERT INTO ezproductcollection_item VALUES (7,4,71,1,0,435);
INSERT INTO ezproductcollection_item VALUES (9,6,89,1,0,398);
INSERT INTO ezproductcollection_item VALUES (11,7,91,1,0,478);
INSERT INTO ezproductcollection_item VALUES (19,11,89,1,0,199);
INSERT INTO ezproductcollection_item VALUES (16,9,91,1,0,239);
INSERT INTO ezproductcollection_item VALUES (15,8,91,1,0,239);
INSERT INTO ezproductcollection_item VALUES (18,10,89,1,0,199);
INSERT INTO ezproductcollection_item VALUES (20,12,89,1,0,199);
INSERT INTO ezproductcollection_item VALUES (21,14,89,1,0,199);

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
INSERT INTO ezsearch_object_word_link VALUES (231,33,123,0,4,122,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (230,33,122,0,3,121,123,1,5);
INSERT INTO ezsearch_object_word_link VALUES (200,1,104,0,5,47,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (199,1,47,0,4,103,104,1,5);
INSERT INTO ezsearch_object_word_link VALUES (198,1,103,0,3,102,47,1,5);
INSERT INTO ezsearch_object_word_link VALUES (197,1,102,0,2,101,103,1,5);
INSERT INTO ezsearch_object_word_link VALUES (196,1,101,0,1,100,102,1,5);
INSERT INTO ezsearch_object_word_link VALUES (195,1,100,0,0,0,101,1,4);
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
INSERT INTO ezsearch_object_word_link VALUES (794,31,375,0,3,88,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (793,31,88,0,2,87,375,1,5);
INSERT INTO ezsearch_object_word_link VALUES (792,31,87,0,1,375,88,1,5);
INSERT INTO ezsearch_object_word_link VALUES (791,31,375,0,0,0,87,1,4);
INSERT INTO ezsearch_object_word_link VALUES (931,32,379,0,3,88,380,1,5);
INSERT INTO ezsearch_object_word_link VALUES (930,32,88,0,2,87,379,1,5);
INSERT INTO ezsearch_object_word_link VALUES (929,32,87,0,1,379,88,1,5);
INSERT INTO ezsearch_object_word_link VALUES (928,32,379,0,0,0,87,1,4);
INSERT INTO ezsearch_object_word_link VALUES (229,33,121,0,2,120,122,1,5);
INSERT INTO ezsearch_object_word_link VALUES (228,33,120,0,1,119,121,1,5);
INSERT INTO ezsearch_object_word_link VALUES (227,33,119,0,0,0,120,1,4);
INSERT INTO ezsearch_object_word_link VALUES (1509,34,596,0,4,103,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1508,34,103,0,3,595,596,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1507,34,595,0,2,594,103,7,27);
INSERT INTO ezsearch_object_word_link VALUES (1506,34,594,0,1,593,595,7,26);
INSERT INTO ezsearch_object_word_link VALUES (1505,34,593,0,0,0,594,7,26);
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
INSERT INTO ezsearch_object_word_link VALUES (932,32,380,0,4,379,0,1,5);
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
INSERT INTO ezsearch_word VALUES (5,'main',1);
INSERT INTO ezsearch_word VALUES (6,'group',2);
INSERT INTO ezsearch_word VALUES (7,'this',3);
INSERT INTO ezsearch_word VALUES (8,'is',2);
INSERT INTO ezsearch_word VALUES (9,'the',4);
INSERT INTO ezsearch_word VALUES (10,'master',1);
INSERT INTO ezsearch_word VALUES (11,'users',5);
INSERT INTO ezsearch_word VALUES (14,'other',4);
INSERT INTO ezsearch_word VALUES (15,'floyd',2);
INSERT INTO ezsearch_word VALUES (16,'first',2);
INSERT INTO ezsearch_word VALUES (17,'article',6);
INSERT INTO ezsearch_word VALUES (18,'bla',26);
INSERT INTO ezsearch_word VALUES (19,'blabla',1);
INSERT INTO ezsearch_word VALUES (20,'blbla',1);
INSERT INTO ezsearch_word VALUES (21,'blaa',1);
INSERT INTO ezsearch_word VALUES (22,'second',2);
INSERT INTO ezsearch_word VALUES (23,'foo',24);
INSERT INTO ezsearch_word VALUES (104,'treet',1);
INSERT INTO ezsearch_word VALUES (103,'i',10);
INSERT INTO ezsearch_word VALUES (102,'node',1);
INSERT INTO ezsearch_word VALUES (101,'topp',1);
INSERT INTO ezsearch_word VALUES (100,'forsiden',1);
INSERT INTO ezsearch_word VALUES (30,'products',2);
INSERT INTO ezsearch_word VALUES (31,'here',2);
INSERT INTO ezsearch_word VALUES (32,'are',1);
INSERT INTO ezsearch_word VALUES (33,'ez',7);
INSERT INTO ezsearch_word VALUES (34,'publish',7);
INSERT INTO ezsearch_word VALUES (35,'3',4);
INSERT INTO ezsearch_word VALUES (36,'0',5);
INSERT INTO ezsearch_word VALUES (37,'(the',1);
INSERT INTO ezsearch_word VALUES (38,'best',1);
INSERT INTO ezsearch_word VALUES (39,'cms',1);
INSERT INTO ezsearch_word VALUES (40,'ever)',1);
INSERT INTO ezsearch_word VALUES (41,'10000',1);
INSERT INTO ezsearch_word VALUES (42,'book',1);
INSERT INTO ezsearch_word VALUES (43,'how',1);
INSERT INTO ezsearch_word VALUES (44,'to',2);
INSERT INTO ezsearch_word VALUES (45,'manage',1);
INSERT INTO ezsearch_word VALUES (46,'your',1);
INSERT INTO ezsearch_word VALUES (47,'content',2);
INSERT INTO ezsearch_word VALUES (48,'with',1);
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
INSERT INTO ezsearch_word VALUES (85,'doe',1);
INSERT INTO ezsearch_word VALUES (84,'john',1);
INSERT INTO ezsearch_word VALUES (83,'setsetsetset',1);
INSERT INTO ezsearch_word VALUES (82,'setset',4);
INSERT INTO ezsearch_word VALUES (81,'new',4);
INSERT INTO ezsearch_word VALUES (375,'nyheter',2);
INSERT INTO ezsearch_word VALUES (87,'hovedkategori',2);
INSERT INTO ezsearch_word VALUES (88,'for',2);
INSERT INTO ezsearch_word VALUES (118,'eiendom',2);
INSERT INTO ezsearch_word VALUES (122,'bildene',1);
INSERT INTO ezsearch_word VALUES (121,'ligger',1);
INSERT INTO ezsearch_word VALUES (120,'her',7);
INSERT INTO ezsearch_word VALUES (119,'bilder',1);
INSERT INTO ezsearch_word VALUES (595,'ute',1);
INSERT INTO ezsearch_word VALUES (594,'blomster',1);
INSERT INTO ezsearch_word VALUES (128,'speed',2);
INSERT INTO ezsearch_word VALUES (129,'nyttr',2);
INSERT INTO ezsearch_word VALUES (123,'lagret',1);
INSERT INTO ezsearch_word VALUES (593,'lilla',1);
INSERT INTO ezsearch_word VALUES (374,'pus',1);
INSERT INTO ezsearch_word VALUES (373,'katte',1);
INSERT INTO ezsearch_word VALUES (134,'statue',2);
INSERT INTO ezsearch_word VALUES (378,'ipsolum',58);
INSERT INTO ezsearch_word VALUES (136,'p',8);
INSERT INTO ezsearch_word VALUES (137,'brd',3);
INSERT INTO ezsearch_word VALUES (138,'farstad',3);
INSERT INTO ezsearch_word VALUES (377,'lorem',64);
INSERT INTO ezsearch_word VALUES (376,'demo',5);
INSERT INTO ezsearch_word VALUES (141,'leilighet/b/l',1);
INSERT INTO ezsearch_word VALUES (142,'rekkehus',2);
INSERT INTO ezsearch_word VALUES (143,'over',3);
INSERT INTO ezsearch_word VALUES (144,'2',10);
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
INSERT INTO ezsearch_word VALUES (194,'like',1);
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
INSERT INTO ezsearch_word VALUES (206,'as',1);
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
INSERT INTO ezsearch_word VALUES (217,'no',2);
INSERT INTO ezsearch_word VALUES (218,'editors',4);
INSERT INTO ezsearch_word VALUES (219,'user',1);
INSERT INTO ezsearch_word VALUES (220,'editor',4);
INSERT INTO ezsearch_word VALUES (221,'advanced',2);
INSERT INTO ezsearch_word VALUES (222,'we',1);
INSERT INTO ezsearch_word VALUES (223,'will',1);
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
INSERT INTO ezsearch_word VALUES (254,'m',4);
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
INSERT INTO ezsearch_word VALUES (289,'et',10);
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
INSERT INTO ezsearch_word VALUES (327,'name',1);
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
INSERT INTO ezsearch_word VALUES (343,'topic',2);
INSERT INTO ezsearch_word VALUES (345,'sdfgsdfgsdfgsdf',1);
INSERT INTO ezsearch_word VALUES (346,'sdfgsdfg',1);
INSERT INTO ezsearch_word VALUES (347,'sdfgsdfgsdfgsdfg',1);
INSERT INTO ezsearch_word VALUES (348,'product',1);
INSERT INTO ezsearch_word VALUES (349,'about',1);
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
INSERT INTO ezsearch_word VALUES (379,'dvd',2);
INSERT INTO ezsearch_word VALUES (380,'filmer',1);
INSERT INTO ezsearch_word VALUES (381,'nyhets',1);
INSERT INTO ezsearch_word VALUES (382,'liten',5);
INSERT INTO ezsearch_word VALUES (383,'nyhetspublisering',2);
INSERT INTO ezsearch_word VALUES (384,'ipsum',6);
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
INSERT INTO ezsearch_word VALUES (427,'in',8);
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
INSERT INTO ezsearch_word VALUES (438,'at',5);
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
INSERT INTO ezsearch_word VALUES (521,'sone',2);
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
INSERT INTO ezsearch_word VALUES (533,'man',1);
INSERT INTO ezsearch_word VALUES (534,'2002',2);
INSERT INTO ezsearch_word VALUES (535,'peter',1);
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
INSERT INTO ezsearch_word VALUES (596,'naturen',1);
INSERT INTO ezsearch_word VALUES (597,'var',1);
INSERT INTO ezsearch_word VALUES (598,'bra',1);
INSERT INTO ezsearch_word VALUES (599,'blalb',1);
INSERT INTO ezsearch_word VALUES (600,'alb',1);
INSERT INTO ezsearch_word VALUES (601,'la',1);

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

INSERT INTO ezsection VALUES (1,'Articles','nor-NO');
INSERT INTO ezsection VALUES (2,'Sport','en-US');
INSERT INTO ezsection VALUES (3,'Products','nor-No');
INSERT INTO ezsection VALUES (4,'Apartaments','en-US');
INSERT INTO ezsection VALUES (5,'Images','en-US');

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
INSERT INTO ezsession VALUES ('7aa2cf9537602fe690dde6d52d7e5bbf',1032098719,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}BrowseFromPage|s:13:\"/task/edit/20\";BrowseActionName|s:22:\"SelectAssignmentObject\";BrowseReturnType|s:15:\"ContentObjectID\";CustomActionButton|N;eZUserLoggedInID|s:1:\"8\";');
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
INSERT INTO ezsession VALUES ('150e052e0b1a3f20da0603ad7620e310',1032098203,'eZExecutionStack|a:0:{}eZUserLoggedInID|N;');

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
INSERT INTO ezworkflow VALUES (1,1,1,'group_ezserial','Publish',-1,30,1024392098,1031833467);

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


