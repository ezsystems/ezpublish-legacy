























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








INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (1, 'Content', 1, 14, 1031216928, 1033922106);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (2, 'Users', 1, 14, 1031216941, 1033922113);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (3, 'Media', 8, 14, 1032009743, 1033922120);







INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (1, 0, 1, 1, 'Frontpage', 1, 0, 1033917596, 1033917596, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (4, 0, 2, 3, 'Users', 1, 0, 0, 0, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (10, 8, 2, 4, 'Anonymous User', 1, 0, 1033920665, 1033920665, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (11, 8, 2, 3, 'Guest accounts', 1, 0, 1033920746, 1033920746, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (12, 8, 2, 3, 'Administrator users', 1, 0, 1033920775, 1033920775, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (13, 8, 2, 3, 'Editors', 1, 0, 1033920794, 1033920794, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (14, 8, 2, 4, 'Administrator User', 1, 0, 1033920830, 1033920830, 1, '');







INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (1, 'eng-GB', 1, 1, 4, 'Frontpage', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (2, 'eng-GB', 1, 1, 119, '<?xml version="1.0"><section><paragraph>This folder contains some information about...</paragraph></section>', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (7, 'eng-GB', 1, 4, 5, 'Main group', NULL, NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (8, 'eng-GB', 1, 4, 6, 'Users', NULL, NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (21, 'eng-GB', 1, 10, 12, '', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (22, 'eng-GB', 1, 11, 6, 'Guest accounts', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (19, 'eng-GB', 1, 10, 8, 'Anonymous', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (20, 'eng-GB', 1, 10, 9, 'User', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (23, 'eng-GB', 1, 11, 7, '', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (24, 'eng-GB', 1, 12, 6, 'Administrator users', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (25, 'eng-GB', 1, 12, 7, '', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (26, 'eng-GB', 1, 13, 6, 'Editors', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (27, 'eng-GB', 1, 13, 7, '', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (28, 'eng-GB', 1, 14, 8, 'Administrator', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (29, 'eng-GB', 1, 14, 9, 'User', 0, 0);
INSERT INTO ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (30, 'eng-GB', 1, 14, 12, '', 0, 0);














INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (1, 1, 1, 0, 1, 1, NULL, 0, '/1/', NULL, 1, 1, 0, NULL);
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (2, 2, 1, 1, 1, 1, 0, 1, '/1/2/', '', 1, 1, 0, 'd41d8cd98f00b204e9800998ecf8427e');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (5, 5, 1, 4, 1, 0, -195235522, 1, '/1/5/', '__1', 1, 1, 0, '08a9d0bbf3381652f7cca8738b5a8469');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (11, 11, 5, 10, 1, 1, 1015610524, 2, '/1/5/11/', '__1/anonymous_user', 1, 1, 0, 'a59d2313b486e0f43477433525edea9b');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (12, 12, 5, 11, 1, 1, 1857785444, 2, '/1/5/12/', '__1/guest_accounts', 1, 1, 0, 'c894997127008ea742913062f39adfc5');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (13, 13, 5, 12, 1, 1, -1978139175, 2, '/1/5/13/', '__1/administrator_users', 1, 1, 0, 'caeccbc33185f04d92e2b6cb83b1c7e4');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (15, 15, 13, 14, 1, 1, -852704961, 3, '/1/5/13/15/', '__1/administrator_users/administrator_user', 1, 1, 0, '2c3f2814cfa91bcb17d7893ca6f8a0c4');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (14, 14, 5, 13, 1, 1, 2094553782, 2, '/1/5/14/', '__1/editors', 1, 1, 0, '39f6f6f51c1e3a922600b2d415d7a46d');







INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (436, 1, 8, 1, 1033919080, 1033919080, 1, 1, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (4, 4, 8, 1, 1033919080, 1033919080, 1, 1, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (438, 10, 8, 1, 1033920649, 1033920665, 1, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (439, 11, 8, 1, 1033920737, 1033920746, 1, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (440, 12, 8, 1, 1033920760, 1033920775, 1, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (441, 13, 8, 1, 1033920786, 1033920794, 1, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (442, 14, 8, 1, 1033920808, 1033920830, 1, 0, 0, NULL);

















































INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (2, 1, 1, 1, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (3, 4, 1, 1, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (148, 9, 1, 2, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (149, 10, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (150, 11, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (151, 12, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (152, 13, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (153, 14, 1, 13, 1, 1, 1, 0, NULL);














INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (308, 2, '*', '*', '*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (315, 1, 'read', 'content', ' ');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (316, 1, 'login', 'user', '*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (317, 3, '*', 'content', '*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (318, 3, 'login', 'user', '*');







INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (245, 315, 'Class', 0, 'read', 'content');







INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (409, 245, 1);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (410, 245, 7);





















INSERT INTO ezrole (id, "version", name, value) VALUES (2, 0, 'Administrator', '*');
INSERT INTO ezrole (id, "version", name, value) VALUES (1, 0, 'Anonymous', ' ');
INSERT INTO ezrole (id, "version", name, value) VALUES (3, 0, 'Editor', ' ');



































INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (1, 'Standard section', 'nor-NO', 'ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (2, 'Users section', '', 'ezusernavigationpart');







INSERT INTO ezsession (session_key, expiration_time, data) VALUES ('7a244467fd70e3ec35bb977be0b1dc6a', 1048773857, 'LastAccessesURI|s:20:"/workflow/grouplist/";eZUserInfoCache_Timestamp|i:1048514591;eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_Timestamp|i:1048514591;eZUserGroupsCache_10|a:1:{i:0;a:2:{i:0;s:1:"4";s:2:"id";s:1:"4";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1048514591;UserRoles|a:1:{i:0;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:13:"Administrator";s:4:"name";s:13:"Administrator";}}canInstantiateClassesCachedForUser|s:2:"14";classesCachedTimestamp|i:1048514624;canInstantiateClasses|i:1;eZUserLoggedInID|s:2:"14";eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_14|a:1:{i:0;a:2:{i:0;s:2:"12";s:2:"id";s:2:"12";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}BrowseFromPage|s:18:"/section/assign/2/";BrowseActionName|s:13:"AssignSection";BrowseReturnType|s:6:"NodeID";CustomActionButton|N;BrowseSelectionType|N;canInstantiateClassList|a:5:{i:0;a:4:{i:0;s:1:"1";s:2:"id";s:1:"1";i:1;s:6:"Folder";s:4:"name";s:6:"Folder";}i:1;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:7:"Article";s:4:"name";s:7:"Article";}i:2;a:4:{i:0;s:1:"3";s:2:"id";s:1:"3";i:1;s:10:"User group";s:4:"name";s:10:"User group";}i:3;a:4:{i:0;s:1:"4";s:2:"id";s:1:"4";i:1;s:4:"User";s:4:"name";s:4:"User";}i:4;a:4:{i:0;s:1:"5";s:2:"id";s:1:"5";i:1;s:5:"Image";s:4:"name";s:5:"Image";}}eZUserDiscountRulesTimestamp|i:1048514636;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:"id";s:1:"2";}');
INSERT INTO ezsession (session_key, expiration_time, data) VALUES ('74791676e2ee2281d335a9aac6d8c752', 1048779918, 'LastAccessesURI|s:19:"/class/classlist/3/";eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserInfoCache_Timestamp|i:1048520517;!eZUserGroupsCache_10|eZUserGroupsCache_Timestamp|i:1048520517;UserRoles|a:1:{i:0;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:13:"Administrator";s:4:"name";s:13:"Administrator";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1048520517;!eZUserDiscountRules10|eZUserDiscountRulesTimestamp|i:1048520517;eZGlobalSection|a:1:{s:2:"id";s:1:"1";}canInstantiateClassesCachedForUser|s:2:"14";canInstantiateClasses|i:1;eZUserLoggedInID|s:2:"14";eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_14|a:1:{i:0;a:2:{i:0;s:2:"12";s:2:"id";s:2:"12";}}eZUserDiscountRules14|a:0:{}classesCachedForUser|s:2:"14";classesCachedTimestamp|i:1048520517;canInstantiateClassList|a:5:{i:0;a:4:{i:0;s:1:"1";s:2:"id";s:1:"1";i:1;s:6:"Folder";s:4:"name";s:6:"Folder";}i:1;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:7:"Article";s:4:"name";s:7:"Article";}i:2;a:4:{i:0;s:1:"3";s:2:"id";s:1:"3";i:1;s:10:"User group";s:4:"name";s:10:"User group";}i:3;a:4:{i:0;s:1:"4";s:2:"id";s:1:"4";i:1;s:4:"User";s:4:"name";s:4:"User";}i:4;a:4:{i:0;s:1:"5";s:2:"id";s:1:"5";i:1;s:5:"Image";s:4:"name";s:5:"Image";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}FromGroupID|s:1:"3";');














INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (10, 'anonymous', 'nospam@ez.no', 2, '4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (14, 'admin', 'nospam@ez.no', 2, 'c78e3b0f3d9244ed8c6d1c29464bdff9');







INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (24, 1, 4);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (25, 2, 12);







INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (10, 1, 1000);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (14, 1, 10);














INSERT INTO ezworkflow (id, "version", workflow_type_string, name, creator_id, modifier_id, created, modified, is_enabled) VALUES (1, 0, 'group_ezserial', 'Sp''s forkflow', 8, 24, 1031927869, 1032856662, 0);














INSERT INTO ezworkflow_event (id, "version", workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (18, 0, 1, 'event_ezapprove', '3333333333', 0, 0, 0, 0, '', '', '', '', 1);
INSERT INTO ezworkflow_event (id, "version", workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (20, 0, 1, 'event_ezmessage', 'foooooo', 0, 0, 0, 0, 'eeeeeeeeeeeeeeeeee', '', '', '', 2);







INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (1, 'Standard', -1, -1, 1024392098, 1024392098);







INSERT INTO ezworkflow_group_link (workflow_id, group_id, workflow_version, group_name) VALUES (1, 1, 0, 'Standard');













































































INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1, 'Frontpage', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (4, 'Users', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (10, 'Anonymous User', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (11, 'Guest accounts', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (12, 'Administrator users', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (13, 'Editors', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14, 'Administrator User', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (1, 'Frontpage', 1, 'nor-NO', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (4, 'Users', 1, 'nor-NO', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (10, 'Anonymous User', 1, 'nor-NO', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (11, 'Guest accounts', 1, 'nor-NO', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (12, 'Administrator users', 1, 'nor-NO', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (13, 'Editors', 1, 'nor-NO', 'eng-GB');
INSERT INTO ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) VALUES (14, 'Administrator User', 1, 'nor-NO', 'eng-GB');
















































































































SELECT pg_catalog.setval ('ezbasket_s', 1, false);







SELECT pg_catalog.setval ('ezcontentclass_s', 14, true);







SELECT pg_catalog.setval ('ezcontentclass_attribute_s', 152, true);







SELECT pg_catalog.setval ('ezcontentclassgroup_s', 4, true);







SELECT pg_catalog.setval ('ezcontentobject_s', 15, true);







SELECT pg_catalog.setval ('ezcontentobject_attribute_s', 371, true);







SELECT pg_catalog.setval ('ezcontentobject_link_s', 1, false);







SELECT pg_catalog.setval ('ezcontentobject_tree_s', 16, true);







SELECT pg_catalog.setval ('ezcontentobject_version_s', 443, true);







SELECT pg_catalog.setval ('ezenumvalue_s', 1, false);







SELECT pg_catalog.setval ('ezmodule_run_s', 1, false);







SELECT pg_catalog.setval ('eznode_assignment_s', 154, true);







SELECT pg_catalog.setval ('ezorder_s', 1, false);







SELECT pg_catalog.setval ('ezpolicy_s', 318, true);







SELECT pg_catalog.setval ('ezpolicy_limitation_s', 245, true);







SELECT pg_catalog.setval ('ezpolicy_limitation_value_s', 410, true);







SELECT pg_catalog.setval ('ezproductcollection_s', 1, false);







SELECT pg_catalog.setval ('ezproductcollection_item_s', 1, false);







SELECT pg_catalog.setval ('ezrole_s', 5, true);







SELECT pg_catalog.setval ('ezsearch_object_word_link_s', 1, false);







SELECT pg_catalog.setval ('ezsearch_return_count_s', 1, false);







SELECT pg_catalog.setval ('ezsearch_search_phrase_s', 1, false);







SELECT pg_catalog.setval ('ezsearch_word_s', 1, false);







SELECT pg_catalog.setval ('ezsection_s', 2, true);







SELECT pg_catalog.setval ('eztrigger_s', 1, false);







SELECT pg_catalog.setval ('ezuser_role_s', 26, false);







SELECT pg_catalog.setval ('ezwishlist_s', 1, false);







SELECT pg_catalog.setval ('ezworkflow_s', 2, false);







SELECT pg_catalog.setval ('ezworkflow_assign_s', 1, false);







SELECT pg_catalog.setval ('ezworkflow_event_s', 3, false);







SELECT pg_catalog.setval ('ezworkflow_group_s', 2, false);







SELECT pg_catalog.setval ('ezworkflow_process_s', 1, false);







SELECT pg_catalog.setval ('ezoperation_memento_s', 1, false);







SELECT pg_catalog.setval ('ezdiscountsubrule_s', 1, false);







SELECT pg_catalog.setval ('ezinfocollection_s', 1, false);







SELECT pg_catalog.setval ('ezinfocollection_attribute_s', 1, false);







SELECT pg_catalog.setval ('ezuser_discountrule_s', 1, false);







SELECT pg_catalog.setval ('ezvattype_s', 1, false);







SELECT pg_catalog.setval ('ezdiscountrule_s', 1, false);







SELECT pg_catalog.setval ('ezorder_item_s', 1, false);







SELECT pg_catalog.setval ('ezwaituntildatevalue_s', 1, false);







SELECT pg_catalog.setval ('ezcontent_translation_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_item_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_group_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_item_message_link_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_simple_message_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_profile_s', 1, false);







SELECT pg_catalog.setval ('ezapprove_items_s', 1, false);







SELECT pg_catalog.setval ('ezurl_s', 1, false);







SELECT pg_catalog.setval ('ezmessage_s', 1, false);







SELECT pg_catalog.setval ('ezproductcollection_item_opt_s', 1, false);







SELECT pg_catalog.setval ('ezforgot_password_s', 1, false);


