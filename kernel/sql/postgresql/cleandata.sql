-- MySQL dump 8.22
--
-- Host: localhost    Database: bf
---------------------------------------------------------
-- Server version	3.23.54-log

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
-- Dumping data for table 'ezcollab_notification_rule'
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
-- Dumping data for table 'ezcontentbrowsebookmark'
--



--
-- Dumping data for table 'ezcontentbrowserecent'
--


INSERT INTO ezcontentbrowserecent VALUES (1,14,2,1060695457,'Root folder');

--
-- Dumping data for table 'ezcontentclass'
--


INSERT INTO ezcontentclass VALUES (1,0,'Folder','folder','<name>',14,14,1024392098,1048494694);
INSERT INTO ezcontentclass VALUES (2,0,'Article','article','<title>',14,14,1024392098,1048494722);
INSERT INTO ezcontentclass VALUES (3,0,'User group','user_group','<name>',14,14,1024392098,1048494743);
INSERT INTO ezcontentclass VALUES (4,0,'User','user','<first_name> <last_name>',14,14,1024392098,1048494759);
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


INSERT INTO ezcontentclass_attribute VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',NULL,0,1);
INSERT INTO ezcontentclass_attribute VALUES (149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',NULL,0,1);

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


INSERT INTO ezcontentobject VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject VALUES (4,14,2,3,'Users',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject VALUES (10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL);
INSERT INTO ezcontentobject VALUES (11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject VALUES (12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject VALUES (13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject VALUES (14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL);
INSERT INTO ezcontentobject VALUES (40,14,2,4,'test test',1,0,1053613020,1053613020,1,'');
INSERT INTO ezcontentobject VALUES (41,14,3,1,'Media',1,0,1060695457,1060695457,1,'');

--
-- Dumping data for table 'ezcontentobject_attribute'
--


INSERT INTO ezcontentobject_attribute VALUES (1,'eng-GB',1,1,4,'Root folder',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL,0,0,'','ezxmltext');
INSERT INTO ezcontentobject_attribute VALUES (7,'eng-GB',1,4,7,'Main group',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (21,'eng-GB',1,10,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (19,'eng-GB',1,10,8,'Anonymous',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (20,'eng-GB',1,10,9,'User',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (23,'eng-GB',1,11,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (25,'eng-GB',1,12,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (26,'eng-GB',1,13,6,'Editors',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (27,'eng-GB',1,13,7,'',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (28,'eng-GB',1,14,8,'Administrator',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (29,'eng-GB',1,14,9,'User',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (30,'eng-GB',1,14,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute VALUES (95,'eng-GB',1,40,8,'test',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (96,'eng-GB',1,40,9,'test',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (97,'eng-GB',1,40,12,'',0,0,0,0,'','ezuser');
INSERT INTO ezcontentobject_attribute VALUES (98,'eng-GB',1,41,4,'Media',0,0,0,0,'','ezstring');
INSERT INTO ezcontentobject_attribute VALUES (99,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />',1045487555,0,0,0,'','ezxmltext');

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
INSERT INTO ezcontentobject_name VALUES (40,'test test',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name VALUES (41,'Media',1,'eng-GB','eng-GB');

--
-- Dumping data for table 'ezcontentobject_tree'
--


INSERT INTO ezcontentobject_tree VALUES (1,1,0,1,1,0,'/1/',1,1,0,NULL,1);
INSERT INTO ezcontentobject_tree VALUES (2,1,1,1,1,1,'/1/2/',1,1,0,'',2);
INSERT INTO ezcontentobject_tree VALUES (5,1,4,1,0,1,'/1/5/',1,1,0,'users',5);
INSERT INTO ezcontentobject_tree VALUES (11,5,10,1,1,2,'/1/5/11/',1,1,0,'users/anonymous_user',11);
INSERT INTO ezcontentobject_tree VALUES (12,5,11,1,1,2,'/1/5/12/',1,1,0,'users/guest_accounts',12);
INSERT INTO ezcontentobject_tree VALUES (13,5,12,1,1,2,'/1/5/13/',1,1,0,'users/administrator_users',13);
INSERT INTO ezcontentobject_tree VALUES (14,5,13,1,1,2,'/1/5/14/',1,1,0,'users/editors',14);
INSERT INTO ezcontentobject_tree VALUES (15,13,14,1,1,3,'/1/5/13/15/',1,1,0,'users/administrator_users/administrator_user',15);
INSERT INTO ezcontentobject_tree VALUES (42,12,40,1,1,3,'/1/5/12/42/',9,1,0,'users/guest_accounts/test_test',42);
INSERT INTO ezcontentobject_tree VALUES (43,1,41,1,1,1,'/1/43/',9,1,0,'media',43);

--
-- Dumping data for table 'ezcontentobject_version'
--


INSERT INTO ezcontentobject_version VALUES (1,1,14,1,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version VALUES (4,4,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version VALUES (438,10,14,1,1033920649,1033920665,1,0,0);
INSERT INTO ezcontentobject_version VALUES (439,11,14,1,1033920737,1033920746,1,0,0);
INSERT INTO ezcontentobject_version VALUES (440,12,14,1,1033920760,1033920775,1,0,0);
INSERT INTO ezcontentobject_version VALUES (441,13,14,1,1033920786,1033920794,1,0,0);
INSERT INTO ezcontentobject_version VALUES (442,14,14,1,1033920808,1033920830,1,0,0);
INSERT INTO ezcontentobject_version VALUES (471,40,14,1,1053613007,1053613020,1,0,0);
INSERT INTO ezcontentobject_version VALUES (472,41,14,1,1060695450,1060695457,1,0,0);

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
-- Dumping data for table 'ezgeneral_digest_user_settings'
--



--
-- Dumping data for table 'ezimage'
--



--
-- Dumping data for table 'ezimagefile'
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
-- Dumping data for table 'ezkeyword'
--



--
-- Dumping data for table 'ezkeyword_attribute_link'
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
INSERT INTO eznode_assignment VALUES (4,8,2,5,1,1,1,0,0);

--
-- Dumping data for table 'eznotificationcollection'
--



--
-- Dumping data for table 'eznotificationcollection_item'
--



--
-- Dumping data for table 'eznotificationevent'
--


INSERT INTO eznotificationevent VALUES (1,0,'ezpublish',41,1,0,0,'','','','');

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
-- Dumping data for table 'ezpdf_export'
--



--
-- Dumping data for table 'ezpolicy'
--


INSERT INTO ezpolicy VALUES (317,3,'*','content','*');
INSERT INTO ezpolicy VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy VALUES (326,1,'read','content','');
INSERT INTO ezpolicy VALUES (325,1,'login','user','*');
INSERT INTO ezpolicy VALUES (319,3,'login','user','*');
INSERT INTO ezpolicy VALUES (323,5,'*','content','*');
INSERT INTO ezpolicy VALUES (324,5,'login','user','*');

--
-- Dumping data for table 'ezpolicy_limitation'
--


INSERT INTO ezpolicy_limitation VALUES (249,326,'Class',0,'read','content');

--
-- Dumping data for table 'ezpolicy_limitation_value'
--


INSERT INTO ezpolicy_limitation_value VALUES (435,249,'1');
INSERT INTO ezpolicy_limitation_value VALUES (436,249,'10');
INSERT INTO ezpolicy_limitation_value VALUES (437,249,'10');
INSERT INTO ezpolicy_limitation_value VALUES (438,249,'11');
INSERT INTO ezpolicy_limitation_value VALUES (439,249,'11');
INSERT INTO ezpolicy_limitation_value VALUES (440,249,'12');
INSERT INTO ezpolicy_limitation_value VALUES (441,249,'12');
INSERT INTO ezpolicy_limitation_value VALUES (442,249,'13');
INSERT INTO ezpolicy_limitation_value VALUES (443,249,'13');
INSERT INTO ezpolicy_limitation_value VALUES (444,249,'2');
INSERT INTO ezpolicy_limitation_value VALUES (445,249,'2');
INSERT INTO ezpolicy_limitation_value VALUES (446,249,'5');
INSERT INTO ezpolicy_limitation_value VALUES (447,249,'5');
INSERT INTO ezpolicy_limitation_value VALUES (448,249,'6');
INSERT INTO ezpolicy_limitation_value VALUES (449,249,'6');
INSERT INTO ezpolicy_limitation_value VALUES (450,249,'7');
INSERT INTO ezpolicy_limitation_value VALUES (451,249,'7');
INSERT INTO ezpolicy_limitation_value VALUES (452,249,'8');
INSERT INTO ezpolicy_limitation_value VALUES (453,249,'8');
INSERT INTO ezpolicy_limitation_value VALUES (454,249,'9');
INSERT INTO ezpolicy_limitation_value VALUES (455,249,'9');

--
-- Dumping data for table 'ezpreferences'
--



--
-- Dumping data for table 'ezproductcollection'
--



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
INSERT INTO ezrole VALUES (5,3,'Editor',NULL);

--
-- Dumping data for table 'ezrss_export'
--



--
-- Dumping data for table 'ezrss_export_item'
--



--
-- Dumping data for table 'ezrss_import'
--



--
-- Dumping data for table 'ezsearch_object_word_link'
--


INSERT INTO ezsearch_object_word_link VALUES (26,40,5,0,0,0,5,4,1053613020,2,8,'',0);
INSERT INTO ezsearch_object_word_link VALUES (27,40,5,0,1,5,0,4,1053613020,2,9,'',0);
INSERT INTO ezsearch_object_word_link VALUES (28,41,6,0,0,0,0,1,1060695457,3,4,'',0);

--
-- Dumping data for table 'ezsearch_return_count'
--



--
-- Dumping data for table 'ezsearch_search_phrase'
--



--
-- Dumping data for table 'ezsearch_word'
--


INSERT INTO ezsearch_word VALUES (5,'test',1);
INSERT INTO ezsearch_word VALUES (6,'media',1);

--
-- Dumping data for table 'ezsection'
--


INSERT INTO ezsection VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO ezsection VALUES (2,'Users','','ezusernavigationpart');
INSERT INTO ezsection VALUES (3,'Media','','ezmedianavigationpart');

--
-- Dumping data for table 'ezsession'
--



--
-- Dumping data for table 'ezsite_data'
--


INSERT INTO ezsite_data VALUES ('ezpublish-version','3.3.0');
INSERT INTO ezsite_data VALUES ('ezpublish-release','1');

--
-- Dumping data for table 'ezsubtree_notification_rule'
--



--
-- Dumping data for table 'eztipafriend_counter'
--



--
-- Dumping data for table 'eztrigger'
--



--
-- Dumping data for table 'ezurl'
--



--
-- Dumping data for table 'ezurl_object_link'
--



--
-- Dumping data for table 'ezurlalias'
--


INSERT INTO ezurlalias VALUES (12,'','d41d8cd98f00b204e9800998ecf8427e','content/view/full/2',1,0,0);
INSERT INTO ezurlalias VALUES (13,'users','9bc65c2abec141778ffaa729489f3e87','content/view/full/5',1,0,0);
INSERT INTO ezurlalias VALUES (14,'users/anonymous_user','a37b7463e2c21098fa1a729dad4b4437','content/view/full/11',1,0,0);
INSERT INTO ezurlalias VALUES (15,'users/guest_accounts','02d4e844e3a660857a3f81585995ffe1','content/view/full/12',1,0,0);
INSERT INTO ezurlalias VALUES (16,'users/administrator_users','1b1d79c16700fd6003ea7be233e754ba','content/view/full/13',1,0,0);
INSERT INTO ezurlalias VALUES (17,'users/editors','0bb9dd665c96bbc1cf36b79180786dea','content/view/full/14',1,0,0);
INSERT INTO ezurlalias VALUES (18,'users/administrator_users/administrator_user','f1305ac5f327a19b451d82719e0c3f5d','content/view/full/15',1,0,0);
INSERT INTO ezurlalias VALUES (19,'users/guest_accounts/test_test','27a1813763d43de613bf05c31df7a6ef','content/view/full/42',1,0,0);
INSERT INTO ezurlalias VALUES (20,'media','62933a2951ef01f4eafd9bdf4d3cd2f0','content/view/full/43',1,0,0);

--
-- Dumping data for table 'ezuser'
--


INSERT INTO ezuser VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');
INSERT INTO ezuser VALUES (40,'test','test@test.com',2,'be778b473235e210cc577056226536a4');

--
-- Dumping data for table 'ezuser_accountkey'
--



--
-- Dumping data for table 'ezuser_discountrule'
--



--
-- Dumping data for table 'ezuser_role'
--


INSERT INTO ezuser_role VALUES (29,1,10);
INSERT INTO ezuser_role VALUES (25,2,12);
INSERT INTO ezuser_role VALUES (30,3,13);
INSERT INTO ezuser_role VALUES (28,1,11);

--
-- Dumping data for table 'ezuser_setting'
--


INSERT INTO ezuser_setting VALUES (10,1,1000);
INSERT INTO ezuser_setting VALUES (14,1,10);
INSERT INTO ezuser_setting VALUES (23,1,0);
INSERT INTO ezuser_setting VALUES (40,1,0);

--
-- Dumping data for table 'ezvattype'
--


INSERT INTO ezvattype VALUES (1,'Std',0);

--
-- Dumping data for table 'ezview_counter'
--



--
-- Dumping data for table 'ezwaituntildatevalue'
--



--
-- Dumping data for table 'ezwishlist'
--



--
-- Dumping data for table 'ezworkflow'
--



--
-- Dumping data for table 'ezworkflow_assign'
--



--
-- Dumping data for table 'ezworkflow_event'
--



--
-- Dumping data for table 'ezworkflow_group'
--


INSERT INTO ezworkflow_group VALUES (1,'Standard',14,14,1024392098,1024392098);

--
-- Dumping data for table 'ezworkflow_group_link'
--


INSERT INTO ezworkflow_group_link VALUES (1,1,0,'Standard');

--
-- Dumping data for table 'ezworkflow_process'
--



select setval('ezbasket_s',max(id)) from ezbasket;
 select setval('ezcollab_group_s',max(id)) from ezcollab_group;
 select setval('ezcollab_item_s',max(id)) from ezcollab_item;
 select setval('ezcollab_item_message_link_s',max(id)) from ezcollab_item_message_link;
 select setval('ezcollab_notification_rule_s',max(id)) from ezcollab_notification_rule;
 select setval('ezcollab_profile_s',max(id)) from ezcollab_profile;
 select setval('ezcollab_simple_message_s',max(id)) from ezcollab_simple_message;
 select setval('ezcontent_translation_s',max(id)) from ezcontent_translation;
 select setval('ezcontentbrowsebookmark_s',max(id)) from ezcontentbrowsebookmark;
 select setval('ezcontentbrowserecent_s',max(id)) from ezcontentbrowserecent;
 select setval('ezcontentclass_attribute_s',max(id)) from ezcontentclass_attribute;
 select setval('ezcontentclassgroup_s',max(id)) from ezcontentclassgroup;
 select setval('ezcontentobject_s',max(id)) from ezcontentobject;
 select setval('ezcontentobject_attribute_s',max(id)) from ezcontentobject_attribute;
 select setval('ezcontentobject_link_s',max(id)) from ezcontentobject_link;
 select setval('ezcontentobject_version_s',max(id)) from ezcontentobject_version;
 select setval('ezdiscountrule_s',max(id)) from ezdiscountrule;
 select setval('ezdiscountsubrule_s',max(id)) from ezdiscountsubrule;
 select setval('ezenumvalue_s',max(id)) from ezenumvalue;
 select setval('ezforgot_password_s',max(id)) from ezforgot_password;
 select setval('ezinfocollection_s',max(id)) from ezinfocollection;
 select setval('ezinfocollection_attribute_s',max(id)) from ezinfocollection_attribute;
 select setval('ezkeyword_s',max(id)) from ezkeyword;
 select setval('ezkeyword_attribute_link_s',max(id)) from ezkeyword_attribute_link;
 select setval('ezmessage_s',max(id)) from ezmessage;
 select setval('ezmodule_run_s',max(id)) from ezmodule_run;
 select setval('eznode_assignment_s',max(id)) from eznode_assignment;
 select setval('eznotificationcollection_s',max(id)) from eznotificationcollection;
 select setval('eznotificationcollection_item_s',max(id)) from eznotificationcollection_item;
 select setval('eznotificationevent_s',max(id)) from eznotificationevent;
 select setval('ezoperation_memento_s',max(id)) from ezoperation_memento;
 select setval('ezorder_s',max(id)) from ezorder;
 select setval('ezorder_item_s',max(id)) from ezorder_item;
 select setval('ezpolicy_s',max(id)) from ezpolicy;
 select setval('ezpolicy_limitation_s',max(id)) from ezpolicy_limitation;
 select setval('ezpolicy_limitation_value_s',max(id)) from ezpolicy_limitation_value;
 select setval('ezproductcollection_s',max(id)) from ezproductcollection;
 select setval('ezproductcollection_item_s',max(id)) from ezproductcollection_item;
 select setval('ezproductcollection_item_opt_s',max(id)) from ezproductcollection_item_opt;
 select setval('ezrole_s',max(id)) from ezrole;
 select setval('ezsearch_object_word_link_s',max(id)) from ezsearch_object_word_link;
 select setval('ezsearch_return_count_s',max(id)) from ezsearch_return_count;
 select setval('ezsearch_search_phrase_s',max(id)) from ezsearch_search_phrase;
 select setval('ezsearch_word_s',max(id)) from ezsearch_word;
 select setval('ezsection_s',max(id)) from ezsection;
 select setval('ezsubtree_notification_rule_s',max(id)) from ezsubtree_notification_rule;
 select setval('eztrigger_s',max(id)) from eztrigger;
 select setval('ezurl_s',max(id)) from ezurl;
 select setval('ezuser_accountkey_s',max(id)) from ezuser_accountkey;
 select setval('ezuser_discountrule_s',max(id)) from ezuser_discountrule;
 select setval('ezuser_role_s',max(id)) from ezuser_role;
 select setval('ezvattype_s',max(id)) from ezvattype;
 select setval('ezwaituntildatevalue_s',max(id)) from ezwaituntildatevalue;
 select setval('ezwishlist_s',max(id)) from ezwishlist;
 select setval('ezworkflow_s',max(id)) from ezworkflow;
 select setval('ezworkflow_assign_s',max(id)) from ezworkflow_assign;
 select setval('ezworkflow_event_s',max(id)) from ezworkflow_event;
 select setval('ezworkflow_group_s',max(id)) from ezworkflow_group;
 select setval('ezpdf_export_s',max(id)) from ezpdf_export;
 select setval('ezrss_export_s',max(id)) from ezrss_export;
 select setval('ezrss_export_item_s',max(id)) from ezrss_export_item;
 select setval('ezrss_import_s',max(id)) from ezrss_import;
 select setval('ezimagefile_s',max(id)) from ezimagefile;

SELECT setval('ezcontentobject_tree_s', max(node_id)) , 'ezcontentobject_tree' as tablename FROM ezcontentobject_tree;
