--
-- TOC Entry ID 2 (OID 650399)
--
-- Name: ezapprovetasks_s Type: SEQUENCE
--

CREATE SEQUENCE "ezapprovetasks_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 4 (OID 650404)
--
-- Name: ezbasket_s Type: SEQUENCE
--

CREATE SEQUENCE "ezbasket_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 106 (OID 650406)
--
-- Name: ezbasket Type: TABLE
--

CREATE TABLE "ezbasket" (
	"id" integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
	"session_id" character varying(255) NOT NULL,
	"productcollection_id" integer NOT NULL,
	Constraint "ezbasket_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 107 (OID 650409)
--
-- Name: ezbinaryfile Type: TABLE
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
-- TOC Entry ID 6 (OID 650412)
--
-- Name: ezcontentclass_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentclass_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 108 (OID 650414)
--
-- Name: ezcontentclass Type: TABLE
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

--
-- TOC Entry ID 8 (OID 650422)
--
-- Name: ezcontentclass_attribute_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentclass_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 109 (OID 650424)
--
-- Name: ezcontentclass_attribute Type: TABLE
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
	"is_information_collector" integer DEFAULT '0' NOT NULL,
	Constraint "ezcontentclass_attribute_pkey" Primary Key ("id", "version")
);

--
-- TOC Entry ID 110 (OID 650442)
--
-- Name: ezcontentclass_classgroup Type: TABLE
--

CREATE TABLE "ezcontentclass_classgroup" (
	"contentclass_id" integer NOT NULL,
	"contentclass_version" integer NOT NULL,
	"group_id" integer NOT NULL,
	"group_name" character varying(255),
	Constraint "ezcontentclass_classgroup_pkey" Primary Key ("contentclass_id", "contentclass_version", "group_id")
);

--
-- TOC Entry ID 10 (OID 650450)
--
-- Name: ezcontentclassgroup_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentclassgroup_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 111 (OID 650452)
--
-- Name: ezcontentclassgroup Type: TABLE
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

--
-- TOC Entry ID 12 (OID 650458)
--
-- Name: ezcontentobject_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentobject_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 112 (OID 650460)
--
-- Name: ezcontentobject Type: TABLE
--

