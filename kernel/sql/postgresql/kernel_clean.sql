--
-- TOC Entry ID 68 (OID 373906)
--
-- Name: ezapprovetasks_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezapprovetasks_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 127 (OID 373908)
--
-- Name: ezapprovetasks Type: TABLE Owner: sp
--

CREATE TABLE "ezapprovetasks" (
	"id" integer DEFAULT nextval('ezapprovetasks_s'::text) NOT NULL,
	"workflow_process_id" integer,
	"task_id" integer,
	Constraint "ezapprovetasks_pkey" Primary Key ("id")
);




--
-- TOC Entry ID 42 (OID 360514)
--
-- Name: ezbasket_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezbasket_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 100 (OID 360516)
--
-- Name: ezbasket Type: TABLE Owner: sp
--

CREATE TABLE "ezbasket" (
	"id" integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
	"session_id" character varying(255) NOT NULL,
	"productcollection_id" integer NOT NULL,
	Constraint "ezbasket_pkey" Primary Key ("id")
);




--
-- TOC Entry ID 93 (OID 360485)
--
-- Name: ezbinaryfile Type: TABLE Owner: sp
--

CREATE TABLE "ezbinaryfile" (
	"contentobject_attribute_id" integer NOT NULL,
	"version" integer NOT NULL,
	"filename" character varying(255) NOT NULL,
	"original_filename" character varying(255) NOT NULL,
	"mime_type" character varying(50) NOT NULL,
	Constraint "ezbinaryfile_pkey" Primary Key ("contentobject_attribute_id", "version")
);


--
-- TOC Entry ID 12 (OID 360419)
--
-- Name: ezcontentclass_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentclass_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 79 (OID 360421)
--
-- Name: ezcontentclass Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentclass" (
	"id" integer DEFAULT nextval('ezcontentclass_s'::text) NOT NULL,
	"version" integer NOT NULL,
	"name" character varying(255),
	"identifier" character varying(50) NOT NULL,
	"contentobject_name" character varying(255),
	"creator_id" integer NOT NULL,
	"modifier_id" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	Constraint "ezcontentclass_pkey" Primary Key ("id", "version")
);

INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1033922265);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1033922035);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1033922064);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1033922083);
INSERT INTO ezcontentclass (id, version, name, identifier, contentobject_name, creator_id, modifier_id, created, modified) VALUES (5,0,'Image','','<name>',8,14,1031484992,1033921948);

-- Name: ezcontentclass_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"ezcontentclass_s"', 6, true);



--
-- TOC Entry ID 14 (OID 360424)
--
-- Name: ezcontentclass_attribute_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentclass_attribute_s" start 124 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 80 (OID 360426)
--
-- Name: ezcontentclass_attribute Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentclass_attribute" (
	"id" integer DEFAULT nextval('ezcontentclass_attribute_s'::text) NOT NULL,
	"version" integer NOT NULL,
	"contentclass_id" integer NOT NULL,
	"identifier" character varying(50) NOT NULL,
	"name" character varying(255) NOT NULL,
	"data_type_string" character varying(50) NOT NULL,
	"placement" integer NOT NULL,
	"is_searchable" smallint DEFAULT '0',
	"is_required" smallint DEFAULT '0',
	"data_int1" integer,
	"data_int2" integer,
	"data_int3" integer,
	"data_int4" integer,
	"data_float1" double precision,
	"data_float2" double precision,
	"data_float3" double precision,
	"data_float4" double precision,
	"data_text1" character varying(50),
	"data_text2" character varying(50),
	"data_text3" character varying(50),
	"data_text4" character varying(50),
    "is_information_collector" integer NOT NULL default '0',
	Constraint "ezcontentclass_attribute_pkey" Primary Key ("id", "version")
);

INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (119,0,1,'description','Description','ezxmltext',1,0,2,0,0,0,0,0,0,0,0,'','','','');

INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (121,0,2,'body','Body','ezxmltext',1,0,3,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (120,0,2,'intro','Intro','ezxmltext',1,1,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (1,0,2,'title','Title','ezstring',0,1,1,255,0,0,0,0,0,0,0,'New article','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',1,0,4,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',1,0,5,0,0,0,0,0,0,0,0,'','','','');

INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (7,0,3,'description','Description','ezstring',1,0,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','');

INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (9,0,4,'last_name','Last name','ezstring',1,1,2,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (12,0,4,'user_account','User account','ezuser',1,1,3,0,0,0,0,0,0,0,0,'','','','');

INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (118,0,5,'image','Image','ezimage',0,0,3,2,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (117,0,5,'caption','Caption','ezxmltext',0,0,2,0,0,0,0,0,0,0,0,'','','','');
INSERT INTO ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, is_required, placement, data_int1, data_int2, data_int3, data_int4, data_float1, data_float2, data_float3, data_float4, data_text1, data_text2, data_text3, data_text4) VALUES (116,0,5,'name','Name','ezstring',0,0,1,150,0,0,0,0,0,0,0,'','','','');

-- Name: ezcontentclass_attribute_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"ezcontentclass_attribute_s"', 124, true);


--
-- TOC Entry ID 97 (OID 360501)
--
-- Name: ezcontentclass_classgroup Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentclass_classgroup" (
	"contentclass_id" integer NOT NULL,
	"contentclass_version" integer NOT NULL,
	"group_id" integer NOT NULL,
	"group_name" character varying(255),
	Constraint "ezcontentclass_classgroup_pkey" Primary Key ("contentclass_id", "contentclass_version", "group_id")
);

INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (1,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (2,0,1,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (4,0,2,'Content');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (5,0,3,'Media');
INSERT INTO ezcontentclass_classgroup (contentclass_id, contentclass_version, group_id, group_name) VALUES (3,0,2,'');


--
-- TOC Entry ID 36 (OID 360496)
--
-- Name: ezcontentclassgroup_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentclassgroup_s" start 4 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 96 (OID 360498)
--
-- Name: ezcontentclassgroup Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentclassgroup" (
	"id" integer DEFAULT nextval('ezcontentclassgroup_s'::text) NOT NULL,
	"name" character varying(255),
	"creator_id" integer NOT NULL,
	"modifier_id" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	Constraint "ezcontentclassgroup_pkey" Primary Key ("id")
);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO ezcontentclassgroup (id, name, creator_id, modifier_id, created, modified) VALUES (3,'Media',8,14,1032009743,1033922120);
-- Name: ezcontentclassgroup_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"ezcontentclassgroup_s"', 4, true);


--
-- TOC Entry ID 16 (OID 360429)
--
-- Name: ezcontentobject_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_s" start 15 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 81 (OID 360431)
--
-- Name: ezcontentobject Type: TABLE Owner: sp
--
-- 	"main_node_id" integer NOT NULL,
-- 	"permission_id" integer,

CREATE TABLE "ezcontentobject" (
	"id" integer DEFAULT nextval('ezcontentobject_s'::text) NOT NULL,
	"parent_id" integer NOT NULL,
	"owner_id" integer DEFAULT '0' NOT NULL,
	"section_id" integer DEFAULT '0' NOT NULL,
	"contentclass_id" integer NOT NULL,
	"name" character varying(255),
	"current_version" integer,
	"is_published" integer,
	"published" integer,
	"modified" integer,
	Constraint "ezcontentobject_pkey" Primary Key ("id")
);

INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id, published, modified) VALUES (1,0,0,2,1,1,'Frontpage',1,0,1,1033917596,1033917596);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id, published, modified) VALUES (4,0,0,5,0,3,'Users',1,0,1,0,0);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id, published, modified) VALUES (10,8,0,11,0,4,'Anonymous User',1,0,1,1033920665,1033920665);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id, published, modified) VALUES (11,8,0,12,0,3,'Guest accounts',1,0,1,1033920746,1033920746);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id, published, modified) VALUES (12,8,0,13,0,3,'Administrator users',1,0,1,1033920775,1033920775);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id, published, modified) VALUES (13,8,0,14,0,3,'Editors',1,0,1,1033920794,1033920794);
INSERT INTO ezcontentobject (id, owner_id, parent_id, main_node_id, section_id, contentclass_id, name, current_version, is_published, permission_id, published, modified) VALUES (14,8,0,15,0,4,'Administrator User',1,0,1,1033920830,1033920830);
-- Name: ezcontentobject_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"ezcontentobject_s"', 15, true);

--
-- TOC Entry ID 22 (OID 360444)
--
-- Name: ezcontentobject_attribute_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_attribute_s" start 30 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 84 (OID 360446)
--
-- Name: ezcontentobject_attribute Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentobject_attribute" (
	"id" integer DEFAULT nextval('ezcontentobject_attribute_s'::text) NOT NULL,
	"language_code" character varying(20) NOT NULL,
	"version" integer NOT NULL,
	"contentobject_id" integer NOT NULL,
	"contentclassattribute_id" integer NOT NULL,
	"data_text" text,
	"data_int" integer,
	"data_float" double precision,
	Constraint "ezcontentobject_attribute_pkey" Primary Key ("id", "version")
);

INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (1,'eng-GB',1,1,4,'Frontpage',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (2,'eng-GB',1,1,119,'This folder contains some information about...',0,0);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (7,'eng-GB',1,4,5,'Main group',NULL,NULL);
INSERT INTO ezcontentobject_attribute (id, language_code, version, contentobject_id, contentclassattribute_id, data_text, data_int, data_float) VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL);
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
-- Name: ezcontentobject_attribute_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"ezcontentobject_attribute_s"', 371, true);

--
-- TOC Entry ID 18 (OID 360434)
--
-- Name: ezcontentobject_link_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 82 (OID 360436)
--
-- Name: ezcontentobject_link Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentobject_link" (
	"id" integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
	"from_contentobject_id" integer NOT NULL,
	"from_contentobject_version" integer NOT NULL,
	"to_contentobject_id" integer,
	Constraint "ezcontentobject_link_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 50 (OID 360540)
