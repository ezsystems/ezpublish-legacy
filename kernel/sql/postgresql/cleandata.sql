



SET search_path = public, pg_catalog;






COPY ezapprove_items (id, workflow_process_id, collaboration_id) FROM stdin;
\.







COPY ezbasket (id, session_id, productcollection_id) FROM stdin;
\.







COPY ezbinaryfile (contentobject_attribute_id, "version", filename, original_filename, mime_type) FROM stdin;
\.







COPY ezcollab_group (id, parent_group_id, depth, path_string, is_open, user_id, title, priority, created, modified) FROM stdin;
\.







COPY ezcollab_item (id, type_identifier, creator_id, status, data_text1, data_text2, data_text3, data_int1, data_int2, data_int3, data_float1, data_float2, data_float3, created, modified) FROM stdin;
\.







COPY ezcollab_item_group_link (collaboration_id, group_id, user_id, is_read, is_active, last_read, created, modified) FROM stdin;
\.







COPY ezcollab_item_message_link (id, collaboration_id, participant_id, message_id, message_type, created, modified) FROM stdin;
\.







COPY ezcollab_item_participant_link (collaboration_id, participant_id, participant_type, participant_role, is_read, is_active, last_read, created, modified) FROM stdin;
\.







COPY ezcollab_item_status (collaboration_id, user_id, is_read, is_active, last_read) FROM stdin;
\.







COPY ezcollab_notification_rule (id, user_id, collab_identifier) FROM stdin;
\.







COPY ezcollab_profile (id, user_id, main_group, data_text1, created, modified) FROM stdin;
\.







COPY ezcollab_simple_message (id, message_type, creator_id, data_text1, data_text2, data_text3, data_int1, data_int2, data_int3, data_float1, data_float2, data_float3, created, modified) FROM stdin;
\.







COPY ezcontent_translation (id, name, locale) FROM stdin;
\.







COPY ezcontentbrowsebookmark (id, user_id, node_id, name) FROM stdin;
\.







COPY ezcontentbrowserecent (id, user_id, node_id, created, name) FROM stdin;
1	14	2	1060695457	Root folder
\.







COPY ezcontentclass (id, "version", name, identifier, contentobject_name, creator_id, modifier_id, created, modified) FROM stdin;
1	0	Folder	folder	<name>	14	14	1024392098	1048494694
2	0	Article	article	<title>	14	14	1024392098	1048494722
3	0	User group	user_group	<name>	14	14	1024392098	1048494743
4	0	User	user	<first_name> <last_name>	14	14	1024392098	1048494759
5	0	Image	image	<name>	8	14	1031484992	1048494784
6	0	Forum	forum	<name>	14	14	1052384723	1052384870
7	0	Forum message	forum_message	<topic>	14	14	1052384877	1052384943
8	0	Product	product	<title>	14	14	1052384951	1052385067
9	0	Product review	product_review	<title>	14	14	1052385080	1052385252
10	0	Info page	info_page	<name>	14	14	1052385274	1052385353
11	0	Link	link	<title>	14	14	1052385361	1052385453
12	0	File	file	<name>	14	14	1052385472	1052385669
13	0	Comment	comment	<subject>	14	14	1052385685	1052385756
\.







