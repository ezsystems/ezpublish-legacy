-- MySQL dump 10.2
--
-- Host: localhost    Database: nextgen
---------------------------------------------------------
-- Server version	4.1.0-alpha

--
-- Dumping data for table 'ezapprove_items'
--

/*!40000 ALTER TABLE `ezapprove_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezapprove_items` ENABLE KEYS */;

--
-- Dumping data for table 'ezbasket'
--

/*!40000 ALTER TABLE `ezbasket` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezbasket` ENABLE KEYS */;

--
-- Dumping data for table 'ezbinaryfile'
--

/*!40000 ALTER TABLE `ezbinaryfile` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezbinaryfile` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_group'
--

/*!40000 ALTER TABLE `ezcollab_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_group` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item'
--

/*!40000 ALTER TABLE `ezcollab_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_item` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_group_link'
--

/*!40000 ALTER TABLE `ezcollab_item_group_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_item_group_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_message_link'
--

/*!40000 ALTER TABLE `ezcollab_item_message_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_item_message_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_participant_link'
--

/*!40000 ALTER TABLE `ezcollab_item_participant_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_item_participant_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_item_status'
--

/*!40000 ALTER TABLE `ezcollab_item_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_item_status` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_notification_rule'
--

/*!40000 ALTER TABLE `ezcollab_notification_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_notification_rule` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_profile'
--

/*!40000 ALTER TABLE `ezcollab_profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_profile` ENABLE KEYS */;

--
-- Dumping data for table 'ezcollab_simple_message'
--