--
-- Name: ezcontentobject_tree_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_tree_s" start 16 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 105 (OID 360542)
--
-- Name: ezcontentobject_tree Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentobject_tree" (
	"node_id" integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
	"parent_node_id" integer NOT NULL,
	"contentobject_id" integer,
	"contentobject_version" integer,
	"contentobject_is_published" integer,
	"crc32_path" integer,
	"depth" integer NOT NULL,
	"path_string" character varying(255) NOT NULL,
	"path_identification_string" text,
    "sort_field" integer default 1,
    sort_order smallint default 1,
    priority integer  default 0,
	Constraint "ezcontentobject_tree_pkey" Primary Key ("node_id")
);

INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (1,1,0,1,1,NULL,0,'/1/',NULL,1,16,NULL);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (2,1,1,1,1,1360594808,1,'/1/2/','',2,7,'frontpage');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (5,1,4,1,NULL,NULL,1,'/1/5/',NULL,8,15,NULL);
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (11,5,10,1,1,-1609495635,2,'/1/5/11/','',0,0,'users/');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (12,5,11,1,1,-1609495635,2,'/1/5/12/','',0,0,'users/');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (13,5,12,1,1,-1609495635,2,'/1/5/13/','',0,0,'users/');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (14,5,13,1,1,-1609495635,2,'/1/5/14/','',0,0,'users/');
INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, crc32_path, depth, path_string, md5_path, left_margin, right_margin, path_identification_string) VALUES (15,13,14,1,1,934329528,3,'/1/5/13/15/','',0,0,'users/administrator_users/');

-- Name: ezcontentobject_tree_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"ezcontentobject_tree_s"', 16, true);

--
-- TOC Entry ID 20 (OID 360439)
--
-- Name: ezcontentobject_version_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_version_s" start 443 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 83 (OID 360441)
--
-- Name: ezcontentobject_version Type: TABLE Owner: sp
--

CREATE TABLE "ezcontentobject_version" (
	"id" integer DEFAULT nextval('ezcontentobject_version_s'::text) NOT NULL,
	"contentobject_id" integer,
	"creator_id" integer DEFAULT '0' NOT NULL,
	"version" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	"status" integer DEFAULT '0' NOT NULL,
	"workflow_event_pos" integer DEFAULT '0' NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"main_node_id" integer,
	Constraint "ezcontentobject_version_pkey" Primary Key ("id")
);

INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (436,1,8,1,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (4,  4,8,1,1033919080,1033919080,1,1,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (438,10,8,1,1033920649,1033920665,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (439,11,8,1,1033920737,1033920746,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (440,12,8,1,1033920760,1033920775,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (441,13,8,1,1033920786,1033920794,0,0,0);
INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, workflow_event_pos, user_id) VALUES (442,14,8,1,1033920808,1033920830,0,0,0);

-- Name: ezcontentobject_version_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"ezcontentobject_version_s"', 443, true);



--
-- TOC Entry ID 95 (OID 360493)
--
-- Name: ezenumobjectvalue Type: TABLE Owner: sp
--

CREATE TABLE "ezenumobjectvalue" (
	"contentobject_attribute_id" integer NOT NULL,
	"contentobject_attribute_version" integer NOT NULL,
	"enumid" integer NOT NULL,
	"enumelement" character varying(255) NOT NULL,
	"enumvalue" character varying(255) NOT NULL,
	Constraint "ezenumobjectvalue_pkey" Primary Key ("contentobject_attribute_id", "contentobject_attribute_version", "enumid")
);


--
-- TOC Entry ID 34 (OID 360488)
--
-- Name: ezenumvalue_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezenumvalue_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 94 (OID 360490)
--
-- Name: ezenumvalue Type: TABLE Owner: sp
--

CREATE TABLE "ezenumvalue" (
	"id" integer DEFAULT nextval('ezenumvalue_s'::text) NOT NULL,
	"contentclass_attribute_id" integer NOT NULL,
	"contentclass_attribute_version" integer NOT NULL,
	"enumelement" character varying(255) NOT NULL,
	"enumvalue" character varying(255) NOT NULL,
	"placement" integer NOT NULL,
	Constraint "ezenumvalue_pkey" Primary Key ("id", "contentclass_attribute_id", "contentclass_attribute_version")
);



--
-- TOC Entry ID 91 (OID 360479)
--
-- Name: ezimage Type: TABLE Owner: sp
--

CREATE TABLE "ezimage" (
	"contentobject_attribute_id" integer NOT NULL,
	"version" integer NOT NULL,
	"filename" character varying(255) NOT NULL,
	"original_filename" character varying(255) NOT NULL,
	"mime_type" character varying(50) NOT NULL,
	Constraint "ezimage_pkey" Primary Key ("contentobject_attribute_id", "version")
);


--
-- TOC Entry ID 92 (OID 360482)
--
-- Name: ezimagevariation Type: TABLE Owner: sp
--

CREATE TABLE "ezimagevariation" (
	"contentobject_attribute_id" integer NOT NULL,
	"version" integer NOT NULL,
	"filename" character varying(255) NOT NULL,
	"additional_path" character varying(255),
	"requested_width" integer NOT NULL,
	"requested_height" integer NOT NULL,
	"width" integer NOT NULL,
	"height" integer NOT NULL,
	Constraint "ezimagevariation_pkey" Primary Key ("contentobject_attribute_id", "version", "requested_width", "requested_height")
);


--
-- TOC Entry ID 128 (OID 373911)
--
-- Name: ezmedia Type: TABLE Owner: sp
--

CREATE TABLE "ezmedia" (
	"contentobject_attribute_id" integer NOT NULL,
	"version" integer NOT NULL,
	"filename" character varying(255) NOT NULL,
	"original_filename" character varying(255) NOT NULL,
	"mime_type" character varying(50) NOT NULL,
	"width" integer,
	"height" integer,
	"has_controller" smallint,
	"controls" character varying(50),
	"is_autoplay" smallint,
	"pluginspage" character varying(255),
	"quality" character varying(50),
	"is_loop" smallint,
	Constraint "ezmedia_pkey" Primary Key ("contentobject_attribute_id", "version")
);




--
-- TOC Entry ID 70 (OID 382072)
--
-- Name: ezmodule_run_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezmodule_run_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 129 (OID 382074)
--
-- Name: ezmodule_run Type: TABLE Owner: sp
--

CREATE TABLE "ezmodule_run" (
	"id" integer DEFAULT nextval('ezmodule_run_s'::text) NOT NULL,
	"workflow_process_id" integer,
	"module_name" character varying(255),
	"function_name" character varying(255),
	"module_data" text,
	Constraint "ezmodule_run_pkey" Primary Key ("id")
);



--
-- TOC Entry ID 72 (OID 382706)
--
-- Name: eznode_assignment_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "eznode_assignment_s" start 154 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 130 (OID 382708)
--
-- Name: eznode_assignment Type: TABLE Owner: sp
--

CREATE TABLE "eznode_assignment" (
	"id" integer DEFAULT nextval('eznode_assignment_s'::text) NOT NULL,
	"contentobject_id" integer,
	"contentobject_version" integer,
	"parent_node" integer,
	"main" integer,
    "sort_field" integer DEFAULT 1,
    "sort_order" smallint DEFAULT 1,
    "from_node_id" integer default '0',
	Constraint "eznode_assignment_pkey" Primary Key ("id")
);

INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (2,1,1,1,1);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (3,4,1,1,1);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (148,9,1,2,1);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (149,10,1,5,1);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (150,11,1,5,1);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (151,12,1,5,1);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (152,13,1,5,1);
INSERT INTO eznode_assignment (id, contentobject_id, contentobject_version, parent_node, main) VALUES (153,14,1,13,1);

-- Name: eznode_assignment_s Type: SEQUENCE SET Owner: sp
SELECT setval ('"eznode_assignment_s"', 154, true);



--
-- TOC Entry ID 44 (OID 360519)
--
-- Name: ezorder_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezorder_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 101 (OID 360521)
--
-- Name: ezorder Type: TABLE Owner: sp
--

CREATE TABLE "ezorder" (
	"id" integer DEFAULT nextval('ezorder_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"productcollection_id" integer NOT NULL,
	"created" integer NOT NULL,
	Constraint "ezorder_pkey" Primary Key ("id")
);



--
-- TOC Entry ID 56 (OID 360558)
--
-- Name: ezpolicy_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezpolicy_s" start 315 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 108 (OID 360560)
--
-- Name: ezpolicy Type: TABLE Owner: sp
--

CREATE TABLE "ezpolicy" (
	"id" integer DEFAULT nextval('ezpolicy_s'::text) NOT NULL,
	"role_id" integer,
	"function_name" character varying,
	"module_name" character varying,
	"limitation" character(1),
	Constraint "ezpolicy_pkey" Primary Key ("id")
);

INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (306,1,'read','content','');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (314,3,'*','content','*');
INSERT INTO ezpolicy (id, role_id, function_name, module_name, limitation) VALUES (308,2,'*','*','*');

--
-- TOC Entry ID 58 (OID 360566)
--
-- Name: ezpolicy_limitation_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezpolicy_limitation_s" start 245 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 109 (OID 360568)
--
-- Name: ezpolicy_limitation Type: TABLE Owner: sp
--

CREATE TABLE "ezpolicy_limitation" (
	"id" integer DEFAULT nextval('ezpolicy_limitation_s'::text) NOT NULL,
	"policy_id" integer,
	"identifier" character varying NOT NULL,
	"role_id" integer,
	"function_name" character varying,
	"module_name" character varying,
	Constraint "ezpolicy_limitation_pkey" Primary Key ("id")
);

INSERT INTO ezpolicy_limitation (id, policy_id, identifier, role_id, function_name, module_name) VALUES (244,306,'Class',0,'read','content');




--
-- TOC Entry ID 60 (OID 360574)
--
-- Name: ezpolicy_limitation_value_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezpolicy_limitation_value_s" start 408 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 110 (OID 360576)
--
-- Name: ezpolicy_limitation_value Type: TABLE Owner: sp
--

CREATE TABLE "ezpolicy_limitation_value" (
	"id" integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
	"limitation_id" integer,
	"value" integer,
	Constraint "ezpolicy_limitation_value_pkey" Primary Key ("id")
);

INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (407,244,1);
INSERT INTO ezpolicy_limitation_value (id, limitation_id, value) VALUES (408,244,7);



-- TOC Entry ID 38 (OID 360504)
--
-- Name: ezproductcollection_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezproductcollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 98 (OID 360506)
--
-- Name: ezproductcollection Type: TABLE Owner: sp
--

CREATE TABLE "ezproductcollection" (
	"id" integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
	Constraint "ezproductcollection_pkey" Primary Key ("id")
);




--
-- TOC Entry ID 40 (OID 360509)
--
-- Name: ezproductcollection_item_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezproductcollection_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 99 (OID 360511)
--
-- Name: ezproductcollection_item Type: TABLE Owner: sp
--

CREATE TABLE "ezproductcollection_item" (
	"id" integer DEFAULT nextval('ezproductcollection_item_s'::text) NOT NULL,
	"productcollection_id" integer NOT NULL,
	"contentobject_id" integer NOT NULL,
	"item_count" integer NOT NULL,
	"price" integer NOT NULL,
	Constraint "ezproductcollection_item_pkey" Primary Key ("id")
);



--
-- TOC Entry ID 52 (OID 360545)
--
-- Name: ezrole_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezrole_s" start 4 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 106 (OID 360547)
--
-- Name: ezrole Type: TABLE Owner: sp
--

CREATE TABLE "ezrole" (
	"id" integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
	"version" integer DEFAULT '0',
	"name" character varying NOT NULL,
	"value" character(1),
	Constraint "ezrole_pkey" Primary Key ("id")
);



INSERT INTO ezrole (id, version, name, value) VALUES (1,0,'Anonymous','');
INSERT INTO ezrole (id, version, name, value) VALUES (2,0,'Administrator','*');
INSERT INTO ezrole (id, version, name, value) VALUES (3,0,'Editor','');


--
-- TOC Entry ID 26 (OID 360459)
--
-- Name: ezsearch_object_word_link_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_object_word_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 87 (OID 360461)
--
-- Name: ezsearch_object_word_link Type: TABLE Owner: sp
--

CREATE TABLE "ezsearch_object_word_link" (
	"id" integer DEFAULT nextval('ezsearch_object_word_link_s'::text) NOT NULL,
	"contentobject_id" integer NOT NULL,
	"word_id" integer NOT NULL,
	"frequency" double precision NOT NULL,
	"placement" integer NOT NULL,
	"prev_word_id" integer NOT NULL,
	"next_word_id" integer NOT NULL,
	"contentclass_id" integer NOT NULL,
	"contentclass_attribute_id" integer NOT NULL,
    "published" integer NOT NULL default '0',
    "section_id" integer NOT NULL default '0',
	Constraint "ezsearch_object_word_link_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 32 (OID 360474)
--
-- Name: ezsearch_return_count_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_return_count_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 90 (OID 360476)
--
-- Name: ezsearch_return_count Type: TABLE Owner: sp
--

CREATE TABLE "ezsearch_return_count" (
	"id" integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
	"phrase_id" integer NOT NULL,
	"time" integer NOT NULL,
	"count" integer NOT NULL,
	Constraint "ezsearch_return_count_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 30 (OID 360469)
--
-- Name: ezsearch_search_phrase_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_search_phrase_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 89 (OID 360471)
--
-- Name: ezsearch_search_phrase Type: TABLE Owner: sp
--

CREATE TABLE "ezsearch_search_phrase" (
	"id" integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
	"phrase" character varying(250),
	Constraint "ezsearch_search_phrase_pkey" Primary Key ("id")
);




--
-- TOC Entry ID 28 (OID 360464)
--
-- Name: ezsearch_word_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_word_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 88 (OID 360466)
--
-- Name: ezsearch_word Type: TABLE Owner: sp
--

CREATE TABLE "ezsearch_word" (
	"id" integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
	"word" character varying(150),
	"object_count" integer NOT NULL,
	Constraint "ezsearch_word_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 48 (OID 360535)
--
-- Name: ezsection_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 104 (OID 360537)
--
-- Name: ezsection Type: TABLE Owner: sp
--

CREATE TABLE "ezsection" (
	"id" integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
	"name" character varying(255),
	"locale" character varying(255),
	Constraint "ezsection_pkey" Primary Key ("id")
);

INSERT INTO ezsection (id, name, locale) VALUES (1,'Standard section','nor-NO');

--
-- TOC Entry ID 103 (OID 360529)
--
-- Name: ezsession Type: TABLE Owner: sp
--

CREATE TABLE "ezsession" (
	"session_key" character(32) NOT NULL,
	"expiration_time" integer NOT NULL,
	"data" text NOT NULL,
	Constraint "ezsession_pkey" Primary Key ("session_key")
);



--
-- TOC Entry ID 64 (OID 365753)
--
-- Name: eztask_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "eztask_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 124 (OID 365755)
--
-- Name: eztask Type: TABLE Owner: sp
--

CREATE TABLE "eztask" (
	"id" integer DEFAULT nextval('eztask_s'::text) NOT NULL,
	"task_type" integer NOT NULL,
	"status" integer NOT NULL,
	"connection_type" integer NOT NULL,
	"session_hash" character varying(80) NOT NULL,
	"creator_id" integer NOT NULL,
	"receiver_id" integer NOT NULL,
	"parent_task_type" integer NOT NULL,
	"parent_task_id" integer NOT NULL,
	"access_type" integer NOT NULL,
	"object_type" integer NOT NULL,
	"object_id" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	Constraint "eztask_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 66 (OID 365758)
--
-- Name: eztask_message_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "eztask_message_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 125 (OID 365760)
--
-- Name: eztask_message Type: TABLE Owner: sp
--

CREATE TABLE "eztask_message" (
	"id" integer DEFAULT nextval('eztask_message_s'::text) NOT NULL,
	"task_id" integer NOT NULL,
	"contentobject_id" integer NOT NULL,
	"created" integer NOT NULL,
	"creator_id" integer NOT NULL,
	"creator_type" integer NOT NULL,
	"is_published" integer,
	Constraint "eztask_message_pkey" Primary Key ("id")
);


--
-- TOC Entry ID 62 (OID 365634)
--
-- Name: eztrigger_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "eztrigger_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 123 (OID 365639)
--
-- Name: eztrigger Type: TABLE Owner: sp
--

CREATE TABLE "eztrigger" (
	"id" integer DEFAULT nextval('eztrigger_s'::text),
    "name" varchar(255),
	"module_name" character varying(255) NOT NULL,
	"function_name" character varying(255) NOT NULL,
	"connect_type" character(1) NOT NULL,
	"workflow_id" integer
);

--
-- TOC Entry ID 86 (OID 360457)
--
-- Name: ezuser Type: TABLE Owner: sp
--

CREATE TABLE "ezuser" (
	"contentobject_id" integer NOT NULL,
	"login" character varying(150) NOT NULL,
	"email" character varying(150) NOT NULL,
	"password_hash_type" integer DEFAULT 1 NOT NULL,
	"password_hash" character varying(50)
);

INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (10,'anonymous','nospam@ez.no',3,'db52c38a553f880386435b8bb1f74393');
INSERT INTO ezuser (contentobject_id, login, email, password_hash_type, password_hash) VALUES (14,'admin','nospam@ez.no',3,'adcd37bc8ee8b2845e8419ac0f752e0f');


--
-- TOC Entry ID 54 (OID 360553)
--
-- Name: ezuser_role_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezuser_role_s" start 26 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 107 (OID 360555)
--
-- Name: ezuser_role Type: TABLE Owner: sp
--

CREATE TABLE "ezuser_role" (
	"id" integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
	"role_id" integer,
	"contentobject_id" integer,
	Constraint "ezuser_role_pkey" Primary Key ("id")
);


INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (24,1,4);
INSERT INTO ezuser_role (id, role_id, contentobject_id) VALUES (25,2,12);


--
-- Table structure for table 'ezuser_setting'
--


CREATE TABLE ezuser_setting (
  user_id int NOT NULL default '0',
  is_enabled smallint NOT NULL default '0',
  max_login int,
  PRIMARY KEY  (user_id)
);

INSERT INTO ezuser_setting VALUES (10,1,1000);
INSERT INTO ezuser_setting VALUES (14,1,10);


--
-- TOC Entry ID 46 (OID 360524)
--
-- Name: ezwishlist_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezwishlist_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 102 (OID 360526)
--
-- Name: ezwishlist Type: TABLE Owner: sp
--

CREATE TABLE "ezwishlist" (
	"id" integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"productcollection_id" integer NOT NULL,
	Constraint "ezwishlist_pkey" Primary Key ("id")
);


--
-- TOC Entry ID 4 (OID 360399)
--
-- Name: ezworkflow_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 76 (OID 360401)
--
-- Name: ezworkflow Type: TABLE Owner: sp
--

CREATE TABLE "ezworkflow" (
	"id" integer DEFAULT nextval('ezworkflow_s'::text) NOT NULL,
	"version" integer NOT NULL,
	"workflow_type_string" character varying(50) NOT NULL,
	"name" character varying(255) NOT NULL,
	"creator_id" integer NOT NULL,
	"modifier_id" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	"is_enabled" integer,
	Constraint "ezworkflow_pkey" Primary Key ("id", "version")
);

INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES (1,0,1,'group_ezserial','Sp\'s forkflow',8,24,1031927869,1032856662);


--
-- TOC Entry ID 6 (OID 360404)
--
-- Name: ezworkflow_assign_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_assign_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 77 (OID 360406)
--
-- Name: ezworkflow_assign Type: TABLE Owner: sp
--

CREATE TABLE "ezworkflow_assign" (
	"id" integer DEFAULT nextval('ezworkflow_assign_s'::text) NOT NULL,
	"workflow_id" integer NOT NULL,
	"node_id" integer NOT NULL,
	"access_type" integer NOT NULL,
	"as_tree" integer NOT NULL,
	Constraint "ezworkflow_assign_pkey" Primary Key ("id")
);


--
-- TOC Entry ID 8 (OID 360409)
--
-- Name: ezworkflow_event_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_event_s" start 3 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 78 (OID 360411)
--
-- Name: ezworkflow_event Type: TABLE Owner: sp
--

CREATE TABLE "ezworkflow_event" (
	"id" integer DEFAULT nextval('ezworkflow_event_s'::text) NOT NULL,
	"version" integer NOT NULL,
	"workflow_id" integer NOT NULL,
	"workflow_type_string" character varying(50) NOT NULL,
	"description" character varying(50) NOT NULL,
	"data_int1" integer,
	"data_int2" integer,
	"data_int3" integer,
	"data_int4" integer,
	"data_text1" character varying(50),
	"data_text2" character varying(50),
	"data_text3" character varying(50),
	"data_text4" character varying(50),
	"placement" integer NOT NULL,
	Constraint "ezworkflow_event_pkey" Primary Key ("id", "version")
);

INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO ezworkflow_event (id, version, workflow_id, workflow_type_string, description, data_int1, data_int2, data_int3, data_int4, data_text1, data_text2, data_text3, data_text4, placement) VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);


--
-- TOC Entry ID 2 (OID 360391)
--
-- Name: ezworkflow_group_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_group_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 74 (OID 360393)
--
-- Name: ezworkflow_group Type: TABLE Owner: sp
--

CREATE TABLE "ezworkflow_group" (
	"id" integer DEFAULT nextval('ezworkflow_group_s'::text) NOT NULL,
	"name" character varying(255) NOT NULL,
	"creator_id" integer NOT NULL,
	"modifier_id" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	Constraint "ezworkflow_group_pkey" Primary Key ("id")
);

INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES (1,'Standard',-1,-1,1024392098,1024392098);


--
-- TOC Entry ID 75 (OID 360396)
--
-- Name: ezworkflow_group_link Type: TABLE Owner: sp
--


CREATE TABLE "ezworkflow_group_link" (
	"workflow_id" integer NOT NULL default '0' ,
	"group_id" integer NOT NULL default '0',
	"workflow_version" integer NOT NULL default '0',
	"group_name" character varying,
    PRIMARY KEY  (workflow_id,group_id,workflow_version)
);

INSERT INTO ezworkflow_group_link (workflow_id, group_id, workflow_version, group_name) VALUES (1,1,0,'Standard');


--
-- TOC Entry ID 10 (OID 360414)
--
-- Name: ezworkflow_process_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_process_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;


--
-- TOC Entry ID 126 (OID 365926)
--
-- Name: ezworkflow_process Type: TABLE Owner: sp
--

CREATE TABLE "ezworkflow_process" (
	"id" integer DEFAULT nextval('ezworkflow_process_s'::text) NOT NULL,
    "process_key" char(32) NOT NULL,
	"workflow_id" integer NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"content_id" integer DEFAULT '0' NOT NULL,
	"content_version" integer DEFAULT '0' NOT NULL,
	"node_id" integer DEFAULT '0' NOT NULL,
    "session_key"  varchar(32) DEFAULT '0',
	"event_id" integer NOT NULL,
	"event_position" integer NOT NULL,
	"last_event_id" integer NOT NULL,
	"last_event_position" integer NOT NULL,
	"last_event_status" integer NOT NULL,
	"event_status" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	"activation_date" integer,
	"event_state" integer DEFAULT 0,
	"status" integer,
    "parameters" text,
    "memento_key" char(32),
	Constraint "ezworkflow_process_pkey" Primary Key ("id")
);

CREATE SEQUENCE "ezoperation_memento_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

CREATE TABLE ezoperation_memento (
    id integer DEFAULT nextval('ezoperation_memento_s'::text) NOT NULL,
    main int NOT NULL default 0,
    memento_key char(32) NOT NULL,
    main_key char(32) NOT NULL,
    memento_data text NOT NULL,
    PRIMARY KEY(id, memento_key)
);


CREATE SEQUENCE "ezdiscountsubrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;
CREATE TABLE ezdiscountsubrule (
  id integer DEFAULT nextval('ezdiscountsubrule_s'::text) NOT NULL,
  name varchar(255) NOT NULL default '',
  discountrule_id integer NOT NULL default '0',
  discount_percent float default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
);


CREATE TABLE ezdiscountsubrule_value (
  discountsubrule_id integer NOT NULL default '0',
  value integer NOT NULL default '0',
  issection int NOT NULL default '0',
  PRIMARY KEY  (discountsubrule_id,value,issection)
);

CREATE SEQUENCE "ezinformationcollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

CREATE TABLE ezinformationcollection (
  id integer DEFAULT nextval('ezinformationcollection_s'::text) NOT NULL ,
  contentobject_id integer NOT NULL default '0',
  created integer NOT NULL default '0',
  PRIMARY KEY  (id)
);

CREATE SEQUENCE "ezinformationcollection_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

create table ezinformationcollection_attribute (
  id integer DEFAULT nextval('ezinformationcollection_attribute_s'::text) NOT NULL,
  informationcollection_id integer not null default 0,
  data_text text,
  data_int integer default NULL,
  data_float float default NULL,
  PRIMARY KEY  (id)
);


CREATE SEQUENCE "ezuser_discountrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

CREATE TABLE ezuser_discountrule (
  id integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
  discountrule_id integer default NULL,
  contentobject_id integer default NULL,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
);

CREATE SEQUENCE "ezvattype_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

CREATE TABLE ezvattype (
  id integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
  name varchar(255) NOT NULL default '',
  percentage float default NULL,
  PRIMARY KEY  (id)
);



CREATE SEQUENCE "eznotification_rule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

CREATE TABLE eznotification_rule (
  id integer DEFAULT nextval('eznotification_rule_s'::text) NOT NULL,
  type varchar(250) NOT NULL default '',
  contentclass_name varchar(250) NOT NULL default '',
  path varchar(250) default NULL,
  keyword varchar(250) default NULL,
  has_constraint smallint NOT NULL default '0',
  PRIMARY KEY  (id)
);


CREATE TABLE eznotification_user_link (
  rule_id integer NOT NULL default '0',
  user_id integer NOT NULL default '0',
  send_method varchar(50) NOT NULL default '',
  send_weekday varchar(50) NOT NULL default '',
  send_time varchar(50) NOT NULL default '',
  destination_address varchar(50) NOT NULL default '',
  PRIMARY KEY  (rule_id,user_id)
);




--
-- TOC Entry ID 131 (OID 360689)
--
-- Name: "ezcontentclass_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentclass_id ON ezcontentclass USING btree (id);

--
-- TOC Entry ID 132 (OID 360690)
--
-- Name: "ezcontentobject_id" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX ezcontentobject_id ON ezcontentobject USING btree (id);

--
-- TOC Entry ID 133 (OID 360691)
--
-- Name: "ezcontentobject_parent_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_parent_id ON ezcontentobject USING btree (parent_id);

--
-- TOC Entry ID 134 (OID 360692)
--
-- Name: "ezsearch_object_word_link_word" Type: INDEX Owner: sp
--

CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link USING btree (word_id);

--
-- TOC Entry ID 135 (OID 360693)
--
-- Name: "ezsearch_object_word_link_freq" Type: INDEX Owner: sp
--

CREATE INDEX ezsearch_object_word_link_freq ON ezsearch_object_word_link USING btree (frequency);

--
-- TOC Entry ID 136 (OID 360694)
--
-- Name: "ezsearch_word_i" Type: INDEX Owner: sp
--

CREATE INDEX ezsearch_word_i ON ezsearch_word USING btree (word);

--
-- TOC Entry ID 137 (OID 360695)
--
-- Name: "ezcontentobject_tree_path" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_path ON ezcontentobject_tree USING btree (path_string);

--
-- TOC Entry ID 138 (OID 360696)
--
-- Name: "ezcontentobject_tree_p_node_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_p_node_id ON ezcontentobject_tree USING btree (parent_node_id);

--
-- TOC Entry ID 139 (OID 360697)
--
-- Name: "ezcontentobject_tree_co_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_co_id ON ezcontentobject_tree USING btree (contentobject_id);

--
-- TOC Entry ID 140 (OID 360698)
--
-- Name: "ezcontentobject_tree_depth" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_depth ON ezcontentobject_tree USING btree (depth);

--
-- TOC Entry ID 141 (OID 365642)
--
-- Name: "eztrigger_id" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX eztrigger_id ON eztrigger USING btree (id);

--
-- TOC Entry ID 142 (OID 365675)
--
-- Name: "eztrigger_fetch" Type: INDEX Owner: sp
--

CREATE INDEX eztrigger_fetch ON eztrigger USING btree (module_name, function_name, connect_type);

--
-- TOC Entry ID 143 (OID 382080)
--
-- Name: "ezmodule_run_workflow_process_id_s" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s ON ezmodule_run USING btree (workflow_process_id);

--
-- TOC Entry ID 3 (OID 360391)
--
-- Name: ezworkflow_group_s Type: SEQUENCE SET Owner: sp
--


create index ezcontentobject_tree_crc32_path on ezcontentobject_tree(crc32_path);
create unique index ezuser_contentobject_id on ezuser(contentobject_id);
create index ezuser_role_contentobject_id on ezuser_role(contentobject_id);
create index ezcontentobject_attribute_contentobject_id on ezcontentobject_attribute(contentobject_id);
create index ezcontentobject_attribute_language_code on  ezcontentobject_attribute(language_code);
create index ezcontentclass_version on ezcontentclass(version);
create index ezenumvalue_co_cl_attr_id_co_class_att_ver on ezenumvalue(contentclass_attribute_id,contentclass_attribute_version);
create index ezenumobjectvalue_co_attr_id_co_attr_ver on ezenumobjectvalue(contentobject_attribute_id,contentobject_attribute_version);

