--
-- PostgreSQL database dump
--

\connect - postgres

SET search_path = public, pg_catalog;

--
-- Data for TOC entry 66 (OID 33320)
-- Name: ezapprove_items; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 67 (OID 33327)
-- Name: ezbasket; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 68 (OID 33334)
-- Name: ezbinaryfile; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 69 (OID 33343)
-- Name: ezcollab_group; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 70 (OID 33359)
-- Name: ezcollab_item; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 71 (OID 33378)
-- Name: ezcollab_item_group_link; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 72 (OID 33390)
-- Name: ezcollab_item_message_link; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 73 (OID 33401)
-- Name: ezcollab_item_participant_link; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 74 (OID 33414)
-- Name: ezcollab_item_status; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 75 (OID 33423)
-- Name: ezcollab_notification_rule; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 76 (OID 33430)
-- Name: ezcollab_profile; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 77 (OID 33442)
-- Name: ezcollab_simple_message; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 78 (OID 33460)
-- Name: ezcontent_translation; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 79 (OID 33467)
-- Name: ezcontentbrowsebookmark; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 80 (OID 33476)
-- Name: ezcontentbrowserecent; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentbrowserecent VALUES (1, 14, 2, 1060695457, 'Root folder');


--
-- Data for TOC entry 81 (OID 33486)
-- Name: ezcontentclass; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentclass VALUES (1, 0, 'Folder', 'folder', '<name>', 14, 14, 1024392098, 1048494694);
INSERT INTO ezcontentclass VALUES (2, 0, 'Article', 'article', '<title>', 14, 14, 1024392098, 1048494722);
INSERT INTO ezcontentclass VALUES (3, 0, 'User group', 'user_group', '<name>', 14, 14, 1024392098, 1048494743);
INSERT INTO ezcontentclass VALUES (4, 0, 'User', 'user', '<first_name> <last_name>', 14, 14, 1024392098, 1048494759);
INSERT INTO ezcontentclass VALUES (5, 0, 'Image', 'image', '<name>', 8, 14, 1031484992, 1048494784);
INSERT INTO ezcontentclass VALUES (6, 0, 'Forum', 'forum', '<name>', 14, 14, 1052384723, 1052384870);
INSERT INTO ezcontentclass VALUES (7, 0, 'Forum message', 'forum_message', '<topic>', 14, 14, 1052384877, 1052384943);
INSERT INTO ezcontentclass VALUES (8, 0, 'Product', 'product', '<title>', 14, 14, 1052384951, 1052385067);
INSERT INTO ezcontentclass VALUES (9, 0, 'Product review', 'product_review', '<title>', 14, 14, 1052385080, 1052385252);
INSERT INTO ezcontentclass VALUES (10, 0, 'Info page', 'info_page', '<name>', 14, 14, 1052385274, 1052385353);
INSERT INTO ezcontentclass VALUES (11, 0, 'Link', 'link', '<title>', 14, 14, 1052385361, 1052385453);
INSERT INTO ezcontentclass VALUES (12, 0, 'File', 'file', '<name>', 14, 14, 1052385472, 1052385669);
INSERT INTO ezcontentclass VALUES (13, 0, 'Comment', 'comment', '<subject>', 14, 14, 1052385685, 1052385756);