/*!40000 ALTER TABLE `ezcollab_simple_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcollab_simple_message` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontent_translation'
--

/*!40000 ALTER TABLE `ezcontent_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcontent_translation` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentbrowsebookmark'
--

/*!40000 ALTER TABLE `ezcontentbrowsebookmark` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcontentbrowsebookmark` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentbrowserecent'
--

/*!40000 ALTER TABLE `ezcontentbrowserecent` DISABLE KEYS */;
INSERT INTO `ezcontentbrowserecent` (`id`, `user_id`, `node_id`, `created`, `name`) VALUES (1,14,2,1060695457,'Root folder');
/*!40000 ALTER TABLE `ezcontentbrowserecent` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclass'
--

/*!40000 ALTER TABLE `ezcontentclass` DISABLE KEYS */;
INSERT INTO `ezcontentclass` (`id`, `version`, `name`, `identifier`, `contentobject_name`, `creator_id`, `modifier_id`, `created`, `modified`) VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694),(2,0,'Article','article','<title>',14,14,1024392098,1048494722),(3,0,'User group','user_group','<name>',14,14,1024392098,1048494743),(4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1048494759),(5,0,'Image','image','<name>',8,14,1031484992,1048494784),(6,0,'Forum','forum','<name>',14,14,1052384723,1052384870),(7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943),(8,0,'Product','product','<title>',14,14,1052384951,1052385067),(9,0,'Product review','product_review','<title>',14,14,1052385080,1052385252),(10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353),(11,0,'Link','link','<title>',14,14,1052385361,1052385453),(12,0,'File','file','<name>',14,14,1052385472,1052385669),(13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756);
/*!40000 ALTER TABLE `ezcontentclass` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclass_attribute'
--

/*!40000 ALTER TABLE `ezcontentclass_attribute` DISABLE KEYS */;
INSERT INTO `ezcontentclass_attribute` (`id`, `version`, `contentclass_id`, `identifier`, `name`, `data_type_string`, `is_searchable`, `is_required`, `placement`, `data_int1`, `data_int2`, `data_int3`, `data_int4`, `data_float1`, `data_float2`, `data_float3`, `data_float4`, `data_text1`, `data_text2`, `data_text3`, `data_text4`, `data_text5`, `is_information_collector`, `can_translate`) VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1),(122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',NULL,0,1),(121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',NULL,0,1),(117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1),(127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1),(128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1),(125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1),(134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1),(132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1),(129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',NULL,0,1),(142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1),(141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1),(140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1),(148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1),(151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1),(150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1),(149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1);
/*!40000 ALTER TABLE `ezcontentclass_attribute` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclass_classgroup'
--

/*!40000 ALTER TABLE `ezcontentclass_classgroup` DISABLE KEYS */;
INSERT INTO `ezcontentclass_classgroup` (`contentclass_id`, `contentclass_version`, `group_id`, `group_name`) VALUES (1,0,1,'Content'),(2,0,1,'Content'),(4,0,2,'Content'),(5,0,3,'Media'),(3,0,2,''),(6,0,1,'Content'),(7,0,1,'Content'),(8,0,1,'Content'),(9,0,1,'Content'),(10,0,1,'Content'),(11,0,1,'Content'),(12,0,3,'Media'),(13,0,1,'Content');
/*!40000 ALTER TABLE `ezcontentclass_classgroup` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentclassgroup'
--

/*!40000 ALTER TABLE `ezcontentclassgroup` DISABLE KEYS */;
INSERT INTO `ezcontentclassgroup` (`id`, `name`, `creator_id`, `modifier_id`, `created`, `modified`) VALUES (1,'Content',1,14,1031216928,1033922106),(2,'Users',1,14,1031216941,1033922113),(3,'Media',8,14,1032009743,1033922120);
/*!40000 ALTER TABLE `ezcontentclassgroup` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject'
--

/*!40000 ALTER TABLE `ezcontentobject` DISABLE KEYS */;
INSERT INTO `ezcontentobject` (`id`, `owner_id`, `section_id`, `contentclass_id`, `name`, `current_version`, `is_published`, `published`, `modified`, `status`, `remote_id`) VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL),(4,14,2,3,'Users',1,0,0,0,1,NULL),(10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL),(11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL),(12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL),(13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL),(14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL),(40,14,2,4,'test test',1,0,1053613020,1053613020,1,''),(41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');
/*!40000 ALTER TABLE `ezcontentobject` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_attribute'
--

/*!40000 ALTER TABLE `ezcontentobject_attribute` DISABLE KEYS */;
INSERT INTO `ezcontentobject_attribute` (`id`, `language_code`, `version`, `contentobject_id`, `contentclassattribute_id`, `data_text`, `data_int`, `data_float`, `attribute_original_id`, `sort_key_int`, `sort_key_string`) VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL,0,0,''),(2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,''),(7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,''),(8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,''),(1,'eng-GB',2,1,4,'My folder',0,0,0,0,''),(2,'eng-GB',2,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0,0,0,''),(21,'eng-GB',1,10,12,'',0,0,0,0,''),(22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,''),(19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,''),(20,'eng-GB',1,10,9,'User',0,0,0,0,''),(23,'eng-GB',1,11,7,'',0,0,0,0,''),(24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,''),(25,'eng-GB',1,12,7,'',0,0,0,0,''),(26,'eng-GB',1,13,6,'Editors',0,0,0,0,''),(27,'eng-GB',1,13,7,'',0,0,0,0,''),(28,'eng-GB',1,14,8,'Administrator',0,0,0,0,''),(29,'eng-GB',1,14,9,'User',0,0,0,0,''),(30,'eng-GB',1,14,12,'',0,0,0,0,''),(95,'eng-GB',1,40,8,'test',0,0,0,0,''),(96,'eng-GB',1,40,9,'test',0,0,0,0,''),(97,'eng-GB',1,40,12,'',0,0,0,0,''),(98,'eng-GB',1,41,4,'Media',0,0,0,0,''),(99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'');
/*!40000 ALTER TABLE `ezcontentobject_attribute` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_link'
--

/*!40000 ALTER TABLE `ezcontentobject_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezcontentobject_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_name'
--

/*!40000 ALTER TABLE `ezcontentobject_name` DISABLE KEYS */;
INSERT INTO `ezcontentobject_name` (`contentobject_id`, `name`, `content_version`, `content_translation`, `real_translation`) VALUES (1,'Root folder',1,'eng-GB','eng-GB'),(4,'Users',1,'eng-GB','eng-GB'),(10,'Anonymous User',1,'eng-GB','eng-GB'),(11,'Guest accounts',1,'eng-GB','eng-GB'),(12,'Administrator users',1,'eng-GB','eng-GB'),(13,'Editors',1,'eng-GB','eng-GB'),(14,'Administrator User',1,'eng-GB','eng-GB'),(40,'test test',1,'eng-GB','eng-GB'),(41,'Media',1,'eng-GB','eng-GB');
/*!40000 ALTER TABLE `ezcontentobject_name` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_tree'
--

/*!40000 ALTER TABLE `ezcontentobject_tree` DISABLE KEYS */;
INSERT INTO `ezcontentobject_tree` (`node_id`, `parent_node_id`, `contentobject_id`, `contentobject_version`, `contentobject_is_published`, `depth`, `path_string`, `sort_field`, `sort_order`, `priority`, `path_identification_string`, `main_node_id`) VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1),(2,1,1,1,1,1,'/1/2/',1,1,0,'',2),(5,1,4,1,0,1,'/1/5/',1,1,0,'users',5),(11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11),(12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12),(13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13),(14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14),(15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15),(42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42),(43,1,41,1,1,1,'/1/43/',9,1,0,'media',43);
/*!40000 ALTER TABLE `ezcontentobject_tree` ENABLE KEYS */;

