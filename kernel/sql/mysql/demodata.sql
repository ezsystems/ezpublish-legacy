-- MySQL dump 8.22
--
-- Host: localhost    Database: demodemo
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


INSERT INTO ezbinaryfile (contentobject_attribute_id, version, filename, original_filename, mime_type) VALUES (116,1,'php4Y5Bes.gif','topmenu.gif','image/gif');

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
-- Dumping data for table 'ezcontentbrowsebookmark'
--



--
-- Dumping data for table 'ezcontentbrowserecent'
--


INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (1,14,2,1053683300,'Root folder');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (2,14,2,1053683312,'Root folder');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (3,14,2,1053683329,'Root folder');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (4,14,2,1053683341,'Root folder');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (5,14,2,1053683356,'Root folder');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (6,14,2,1053683368,'Root folder');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (7,14,2,1053683378,'Root folder');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (8,14,19,1053685679,'The Book Corner');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (9,14,17,1053687979,'News');
INSERT INTO ezcontentbrowserecent (id, user_id, node_id, created, name) VALUES (45,14,16,1053703694,'White box');

--
-- Dumping data for table 'ezcontentclass'
--


INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1048494694);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1048494722);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1048494743);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1048494759);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048494784);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (6,0,'Forum','forum','<name>',14,14,1052384723,1052384870);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (7,0,'Forum message','forum_message','<topic>',14,14,1052384877,1052384943);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (8,0,'Product','product','<title>',14,14,1052384951,1052385067);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (9,0,'Product review','product_review','<title>',14,14,1052385080,1052385252);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (10,0,'Info page','info_page','<name>',14,14,1052385274,1052385353);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (11,0,'Link','link','<title>',14,14,1052385361,1052385453);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (12,0,'File','file','<name>',14,14,1052385472,1052385669);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (13,0,'Comment','comment','<subject>',14,14,1052385685,1052385756);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (6,1,'Forum','forum','<name>',14,14,1052384723,1053700668);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2,1,'Article','article','<title>',-1,14,1024392098,1053701185);

--
-- Dumping data for table 'ezcontentclass_attribute'
--


INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (12,0,4,'user_account','User account','ezuser',0,1,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (117,0,5,'caption','Caption','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (127,0,7,'topic','Topic','ezstring',1,1,1,150,0,0,0,0,0,0,0,'New topic','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (128,0,7,'message','Message','eztext',1,1,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (126,0,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (125,0,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (124,0,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (134,0,8,'photo','Photo','ezimage',0,0,6,1,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (133,0,8,'price','Price','ezprice',0,1,5,1,0,0,0,1,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (132,0,8,'description','Description','ezxmltext',1,0,4,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (131,0,8,'intro','Intro','ezxmltext',1,0,3,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (130,0,8,'product_nr','Product nr.','ezstring',1,0,2,40,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (129,0,8,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (139,0,9,'review','Review','ezxmltext',1,0,5,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (138,0,9,'geography','Town, Country','ezstring',1,1,4,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (137,0,9,'reviewer_name','Reviewer Name','ezstring',1,1,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (136,0,9,'rating','Rating','ezenum',1,0,2,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (135,0,9,'title','Title','ezstring',1,1,1,50,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (142,0,10,'image','Image','ezimage',0,0,3,1,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (141,0,10,'body','Body','ezxmltext',1,0,2,20,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (140,0,10,'name','Name','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (146,0,12,'name','Name','ezstring',1,1,1,0,0,0,0,0,0,0,0,'New file','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (148,0,12,'file','File','ezbinaryfile',0,1,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (147,0,12,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (145,0,11,'link','Link','ezurl',0,0,3,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (144,0,11,'description','Description','ezxmltext',1,0,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (143,0,11,'title','Title','ezstring',1,1,1,100,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (151,0,13,'message','Message','eztext',1,1,3,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (150,0,13,'author','Author','ezstring',1,1,2,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (149,0,13,'subject','Subject','ezstring',1,1,1,40,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (126,1,6,'description','Description','ezxmltext',1,0,3,15,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (124,1,6,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (125,1,6,'icon','Icon','ezimage',0,0,2,1,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (120,1,2,'intro','Intro','ezxmltext',1,1,2,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (121,1,2,'body','Body','ezxmltext',1,0,3,20,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (1,1,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (122,1,2,'thumbnail','Thumbnail','ezimage',0,0,4,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (123,1,2,'enable_comments','Enable comments','ezboolean',0,0,5,0,0,0,0,0,0,0,0,'','','','',0);

--
-- Dumping data for table 'ezcontentclass_classgroup'
--


INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (1,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (2,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (4,0,2,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (5,0,3,'Media');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (3,0,2,'');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (6,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (7,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (8,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (9,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (10,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (11,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (12,0,3,'Media');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (13,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (6,1,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (2,1,1,'Content');

--
-- Dumping data for table 'ezcontentclassgroup'
--


INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (3,'Media',8,14,1032009743,1033922120);

--
-- Dumping data for table 'ezcontentobject'
--


INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (4,14,9,3,'Users',1,0,0,0,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (10,14,9,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (11,14,9,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (12,14,9,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (13,14,9,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (14,14,9,4,'Administrator User',1,0,1033920830,1033920830,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (15,14,2,1,'White box',1,0,1053683300,1053683300,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (16,14,3,1,'News',1,0,1053683312,1053683312,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (17,14,4,1,'Crossroads forum',1,0,1053683329,1053683329,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (18,14,5,1,'The Book Corner',1,0,1053683341,1053683341,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (19,14,6,1,'My company',2,0,1053683356,1053697488,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (20,14,7,1,'My Intranet',1,0,1053683368,1053683368,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (21,14,8,1,'My site',1,0,1053683378,1053683378,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (22,14,5,1,'test',1,0,1053685679,1053685679,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (23,14,3,1,'Frontpage',1,0,1053687979,1053687979,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (24,14,3,1,'Sport',1,0,1053687993,1053687993,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (25,14,3,1,'Action',1,0,1053688095,1053688095,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (26,14,3,1,'World News',1,0,1053688106,1053688106,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (27,14,3,1,'Leisure',1,0,1053688126,1053688126,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (28,14,3,2,'Food for the soul',1,0,1053688189,1053688189,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (32,14,6,1,'News',1,0,1053697239,1053697239,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (33,14,6,1,'Products',1,0,1053697367,1053697367,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (34,14,6,1,'Services',1,0,1053697388,1053697388,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (35,14,6,2,'About',1,0,1053697410,1053697410,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (36,14,6,1,'Software',1,0,1053697576,1053697576,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (37,14,6,1,'Servers',2,0,1053697586,1053697598,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (38,14,6,10,'Test test test',1,0,1053697630,1053697630,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (39,14,6,8,'test product',1,0,1053697769,1053697769,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (40,14,5,8,'test product',1,0,1053697961,1053697961,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (41,14,7,1,'News',1,0,1053698033,1053698033,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (42,14,7,1,'Files',1,0,1053698046,1053698046,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (44,14,7,12,'Test file',1,0,1053698475,1053698475,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (45,14,7,2,'test article',1,0,1053698514,1053698514,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (46,14,4,1,'Links',1,0,1053699789,1053699789,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (47,14,4,10,'About this forum',1,0,1053699815,1053699815,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (48,14,4,6,'New Forum',1,0,0,0,0,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (49,14,4,1,'Forums',1,0,1053699844,1053699844,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (50,14,4,6,'Sports',1,0,1053699973,1053699973,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (51,14,4,6,'Computers',1,0,1053699986,1053699986,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (55,14,4,7,'test topic',1,0,1053701308,1053701308,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (56,14,8,10,'test',1,0,1053702943,1053702943,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (57,14,2,1,'Landscape',1,0,1053703449,1053703449,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (58,14,2,1,'Flowers',1,0,1053703495,1053703495,1,'');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (60,14,2,10,'About',1,0,1053703694,1053703694,1,'');

--
-- Dumping data for table 'ezcontentobject_attribute'
--


INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (1,'eng-GB',1,1,4,'My folder',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (2,'eng-GB',1,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (7,'eng-GB',1,4,7,'Main group',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (1,'eng-GB',2,1,4,'My folder',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (2,'eng-GB',2,1,119,'<?xml version=\"1.0\"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (21,'eng-GB',1,10,12,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (19,'eng-GB',1,10,8,'Anonymous',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (20,'eng-GB',1,10,9,'User',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (23,'eng-GB',1,11,7,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (25,'eng-GB',1,12,7,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (26,'eng-GB',1,13,6,'Editors',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (27,'eng-GB',1,13,7,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (28,'eng-GB',1,14,8,'Administrator',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (29,'eng-GB',1,14,9,'User',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (30,'eng-GB',1,14,12,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (31,'eng-GB',1,15,4,'White box',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (32,'eng-GB',1,15,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (33,'eng-GB',1,16,4,'News',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (34,'eng-GB',1,16,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (35,'eng-GB',1,17,4,'Crossroads forum ',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (36,'eng-GB',1,17,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (37,'eng-GB',1,18,4,'The Book Corner',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (38,'eng-GB',1,18,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (39,'eng-GB',1,19,4,'My company',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (40,'eng-GB',1,19,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (41,'eng-GB',1,20,4,'My Intranet',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (42,'eng-GB',1,20,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (43,'eng-GB',1,21,4,'My site',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (44,'eng-GB',1,21,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (45,'eng-GB',1,22,4,'test',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (46,'eng-GB',1,22,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (47,'eng-GB',1,23,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (48,'eng-GB',1,23,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (49,'eng-GB',1,24,4,'Sport',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (50,'eng-GB',1,24,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (51,'eng-GB',1,25,4,'Action',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (52,'eng-GB',1,25,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (53,'eng-GB',1,26,4,'World News',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (54,'eng-GB',1,26,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (55,'eng-GB',1,27,4,'Leisure',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (56,'eng-GB',1,27,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (57,'eng-GB',1,28,1,'Food for the soul',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (58,'eng-GB',1,28,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>Soulfood.no is a result of a passionate interest for photography and people. This interesting site runs on eZ publish and is a very good example on what you can do with content and design on an eZ publish powered site. </paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (59,'eng-GB',1,28,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>Christian Houge, b. 1972, is a freelance photographer educated in USA and have worked with advertising, portraits and travelling since 1994. On his last travel, during the winter of 1999, Houge spend six months with the exile tibetanians in the South and North India as well as in Nepal. Many of his pictures are influenced by this visit. For long periods of this stay he lived in a tibetanian monastery as the only western representative and he got an insight in the daily life of the munks. </paragraph>\n  <paragraph>The design for this site is made by Sigurd Kristiansen Superstar.no and has been set up by one of eZ systems official partners Petraflux.com eZ systems has assisted our partner with support. Automatical image import was created amongst other things. </paragraph>\n  <paragraph>Visit Soulfood </paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (60,'eng-GB',1,28,122,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (61,'eng-GB',1,28,123,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (75,'eng-GB',1,32,4,'News',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (76,'eng-GB',1,32,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (77,'eng-GB',1,33,4,'Products',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (78,'eng-GB',1,33,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (79,'eng-GB',1,34,4,'Services',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (80,'eng-GB',1,34,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (81,'eng-GB',1,35,1,'About',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (82,'eng-GB',1,35,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (83,'eng-GB',1,35,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (84,'eng-GB',1,35,122,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (85,'eng-GB',1,35,123,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (39,'eng-GB',2,19,4,'My company',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (40,'eng-GB',2,19,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (86,'eng-GB',1,36,4,'Software',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (87,'eng-GB',1,36,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (88,'eng-GB',1,37,4,'Software',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (89,'eng-GB',1,37,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (88,'eng-GB',2,37,4,'Servers',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (89,'eng-GB',2,37,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (90,'eng-GB',1,38,140,'Test test test',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (91,'eng-GB',1,38,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (92,'eng-GB',1,38,142,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (93,'eng-GB',1,39,129,'test product',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (94,'eng-GB',1,39,130,'3',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (95,'eng-GB',1,39,131,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>123</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (96,'eng-GB',1,39,132,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>123</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (97,'eng-GB',1,39,133,'',0,11);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (98,'eng-GB',1,39,134,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (99,'eng-GB',1,40,129,'test product',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (100,'eng-GB',1,40,130,'123',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (101,'eng-GB',1,40,131,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>123123sdfsdfsdf</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (102,'eng-GB',1,40,132,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>sd</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (103,'eng-GB',1,40,133,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (104,'eng-GB',1,40,134,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (105,'eng-GB',1,41,4,'News',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (106,'eng-GB',1,41,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (107,'eng-GB',1,42,4,'Files',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (108,'eng-GB',1,42,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (114,'eng-GB',1,44,146,'Test file',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (115,'eng-GB',1,44,147,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>bla</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (116,'eng-GB',1,44,148,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (117,'eng-GB',1,45,1,'test article',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (118,'eng-GB',1,45,120,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>tet</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (119,'eng-GB',1,45,121,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>tet</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (120,'eng-GB',1,45,122,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (121,'eng-GB',1,45,123,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (122,'eng-GB',1,46,4,'Links',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (123,'eng-GB',1,46,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (124,'eng-GB',1,47,140,'About this forum',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (125,'eng-GB',1,47,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>This forum is for discussion, bet you didn&apos;t figure that out. </paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (126,'eng-GB',1,47,142,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (130,'eng-GB',1,49,4,'Forums',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (131,'eng-GB',1,49,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (132,'eng-GB',1,50,124,'Sports',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (133,'eng-GB',1,50,125,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (134,'eng-GB',1,50,126,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (135,'eng-GB',1,51,124,'Computers',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (136,'eng-GB',1,51,125,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (137,'eng-GB',1,51,126,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (148,'eng-GB',1,55,127,'test topic',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (149,'eng-GB',1,55,128,'hihi',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (150,'eng-GB',1,56,140,'test',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (151,'eng-GB',1,56,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (152,'eng-GB',1,56,142,'',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (153,'eng-GB',1,57,4,'Landscape',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (154,'eng-GB',1,57,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (155,'eng-GB',1,58,4,'Flowers',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (156,'eng-GB',1,58,119,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\" />',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (160,'eng-GB',1,60,140,'About',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (161,'eng-GB',1,60,141,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\">\n  <paragraph>about about about about</paragraph>\n</section>',1045487555,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (162,'eng-GB',1,60,142,'',0,0);

--
-- Dumping data for table 'ezcontentobject_link'
--



--
-- Dumping data for table 'ezcontentobject_name'
--


INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Root folder',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (4,'Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (10,'Anonymous User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (11,'Guest accounts',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (12,'Administrator users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (13,'Editors',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14,'Administrator User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (15,'White box',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (16,'News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (17,'Crossroads forum',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (18,'The Book Corner',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (19,'My company',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (20,'My Intranet',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (21,'My site',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (22,'test',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (23,'Frontpage',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (24,'Sport',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (25,'Action',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (26,'World News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (27,'Leisure',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (28,'Food for the soul',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (32,'News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (33,'Products',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (34,'Services',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (35,'About',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (19,'My company',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (36,'Software',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (37,'Software',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (37,'Servers',2,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (38,'Test test test',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (39,'test product',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (40,'test product',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (41,'News',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (42,'Files',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (44,'Test file',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (45,'test article',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (46,'Links',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (47,'About this forum',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (48,'New Forum',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (49,'Forums',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (50,'Sports',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (51,'Computers',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (55,'test topic',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (56,'test',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (57,'Landscape',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (58,'Flowers',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (60,'About',1,'eng-GB','eng-GB');

--
-- Dumping data for table 'ezcontentobject_tree'
--


INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (1,1,0,1,1,NULL,0,'/1/',1,1,0,NULL,1,NULL);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (2,1,1,1,1,0,1,'/1/2/',1,1,0,'',2,'d41d8cd98f00b204e9800998ecf8427e');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (5,1,4,1,0,-195235522,1,'/1/5/',1,1,0,'__1',5,'08a9d0bbf3381652f7cca8738b5a8469');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (11,5,10,1,1,1015610524,2,'/1/5/11/',1,1,0,'__1/anonymous_user',11,'a59d2313b486e0f43477433525edea9b');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (12,5,11,1,1,1857785444,2,'/1/5/12/',1,1,0,'__1/guest_accounts',12,'c894997127008ea742913062f39adfc5');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (13,5,12,1,1,-1978139175,2,'/1/5/13/',1,1,0,'__1/administrator_users',13,'caeccbc33185f04d92e2b6cb83b1c7e4');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (14,5,13,1,1,2094553782,2,'/1/5/14/',1,1,0,'__1/editors',14,'39f6f6f51c1e3a922600b2d415d7a46d');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (15,13,14,1,1,-852704961,3,'/1/5/13/15/',1,1,0,'__1/administrator_users/administrator_user',15,'2c3f2814cfa91bcb17d7893ca6f8a0c4');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (16,2,15,1,1,-905170500,2,'/1/2/16/',9,1,0,'white_box',16,'e6a63f4ee6b40c2d439c4285ad9d6ee9');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (17,2,16,1,1,500406608,2,'/1/2/17/',9,1,0,'news',17,'508c75c8507a2ae5223dfd2faeb98122');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (18,2,17,1,1,-2011090623,2,'/1/2/18/',9,1,0,'crossroads_forum_',18,'9488fc1a950786ff72566b9915ad7224');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (19,2,18,1,1,-97909150,2,'/1/2/19/',9,1,0,'the_book_corner',19,'b4690804656244a6fd83b20db9b3aad4');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (20,2,19,2,1,-985225885,2,'/1/2/20/',8,1,0,'my_company',20,'93991b3a2cc7a1bc8c6eff64a97fe1a1');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (21,2,20,1,1,-556964099,2,'/1/2/21/',9,1,0,'my_intranet',21,'925a84f12d0e2a2afc289c7280b41ef8');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (22,2,21,1,1,-1453055824,2,'/1/2/22/',9,1,0,'my_site',22,'6a217f0f7032720eb50a1a2fbf258463');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (23,19,22,1,1,1637375784,3,'/1/2/19/23/',9,1,0,'the_book_corner/test',23,'5881d3ccdc835e51532bf726a99bdf6b');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (24,17,23,1,1,-1149903583,3,'/1/2/17/24/',9,1,0,'news/frontpage',24,'7c35fa6d599b052dde38d0694ba84c18');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (25,17,24,1,1,1796760909,3,'/1/2/17/25/',9,1,0,'news/sport',25,'50ffe644772ce5852f23fe34c4fc48a1');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (26,17,25,1,1,655204301,3,'/1/2/17/26/',9,1,0,'news/action',26,'5331d5c665713085cf2fe440f461304d');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (27,17,26,1,1,-783622642,3,'/1/2/17/27/',9,1,0,'news/world_news',27,'de23a31dde3637232e47ef09c2ac78c9');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (28,17,27,1,1,-306916314,3,'/1/2/17/28/',9,1,0,'news/leisure',28,'6430731dda8bd8c67ad4b01673b4d499');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (29,24,28,1,1,-1361070529,4,'/1/2/17/24/29/',9,1,0,'news/frontpage/food_for_the_soul',29,'af8e4059be6ac871c0529210f7859d9b');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (33,20,32,1,1,-543604905,3,'/1/2/20/33/',9,1,1,'my_company/news',33,'d9dfb5315b65152afe3c9f3cdd1a9e37');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (34,20,33,1,1,-1939585246,3,'/1/2/20/34/',9,1,2,'my_company/products',34,'498230da01fb414d2ad4524fc29d246b');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (35,20,34,1,1,1290599441,3,'/1/2/20/35/',9,1,3,'my_company/services',35,'6258930ba5c27aed4021b46ccfb10f4f');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (36,20,35,1,1,726859238,3,'/1/2/20/36/',9,1,0,'my_company/about',36,'45abc6a50b7018ec0e001966ef5d8dcb');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (37,34,36,1,1,812069886,4,'/1/2/20/34/37/',9,1,0,'my_company/products/software',37,'a34b5df1de9e330e4adffd579cbd2aa2');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (38,34,37,2,1,1243527961,4,'/1/2/20/34/38/',9,1,0,'my_company/products/servers',38,'6f1919abcd6c6a0209c499dfd59f44ff');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (39,35,38,1,1,981758404,4,'/1/2/20/35/39/',9,1,0,'my_company/services/test_test_test',39,'475d19eeda58affc237f380dfe7dc1de');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (40,38,39,1,1,-1403853004,5,'/1/2/20/34/38/40/',9,1,0,'my_company/products/servers/test_product',40,'cd52c59c21af23ca43f63e50a709379e');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (41,23,40,1,1,365423858,4,'/1/2/19/23/41/',9,1,0,'the_book_corner/test/test_product',41,'35cecd9c87123d5c53d7380491205a90');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (42,21,41,1,1,-1862150091,3,'/1/2/21/42/',9,1,0,'my_intranet/news',42,'04e9eac1496c2cd185b0e0c5a15c1ec7');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (43,21,42,1,1,996214735,3,'/1/2/21/43/',9,1,0,'my_intranet/files',43,'8c5ee7aa12c046b0870d54c64e057405');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (46,42,45,1,1,-1848252998,4,'/1/2/21/42/46/',9,1,0,'my_intranet/news/test_article',46,'6dec79a7e3e5b58742b3738697c75f42');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (45,43,44,1,1,1774407986,4,'/1/2/21/43/45/',9,1,0,'my_intranet/files/test_file',45,'a4cd05106bc6ba8a5987d0e6ebb7cf9f');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (47,18,46,1,1,-9073959,3,'/1/2/18/47/',9,1,0,'crossroads_forum_/links',47,'57236c7f5b5279f706b0718d49ba3568');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (48,18,47,1,1,1571788090,3,'/1/2/18/48/',9,1,0,'crossroads_forum_/about_this_forum',48,'df018f48f0562b0abdf82960795d1d4f');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (49,18,49,1,1,303259573,3,'/1/2/18/49/',9,1,0,'crossroads_forum_/forums',49,'7f323d41c1c86b02a8511e5d621c832d');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (50,49,50,1,1,878143191,4,'/1/2/18/49/50/',9,1,0,'crossroads_forum_/forums/sports',50,'0e439fed0182c0fa0a920733182cde2f');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (51,49,51,1,1,1485635585,4,'/1/2/18/49/51/',9,1,0,'crossroads_forum_/forums/computers',51,'e80cca635710f704a4b48f56bcdfb668');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (58,16,58,1,1,-23261470,3,'/1/2/16/58/',9,1,0,'white_box/flowers',58,'bbb88b4dcf8f0f69deb36b03f2d264a9');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (57,16,57,1,1,-310936286,3,'/1/2/16/57/',9,1,0,'white_box/landscape',57,'e9a618ea1db87e4369536567fb58f395');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (56,22,56,1,1,-60412498,3,'/1/2/22/56/',9,1,0,'my_site/test',56,'8a2c44729806c59e7af146de37a60533');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (55,51,55,1,1,-610268624,5,'/1/2/18/49/51/55/',9,1,0,'crossroads_forum_/forums/computers/test_topic',55,'a1bd16ad6d8e98dea7d8f8775bd0d13b');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (60,16,60,1,1,323562920,3,'/1/2/16/60/',9,1,0,'white_box/about',60,'a3c176ad229383f7876ce83c7648fc19');

--
-- Dumping data for table 'ezcontentobject_version'
--


INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (1,1,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (4,4,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (436,1,14,2,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (438,10,14,1,1033920649,1033920665,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (439,11,14,1,1033920737,1033920746,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (440,12,14,1,1033920760,1033920775,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (441,13,14,1,1033920786,1033920794,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (442,14,14,1,1033920808,1033920830,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (443,15,14,1,1053683271,1053683300,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (444,16,14,1,1053683307,1053683312,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (445,17,14,1,1053683318,1053683329,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (446,18,14,1,1053683333,1053683341,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (447,19,14,1,1053683350,1053683356,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (448,20,14,1,1053683362,1053683368,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (449,21,14,1,1053683372,1053683378,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (450,22,14,1,1053685674,1053685678,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (451,23,14,1,1053687972,1053687979,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (452,24,14,1,1053687986,1053687993,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (453,25,14,1,1053688087,1053688094,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (454,26,14,1,1053688099,1053688106,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (455,27,14,1,1053688117,1053688126,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (456,28,14,1,1053688159,1053688189,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (461,32,14,1,1053697233,1053697239,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (462,33,14,1,1053697358,1053697367,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (463,34,14,1,1053697382,1053697388,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (464,35,14,1,1053697402,1053697410,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (465,19,14,2,1053697484,1053697488,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (466,36,14,1,1053697569,1053697576,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (467,37,14,1,1053697581,1053697586,3,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (468,37,14,2,1053697592,1053697598,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (469,38,14,1,1053697621,1053697630,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (470,39,14,1,1053697743,1053697769,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (471,40,14,1,1053697949,1053697961,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (472,41,14,1,1053698027,1053698033,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (473,42,14,1,1053698039,1053698046,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (478,44,14,1,1053698458,1053698475,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (479,45,14,1,1053698500,1053698514,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (480,46,14,1,1053699781,1053699789,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (481,47,14,1,1053699800,1053699815,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (483,49,14,1,1053699838,1053699844,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (484,50,14,1,1053699964,1053699973,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (485,51,14,1,1053699980,1053699986,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (489,55,14,1,1053701299,1053701308,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (490,56,14,1,1053702936,1053702943,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (491,57,14,1,1053703435,1053703449,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (492,58,14,1,1053703455,1053703495,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (494,60,14,1,1053703679,1053703694,1,0,0);

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


INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (2,136,0,'Ok','3',2);
INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (1,136,0,'Poor','2',1);
INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (3,136,0,'Good','5',3);

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


INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (2,1,1,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (4,8,2,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (144,4,4,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (147,210,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (146,209,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (145,1,2,1,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (148,9,1,2,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (149,10,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (150,11,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (151,12,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (152,13,1,5,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (153,14,1,13,1,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (154,15,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (155,16,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (156,17,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (157,18,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (158,19,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (159,20,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (160,21,1,2,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (161,22,1,19,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (162,23,1,17,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (163,24,1,17,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (164,25,1,17,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (165,26,1,17,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (166,27,1,17,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (167,28,1,24,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (172,32,1,20,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (173,33,1,20,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (174,34,1,20,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (175,35,1,20,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (176,19,2,2,8,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (177,36,1,34,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (178,37,1,34,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (179,37,2,34,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (180,38,1,35,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (181,39,1,38,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (182,40,1,23,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (183,41,1,21,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (184,42,1,21,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (189,44,1,43,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (190,45,1,42,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (191,46,1,18,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (192,47,1,18,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (194,49,1,18,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (195,50,1,49,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (196,51,1,49,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (200,55,1,51,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (201,56,1,22,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (202,57,1,16,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (203,58,1,16,9,1,1,0,0);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (205,60,1,16,9,1,1,0,0);

--
-- Dumping data for table 'eznotificationcollection'
--



--
-- Dumping data for table 'eznotificationcollection_item'
--



--
-- Dumping data for table 'eznotificationevent'
--


INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (1,0,'ezpublish',15,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (2,0,'ezpublish',16,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (3,0,'ezpublish',17,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (4,0,'ezpublish',18,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (5,0,'ezpublish',19,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (6,0,'ezpublish',20,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (7,0,'ezpublish',21,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (8,0,'ezpublish',22,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (9,0,'ezpublish',23,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (10,0,'ezpublish',24,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (11,0,'ezpublish',25,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (12,0,'ezpublish',26,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (13,0,'ezpublish',27,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (14,0,'ezpublish',28,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (15,0,'ezpublish',29,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (16,0,'ezpublish',30,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (17,0,'ezpublish',31,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (18,0,'ezpublish',32,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (19,0,'ezpublish',33,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (20,0,'ezpublish',34,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (21,0,'ezpublish',35,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (22,0,'ezpublish',19,2,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (23,0,'ezpublish',36,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (24,0,'ezpublish',37,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (25,0,'ezpublish',37,2,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (26,0,'ezpublish',38,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (27,0,'ezpublish',39,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (28,0,'ezpublish',40,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (29,0,'ezpublish',41,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (30,0,'ezpublish',42,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (31,0,'ezpublish',43,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (32,0,'ezpublish',44,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (33,0,'ezpublish',45,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (34,0,'ezpublish',46,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (35,0,'ezpublish',47,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (36,0,'ezpublish',49,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (37,0,'ezpublish',50,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (38,0,'ezpublish',51,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (39,0,'ezpublish',52,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (40,0,'ezpublish',53,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (41,0,'ezpublish',54,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (42,0,'ezpublish',55,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (43,0,'ezpublish',56,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (44,0,'ezpublish',57,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (45,0,'ezpublish',58,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (46,0,'ezpublish',59,1,0,0,'','','','');
INSERT INTO eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) VALUES (47,0,'ezpublish',60,1,0,0,'','','','');

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


INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (317,3,'*','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (338,17,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (333,13,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (319,3,'login','user','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (336,6,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (339,17,'login','user','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (323,5,'*','*','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (324,6,'*','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (325,6,'login','user','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (326,9,'read','content','');

--
-- Dumping data for table 'ezpolicy_limitation'
--


INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (257,333,'Subtree',0,'read','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (259,333,'Class',0,'read','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (248,326,'Subtree',0,'read','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (261,338,'Class',0,'read','content');
INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (260,336,'Subtree',0,'read','content');

--
-- Dumping data for table 'ezpolicy_limitation_value'
--


INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (516,257,0);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (515,257,0);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (414,248,0);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (570,261,12);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (569,261,11);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (568,261,10);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (567,261,9);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (566,261,8);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (565,261,7);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (564,261,6);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (563,261,5);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (562,261,2);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (561,261,13);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (560,261,12);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (559,261,11);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (558,261,10);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (557,261,9);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (556,261,8);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (555,261,7);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (554,261,6);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (553,261,5);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (552,261,2);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (551,261,1);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (517,257,0);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (571,261,13);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (549,259,7);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (548,259,6);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (547,259,5);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (546,259,4);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (545,259,3);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (544,259,7);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (543,259,6);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (542,259,5);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (541,259,4);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (540,259,3);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (539,259,2);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (550,260,0);

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


INSERT INTO ezrole (id, version, name, value) VALUES (2,0,'Administrator','*');
INSERT INTO ezrole (id, version, name, value) VALUES (3,0,'Editor','');
INSERT INTO ezrole (id, version, name, value) VALUES (17,0,'Anonymous',NULL);
INSERT INTO ezrole (id, version, name, value) VALUES (5,2,'Administrator',NULL);
INSERT INTO ezrole (id, version, name, value) VALUES (6,3,'Editor',NULL);
INSERT INTO ezrole (id, version, name, value) VALUES (9,7,'Anonymous2',NULL);
INSERT INTO ezrole (id, version, name, value) VALUES (13,12,'New Role',NULL);
INSERT INTO ezrole (id, version, name, value) VALUES (11,10,'test',NULL);

--
-- Dumping data for table 'ezsearch_object_word_link'
--


INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (1,15,1,0,0,0,2,1,1053683300,2,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (2,15,2,0,1,1,0,1,1053683300,2,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (3,16,3,0,0,0,0,1,1053683312,3,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (4,17,4,0,0,0,5,1,1053683329,4,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (5,17,5,0,1,4,0,1,1053683329,4,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (6,18,6,0,0,0,7,1,1053683341,5,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (7,18,7,0,1,6,8,1,1053683341,5,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (8,18,8,0,2,7,0,1,1053683341,5,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (209,19,139,0,1,9,0,1,1053683356,6,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (208,19,9,0,0,0,139,1,1053683356,6,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (11,20,9,0,0,0,11,1,1053683368,7,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (12,20,11,0,1,9,0,1,1053683368,7,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (13,21,9,0,0,0,12,1,1053683378,8,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (14,21,12,0,1,9,0,1,1053683378,8,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (15,22,13,0,0,0,0,1,1053685679,5,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (16,23,14,0,0,0,0,1,1053687979,3,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (17,24,15,0,0,0,0,1,1053687993,3,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (18,25,16,0,0,0,0,1,1053688095,3,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (19,26,17,0,0,0,3,1,1053688106,3,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (20,26,3,0,1,17,0,1,1053688106,3,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (21,27,18,0,0,0,0,1,1053688126,3,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (22,28,19,0,0,0,85,2,1053688189,3,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (23,28,85,0,1,19,6,2,1053688189,3,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (24,28,6,0,2,85,20,2,1053688189,3,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (25,28,20,0,3,6,21,2,1053688189,3,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (26,28,21,0,4,20,29,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (27,28,29,0,5,21,30,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (28,28,30,0,6,29,22,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (29,28,22,0,7,30,101,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (30,28,101,0,8,22,30,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (31,28,30,0,9,101,23,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (32,28,23,0,10,30,24,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (33,28,24,0,11,23,85,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (34,28,85,0,12,24,25,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (35,28,25,0,13,85,50,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (36,28,50,0,14,25,26,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (37,28,26,0,15,50,88,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (38,28,88,0,16,26,27,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (39,28,27,0,17,88,12,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (40,28,12,0,18,27,28,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (41,28,28,0,19,12,59,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (42,28,59,0,20,28,114,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (43,28,114,0,21,59,41,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (44,28,41,0,22,114,50,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (45,28,50,0,23,41,29,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (46,28,29,0,24,50,30,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (47,28,30,0,25,29,31,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (48,28,31,0,26,30,32,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (49,28,32,0,27,31,33,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (50,28,33,0,28,32,59,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (51,28,59,0,29,33,34,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (52,28,34,0,30,59,35,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (53,28,35,0,31,34,36,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (54,28,36,0,32,35,37,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (55,28,37,0,33,36,53,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (56,28,53,0,34,37,38,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (57,28,38,0,35,53,50,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (58,28,50,0,36,38,39,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (59,28,39,0,37,50,59,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (60,28,59,0,38,39,40,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (61,28,40,0,39,59,114,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (62,28,114,0,40,40,41,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (63,28,41,0,41,114,42,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (64,28,42,0,42,41,12,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (65,28,12,0,43,42,43,2,1053688189,3,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (66,28,43,0,44,12,65,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (67,28,65,0,45,43,44,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (68,28,44,0,46,65,45,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (69,28,45,0,47,44,29,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (70,28,29,0,48,45,30,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (71,28,30,0,49,29,46,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (72,28,46,0,50,30,47,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (73,28,47,0,51,46,48,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (74,28,48,0,52,47,71,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (75,28,71,0,53,48,49,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (76,28,49,0,54,71,50,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (77,28,50,0,55,49,51,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (78,28,51,0,56,50,52,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (79,28,52,0,57,51,53,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (80,28,53,0,58,52,54,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (81,28,54,0,59,53,55,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (82,28,55,0,60,54,50,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (83,28,50,0,61,55,56,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (84,28,56,0,62,50,57,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (85,28,57,0,63,56,58,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (86,28,58,0,64,57,59,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (87,28,59,0,65,58,79,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (88,28,79,0,66,59,60,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (89,28,60,0,67,79,61,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (90,28,61,0,68,60,62,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (91,28,62,0,69,61,6,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (92,28,6,0,70,62,63,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (93,28,63,0,71,6,101,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (94,28,101,0,72,63,64,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (95,28,64,0,73,101,65,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (96,28,65,0,74,64,66,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (97,28,66,0,75,65,67,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (98,28,67,0,76,66,68,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (99,28,68,0,77,67,53,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (100,28,53,0,78,68,6,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (101,28,6,0,79,53,69,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (102,28,69,0,80,6,70,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (103,28,70,0,81,69,71,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (104,28,71,0,82,70,6,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (105,28,6,0,83,71,72,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (106,28,72,0,84,6,50,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (107,28,50,0,85,72,73,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (108,28,73,0,86,50,74,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (109,28,74,0,87,73,75,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (110,28,75,0,88,74,76,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (111,28,76,0,89,75,75,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (112,28,75,0,90,76,71,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (113,28,71,0,91,75,77,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (114,28,77,0,92,71,78,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (115,28,78,0,93,77,101,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (116,28,101,0,94,78,79,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (117,28,79,0,95,101,80,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (118,28,80,0,96,79,81,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (119,28,81,0,97,80,82,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (120,28,82,0,98,81,83,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (121,28,83,0,99,82,88,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (122,28,88,0,100,83,84,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (123,28,84,0,101,88,85,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (124,28,85,0,102,84,86,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (125,28,86,0,103,85,87,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (126,28,87,0,104,86,101,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (127,28,101,0,105,87,88,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (128,28,88,0,106,101,89,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (129,28,89,0,107,88,96,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (130,28,96,0,108,89,90,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (131,28,90,0,109,96,71,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (132,28,71,0,110,90,30,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (133,28,30,0,111,71,91,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (134,28,91,0,112,30,92,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (135,28,92,0,113,91,75,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (136,28,75,0,114,92,6,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (137,28,6,0,115,75,93,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (138,28,93,0,116,6,94,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (139,28,94,0,117,93,95,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (140,28,95,0,118,94,50,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (141,28,50,0,119,95,96,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (142,28,96,0,120,50,97,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (143,28,97,0,121,96,40,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (144,28,40,0,122,97,98,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (145,28,98,0,123,40,71,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (146,28,71,0,124,98,6,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (147,28,6,0,125,71,99,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (148,28,99,0,126,6,100,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (149,28,100,0,127,99,101,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (150,28,101,0,128,100,6,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (151,28,6,0,129,101,102,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (152,28,102,0,130,6,6,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (153,28,6,0,131,102,39,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (154,28,39,0,132,6,85,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (155,28,85,0,133,39,88,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (156,28,88,0,134,85,12,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (157,28,12,0,135,88,29,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (158,28,29,0,136,12,103,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (159,28,103,0,137,29,83,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (160,28,83,0,138,103,104,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (161,28,104,0,139,83,105,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (162,28,105,0,140,104,106,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (163,28,106,0,141,105,50,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (164,28,50,0,142,106,116,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (165,28,116,0,143,50,107,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (166,28,107,0,144,116,108,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (167,28,108,0,145,107,109,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (168,28,109,0,146,108,83,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (169,28,83,0,147,109,110,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (170,28,110,0,148,83,101,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (171,28,101,0,149,110,114,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (172,28,114,0,150,101,115,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (173,28,115,0,151,114,111,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (174,28,111,0,152,115,112,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (175,28,112,0,153,111,113,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (176,28,113,0,154,112,114,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (177,28,114,0,155,113,115,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (178,28,115,0,156,114,116,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (179,28,116,0,157,115,117,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (180,28,117,0,158,116,118,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (181,28,118,0,159,117,119,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (182,28,119,0,160,118,53,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (183,28,53,0,161,119,120,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (184,28,120,0,162,53,121,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (185,28,121,0,163,120,122,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (186,28,122,0,164,121,123,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (187,28,123,0,165,122,124,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (188,28,124,0,166,123,125,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (189,28,125,0,167,124,126,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (190,28,126,0,168,125,127,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (191,28,127,0,169,126,128,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (192,28,128,0,170,127,84,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (193,28,84,0,171,128,129,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (194,28,129,0,172,84,0,2,1053688189,3,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (207,35,138,0,0,0,0,2,1053697410,6,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (204,32,3,0,0,0,0,1,1053697239,6,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (206,34,137,0,0,0,0,1,1053697388,6,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (205,33,136,0,0,0,0,1,1053697367,6,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (210,36,140,0,0,0,0,1,1053697576,6,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (212,37,141,0,0,0,0,1,1053697586,6,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (213,38,13,0,0,0,13,10,1053697630,6,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (214,38,13,0,1,13,13,10,1053697630,6,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (215,38,13,0,2,13,0,10,1053697630,6,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (216,39,13,0,0,0,142,8,1053697769,6,129);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (217,39,142,0,1,13,143,8,1053697769,6,129);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (218,39,143,0,2,142,144,8,1053697769,6,130);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (219,39,144,0,3,143,144,8,1053697769,6,131);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (220,39,144,0,4,144,0,8,1053697769,6,132);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (221,40,13,0,0,0,142,8,1053697961,5,129);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (222,40,142,0,1,13,144,8,1053697961,5,129);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (223,40,144,0,2,142,145,8,1053697961,5,130);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (224,40,145,0,3,144,146,8,1053697961,5,131);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (225,40,146,0,4,145,0,8,1053697961,5,132);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (226,41,3,0,0,0,0,1,1053698033,7,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (227,42,147,0,0,0,0,1,1053698046,7,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (235,45,13,0,0,0,151,2,1053698514,7,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (234,44,150,0,2,149,0,12,1053698475,7,147);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (233,44,149,0,1,13,150,12,1053698475,7,146);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (232,44,13,0,0,0,149,12,1053698475,7,146);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (236,45,151,0,1,13,152,2,1053698514,7,1);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (237,45,152,0,2,151,152,2,1053698514,7,120);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (238,45,152,0,3,152,0,2,1053698514,7,121);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (239,46,153,0,0,0,0,1,1053699789,4,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (240,47,138,0,0,0,88,10,1053699815,4,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (241,47,88,0,1,138,5,10,1053699815,4,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (242,47,5,0,2,88,88,10,1053699815,4,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (243,47,88,0,3,5,5,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (244,47,5,0,4,88,29,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (245,47,29,0,5,5,85,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (246,47,85,0,6,29,154,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (247,47,154,0,7,85,155,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (248,47,155,0,8,154,35,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (249,47,35,0,9,155,156,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (250,47,156,0,10,35,157,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (251,47,157,0,11,156,158,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (252,47,158,0,12,157,159,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (253,47,159,0,13,158,160,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (254,47,160,0,14,159,0,10,1053699815,4,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (255,49,161,0,0,0,0,1,1053699844,4,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (256,50,162,0,0,0,0,6,1053699973,4,124);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (257,51,163,0,0,0,0,6,1053699986,4,124);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (267,57,168,0,0,0,0,1,1053703449,2,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (266,56,13,0,0,0,0,10,1053702943,8,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (265,55,167,0,2,166,0,7,1053701308,4,128);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (264,55,166,0,1,13,167,7,1053701308,4,127);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (263,55,13,0,0,0,166,7,1053701308,4,127);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (268,58,169,0,0,0,0,1,1053703495,2,4);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (274,60,138,0,2,138,138,10,1053703694,2,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (273,60,138,0,1,138,138,10,1053703694,2,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (272,60,138,0,0,0,138,10,1053703694,2,140);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (275,60,138,0,3,138,138,10,1053703694,2,141);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (276,60,138,0,4,138,0,10,1053703694,2,141);

--
-- Dumping data for table 'ezsearch_return_count'
--



--
-- Dumping data for table 'ezsearch_search_phrase'
--



--
-- Dumping data for table 'ezsearch_word'
--


INSERT INTO ezsearch_word (id, word, object_count) VALUES (1,'white',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (2,'box',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (3,'news',4);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (4,'crossroads',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (5,'forum',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (6,'the',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (7,'book',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (8,'corner',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (9,'my',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (139,'company',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (11,'intranet',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (12,'site',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (13,'test',8);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (14,'frontpage',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (15,'sport',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (16,'action',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (17,'world',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (18,'leisure',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (19,'food',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (20,'soul',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (21,'soulfood.no',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (22,'result',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (23,'passionate',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (24,'interest',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (25,'photography',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (26,'people',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (27,'interesting',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (28,'runs',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (29,'is',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (30,'a',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (31,'very',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (32,'good',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (33,'example',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (34,'what',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (35,'you',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (36,'can',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (37,'do',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (38,'content',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (39,'design',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (40,'an',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (41,'publish',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (42,'powered',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (43,'christian',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (44,'b',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (45,'1972',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (46,'freelance',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (47,'photographer',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (48,'educated',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (49,'usa',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (50,'and',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (51,'have',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (52,'worked',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (53,'with',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (54,'advertising',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (55,'portraits',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (56,'travelling',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (57,'since',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (58,'1994',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (59,'on',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (60,'last',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (61,'travel',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (62,'during',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (63,'winter',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (64,'1999',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (65,'houge',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (66,'spend',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (67,'six',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (68,'months',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (69,'exile',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (70,'tibetanians',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (71,'in',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (72,'south',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (73,'north',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (74,'india',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (75,'as',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (76,'well',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (77,'nepal',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (78,'many',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (79,'his',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (80,'pictures',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (81,'are',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (82,'influenced',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (83,'by',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (84,'visit',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (85,'for',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (86,'long',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (87,'periods',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (88,'this',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (89,'stay',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (90,'lived',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (91,'tibetanian',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (92,'monastery',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (93,'only',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (94,'western',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (95,'representative',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (96,'he',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (97,'got',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (98,'insight',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (99,'daily',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (100,'life',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (101,'of',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (102,'munks',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (103,'made',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (104,'sigurd',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (105,'kristiansen',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (106,'superstar.no',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (107,'been',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (108,'set',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (109,'up',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (110,'one',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (111,'official',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (112,'partners',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (113,'petraflux.com',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (114,'ez',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (115,'systems',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (116,'has',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (117,'assisted',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (118,'our',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (119,'partner',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (120,'support',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (121,'automatical',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (122,'image',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (123,'import',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (124,'was',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (125,'created',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (126,'amongst',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (127,'other',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (128,'things',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (129,'soulfood',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (138,'about',3);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (137,'services',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (136,'products',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (140,'software',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (141,'servers',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (142,'product',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (143,'3',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (144,'123',2);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (145,'123123sdfsdfsdf',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (146,'sd',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (147,'files',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (149,'file',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (150,'bla',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (151,'article',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (152,'tet',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (153,'links',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (154,'discussion',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (155,'bet',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (156,'didn',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (157,'t',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (158,'figure',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (159,'that',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (160,'out',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (161,'forums',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (162,'sports',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (163,'computers',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (167,'hihi',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (166,'topic',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (168,'landscape',1);
INSERT INTO ezsearch_word (id, word, object_count) VALUES (169,'flowers',1);

--
-- Dumping data for table 'ezsection'
--


INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (2,'Whitebox','','ezusernavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (3,'News section','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (4,'Crossroards forum','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (5,'The book corner','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (6,'My company','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (7,'Intranet','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (8,'My site','','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (9,'Users','','ezusernavigationpart');

--
-- Dumping data for table 'ezsession'
--



--
-- Dumping data for table 'ezsubtree_notification_rule'
--



--
-- Dumping data for table 'eztrigger'
--



--
-- Dumping data for table 'ezurl'
--



--
-- Dumping data for table 'ezuser'
--


INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');

--
-- Dumping data for table 'ezuser_accountkey'
--



--
-- Dumping data for table 'ezuser_discountrule'
--



--
-- Dumping data for table 'ezuser_role'
--


INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (25,2,12);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (36,17,10);

--
-- Dumping data for table 'ezuser_setting'
--


INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (10,1,1000);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (14,1,10);

--
-- Dumping data for table 'ezvattype'
--


INSERT INTO ezvattype (id, name, percentage) VALUES (1,'Std',0);

--
-- Dumping data for table 'ezwaituntildatevalue'
--



--
-- Dumping data for table 'ezwishlist'
--



--
-- Dumping data for table 'ezworkflow'
--


INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES (1,0,0,'group_ezserial','Sp\'s forkflow',8,24,1031927869,1032856662);

--
-- Dumping data for table 'ezworkflow_assign'
--



--
-- Dumping data for table 'ezworkflow_event'
--


INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);

--
-- Dumping data for table 'ezworkflow_group'
--


INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Standard',-1,-1,1024392098,1024392098);

--
-- Dumping data for table 'ezworkflow_group_link'
--


INSERT INTO ezworkflow_group_link (workflow_id, group_id, workflow_version, group_name) VALUES (1,1,0,'Standard');

--
-- Dumping data for table 'ezworkflow_process'
--



