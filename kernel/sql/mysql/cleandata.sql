-- MySQL dump 8.22
--
-- Host: localhost    Database: demonew
---------------------------------------------------------
-- Server version	3.23.54

--
-- Dumping data for table 'ezapprove_items'
--



--
-- Dumping data for table 'ezbasket'
--



--
-- Dumping data for table 'ezbinaryfile'
--



--
-- Dumping data for table 'ezcollab_group'
--



--
-- Dumping data for table 'ezcollab_item'
--



--
-- Dumping data for table 'ezcollab_item_group_link'
--



--
-- Dumping data for table 'ezcollab_item_message_link'
--



--
-- Dumping data for table 'ezcollab_item_participant_link'
--



--
-- Dumping data for table 'ezcollab_item_status'
--



--
-- Dumping data for table 'ezcollab_profile'
--



--
-- Dumping data for table 'ezcollab_simple_message'
--



--
-- Dumping data for table 'ezcontent_translation'
--



--
-- Dumping data for table 'ezcontentclass'
--


INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1048494694);
INSERT INTO ezcontentclass VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1048494722);
INSERT INTO ezcontentclass VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1048494743);
INSERT INTO ezcontentclass VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1048494759);
INSERT INTO ezcontentclass VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048494784);
INSERT INTO ezcontentclass VALUES (6,0,'Forum','forum','<name>',14,14,1052384723,1052384870);
INSERT INTO ezcontentclass VALUES (7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943);
INSERT INTO ezcontentclass VALUES (8,0,'Product','product','<title>',14,14,1052384951,1052385067);
INSERT INTO ezcontentclass VALUES (9,0,'Product review','product_review','<title>',14,14,1052385080,1052385252);
INSERT INTO ezcontentclass VALUES (10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353);
INSERT INTO ezcontentclass VALUES (11,0,'Link','link','<title>',14,14,1052385361,1052385453);
INSERT INTO ezcontentclass VALUES (12,0,'File','file','<name>',14,14,1052385472,1052385669);
INSERT INTO ezcontentclass VALUES (13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756);

--
-- Dumping data for table 'ezcontentclass_attribute'
--


INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute VALUES (149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',0);

--
-- Dumping data for table 'ezcontentclass_classgroup'
--


INSERT INTO ezcontentclass_classgroup VALUES (1,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (2,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (4,0,2,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (5,0,3,'Media');
INSERT INTO ezcontentclass_classgroup VALUES (3,0,2,'');
INSERT INTO ezcontentclass_classgroup VALUES (6,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (9,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (10,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (11,0,1,'Content');
INSERT INTO ezcontentclass_classgroup VALUES (12,0,3,'Media');
INSERT INTO ezcontentclass_classgroup VALUES (13,0,1,'Content');

--
-- Dumping data for table 'ezcontentclassgroup'
--


INSERT INTO ezcontentclassgroup VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO ezcontentclassgroup VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO ezcontentclassgroup VALUES (3,'Media',8,14,1032009743,1033922120);

--
-- Dumping data for table 'ezcontentobject'
--


INSERT INTO ezcontentobject VALUES (1,0,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject VALUES (4,0,2,3,'Users',1,0,0,0,1,NULL);
INSERT INTO ezcontentobject VALUES (10,8,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL);
INSERT INTO ezcontentobject VALUES (11,8,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject VALUES (12,8,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject VALUES (13,8,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject VALUES (14,8,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL);

--
-- Dumping data for table 'ezcontentobject_attribute'
--


INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (7,'eng-GB',1,4,5,'Main group',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL);
INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',2,1,4,'My folder',0,0);
INSERT INTO ezcontentobject_attribute VALUES (2,'eng-GB',2,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0);
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

--
-- Dumping data for table 'ezcontentobject_link'
--



--
-- Dumping data for table 'ezcontentobject_name'
--


INSERT INTO ezcontentobject_name VALUES (1,'Root folder',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (4,'Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (10,'Anonymous User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (11,'Guest accounts',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (12,'Administrator users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (13,'Editors',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (14,'Administrator User',1,'eng-GB','eng-GB');

--
-- Dumping data for table 'ezcontentobject_tree'
--


INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,NULL,0,'/1/',1,1,0,NULL,1,NULL);
INSERT INTO ezcontentobject_tree VALUES (2,1,1,1,1,0,1,'/1/2/',1,1,0,'',2,'d41d8cd98f00b204e9800998ecf8427e');
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,0,-195235522,1,'/1/5/',1,1,0,'__1',5,'08a9d0bbf3381652f7cca8738b5a8469');
INSERT INTO ezcontentobject_tree VALUES (11,5,10,1,1,1015610524,2,'/1/5/11/',1,1,0,'__1/anonymous_user',11,'a59d2313b486e0f43477433525edea9b');
INSERT INTO ezcontentobject_tree VALUES (12,5,11,1,1,1857785444,2,'/1/5/12/',1,1,0,'__1/guest_accounts',12,'c894997127008ea742913062f39adfc5');
INSERT INTO ezcontentobject_tree VALUES (13,5,12,1,1,-1978139175,2,'/1/5/13/',1,1,0,'__1/administrator_users',13,'caeccbc33185f04d92e2b6cb83b1c7e4');
INSERT INTO ezcontentobject_tree VALUES (14,5,13,1,1,2094553782,2,'/1/5/14/',1,1,0,'__1/editors',14,'39f6f6f51c1e3a922600b2d415d7a46d');
INSERT INTO ezcontentobject_tree VALUES (15,13,14,1,1,-852704961,3,'/1/5/13/15/',1,1,0,'__1/administrator_users/administrator_user',15,'2c3f2814cfa91bcb17d7893ca6f8a0c4');

--
-- Dumping data for table 'ezcontentobject_version'
--


INSERT INTO ezcontentobject_version VALUES (1,1,0,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version VALUES (4,4,0,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version VALUES (436,1,8,2,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version VALUES (438,10,8,1,1033920649,1033920665,0,0,0);
INSERT INTO ezcontentobject_version VALUES (439,11,8,1,1033920737,1033920746,0,0,0);
INSERT INTO ezcontentobject_version VALUES (440,12,8,1,1033920760,1033920775,0,0,0);
INSERT INTO ezcontentobject_version VALUES (441,13,8,1,1033920786,1033920794,0,0,0);
INSERT INTO ezcontentobject_version VALUES (442,14,8,1,1033920808,1033920830,0,0,0);

--
-- Dumping data for table 'ezdiscountrule'
--



--
-- Dumping data for table 'ezdiscountsubrule'
--



--
-- Dumping data for table 'ezdiscountsubrule_value'
--



--
-- Dumping data for table 'ezenumobjectvalue'
--



--
-- Dumping data for table 'ezenumvalue'
--


INSERT INTO ezenumvalue VALUES (2,136,0,'Ok','3',2);
INSERT INTO ezenumvalue VALUES (1,136,0,'Poor','2',1);
INSERT INTO ezenumvalue VALUES (3,136,0,'Good','5',3);

--
-- Dumping data for table 'ezforgot_password'
--



--
-- Dumping data for table 'ezimage'
--



--
-- Dumping data for table 'ezimagevariation'
--



--
-- Dumping data for table 'ezinfocollection'
--



--
-- Dumping data for table 'ezinfocollection_attribute'
--



--
-- Dumping data for table 'ezmedia'
--



--
-- Dumping data for table 'ezmessage'
--



--
-- Dumping data for table 'ezmodule_run'
--



--
-- Dumping data for table 'eznode_assignment'
--


INSERT INTO eznode_assignment VALUES (2,1,1,1,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (3,4,2,1,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (4,8,2,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (144,4,4,1,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (147,210,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (146,209,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (145,1,2,1,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (148,9,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (149,10,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (150,11,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (151,12,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (152,13,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment VALUES (153,14,1,13,1,1,1,0,0);

--
-- Dumping data for table 'ezoperation_memento'
--



--
-- Dumping data for table 'ezorder'
--



--
-- Dumping data for table 'ezorder_item'
--



--
-- Dumping data for table 'ezpolicy'
--


INSERT INTO ezpolicy VALUES (317,3,'*','content','*');
INSERT INTO ezpolicy VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy VALUES (315,1,'read','content','');
INSERT INTO ezpolicy VALUES (316,1,'login','user','*');
INSERT INTO ezpolicy VALUES (319,3,'login','user','*');

--
-- Dumping data for table 'ezpolicy_limitation'
--


INSERT INTO ezpolicy_limitation VALUES (245,315,'Class',0,'read','content');

--
-- Dumping data for table 'ezpolicy_limitation_value'
--


INSERT INTO ezpolicy_limitation_value VALUES (409,245,1);
INSERT INTO ezpolicy_limitation_value VALUES (410,245,7);

--
-- Dumping data for table 'ezproductcollection'
--


INSERT INTO ezproductcollection VALUES (1,NULL);
INSERT INTO ezproductcollection VALUES (2,NULL);

--
-- Dumping data for table 'ezproductcollection_item'
--



--
-- Dumping data for table 'ezproductcollection_item_opt'
--



--
-- Dumping data for table 'ezrole'
--


INSERT INTO ezrole VALUES (1,0,'Anonymous','');
INSERT INTO ezrole VALUES (2,0,'Administrator','*');
INSERT INTO ezrole VALUES (3,0,'Editor','');

--
-- Dumping data for table 'ezsearch_object_word_link'
--



--
-- Dumping data for table 'ezsearch_return_count'
--



--
-- Dumping data for table 'ezsearch_search_phrase'
--



--
-- Dumping data for table 'ezsearch_word'
--



--
-- Dumping data for table 'ezsection'
--


INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO ezsection VALUES (2,'Users','','ezusernavigationpart');

--
-- Dumping data for table 'ezsession'
--


INSERT INTO ezsession VALUES ('c7d350aba981f1a0d7df38f30eced9d0',1052644957,'eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserInfoCache_Timestamp|i:1052384612;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}eZUserGroupsCache_Timestamp|i:1052384612;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1052384612;eZUserDiscountRules10|a:0:{}eZUserDiscountRulesTimestamp|i:1052384612;eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}userLimitations|a:1:{i:315;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"245\";s:9:\"policy_id\";s:3:\"315\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:245;a:2:{i:0;a:3:{s:2:\"id\";s:3:\"409\";s:13:\"limitation_id\";s:3:\"245\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"410\";s:13:\"limitation_id\";s:3:\"245\";s:5:\"value\";s:1:\"7\";}}}eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserDiscountRules14|a:0:{}canInstantiateClassesCachedForUser|s:2:\"14\";canInstantiateClasses|i:1;classesCachedForUser|s:2:\"14\";classesCachedTimestamp|i:1052384613;canInstantiateClassList|a:5:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}FromGroupID|s:1:\"1\";');
INSERT INTO ezsession VALUES ('c33c49ace0791376c1e120ffcf666cad',1053774117,'eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserInfoCache_Timestamp|i:1053514897;eZUserGroupsCache_10|a:1:{i:0;a:1:{s:2:\"id\";s:1:\"4\";}}eZUserGroupsCache_Timestamp|i:1053514897;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:9:\"Anonymous\";}}PermissionCachedForUserID|s:2:\"10\";PermissionCachedForUserIDTimestamp|i:1053514897;eZUserDiscountRules10|a:0:{}eZUserDiscountRulesTimestamp|i:1053514898;eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}eZUserLoggedInID|N;UserPolicies|a:1:{i:1;a:2:{i:0;a:5:{s:2:\"id\";s:3:\"315\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";s:10:\"limitation\";s:0:\"\";}i:1;a:5:{s:2:\"id\";s:3:\"316\";s:7:\"role_id\";s:1:\"1\";s:11:\"module_name\";s:4:\"user\";s:13:\"function_name\";s:5:\"login\";s:10:\"limitation\";s:1:\"*\";}}}userLimitations|a:1:{i:315;a:1:{i:0;a:6:{s:2:\"id\";s:3:\"245\";s:9:\"policy_id\";s:3:\"315\";s:10:\"identifier\";s:5:\"Class\";s:7:\"role_id\";s:1:\"0\";s:11:\"module_name\";s:7:\"content\";s:13:\"function_name\";s:4:\"read\";}}}userLimitationValues|a:1:{i:245;a:2:{i:0;a:3:{s:2:\"id\";s:3:\"409\";s:13:\"limitation_id\";s:3:\"245\";s:5:\"value\";s:1:\"1\";}i:1;a:3:{s:2:\"id\";s:3:\"410\";s:13:\"limitation_id\";s:3:\"245\";s:5:\"value\";s:1:\"7\";}}}');
INSERT INTO ezsession VALUES ('826d99a378e756483fbe9b8cb42eb2ac',1053774234,'eZUserInfoCache_10|a:5:{s:16:\"contentobject_id\";s:2:\"10\";s:5:\"login\";s:9:\"anonymous\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"4e6f6184135228ccd45f8233d72a0363\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserInfoCache_Timestamp|i:1053514934;eZUserLoggedInID|s:2:\"14\";eZUserInfoCache_14|a:5:{s:16:\"contentobject_id\";s:2:\"14\";s:5:\"login\";s:5:\"admin\";s:5:\"email\";s:12:\"nospam@ez.no\";s:13:\"password_hash\";s:32:\"c78e3b0f3d9244ed8c6d1c29464bdff9\";s:18:\"password_hash_type\";s:1:\"2\";}eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:\"id\";s:2:\"12\";}}eZUserGroupsCache_Timestamp|i:1053514934;UserRoles|a:1:{i:0;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:13:\"Administrator\";}}PermissionCachedForUserID|s:2:\"14\";PermissionCachedForUserIDTimestamp|i:1053514934;eZUserDiscountRules14|a:0:{}eZUserDiscountRulesTimestamp|i:1053514934;eZGlobalSection|a:1:{s:2:\"id\";s:1:\"1\";}canInstantiateClassesCachedForUser|s:2:\"14\";canInstantiateClasses|i:1;canInstantiateClassList|a:13:{i:0;a:2:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:6:\"Folder\";}i:1;a:2:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:7:\"Article\";}i:2;a:2:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:10:\"User group\";}i:3;a:2:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:4:\"User\";}i:4;a:2:{s:2:\"id\";s:1:\"5\";s:4:\"name\";s:5:\"Image\";}i:5;a:2:{s:2:\"id\";s:1:\"6\";s:4:\"name\";s:5:\"Forum\";}i:6;a:2:{s:2:\"id\";s:1:\"7\";s:4:\"name\";s:13:\"Forum message\";}i:7;a:2:{s:2:\"id\";s:1:\"8\";s:4:\"name\";s:7:\"Product\";}i:8;a:2:{s:2:\"id\";s:1:\"9\";s:4:\"name\";s:14:\"Product review\";}i:9;a:2:{s:2:\"id\";s:2:\"10\";s:4:\"name\";s:9:\"Info page\";}i:10;a:2:{s:2:\"id\";s:2:\"11\";s:4:\"name\";s:4:\"Link\";}i:11;a:2:{s:2:\"id\";s:2:\"12\";s:4:\"name\";s:4:\"File\";}i:12;a:2:{s:2:\"id\";s:2:\"13\";s:4:\"name\";s:7:\"Comment\";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:\"id\";s:3:\"308\";s:7:\"role_id\";s:1:\"2\";s:11:\"module_name\";s:1:\"*\";s:13:\"function_name\";s:1:\"*\";s:10:\"limitation\";s:1:\"*\";}}}classesCachedTimestamp|N;FromGroupID|s:1:\"1\";eZTemplateAdminCurrentSiteAccess|s:5:\"admin\";LastAccessesURI|s:41:\"/setup/templateview/content/view/full.tpl\";');

--
-- Dumping data for table 'eztrigger'
--



--
-- Dumping data for table 'ezurl'
--



--
-- Dumping data for table 'ezuser'
--


INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');

--
-- Dumping data for table 'ezuser_accountkey'
--



--
-- Dumping data for table 'ezuser_discountrule'
--



--
-- Dumping data for table 'ezuser_role'
--


INSERT INTO ezuser_role VALUES (24,1,4);
INSERT INTO ezuser_role VALUES (25,2,12);

--
-- Dumping data for table 'ezuser_setting'
--


INSERT INTO ezuser_setting VALUES (10,1,1000);
INSERT INTO ezuser_setting VALUES (14,1,10);

--
-- Dumping data for table 'ezvattype'
--


INSERT INTO ezvattype VALUES (1,'Std',0);

--
-- Dumping data for table 'ezwaituntildatevalue'
--



--
-- Dumping data for table 'ezwishlist'
--



--
-- Dumping data for table 'ezworkflow'
--


INSERT INTO ezworkflow VALUES (1,0,0,'group_ezserial','Sp\'s forkflow',8,24,1031927869,1032856662);

--
-- Dumping data for table 'ezworkflow_assign'
--



--
-- Dumping data for table 'ezworkflow_event'
--


INSERT INTO ezworkflow_event VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO ezworkflow_event VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);

--
-- Dumping data for table 'ezworkflow_group'
--


INSERT INTO ezworkflow_group VALUES (1,'Standard',-1,-1,1024392098,1024392098);

--
-- Dumping data for table 'ezworkflow_group_link'
--


INSERT INTO ezworkflow_group_link VALUES (1,1,0,'Standard');

--
-- Dumping data for table 'ezworkflow_process'
--