--
-- Dumping data for table 'ezcontentobject_version'
--

/*!40000 ALTER TABLE `ezcontentobject_version` DISABLE KEYS */;
INSERT INTO `ezcontentobject_version` (`id`, `contentobject_id`, `creator_id`, `version`, `created`, `modified`, `status`, `workflow_event_pos`, `user_id`) VALUES (1,1,14,1,0,0,1,1,0),(4,4,14,1,0,0,1,1,0),(436,1,14,2,1033919080,1033919080,1,1,0),(438,10,14,1,1033920649,1033920665,1,0,0),(439,11,14,1,1033920737,1033920746,1,0,0),(440,12,14,1,1033920760,1033920775,1,0,0),(441,13,14,1,1033920786,1033920794,1,0,0),(442,14,14,1,1033920808,1033920830,1,0,0),(471,40,14,1,1053613007,1053613020,1,0,0),(472,41,14,1,1060695450,1060695457,1,0,0);
/*!40000 ALTER TABLE `ezcontentobject_version` ENABLE KEYS */;

--
-- Dumping data for table 'ezdiscountrule'
--

/*!40000 ALTER TABLE `ezdiscountrule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezdiscountrule` ENABLE KEYS */;

--
-- Dumping data for table 'ezdiscountsubrule'
--

/*!40000 ALTER TABLE `ezdiscountsubrule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezdiscountsubrule` ENABLE KEYS */;

--
-- Dumping data for table 'ezdiscountsubrule_value'
--

/*!40000 ALTER TABLE `ezdiscountsubrule_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezdiscountsubrule_value` ENABLE KEYS */;

--
-- Dumping data for table 'ezenumobjectvalue'
--

/*!40000 ALTER TABLE `ezenumobjectvalue` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezenumobjectvalue` ENABLE KEYS */;

--
-- Dumping data for table 'ezenumvalue'
--

/*!40000 ALTER TABLE `ezenumvalue` DISABLE KEYS */;
INSERT INTO `ezenumvalue` (`id`, `contentclass_attribute_id`, `contentclass_attribute_version`, `enumelement`, `enumvalue`, `placement`) VALUES (2,136,0,'Ok','3',2),(1,136,0,'Poor','2',1),(3,136,0,'Good','5',3);
/*!40000 ALTER TABLE `ezenumvalue` ENABLE KEYS */;

--
-- Dumping data for table 'ezforgot_password'
--

/*!40000 ALTER TABLE `ezforgot_password` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezforgot_password` ENABLE KEYS */;

--
-- Dumping data for table 'ezgeneral_digest_user_settings'
--

/*!40000 ALTER TABLE `ezgeneral_digest_user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezgeneral_digest_user_settings` ENABLE KEYS */;

--
-- Dumping data for table 'ezimage'
--

/*!40000 ALTER TABLE `ezimage` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezimage` ENABLE KEYS */;

--
-- Dumping data for table 'ezimagevariation'
--

/*!40000 ALTER TABLE `ezimagevariation` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezimagevariation` ENABLE KEYS */;

--
-- Dumping data for table 'ezinfocollection'
--

/*!40000 ALTER TABLE `ezinfocollection` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezinfocollection` ENABLE KEYS */;

--
-- Dumping data for table 'ezinfocollection_attribute'
--

/*!40000 ALTER TABLE `ezinfocollection_attribute` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezinfocollection_attribute` ENABLE KEYS */;

--
-- Dumping data for table 'ezkeyword'
--

/*!40000 ALTER TABLE `ezkeyword` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezkeyword` ENABLE KEYS */;

--
-- Dumping data for table 'ezkeyword_attribute_link'
--

/*!40000 ALTER TABLE `ezkeyword_attribute_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezkeyword_attribute_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezmedia'
--

/*!40000 ALTER TABLE `ezmedia` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezmedia` ENABLE KEYS */;

--
-- Dumping data for table 'ezmessage'
--

/*!40000 ALTER TABLE `ezmessage` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezmessage` ENABLE KEYS */;

