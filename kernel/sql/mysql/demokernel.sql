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

INSERT INTO ezbinaryfile VALUES (136,1,'4hBboh.bin','ez.licence','application/octet-stream');

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
INSERT INTO ezcontentclass VALUES (4,1,'User','user','<first_name> <last_name>',-1,8,1024392098,1031241977);
INSERT INTO ezcontentclass VALUES (8,1,'Appartment','','<name>',8,8,1031485062,1031488190);
INSERT INTO ezcontentclass VALUES (7,0,'Image','','<name>',8,8,1031484992,1031485055);
INSERT INTO ezcontentclass VALUES (8,0,'Appartment','','<name>',8,8,1031485062,1031485170);
INSERT INTO ezcontentclass VALUES (2,1,'Article','article','<title>',-1,8,1024392098,1031489068);
INSERT INTO ezcontentclass VALUES (9,0,'Presentasjon','','<navn>',8,8,1031492055,1031502380);
INSERT INTO ezcontentclass VALUES (5,1,'Simple Product','simple_product','<name>',8,8,1031228120,1031497734);
INSERT INTO ezcontentclass VALUES (10,0,'Fish','','<navn>',8,8,1031502832,1031503049);

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
INSERT INTO ezcontentclass_attribute VALUES (38,0,9,'region','region','ezenum',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (14,1,5,'name','Name','ezstring',1,0,1,255,0,0,0,0,0,0,0,'Product','','','');
INSERT INTO ezcontentclass_attribute VALUES (15,1,5,'description','description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'About','','','');
INSERT INTO ezcontentclass_attribute VALUES (16,1,5,'price','Price','ezprice',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (39,0,9,'valg','Valg','ezoption',1,0,4,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (37,0,9,'fil','Fil','ezbinaryfile',1,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (36,0,9,'navn','Navn','ezstring',1,0,1,50,0,0,0,0,0,0,0,'Ny presentasjon','','','');
INSERT INTO ezcontentclass_attribute VALUES (42,0,10,'beskrivelse','Beskrivelse','eztext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (41,0,10,'alder','Alder','ezinteger',1,0,2,1,100,0,3,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute VALUES (40,0,10,'navn','Navn','ezstring',1,0,1,150,0,0,0,0,0,0,0,'En ny fisk...','','','');

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
INSERT INTO ezcontentclass_classgroup VALUES (2,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,1,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (4,1,2,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (5,1,3,'Products');
INSERT INTO ezcontentclass_classgroup VALUES (5,0,3,'Products');
INSERT INTO ezcontentclass_classgroup VALUES (9,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (10,0,1,'Content');

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
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table 'ezcontentobject'
#

INSERT INTO ezcontentobject VALUES (1,0,0,2,1,1,'Hovedkategori',6,0,1);
INSERT INTO ezcontentobject VALUES (4,0,0,5,0,3,'This is the master users',2,0,1);
INSERT INTO ezcontentobject VALUES (8,1,1,6,0,4,'Sergey Pushchin',2,0,1);
INSERT INTO ezcontentobject VALUES (10,1,1,11,0,3,'Other users',1,0,1);
INSERT INTO ezcontentobject VALUES (11,1,1,12,0,4,'Floyd Floyd',1,0,1);
INSERT INTO ezcontentobject VALUES (39,8,1,41,1,2,'Demo på eZ publish',1,0,1);
INSERT INTO ezcontentobject VALUES (38,8,1,40,5,7,'Statue',1,0,1);
INSERT INTO ezcontentobject VALUES (36,8,1,37,5,7,'Nyttår',2,0,1);
INSERT INTO ezcontentobject VALUES (33,8,1,34,5,1,'Bilder',2,0,1);
INSERT INTO ezcontentobject VALUES (34,8,1,35,5,7,'Lilla blomster',2,0,1);
INSERT INTO ezcontentobject VALUES (30,8,1,31,0,4,'John Doe',1,0,1);
INSERT INTO ezcontentobject VALUES (21,1,1,22,0,4,'Amos',1,0,1);
INSERT INTO ezcontentobject VALUES (41,8,1,43,0,3,'Editors',1,0,1);
INSERT INTO ezcontentobject VALUES (23,21,1,24,0,4,'Test user',1,0,1);
INSERT INTO ezcontentobject VALUES (37,8,1,39,5,7,'Katte',2,0,1);
INSERT INTO ezcontentobject VALUES (32,8,1,33,4,1,'Eiendom',4,0,1);
INSERT INTO ezcontentobject VALUES (31,8,1,32,1,1,'Nyheter',4,0,1);
INSERT INTO ezcontentobject VALUES (35,8,1,36,5,7,'Speed',2,0,1);
INSERT INTO ezcontentobject VALUES (42,8,1,44,0,4,'First  Editor',1,0,1);
INSERT INTO ezcontentobject VALUES (43,8,1,45,0,3,'Advanced editors',1,0,1);
INSERT INTO ezcontentobject VALUES (44,8,1,46,0,4,'Chief Editor',1,0,1);
INSERT INTO ezcontentobject VALUES (45,8,1,47,4,8,'Fritidseiendom - Brandbu GRAN, Hornslinna 154',1,0,1);
INSERT INTO ezcontentobject VALUES (46,8,1,48,1,2,'Nyhet 2',3,0,1);
INSERT INTO ezcontentobject VALUES (47,8,1,49,1,1,'Presentasjoner',1,0,1);
INSERT INTO ezcontentobject VALUES (48,8,1,50,1,9,'New Presentasjon',1,0,1);
INSERT INTO ezcontentobject VALUES (49,8,1,51,1,9,'Ny presentasjon',1,0,1);
INSERT INTO ezcontentobject VALUES (52,8,1,55,0,4,'test test',1,0,1);
INSERT INTO ezcontentobject VALUES (53,8,1,56,0,4,'t1 t1',1,0,1);
INSERT INTO ezcontentobject VALUES (54,8,1,57,1,9,'Ny presentasjon',1,0,1);
INSERT INTO ezcontentobject VALUES (55,8,1,58,1,9,'Ny presentasjon',1,0,1);
INSERT INTO ezcontentobject VALUES (56,8,1,59,1,10,'',1,0,1);

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
INSERT INTO ezcontentobject_attribute VALUES (88,'en_GB',1,39,1,'Demo på eZ publish',0,0);
INSERT INTO ezcontentobject_attribute VALUES (89,'en_GB',1,39,21,'demo test demo test demo',0,0);
INSERT INTO ezcontentobject_attribute VALUES (90,'en_GB',1,39,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (91,'en_GB',1,39,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (92,'en_GB',1,39,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph><header>Lorem ipsolum</header>\nLorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum.</paragraph><paragraph><header>Lorem ipsolum</header>\nLorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum. Lorem ipsolum.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (93,'en_GB',1,39,25,'',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (111,'en_GB',1,45,29,'Fritidseiendom - Brandbu GRAN, Hornslinna 154',0,0);
INSERT INTO ezcontentobject_attribute VALUES (112,'en_GB',1,45,30,'55 Kvm',0,0);
INSERT INTO ezcontentobject_attribute VALUES (113,'en_GB',1,45,31,'kr 490 000 + omk.',0,0);
INSERT INTO ezcontentobject_attribute VALUES (114,'en_GB',1,45,32,'1956',0,0);
INSERT INTO ezcontentobject_attribute VALUES (115,'en_GB',1,45,33,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (116,'en_GB',1,45,34,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Innhold: gang, kjøkken, wc, bad, 2 soverom, 2 stuer. Fritidsbolig ca 9 km fra Brandbu sentrum, solrik og svært fredelig område. Fritidsboligen har innlagt strøm og er i to etasjer. Ca 40 m. til Randsfjordens strandlinje. Kort vei til skog og mark.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (117,'en_GB',1,45,35,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Notar Eiendom Lillestrøm AS</paragraph><paragraph>Jernbanegata 8 Jernbanegata 8</paragraph><paragraph>Tlf.: 63805300, fax: 63805301</paragraph><paragraph>Epost: kontakt.lillestrom@notar.no</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (118,'en_GB',1,46,1,'Nyhet 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (119,'en_GB',1,46,21,'Dette er siste nytt..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (120,'en_GB',1,46,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (121,'en_GB',1,46,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (122,'en_GB',1,46,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Her er innholdet... Dette er et inkludert objekt av typen bilde:\r\n\r\n<object id=\"35\" /></paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (123,'en_GB',1,46,25,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (118,'en_GB',2,46,1,'Nyhet 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (119,'en_GB',2,46,21,'Dette er siste nytt..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (120,'en_GB',2,46,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (121,'en_GB',2,46,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (122,'en_GB',2,46,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Her er innholdet... Dette er et inkludert objekt av typen bilde:\r\n\r\n<object id=\'35\'/></paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (123,'en_GB',2,46,25,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (118,'en_GB',3,46,1,'Nyhet 2',0,0);
INSERT INTO ezcontentobject_attribute VALUES (119,'en_GB',3,46,21,'Dette er siste nytt..',0,0);
INSERT INTO ezcontentobject_attribute VALUES (120,'en_GB',3,46,22,'Bård Farstad',0,0);
INSERT INTO ezcontentobject_attribute VALUES (121,'en_GB',3,46,23,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her. Introduksjon til en stor nyhet, masse interessant her.</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (122,'en_GB',3,46,24,'<?xml version=\"1.0\" encoding=\"utf-8\" ?><section><paragraph>Her er innholdet... Dette er et inkludert objekt av typen bilde:</paragraph><paragraph><object id=\'35\'/></paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute VALUES (123,'en_GB',3,46,25,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (131,'en_GB',1,47,4,'Presentasjoner',0,0);
INSERT INTO ezcontentobject_attribute VALUES (132,'en_GB',1,47,5,'Her finner du powerpoint presentasjoner',0,0);
INSERT INTO ezcontentobject_attribute VALUES (133,'en_GB',1,48,36,'Ny presentasjon',0,0);
INSERT INTO ezcontentobject_attribute VALUES (134,'en_GB',1,48,37,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (135,'en_GB',1,49,36,'Ny presentasjon',0,0);
INSERT INTO ezcontentobject_attribute VALUES (136,'en_GB',1,49,37,'',0,0);
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
INSERT INTO ezcontentobject_attribute VALUES (151,'en_GB',1,54,36,'Ny presentasjon',0,0);
INSERT INTO ezcontentobject_attribute VALUES (152,'en_GB',1,54,37,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (153,'en_GB',1,54,38,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (154,'en_GB',1,55,36,'Ny presentasjon',0,0);
INSERT INTO ezcontentobject_attribute VALUES (155,'en_GB',1,55,37,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (156,'en_GB',1,55,38,'',0,0);
INSERT INTO ezcontentobject_attribute VALUES (157,'en_GB',1,55,39,'<?xml version=\"1.0\"?>\n<ezoption>  <name>Colour</name>\n  <options>    <option id=\"0\" >gfj</option>\n    <option id=\"1\" >fgjfgj</option>\n    <option id=\"2\" >gfjgfhj</option>\n    <option id=\"3\" >dfhdfghgh</option>\n    <option id=\"4\" >ghkghkghj</option>\n</options>\n</ezoption>\n',0,0);
INSERT INTO ezcontentobject_attribute VALUES (158,'en_GB',1,56,40,'Jalmar',0,0);
INSERT INTO ezcontentobject_attribute VALUES (159,'en_GB',1,56,41,'',56,0);
INSERT INTO ezcontentobject_attribute VALUES (160,'en_GB',1,56,42,'\n        $root =& $doc->createElementNode( \"ezoption\" );\n        $doc->setRoot( $root );\n\n        $name =& $doc->createElementNode( \"name\" );\n        $nameValue =& $doc->createTextNode( $this->Name );\n        $name->appendChild( $nameValue );\n\n        $name->setContent( $this->Name() );\n\n        $root->appendChild( $name );\n',0,0);

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
INSERT INTO ezcontentobject_tree VALUES (33,2,32,0,0,-1780448276,2,'/1/2/33/','',7,8,'hovedkategori/eiendom');
INSERT INTO ezcontentobject_tree VALUES (36,34,35,0,0,766147886,3,'/1/2/34/36/','',8,9,'hovedkategori/bilder/speed');
INSERT INTO ezcontentobject_tree VALUES (37,34,36,0,0,1916485265,3,'/1/2/34/37/','',8,9,'hovedkategori/bilder/nyttr');
INSERT INTO ezcontentobject_tree VALUES (40,34,38,0,0,134088232,3,'/1/2/34/40/','',8,9,'hovedkategori/bilder/statue');
INSERT INTO ezcontentobject_tree VALUES (39,34,37,0,0,-1398453418,3,'/1/2/34/39/','',8,9,'hovedkategori/bilder/katte');
INSERT INTO ezcontentobject_tree VALUES (41,32,39,0,0,544764248,3,'/1/2/32/41/','',8,9,'hovedkategori/nyheter/demo_p_ez_publish');
INSERT INTO ezcontentobject_tree VALUES (43,5,41,0,0,961378214,2,'/1/5/43/','',15,16,'this_is_the_master_users/editors');
INSERT INTO ezcontentobject_tree VALUES (44,43,42,0,0,-1498193455,3,'/1/5/43/44/','',16,17,'this_is_the_master_users/editors/first_editor');
INSERT INTO ezcontentobject_tree VALUES (45,5,43,0,0,1649068118,2,'/1/5/45/','',15,16,'this_is_the_master_users/advanced_editors');
INSERT INTO ezcontentobject_tree VALUES (46,45,44,0,0,-1245754999,3,'/1/5/45/46/','',16,17,'this_is_the_master_users/advanced_editors/chief_editor');
INSERT INTO ezcontentobject_tree VALUES (47,33,45,0,0,1887446632,3,'/1/2/33/47/','',8,9,'hovedkategori/eiendom/fritidseiendom_brandbu_gran_hornslinna_154');
INSERT INTO ezcontentobject_tree VALUES (48,32,46,0,0,-1760502805,3,'/1/2/32/48/','',8,9,'hovedkategori/nyheter/nyhet_2');
INSERT INTO ezcontentobject_tree VALUES (49,2,47,0,0,-661983820,2,'/1/2/49/','',7,8,'hovedkategori/presentasjoner');
INSERT INTO ezcontentobject_tree VALUES (50,49,48,0,0,-1243090587,3,'/1/2/49/50/','',8,9,'hovedkategori/presentasjoner/new_presentasjon');
INSERT INTO ezcontentobject_tree VALUES (51,49,49,0,0,-469312657,3,'/1/2/49/51/','',8,9,'hovedkategori/presentasjoner/ny_presentasjon');
INSERT INTO ezcontentobject_tree VALUES (55,5,52,0,0,214034150,2,'/1/5/55/','',15,16,'this_is_the_master_users/test_test');
INSERT INTO ezcontentobject_tree VALUES (56,5,53,0,0,-1469971828,2,'/1/5/56/','',15,16,'this_is_the_master_users/t1_t1');
INSERT INTO ezcontentobject_tree VALUES (57,2,54,0,0,1374516975,2,'/1/2/57/','',7,8,'hovedkategori/ny_presentasjon');
INSERT INTO ezcontentobject_tree VALUES (58,2,55,0,0,1374516975,2,'/1/2/58/','',7,8,'hovedkategori/ny_presentasjon');
INSERT INTO ezcontentobject_tree VALUES (59,2,56,0,0,-136210981,2,'/1/2/59/','',7,8,'hovedkategori/');

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
INSERT INTO ezcontentobject_version VALUES (76,39,8,1,1031487619,1031487722,0,0,0);
INSERT INTO ezcontentobject_version VALUES (78,41,8,1,1031488556,1031488576,0,0,0);
INSERT INTO ezcontentobject_version VALUES (79,42,8,1,1031488585,1031488617,0,0,0);
INSERT INTO ezcontentobject_version VALUES (80,43,8,1,1031488920,1031488980,0,0,0);
INSERT INTO ezcontentobject_version VALUES (81,44,8,1,1031488986,1031489022,0,0,0);
INSERT INTO ezcontentobject_version VALUES (82,45,8,1,1031489209,1031489264,0,0,0);
INSERT INTO ezcontentobject_version VALUES (83,46,8,1,1031489603,1031489863,0,0,0);
INSERT INTO ezcontentobject_version VALUES (84,46,8,2,1031489886,1031489905,0,0,0);
INSERT INTO ezcontentobject_version VALUES (85,46,8,3,1031489939,1031489982,0,0,0);
INSERT INTO ezcontentobject_version VALUES (88,47,8,1,1031492195,1031492212,0,0,0);
INSERT INTO ezcontentobject_version VALUES (89,48,8,1,1031492219,1031492219,0,0,0);
INSERT INTO ezcontentobject_version VALUES (90,49,8,1,1031492241,1031492323,0,0,0);
INSERT INTO ezcontentobject_version VALUES (102,1,8,7,1031504607,1031504607,1,1,0);
INSERT INTO ezcontentobject_version VALUES (96,52,8,1,1031501235,1031501252,0,0,0);
INSERT INTO ezcontentobject_version VALUES (97,53,8,1,1031501320,1031501337,0,0,0);
INSERT INTO ezcontentobject_version VALUES (98,38,8,2,1031502085,1031502085,0,0,0);
INSERT INTO ezcontentobject_version VALUES (99,54,8,1,1031502290,1031502302,0,0,0);
INSERT INTO ezcontentobject_version VALUES (100,55,8,1,1031502397,1031502453,0,0,0);
INSERT INTO ezcontentobject_version VALUES (101,56,8,1,1031502920,1031502949,0,0,0);

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

INSERT INTO ezenumobjectvalue VALUES (153,1,2,'Porsgrunn','');

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
INSERT INTO ezimage VALUES (93,1,'CtZ9v0.jpg','dscn0360.jpg','image/jpeg');
INSERT INTO ezimage VALUES (115,1,'7HzkUQ.jpg','hus2.jpg','image/jpeg');
INSERT INTO ezimage VALUES (123,1,'nI8hgL','images','application/octet-stream');
INSERT INTO ezimage VALUES (123,2,'jJOmTe','images','application/octet-stream');
INSERT INTO ezimage VALUES (123,3,'KDnlNv.jpg','dscn0059.jpg','image/jpeg');
INSERT INTO ezimage VALUES (87,2,'U1Gxxl.jpg','dscn1534.jpg','image/jpeg');

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
INSERT INTO ezimagevariation VALUES (93,1,'CtZ9v0_100x100_93.jpg','C/t/Z/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (93,1,'CtZ9v0_600x600_93.jpg','C/t/Z/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (115,1,'7HzkUQ_600x600_115.jpg','7/H/z/',600,600,270,216);
INSERT INTO ezimagevariation VALUES (123,1,'nI8hgL','n/I/8/',600,600,113,74);
INSERT INTO ezimagevariation VALUES (123,1,'nI8hgL','n/I/8/',100,100,100,65);
INSERT INTO ezimagevariation VALUES (123,2,'jJOmTe','j/J/O/',100,100,100,65);
INSERT INTO ezimagevariation VALUES (123,3,'KDnlNv_100x100_123.jpg','K/D/n/',100,100,100,75);
INSERT INTO ezimagevariation VALUES (123,3,'KDnlNv_600x600_123.jpg','K/D/n/',600,600,400,300);
INSERT INTO ezimagevariation VALUES (87,2,'U1Gxxl_100x100_87.jpg','U/1/G/',100,100,100,75);

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

INSERT INTO ezpolicy VALUES (87,1,'*','search','*');
INSERT INTO ezpolicy VALUES (91,3,'*','search','*');
INSERT INTO ezpolicy VALUES (92,3,'read','content','*');
INSERT INTO ezpolicy VALUES (113,22,'edit','content','');
INSERT INTO ezpolicy VALUES (47,2,'*','*','*');
INSERT INTO ezpolicy VALUES (86,1,'read','content','');
INSERT INTO ezpolicy VALUES (85,1,'*','task','*');
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

INSERT INTO ezpolicy_limitation VALUES (29,86,'ClassID',0,'','');
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

INSERT INTO ezpolicy_limitation_value VALUES (54,29,1);
INSERT INTO ezpolicy_limitation_value VALUES (55,29,2);
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
INSERT INTO ezpolicy_limitation_value VALUES (56,29,5);
INSERT INTO ezpolicy_limitation_value VALUES (57,29,7);
INSERT INTO ezpolicy_limitation_value VALUES (58,29,8);
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

INSERT INTO ezproductcollection_item VALUES (1,1,15,1,0,10000);
INSERT INTO ezproductcollection_item VALUES (2,2,16,1,0,500);

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
INSERT INTO ezsearch_object_word_link VALUES (222,31,117,0,3,88,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (221,31,88,0,2,87,117,1,5);
INSERT INTO ezsearch_object_word_link VALUES (220,31,87,0,1,117,88,1,5);
INSERT INTO ezsearch_object_word_link VALUES (219,31,117,0,0,0,87,1,4);
INSERT INTO ezsearch_object_word_link VALUES (226,32,118,0,3,88,0,1,5);
INSERT INTO ezsearch_object_word_link VALUES (225,32,88,0,2,87,118,1,5);
INSERT INTO ezsearch_object_word_link VALUES (224,32,87,0,1,118,88,1,5);
INSERT INTO ezsearch_object_word_link VALUES (223,32,118,0,0,0,87,1,4);
INSERT INTO ezsearch_object_word_link VALUES (229,33,121,0,2,120,122,1,5);
INSERT INTO ezsearch_object_word_link VALUES (228,33,120,0,1,119,121,1,5);
INSERT INTO ezsearch_object_word_link VALUES (227,33,119,0,0,0,120,1,4);
INSERT INTO ezsearch_object_word_link VALUES (236,34,127,0,4,103,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (235,34,103,0,3,126,127,7,27);
INSERT INTO ezsearch_object_word_link VALUES (234,34,126,0,2,125,103,7,27);
INSERT INTO ezsearch_object_word_link VALUES (233,34,125,0,1,124,126,7,26);
INSERT INTO ezsearch_object_word_link VALUES (232,34,124,0,0,0,125,7,26);
INSERT INTO ezsearch_object_word_link VALUES (238,35,128,0,1,128,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (237,35,128,0,0,0,128,7,26);
INSERT INTO ezsearch_object_word_link VALUES (240,36,129,0,1,129,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (239,36,129,0,0,0,129,7,26);
INSERT INTO ezsearch_object_word_link VALUES (244,37,133,0,1,132,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (243,37,132,0,0,0,133,7,26);
INSERT INTO ezsearch_object_word_link VALUES (245,38,134,0,0,0,134,7,26);
INSERT INTO ezsearch_object_word_link VALUES (246,38,134,0,1,134,0,7,27);
INSERT INTO ezsearch_object_word_link VALUES (247,39,135,0,0,0,136,2,1);
INSERT INTO ezsearch_object_word_link VALUES (248,39,136,0,1,135,33,2,1);
INSERT INTO ezsearch_object_word_link VALUES (249,39,33,0,2,136,34,2,1);
INSERT INTO ezsearch_object_word_link VALUES (250,39,34,0,3,33,135,2,1);
INSERT INTO ezsearch_object_word_link VALUES (251,39,135,0,4,34,50,2,21);
INSERT INTO ezsearch_object_word_link VALUES (252,39,50,0,5,135,135,2,21);
INSERT INTO ezsearch_object_word_link VALUES (253,39,135,0,6,50,50,2,21);
INSERT INTO ezsearch_object_word_link VALUES (254,39,50,0,7,135,135,2,21);
INSERT INTO ezsearch_object_word_link VALUES (255,39,135,0,8,50,137,2,21);
INSERT INTO ezsearch_object_word_link VALUES (256,39,137,0,9,135,138,2,22);
INSERT INTO ezsearch_object_word_link VALUES (257,39,138,0,10,137,139,2,22);
INSERT INTO ezsearch_object_word_link VALUES (258,39,139,0,11,138,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (259,39,140,0,12,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (260,39,139,0,13,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (261,39,140,0,14,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (262,39,139,0,15,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (263,39,140,0,16,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (264,39,139,0,17,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (265,39,140,0,18,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (266,39,139,0,19,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (267,39,140,0,20,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (268,39,139,0,21,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (269,39,140,0,22,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (270,39,139,0,23,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (271,39,140,0,24,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (272,39,139,0,25,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (273,39,140,0,26,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (274,39,139,0,27,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (275,39,140,0,28,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (276,39,139,0,29,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (277,39,140,0,30,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (278,39,139,0,31,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (279,39,140,0,32,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (280,39,139,0,33,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (281,39,140,0,34,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (282,39,139,0,35,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (283,39,140,0,36,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (284,39,139,0,37,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (285,39,140,0,38,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (286,39,139,0,39,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (287,39,140,0,40,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (288,39,139,0,41,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (289,39,140,0,42,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (290,39,139,0,43,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (291,39,140,0,44,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (292,39,139,0,45,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (293,39,140,0,46,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (294,39,139,0,47,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (295,39,140,0,48,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (296,39,139,0,49,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (297,39,140,0,50,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (298,39,139,0,51,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (299,39,140,0,52,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (300,39,139,0,53,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (301,39,140,0,54,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (302,39,139,0,55,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (303,39,140,0,56,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (304,39,139,0,57,140,140,2,23);
INSERT INTO ezsearch_object_word_link VALUES (305,39,140,0,58,139,139,2,23);
INSERT INTO ezsearch_object_word_link VALUES (306,39,139,0,59,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (307,39,140,0,60,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (308,39,139,0,61,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (309,39,140,0,62,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (310,39,139,0,63,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (311,39,140,0,64,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (312,39,139,0,65,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (313,39,140,0,66,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (314,39,139,0,67,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (315,39,140,0,68,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (316,39,139,0,69,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (317,39,140,0,70,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (318,39,139,0,71,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (319,39,140,0,72,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (320,39,139,0,73,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (321,39,140,0,74,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (322,39,139,0,75,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (323,39,140,0,76,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (324,39,139,0,77,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (325,39,140,0,78,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (326,39,139,0,79,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (327,39,140,0,80,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (328,39,139,0,81,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (329,39,140,0,82,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (330,39,139,0,83,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (331,39,140,0,84,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (332,39,139,0,85,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (333,39,140,0,86,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (334,39,139,0,87,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (335,39,140,0,88,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (336,39,139,0,89,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (337,39,140,0,90,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (338,39,139,0,91,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (339,39,140,0,92,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (340,39,139,0,93,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (341,39,140,0,94,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (342,39,139,0,95,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (343,39,140,0,96,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (344,39,139,0,97,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (345,39,140,0,98,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (346,39,139,0,99,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (347,39,140,0,100,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (348,39,139,0,101,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (349,39,140,0,102,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (350,39,139,0,103,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (351,39,140,0,104,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (352,39,139,0,105,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (353,39,140,0,106,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (354,39,139,0,107,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (355,39,140,0,108,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (356,39,139,0,109,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (357,39,140,0,110,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (358,39,139,0,111,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (359,39,140,0,112,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (360,39,139,0,113,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (361,39,140,0,114,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (362,39,139,0,115,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (363,39,140,0,116,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (364,39,139,0,117,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (365,39,140,0,118,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (366,39,139,0,119,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (367,39,140,0,120,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (368,39,139,0,121,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (369,39,140,0,122,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (370,39,139,0,123,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (371,39,140,0,124,139,139,2,24);
INSERT INTO ezsearch_object_word_link VALUES (372,39,139,0,125,140,140,2,24);
INSERT INTO ezsearch_object_word_link VALUES (373,39,140,0,126,139,0,2,24);
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
INSERT INTO ezsearch_word VALUES (7,'this',2);
INSERT INTO ezsearch_word VALUES (8,'is',2);
INSERT INTO ezsearch_word VALUES (9,'the',3);
INSERT INTO ezsearch_word VALUES (10,'master',1);
INSERT INTO ezsearch_word VALUES (11,'users',5);
INSERT INTO ezsearch_word VALUES (14,'other',4);
INSERT INTO ezsearch_word VALUES (15,'floyd',2);
INSERT INTO ezsearch_word VALUES (16,'first',2);
INSERT INTO ezsearch_word VALUES (17,'article',5);
INSERT INTO ezsearch_word VALUES (18,'bla',22);
INSERT INTO ezsearch_word VALUES (19,'blabla',1);
INSERT INTO ezsearch_word VALUES (20,'blbla',1);
INSERT INTO ezsearch_word VALUES (21,'blaa',1);
INSERT INTO ezsearch_word VALUES (22,'second',2);
INSERT INTO ezsearch_word VALUES (23,'foo',24);
INSERT INTO ezsearch_word VALUES (104,'treet',1);
INSERT INTO ezsearch_word VALUES (103,'i',5);
INSERT INTO ezsearch_word VALUES (102,'node',1);
INSERT INTO ezsearch_word VALUES (101,'topp',1);
INSERT INTO ezsearch_word VALUES (100,'forsiden',1);
INSERT INTO ezsearch_word VALUES (30,'products',2);
INSERT INTO ezsearch_word VALUES (31,'here',2);
INSERT INTO ezsearch_word VALUES (32,'are',1);
INSERT INTO ezsearch_word VALUES (33,'ez',5);
INSERT INTO ezsearch_word VALUES (34,'publish',5);
INSERT INTO ezsearch_word VALUES (35,'3',3);
INSERT INTO ezsearch_word VALUES (36,'0',3);
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
INSERT INTO ezsearch_word VALUES (50,'test',5);
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
INSERT INTO ezsearch_word VALUES (82,'setset',1);
INSERT INTO ezsearch_word VALUES (81,'new',1);
INSERT INTO ezsearch_word VALUES (117,'nyheter',2);
INSERT INTO ezsearch_word VALUES (87,'hovedkategori',2);
INSERT INTO ezsearch_word VALUES (88,'for',2);
INSERT INTO ezsearch_word VALUES (118,'eiendom',4);
INSERT INTO ezsearch_word VALUES (122,'bildene',1);
INSERT INTO ezsearch_word VALUES (121,'ligger',1);
INSERT INTO ezsearch_word VALUES (120,'her',7);
INSERT INTO ezsearch_word VALUES (119,'bilder',1);
INSERT INTO ezsearch_word VALUES (126,'ute',1);
INSERT INTO ezsearch_word VALUES (125,'blomster',1);
INSERT INTO ezsearch_word VALUES (124,'lilla',1);
INSERT INTO ezsearch_word VALUES (128,'speed',2);
INSERT INTO ezsearch_word VALUES (129,'nyttr',2);
INSERT INTO ezsearch_word VALUES (123,'lagret',1);
INSERT INTO ezsearch_word VALUES (127,'naturen',1);
INSERT INTO ezsearch_word VALUES (133,'pus',1);
INSERT INTO ezsearch_word VALUES (132,'katte',1);
INSERT INTO ezsearch_word VALUES (134,'statue',2);
INSERT INTO ezsearch_word VALUES (135,'demo',4);
INSERT INTO ezsearch_word VALUES (136,'p',3);
INSERT INTO ezsearch_word VALUES (137,'brd',2);
INSERT INTO ezsearch_word VALUES (138,'farstad',2);
INSERT INTO ezsearch_word VALUES (139,'lorem',58);
INSERT INTO ezsearch_word VALUES (140,'ipsolum',58);
INSERT INTO ezsearch_word VALUES (141,'leilighet/b/l',1);
INSERT INTO ezsearch_word VALUES (142,'rekkehus',2);
INSERT INTO ezsearch_word VALUES (143,'over',2);
INSERT INTO ezsearch_word VALUES (144,'2',6);
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
INSERT INTO ezsearch_word VALUES (156,'med',4);
INSERT INTO ezsearch_word VALUES (157,'gode',1);
INSERT INTO ezsearch_word VALUES (158,'kvaliter',1);
INSERT INTO ezsearch_word VALUES (159,'stor',6);
INSERT INTO ezsearch_word VALUES (160,'usjenert',1);
INSERT INTO ezsearch_word VALUES (161,'og',8);
INSERT INTO ezsearch_word VALUES (162,'solrik',2);
INSERT INTO ezsearch_word VALUES (163,'terasse',1);
INSERT INTO ezsearch_word VALUES (164,'leiligheten',1);
INSERT INTO ezsearch_word VALUES (165,'har',2);
INSERT INTO ezsearch_word VALUES (166,'god',1);
INSERT INTO ezsearch_word VALUES (167,'planlsning',1);
INSERT INTO ezsearch_word VALUES (168,'stue',1);
INSERT INTO ezsearch_word VALUES (169,'etasje',1);
INSERT INTO ezsearch_word VALUES (170,'nytt',2);
INSERT INTO ezsearch_word VALUES (171,'eikekjkken',1);
INSERT INTO ezsearch_word VALUES (172,'stort',1);
INSERT INTO ezsearch_word VALUES (173,'flislagt',1);
INSERT INTO ezsearch_word VALUES (174,'bad',2);
INSERT INTO ezsearch_word VALUES (175,'det',3);
INSERT INTO ezsearch_word VALUES (176,'er',7);
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
INSERT INTO ezsearch_word VALUES (198,'til',7);
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
INSERT INTO ezsearch_word VALUES (246,'fra',1);
INSERT INTO ezsearch_word VALUES (247,'sentrum',1);
INSERT INTO ezsearch_word VALUES (248,'fredelig',1);
INSERT INTO ezsearch_word VALUES (249,'omrde',1);
INSERT INTO ezsearch_word VALUES (250,'fritidsboligen',1);
INSERT INTO ezsearch_word VALUES (251,'innlagt',1);
INSERT INTO ezsearch_word VALUES (252,'strm',1);
INSERT INTO ezsearch_word VALUES (253,'40',1);
INSERT INTO ezsearch_word VALUES (254,'m',1);
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
INSERT INTO ezsearch_word VALUES (289,'et',1);
INSERT INTO ezsearch_word VALUES (288,'innholdet',1);
INSERT INTO ezsearch_word VALUES (287,'interessant',4);
INSERT INTO ezsearch_word VALUES (286,'masse',4);
INSERT INTO ezsearch_word VALUES (285,'en',4);
INSERT INTO ezsearch_word VALUES (284,'introduksjon',4);
INSERT INTO ezsearch_word VALUES (283,'siste',1);
INSERT INTO ezsearch_word VALUES (282,'dette',2);
INSERT INTO ezsearch_word VALUES (281,'nyhet',5);
INSERT INTO ezsearch_word VALUES (292,'av',1);
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
INSERT INTO ezsession VALUES ('b12170da25d040a306423039163ea168',1031588809,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('19d79c940f2841f5add6f45a59de8eac',1031570780,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('bbb1be56a84d4847b4e48679954a2164',1031569105,'eZExecutionStack|a:0:{}eZUserLoggedInID|i:1;');
INSERT INTO ezsession VALUES ('93ef579fee86c07eeb7cc3d77d93fe08',1031572879,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('4dacda826a8ca23f5a623fe41c148c6f',1031761419,'eZExecutionStack|a:0:{}BrowseFromPage|s:14:\"/role/view/16/\";BrowseActionName|s:10:\"AssignRole\";BrowseReturnType|s:8:\"ObjectID\";CustomActionButton|N;eZUserLoggedInID|s:2:\"53\";');
INSERT INTO ezsession VALUES ('27874ed829d7f9e36b699c0fa64d35c4',1031757392,'eZExecutionStack|a:1:{i:0;a:3:{s:3:\"uri\";s:14:\"/workflow/list\";s:6:\"module\";s:8:\"workflow\";s:8:\"function\";s:4:\"list\";}}eZUserLoggedInID|s:1:\"8\";BrowseFromPage|s:19:\"/content/edit/40/5/\";BrowseActionName|s:17:\"AddNodeAssignment\";BrowseReturnType|s:6:\"NodeID\";CustomActionButton|N;');
INSERT INTO ezsession VALUES ('311e6e5faa61a394b77a4bba5cb60cac',1031758788,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('ec6cebd7b059900af815fecd746f47fb',1031757309,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('a14af21a55467e4367bee661bc7aa164',1031757333,'!eZExecutionStack|');
INSERT INTO ezsession VALUES ('3ead7835ca4ec589ce7fa9d769008c2d',1031758926,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');
INSERT INTO ezsession VALUES ('7aa2cf9537602fe690dde6d52d7e5bbf',1031816545,'eZExecutionStack|a:0:{}eZUserLoggedInID|s:1:\"8\";');

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
INSERT INTO ezuser VALUES (8,'sp','sp@sp',3,'077194387c925d3dc9e6e6777ad685e4');
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
INSERT INTO ezuser_role VALUES (11,1,0);
INSERT INTO ezuser_role VALUES (12,1,4);
INSERT INTO ezuser_role VALUES (17,13,30);
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
INSERT INTO ezworkflow VALUES (3,1,1,'group_ezserial','Advanced approval',-1,8,1024392098,1031497060);

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
INSERT INTO ezworkflow_event VALUES (4,1,3,'event_ezmessage','Send first message',0,0,0,0,'First test message from event','','','',1);
INSERT INTO ezworkflow_event VALUES (5,1,3,'event_ezapprove','Approve by editor',0,0,0,0,'','','','',2);
INSERT INTO ezworkflow_event VALUES (6,1,3,'event_ezpublish','Unpublish',0,0,0,0,'','','','',3);
INSERT INTO ezworkflow_event VALUES (7,1,3,'event_ezmessage','Send second message',0,0,0,0,'Some text','','','',4);
INSERT INTO ezworkflow_event VALUES (8,1,3,'event_ezpublish','Publish',1,0,0,0,'','','','',5);

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


