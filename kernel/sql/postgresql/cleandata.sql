--
-- PostgreSQL database dump
--

--
-- Data for TOC entry 53 (OID 18805)
-- Name: ezbasket; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 54 (OID 18810)
-- Name: ezbinaryfile; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 55 (OID 18816)
-- Name: ezcontentclass; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezcontentclass (id, "version", name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (3, 0, 'User group', 'user_group', '<name>', -1, 14, 1024392098, 1033922064);
INSERT INTO ezcontentclass (id, "version", name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (4, 0, 'User', 'user', '<first_name> <last_name>', -1, 14, 1024392098, 1033922083);
INSERT INTO ezcontentclass (id, "version", name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (1, 0, 'Folder', 'folder', '<name>', -1, 14, 1024392098, 1048520596);
INSERT INTO ezcontentclass (id, "version", name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2, 0, 'Article', 'article', '<title>', -1, 14, 1024392098, 1048520632);
INSERT INTO ezcontentclass (id, "version", name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (5, 0, 'Image', 'image', '<name>', 8, 14, 1031484992, 1048520714);


--
-- Data for TOC entry 56 (OID 18823)
-- Name: ezcontentclass_attribute; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (7, 0, 3, 'description', 'Description', 'ezstring', 2, 1, 0, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (6, 0, 3, 'name', 'Name', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (9, 0, 4, 'last_name', 'Last name', 'ezstring', 2, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (8, 0, 4, 'first_name', 'First name', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (12, 0, 4, 'user_account', 'User account', 'ezuser', 3, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (4, 0, 1, 'name', 'Name', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, 'Folder', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (119, 0, 1, 'description', 'Description', 'ezxmltext', 2, 1, 0, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (1, 0, 2, 'title', 'Title', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, 'New article', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (120, 0, 2, 'intro', 'Intro', 'ezxmltext', 2, 1, 1, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (121, 0, 2, 'body', 'Body', 'ezxmltext', 3, 1, 0, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (122, 0, 2, 'thumbnail', 'Thumbnail', 'ezimage', 4, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (123, 0, 2, 'enable_comments', 'Enable comments', 'ezboolean', 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (116, 0, 5, 'name', 'Name', 'ezstring', 1, 1, 1, 150, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (117, 0, 5, 'caption', 'Caption', 'ezxmltext', 2, 1, 0, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);
INSERT INTO ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, placement, is_searchable, is_required, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, is_information_collector) VALUES (118, 0, 5, 'image', 'Image', 'ezimage', 3, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0);


--
-- Data for TOC entry 57 (OID 18831)
-- Name: ezcontentclass_classgroup; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (4, 0, 2, 'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (3, 0, 2, '');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (1, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (2, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (5, 0, 3, 'Media');


--
-- Data for TOC entry 58 (OID 18837)
-- Name: ezcontentclassgroup; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (1, 'Content', 1, 14, 1031216928, 1033922106);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (2, 'Users', 1, 14, 1031216941, 1033922113);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (3, 'Media', 8, 14, 1032009743, 1033922120);


--
-- Data for TOC entry 59 (OID 18844)
-- Name: ezcontentobject; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (1, 0, 1, 1, 'Frontpage', 1, 0, 1033917596, 1033917596, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (4, 0, 2, 3, 'Users', 1, 0, 0, 0, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (10, 8, 2, 4, 'Anonymous User', 1, 0, 1033920665, 1033920665, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (11, 8, 2, 3, 'Guest accounts', 1, 0, 1033920746, 1033920746, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (12, 8, 2, 3, 'Administrator users', 1, 0, 1033920775, 1033920775, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (13, 8, 2, 3, 'Editors', 1, 0, 1033920794, 1033920794, 1, '');
INSERT INTO ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) VALUES (14, 8, 2, 4, 'Administrator User', 1, 0, 1033920830, 1033920830, 1, '');


--
-- Data for TOC entry 60 (OID 18855)
-- Name: ezcontentobject_attribute; Type: TABLE DATA; Schema: public; Owner: sp
--

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


--
-- Data for TOC entry 61 (OID 18865)
-- Name: ezcontentobject_link; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 62 (OID 18872)
-- Name: ezcontentobject_tree; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (1, 1, 1, 0, 1, 1, NULL, 0, '/1/', NULL, 1, 1, 0, NULL);
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (2, 2, 1, 1, 1, 1, 0, 1, '/1/2/', '', 1, 1, 0, 'd41d8cd98f00b204e9800998ecf8427e');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (5, 5, 1, 4, 1, 0, -195235522, 1, '/1/5/', '__1', 1, 1, 0, '08a9d0bbf3381652f7cca8738b5a8469');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (11, 11, 5, 10, 1, 1, 1015610524, 2, '/1/5/11/', '__1/anonymous_user', 1, 1, 0, 'a59d2313b486e0f43477433525edea9b');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (12, 12, 5, 11, 1, 1, 1857785444, 2, '/1/5/12/', '__1/guest_accounts', 1, 1, 0, 'c894997127008ea742913062f39adfc5');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (13, 13, 5, 12, 1, 1, -1978139175, 2, '/1/5/13/', '__1/administrator_users', 1, 1, 0, 'caeccbc33185f04d92e2b6cb83b1c7e4');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (15, 15, 13, 14, 1, 1, -852704961, 3, '/1/5/13/15/', '__1/administrator_users/administrator_user', 1, 1, 0, '2c3f2814cfa91bcb17d7893ca6f8a0c4');
INSERT INTO ezcontentobject_tree (node_id, main_node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, path_identification_string, sort_field, sort_order, priority, md5_path) VALUES (14, 14, 5, 13, 1, 1, 2094553782, 2, '/1/5/14/', '__1/editors', 1, 1, 0, '39f6f6f51c1e3a922600b2d415d7a46d');


--
-- Data for TOC entry 63 (OID 18885)
-- Name: ezcontentobject_version; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (436, 1, 8, 1, 1033919080, 1033919080, 1, 1, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (4, 4, 8, 1, 1033919080, 1033919080, 1, 1, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (438, 10, 8, 1, 1033920649, 1033920665, 0, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (439, 11, 8, 1, 1033920737, 1033920746, 0, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (440, 12, 8, 1, 1033920760, 1033920775, 0, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (441, 13, 8, 1, 1033920786, 1033920794, 0, 0, 0, NULL);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, workflow_event_pos, user_id, main_node_id) VALUES (442, 14, 8, 1, 1033920808, 1033920830, 0, 0, 0, NULL);


--
-- Data for TOC entry 64 (OID 18897)
-- Name: ezenumobjectvalue; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 65 (OID 18903)
-- Name: ezenumvalue; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 66 (OID 18908)
-- Name: ezimage; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 67 (OID 18913)
-- Name: ezimagevariation; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 68 (OID 18917)
-- Name: ezmedia; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 69 (OID 18923)
-- Name: ezmodule_run; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 70 (OID 18933)
-- Name: eznode_assignment; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (2, 1, 1, 1, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (3, 4, 1, 1, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (148, 9, 1, 2, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (149, 10, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (150, 11, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (151, 12, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (152, 13, 1, 5, 1, 1, 1, 0, NULL);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, is_main, sort_field, sort_order, from_node_id, remote_id) VALUES (153, 14, 1, 13, 1, 1, 1, 0, NULL);


--
-- Data for TOC entry 71 (OID 18943)
-- Name: ezorder; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 72 (OID 18957)
-- Name: ezpolicy; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (308, 2, '*', '*', '*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (315, 1, 'read', 'content', ' ');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (316, 1, 'login', 'user', '*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (317, 3, '*', 'content', '*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (318, 3, 'login', 'user', '*');


--
-- Data for TOC entry 73 (OID 18967)
-- Name: ezpolicy_limitation; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (245, 315, 'Class', 0, 'read', 'content');


--
-- Data for TOC entry 74 (OID 18977)
-- Name: ezpolicy_limitation_value; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (409, 245, 1);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (410, 245, 7);


--
-- Data for TOC entry 75 (OID 18984)
-- Name: ezproductcollection; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 76 (OID 18991)
-- Name: ezproductcollection_item; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 77 (OID 19000)
-- Name: ezrole; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezrole (id, "version", name, value) VALUES (2, 0, 'Administrator', '*');
INSERT INTO ezrole (id, "version", name, value) VALUES (1, 0, 'Anonymous', ' ');
INSERT INTO ezrole (id, "version", name, value) VALUES (3, 0, 'Editor', ' ');


--
-- Data for TOC entry 78 (OID 19011)
-- Name: ezsearch_object_word_link; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 79 (OID 19020)
-- Name: ezsearch_return_count; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 80 (OID 19027)
-- Name: ezsearch_search_phrase; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 81 (OID 19034)
-- Name: ezsearch_word; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 82 (OID 19041)
-- Name: ezsection; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (1, 'Standard section', 'nor-NO', 'ezcontentnavigationpart');
INSERT INTO ezsection (id, name, locale, navigation_part_identifier) VALUES (2, 'Users section', '', 'ezusernavigationpart');


--
-- Data for TOC entry 83 (OID 19047)
-- Name: ezsession; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezsession (session_key, expiration_time, data) VALUES ('7a244467fd70e3ec35bb977be0b1dc6a', 1048773857, 'LastAccessesURI|s:20:"/workflow/grouplist/";eZUserInfoCache_Timestamp|i:1048514591;eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_Timestamp|i:1048514591;eZUserGroupsCache_10|a:1:{i:0;a:2:{i:0;s:1:"4";s:2:"id";s:1:"4";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1048514591;UserRoles|a:1:{i:0;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:13:"Administrator";s:4:"name";s:13:"Administrator";}}canInstantiateClassesCachedForUser|s:2:"14";classesCachedTimestamp|i:1048514624;canInstantiateClasses|i:1;eZUserLoggedInID|s:2:"14";eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_14|a:1:{i:0;a:2:{i:0;s:2:"12";s:2:"id";s:2:"12";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}BrowseFromPage|s:18:"/section/assign/2/";BrowseActionName|s:13:"AssignSection";BrowseReturnType|s:6:"NodeID";CustomActionButton|N;BrowseSelectionType|N;canInstantiateClassList|a:5:{i:0;a:4:{i:0;s:1:"1";s:2:"id";s:1:"1";i:1;s:6:"Folder";s:4:"name";s:6:"Folder";}i:1;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:7:"Article";s:4:"name";s:7:"Article";}i:2;a:4:{i:0;s:1:"3";s:2:"id";s:1:"3";i:1;s:10:"User group";s:4:"name";s:10:"User group";}i:3;a:4:{i:0;s:1:"4";s:2:"id";s:1:"4";i:1;s:4:"User";s:4:"name";s:4:"User";}i:4;a:4:{i:0;s:1:"5";s:2:"id";s:1:"5";i:1;s:5:"Image";s:4:"name";s:5:"Image";}}eZUserDiscountRulesTimestamp|i:1048514636;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:"id";s:1:"2";}');
INSERT INTO ezsession (session_key, expiration_time, data) VALUES ('74791676e2ee2281d335a9aac6d8c752', 1048779918, 'LastAccessesURI|s:19:"/class/classlist/3/";eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserInfoCache_Timestamp|i:1048520517;!eZUserGroupsCache_10|eZUserGroupsCache_Timestamp|i:1048520517;UserRoles|a:1:{i:0;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:13:"Administrator";s:4:"name";s:13:"Administrator";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1048520517;!eZUserDiscountRules10|eZUserDiscountRulesTimestamp|i:1048520517;eZGlobalSection|a:1:{s:2:"id";s:1:"1";}canInstantiateClassesCachedForUser|s:2:"14";canInstantiateClasses|i:1;eZUserLoggedInID|s:2:"14";eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_14|a:1:{i:0;a:2:{i:0;s:2:"12";s:2:"id";s:2:"12";}}eZUserDiscountRules14|a:0:{}classesCachedForUser|s:2:"14";classesCachedTimestamp|i:1048520517;canInstantiateClassList|a:5:{i:0;a:4:{i:0;s:1:"1";s:2:"id";s:1:"1";i:1;s:6:"Folder";s:4:"name";s:6:"Folder";}i:1;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:7:"Article";s:4:"name";s:7:"Article";}i:2;a:4:{i:0;s:1:"3";s:2:"id";s:1:"3";i:1;s:10:"User group";s:4:"name";s:10:"User group";}i:3;a:4:{i:0;s:1:"4";s:2:"id";s:1:"4";i:1;s:4:"User";s:4:"name";s:4:"User";}i:4;a:4:{i:0;s:1:"5";s:2:"id";s:1:"5";i:1;s:5:"Image";s:4:"name";s:5:"Image";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}FromGroupID|s:1:"3";');


--
-- Data for TOC entry 84 (OID 19056)
-- Name: eztrigger; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 85 (OID 19059)
-- Name: ezuser; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (10, 'anonymous', 'nospam@ez.no', 2, '4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (14, 'admin', 'nospam@ez.no', 2, 'c78e3b0f3d9244ed8c6d1c29464bdff9');


--
-- Data for TOC entry 86 (OID 19064)
-- Name: ezuser_role; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (24, 1, 4);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (25, 2, 12);


--
-- Data for TOC entry 87 (OID 19069)
-- Name: ezuser_setting; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (10, 1, 1000);
INSERT INTO ezuser_setting (user_id, is_enabled, max_login) VALUES (14, 1, 10);


--
-- Data for TOC entry 88 (OID 19077)
-- Name: ezwishlist; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 89 (OID 19084)
-- Name: ezworkflow; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezworkflow (id, "version", workflow_type_string, name, creator_id, modifier_id, created, modified, is_enabled) VALUES (1, 0, 'group_ezserial', 'Sp''s forkflow', 8, 24, 1031927869, 1032856662, 0);


--
-- Data for TOC entry 90 (OID 19091)
-- Name: ezworkflow_assign; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 91 (OID 19098)
-- Name: ezworkflow_event; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezworkflow_event (id, "version", workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (18, 0, 1, 'event_ezapprove', '3333333333', 0, 0, 0, 0, '', '', '', '', 1);
INSERT INTO ezworkflow_event (id, "version", workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (20, 0, 1, 'event_ezmessage', 'foooooo', 0, 0, 0, 0, 'eeeeeeeeeeeeeeeeee', '', '', '', 2);


--
-- Data for TOC entry 92 (OID 19105)
-- Name: ezworkflow_group; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (1, 'Standard', -1, -1, 1024392098, 1024392098);


--
-- Data for TOC entry 93 (OID 19110)
-- Name: ezworkflow_group_link; Type: TABLE DATA; Schema: public; Owner: sp
--

INSERT INTO ezworkflow_group_link (workflow_id, group_id, workflow_version, group_name) VALUES (1, 1, 0, 'Standard');


--
-- Data for TOC entry 94 (OID 19122)
-- Name: ezworkflow_process; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 95 (OID 19138)
-- Name: ezoperation_memento; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 96 (OID 19149)
-- Name: ezdiscountsubrule; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 97 (OID 19156)
-- Name: ezdiscountsubrule_value; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 98 (OID 19165)
-- Name: ezinfocollection; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 99 (OID 19174)
-- Name: ezinfocollection_attribute; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 100 (OID 19185)
-- Name: ezuser_discountrule; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 101 (OID 19193)
-- Name: ezvattype; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 102 (OID 19201)
-- Name: ezdiscountrule; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 103 (OID 19208)
-- Name: ezorder_item; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 104 (OID 19214)
-- Name: ezcontentobject_name; Type: TABLE DATA; Schema: public; Owner: sp
--

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


--
-- Data for TOC entry 105 (OID 19220)
-- Name: ezwaituntildatevalue; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 106 (OID 19231)
-- Name: ezcontent_translation; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 107 (OID 19239)
-- Name: ezcollab_item; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 108 (OID 19263)
-- Name: ezcollab_group; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 109 (OID 19277)
-- Name: ezcollab_item_group_link; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 110 (OID 19289)
-- Name: ezcollab_item_status; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 111 (OID 19298)
-- Name: ezcollab_item_participant_link; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 112 (OID 19311)
-- Name: ezcollab_item_message_link; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 113 (OID 19324)
-- Name: ezcollab_simple_message; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 114 (OID 19347)
-- Name: ezcollab_profile; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 115 (OID 19362)
-- Name: ezapprove_items; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 116 (OID 19371)
-- Name: ezurl; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 117 (OID 19383)
-- Name: ezmessage; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 118 (OID 19396)
-- Name: ezproductcollection_item_opt; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- Data for TOC entry 119 (OID 19404)
-- Name: ezforgot_password; Type: TABLE DATA; Schema: public; Owner: sp
--



--
-- TOC entry 1 (OID 18803)
-- Name: ezbasket_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezbasket_s', 1, false);


--
-- TOC entry 2 (OID 18814)
-- Name: ezcontentclass_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentclass_s', 6, true);


--
-- TOC entry 3 (OID 18821)
-- Name: ezcontentclass_attribute_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentclass_attribute_s', 124, true);


--
-- TOC entry 4 (OID 18835)
-- Name: ezcontentclassgroup_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentclassgroup_s', 4, true);


--
-- TOC entry 5 (OID 18842)
-- Name: ezcontentobject_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentobject_s', 15, true);


--
-- TOC entry 6 (OID 18853)
-- Name: ezcontentobject_attribute_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentobject_attribute_s', 371, true);


--
-- TOC entry 7 (OID 18863)
-- Name: ezcontentobject_link_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentobject_link_s', 1, false);


--
-- TOC entry 8 (OID 18870)
-- Name: ezcontentobject_tree_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentobject_tree_s', 16, true);


--
-- TOC entry 9 (OID 18883)
-- Name: ezcontentobject_version_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontentobject_version_s', 443, true);


--
-- TOC entry 10 (OID 18901)
-- Name: ezenumvalue_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezenumvalue_s', 1, false);


--
-- TOC entry 11 (OID 18921)
-- Name: ezmodule_run_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezmodule_run_s', 1, false);


--
-- TOC entry 12 (OID 18931)
-- Name: eznode_assignment_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('eznode_assignment_s', 154, true);


--
-- TOC entry 13 (OID 18941)
-- Name: ezorder_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezorder_s', 1, false);


--
-- TOC entry 14 (OID 18955)
-- Name: ezpolicy_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezpolicy_s', 318, true);


--
-- TOC entry 15 (OID 18965)
-- Name: ezpolicy_limitation_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezpolicy_limitation_s', 245, true);


--
-- TOC entry 16 (OID 18975)
-- Name: ezpolicy_limitation_value_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezpolicy_limitation_value_s', 410, true);


--
-- TOC entry 17 (OID 18982)
-- Name: ezproductcollection_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezproductcollection_s', 1, false);


--
-- TOC entry 18 (OID 18989)
-- Name: ezproductcollection_item_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezproductcollection_item_s', 1, false);


--
-- TOC entry 19 (OID 18998)
-- Name: ezrole_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezrole_s', 5, true);


--
-- TOC entry 20 (OID 19009)
-- Name: ezsearch_object_word_link_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezsearch_object_word_link_s', 1, false);


--
-- TOC entry 21 (OID 19018)
-- Name: ezsearch_return_count_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezsearch_return_count_s', 1, false);


--
-- TOC entry 22 (OID 19025)
-- Name: ezsearch_search_phrase_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezsearch_search_phrase_s', 1, false);


--
-- TOC entry 23 (OID 19032)
-- Name: ezsearch_word_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezsearch_word_s', 1, false);


--
-- TOC entry 24 (OID 19039)
-- Name: ezsection_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezsection_s', 2, true);


--
-- TOC entry 25 (OID 19054)
-- Name: eztrigger_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('eztrigger_s', 1, false);


--
-- TOC entry 26 (OID 19062)
-- Name: ezuser_role_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezuser_role_s', 26, false);


--
-- TOC entry 27 (OID 19075)
-- Name: ezwishlist_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezwishlist_s', 1, false);


--
-- TOC entry 28 (OID 19082)
-- Name: ezworkflow_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezworkflow_s', 2, false);


--
-- TOC entry 29 (OID 19089)
-- Name: ezworkflow_assign_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezworkflow_assign_s', 1, false);


--
-- TOC entry 30 (OID 19096)
-- Name: ezworkflow_event_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezworkflow_event_s', 3, false);


--
-- TOC entry 31 (OID 19103)
-- Name: ezworkflow_group_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezworkflow_group_s', 2, false);


--
-- TOC entry 32 (OID 19120)
-- Name: ezworkflow_process_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezworkflow_process_s', 1, false);


--
-- TOC entry 33 (OID 19136)
-- Name: ezoperation_memento_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezoperation_memento_s', 1, false);


--
-- TOC entry 34 (OID 19147)
-- Name: ezdiscountsubrule_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezdiscountsubrule_s', 1, false);


--
-- TOC entry 35 (OID 19163)
-- Name: ezinfocollection_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezinfocollection_s', 1, false);


--
-- TOC entry 36 (OID 19172)
-- Name: ezinfocollection_attribute_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezinfocollection_attribute_s', 1, false);


--
-- TOC entry 37 (OID 19183)
-- Name: ezuser_discountrule_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezuser_discountrule_s', 1, false);


--
-- TOC entry 38 (OID 19191)
-- Name: ezvattype_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezvattype_s', 1, false);


--
-- TOC entry 39 (OID 19199)
-- Name: ezdiscountrule_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezdiscountrule_s', 1, false);


--
-- TOC entry 40 (OID 19206)
-- Name: ezorder_item_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezorder_item_s', 1, false);


--
-- TOC entry 41 (OID 19218)
-- Name: ezwaituntildatevalue_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezwaituntildatevalue_s', 1, false);


--
-- TOC entry 42 (OID 19229)
-- Name: ezcontent_translation_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcontent_translation_s', 1, false);


--
-- TOC entry 43 (OID 19237)
-- Name: ezcollab_item_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcollab_item_s', 1, false);


--
-- TOC entry 44 (OID 19261)
-- Name: ezcollab_group_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcollab_group_s', 1, false);


--
-- TOC entry 45 (OID 19309)
-- Name: ezcollab_item_message_link_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcollab_item_message_link_s', 1, false);


--
-- TOC entry 46 (OID 19322)
-- Name: ezcollab_simple_message_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcollab_simple_message_s', 1, false);


--
-- TOC entry 47 (OID 19345)
-- Name: ezcollab_profile_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezcollab_profile_s', 1, false);


--
-- TOC entry 48 (OID 19360)
-- Name: ezapprove_items_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezapprove_items_s', 1, false);


--
-- TOC entry 49 (OID 19369)
-- Name: ezurl_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezurl_s', 1, false);


--
-- TOC entry 50 (OID 19381)
-- Name: ezmessage_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezmessage_s', 1, false);


--
-- TOC entry 51 (OID 19394)
-- Name: ezproductcollection_item_opt_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezproductcollection_item_opt_s', 1, false);


--
-- TOC entry 52 (OID 19402)
-- Name: ezforgot_password_s; Type: SEQUENCE SET; Schema: public; Owner: sp
--

SELECT pg_catalog.setval ('ezforgot_password_s', 1, false);