--
-- Dumping data for table 'ezmodule_run'
--

/*!40000 ALTER TABLE `ezmodule_run` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezmodule_run` ENABLE KEYS */;

--
-- Dumping data for table 'eznode_assignment'
--

/*!40000 ALTER TABLE `eznode_assignment` DISABLE KEYS */;
INSERT INTO `eznode_assignment` (`id`, `contentobject_id`, `contentobject_version`, `parent_node`, `sort_field`, `sort_order`, `is_main`, `from_node_id`, `remote_id`) VALUES (2,1,1,1,1,1,1,0,0),(4,8,2,5,1,1,1,0,0),(144,4,1,1,1,1,1,0,0),(147,210,1,5,1,1,1,0,0),(146,209,1,5,1,1,1,0,0),(145,1,2,1,1,1,1,0,0),(148,9,1,2,1,1,1,0,0),(149,10,1,5,1,1,1,0,0),(150,11,1,5,1,1,1,0,0),(151,12,1,5,1,1,1,0,0),(152,13,1,5,1,1,1,0,0),(153,14,1,13,1,1,1,0,0),(181,40,1,12,9,1,1,0,0),(182,41,1,1,9,1,1,0,0);
/*!40000 ALTER TABLE `eznode_assignment` ENABLE KEYS */;

--
-- Dumping data for table 'eznotificationcollection'
--

/*!40000 ALTER TABLE `eznotificationcollection` DISABLE KEYS */;
/*!40000 ALTER TABLE `eznotificationcollection` ENABLE KEYS */;

--
-- Dumping data for table 'eznotificationcollection_item'
--

/*!40000 ALTER TABLE `eznotificationcollection_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `eznotificationcollection_item` ENABLE KEYS */;

--
-- Dumping data for table 'eznotificationevent'
--

/*!40000 ALTER TABLE `eznotificationevent` DISABLE KEYS */;
INSERT INTO `eznotificationevent` (`id`, `status`, `event_type_string`, `data_int1`, `data_int2`, `data_int3`, `data_int4`, `data_text1`, `data_text2`, `data_text3`, `data_text4`) VALUES (1,0,'ezpublish',41,1,0,0,'','','','');
/*!40000 ALTER TABLE `eznotificationevent` ENABLE KEYS */;

--
-- Dumping data for table 'ezoperation_memento'
--

/*!40000 ALTER TABLE `ezoperation_memento` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezoperation_memento` ENABLE KEYS */;

--
-- Dumping data for table 'ezorder'
--

/*!40000 ALTER TABLE `ezorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezorder` ENABLE KEYS */;

--
-- Dumping data for table 'ezorder_item'
--

/*!40000 ALTER TABLE `ezorder_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezorder_item` ENABLE KEYS */;

--
-- Dumping data for table 'ezpolicy'
--

/*!40000 ALTER TABLE `ezpolicy` DISABLE KEYS */;
INSERT INTO `ezpolicy` (`id`, `role_id`, `function_name`, `module_name`, `limitation`) VALUES (317,3,'*','content','*'),(308,2,'*','*','*'),(326,1,'read','content',''),(325,1,'login','user','*'),(319,3,'login','user','*'),(323,5,'*','content','*'),(324,5,'login','user','*');
/*!40000 ALTER TABLE `ezpolicy` ENABLE KEYS */;

--
-- Dumping data for table 'ezpolicy_limitation'
--

/*!40000 ALTER TABLE `ezpolicy_limitation` DISABLE KEYS */;
INSERT INTO `ezpolicy_limitation` (`id`, `policy_id`, `identifier`, `role_id`, `function_name`, `module_name`) VALUES (249,326,'Class',0,'read','content');
/*!40000 ALTER TABLE `ezpolicy_limitation` ENABLE KEYS */;

--
-- Dumping data for table 'ezpolicy_limitation_value'
--

/*!40000 ALTER TABLE `ezpolicy_limitation_value` DISABLE KEYS */;
INSERT INTO `ezpolicy_limitation_value` (`id`, `limitation_id`, `value`) VALUES (435,249,'1'),(436,249,'10'),(437,249,'10'),(438,249,'11'),(439,249,'11'),(440,249,'12'),(441,249,'12'),(442,249,'13'),(443,249,'13'),(444,249,'2'),(445,249,'2'),(446,249,'5'),(447,249,'5'),(448,249,'6'),(449,249,'6'),(450,249,'7'),(451,249,'7'),(452,249,'8'),(453,249,'8'),(454,249,'9'),(455,249,'9');
/*!40000 ALTER TABLE `ezpolicy_limitation_value` ENABLE KEYS */;