COPY ezcontentclass_attribute (id, "version", contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4, data_text5, is_information_collector, can_translate) FROM stdin;
123	0	2	enable_comments	Enable comments	ezboolean	0	0	5	0	0	0	0	0	0	0	0					\N	0	1
119	0	1	description	Description	ezxmltext	1	0	2	10	0	0	0	0	0	0	0					\N	0	1
116	0	5	name	Name	ezstring	1	1	1	150	0	0	0	0	0	0	0					\N	0	1
6	0	3	name	Name	ezstring	1	1	1	255	0	0	0	0	0	0	0					\N	0	1
7	0	3	description	Description	ezstring	1	0	2	255	0	0	0	0	0	0	0					\N	0	1
9	0	4	last_name	Last name	ezstring	1	1	2	255	0	0	0	0	0	0	0					\N	0	1
12	0	4	user_account	User account	ezuser	0	1	3	0	0	0	0	0	0	0	0					\N	0	1
118	0	5	image	Image	ezimage	0	0	3	2	0	0	0	0	0	0	0					\N	0	1
4	0	1	name	Name	ezstring	1	1	1	255	0	0	0	0	0	0	0	Folder				\N	0	1
122	0	2	thumbnail	Thumbnail	ezimage	0	0	4	2	0	0	0	0	0	0	0					\N	0	1
121	0	2	body	Body	ezxmltext	1	0	3	20	0	0	0	0	0	0	0					\N	0	1
120	0	2	intro	Intro	ezxmltext	1	1	2	10	0	0	0	0	0	0	0					\N	0	1
1	0	2	title	Title	ezstring	1	1	1	255	0	0	0	0	0	0	0	New article				\N	0	1
117	0	5	caption	Caption	ezxmltext	1	0	2	10	0	0	0	0	0	0	0					\N	0	1
8	0	4	first_name	First name	ezstring	1	1	1	255	0	0	0	0	0	0	0					\N	0	1
127	0	7	topic	Topic	ezstring	1	1	1	150	0	0	0	0	0	0	0	New topic				\N	0	1
128	0	7	message	Message	eztext	1	1	2	10	0	0	0	0	0	0	0					\N	0	1
126	0	6	description	Description	ezxmltext	1	0	3	15	0	0	0	0	0	0	0					\N	0	1
125	0	6	icon	Icon	ezimage	0	0	2	1	0	0	0	0	0	0	0					\N	0	1
124	0	6	name	Name	ezstring	1	1	1	150	0	0	0	0	0	0	0					\N	0	1
134	0	8	photo	Photo	ezimage	0	0	6	1	0	0	0	0	0	0	0					\N	0	1
133	0	8	price	Price	ezprice	0	1	5	1	0	0	0	1	0	0	0					\N	0	1
132	0	8	description	Description	ezxmltext	1	0	4	10	0	0	0	0	0	0	0					\N	0	1
131	0	8	intro	Intro	ezxmltext	1	0	3	10	0	0	0	0	0	0	0					\N	0	1
130	0	8	product_nr	Product nr.	ezstring	1	0	2	40	0	0	0	0	0	0	0					\N	0	1
129	0	8	title	Title	ezstring	1	1	1	100	0	0	0	0	0	0	0					\N	0	1
139	0	9	review	Review	ezxmltext	1	0	5	10	0	0	0	0	0	0	0					\N	0	1
138	0	9	geography	Town, Country	ezstring	1	1	4	0	0	0	0	0	0	0	0					\N	0	1
137	0	9	reviewer_name	Reviewer Name	ezstring	1	1	3	0	0	0	0	0	0	0	0					\N	0	1
136	0	9	rating	Rating	ezenum	1	0	2	0	0	0	0	0	0	0	0					\N	0	1
135	0	9	title	Title	ezstring	1	1	1	50	0	0	0	0	0	0	0					\N	0	1
142	0	10	image	Image	ezimage	0	0	3	1	0	0	0	0	0	0	0					\N	0	1
141	0	10	body	Body	ezxmltext	1	0	2	20	0	0	0	0	0	0	0					\N	0	1
140	0	10	name	Name	ezstring	1	1	1	100	0	0	0	0	0	0	0					\N	0	1
146	0	12	name	Name	ezstring	1	1	1	0	0	0	0	0	0	0	0	New file				\N	0	1
148	0	12	file	File	ezbinaryfile	0	1	3	0	0	0	0	0	0	0	0					\N	0	1
147	0	12	description	Description	ezxmltext	1	0	2	10	0	0	0	0	0	0	0					\N	0	1
145	0	11	link	Link	ezurl	0	0	3	0	0	0	0	0	0	0	0					\N	0	1
144	0	11	description	Description	ezxmltext	1	0	2	10	0	0	0	0	0	0	0					\N	0	1
143	0	11	title	Title	ezstring	1	1	1	100	0	0	0	0	0	0	0					\N	0	1
151	0	13	message	Message	eztext	1	1	3	10	0	0	0	0	0	0	0					\N	0	1
150	0	13	author	Author	ezstring	1	1	2	0	0	0	0	0	0	0	0					\N	0	1
149	0	13	subject	Subject	ezstring	1	1	1	40	0	0	0	0	0	0	0					\N	0	1
\.







