-- MySQL dump 10.2
--
-- Host: localhost    Database: nextgen
---------------------------------------------------------
-- Server version	4.1.0-alpha

--
-- Dumping data for table 'ezapprove_items'
--

/*!40000 ALTER TABLE ezapprove_items DISABLE KEYS */;
LOCK TABLES ezapprove_items WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezapprove_items ENABLE KEYS */;

--
-- Dumping data for table 'ezbasket'
--

/*!40000 ALTER TABLE ezbasket DISABLE KEYS */;
LOCK TABLES ezbasket WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezbasket ENABLE KEYS */;

--
-- Dumping data for table 'ezbinaryfile'
--

/*!40000 ALTER TABLE ezbinaryfile DISABLE KEYS */;
LOCK TABLES ezbinaryfile WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezbinaryfile ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_group'
--

/*!40000 ALTER TABLE ezcollab_group DISABLE KEYS */;
LOCK TABLES ezcollab_group WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_group ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item'
--

/*!40000 ALTER TABLE ezcollab_item DISABLE KEYS */;
LOCK TABLES ezcollab_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_group_link'
--

/*!40000 ALTER TABLE ezcollab_item_group_link DISABLE KEYS */;
LOCK TABLES ezcollab_item_group_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_group_link ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_message_link'
--

/*!40000 ALTER TABLE ezcollab_item_message_link DISABLE KEYS */;
LOCK TABLES ezcollab_item_message_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_message_link ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_participant_link'
--

/*!40000 ALTER TABLE ezcollab_item_participant_link DISABLE KEYS */;
LOCK TABLES ezcollab_item_participant_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_participant_link ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_status'
--

/*!40000 ALTER TABLE ezcollab_item_status DISABLE KEYS */;
LOCK TABLES ezcollab_item_status WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_item_status ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_notification_rule'
--

/*!40000 ALTER TABLE ezcollab_notification_rule DISABLE KEYS */;
LOCK TABLES ezcollab_notification_rule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_notification_rule ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_profile'
--

/*!40000 ALTER TABLE ezcollab_profile DISABLE KEYS */;
LOCK TABLES ezcollab_profile WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_profile ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_simple_message'
--

/*!40000 ALTER TABLE ezcollab_simple_message DISABLE KEYS */;
LOCK TABLES ezcollab_simple_message WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcollab_simple_message ENABLE KEYS */;

--
-- Dumping data for table 'ezcontent_translation'
--

/*!40000 ALTER TABLE ezcontent_translation DISABLE KEYS */;
LOCK TABLES ezcontent_translation WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontent_translation ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentbrowsebookmark'
--

/*!40000 ALTER TABLE ezcontentbrowsebookmark DISABLE KEYS */;
LOCK TABLES ezcontentbrowsebookmark WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentbrowsebookmark ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentbrowserecent'
--

/*!40000 ALTER TABLE ezcontentbrowserecent DISABLE KEYS */;
LOCK TABLES ezcontentbrowserecent WRITE;
INSERT INTO ezcontentbrowserecent VALUES (1,14,2,1060695457,'Root folder');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentbrowserecent ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclass'
--

/*!40000 ALTER TABLE ezcontentclass DISABLE KEYS */;
LOCK TABLES ezcontentclass WRITE;
INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1048494694),(2,0,'Article','article','<title>',-1,14,1024392098,1048494722),(3,0,'User group','user_group','<name>',-1,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1048494759),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(6,0,'Forum','forum','<name>',14,14,1052384723,1052384870),(7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943),(8,0,'Product','product','<title>',14,14,1052384951,1052385067),(9,0,'Product review','product_review','<title>',14,14,1052385080,1052385252),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(11,0,'Link','link','<title>',14,14,1052385361,1052385453),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclass ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclass_attribute'
--