--
-- Dumping data for table 'ezpreferences'
--

/*!40000 ALTER TABLE `ezpreferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezpreferences` ENABLE KEYS */;

--
-- Dumping data for table 'ezproductcollection'
--

/*!40000 ALTER TABLE `ezproductcollection` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezproductcollection` ENABLE KEYS */;

--
-- Dumping data for table 'ezproductcollection_item'
--

/*!40000 ALTER TABLE `ezproductcollection_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezproductcollection_item` ENABLE KEYS */;

--
-- Dumping data for table 'ezproductcollection_item_opt'
--

/*!40000 ALTER TABLE `ezproductcollection_item_opt` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezproductcollection_item_opt` ENABLE KEYS */;

--
-- Dumping data for table 'ezrole'
--

/*!40000 ALTER TABLE `ezrole` DISABLE KEYS */;
INSERT INTO `ezrole` (`id`, `version`, `name`, `value`) VALUES (1,0,'Anonymous',''),(2,0,'Administrator','*'),(3,0,'Editor',''),(5,3,'Editor',NULL);
/*!40000 ALTER TABLE `ezrole` ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_object_word_link'
--

/*!40000 ALTER TABLE `ezsearch_object_word_link` DISABLE KEYS */;
INSERT INTO `ezsearch_object_word_link` (`id`, `contentobject_id`, `word_id`, `frequency`, `placement`, `prev_word_id`, `next_word_id`, `contentclass_id`, `published`, `section_id`, `contentclass_attribute_id`, `identifier`, `integer_value`) VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0),(27,40,5,0,1,5,0,4,1053613020,2,9,'',0),(28,41,6,0,0,0,0,1,1060695457,3,4,'',0);
/*!40000 ALTER TABLE `ezsearch_object_word_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_return_count'
--

/*!40000 ALTER TABLE `ezsearch_return_count` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezsearch_return_count` ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_search_phrase'
--

/*!40000 ALTER TABLE `ezsearch_search_phrase` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezsearch_search_phrase` ENABLE KEYS */;

--
-- Dumping data for table 'ezsearch_word'
--

/*!40000 ALTER TABLE `ezsearch_word` DISABLE KEYS */;
INSERT INTO `ezsearch_word` (`id`, `word`, `object_count`) VALUES (5,'test',1),(6,'media',1);
/*!40000 ALTER TABLE `ezsearch_word` ENABLE KEYS */;

--
-- Dumping data for table 'ezsection'
--

/*!40000 ALTER TABLE `ezsection` DISABLE KEYS */;
INSERT INTO `ezsection` (`id`, `name`, `locale`, `navigation_part_identifier`) VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart'),(2,'Users','','ezusernavigationpart'),(3,'Media','','ezmedianavigationpart');
/*!40000 ALTER TABLE `ezsection` ENABLE KEYS */;

--
-- Dumping data for table 'ezsession'
--

/*!40000 ALTER TABLE `ezsession` DISABLE KEYS */;
INSERT INTO `ezsession` (`session_key`, `expiration_time`, `data`) VALUES ('bc038fad359d92172ab11d5b1f1cb461',1061301451,'LastAccessesURI|s:21:\"/content/view/full/43\";eZUserInfoCache_Timestamp|i:1061041383;eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_Timestamp|i:1061041383;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1061041383;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}eZUserDiscountRulesTimestamp|i:1061041383;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:\"id\";s:1:\"3\";}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;canInstantiateClassesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1061041387;canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";canInstantiateClassList|a:13:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:1:\"6\";s:4:\"name\";s:5:\"Forum\";}i:6;a:2:{s:2:\"id\";s:1:\"7\";s:4:\"name\";s:13:\"Forum message\";}i:7;a:2:{s:2:\"id\";s:1:\"8\";s:4:\"name\";s:7:\"Product\";}i:8;a:2:{s:2:\"id\";s:1:\"9\";s:4:\"name\";s:14:\"Product review\";}i:9;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:10;a:2:{s:2:\"id\";s:2:\"11\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:12;a:2:{s:2:\"id\";s:2:\"13\";s:4:\"name\";s:7:\"Comment\";}}FromGroupID|s:0:\"\";');
/*!40000 ALTER TABLE `ezsession` ENABLE KEYS */;

--
-- Dumping data for table 'ezsubtree_notification_rule'
--