COPY ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) FROM stdin;
1	0	1	Content
2	0	1	Content
4	0	2	Content
5	0	3	Media
3	0	2	
6	0	1	Content
7	0	1	Content
8	0	1	Content
9	0	1	Content
10	0	1	Content
11	0	1	Content
12	0	3	Media
13	0	1	Content
\.







COPY ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) FROM stdin;
1	Content	1	14	1031216928	1033922106
2	Users	1	14	1031216941	1033922113
3	Media	8	14	1032009743	1033922120
\.







COPY ezcontentobject (id, owner_id, section_id, contentclass_id, name, current_version, is_published, published, modified, status, remote_id) FROM stdin;
1	14	1	1	Root folder	1	0	1033917596	1033917596	1	\N
4	14	2	3	Users	1	0	1033917596	1033917596	1	\N
10	14	2	4	Anonymous User	1	0	1033920665	1033920665	1	\N
11	14	2	3	Guest accounts	1	0	1033920746	1033920746	1	\N
12	14	2	3	Administrator users	1	0	1033920775	1033920775	1	\N
13	14	2	3	Editors	1	0	1033920794	1033920794	1	\N
14	14	2	4	Administrator User	1	0	1033920830	1033920830	1	\N
40	14	2	4	test test	1	0	1053613020	1053613020	1	
41	14	3	1	Media	1	0	1060695457	1060695457	1	
\.







COPY ezcontentobject_attribute (id, language_code, "version", contentobject_id, contentclassattribute_id, data_text, data_int, data_float, attribute_original_id, sort_key_int, sort_key_string) FROM stdin;
1	eng-GB	1	1	4	Root folder	\N	\N	0	0	
2	eng-GB	1	1	119	<?xml version="1.0"><section><paragraph>This folder contains some information about...</paragraph></section>	\N	\N	0	0	
7	eng-GB	1	4	7	Main group	\N	\N	0	0	
8	eng-GB	1	4	6	Users	\N	\N	0	0	
21	eng-GB	1	10	12		0	0	0	0	
22	eng-GB	1	11	6	Guest accounts	0	0	0	0	
19	eng-GB	1	10	8	Anonymous	0	0	0	0	
20	eng-GB	1	10	9	User	0	0	0	0	
23	eng-GB	1	11	7		0	0	0	0	
24	eng-GB	1	12	6	Administrator users	0	0	0	0	
25	eng-GB	1	12	7		0	0	0	0	
26	eng-GB	1	13	6	Editors	0	0	0	0	
27	eng-GB	1	13	7		0	0	0	0	
28	eng-GB	1	14	8	Administrator	0	0	0	0	
29	eng-GB	1	14	9	User	0	0	0	0	
30	eng-GB	1	14	12		0	0	0	0	
95	eng-GB	1	40	8	test	0	0	0	0	
96	eng-GB	1	40	9	test	0	0	0	0	
97	eng-GB	1	40	12		0	0	0	0	
98	eng-GB	1	41	4	Media	0	0	0	0	
99	eng-GB	1	41	119	<?xml version="1.0" encoding="iso-8859-1"?>\n<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"\nxmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"\nxmlns:custom="http://ez.no/namespaces/ezpublish3/custom/" />	1045487555	0	0	0	
\.







COPY ezcontentobject_link (id, from_contentobject_id, from_contentobject_version, to_contentobject_id) FROM stdin;
\.