/*!40000 ALTER TABLE ezcontentclass_attribute DISABLE KEYS */;
LOCK TABLES ezcontentclass_attribute WRITE;
INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1),(128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1),(125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1),(132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclass_attribute ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclass_classgroup'
--

/*!40000 ALTER TABLE ezcontentclass_classgroup DISABLE KEYS */;
LOCK TABLES ezcontentclass_classgroup WRITE;
INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclass_classgroup ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclassgroup'
--

/*!40000 ALTER TABLE ezcontentclassgroup DISABLE KEYS */;
LOCK TABLES ezcontentclassgroup WRITE;
INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,14,1031216928,1033922106),(2,'Users',1,14,1031216941,1033922113),(3,'Media',8,14,1032009743,1033922120);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentclassgroup ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject'
--

/*!40000 ALTER TABLE ezcontentobject DISABLE KEYS */;
LOCK TABLES ezcontentobject WRITE;
INSERT INTO ezcontentobject VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL),(4,14,2,3,'Users',1,0,0,0,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(40,14,2,4,'test test',1,0,1053613020,1053613020,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_attribute'
--

/*!40000 ALTER TABLE ezcontentobject_attribute DISABLE KEYS */;
LOCK TABLES ezcontentobject_attribute WRITE;
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL,NULL,0),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,NULL,0),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,NULL,0),(8,'eng-GB',1,4,6,'Users',NULL,NULL,NULL,0),(1,'eng-GB',2,1,4,'My folder',0,0,NULL,0),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0,NULL,0),(21,'eng-GB',1,10,12,'',0,0,NULL,0),(22,'eng-GB',1,11,6,'Guest accounts',0,0,NULL,0),(19,'eng-GB',1,10,8,'Anonymous',0,0,NULL,0),(20,'eng-GB',1,10,9,'User',0,0,NULL,0),(23,'eng-GB',1,11,7,'',0,0,NULL,0),(24,'eng-GB',1,12,6,'Administrator users',0,0,NULL,0),(25,'eng-GB',1,12,7,'',0,0,NULL,0),(26,'eng-GB',1,13,6,'Editors',0,0,NULL,0),(27,'eng-GB',1,13,7,'',0,0,NULL,0),(28,'eng-GB',1,14,8,'Administrator',0,0,NULL,0),(29,'eng-GB',1,14,9,'User',0,0,NULL,0),(30,'eng-GB',1,14,12,'',0,0,NULL,0),(95,'eng-GB',1,40,8,'test',0,0,NULL,0),(96,'eng-GB',1,40,9,'test',0,0,NULL,0),(97,'eng-GB',1,40,12,'',0,0,NULL,0),(98,'eng-GB',1,41,4,'Media',0,0,'media',0),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,'',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_attribute ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_link'
--

/*!40000 ALTER TABLE ezcontentobject_link DISABLE KEYS */;
LOCK TABLES ezcontentobject_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_link ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_name'
--