/*!40000 ALTER TABLE `ezsubtree_notification_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezsubtree_notification_rule` ENABLE KEYS */;

--
-- Dumping data for table 'eztrigger'
--

/*!40000 ALTER TABLE `eztrigger` DISABLE KEYS */;
/*!40000 ALTER TABLE `eztrigger` ENABLE KEYS */;

--
-- Dumping data for table 'ezurl'
--

/*!40000 ALTER TABLE `ezurl` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezurl` ENABLE KEYS */;

--
-- Dumping data for table 'ezurl_object_link'
--

/*!40000 ALTER TABLE `ezurl_object_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezurl_object_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezurlalias'
--

/*!40000 ALTER TABLE `ezurlalias` DISABLE KEYS */;
INSERT INTO `ezurlalias` (`id`, `source_url`, `source_md5`, `destination_url`, `is_internal`, `forward_to_id`) VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0),(13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0),(14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0),(15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0),(16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0),(17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0),(18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0),(19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0),(20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0);
/*!40000 ALTER TABLE `ezurlalias` ENABLE KEYS */;

--
-- Dumping data for table 'ezuser'
--

/*!40000 ALTER TABLE `ezuser` DISABLE KEYS */;
INSERT INTO `ezuser` (`contentobject_id`, `login`, `email`, `password_hash_type`, `password_hash`) VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363'),(14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9'),(40,'test','test@test.com',2,'be778b473235e210cc577056226536a4');
/*!40000 ALTER TABLE `ezuser` ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_accountkey'
--

/*!40000 ALTER TABLE `ezuser_accountkey` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezuser_accountkey` ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_discountrule'
--

/*!40000 ALTER TABLE `ezuser_discountrule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezuser_discountrule` ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_role'
--

/*!40000 ALTER TABLE `ezuser_role` DISABLE KEYS */;
INSERT INTO `ezuser_role` (`id`, `role_id`, `contentobject_id`) VALUES (29,1,10),(25,2,12),(30,3,13),(28,1,11);
/*!40000 ALTER TABLE `ezuser_role` ENABLE KEYS */;

--
-- Dumping data for table 'ezuser_setting'
--

/*!40000 ALTER TABLE `ezuser_setting` DISABLE KEYS */;
INSERT INTO `ezuser_setting` (`user_id`, `is_enabled`, `max_login`) VALUES (10,1,1000),(14,1,10),(23,1,0),(40,1,0);
/*!40000 ALTER TABLE `ezuser_setting` ENABLE KEYS */;

--
-- Dumping data for table 'ezvattype'
--

/*!40000 ALTER TABLE `ezvattype` DISABLE KEYS */;
INSERT INTO `ezvattype` (`id`, `name`, `percentage`) VALUES (1,'Std',0);
/*!40000 ALTER TABLE `ezvattype` ENABLE KEYS */;

--
-- Dumping data for table 'ezwaituntildatevalue'
--

/*!40000 ALTER TABLE `ezwaituntildatevalue` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezwaituntildatevalue` ENABLE KEYS */;

--
-- Dumping data for table 'ezwishlist'
--

/*!40000 ALTER TABLE `ezwishlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezwishlist` ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow'
--

/*!40000 ALTER TABLE `ezworkflow` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezworkflow` ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_assign'
--

/*!40000 ALTER TABLE `ezworkflow_assign` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezworkflow_assign` ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_event'
--

/*!40000 ALTER TABLE `ezworkflow_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezworkflow_event` ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_group'
--

/*!40000 ALTER TABLE `ezworkflow_group` DISABLE KEYS */;
INSERT INTO `ezworkflow_group` (`id`, `name`, `creator_id`, `modifier_id`, `created`, `modified`) VALUES (1,'Standard',14,14,1024392098,1024392098);
/*!40000 ALTER TABLE `ezworkflow_group` ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_group_link'
--

/*!40000 ALTER TABLE `ezworkflow_group_link` DISABLE KEYS */;
INSERT INTO `ezworkflow_group_link` (`workflow_id`, `group_id`, `workflow_version`, `group_name`) VALUES (1,1,0,'Standard');
/*!40000 ALTER TABLE `ezworkflow_group_link` ENABLE KEYS */;

--
-- Dumping data for table 'ezworkflow_process'
--

/*!40000 ALTER TABLE `ezworkflow_process` DISABLE KEYS */;
/*!40000 ALTER TABLE `ezworkflow_process` ENABLE KEYS */;