COPY ezcontentobject_name (contentobject_id, name, content_version, content_translation, real_translation) FROM stdin;
1	Root folder	1	eng-GB	eng-GB
4	Users	1	eng-GB	eng-GB
10	Anonymous User	1	eng-GB	eng-GB
11	Guest accounts	1	eng-GB	eng-GB
12	Administrator users	1	eng-GB	eng-GB
13	Editors	1	eng-GB	eng-GB
14	Administrator User	1	eng-GB	eng-GB
40	test test	1	eng-GB	eng-GB
41	Media	1	eng-GB	eng-GB
\.







COPY ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id) FROM stdin;
1	1	0	1	1	0	/1/	1	1	0	\N	1
2	1	1	1	1	1	/1/2/	1	1	0		2
5	1	4	1	0	1	/1/5/	1	1	0	users	5
11	5	10	1	1	2	/1/5/11/	1	1	0	users/anonymous_user	11
12	5	11	1	1	2	/1/5/12/	1	1	0	users/guest_accounts	12
13	5	12	1	1	2	/1/5/13/	1	1	0	users/administrator_users	13
14	5	13	1	1	2	/1/5/14/	1	1	0	users/editors	14
15	13	14	1	1	3	/1/5/13/15/	1	1	0	users/administrator_users/administrator_user	15
42	12	40	1	1	3	/1/5/12/42/	9	1	0	users/guest_accounts/test_test	42
43	1	41	1	1	1	/1/43/	9	1	0	media	43
\.







COPY ezcontentobject_version (id, contentobject_id, creator_id, "version", created, modified, status, user_id) FROM stdin;
\.







COPY ezdiscountrule (id, name) FROM stdin;
\.







COPY ezdiscountsubrule (id, name, discountrule_id, discount_percent, limitation) FROM stdin;
\.







COPY ezdiscountsubrule_value (discountsubrule_id, value, issection) FROM stdin;
\.







COPY ezenumobjectvalue (contentobject_attribute_id, contentobject_attribute_version, enumid, enumelement, enumvalue) FROM stdin;
\.







COPY ezenumvalue (id, contentclass_attribute_id, contentclass_attribute_version, enumelement, enumvalue, placement) FROM stdin;
2	136	0	Ok	3	2
1	136	0	Poor	2	1
3	136	0	Good	5	3
\.







COPY ezforgot_password (id, user_id, hash_key, "time") FROM stdin;
\.







COPY ezgeneral_digest_user_settings (id, address, receive_digest, digest_type, "day", "time") FROM stdin;
\.







COPY ezimage (contentobject_attribute_id, "version", filename, original_filename, mime_type, alternative_text) FROM stdin;
\.







COPY ezimagevariation (contentobject_attribute_id, "version", filename, additional_path, requested_width, requested_height, width, height) FROM stdin;
\.







COPY ezinfocollection (id, contentobject_id, created) FROM stdin;
\.







COPY ezinfocollection_attribute (id, informationcollection_id, data_text, data_int, data_float, contentclass_attribute_id) FROM stdin;
\.







COPY ezkeyword (id, keyword, class_id) FROM stdin;
\.







COPY ezkeyword_attribute_link (id, keyword_id, objectattribute_id) FROM stdin;
\.







COPY ezmedia (contentobject_attribute_id, "version", filename, original_filename, mime_type, width, height, has_controller, is_autoplay, pluginspage, quality, controls, is_loop) FROM stdin;
\.







COPY ezmessage (id, send_method, send_weekday, send_time, destination_address, title, body, is_sent) FROM stdin;
\.







COPY ezmodule_run (id, workflow_process_id, module_name, function_name, module_data) FROM stdin;
\.







COPY eznode_assignment (id, contentobject_id, contentobject_version, parent_node, sort_field, sort_order, is_main, from_node_id, remote_id) FROM stdin;
2	1	1	1	1	1	1	0	0
4	8	2	5	1	1	1	0	0
144	4	1	1	1	1	1	0	0
147	210	1	5	1	1	1	0	0
146	209	1	5	1	1	1	0	0
145	1	2	1	1	1	1	0	0
148	9	1	2	1	1	1	0	0
149	10	1	5	1	1	1	0	0
150	11	1	5	1	1	1	0	0
151	12	1	5	1	1	1	0	0
152	13	1	5	1	1	1	0	0
153	14	1	13	1	1	1	0	0
181	40	1	12	9	1	1	0	0
182	41	1	1	9	1	1	0	0
\.