/*!40000 ALTER TABLE ezcontentobject_name DISABLE KEYS */;
LOCK TABLES ezcontentobject_name WRITE;
INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(40,'test test',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_name ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_tree'
--

/*!40000 ALTER TABLE ezcontentobject_tree DISABLE KEYS */;
LOCK TABLES ezcontentobject_tree WRITE;
INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,NULL,0,'/1/',1,1,0,NULL,1,NULL),(2,1,1,1,1,0,1,'/1/2/',1,1,0,'',2,'d41d8cd98f00b204e9800998ecf8427e'),(5,1,4,1,0,-195235522,1,'/1/5/',1,1,0,'__1',5,'08a9d0bbf3381652f7cca8738b5a8469'),(11,5,10,1,1,1015610524,2,'/1/5/11/',1,1,0,'__1/anonymous_user',11,'a59d2313b486e0f43477433525edea9b'),(12,5,11,1,1,1857785444,2,'/1/5/12/',1,1,0,'__1/guest_accounts',12,'c894997127008ea742913062f39adfc5'),(13,5,12,1,1,-1978139175,2,'/1/5/13/',1,1,0,'__1/administrator_users',13,'caeccbc33185f04d92e2b6cb83b1c7e4'),(14,5,13,1,1,2094553782,2,'/1/5/14/',1,1,0,'__1/editors',14,'39f6f6f51c1e3a922600b2d415d7a46d'),(15,13,14,1,1,-852704961,3,'/1/5/13/15/',1,1,0,'__1/administrator_users/administrator_user',15,'2c3f2814cfa91bcb17d7893ca6f8a0c4'),(42,12,40,1,1,1079898840,3,'/1/5/12/42/',9,1,0,'__1/guest_accounts/test_test',42,'89deb24e7d441d8088ee611c7b5c5a95'),(43,1,41,1,1,NULL,2,'/1/43/',9,1,0,'media',43,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_tree ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_version'
--

/*!40000 ALTER TABLE ezcontentobject_version DISABLE KEYS */;
LOCK TABLES ezcontentobject_version WRITE;
INSERT INTO ezcontentobject_version VALUES (1,1,14,1,0,0,1,1,0),(4,4,14,1,0,0,1,1,0),(436,1,14,2,1033919080,1033919080,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(471,40,14,1,1053613007,1053613020,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezcontentobject_version ENABLE KEYS */;

--
-- Dumping data for table 'ezdiscountrule'
--

/*!40000 ALTER TABLE ezdiscountrule DISABLE KEYS */;
LOCK TABLES ezdiscountrule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezdiscountrule ENABLE KEYS */;

--
-- Dumping data for table 'ezdiscountsubrule'
--

/*!40000 ALTER TABLE ezdiscountsubrule DISABLE KEYS */;
LOCK TABLES ezdiscountsubrule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezdiscountsubrule ENABLE KEYS */;

--
-- Dumping data for table 'ezdiscountsubrule_value'
--

/*!40000 ALTER TABLE ezdiscountsubrule_value DISABLE KEYS */;
LOCK TABLES ezdiscountsubrule_value WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezdiscountsubrule_value ENABLE KEYS */;

--
-- Dumping data for table 'ezenumobjectvalue'
--

/*!40000 ALTER TABLE ezenumobjectvalue DISABLE KEYS */;
LOCK TABLES ezenumobjectvalue WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezenumobjectvalue ENABLE KEYS */;

--
-- Dumping data for table 'ezenumvalue'
--

/*!40000 ALTER TABLE ezenumvalue DISABLE KEYS */;
LOCK TABLES ezenumvalue WRITE;
INSERT INTO ezenumvalue VALUES (2,136,0,'Ok','3',2),(1,136,0,'Poor','2',1),(3,136,0,'Good','5',3);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezenumvalue ENABLE KEYS */;

--
-- Dumping data for table 'ezforgot_password'
--

/*!40000 ALTER TABLE ezforgot_password DISABLE KEYS */;
LOCK TABLES ezforgot_password WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezforgot_password ENABLE KEYS */;

--
-- Dumping data for table 'ezgeneral_digest_user_settings'
--

/*!40000 ALTER TABLE ezgeneral_digest_user_settings DISABLE KEYS */;
LOCK TABLES ezgeneral_digest_user_settings WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezgeneral_digest_user_settings ENABLE KEYS */;

--
-- Dumping data for table 'ezimage'
--

/*!40000 ALTER TABLE ezimage DISABLE KEYS */;
LOCK TABLES ezimage WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezimage ENABLE KEYS */;

--
-- Dumping data for table 'ezimagevariation'
--

/*!40000 ALTER TABLE ezimagevariation DISABLE KEYS */;
LOCK TABLES ezimagevariation WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezimagevariation ENABLE KEYS */;

--
-- Dumping data for table 'ezinfocollection'
--

/*!40000 ALTER TABLE ezinfocollection DISABLE KEYS */;
LOCK TABLES ezinfocollection WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezinfocollection ENABLE KEYS */;

--
-- Dumping data for table 'ezinfocollection_attribute'
--

/*!40000 ALTER TABLE ezinfocollection_attribute DISABLE KEYS */;
LOCK TABLES ezinfocollection_attribute WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezinfocollection_attribute ENABLE KEYS */;

--
-- Dumping data for table 'ezkeyword'
--

/*!40000 ALTER TABLE ezkeyword DISABLE KEYS */;
LOCK TABLES ezkeyword WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezkeyword ENABLE KEYS */;

--
-- Dumping data for table 'ezkeyword_attribute_link'
--

/*!40000 ALTER TABLE ezkeyword_attribute_link DISABLE KEYS */;
LOCK TABLES ezkeyword_attribute_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezkeyword_attribute_link ENABLE KEYS */;

--
-- Dumping data for table 'ezmedia'
--

/*!40000 ALTER TABLE ezmedia DISABLE KEYS */;
LOCK TABLES ezmedia WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezmedia ENABLE KEYS */;

--
-- Dumping data for table 'ezmessage'
--

/*!40000 ALTER TABLE ezmessage DISABLE KEYS */;
LOCK TABLES ezmessage WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezmessage ENABLE KEYS */;

--
-- Dumping data for table 'ezmodule_run'
--

/*!40000 ALTER TABLE ezmodule_run DISABLE KEYS */;
LOCK TABLES ezmodule_run WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezmodule_run ENABLE KEYS */;

--
-- Dumping data for table 'eznode_assignment'
--

/*!40000 ALTER TABLE eznode_assignment DISABLE KEYS */;
LOCK TABLES eznode_assignment WRITE;
INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,4,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(145,1,2,1,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(181,40,1,12,9,1,1,0,0),(182,41,1,2,9,1,1,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE eznode_assignment ENABLE KEYS */;

--
-- Dumping data for table 'eznotificationcollection'
--

/*!40000 ALTER TABLE eznotificationcollection DISABLE KEYS */;
LOCK TABLES eznotificationcollection WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE eznotificationcollection ENABLE KEYS */;

--
-- Dumping data for table 'eznotificationcollection_item'
--

/*!40000 ALTER TABLE eznotificationcollection_item DISABLE KEYS */;
LOCK TABLES eznotificationcollection_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE eznotificationcollection_item ENABLE KEYS */;

--
-- Dumping data for table 'eznotificationevent'
--

/*!40000 ALTER TABLE eznotificationevent DISABLE KEYS */;
LOCK TABLES eznotificationevent WRITE;
INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',41,1,0,0,'','','','');
UNLOCK TABLES;
/*!40000 ALTER TABLE eznotificationevent ENABLE KEYS */;

--
-- Dumping data for table 'ezoperation_memento'
--

/*!40000 ALTER TABLE ezoperation_memento DISABLE KEYS */;
LOCK TABLES ezoperation_memento WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezoperation_memento ENABLE KEYS */;

--
-- Dumping data for table 'ezorder'
--

/*!40000 ALTER TABLE ezorder DISABLE KEYS */;
LOCK TABLES ezorder WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezorder ENABLE KEYS */;

--
-- Dumping data for table 'ezorder_item'
--

/*!40000 ALTER TABLE ezorder_item DISABLE KEYS */;
LOCK TABLES ezorder_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezorder_item ENABLE KEYS */;

--
-- Dumping data for table 'ezpolicy'
--

/*!40000 ALTER TABLE ezpolicy DISABLE KEYS */;
LOCK TABLES ezpolicy WRITE;
INSERT INTO ezpolicy VALUES (317,3,'*','content','*'),(308,2,'*','*','*'),(326,1,'read','content',''),(325,1,'login','user','*'),(319,3,'login','user','*'),(323,5,'*','content','*'),(324,5,'login','user','*');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpolicy ENABLE KEYS */;

--
-- Dumping data for table 'ezpolicy_limitation'
--

/*!40000 ALTER TABLE ezpolicy_limitation DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation WRITE;
INSERT INTO ezpolicy_limitation VALUES (249,326,'Class',0,'read','content');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpolicy_limitation ENABLE KEYS */;

--
-- Dumping data for table 'ezpolicy_limitation_value'
--

/*!40000 ALTER TABLE ezpolicy_limitation_value DISABLE KEYS */;
LOCK TABLES ezpolicy_limitation_value WRITE;
INSERT INTO ezpolicy_limitation_value VALUES (435,249,'1'),(436,249,'10'),(437,249,'10'),(438,249,'11'),(439,249,'11'),(440,249,'12'),(441,249,'12'),(442,249,'13'),(443,249,'13'),(444,249,'2'),(445,249,'2'),(446,249,'5'),(447,249,'5'),(448,249,'6'),(449,249,'6'),(450,249,'7'),(451,249,'7'),(452,249,'8'),(453,249,'8'),(454,249,'9'),(455,249,'9');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpolicy_limitation_value ENABLE KEYS */;

--
-- Dumping data for table 'ezpreferences'
--

/*!40000 ALTER TABLE ezpreferences DISABLE KEYS */;
LOCK TABLES ezpreferences WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezpreferences ENABLE KEYS */;

--
-- Dumping data for table 'ezproductcollection'
--

/*!40000 ALTER TABLE ezproductcollection DISABLE KEYS */;
LOCK TABLES ezproductcollection WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezproductcollection ENABLE KEYS */;

--
-- Dumping data for table 'ezproductcollection_item'
--

/*!40000 ALTER TABLE ezproductcollection_item DISABLE KEYS */;
LOCK TABLES ezproductcollection_item WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezproductcollection_item ENABLE KEYS */;

--
-- Dumping data for table 'ezproductcollection_item_opt'
--

/*!40000 ALTER TABLE ezproductcollection_item_opt DISABLE KEYS */;
LOCK TABLES ezproductcollection_item_opt WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezproductcollection_item_opt ENABLE KEYS */;

--
-- Dumping data for table 'ezrole'
--

/*!40000 ALTER TABLE ezrole DISABLE KEYS */;
LOCK TABLES ezrole WRITE;
INSERT INTO ezrole VALUES (1,0,'Anonymous',''),(2,0,'Administrator','*'),(3,0,'Editor',''),(5,3,'Editor',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezrole ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_object_word_link'
--

/*!40000 ALTER TABLE ezsearch_object_word_link DISABLE KEYS */;
LOCK TABLES ezsearch_object_word_link WRITE;
INSERT INTO ezsearch_object_word_link VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0),(27,40,5,0,1,5,0,4,1053613020,2,9,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_object_word_link ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_return_count'
--

/*!40000 ALTER TABLE ezsearch_return_count DISABLE KEYS */;
LOCK TABLES ezsearch_return_count WRITE;
INSERT INTO ezsearch_return_count VALUES (1,1,1053603212,0),(2,2,1053603219,0),(3,2,1053603317,0),(4,3,1053603339,0),(5,4,1053603362,0),(6,4,1053603366,0),(7,5,1053603417,1),(8,5,1053603976,1),(9,5,1053604331,1),(10,5,1053604371,1),(11,5,1053604407,1),(12,5,1053604433,1),(13,5,1053604493,1),(14,5,1053604514,1),(15,5,1053604563,1),(16,5,1053604605,1),(17,5,1053604624,1),(18,5,1053604678,1),(19,5,1053604748,1),(20,5,1053604807,1),(21,5,1053604826,1),(22,5,1053604937,1),(23,5,1053604982,1),(24,5,1053605043,1),(25,5,1053606010,1),(26,5,1053606051,1),(27,5,1053606081,1),(28,5,1053606198,1),(29,5,1053606233,1),(30,5,1053606265,1),(31,5,1053606293,1),(32,5,1053606343,1),(33,5,1053606415,1),(34,5,1053606522,1),(35,5,1053606559,1),(36,5,1053606688,1),(37,5,1053606732,1),(38,5,1053606775,1),(39,5,1053606808,1),(40,5,1053606853,1),(41,5,1053606903,1),(42,5,1053606949,1),(43,5,1053607040,1),(44,5,1053607066,1),(45,5,1053607089,1),(46,5,1053607114,1),(47,5,1053607137,1),(48,5,1053607190,1),(49,5,1053607233,1),(50,5,1053607265,1),(51,5,1053607299,1),(52,5,1053607352,1),(53,5,1053607524,1),(54,5,1053607557,1),(55,5,1053607599,1),(56,5,1053607626,1),(57,5,1053607655,1),(58,5,1053607690,1),(59,5,1053607725,1),(60,5,1053608672,1),(61,5,1053608711,1),(62,5,1053608797,1),(63,5,1053608861,1),(64,5,1053608907,1),(65,5,1053608953,1),(66,5,1053608996,1),(67,5,1053609019,1),(68,5,1053609058,1),(69,5,1053609197,1),(70,5,1053609408,0),(71,6,1053609435,0),(72,7,1053609446,0),(73,8,1053609450,0),(74,9,1053609801,0),(75,10,1053609804,0),(76,9,1053609809,0),(77,9,1053609821,0),(78,9,1053609823,0),(79,9,1053609828,0),(80,9,1053609836,0),(81,5,1053611177,1),(82,5,1053611230,3),(83,5,1053612092,3),(84,5,1053612155,3),(85,5,1053612195,3),(86,5,1053612197,3),(87,5,1053612237,3),(88,5,1053612245,3),(89,5,1053612332,3),(90,5,1053612404,3),(91,5,1053612694,0),(92,5,1053612699,0),(93,2,1053612736,0),(94,5,1053612977,16),(95,5,1053612986,16),(96,5,1053612991,16),(97,5,1053613125,17),(98,5,1053613272,15),(99,5,1053613278,15),(100,5,1053613281,15),(101,5,1053613297,15),(102,5,1053613300,15),(103,5,1053614865,15),(104,5,1053614878,15),(105,5,1053614902,15),(106,5,1053614952,15),(107,5,1053615196,15),(108,5,1053615246,15),(109,5,1053616143,17),(110,5,1053679445,17),(111,5,1053679454,17),(112,5,1053679458,17),(113,5,1053679462,17),(114,5,1053679466,17),(115,5,1053679471,17),(116,5,1053681400,17),(117,5,1053681419,17),(118,5,1053681442,17),(119,5,1053681678,17),(120,5,1053681694,17);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_return_count ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_search_phrase'
--

/*!40000 ALTER TABLE ezsearch_search_phrase DISABLE KEYS */;
LOCK TABLES ezsearch_search_phrase WRITE;
INSERT INTO ezsearch_search_phrase VALUES (1,'user'),(2,'Administrator'),(3,'Guest'),(4,'Editors'),(5,'test'),(6,'Anonymous User'),(7,'Article'),(8,'s'),(9,'t'),(10,'');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_search_phrase ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_word'
--

/*!40000 ALTER TABLE ezsearch_word DISABLE KEYS */;
LOCK TABLES ezsearch_word WRITE;
INSERT INTO ezsearch_word VALUES (5,'test',1),(6,'media',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsearch_word ENABLE KEYS */;

--
-- Dumping data for table 'ezsection'
--

/*!40000 ALTER TABLE ezsection DISABLE KEYS */;
LOCK TABLES ezsection WRITE;
INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart'),(2,'Users','','ezusernavigationpart'),(3,'Media','','ezmedianavigationpart');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsection ENABLE KEYS */;

--
-- Dumping data for table 'ezsession'
--

/*!40000 ALTER TABLE ezsession DISABLE KEYS */;
LOCK TABLES ezsession WRITE;
INSERT INTO ezsession VALUES ('e1dda328caaf93cd0d0ae57c05f9b97d',1060955577,'LastAccessesURI|s:21:\"/content/view/full/43\";eZUserInfoCache_Timestamp|i:1060695414;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1060695414;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1060695414;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}eZUserDiscountRulesTimestamp|i:1060695414;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"3\";}canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1060695416;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:13:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:1:\"6\";s:4:\"name\";s:5:\"Forum\";}i:6;a:2:{s:2:\"id\";s:1:\"7\";s:4:\"name\";s:13:\"Forum message\";}i:7;a:2:{s:2:\"id\";s:1:\"8\";s:4:\"name\";s:7:\"Product\";}i:8;a:2:{s:2:\"id\";s:1:\"9\";s:4:\"name\";s:14:\"Product review\";}i:9;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:10;a:2:{s:2:\"id\";s:2:\"11\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:12;a:2:{s:2:\"id\";s:2:\"13\";s:4:\"name\";s:7:\"Comment\";}}BrowseParameters|a:12:{s:11:\"action_name\";s:13:\"AssignSection\";s:20:\"description_template\";s:32:\"design:section/browse_assign.tpl\";s:7:\"content\";a:1:{s:10:\"section_id\";s:1:\"3\";}s:9:\"from_page\";s:18:\"/section/assign/3/\";s:4:\"type\";s:13:\"AssignSection\";s:9:\"selection\";s:6:\"single\";s:11:\"return_type\";s:6:\"NodeID\";s:20:\"browse_custom_action\";b:0;s:18:\"custom_action_data\";b:0;s:10:\"start_node\";s:2:\"43\";s:15:\"persistent_data\";b:0;s:15:\"top_level_nodes\";a:3:{i:0;s:1:\"2\";i:1;s:1:\"5\";i:2;s:2:\"43\";}}'),('6d00c99a2e2648e80fa01fc060611640',1060955016,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695816;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('da3b34f95c343e7feb19730e80877280',1060955016,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695816;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('525a1d94b7b39bb3922f0271b34524cd',1060955063,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695863;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('7d2e024021a44ac859415b1b95e8ac2e',1060955063,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695863;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('21b4464029119621f519f2b2dc2a7a58',1060955171,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695970;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('b7e363ba72703f64774662478dcc5855',1060955171,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695971;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('da3249a6ce4a7dd10d57de2c92b048e0',1060955177,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695977;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('2fc7e6ca08620c754f4f7568fc3bcafc',1060955177,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695977;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('88843221a6c019e822667fb8dd24e84e',1060955198,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695997;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('bf7607b2498406f864ca4dd08c49e90b',1060955197,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060695997;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('24707a180065bee268bfc00eb4117eb4',1060955495,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060696295;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('24a63d6615f37e0b0fdedb84e7897ebb',1060955496,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060696295;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('b28b4004ca34b117b83dfb4758395414',1060955531,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060696331;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('b5dd59a0b1005fa11702b8bcb4669b07',1060955531,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060696331;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('0e68e64f637ced1e7e5553a0e906a102',1060955534,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060696334;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}'),('b5c8d672389b9ee186f17185b565a2d3',1060955534,'eZUserLoggedInID|N;eZUserInfoCache_Timestamp|i:1060696334;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsession ENABLE KEYS */;

--
-- Dumping data for table 'ezsubtree_notification_rule'
--

/*!40000 ALTER TABLE ezsubtree_notification_rule DISABLE KEYS */;
LOCK TABLES ezsubtree_notification_rule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezsubtree_notification_rule ENABLE KEYS */;

--
-- Dumping data for table 'eztrigger'
--

/*!40000 ALTER TABLE eztrigger DISABLE KEYS */;
LOCK TABLES eztrigger WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE eztrigger ENABLE KEYS */;

--
-- Dumping data for table 'ezurl'
--

/*!40000 ALTER TABLE ezurl DISABLE KEYS */;
LOCK TABLES ezurl WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezurl ENABLE KEYS */;

--
-- Dumping data for table 'ezurl_object_link'
--

/*!40000 ALTER TABLE ezurl_object_link DISABLE KEYS */;
LOCK TABLES ezurl_object_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezurl_object_link ENABLE KEYS */;

--
-- Dumping data for table 'ezurlalias'
--

/*!40000 ALTER TABLE ezurlalias DISABLE KEYS */;
LOCK TABLES ezurlalias WRITE;
INSERT INTO ezurlalias VALUES (1,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezurlalias ENABLE KEYS */;

--
-- Dumping data for table 'ezuser'
--

/*!40000 ALTER TABLE ezuser DISABLE KEYS */;
LOCK TABLES ezuser WRITE;
INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(40,'test','test@test.com',2,'be778b473235e210cc577056226536a4');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_accountkey'
--

/*!40000 ALTER TABLE ezuser_accountkey DISABLE KEYS */;
LOCK TABLES ezuser_accountkey WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_accountkey ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_discountrule'
--

/*!40000 ALTER TABLE ezuser_discountrule DISABLE KEYS */;
LOCK TABLES ezuser_discountrule WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_discountrule ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_role'
--

/*!40000 ALTER TABLE ezuser_role DISABLE KEYS */;
LOCK TABLES ezuser_role WRITE;
INSERT INTO ezuser_role VALUES (29,1,10),(25,2,12),(30,3,13),(28,1,11);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_role ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_setting'
--

/*!40000 ALTER TABLE ezuser_setting DISABLE KEYS */;
LOCK TABLES ezuser_setting WRITE;
INSERT INTO ezuser_setting VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezuser_setting ENABLE KEYS */;

--
-- Dumping data for table 'ezvattype'
--

/*!40000 ALTER TABLE ezvattype DISABLE KEYS */;
LOCK TABLES ezvattype WRITE;
INSERT INTO ezvattype VALUES (1,'Std',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezvattype ENABLE KEYS */;

--
-- Dumping data for table 'ezwaituntildatevalue'
--

/*!40000 ALTER TABLE ezwaituntildatevalue DISABLE KEYS */;
LOCK TABLES ezwaituntildatevalue WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezwaituntildatevalue ENABLE KEYS */;

--
-- Dumping data for table 'ezwishlist'
--

/*!40000 ALTER TABLE ezwishlist DISABLE KEYS */;
LOCK TABLES ezwishlist WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezwishlist ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow'
--

/*!40000 ALTER TABLE ezworkflow DISABLE KEYS */;
LOCK TABLES ezworkflow WRITE;
INSERT INTO ezworkflow VALUES (1,0,0,'group_ezserial','Sp\'s forkflow',8,24,1031927869,1032856662);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_assign'
--

/*!40000 ALTER TABLE ezworkflow_assign DISABLE KEYS */;
LOCK TABLES ezworkflow_assign WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_assign ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_event'
--

/*!40000 ALTER TABLE ezworkflow_event DISABLE KEYS */;
LOCK TABLES ezworkflow_event WRITE;
INSERT INTO ezworkflow_event VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1),(20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_event ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_group'
--

/*!40000 ALTER TABLE ezworkflow_group DISABLE KEYS */;
LOCK TABLES ezworkflow_group WRITE;
INSERT INTO ezworkflow_group VALUES (1,'Standard',-1,-1,1024392098,1024392098);
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_group ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_group_link'
--

/*!40000 ALTER TABLE ezworkflow_group_link DISABLE KEYS */;
LOCK TABLES ezworkflow_group_link WRITE;
INSERT INTO ezworkflow_group_link VALUES (1,1,0,'Standard');
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_group_link ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_process'
--

/*!40000 ALTER TABLE ezworkflow_process DISABLE KEYS */;
LOCK TABLES ezworkflow_process WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE ezworkflow_process ENABLE KEYS */;