--
-- Data for TOC entry 82 (OID 33498)
-- Name: ezcontentclass_attribute; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentclass_attribute VALUES (123, 0, 2, 'enable_comments', 'Enable comments', 'ezboolean', 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (119, 0, 1, 'description', 'Description', 'ezxmltext', 1, 0, 2, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (116, 0, 5, 'name', 'Name', 'ezstring', 1, 1, 1, 150, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (6, 0, 3, 'name', 'Name', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (7, 0, 3, 'description', 'Description', 'ezstring', 1, 0, 2, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (9, 0, 4, 'last_name', 'Last name', 'ezstring', 1, 1, 2, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (12, 0, 4, 'user_account', 'User account', 'ezuser', 0, 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (118, 0, 5, 'image', 'Image', 'ezimage', 0, 0, 3, 2, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (4, 0, 1, 'name', 'Name', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, 'Folder', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (122, 0, 2, 'thumbnail', 'Thumbnail', 'ezimage', 0, 0, 4, 2, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (121, 0, 2, 'body', 'Body', 'ezxmltext', 1, 0, 3, 20, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (120, 0, 2, 'intro', 'Intro', 'ezxmltext', 1, 1, 2, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (1, 0, 2, 'title', 'Title', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, 'New article', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (117, 0, 5, 'caption', 'Caption', 'ezxmltext', 1, 0, 2, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (8, 0, 4, 'first_name', 'First name', 'ezstring', 1, 1, 1, 255, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (127, 0, 7, 'topic', 'Topic', 'ezstring', 1, 1, 1, 150, 0, 0, 0, 0, 0, 0, 0, 'New topic', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (128, 0, 7, 'message', 'Message', 'eztext', 1, 1, 2, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (126, 0, 6, 'description', 'Description', 'ezxmltext', 1, 0, 3, 15, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (125, 0, 6, 'icon', 'Icon', 'ezimage', 0, 0, 2, 1, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (124, 0, 6, 'name', 'Name', 'ezstring', 1, 1, 1, 150, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (134, 0, 8, 'photo', 'Photo', 'ezimage', 0, 0, 6, 1, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (133, 0, 8, 'price', 'Price', 'ezprice', 0, 1, 5, 1, 0, 0, 0, 1, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (132, 0, 8, 'description', 'Description', 'ezxmltext', 1, 0, 4, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (131, 0, 8, 'intro', 'Intro', 'ezxmltext', 1, 0, 3, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (130, 0, 8, 'product_nr', 'Product nr.', 'ezstring', 1, 0, 2, 40, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (129, 0, 8, 'title', 'Title', 'ezstring', 1, 1, 1, 100, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (139, 0, 9, 'review', 'Review', 'ezxmltext', 1, 0, 5, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (138, 0, 9, 'geography', 'Town, Country', 'ezstring', 1, 1, 4, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (137, 0, 9, 'reviewer_name', 'Reviewer Name', 'ezstring', 1, 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (136, 0, 9, 'rating', 'Rating', 'ezenum', 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (135, 0, 9, 'title', 'Title', 'ezstring', 1, 1, 1, 50, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (142, 0, 10, 'image', 'Image', 'ezimage', 0, 0, 3, 1, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (141, 0, 10, 'body', 'Body', 'ezxmltext', 1, 0, 2, 20, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (140, 0, 10, 'name', 'Name', 'ezstring', 1, 1, 1, 100, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (146, 0, 12, 'name', 'Name', 'ezstring', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 'New file', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (148, 0, 12, 'file', 'File', 'ezbinaryfile', 0, 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (147, 0, 12, 'description', 'Description', 'ezxmltext', 1, 0, 2, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (145, 0, 11, 'link', 'Link', 'ezurl', 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (144, 0, 11, 'description', 'Description', 'ezxmltext', 1, 0, 2, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (143, 0, 11, 'title', 'Title', 'ezstring', 1, 1, 1, 100, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (151, 0, 13, 'message', 'Message', 'eztext', 1, 1, 3, 10, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (150, 0, 13, 'author', 'Author', 'ezstring', 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);
INSERT INTO ezcontentclass_attribute VALUES (149, 0, 13, 'subject', 'Subject', 'ezstring', 1, 1, 1, 40, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, 0, 1);


--
-- Data for TOC entry 83 (OID 33516)
-- Name: ezcontentclass_classgroup; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentclass_classgroup VALUES (1, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (2, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (4, 0, 2, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (5, 0, 3, 'Media');
INSERT INTO ezcontentclass_classgroup VALUES (3, 0, 2, '');
INSERT INTO ezcontentclass_classgroup VALUES (6, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (7, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (8, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (9, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (10, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (11, 0, 1, 'Content');
INSERT INTO ezcontentclass_classgroup VALUES (12, 0, 3, 'Media');
INSERT INTO ezcontentclass_classgroup VALUES (13, 0, 1, 'Content');


--
-- Data for TOC entry 84 (OID 33523)
-- Name: ezcontentclassgroup; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentclassgroup VALUES (1, 'Content', 1, 14, 1031216928, 1033922106);
INSERT INTO ezcontentclassgroup VALUES (2, 'Users', 1, 14, 1031216941, 1033922113);
INSERT INTO ezcontentclassgroup VALUES (3, 'Media', 8, 14, 1032009743, 1033922120);


--
-- Data for TOC entry 85 (OID 33532)
-- Name: ezcontentobject; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentobject VALUES (1, 14, 1, 1, 'Root folder', 1, 0, 1033917596, 1033917596, 1, NULL);
INSERT INTO ezcontentobject VALUES (4, 14, 2, 3, 'Users', 1, 0, 0, 0, 1, NULL);
INSERT INTO ezcontentobject VALUES (10, 14, 2, 4, 'Anonymous User', 1, 0, 1033920665, 1033920665, 1, NULL);
INSERT INTO ezcontentobject VALUES (11, 14, 2, 3, 'Guest accounts', 1, 0, 1033920746, 1033920746, 1, NULL);
INSERT INTO ezcontentobject VALUES (12, 14, 2, 3, 'Administrator users', 1, 0, 1033920775, 1033920775, 1, NULL);
INSERT INTO ezcontentobject VALUES (13, 14, 2, 3, 'Editors', 1, 0, 1033920794, 1033920794, 1, NULL);
INSERT INTO ezcontentobject VALUES (14, 14, 2, 4, 'Administrator User', 1, 0, 1033920830, 1033920830, 1, NULL);
INSERT INTO ezcontentobject VALUES (40, 14, 2, 4, 'test test', 1, 0, 1053613020, 1053613020, 1, '');
INSERT INTO ezcontentobject VALUES (41, 14, 3, 1, 'Media', 1, 0, 1060695457, 1060695457, 1, '');


--
-- Data for TOC entry 86 (OID 33543)
-- Name: ezcontentobject_attribute; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentobject_attribute VALUES (1, 'eng-GB', 1, 1, 4, 'My folder', NULL, NULL, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (2, 'eng-GB', 1, 1, 119, '<?xml version="1.0"><section><paragraph>This folder contains some information about...</paragraph></section>', NULL, NULL, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (7, 'eng-GB', 1, 4, 7, 'Main group', NULL, NULL, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (8, 'eng-GB', 1, 4, 6, 'Users', NULL, NULL, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (1, 'eng-GB', 2, 1, 4, 'My folder', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (2, 'eng-GB', 2, 1, 119, '<?xml version="1.0"><section><paragraph>This folder contains some information about...</paragraph></section>', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (21, 'eng-GB', 1, 10, 12, '', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (22, 'eng-GB', 1, 11, 6, 'Guest accounts', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (19, 'eng-GB', 1, 10, 8, 'Anonymous', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (20, 'eng-GB', 1, 10, 9, 'User', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (23, 'eng-GB', 1, 11, 7, '', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (24, 'eng-GB', 1, 12, 6, 'Administrator users', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (25, 'eng-GB', 1, 12, 7, '', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (26, 'eng-GB', 1, 13, 6, 'Editors', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (27, 'eng-GB', 1, 13, 7, '', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (28, 'eng-GB', 1, 14, 8, 'Administrator', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (29, 'eng-GB', 1, 14, 9, 'User', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (30, 'eng-GB', 1, 14, 12, '', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (95, 'eng-GB', 1, 40, 8, 'test', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (96, 'eng-GB', 1, 40, 9, 'test', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (97, 'eng-GB', 1, 40, 12, '', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (98, 'eng-GB', 1, 41, 4, 'Media', 0, 0, 0, 0, '');
INSERT INTO ezcontentobject_attribute VALUES (99, 'eng-GB', 1, 41, 119, '<?xml version="1.0" encoding="iso-8859-1"?>
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
         xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
         xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/" />', 1045487555, 0, 0, 0, '');


--
-- Data for TOC entry 87 (OID 33562)
-- Name: ezcontentobject_link; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 88 (OID 33570)
-- Name: ezcontentobject_name; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentobject_name VALUES (1, 'Root folder', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (4, 'Users', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (10, 'Anonymous User', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (11, 'Guest accounts', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (12, 'Administrator users', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (13, 'Editors', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (14, 'Administrator User', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (40, 'test test', 1, 'eng-GB', 'eng-GB');
INSERT INTO ezcontentobject_name VALUES (41, 'Media', 1, 'eng-GB', 'eng-GB');


--
-- Data for TOC entry 89 (OID 33577)
-- Name: ezcontentobject_tree; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentobject_tree VALUES (1, 1, 0, 1, 1, 0, '/1/', 1, 1, 0, NULL, 1);
INSERT INTO ezcontentobject_tree VALUES (2, 1, 1, 1, 1, 1, '/1/2/', 1, 1, 0, '', 2);
INSERT INTO ezcontentobject_tree VALUES (5, 1, 4, 1, 0, 1, '/1/5/', 1, 1, 0, 'users', 5);
INSERT INTO ezcontentobject_tree VALUES (11, 5, 10, 1, 1, 2, '/1/5/11/', 1, 1, 0, 'users/anonymous_user', 11);
INSERT INTO ezcontentobject_tree VALUES (12, 5, 11, 1, 1, 2, '/1/5/12/', 1, 1, 0, 'users/guest_accounts', 12);
INSERT INTO ezcontentobject_tree VALUES (13, 5, 12, 1, 1, 2, '/1/5/13/', 1, 1, 0, 'users/administrator_users', 13);
INSERT INTO ezcontentobject_tree VALUES (14, 5, 13, 1, 1, 2, '/1/5/14/', 1, 1, 0, 'users/editors', 14);
INSERT INTO ezcontentobject_tree VALUES (15, 13, 14, 1, 1, 3, '/1/5/13/15/', 1, 1, 0, 'users/administrator_users/administrator_user', 15);
INSERT INTO ezcontentobject_tree VALUES (42, 12, 40, 1, 1, 3, '/1/5/12/42/', 9, 1, 0, 'users/guest_accounts/test_test', 42);
INSERT INTO ezcontentobject_tree VALUES (43, 1, 41, 1, 1, 1, '/1/43/', 9, 1, 0, 'media', 43);


--
-- Data for TOC entry 90 (OID 33595)
-- Name: ezcontentobject_version; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezcontentobject_version VALUES (1, 1, 14, 1, 0, 0, 1, 1, 0);
INSERT INTO ezcontentobject_version VALUES (4, 4, 14, 1, 0, 0, 1, 1, 0);
INSERT INTO ezcontentobject_version VALUES (436, 1, 14, 2, 1033919080, 1033919080, 1, 1, 0);
INSERT INTO ezcontentobject_version VALUES (438, 10, 14, 1, 1033920649, 1033920665, 1, 0, 0);
INSERT INTO ezcontentobject_version VALUES (439, 11, 14, 1, 1033920737, 1033920746, 1, 0, 0);
INSERT INTO ezcontentobject_version VALUES (440, 12, 14, 1, 1033920760, 1033920775, 1, 0, 0);
INSERT INTO ezcontentobject_version VALUES (441, 13, 14, 1, 1033920786, 1033920794, 1, 0, 0);
INSERT INTO ezcontentobject_version VALUES (442, 14, 14, 1, 1033920808, 1033920830, 1, 0, 0);
INSERT INTO ezcontentobject_version VALUES (471, 40, 14, 1, 1053613007, 1053613020, 1, 0, 0);
INSERT INTO ezcontentobject_version VALUES (472, 41, 14, 1, 1060695450, 1060695457, 1, 0, 0);


--
-- Data for TOC entry 91 (OID 33607)
-- Name: ezdiscountrule; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 92 (OID 33613)
-- Name: ezdiscountsubrule; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 93 (OID 33620)
-- Name: ezdiscountsubrule_value; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 94 (OID 33627)
-- Name: ezenumobjectvalue; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 95 (OID 33637)
-- Name: ezenumvalue; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezenumvalue VALUES (2, 136, 0, 'Ok', '3', 2);
INSERT INTO ezenumvalue VALUES (1, 136, 0, 'Poor', '2', 1);
INSERT INTO ezenumvalue VALUES (3, 136, 0, 'Good', '5', 3);


--
-- Data for TOC entry 96 (OID 33648)
-- Name: ezforgot_password; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 97 (OID 33656)
-- Name: ezgeneral_digest_user_settings; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 98 (OID 33666)
-- Name: ezimage; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 99 (OID 33676)
-- Name: ezimagevariation; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 100 (OID 33687)
-- Name: ezinfocollection; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 101 (OID 33694)
-- Name: ezinfocollection_attribute; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 102 (OID 33704)
-- Name: ezkeyword; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 103 (OID 33710)
-- Name: ezkeyword_attribute_link; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 104 (OID 33717)
-- Name: ezmedia; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 105 (OID 33726)
-- Name: ezmessage; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 106 (OID 33740)
-- Name: ezmodule_run; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 107 (OID 33749)
-- Name: eznode_assignment; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO eznode_assignment VALUES (2, 1, 1, 1, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (4, 8, 2, 5, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (144, 4, 1, 1, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (147, 210, 1, 5, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (146, 209, 1, 5, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (145, 1, 2, 1, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (148, 9, 1, 2, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (149, 10, 1, 5, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (150, 11, 1, 5, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (151, 12, 1, 5, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (152, 13, 1, 5, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (153, 14, 1, 13, 1, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (181, 40, 1, 12, 9, 1, 1, 0, 0);
INSERT INTO eznode_assignment VALUES (182, 41, 1, 1, 9, 1, 1, 0, 0);


--
-- Data for TOC entry 108 (OID 33759)
-- Name: eznotificationcollection; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 109 (OID 33770)
-- Name: eznotificationcollection_item; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 110 (OID 33779)
-- Name: eznotificationevent; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO eznotificationevent VALUES (1, 0, 'ezpublish', 41, 1, 0, 0, '', '', '', '');


--
-- Data for TOC entry 111 (OID 33793)
-- Name: ezoperation_memento; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 112 (OID 33804)
-- Name: ezorder; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 113 (OID 33819)
-- Name: ezorder_item; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 114 (OID 33826)
-- Name: ezpolicy; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezpolicy VALUES (317, 3, '*', 'content', '*');
INSERT INTO ezpolicy VALUES (308, 2, '*', '*', '*');
INSERT INTO ezpolicy VALUES (326, 1, 'read', 'content', ' ');
INSERT INTO ezpolicy VALUES (325, 1, 'login', 'user', '*');
INSERT INTO ezpolicy VALUES (319, 3, 'login', 'user', '*');
INSERT INTO ezpolicy VALUES (323, 5, '*', 'content', '*');
INSERT INTO ezpolicy VALUES (324, 5, 'login', 'user', '*');


--
-- Data for TOC entry 115 (OID 33831)
-- Name: ezpolicy_limitation; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezpolicy_limitation VALUES (249, 326, 'Class', 0, 'read', 'content');


--
-- Data for TOC entry 116 (OID 33837)
-- Name: ezpolicy_limitation_value; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezpolicy_limitation_value VALUES (435, 249, '1');
INSERT INTO ezpolicy_limitation_value VALUES (436, 249, '10');
INSERT INTO ezpolicy_limitation_value VALUES (437, 249, '10');
INSERT INTO ezpolicy_limitation_value VALUES (438, 249, '11');
INSERT INTO ezpolicy_limitation_value VALUES (439, 249, '11');
INSERT INTO ezpolicy_limitation_value VALUES (440, 249, '12');
INSERT INTO ezpolicy_limitation_value VALUES (441, 249, '12');
INSERT INTO ezpolicy_limitation_value VALUES (442, 249, '13');
INSERT INTO ezpolicy_limitation_value VALUES (443, 249, '13');
INSERT INTO ezpolicy_limitation_value VALUES (444, 249, '2');
INSERT INTO ezpolicy_limitation_value VALUES (445, 249, '2');
INSERT INTO ezpolicy_limitation_value VALUES (446, 249, '5');
INSERT INTO ezpolicy_limitation_value VALUES (447, 249, '5');
INSERT INTO ezpolicy_limitation_value VALUES (448, 249, '6');
INSERT INTO ezpolicy_limitation_value VALUES (449, 249, '6');
INSERT INTO ezpolicy_limitation_value VALUES (450, 249, '7');
INSERT INTO ezpolicy_limitation_value VALUES (451, 249, '7');
INSERT INTO ezpolicy_limitation_value VALUES (452, 249, '8');
INSERT INTO ezpolicy_limitation_value VALUES (453, 249, '8');
INSERT INTO ezpolicy_limitation_value VALUES (454, 249, '9');
INSERT INTO ezpolicy_limitation_value VALUES (455, 249, '9');


--
-- Data for TOC entry 117 (OID 33842)
-- Name: ezpreferences; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 118 (OID 33849)
-- Name: ezproductcollection; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 119 (OID 33854)
-- Name: ezproductcollection_item; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 120 (OID 33862)
-- Name: ezproductcollection_item_opt; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 121 (OID 33872)
-- Name: ezrole; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezrole VALUES (1, 0, 'Anonymous', ' ');
INSERT INTO ezrole VALUES (2, 0, 'Administrator', '*');
INSERT INTO ezrole VALUES (3, 0, 'Editor', ' ');
INSERT INTO ezrole VALUES (5, 3, 'Editor', NULL);


--
-- Data for TOC entry 122 (OID 33879)
-- Name: ezsearch_object_word_link; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezsearch_object_word_link VALUES (26, 40, 5, 0, 0, 0, 5, 4, 1053613020, 2, 8, '', 0);
INSERT INTO ezsearch_object_word_link VALUES (27, 40, 5, 0, 1, 5, 0, 4, 1053613020, 2, 9, '', 0);
INSERT INTO ezsearch_object_word_link VALUES (28, 41, 6, 0, 0, 0, 0, 1, 1060695457, 3, 4, '', 0);


--
-- Data for TOC entry 123 (OID 33901)
-- Name: ezsearch_return_count; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezsearch_return_count VALUES (1, 1, 1061045391, 1);


--
-- Data for TOC entry 124 (OID 33909)
-- Name: ezsearch_search_phrase; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezsearch_search_phrase VALUES (1, 'media');


--
-- Data for TOC entry 125 (OID 33914)
-- Name: ezsearch_word; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezsearch_word VALUES (5, 'test', 1);
INSERT INTO ezsearch_word VALUES (6, 'media', 1);


--
-- Data for TOC entry 126 (OID 33921)
-- Name: ezsection; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezsection VALUES (1, 'Standard section', 'nor-NO', 'ezcontentnavigationpart');
INSERT INTO ezsection VALUES (2, 'Users', '', 'ezusernavigationpart');
INSERT INTO ezsection VALUES (3, 'Media', '', 'ezmedianavigationpart');


--
-- Data for TOC entry 127 (OID 33927)
-- Name: ezsession; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezsession VALUES ('bc038fad359d92172ab11d5b1f1cb461', 1061304663, 'LastAccessesURI|s:21:"/content/view/full/2/";eZUserInfoCache_Timestamp|i:1061041383;eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_Timestamp|i:1061041383;eZUserGroupsCache_14|a:1:{i:0;a:1:{s:2:"id";s:2:"12";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1061041383;UserRoles|a:1:{i:0;a:2:{s:2:"id";s:1:"2";s:4:"name";s:13:"Administrator";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}eZUserDiscountRulesTimestamp|i:1061041383;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:"id";s:1:"1";}Preferences-bookmark_menu|b:0;Preferences-history_menu|b:0;canInstantiateClassesCachedForUser|s:2:"14";classesCachedTimestamp|i:1061041387;canInstantiateClasses|i:1;classesCachedForUser|s:2:"14";canInstantiateClassList|a:13:{i:0;a:2:{s:2:"id";s:1:"1";s:4:"name";s:6:"Folder";}i:1;a:2:{s:2:"id";s:1:"2";s:4:"name";s:7:"Article";}i:2;a:2:{s:2:"id";s:1:"3";s:4:"name";s:10:"User group";}i:3;a:2:{s:2:"id";s:1:"4";s:4:"name";s:4:"User";}i:4;a:2:{s:2:"id";s:1:"5";s:4:"name";s:5:"Image";}i:5;a:2:{s:2:"id";s:1:"6";s:4:"name";s:5:"Forum";}i:6;a:2:{s:2:"id";s:1:"7";s:4:"name";s:13:"Forum message";}i:7;a:2:{s:2:"id";s:1:"8";s:4:"name";s:7:"Product";}i:8;a:2:{s:2:"id";s:1:"9";s:4:"name";s:14:"Product review";}i:9;a:2:{s:2:"id";s:2:"10";s:4:"name";s:9:"Info page";}i:10;a:2:{s:2:"id";s:2:"11";s:4:"name";s:4:"Link";}i:11;a:2:{s:2:"id";s:2:"12";s:4:"name";s:4:"File";}i:12;a:2:{s:2:"id";s:2:"13";s:4:"name";s:7:"Comment";}}FromGroupID|s:0:"";eZUserLoggedInID|s:2:"14";');


--
-- Data for TOC entry 128 (OID 33938)
-- Name: ezsubtree_notification_rule; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 129 (OID 33946)
-- Name: eztrigger; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 130 (OID 33955)
-- Name: ezurl; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 131 (OID 33965)
-- Name: ezurl_object_link; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 132 (OID 33972)
-- Name: ezurlalias; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezurlalias VALUES (12, '', 'd41d8cd98f00b204e9800998ecf8427e', 'content/view/full/2', 1, 0);
INSERT INTO ezurlalias VALUES (13, 'users', '9bc65c2abec141778ffaa729489f3e87', 'content/view/full/5', 1, 0);
INSERT INTO ezurlalias VALUES (14, 'users/anonymous_user', 'a37b7463e2c21098fa1a729dad4b4437', 'content/view/full/11', 1, 0);
INSERT INTO ezurlalias VALUES (15, 'users/guest_accounts', '02d4e844e3a660857a3f81585995ffe1', 'content/view/full/12', 1, 0);
INSERT INTO ezurlalias VALUES (16, 'users/administrator_users', '1b1d79c16700fd6003ea7be233e754ba', 'content/view/full/13', 1, 0);
INSERT INTO ezurlalias VALUES (17, 'users/editors', '0bb9dd665c96bbc1cf36b79180786dea', 'content/view/full/14', 1, 0);
INSERT INTO ezurlalias VALUES (18, 'users/administrator_users/administrator_user', 'f1305ac5f327a19b451d82719e0c3f5d', 'content/view/full/15', 1, 0);
INSERT INTO ezurlalias VALUES (19, 'users/guest_accounts/test_test', '27a1813763d43de613bf05c31df7a6ef', 'content/view/full/42', 1, 0);
INSERT INTO ezurlalias VALUES (20, 'media', '62933a2951ef01f4eafd9bdf4d3cd2f0', 'content/view/full/43', 1, 0);


--
-- Data for TOC entry 133 (OID 33983)
-- Name: ezuser; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezuser VALUES (10, 'anonymous', 'nospam@ez.no', 2, '4e6f6184135228ccd45f8233d72a0363');
INSERT INTO ezuser VALUES (14, 'admin', 'nospam@ez.no', 2, 'c78e3b0f3d9244ed8c6d1c29464bdff9');
INSERT INTO ezuser VALUES (40, 'test', 'test@test.com', 2, 'be778b473235e210cc577056226536a4');


--
-- Data for TOC entry 134 (OID 33991)
-- Name: ezuser_accountkey; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 135 (OID 33999)
-- Name: ezuser_discountrule; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 136 (OID 34005)
-- Name: ezuser_role; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezuser_role VALUES (29, 1, 10);
INSERT INTO ezuser_role VALUES (25, 2, 12);
INSERT INTO ezuser_role VALUES (30, 3, 13);
INSERT INTO ezuser_role VALUES (28, 1, 11);


--
-- Data for TOC entry 137 (OID 34011)
-- Name: ezuser_setting; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezuser_setting VALUES (10, 1, 1000);
INSERT INTO ezuser_setting VALUES (14, 1, 10);
INSERT INTO ezuser_setting VALUES (23, 1, 0);
INSERT INTO ezuser_setting VALUES (40, 1, 0);


--
-- Data for TOC entry 138 (OID 34017)
-- Name: ezvattype; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezvattype VALUES (1, 'Std', 0);


--
-- Data for TOC entry 139 (OID 34023)
-- Name: ezwaituntildatevalue; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 140 (OID 34033)
-- Name: ezwishlist; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 141 (OID 34040)
-- Name: ezworkflow; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 142 (OID 34053)
-- Name: ezworkflow_assign; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 143 (OID 34062)
-- Name: ezworkflow_event; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for TOC entry 144 (OID 34072)
-- Name: ezworkflow_group; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezworkflow_group VALUES (1, 'Standard', 14, 14, 1024392098, 1024392098);


--
-- Data for TOC entry 145 (OID 34082)
-- Name: ezworkflow_group_link; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ezworkflow_group_link VALUES (1, 1, 0, 'Standard');


--
-- Data for TOC entry 146 (OID 34089)
-- Name: ezworkflow_process; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1 (OID 33190)
-- Name: ezapprove_items_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezapprove_items_s', 1, false);


--
-- TOC entry 2 (OID 33192)
-- Name: ezbasket_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezbasket_s', 1, false);


--
-- TOC entry 3 (OID 33194)
-- Name: ezcollab_group_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcollab_group_s', 1, false);


--
-- TOC entry 4 (OID 33196)
-- Name: ezcollab_item_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcollab_item_s', 1, false);


--
-- TOC entry 5 (OID 33198)
-- Name: ezcollab_item_message_link_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcollab_item_message_link_s', 1, false);


--
-- TOC entry 6 (OID 33200)
-- Name: ezcollab_notification_rule_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcollab_notification_rule_s', 1, false);


--
-- TOC entry 7 (OID 33202)
-- Name: ezcollab_profile_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcollab_profile_s', 1, false);


--
-- TOC entry 8 (OID 33204)
-- Name: ezcollab_simple_message_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcollab_simple_message_s', 1, false);


--
-- TOC entry 9 (OID 33206)
-- Name: ezcontent_translation_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontent_translation_s', 1, false);


--
-- TOC entry 10 (OID 33208)
-- Name: ezcontentbrowsebookmark_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentbrowsebookmark_s', 1, false);


--
-- TOC entry 11 (OID 33210)
-- Name: ezcontentbrowserecent_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentbrowserecent_s', 1, false);


--
-- TOC entry 12 (OID 33212)
-- Name: ezcontentclass_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentclass_s', 1, false);


--
-- TOC entry 13 (OID 33214)
-- Name: ezcontentclass_attribute_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentclass_attribute_s', 1, false);


--
-- TOC entry 14 (OID 33216)
-- Name: ezcontentclassgroup_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentclassgroup_s', 1, false);


--
-- TOC entry 15 (OID 33218)
-- Name: ezcontentobject_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentobject_s', 1, false);


--
-- TOC entry 16 (OID 33220)
-- Name: ezcontentobject_attribute_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentobject_attribute_s', 1, false);


--
-- TOC entry 17 (OID 33222)
-- Name: ezcontentobject_link_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentobject_link_s', 1, false);


--
-- TOC entry 18 (OID 33224)
-- Name: ezcontentobject_tree_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentobject_tree_s', 1, false);


--
-- TOC entry 19 (OID 33226)
-- Name: ezcontentobject_version_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezcontentobject_version_s', 1, false);


--
-- TOC entry 20 (OID 33228)
-- Name: ezdiscountrule_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezdiscountrule_s', 1, false);


--
-- TOC entry 21 (OID 33230)
-- Name: ezdiscountsubrule_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezdiscountsubrule_s', 1, false);


--
-- TOC entry 22 (OID 33232)
-- Name: ezenumvalue_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezenumvalue_s', 1, false);


--
-- TOC entry 23 (OID 33234)
-- Name: ezforgot_password_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezforgot_password_s', 1, false);


--
-- TOC entry 24 (OID 33236)
-- Name: ezgeneral_digest_user_settings_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezgeneral_digest_user_settings_s', 1, false);


--
-- TOC entry 25 (OID 33238)
-- Name: ezinfocollection_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezinfocollection_s', 1, false);


--
-- TOC entry 26 (OID 33240)
-- Name: ezinfocollection_attribute_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezinfocollection_attribute_s', 1, false);


--
-- TOC entry 27 (OID 33242)
-- Name: ezkeyword_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezkeyword_s', 1, false);


--
-- TOC entry 28 (OID 33244)
-- Name: ezkeyword_attribute_link_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezkeyword_attribute_link_s', 1, false);


--
-- TOC entry 29 (OID 33246)
-- Name: ezmessage_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezmessage_s', 1, false);


--
-- TOC entry 30 (OID 33248)
-- Name: ezmodule_run_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezmodule_run_s', 1, false);


--
-- TOC entry 31 (OID 33250)
-- Name: eznode_assignment_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('eznode_assignment_s', 1, false);


--
-- TOC entry 32 (OID 33252)
-- Name: eznotificationcollection_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('eznotificationcollection_s', 1, false);


--
-- TOC entry 33 (OID 33254)
-- Name: eznotificationcollection_item_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('eznotificationcollection_item_s', 1, false);


--
-- TOC entry 34 (OID 33256)
-- Name: eznotificationevent_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('eznotificationevent_s', 1, false);


--
-- TOC entry 35 (OID 33258)
-- Name: ezoperation_memento_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezoperation_memento_s', 1, false);


--
-- TOC entry 36 (OID 33260)
-- Name: ezorder_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezorder_s', 1, false);


--
-- TOC entry 37 (OID 33262)
-- Name: ezorder_item_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezorder_item_s', 1, false);


--
-- TOC entry 38 (OID 33264)
-- Name: ezpolicy_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezpolicy_s', 1, false);


--
-- TOC entry 39 (OID 33266)
-- Name: ezpolicy_limitation_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezpolicy_limitation_s', 1, false);


--
-- TOC entry 40 (OID 33268)
-- Name: ezpolicy_limitation_value_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezpolicy_limitation_value_s', 1, false);


--
-- TOC entry 41 (OID 33270)
-- Name: ezpreferences_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezpreferences_s', 1, false);


--
-- TOC entry 42 (OID 33272)
-- Name: ezproductcollection_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezproductcollection_s', 1, false);


--
-- TOC entry 43 (OID 33274)
-- Name: ezproductcollection_item_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezproductcollection_item_s', 1, false);


--
-- TOC entry 44 (OID 33276)
-- Name: ezproductcollection_item_opt_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezproductcollection_item_opt_s', 1, false);


--
-- TOC entry 45 (OID 33278)
-- Name: ezrole_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezrole_s', 1, false);


--
-- TOC entry 46 (OID 33280)
-- Name: ezsearch_object_word_link_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezsearch_object_word_link_s', 1, false);


--
-- TOC entry 47 (OID 33282)
-- Name: ezsearch_return_count_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezsearch_return_count_s', 1, true);


--
-- TOC entry 48 (OID 33284)
-- Name: ezsearch_search_phrase_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezsearch_search_phrase_s', 1, true);


--
-- TOC entry 49 (OID 33286)
-- Name: ezsearch_word_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezsearch_word_s', 1, false);


--
-- TOC entry 50 (OID 33288)
-- Name: ezsection_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezsection_s', 1, false);


--
-- TOC entry 51 (OID 33290)
-- Name: ezsubtree_notification_rule_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezsubtree_notification_rule_s', 1, false);


--
-- TOC entry 52 (OID 33292)
-- Name: eztrigger_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('eztrigger_s', 1, false);


--
-- TOC entry 53 (OID 33294)
-- Name: ezurl_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezurl_s', 1, false);


--
-- TOC entry 54 (OID 33296)
-- Name: ezurlalias_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezurlalias_s', 1, false);


--
-- TOC entry 55 (OID 33298)
-- Name: ezuser_accountkey_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezuser_accountkey_s', 1, false);


--
-- TOC entry 56 (OID 33300)
-- Name: ezuser_discountrule_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezuser_discountrule_s', 1, false);


--
-- TOC entry 57 (OID 33302)
-- Name: ezuser_role_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezuser_role_s', 1, false);


--
-- TOC entry 58 (OID 33304)
-- Name: ezvattype_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezvattype_s', 1, false);


--
-- TOC entry 59 (OID 33306)
-- Name: ezwaituntildatevalue_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezwaituntildatevalue_s', 1, false);


--
-- TOC entry 60 (OID 33308)
-- Name: ezwishlist_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezwishlist_s', 1, false);


--
-- TOC entry 61 (OID 33310)
-- Name: ezworkflow_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezworkflow_s', 1, false);


--
-- TOC entry 62 (OID 33312)
-- Name: ezworkflow_assign_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezworkflow_assign_s', 1, false);


--
-- TOC entry 63 (OID 33314)
-- Name: ezworkflow_event_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezworkflow_event_s', 1, false);


--
-- TOC entry 64 (OID 33316)
-- Name: ezworkflow_group_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezworkflow_group_s', 1, false);


--
-- TOC entry 65 (OID 33318)
-- Name: ezworkflow_process_s; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval ('ezworkflow_process_s', 1, false);