CREATE TABLE "ezcontentobject" (
	"id" integer DEFAULT nextval('ezcontentobject_s'::text) NOT NULL,
	"owner_id" integer DEFAULT '0' NOT NULL,
	"section_id" integer DEFAULT '0' NOT NULL,
	"contentclass_id" integer NOT NULL,
	"name" character varying(255),
	"current_version" integer,
	"is_published" integer,
	"published" integer,
	"modified" integer,
	"status" smallint DEFAULT 0,
	Constraint "ezcontentobject_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 14 (OID 650470)
--
-- Name: ezcontentobject_attribute_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentobject_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 113 (OID 650472)
--
-- Name: ezcontentobject_attribute Type: TABLE
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

--
-- TOC Entry ID 16 (OID 650494)
--
-- Name: ezcontentobject_link_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentobject_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 114 (OID 650496)
--
-- Name: ezcontentobject_link Type: TABLE
--

CREATE TABLE "ezcontentobject_link" (
	"id" integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
	"from_contentobject_id" integer NOT NULL,
	"from_contentobject_version" integer NOT NULL,
	"to_contentobject_id" integer,
	Constraint "ezcontentobject_link_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 18 (OID 650499)
--
-- Name: ezcontentobject_tree_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentobject_tree_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 115 (OID 650501)
--
-- Name: ezcontentobject_tree Type: TABLE
--

CREATE TABLE "ezcontentobject_tree" (
	"node_id" integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
	"main_node_id" integer,
	"parent_node_id" integer NOT NULL,
	"contentobject_id" integer,
	"contentobject_version" integer,
	"contentobject_is_published" integer,
	"crc32_path" integer,
	"depth" integer NOT NULL,
	"path_string" character varying(255) NOT NULL,
	"path_identification_string" text,
	"sort_field" integer DEFAULT 1,
	"sort_order" smallint DEFAULT 1,
	"priority" integer DEFAULT 0,
	"md5_path" character varying(32),
	Constraint "ezcontentobject_tree_pkey" Primary Key ("node_id")
);

--
-- TOC Entry ID 20 (OID 650515)
--
-- Name: ezcontentobject_version_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontentobject_version_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 116 (OID 650517)
--
-- Name: ezcontentobject_version Type: TABLE
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

--
-- TOC Entry ID 117 (OID 650527)
--
-- Name: ezenumobjectvalue Type: TABLE
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
-- TOC Entry ID 22 (OID 650530)
--
-- Name: ezenumvalue_s Type: SEQUENCE
--

CREATE SEQUENCE "ezenumvalue_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 118 (OID 650532)
--
-- Name: ezenumvalue Type: TABLE
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
-- TOC Entry ID 119 (OID 650535)
--
-- Name: ezimage Type: TABLE
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
-- TOC Entry ID 120 (OID 650538)
--
-- Name: ezimagevariation Type: TABLE
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
-- TOC Entry ID 121 (OID 650541)
--
-- Name: ezmedia Type: TABLE
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
-- TOC Entry ID 24 (OID 650544)
--
-- Name: ezmodule_run_s Type: SEQUENCE
--

CREATE SEQUENCE "ezmodule_run_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 122 (OID 650546)
--
-- Name: ezmodule_run Type: TABLE
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
-- TOC Entry ID 26 (OID 650552)
--
-- Name: eznode_assignment_s Type: SEQUENCE
--

CREATE SEQUENCE "eznode_assignment_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 123 (OID 650554)
--
-- Name: eznode_assignment Type: TABLE
--

CREATE TABLE "eznode_assignment" (
	"id" integer DEFAULT nextval('eznode_assignment_s'::text) NOT NULL,
	"contentobject_id" integer,
	"contentobject_version" integer,
	"parent_node" integer,
	"is_main" integer,
	"sort_field" integer DEFAULT 1,
	"sort_order" smallint DEFAULT 1,
	"from_node_id" integer DEFAULT '0',
	"remote_id" integer,
	Constraint "eznode_assignment_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 28 (OID 650565)
--
-- Name: ezorder_s Type: SEQUENCE
--

CREATE SEQUENCE "ezorder_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 124 (OID 650567)
--
-- Name: ezorder Type: TABLE
--

CREATE TABLE "ezorder" (
	"id" integer DEFAULT nextval('ezorder_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"productcollection_id" integer NOT NULL,
	"created" integer NOT NULL,
	"is_temporary" integer DEFAULT '1' NOT NULL,
	"order_nr" integer DEFAULT '0' NOT NULL,
	Constraint "ezorder_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 30 (OID 650570)
--
-- Name: ezpolicy_s Type: SEQUENCE
--

CREATE SEQUENCE "ezpolicy_s" start 315 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 125 (OID 650572)
--
-- Name: ezpolicy Type: TABLE
--

CREATE TABLE "ezpolicy" (
	"id" integer DEFAULT nextval('ezpolicy_s'::text) NOT NULL,
	"role_id" integer,
	"function_name" character varying,
	"module_name" character varying,
	"limitation" character(1),
	Constraint "ezpolicy_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 32 (OID 650581)
--
-- Name: ezpolicy_limitation_s Type: SEQUENCE
--

CREATE SEQUENCE "ezpolicy_limitation_s" start 245 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 126 (OID 650583)
--
-- Name: ezpolicy_limitation Type: TABLE
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

--
-- TOC Entry ID 34 (OID 650590)
--
-- Name: ezpolicy_limitation_value_s Type: SEQUENCE
--

CREATE SEQUENCE "ezpolicy_limitation_value_s" start 408 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 127 (OID 650592)
--
-- Name: ezpolicy_limitation_value Type: TABLE
--

CREATE TABLE "ezpolicy_limitation_value" (
	"id" integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
	"limitation_id" integer,
	"value" integer,
	Constraint "ezpolicy_limitation_value_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 36 (OID 650597)
--
-- Name: ezproductcollection_s Type: SEQUENCE
--

CREATE SEQUENCE "ezproductcollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 128 (OID 650599)
--
-- Name: ezproductcollection Type: TABLE
--

CREATE TABLE "ezproductcollection" (
	"id" integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
	Constraint "ezproductcollection_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 38 (OID 650602)
--
-- Name: ezproductcollection_item_s Type: SEQUENCE
--

CREATE SEQUENCE "ezproductcollection_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 129 (OID 650604)
--
-- Name: ezproductcollection_item Type: TABLE
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
-- TOC Entry ID 40 (OID 650607)
--
-- Name: ezrole_s Type: SEQUENCE
--

CREATE SEQUENCE "ezrole_s" start 4 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 130 (OID 650609)
--
-- Name: ezrole Type: TABLE
--

CREATE TABLE "ezrole" (
	"id" integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
	"version" integer DEFAULT '0',
	"name" character varying NOT NULL,
	"value" character(1),
	Constraint "ezrole_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 42 (OID 650618)
--
-- Name: ezsearch_object_word_link_s Type: SEQUENCE
--

CREATE SEQUENCE "ezsearch_object_word_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 131 (OID 650620)
--
-- Name: ezsearch_object_word_link Type: TABLE
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
	"published" integer DEFAULT '0' NOT NULL,
	"section_id" integer DEFAULT '0' NOT NULL,
	Constraint "ezsearch_object_word_link_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 44 (OID 650623)
--
-- Name: ezsearch_return_count_s Type: SEQUENCE
--

CREATE SEQUENCE "ezsearch_return_count_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 132 (OID 650625)
--
-- Name: ezsearch_return_count Type: TABLE
--

CREATE TABLE "ezsearch_return_count" (
	"id" integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
	"phrase_id" integer NOT NULL,
	"time" integer NOT NULL,
	"count" integer NOT NULL,
	Constraint "ezsearch_return_count_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 46 (OID 650628)
--
-- Name: ezsearch_search_phrase_s Type: SEQUENCE
--

CREATE SEQUENCE "ezsearch_search_phrase_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 133 (OID 650630)
--
-- Name: ezsearch_search_phrase Type: TABLE
--

CREATE TABLE "ezsearch_search_phrase" (
	"id" integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
	"phrase" character varying(250),
	Constraint "ezsearch_search_phrase_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 48 (OID 650633)
--
-- Name: ezsearch_word_s Type: SEQUENCE
--

CREATE SEQUENCE "ezsearch_word_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 134 (OID 650635)
--
-- Name: ezsearch_word Type: TABLE
--

CREATE TABLE "ezsearch_word" (
	"id" integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
	"word" character varying(150),
	"object_count" integer NOT NULL,
	Constraint "ezsearch_word_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 50 (OID 650638)
--
-- Name: ezsection_s Type: SEQUENCE
--

CREATE SEQUENCE "ezsection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 135 (OID 650640)
--
-- Name: ezsection Type: TABLE
--

CREATE TABLE "ezsection" (
	"id" integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
	"name" character varying(255),
	"locale" character varying(255),
	Constraint "ezsection_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 136 (OID 650644)
--
-- Name: ezsession Type: TABLE
--

CREATE TABLE "ezsession" (
	"session_key" character(32) NOT NULL,
	"expiration_time" integer NOT NULL,
	"data" text NOT NULL,
	"cache_mask_1" integer,
	Constraint "ezsession_pkey" Primary Key ("session_key")
);

--
-- TOC Entry ID 52 (OID 650650)
--
-- Name: eztask_s Type: SEQUENCE
--

CREATE SEQUENCE "eztask_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 137 (OID 650652)
--
-- Name: eztask Type: TABLE
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
-- TOC Entry ID 54 (OID 650655)
--
-- Name: eztask_message_s Type: SEQUENCE
--

CREATE SEQUENCE "eztask_message_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 138 (OID 650657)
--
-- Name: eztask_message Type: TABLE
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
-- TOC Entry ID 56 (OID 650660)
--
-- Name: eztrigger_s Type: SEQUENCE
--

CREATE SEQUENCE "eztrigger_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 139 (OID 650662)
--
-- Name: eztrigger Type: TABLE
--

CREATE TABLE "eztrigger" (
	"id" integer DEFAULT nextval('eztrigger_s'::text),
	"name" character varying(255),
	"module_name" character varying(255) NOT NULL,
	"function_name" character varying(255) NOT NULL,
	"connect_type" character(1) NOT NULL,
	"workflow_id" integer
);

--
-- TOC Entry ID 140 (OID 650664)
--
-- Name: ezuser Type: TABLE
--

CREATE TABLE "ezuser" (
	"contentobject_id" integer NOT NULL,
	"login" character varying(150) NOT NULL,
	"email" character varying(150) NOT NULL,
	"password_hash_type" integer DEFAULT 1 NOT NULL,
	"password_hash" character varying(50)
);

--
-- TOC Entry ID 58 (OID 650668)
--
-- Name: ezuser_role_s Type: SEQUENCE
--

CREATE SEQUENCE "ezuser_role_s" start 26 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 141 (OID 650670)
--
-- Name: ezuser_role Type: TABLE
--

CREATE TABLE "ezuser_role" (
	"id" integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
	"role_id" integer,
	"contentobject_id" integer,
	Constraint "ezuser_role_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 142 (OID 650675)
--
-- Name: ezuser_setting Type: TABLE
--

CREATE TABLE "ezuser_setting" (
	"user_id" integer DEFAULT '0' NOT NULL,
	"is_enabled" smallint DEFAULT '0' NOT NULL,
	"max_login" integer,
	Constraint "ezuser_setting_pkey" Primary Key ("user_id")
);

--
-- TOC Entry ID 60 (OID 650680)
--
-- Name: ezwishlist_s Type: SEQUENCE
--

CREATE SEQUENCE "ezwishlist_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 143 (OID 650682)
--
-- Name: ezwishlist Type: TABLE
--

CREATE TABLE "ezwishlist" (
	"id" integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"productcollection_id" integer NOT NULL,
	Constraint "ezwishlist_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 62 (OID 650685)
--
-- Name: ezworkflow_s Type: SEQUENCE
--

CREATE SEQUENCE "ezworkflow_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 144 (OID 650687)
--
-- Name: ezworkflow Type: TABLE
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

--
-- TOC Entry ID 64 (OID 650691)
--
-- Name: ezworkflow_assign_s Type: SEQUENCE
--

CREATE SEQUENCE "ezworkflow_assign_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 145 (OID 650693)
--
-- Name: ezworkflow_assign Type: TABLE
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
-- TOC Entry ID 66 (OID 650696)
--
-- Name: ezworkflow_event_s Type: SEQUENCE
--

CREATE SEQUENCE "ezworkflow_event_s" start 3 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 146 (OID 650698)
--
-- Name: ezworkflow_event Type: TABLE
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

--
-- TOC Entry ID 68 (OID 650703)
--
-- Name: ezworkflow_group_s Type: SEQUENCE
--

CREATE SEQUENCE "ezworkflow_group_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 147 (OID 650705)
--
-- Name: ezworkflow_group Type: TABLE
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

--
-- TOC Entry ID 148 (OID 650709)
--
-- Name: ezworkflow_group_link Type: TABLE
--

CREATE TABLE "ezworkflow_group_link" (
	"workflow_id" integer DEFAULT '0' NOT NULL,
	"group_id" integer DEFAULT '0' NOT NULL,
	"workflow_version" integer DEFAULT '0' NOT NULL,
	"group_name" character varying,
	Constraint "ezworkflow_group_link_pkey" Primary Key ("workflow_id", "group_id", "workflow_version")
);

--
-- TOC Entry ID 70 (OID 650716)
--
-- Name: ezworkflow_process_s Type: SEQUENCE
--

CREATE SEQUENCE "ezworkflow_process_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 149 (OID 650718)
--
-- Name: ezworkflow_process Type: TABLE
--

CREATE TABLE "ezworkflow_process" (
	"id" integer DEFAULT nextval('ezworkflow_process_s'::text) NOT NULL,
	"process_key" character(32) NOT NULL,
	"workflow_id" integer NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"content_id" integer DEFAULT '0' NOT NULL,
	"content_version" integer DEFAULT '0' NOT NULL,
	"node_id" integer DEFAULT '0' NOT NULL,
	"session_key" character varying(32) DEFAULT '0',
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
	"memento_key" character(32),
	Constraint "ezworkflow_process_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 72 (OID 650724)
--
-- Name: ezoperation_memento_s Type: SEQUENCE
--

CREATE SEQUENCE "ezoperation_memento_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 150 (OID 650726)
--
-- Name: ezoperation_memento Type: TABLE
--

CREATE TABLE "ezoperation_memento" (
	"id" integer DEFAULT nextval('ezoperation_memento_s'::text) NOT NULL,
	"main" integer DEFAULT 0 NOT NULL,
	"memento_key" character(32) NOT NULL,
	"main_key" character(32) NOT NULL,
	"memento_data" text NOT NULL,
	Constraint "ezoperation_memento_pkey" Primary Key ("id", "memento_key")
);

--
-- TOC Entry ID 74 (OID 650732)
--
-- Name: ezdiscountsubrule_s Type: SEQUENCE
--

CREATE SEQUENCE "ezdiscountsubrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 151 (OID 650734)
--
-- Name: ezdiscountsubrule Type: TABLE
--

CREATE TABLE "ezdiscountsubrule" (
	"id" integer DEFAULT nextval('ezdiscountsubrule_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"discountrule_id" integer DEFAULT '0' NOT NULL,
	"discount_percent" double precision,
	"limitation" character(1),
	Constraint "ezdiscountsubrule_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 152 (OID 650737)
--
-- Name: ezdiscountsubrule_value Type: TABLE
--

CREATE TABLE "ezdiscountsubrule_value" (
	"discountsubrule_id" integer DEFAULT '0' NOT NULL,
	"value" integer DEFAULT '0' NOT NULL,
	"issection" integer DEFAULT '0' NOT NULL,
	Constraint "ezdiscountsubrule_value_pkey" Primary Key ("discountsubrule_id", "value", "issection")
);

--
-- TOC Entry ID 76 (OID 650740)
--
-- Name: ezinformationcollection_s Type: SEQUENCE
--

CREATE SEQUENCE "ezinfocollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 153 (OID 650742)
--
-- Name: ezinfocollection Type: TABLE
--

CREATE TABLE "ezinfocollection" (
	"id" integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
	"contentobject_id" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	Constraint "ezinfocollection_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 78 (OID 650745)
--
-- Name: ezinfocollection_attribute_s Type: SEQUENCE
--

CREATE SEQUENCE "ezinfocollection_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 154 (OID 650747)
--
-- Name: ezinfocollection_attribute Type: TABLE
--

CREATE TABLE "ezinfocollection_attribute" (
	"id" integer DEFAULT nextval('ezinfocollection_attribute_s'::text) NOT NULL,
	"informationcollection_id" integer DEFAULT 0 NOT NULL,
	"data_text" text,
	"data_int" integer,
	"data_float" double precision,
	"contentclass_attribute_id" integer,
	Constraint "ezinfocollection_attribute_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 80 (OID 650753)
--
-- Name: ezuser_discountrule_s Type: SEQUENCE
--

CREATE SEQUENCE "ezuser_discountrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 155 (OID 650755)
--
-- Name: ezuser_discountrule Type: TABLE
--

CREATE TABLE "ezuser_discountrule" (
	"id" integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
	"discountrule_id" integer,
	"contentobject_id" integer,
	"name" character varying(255) DEFAULT '' NOT NULL,
	Constraint "ezuser_discountrule_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 82 (OID 650758)
--
-- Name: ezvattype_s Type: SEQUENCE
--

CREATE SEQUENCE "ezvattype_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 156 (OID 650760)
--
-- Name: ezvattype Type: TABLE
--

CREATE TABLE "ezvattype" (
	"id" integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"percentage" double precision,
	Constraint "ezvattype_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 84 (OID 650763)
--
-- Name: eznotification_rule_s Type: SEQUENCE
--

CREATE SEQUENCE "eznotification_rule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 157 (OID 650765)
--
-- Name: eznotification_rule Type: TABLE
--

CREATE TABLE "eznotification_rule" (
	"id" integer DEFAULT nextval('eznotification_rule_s'::text) NOT NULL,
	"type" character varying(250) DEFAULT '' NOT NULL,
	"contentclass_name" character varying(250) DEFAULT '' NOT NULL,
	"path" character varying(250),
	"keyword" character varying(250),
	"has_constraint" smallint DEFAULT '0' NOT NULL,
	Constraint "eznotification_rule_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 158 (OID 650768)
--
-- Name: eznotification_user_link Type: TABLE
--

CREATE TABLE "eznotification_user_link" (
	"rule_id" integer DEFAULT '0' NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"send_method" character varying(50) DEFAULT '' NOT NULL,
	"send_weekday" character varying(50) DEFAULT '' NOT NULL,
	"send_time" character varying(50) DEFAULT '' NOT NULL,
	"destination_address" character varying(50) DEFAULT '' NOT NULL,
	Constraint "eznotification_user_link_pkey" Primary Key ("rule_id", "user_id")
);

--
-- TOC Entry ID 86 (OID 650771)
--
-- Name: ezdiscountrule_s Type: SEQUENCE
--

CREATE SEQUENCE "ezdiscountrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 159 (OID 650773)
--
-- Name: ezdiscountrule Type: TABLE
--

CREATE TABLE "ezdiscountrule" (
	"id" integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
	"name" character varying(255) NOT NULL,
	Constraint "ezdiscountrule_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 88 (OID 650776)
--
-- Name: ezorder_item_s Type: SEQUENCE
--

CREATE SEQUENCE "ezorder_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 160 (OID 650778)
--
-- Name: ezorder_item Type: TABLE
--

CREATE TABLE "ezorder_item" (
	"id" integer DEFAULT nextval('ezorder_item_s'::text) NOT NULL,
	"order_id" integer NOT NULL,
	"description" character varying(255),
	"price" double precision,
	"vat_is_included" integer,
	"vat_type_id" integer,
	Constraint "ezorder_item_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 161 (OID 650801)
--
-- Name: ezcontentobject_name Type: TABLE
--

CREATE TABLE "ezcontentobject_name" (
	"contentobject_id" integer NOT NULL,
	"name" character varying(255),
	"content_version" integer NOT NULL,
	"content_translation" character varying(20) NOT NULL,
	"real_translation" character varying(20),
	Constraint "ezcontentobject_name_pkey" Primary Key ("contentobject_id", "content_version", "content_translation")
);

--
-- TOC Entry ID 90 (OID 650818)
--
-- Name: ezwaituntildatevalue_s Type: SEQUENCE
--

CREATE SEQUENCE "ezwaituntildatevalue_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 162 (OID 650820)
--
-- Name: ezwaituntildatevalue Type: TABLE
--

CREATE TABLE "ezwaituntildatevalue" (
	"id" integer DEFAULT nextval('ezwaituntildatevalue_s'::text) NOT NULL,
	"workflow_event_id" integer DEFAULT '0' NOT NULL,
	"workflow_event_version" integer DEFAULT '0' NOT NULL,
	"contentclass_id" integer DEFAULT '0' NOT NULL,
	"contentclass_attribute_id" integer DEFAULT '0' NOT NULL,
	Constraint "ezwaituntildatevalue_pkey" Primary Key ("id", "workflow_event_id", "workflow_event_version")
);

--
-- TOC Entry ID 92 (OID 650824)
--
-- Name: ezcontent_translation_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcontent_translation_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 163 (OID 650826)
--
-- Name: ezcontent_translation Type: TABLE
--

CREATE TABLE "ezcontent_translation" (
	"id" integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"locale" character varying(255) NOT NULL,
	Constraint "ezcontent_translation_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 94 (OID 650829)
--
-- Name: ezcollab_item_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcollab_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 164 (OID 650831)
--
-- Name: ezcollab_item Type: TABLE
--

CREATE TABLE "ezcollab_item" (
	"id" integer DEFAULT nextval('ezcollab_item_s'::text) NOT NULL,
	"type_identifier" character varying(40) DEFAULT '' NOT NULL,
	"creator_id" integer DEFAULT '0' NOT NULL,
	"status" integer DEFAULT '1' NOT NULL,
	"data_text1" text DEFAULT '' NOT NULL,
	"data_text2" text DEFAULT '' NOT NULL,
	"data_text3" text DEFAULT '' NOT NULL,
	"data_int1" integer DEFAULT '0' NOT NULL,
	"data_int2" integer DEFAULT '0' NOT NULL,
	"data_int3" integer DEFAULT '0' NOT NULL,
	"data_float1" double precision DEFAULT '0' NOT NULL,
	"data_float2" double precision DEFAULT '0' NOT NULL,
	"data_float3" double precision DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_item_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 96 (OID 650837)
--
-- Name: ezcollab_group_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcollab_group_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 165 (OID 650839)
--
-- Name: ezcollab_group Type: TABLE
--

CREATE TABLE "ezcollab_group" (
	"id" integer DEFAULT nextval('ezcollab_group_s'::text) NOT NULL,
	"parent_group_id" integer DEFAULT '0' NOT NULL,
	"depth" integer DEFAULT '0' NOT NULL,
	"path_string" character varying(255) DEFAULT '' NOT NULL,
	"is_open" integer DEFAULT '1' NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"title" character varying(255) DEFAULT '' NOT NULL,
	"priority" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_group_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 166 (OID 650844)
--
-- Name: ezcollab_item_group_link Type: TABLE
--

CREATE TABLE "ezcollab_item_group_link" (
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	"group_id" integer DEFAULT '0' NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"is_read" integer DEFAULT '0' NOT NULL,
	"is_active" integer DEFAULT '1' NOT NULL,
	"last_read" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_item_group_link_pkey" Primary Key ("collaboration_id", "group_id", "user_id")
);

--
-- TOC Entry ID 167 (OID 650847)
--
-- Name: ezcollab_item_status Type: TABLE
--

CREATE TABLE "ezcollab_item_status" (
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"is_read" integer DEFAULT '0' NOT NULL,
	"is_active" integer DEFAULT '1' NOT NULL,
	"last_read" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_item_status_pkey" Primary Key ("collaboration_id", "user_id")
);

--
-- TOC Entry ID 168 (OID 650850)
--
-- Name: ezcollab_item_participant_link Type: TABLE
--

CREATE TABLE "ezcollab_item_participant_link" (
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	"participant_id" integer DEFAULT '0' NOT NULL,
	"participant_type" integer DEFAULT '1' NOT NULL,
	"participant_role" integer DEFAULT '1' NOT NULL,
	"is_read" integer DEFAULT '0' NOT NULL,
	"is_active" integer DEFAULT '1' NOT NULL,
	"last_read" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_item_participant_link_pkey" Primary Key ("collaboration_id", "participant_id")
);

--
-- TOC Entry ID 98 (OID 650853)
--
-- Name: ezcollab_item_message_link_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcollab_item_message_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 169 (OID 650855)
--
-- Name: ezcollab_item_message_link Type: TABLE
--

CREATE TABLE "ezcollab_item_message_link" (
	"id" integer DEFAULT nextval('ezcollab_item_message_link_s'::text) NOT NULL,
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	"participant_id" integer DEFAULT '0' NOT NULL,
	"message_id" integer DEFAULT '0' NOT NULL,
	"message_type" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_item_message_link_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 100 (OID 650858)
--
-- Name: ezcollab_simple_message_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcollab_simple_message_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 170 (OID 650860)
--
-- Name: ezcollab_simple_message Type: TABLE
--

CREATE TABLE "ezcollab_simple_message" (
	"id" integer DEFAULT nextval('ezcollab_simple_message_s'::text) NOT NULL,
	"message_type" character varying(40) DEFAULT '' NOT NULL,
	"creator_id" integer DEFAULT '0' NOT NULL,
	"data_text1" text DEFAULT '' NOT NULL,
	"data_text2" text DEFAULT '' NOT NULL,
	"data_text3" text DEFAULT '' NOT NULL,
	"data_int1" integer DEFAULT '0' NOT NULL,
	"data_int2" integer DEFAULT '0' NOT NULL,
	"data_int3" integer DEFAULT '0' NOT NULL,
	"data_float1" double precision DEFAULT '0' NOT NULL,
	"data_float2" double precision DEFAULT '0' NOT NULL,
	"data_float3" double precision DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_simple_message_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 102 (OID 650866)
--
-- Name: ezcollab_profile_s Type: SEQUENCE
--

CREATE SEQUENCE "ezcollab_profile_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 171 (OID 650868)
--
-- Name: ezcollab_profile Type: TABLE
--

CREATE TABLE "ezcollab_profile" (
	"id" integer DEFAULT nextval('ezcollab_profile_s'::text) NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"main_group" integer DEFAULT '0' NOT NULL,
	"data_text1" text DEFAULT '' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_profile_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 104 (OID 650874)
--
-- Name: ezapprove_items_s Type: SEQUENCE
--

CREATE SEQUENCE "ezapprove_items_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 172 (OID 650876)
--
-- Name: ezapprove_items Type: TABLE
--

CREATE TABLE "ezapprove_items" (
	"id" integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
	"workflow_process_id" integer DEFAULT '0' NOT NULL,
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	Constraint "ezapprove_items_pkey" Primary Key ("id")
);

--
-- Data for TOC Entry ID 196 (OID 650406)
--
-- Name: ezbasket Type: TABLE DATA
--


--
-- Data for TOC Entry ID 197 (OID 650409)
--
-- Name: ezbinaryfile Type: TABLE DATA
--


--
-- Data for TOC Entry ID 198 (OID 650414)
--
-- Name: ezcontentclass Type: TABLE DATA
--


INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1033922265);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1033922035);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1033922064);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1033922083);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (5,0,'Image','','<name>',8,14,1031484992,1033921948);
--
-- Data for TOC Entry ID 199 (OID 650424)
--
-- Name: ezcontentclass_attribute Type: TABLE DATA
--


INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (119,0,1,'description','Description','ezxmltext',2,1,0,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (121,0,2,'body','Body','ezxmltext',3,1,0,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (120,0,2,'intro','Intro','ezxmltext',2,1,1,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (1,0,2,'title','Title','ezstring',1,0,1,255,0,0,0,0,0,0,0,'New article','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',4,1,0,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',5,1,0,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (7,0,3,'description','Description','ezstring',2,1,0,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (9,0,4,'last_name','Last name','ezstring',2,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (12,0,4,'user_account','User account','ezuser',3,1,1,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (118,0,5,'image','Image','ezimage',3,0,0,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (117,0,5,'caption','Caption','ezxmltext',2,0,0,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (116,0,5,'name','Name','ezstring',1,0,0,150,0,0,0,0,0,0,0,'','','','',0);
--
-- Data for TOC Entry ID 200 (OID 650442)
--
-- Name: ezcontentclass_classgroup Type: TABLE DATA
--


INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (1,0,1,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (2,0,1,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (4,0,2,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (5,0,3,'Media');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (3,0,2,'');
--
-- Data for TOC Entry ID 201 (OID 650452)
--
-- Name: ezcontentclassgroup Type: TABLE DATA
--


INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (3,'Media',8,14,1032009743,1033922120);
--
-- Data for TOC Entry ID 202 (OID 650460)
--
-- Name: ezcontentobject Type: TABLE DATA
--


INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status") VALUES (1,0,1,1,'Frontpage',1,0,1033917596,1033917596,1);
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status") VALUES (4,0,0,3,'Users',1,0,0,0,1);
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status") VALUES (10,8,0,4,'Anonymous User',1,0,1033920665,1033920665,1);
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status") VALUES (11,8,0,3,'Guest accounts',1,0,1033920746,1033920746,1);
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status") VALUES (12,8,0,3,'Administrator users',1,0,1033920775,1033920775,1);
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status") VALUES (13,8,0,3,'Editors',1,0,1033920794,1033920794,1);
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status") VALUES (14,8,0,4,'Administrator User',1,0,1033920830,1033920830,1);
--
-- Data for TOC Entry ID 203 (OID 650472)
--
-- Name: ezcontentobject_attribute Type: TABLE DATA
--


INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (1,'eng-GB',1,1,4,'Frontpage',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (2,'eng-GB',1,1,119,'This folder contains some information about...',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (7,'eng-GB',1,4,5,'Main group',NULL,NULL);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (8,'eng-GB',1,4,6,'Users',NULL,NULL);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (21,'eng-GB',1,10,12,'',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (22,'eng-GB',1,11,6,'Guest accounts',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (19,'eng-GB',1,10,8,'Anonymous',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (20,'eng-GB',1,10,9,'User',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (23,'eng-GB',1,11,7,'',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (24,'eng-GB',1,12,6,'Administrator users',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (25,'eng-GB',1,12,7,'',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (26,'eng-GB',1,13,6,'Editors',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (27,'eng-GB',1,13,7,'',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (28,'eng-GB',1,14,8,'Administrator',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (29,'eng-GB',1,14,9,'User',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (30,'eng-GB',1,14,12,'',0,0);
--
-- Data for TOC Entry ID 204 (OID 650496)
--
-- Name: ezcontentobject_link Type: TABLE DATA
--


--
-- Data for TOC Entry ID 205 (OID 650501)
--
-- Name: ezcontentobject_tree Type: TABLE DATA
--


INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (1,1,1,0,1,1,NULL,0,'/1/',NULL,1,1,0,NULL);
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (2,2,1,1,1,1,1360594808,1,'/1/2/','frontpage',1,1,0,'');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (5,5,1,4,1,NULL,NULL,1,'/1/5/',NULL,1,1,0,NULL);
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (11,11,5,10,1,1,-1609495635,2,'/1/5/11/','users/',1,1,0,'');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (12,12,5,11,1,1,-1609495635,2,'/1/5/12/','users/',1,1,0,'');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (13,13,5,12,1,1,-1609495635,2,'/1/5/13/','users/',1,1,0,'');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (14,14,5,13,1,1,-1609495635,2,'/1/5/14/','users/',1,1,0,'');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (15,15,13,14,1,1,934329528,3,'/1/5/13/15/','users/administrator_users/',1,1,0,'');
--
-- Data for TOC Entry ID 206 (OID 650517)
--
-- Name: ezcontentobject_version Type: TABLE DATA
--


INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (436,1,8,1,1033919080,1033919080,1,1,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (4,4,8,1,1033919080,1033919080,1,1,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (438,10,8,1,1033920649,1033920665,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (439,11,8,1,1033920737,1033920746,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (440,12,8,1,1033920760,1033920775,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (441,13,8,1,1033920786,1033920794,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (442,14,8,1,1033920808,1033920830,0,0,0,NULL);
--
-- Data for TOC Entry ID 207 (OID 650527)
--
-- Name: ezenumobjectvalue Type: TABLE DATA
--


--
-- Data for TOC Entry ID 208 (OID 650532)
--
-- Name: ezenumvalue Type: TABLE DATA
--


--
-- Data for TOC Entry ID 209 (OID 650535)
--
-- Name: ezimage Type: TABLE DATA
--


--
-- Data for TOC Entry ID 210 (OID 650538)
--
-- Name: ezimagevariation Type: TABLE DATA
--


--
-- Data for TOC Entry ID 211 (OID 650541)
--
-- Name: ezmedia Type: TABLE DATA
--


--
-- Data for TOC Entry ID 212 (OID 650546)
--
-- Name: ezmodule_run Type: TABLE DATA
--


--
-- Data for TOC Entry ID 213 (OID 650554)
--
-- Name: eznode_assignment Type: TABLE DATA
--


INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (2,1,1,1,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (3,4,1,1,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (148,9,1,2,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (149,10,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (150,11,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (151,12,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (152,13,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (153,14,1,13,1,1,1,0,NULL);
--
-- Data for TOC Entry ID 214 (OID 650567)
--
-- Name: ezorder Type: TABLE DATA
--


--
-- Data for TOC Entry ID 215 (OID 650572)
--
-- Name: ezpolicy Type: TABLE DATA
--


INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (306,1,'read','content',' ');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (314,3,'*','content','*');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (308,2,'*','*','*');
--
-- Data for TOC Entry ID 216 (OID 650583)
--
-- Name: ezpolicy_limitation Type: TABLE DATA
--


INSERT INTO "ezpolicy_limitation" ("id","policy_id","identifier","role_id","function_name","module_name") VALUES (244,306,'Class',0,'read','content');
--
-- Data for TOC Entry ID 217 (OID 650592)
--
-- Name: ezpolicy_limitation_value Type: TABLE DATA
--


INSERT INTO "ezpolicy_limitation_value" ("id","limitation_id","value") VALUES (407,244,1);
INSERT INTO "ezpolicy_limitation_value" ("id","limitation_id","value") VALUES (408,244,7);
--
-- Data for TOC Entry ID 218 (OID 650599)
--
-- Name: ezproductcollection Type: TABLE DATA
--


--
-- Data for TOC Entry ID 219 (OID 650604)
--
-- Name: ezproductcollection_item Type: TABLE DATA
--


--
-- Data for TOC Entry ID 220 (OID 650609)
--
-- Name: ezrole Type: TABLE DATA
--


INSERT INTO "ezrole" ("id","version","name","value") VALUES (1,0,'Anonymous',' ');
INSERT INTO "ezrole" ("id","version","name","value") VALUES (2,0,'Administrator','*');
INSERT INTO "ezrole" ("id","version","name","value") VALUES (3,0,'Editor',' ');
--
-- Data for TOC Entry ID 221 (OID 650620)
--
-- Name: ezsearch_object_word_link Type: TABLE DATA
--


--
-- Data for TOC Entry ID 222 (OID 650625)
--
-- Name: ezsearch_return_count Type: TABLE DATA
--


--
-- Data for TOC Entry ID 223 (OID 650630)
--
-- Name: ezsearch_search_phrase Type: TABLE DATA
--


--
-- Data for TOC Entry ID 224 (OID 650635)
--
-- Name: ezsearch_word Type: TABLE DATA
--


--
-- Data for TOC Entry ID 225 (OID 650640)
--
-- Name: ezsection Type: TABLE DATA
--


INSERT INTO "ezsection" ("id","name","locale") VALUES (1,'Standard section','nor-NO');
--
-- Data for TOC Entry ID 226 (OID 650644)
--
-- Name: ezsession Type: TABLE DATA
--


--
-- Data for TOC Entry ID 227 (OID 650652)
--
-- Name: eztask Type: TABLE DATA
--


--
-- Data for TOC Entry ID 228 (OID 650657)
--
-- Name: eztask_message Type: TABLE DATA
--


--
-- Data for TOC Entry ID 229 (OID 650662)
--
-- Name: eztrigger Type: TABLE DATA
--


--
-- Data for TOC Entry ID 230 (OID 650664)
--
-- Name: ezuser Type: TABLE DATA
--


INSERT INTO "ezuser" ("contentobject_id","login","email","password_hash_type","password_hash") VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO "ezuser" ("contentobject_id","login","email","password_hash_type","password_hash") VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');
--
-- Data for TOC Entry ID 231 (OID 650670)
--
-- Name: ezuser_role Type: TABLE DATA
--


INSERT INTO "ezuser_role" ("id","role_id","contentobject_id") VALUES (24,1,4);
INSERT INTO "ezuser_role" ("id","role_id","contentobject_id") VALUES (25,2,12);
--
-- Data for TOC Entry ID 232 (OID 650675)
--
-- Name: ezuser_setting Type: TABLE DATA
--


INSERT INTO "ezuser_setting" ("user_id","is_enabled","max_login") VALUES (10,1,1000);
INSERT INTO "ezuser_setting" ("user_id","is_enabled","max_login") VALUES (14,1,10);
--
-- Data for TOC Entry ID 233 (OID 650682)
--
-- Name: ezwishlist Type: TABLE DATA
--


--
-- Data for TOC Entry ID 234 (OID 650687)
--
-- Name: ezworkflow Type: TABLE DATA
--


INSERT INTO "ezworkflow" ("id","version","workflow_type_string","name","creator_id","modifier_id","created","modified","is_enabled") VALUES (1,0,'group_ezserial','Sp''s forkflow',8,24,1031927869,1032856662,1);
--
-- Data for TOC Entry ID 235 (OID 650693)
--
-- Name: ezworkflow_assign Type: TABLE DATA
--


--
-- Data for TOC Entry ID 236 (OID 650698)
--
-- Name: ezworkflow_event Type: TABLE DATA
--


INSERT INTO "ezworkflow_event" ("id","version","workflow_id","workflow_type_string","description","data_int1","data_int2","data_int3","data_int4","data_text1","data_text2","data_text3","data_text4","placement") VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO "ezworkflow_event" ("id","version","workflow_id","workflow_type_string","description","data_int1","data_int2","data_int3","data_int4","data_text1","data_text2","data_text3","data_text4","placement") VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);
--
-- Data for TOC Entry ID 237 (OID 650705)
--
-- Name: ezworkflow_group Type: TABLE DATA
--


INSERT INTO "ezworkflow_group" ("id","name","creator_id","modifier_id","created","modified") VALUES (1,'Standard',-1,-1,1024392098,1024392098);
--
-- Data for TOC Entry ID 238 (OID 650709)
--
-- Name: ezworkflow_group_link Type: TABLE DATA
--


INSERT INTO "ezworkflow_group_link" ("workflow_id","group_id","workflow_version","group_name") VALUES (1,1,0,'Standard');
--
-- Data for TOC Entry ID 239 (OID 650718)
--
-- Name: ezworkflow_process Type: TABLE DATA
--


--
-- Data for TOC Entry ID 240 (OID 650726)
--
-- Name: ezoperation_memento Type: TABLE DATA
--


--
-- Data for TOC Entry ID 241 (OID 650734)
--
-- Name: ezdiscountsubrule Type: TABLE DATA
--


--
-- Data for TOC Entry ID 242 (OID 650737)
--
-- Name: ezdiscountsubrule_value Type: TABLE DATA
--


--
-- Data for TOC Entry ID 243 (OID 650742)
--
-- Name: ezinfocollection Type: TABLE DATA
--


--
-- Data for TOC Entry ID 244 (OID 650747)
--
-- Name: ezinfocollection_attribute Type: TABLE DATA
--


--
-- Data for TOC Entry ID 245 (OID 650755)
--
-- Name: ezuser_discountrule Type: TABLE DATA
--


--
-- Data for TOC Entry ID 246 (OID 650760)
--
-- Name: ezvattype Type: TABLE DATA
--


--
-- Data for TOC Entry ID 247 (OID 650765)
--
-- Name: eznotification_rule Type: TABLE DATA
--


--
-- Data for TOC Entry ID 248 (OID 650768)
--
-- Name: eznotification_user_link Type: TABLE DATA
--


--
-- Data for TOC Entry ID 249 (OID 650773)
--
-- Name: ezdiscountrule Type: TABLE DATA
--


--
-- Data for TOC Entry ID 250 (OID 650778)
--
-- Name: ezorder_item Type: TABLE DATA
--


--
-- Data for TOC Entry ID 251 (OID 650801)
--
-- Name: ezcontentobject_name Type: TABLE DATA
--


INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (1,'Frontpage',1,'eng-GB','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (4,'Users',1,'eng-GB','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (10,'Anonymous User',1,'eng-GB','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (11,'Guest accounts',1,'eng-GB','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (12,'Administrator users',1,'eng-GB','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (13,'Editors',1,'eng-GB','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (14,'Administrator User',1,'eng-GB','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (1,'Frontpage',1,'nor-NO','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (4,'Users',1,'nor-NO','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (10,'Anonymous User',1,'nor-NO','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (11,'Guest accounts',1,'nor-NO','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (12,'Administrator users',1,'nor-NO','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (13,'Editors',1,'nor-NO','eng-GB');
INSERT INTO "ezcontentobject_name" ("contentobject_id","name","content_version","content_translation","real_translation") VALUES (14,'Administrator User',1,'nor-NO','eng-GB');
--
-- Data for TOC Entry ID 252 (OID 650820)
--
-- Name: ezwaituntildatevalue Type: TABLE DATA
--


--
-- Data for TOC Entry ID 253 (OID 650826)
--
-- Name: ezcontent_translation Type: TABLE DATA
--


--
-- Data for TOC Entry ID 254 (OID 650831)
--
-- Name: ezcollab_item Type: TABLE DATA
--


--
-- Data for TOC Entry ID 255 (OID 650839)
--
-- Name: ezcollab_group Type: TABLE DATA
--


--
-- Data for TOC Entry ID 256 (OID 650844)
--
-- Name: ezcollab_item_group_link Type: TABLE DATA
--


--
-- Data for TOC Entry ID 257 (OID 650847)
--
-- Name: ezcollab_item_status Type: TABLE DATA
--


--
-- Data for TOC Entry ID 258 (OID 650850)
--
-- Name: ezcollab_item_participant_link Type: TABLE DATA
--


--
-- Data for TOC Entry ID 259 (OID 650855)
--
-- Name: ezcollab_item_message_link Type: TABLE DATA
--


--
-- Data for TOC Entry ID 260 (OID 650860)
--
-- Name: ezcollab_simple_message Type: TABLE DATA
--


--
-- Data for TOC Entry ID 261 (OID 650868)
--
-- Name: ezcollab_profile Type: TABLE DATA
--


--
-- Data for TOC Entry ID 262 (OID 650876)
--
-- Name: ezapprove_items Type: TABLE DATA
--


--
-- TOC Entry ID 173 (OID 650781)
--
-- Name: "ezcontentclass_id" Type: INDEX
--

CREATE INDEX ezcontentclass_id ON ezcontentclass USING btree (id);

--
-- TOC Entry ID 174 (OID 650782)
--
-- Name: "ezcontentobject_id" Type: INDEX
--

CREATE UNIQUE INDEX ezcontentobject_id ON ezcontentobject USING btree (id);

--
-- TOC Entry ID 175 (OID 650783)
--
-- Name: "ezsearch_object_word_link_word" Type: INDEX
--

CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link USING btree (word_id);

--
-- TOC Entry ID 176 (OID 650784)
--
-- Name: "ezsearch_object_word_link_freq" Type: INDEX
--

CREATE INDEX ezsearch_object_word_link_freq ON ezsearch_object_word_link USING btree (frequency);

--
-- TOC Entry ID 177 (OID 650785)
--
-- Name: "ezsearch_word_i" Type: INDEX
--

CREATE INDEX ezsearch_word_i ON ezsearch_word USING btree (word);

--
-- TOC Entry ID 178 (OID 650786)
--
-- Name: "ezcontentobject_tree_path" Type: INDEX
--

CREATE INDEX ezcontentobject_tree_path ON ezcontentobject_tree USING btree (path_string);

--
-- TOC Entry ID 179 (OID 650787)
--
-- Name: "ezcontentobject_tree_p_node_id" Type: INDEX
--

CREATE INDEX ezcontentobject_tree_p_node_id ON ezcontentobject_tree USING btree (parent_node_id);

--
-- TOC Entry ID 180 (OID 650788)
--
-- Name: "ezcontentobject_tree_co_id" Type: INDEX
--

CREATE INDEX ezcontentobject_tree_co_id ON ezcontentobject_tree USING btree (contentobject_id);

--
-- TOC Entry ID 181 (OID 650789)
--
-- Name: "ezcontentobject_tree_depth" Type: INDEX
--

CREATE INDEX ezcontentobject_tree_depth ON ezcontentobject_tree USING btree (depth);

--
-- TOC Entry ID 182 (OID 650790)
--
-- Name: "eztrigger_id" Type: INDEX
--

CREATE UNIQUE INDEX eztrigger_id ON eztrigger USING btree (id);

--
-- TOC Entry ID 183 (OID 650791)
--
-- Name: "eztrigger_fetch" Type: INDEX
--

CREATE INDEX eztrigger_fetch ON eztrigger USING btree (module_name, function_name, connect_type);

--
-- TOC Entry ID 184 (OID 650792)
--
-- Name: "ezmodule_run_workflow_process_id_s" Type: INDEX
--

CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s ON ezmodule_run USING btree (workflow_process_id);

--
-- TOC Entry ID 185 (OID 650793)
--
-- Name: "ezcontentobject_tree_crc32_path" Type: INDEX
--

CREATE INDEX ezcontentobject_tree_crc32_path ON ezcontentobject_tree USING btree (crc32_path);

--
-- TOC Entry ID 186 (OID 650794)
--
-- Name: "ezuser_contentobject_id" Type: INDEX
--

CREATE UNIQUE INDEX ezuser_contentobject_id ON ezuser USING btree (contentobject_id);

--
-- TOC Entry ID 187 (OID 650795)
--
-- Name: "ezuser_role_contentobject_id" Type: INDEX
--

CREATE INDEX ezuser_role_contentobject_id ON ezuser_role USING btree (contentobject_id);

--
-- TOC Entry ID 188 (OID 650796)
--
-- Name: "ezcontentobject_attribute_contentobject_id" Type: INDEX
--

CREATE INDEX ezcontentobject_attribute_contentobject_id ON ezcontentobject_attribute USING btree (contentobject_id);

--
-- TOC Entry ID 189 (OID 650797)
--
-- Name: "ezcontentobject_attribute_language_code" Type: INDEX
--

CREATE INDEX ezcontentobject_attribute_language_code ON ezcontentobject_attribute USING btree (language_code);

--
-- TOC Entry ID 190 (OID 650798)
--
-- Name: "ezcontentclass_version" Type: INDEX
--

CREATE INDEX ezcontentclass_version ON ezcontentclass USING btree ("version");

--
-- TOC Entry ID 191 (OID 650799)
--
-- Name: "ezenumvalue_co_cl_attr_id_co_class_att_ver" Type: INDEX
--

CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver ON ezenumvalue USING btree (contentclass_attribute_id, contentclass_attribute_version);

--
-- TOC Entry ID 192 (OID 650800)
--
-- Name: "ezenumobjectvalue_co_attr_id_co_attr_ver" Type: INDEX
--

CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver ON ezenumobjectvalue USING btree (contentobject_attribute_id, contentobject_attribute_version);

--
-- TOC Entry ID 193 (OID 650823)
--
-- Name: "ezwaituntildatevalue_wf_ev_id_wf_ver" Type: INDEX
--

CREATE INDEX ezwaituntildatevalue_wf_ev_id_wf_ver ON ezwaituntildatevalue USING btree (workflow_event_id, workflow_event_version);

--
-- TOC Entry ID 194 (OID 650842)
--
-- Name: "ezcollab_group_path" Type: INDEX
--

CREATE INDEX ezcollab_group_path ON ezcollab_group USING btree (path_string);

--
-- TOC Entry ID 195 (OID 650843)
--
-- Name: "ezcollab_group_dept" Type: INDEX
--

CREATE INDEX ezcollab_group_dept ON ezcollab_group USING btree (depth);

--
-- TOC Entry ID 3 (OID 650399)
--
-- Name: ezapprovetasks_s Type: SEQUENCE SET
--

SELECT setval ('"ezapprovetasks_s"', 1, false);

--
-- TOC Entry ID 5 (OID 650404)
--
-- Name: ezbasket_s Type: SEQUENCE SET
--

SELECT setval ('"ezbasket_s"', 1, false);

--
-- TOC Entry ID 7 (OID 650412)
--
-- Name: ezcontentclass_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentclass_s"', 6, true);

--
-- TOC Entry ID 9 (OID 650422)
--
-- Name: ezcontentclass_attribute_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentclass_attribute_s"', 124, true);

--
-- TOC Entry ID 11 (OID 650450)
--
-- Name: ezcontentclassgroup_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentclassgroup_s"', 4, true);

--
-- TOC Entry ID 13 (OID 650458)
--
-- Name: ezcontentobject_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentobject_s"', 15, true);

--
-- TOC Entry ID 15 (OID 650470)
--
-- Name: ezcontentobject_attribute_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentobject_attribute_s"', 371, true);

--
-- TOC Entry ID 17 (OID 650494)
--
-- Name: ezcontentobject_link_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentobject_link_s"', 1, false);

--
-- TOC Entry ID 19 (OID 650499)
--
-- Name: ezcontentobject_tree_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentobject_tree_s"', 16, true);

--
-- TOC Entry ID 21 (OID 650515)
--
-- Name: ezcontentobject_version_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontentobject_version_s"', 443, true);

--
-- TOC Entry ID 23 (OID 650530)
--
-- Name: ezenumvalue_s Type: SEQUENCE SET
--

SELECT setval ('"ezenumvalue_s"', 1, false);

--
-- TOC Entry ID 25 (OID 650544)
--
-- Name: ezmodule_run_s Type: SEQUENCE SET
--

SELECT setval ('"ezmodule_run_s"', 1, false);

--
-- TOC Entry ID 27 (OID 650552)
--
-- Name: eznode_assignment_s Type: SEQUENCE SET
--

SELECT setval ('"eznode_assignment_s"', 154, true);

--
-- TOC Entry ID 29 (OID 650565)
--
-- Name: ezorder_s Type: SEQUENCE SET
--

SELECT setval ('"ezorder_s"', 1, false);

--
-- TOC Entry ID 31 (OID 650570)
--
-- Name: ezpolicy_s Type: SEQUENCE SET
--

SELECT setval ('"ezpolicy_s"', 315, false);

--
-- TOC Entry ID 33 (OID 650581)
--
-- Name: ezpolicy_limitation_s Type: SEQUENCE SET
--

SELECT setval ('"ezpolicy_limitation_s"', 245, false);

--
-- TOC Entry ID 35 (OID 650590)
--
-- Name: ezpolicy_limitation_value_s Type: SEQUENCE SET
--

SELECT setval ('"ezpolicy_limitation_value_s"', 408, false);

--
-- TOC Entry ID 37 (OID 650597)
--
-- Name: ezproductcollection_s Type: SEQUENCE SET
--

SELECT setval ('"ezproductcollection_s"', 1, false);

--
-- TOC Entry ID 39 (OID 650602)
--
-- Name: ezproductcollection_item_s Type: SEQUENCE SET
--

SELECT setval ('"ezproductcollection_item_s"', 1, false);

--
-- TOC Entry ID 41 (OID 650607)
--
-- Name: ezrole_s Type: SEQUENCE SET
--

SELECT setval ('"ezrole_s"', 4, false);

--
-- TOC Entry ID 43 (OID 650618)
--
-- Name: ezsearch_object_word_link_s Type: SEQUENCE SET
--

SELECT setval ('"ezsearch_object_word_link_s"', 1, false);

--
-- TOC Entry ID 45 (OID 650623)
--
-- Name: ezsearch_return_count_s Type: SEQUENCE SET
--

SELECT setval ('"ezsearch_return_count_s"', 1, false);

--
-- TOC Entry ID 47 (OID 650628)
--
-- Name: ezsearch_search_phrase_s Type: SEQUENCE SET
--

SELECT setval ('"ezsearch_search_phrase_s"', 1, false);

--
-- TOC Entry ID 49 (OID 650633)
--
-- Name: ezsearch_word_s Type: SEQUENCE SET
--

SELECT setval ('"ezsearch_word_s"', 1, false);

--
-- TOC Entry ID 51 (OID 650638)
--
-- Name: ezsection_s Type: SEQUENCE SET
--

SELECT setval ('"ezsection_s"', 1, false);

--
-- TOC Entry ID 53 (OID 650650)
--
-- Name: eztask_s Type: SEQUENCE SET
--

SELECT setval ('"eztask_s"', 1, false);

--
-- TOC Entry ID 55 (OID 650655)
--
-- Name: eztask_message_s Type: SEQUENCE SET
--

SELECT setval ('"eztask_message_s"', 1, false);

--
-- TOC Entry ID 57 (OID 650660)
--
-- Name: eztrigger_s Type: SEQUENCE SET
--

SELECT setval ('"eztrigger_s"', 1, false);

--
-- TOC Entry ID 59 (OID 650668)
--
-- Name: ezuser_role_s Type: SEQUENCE SET
--

SELECT setval ('"ezuser_role_s"', 26, false);

--
-- TOC Entry ID 61 (OID 650680)
--
-- Name: ezwishlist_s Type: SEQUENCE SET
--

SELECT setval ('"ezwishlist_s"', 1, false);

--
-- TOC Entry ID 63 (OID 650685)
--
-- Name: ezworkflow_s Type: SEQUENCE SET
--

SELECT setval ('"ezworkflow_s"', 2, false);

--
-- TOC Entry ID 65 (OID 650691)
--
-- Name: ezworkflow_assign_s Type: SEQUENCE SET
--

SELECT setval ('"ezworkflow_assign_s"', 1, false);

--
-- TOC Entry ID 67 (OID 650696)
--
-- Name: ezworkflow_event_s Type: SEQUENCE SET
--

SELECT setval ('"ezworkflow_event_s"', 3, false);

--
-- TOC Entry ID 69 (OID 650703)
--
-- Name: ezworkflow_group_s Type: SEQUENCE SET
--

SELECT setval ('"ezworkflow_group_s"', 2, false);

--
-- TOC Entry ID 71 (OID 650716)
--
-- Name: ezworkflow_process_s Type: SEQUENCE SET
--

SELECT setval ('"ezworkflow_process_s"', 1, false);

--
-- TOC Entry ID 73 (OID 650724)
--
-- Name: ezoperation_memento_s Type: SEQUENCE SET
--

SELECT setval ('"ezoperation_memento_s"', 1, false);

--
-- TOC Entry ID 75 (OID 650732)
--
-- Name: ezdiscountsubrule_s Type: SEQUENCE SET
--

SELECT setval ('"ezdiscountsubrule_s"', 1, false);

--
-- TOC Entry ID 77 (OID 650740)
--
-- Name: ezinfocollection_s Type: SEQUENCE SET
--

SELECT setval ('"ezinfocollection_s"', 1, false);

--
-- TOC Entry ID 79 (OID 650745)
--
-- Name: ezinfocollection_attribute_s Type: SEQUENCE SET
--

SELECT setval ('"ezinfocollection_attribute_s"', 1, false);

--
-- TOC Entry ID 81 (OID 650753)
--
-- Name: ezuser_discountrule_s Type: SEQUENCE SET
--

SELECT setval ('"ezuser_discountrule_s"', 1, false);

--
-- TOC Entry ID 83 (OID 650758)
--
-- Name: ezvattype_s Type: SEQUENCE SET
--

SELECT setval ('"ezvattype_s"', 1, false);

--
-- TOC Entry ID 85 (OID 650763)
--
-- Name: eznotification_rule_s Type: SEQUENCE SET
--

SELECT setval ('"eznotification_rule_s"', 1, false);

--
-- TOC Entry ID 87 (OID 650771)
--
-- Name: ezdiscountrule_s Type: SEQUENCE SET
--

SELECT setval ('"ezdiscountrule_s"', 1, false);

--
-- TOC Entry ID 89 (OID 650776)
--
-- Name: ezorder_item_s Type: SEQUENCE SET
--

SELECT setval ('"ezorder_item_s"', 1, false);

--
-- TOC Entry ID 91 (OID 650818)
--
-- Name: ezwaituntildatevalue_s Type: SEQUENCE SET
--

SELECT setval ('"ezwaituntildatevalue_s"', 1, false);

--
-- TOC Entry ID 93 (OID 650824)
--
-- Name: ezcontent_translation_s Type: SEQUENCE SET
--

SELECT setval ('"ezcontent_translation_s"', 1, false);

--
-- TOC Entry ID 95 (OID 650829)
--
-- Name: ezcollab_item_s Type: SEQUENCE SET
--

SELECT setval ('"ezcollab_item_s"', 1, false);

--
-- TOC Entry ID 97 (OID 650837)
--
-- Name: ezcollab_group_s Type: SEQUENCE SET
--

SELECT setval ('"ezcollab_group_s"', 1, false);

--
-- TOC Entry ID 99 (OID 650853)
--
-- Name: ezcollab_item_message_link_s Type: SEQUENCE SET
--

SELECT setval ('"ezcollab_item_message_link_s"', 1, false);

--
-- TOC Entry ID 101 (OID 650858)
--
-- Name: ezcollab_simple_message_s Type: SEQUENCE SET
--

SELECT setval ('"ezcollab_simple_message_s"', 1, false);

--
-- TOC Entry ID 103 (OID 650866)
--
-- Name: ezcollab_profile_s Type: SEQUENCE SET
--

SELECT setval ('"ezcollab_profile_s"', 1, false);

--
-- TOC Entry ID 105 (OID 650874)
--
-- Name: ezapprove_items_s Type: SEQUENCE SET
--

SELECT setval ('"ezapprove_items_s"', 1, false);