COPY eznotificationcollection (id, event_id, "handler", transport, data_subject, data_text) FROM stdin;
\.







COPY eznotificationcollection_item (id, collection_id, event_id, address, send_date) FROM stdin;
\.







COPY eznotificationevent (id, status, event_type_string, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4) FROM stdin;
1	0	ezpublish	41	1	0	0				
\.







COPY ezoperation_memento (id, memento_key, memento_data, main, main_key) FROM stdin;
\.







COPY ezorder (id, user_id, productcollection_id, created, is_temporary, order_nr, data_text_2, data_text_1, account_identifier, ignore_vat) FROM stdin;
\.







COPY ezorder_item (id, order_id, description, price, vat_value) FROM stdin;
\.







COPY ezpolicy (id, role_id, function_name, module_name, limitation) FROM stdin;
317	3	*	content	*
308	2	*	*	*
326	1	read	content	 
325	1	login	user	*
319	3	login	user	*
\.







COPY ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) FROM stdin;
249	326	Class	0	read	content
\.







COPY ezpolicy_limitation_value (id, limitation_id, value) FROM stdin;
435	249	1
436	249	10
437	249	10
438	249	11
439	249	11
440	249	12
441	249	12
442	249	13
443	249	13
444	249	2
445	249	2
446	249	5
447	249	5
448	249	6
449	249	6
450	249	7
451	249	7
452	249	8
453	249	8
454	249	9
455	249	9
\.







COPY ezpreferences (id, user_id, name, value) FROM stdin;
\.







COPY ezproductcollection (id, created) FROM stdin;
\.







COPY ezproductcollection_item (id, productcollection_id, contentobject_id, item_count, price, is_vat_inc, vat_value, discount) FROM stdin;
\.







COPY ezproductcollection_item_opt (id, item_id, option_item_id, name, value, price, object_attribute_id) FROM stdin;
\.







COPY ezrole (id, "version", name, value) FROM stdin;
1	0	Anonymous	 
2	0	Administrator	*
3	0	Editor	 
\.







COPY ezsearch_object_word_link (id, contentobject_id, word_id, frequency, placement, prev_word_id, next_word_id, contentclass_id, published, section_id, contentclass_attribute_id, identifier, integer_value) FROM stdin;
26	40	5	0	0	0	5	4	1053613020	2	8		0
27	40	5	0	1	5	0	4	1053613020	2	9		0
28	41	6	0	0	0	0	1	1060695457	3	4		0
\.







COPY ezsearch_return_count (id, phrase_id, "time", count) FROM stdin;
\.







COPY ezsearch_search_phrase (id, phrase) FROM stdin;
\.







COPY ezsearch_word (id, word, object_count) FROM stdin;
5	test	1
6	media	1
\.







COPY ezsection (id, name, locale, navigation_part_identifier) FROM stdin;
1	Standard section	nor-NO	ezcontentnavigationpart
2	Users		ezusernavigationpart
3	Media		ezmedianavigationpart
\.







COPY ezsession (session_key, expiration_time, data) FROM stdin;
\.







COPY ezsubtree_notification_rule (id, address, use_digest, node_id) FROM stdin;
\.







COPY eztrigger (id, name, module_name, function_name, connect_type, workflow_id) FROM stdin;
\.







COPY ezurl (id, url, created, modified, is_valid, last_checked, original_url_md5) FROM stdin;
\.







COPY ezurl_object_link (url_id, contentobject_attribute_id, contentobject_attribute_version) FROM stdin;
\.







