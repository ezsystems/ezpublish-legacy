






























































































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






INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (3,'Media',8,14,1032009743,1033922120);






INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (1,14,1,1,'Root folder',1,0,1033917596,1033917596,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (4,14,2,3,'Users',1,0,0,0,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (10,14,2,4,'Anonymous User',1,0,1033920665,1033920665,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (11,14,2,3,'Guest accounts',1,0,1033920746,1033920746,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (12,14,2,3,'Administrator users',1,0,1033920775,1033920775,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (13,14,2,3,'Editors',1,0,1033920794,1033920794,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (14,14,2,4,'Administrator User',1,0,1033920830,1033920830,1,NULL);
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (40,14,2,4,'test test',1,0,1053613020,1053613020,1,'');






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
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (95,'eng-GB',1,40,8,'test',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (96,'eng-GB',1,40,9,'test',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (97,'eng-GB',1,40,12,'',0,0);












INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1,'Root folder',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (4,'Users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (10,'Anonymous User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (11,'Guest accounts',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (12,'Administrator users',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (13,'Editors',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14,'Administrator User',1,'eng-GB','eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (40,'test test',1,'eng-GB','eng-GB');






INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (1,1,0,1,1,NULL,0,'/1/',1,1,0,NULL,1,NULL);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (2,1,1,1,1,0,1,'/1/2/',1,1,0,'',2,'d41d8cd98f00b204e9800998ecf8427e');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (5,1,4,1,0,-195235522,1,'/1/5/',1,1,0,'__1',5,'08a9d0bbf3381652f7cca8738b5a8469');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (11,5,10,1,1,1015610524,2,'/1/5/11/',1,1,0,'__1/anonymous_user',11,'a59d2313b486e0f43477433525edea9b');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (12,5,11,1,1,1857785444,2,'/1/5/12/',1,1,0,'__1/guest_accounts',12,'c894997127008ea742913062f39adfc5');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (13,5,12,1,1,-1978139175,2,'/1/5/13/',1,1,0,'__1/administrator_users',13,'caeccbc33185f04d92e2b6cb83b1c7e4');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (14,5,13,1,1,2094553782,2,'/1/5/14/',1,1,0,'__1/editors',14,'39f6f6f51c1e3a922600b2d415d7a46d');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (15,13,14,1,1,-852704961,3,'/1/5/13/15/',1,1,0,'__1/administrator_users/administrator_user',15,'2c3f2814cfa91bcb17d7893ca6f8a0c4');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, md5_path) VALUES (42,12,40,1,1,1079898840,3,'/1/5/12/42/',9,1,0,'__1/guest_accounts/test_test',42,'89deb24e7d441d8088ee611c7b5c5a95');






INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (1,1,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (4,4,14,1,0,0,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (436,1,14,2,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (438,10,14,1,1033920649,1033920665,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (439,11,14,1,1033920737,1033920746,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (440,12,14,1,1033920760,1033920775,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (441,13,14,1,1033920786,1033920794,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (442,14,14,1,1033920808,1033920830,1,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (471,40,14,1,1053613007,1053613020,1,0,0);






























INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (2,136,0,'Ok','3',2);
INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (1,136,0,'Poor','2',1);
INSERT INTO ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) VALUES (3,136,0,'Good','5',3);








































































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
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) VALUES (181,40,1,12,9,1,1,0,0);










































INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (317,3,'*','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (308,2,'*','*','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (326,1,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (325,1,'login','user','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (319,3,'login','user','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (323,5,'*','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (324,5,'login','user','*');






INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (249,326,'Class',0,'read','content');






INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (435,249,'1');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (436,249,'10');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (437,249,'10');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (438,249,'11');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (439,249,'11');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (440,249,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (441,249,'12');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (442,249,'13');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (443,249,'13');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (444,249,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (445,249,'2');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (446,249,'5');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (447,249,'5');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (448,249,'6');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (449,249,'6');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (450,249,'7');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (451,249,'7');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (452,249,'8');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (453,249,'8');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (454,249,'9');
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (455,249,'9');
























INSERT INTO ezrole (id, version, name, value) VALUES (1,0,'Anonymous','');
INSERT INTO ezrole (id, version, name, value) VALUES (2,0,'Administrator','*');
INSERT INTO ezrole (id, version, name, value) VALUES (3,0,'Editor','');
INSERT INTO ezrole (id, version, name, value) VALUES (5,3,'Editor',NULL);






INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (26,40,5,0,0,0,5,4,1053613020,2,8);
INSERT INTO ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id) VALUES (27,40,5,0,1,5,0,4,1053613020,2,9);






INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (1,1,1053603212,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (2,2,1053603219,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (3,2,1053603317,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (4,3,1053603339,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (5,4,1053603362,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (6,4,1053603366,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (7,5,1053603417,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (8,5,1053603976,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (9,5,1053604331,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (10,5,1053604371,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (11,5,1053604407,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (12,5,1053604433,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (13,5,1053604493,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (14,5,1053604514,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (15,5,1053604563,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (16,5,1053604605,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (17,5,1053604624,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (18,5,1053604678,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (19,5,1053604748,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (20,5,1053604807,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (21,5,1053604826,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (22,5,1053604937,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (23,5,1053604982,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (24,5,1053605043,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (25,5,1053606010,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (26,5,1053606051,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (27,5,1053606081,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (28,5,1053606198,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (29,5,1053606233,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (30,5,1053606265,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (31,5,1053606293,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (32,5,1053606343,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (33,5,1053606415,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (34,5,1053606522,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (35,5,1053606559,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (36,5,1053606688,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (37,5,1053606732,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (38,5,1053606775,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (39,5,1053606808,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (40,5,1053606853,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (41,5,1053606903,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (42,5,1053606949,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (43,5,1053607040,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (44,5,1053607066,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (45,5,1053607089,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (46,5,1053607114,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (47,5,1053607137,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (48,5,1053607190,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (49,5,1053607233,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (50,5,1053607265,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (51,5,1053607299,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (52,5,1053607352,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (53,5,1053607524,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (54,5,1053607557,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (55,5,1053607599,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (56,5,1053607626,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (57,5,1053607655,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (58,5,1053607690,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (59,5,1053607725,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (60,5,1053608672,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (61,5,1053608711,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (62,5,1053608797,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (63,5,1053608861,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (64,5,1053608907,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (65,5,1053608953,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (66,5,1053608996,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (67,5,1053609019,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (68,5,1053609058,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (69,5,1053609197,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (70,5,1053609408,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (71,6,1053609435,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (72,7,1053609446,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (73,8,1053609450,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (74,9,1053609801,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (75,10,1053609804,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (76,9,1053609809,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (77,9,1053609821,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (78,9,1053609823,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (79,9,1053609828,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (80,9,1053609836,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (81,5,1053611177,1);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (82,5,1053611230,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (83,5,1053612092,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (84,5,1053612155,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (85,5,1053612195,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (86,5,1053612197,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (87,5,1053612237,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (88,5,1053612245,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (89,5,1053612332,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (90,5,1053612404,3);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (91,5,1053612694,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (92,5,1053612699,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (93,2,1053612736,0);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (94,5,1053612977,16);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (95,5,1053612986,16);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (96,5,1053612991,16);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (97,5,1053613125,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (98,5,1053613272,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (99,5,1053613278,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (100,5,1053613281,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (101,5,1053613297,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (102,5,1053613300,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (103,5,1053614865,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (104,5,1053614878,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (105,5,1053614902,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (106,5,1053614952,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (107,5,1053615196,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (108,5,1053615246,15);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (109,5,1053616143,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (110,5,1053679445,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (111,5,1053679454,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (112,5,1053679458,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (113,5,1053679462,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (114,5,1053679466,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (115,5,1053679471,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (116,5,1053681400,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (117,5,1053681419,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (118,5,1053681442,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (119,5,1053681678,17);
INSERT INTO ezsearch_return_count (id, phrase_id, time, count) VALUES (120,5,1053681694,17);






INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (1,'user');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (2,'Administrator');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (3,'Guest');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (4,'Editors');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (5,'test');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (6,'Anonymous User');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (7,'Article');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (8,'s');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (9,'t');
INSERT INTO ezsearch_search_phrase (id, phrase) VALUES (10,'');






INSERT INTO ezsearch_word (id, word, object_count) VALUES (5,'test',1);






INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (2,'Users','','ezusernavigationpart');






























INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (40,'test','test@test.com',2,'be778b473235e210cc577056226536a4');


















INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (29,1,10);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (25,2,12);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (30,3,13);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (28,1,11);






INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (10,1,1000);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (14,1,10);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (23,1,0);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (40,1,0);






INSERT INTO ezvattype (id, name, percentage) VALUES (1,'Std',0);


















INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES (1,0,0,'group_ezserial','Sp\'s forkflow',8,24,1031927869,1032856662);












INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);






INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Standard',-1,-1,1024392098,1024392098);






INSERT INTO ezworkflow_group_link (workflow_id, group_id, workflow_version, group_name) VALUES (1,1,0,'Standard');