COPY ezurlalias (id, source_url, source_md5, destination_url, is_internal, forward_to_id, is_wildcard) FROM stdin;
12		d41d8cd98f00b204e9800998ecf8427e	content/view/full/2	1	0	0
13	users	9bc65c2abec141778ffaa729489f3e87	content/view/full/5	1	0	0
14	users/anonymous_user	a37b7463e2c21098fa1a729dad4b4437	content/view/full/11	1	0	0
15	users/guest_accounts	02d4e844e3a660857a3f81585995ffe1	content/view/full/12	1	0	0
16	users/administrator_users	1b1d79c16700fd6003ea7be233e754ba	content/view/full/13	1	0	0
17	users/editors	0bb9dd665c96bbc1cf36b79180786dea	content/view/full/14	1	0	0
18	users/administrator_users/administrator_user	f1305ac5f327a19b451d82719e0c3f5d	content/view/full/15	1	0	0
19	users/guest_accounts/test_test	27a1813763d43de613bf05c31df7a6ef	content/view/full/42	1	0	0
20	media	62933a2951ef01f4eafd9bdf4d3cd2f0	content/view/full/43	1	0	0
\.







COPY ezuser (contentobject_id, login, email, password_hash_type, password_hash) FROM stdin;
10	anonymous	nospam@ez.no	2	4e6f6184135228ccd45f8233d72a0363
14	admin	nospam@ez.no	2	c78e3b0f3d9244ed8c6d1c29464bdff9
40	test	test@test.com	2	be778b473235e210cc577056226536a4
\.







COPY ezuser_accountkey (id, user_id, hash_key, "time") FROM stdin;
\.







COPY ezuser_discountrule (id, discountrule_id, contentobject_id, name) FROM stdin;
\.







COPY ezuser_role (id, role_id, contentobject_id) FROM stdin;
29	1	10
25	2	12
30	3	13
28	1	11
\.







COPY ezuser_setting (user_id, is_enabled, max_login) FROM stdin;
10	1	1000
14	1	10
23	1	0
40	1	0
\.







COPY ezvattype (id, name, percentage) FROM stdin;
1	Std	0
\.







COPY ezwaituntildatevalue (id, workflow_event_id, workflow_event_version, contentclass_id, contentclass_attribute_id) FROM stdin;
\.







COPY ezwishlist (id, user_id, productcollection_id) FROM stdin;
\.







COPY ezworkflow (id, "version", is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) FROM stdin;
\.







COPY ezworkflow_assign (id, workflow_id, node_id, access_type, as_tree) FROM stdin;
\.







COPY ezworkflow_event (id, "version", workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) FROM stdin;
\.







COPY ezworkflow_group (id, name, creator_id, modifier_id, created, modified) FROM stdin;
1	Standard	14	14	1024392098	1024392098
\.







COPY ezworkflow_group_link (workflow_id, group_id, workflow_version, group_name) FROM stdin;
1	1	0	Standard
\.







COPY ezworkflow_process (id, process_key, workflow_id, user_id, content_id, content_version, node_id, session_key, event_id, event_position, last_event_id, last_event_position, last_event_status, event_status, created, modified, activation_date, event_state, status, parameters, memento_key) FROM stdin;
\.







COPY ezsite_data (name, value) FROM stdin;
ezpublish-version	3.2
ezpublish-release	2
\.







SELECT pg_catalog.setval ('ezapprove_items_s', 1, false);







SELECT pg_catalog.setval ('ezbasket_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_group_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_item_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_item_message_link_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_notification_rule_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_profile_s', 1, false);







SELECT pg_catalog.setval ('ezcollab_simple_message_s', 1, false);







SELECT pg_catalog.setval ('ezcontent_translation_s', 1, false);







SELECT pg_catalog.setval ('ezcontentbrowsebookmark_s', 1, false);







SELECT pg_catalog.setval ('ezcontentbrowserecent_s', 1, true);







SELECT pg_catalog.setval ('ezcontentclass_s', 13, true);







SELECT pg_catalog.setval ('ezcontentclass_attribute_s', 151, true);







SELECT pg_catalog.setval ('ezcontentclassgroup_s', 3, true);







SELECT pg_catalog.setval ('ezcontentobject_s', 41, true);







SELECT pg_catalog.setval ('ezcontentobject_attribute_s', 99, true);







SELECT pg_catalog.setval ('ezcontentobject_link_s', 1, false);







SELECT pg_catalog.setval ('ezcontentobject_tree_s', 43, true);







SELECT pg_catalog.setval ('ezcontentobject_version_s', 472, true);







SELECT pg_catalog.setval ('ezdiscountrule_s', 1, false);







SELECT pg_catalog.setval ('ezdiscountsubrule_s', 1, false);







SELECT pg_catalog.setval ('ezenumvalue_s', 3, true);







SELECT pg_catalog.setval ('ezforgot_password_s', 1, false);







SELECT pg_catalog.setval ('ezgeneral_digest_user_settings_s', 1, false);







SELECT pg_catalog.setval ('ezinfocollection_s', 1, false);







SELECT pg_catalog.setval ('ezinfocollection_attribute_s', 1, false);







SELECT pg_catalog.setval ('ezkeyword_s', 1, false);







SELECT pg_catalog.setval ('ezkeyword_attribute_link_s', 1, false);







SELECT pg_catalog.setval ('ezmessage_s', 1, false);







SELECT pg_catalog.setval ('ezmodule_run_s', 1, false);







SELECT pg_catalog.setval ('eznode_assignment_s', 182, true);







SELECT pg_catalog.setval ('eznotificationcollection_s', 1, false);







SELECT pg_catalog.setval ('eznotificationcollection_item_s', 1, false);







SELECT pg_catalog.setval ('eznotificationevent_s', 1, true);







SELECT pg_catalog.setval ('ezoperation_memento_s', 1, false);







SELECT pg_catalog.setval ('ezorder_s', 1, false);







SELECT pg_catalog.setval ('ezorder_item_s', 1, false);







SELECT pg_catalog.setval ('ezpolicy_s', 326, true);







SELECT pg_catalog.setval ('ezpolicy_limitation_s', 249, true);







SELECT pg_catalog.setval ('ezpolicy_limitation_value_s', 455, true);







SELECT pg_catalog.setval ('ezpreferences_s', 1, false);







SELECT pg_catalog.setval ('ezproductcollection_s', 1, false);







SELECT pg_catalog.setval ('ezproductcollection_item_s', 1, false);







SELECT pg_catalog.setval ('ezproductcollection_item_opt_s', 1, false);







SELECT pg_catalog.setval ('ezrole_s', 5, true);







SELECT pg_catalog.setval ('ezsearch_object_word_link_s', 28, true);







SELECT pg_catalog.setval ('ezsearch_return_count_s', 1, false);







SELECT pg_catalog.setval ('ezsearch_search_phrase_s', 1, false);







SELECT pg_catalog.setval ('ezsearch_word_s', 6, true);







SELECT pg_catalog.setval ('ezsection_s', 3, true);







SELECT pg_catalog.setval ('ezsubtree_notification_rule_s', 1, false);







SELECT pg_catalog.setval ('eztrigger_s', 1, false);







SELECT pg_catalog.setval ('ezurl_s', 1, false);







SELECT pg_catalog.setval ('ezurlalias_s', 20, true);







SELECT pg_catalog.setval ('ezuser_accountkey_s', 1, false);







SELECT pg_catalog.setval ('ezuser_discountrule_s', 1, false);







SELECT pg_catalog.setval ('ezuser_role_s', 30, true);







SELECT pg_catalog.setval ('ezvattype_s', 1, true);







SELECT pg_catalog.setval ('ezwaituntildatevalue_s', 1, false);







SELECT pg_catalog.setval ('ezwishlist_s', 1, false);







SELECT pg_catalog.setval ('ezworkflow_s', 1, false);







SELECT pg_catalog.setval ('ezworkflow_assign_s', 1, false);







SELECT pg_catalog.setval ('ezworkflow_event_s', 1, false);







SELECT pg_catalog.setval ('ezworkflow_group_s', 1, true);







SELECT pg_catalog.setval ('ezworkflow_process_s', 1, false);


