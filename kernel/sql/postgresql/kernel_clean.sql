--
-- Selected TOC Entries:
--
--
-- TOC Entry ID 2 (OID 671814)
--
-- Name: ezapprovetasks_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezapprovetasks_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 4 (OID 671816)
--
-- Name: ezbasket_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezbasket_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 110 (OID 671818)
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
-- TOC Entry ID 111 (OID 671821)
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
-- TOC Entry ID 6 (OID 671824)
--
-- Name: ezcontentclass_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentclass_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 112 (OID 671826)
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

--
-- TOC Entry ID 8 (OID 671829)
--
-- Name: ezcontentclass_attribute_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentclass_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 113 (OID 671831)
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
	"is_information_collector" integer DEFAULT '0' NOT NULL,
	Constraint "ezcontentclass_attribute_pkey" Primary Key ("id", "version")
);

--
-- TOC Entry ID 114 (OID 671834)
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

--
-- TOC Entry ID 10 (OID 671837)
--
-- Name: ezcontentclassgroup_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentclassgroup_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 115 (OID 671839)
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

--
-- TOC Entry ID 12 (OID 671842)
--
-- Name: ezcontentobject_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 116 (OID 671844)
--
-- Name: ezcontentobject Type: TABLE Owner: sp
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
	"remote_id" character varying(100) DEFAULT '' NOT NULL,
	Constraint "ezcontentobject_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 14 (OID 671847)
--
-- Name: ezcontentobject_attribute_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 117 (OID 671849)
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

--
-- TOC Entry ID 16 (OID 671855)
--
-- Name: ezcontentobject_link_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 118 (OID 671857)
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
-- TOC Entry ID 18 (OID 671860)
--
-- Name: ezcontentobject_tree_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_tree_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 119 (OID 671862)
--
-- Name: ezcontentobject_tree Type: TABLE Owner: sp
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
-- TOC Entry ID 20 (OID 671868)
--
-- Name: ezcontentobject_version_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontentobject_version_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 120 (OID 671870)
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

--
-- TOC Entry ID 121 (OID 671873)
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
-- TOC Entry ID 22 (OID 671876)
--
-- Name: ezenumvalue_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezenumvalue_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 122 (OID 671878)
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
-- TOC Entry ID 123 (OID 671881)
--
-- Name: ezimage Type: TABLE Owner: sp
--

CREATE TABLE "ezimage" (
	"contentobject_attribute_id" integer NOT NULL,
	"version" integer NOT NULL,
	"filename" character varying(255) NOT NULL,
	"original_filename" character varying(255) NOT NULL,
	"mime_type" character varying(50) NOT NULL,
	"alternative_text" character varying(255) DEFAULT '' NOT NULL,
	Constraint "ezimage_pkey" Primary Key ("contentobject_attribute_id", "version")
);

--
-- TOC Entry ID 124 (OID 671884)
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
-- TOC Entry ID 125 (OID 671887)
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
-- TOC Entry ID 24 (OID 671890)
--
-- Name: ezmodule_run_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezmodule_run_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 126 (OID 671892)
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
-- TOC Entry ID 26 (OID 671898)
--
-- Name: eznode_assignment_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "eznode_assignment_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 127 (OID 671900)
--
-- Name: eznode_assignment Type: TABLE Owner: sp
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
-- TOC Entry ID 28 (OID 671903)
--
-- Name: ezorder_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezorder_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 128 (OID 671905)
--
-- Name: ezorder Type: TABLE Owner: sp
--

CREATE TABLE "ezorder" (
	"id" integer DEFAULT nextval('ezorder_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"productcollection_id" integer NOT NULL,
	"created" integer NOT NULL,
	"is_temporary" integer DEFAULT '1' NOT NULL,
	"order_nr" integer DEFAULT '0' NOT NULL,
	"account_identifier" character varying(100) DEFAULT 'default' NOT NULL,
	"ignore_vat" integer DEFAULT '0' NOT NULL,
	"data_text_1" text,
	"data_text_2" text,
	Constraint "ezorder_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 30 (OID 671911)
--
-- Name: ezpolicy_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezpolicy_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 129 (OID 671913)
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

--
-- TOC Entry ID 32 (OID 671919)
--
-- Name: ezpolicy_limitation_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezpolicy_limitation_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 130 (OID 671921)
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

--
-- TOC Entry ID 34 (OID 671927)
--
-- Name: ezpolicy_limitation_value_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezpolicy_limitation_value_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 131 (OID 671929)
--
-- Name: ezpolicy_limitation_value Type: TABLE Owner: sp
--

CREATE TABLE "ezpolicy_limitation_value" (
	"id" integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
	"limitation_id" integer,
	"value" integer,
	Constraint "ezpolicy_limitation_value_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 36 (OID 671932)
--
-- Name: ezproductcollection_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezproductcollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 132 (OID 671934)
--
-- Name: ezproductcollection Type: TABLE Owner: sp
--

CREATE TABLE "ezproductcollection" (
	"id" integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
	"created" integer,
	Constraint "ezproductcollection_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 38 (OID 671937)
--
-- Name: ezproductcollection_item_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezproductcollection_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 133 (OID 671939)
--
-- Name: ezproductcollection_item Type: TABLE Owner: sp
--

CREATE TABLE "ezproductcollection_item" (
	"id" integer DEFAULT nextval('ezproductcollection_item_s'::text) NOT NULL,
	"productcollection_id" integer NOT NULL,
	"contentobject_id" integer NOT NULL,
	"item_count" integer NOT NULL,
	"price" double precision NOT NULL,
	"is_vat_inc" integer NOT NULL,
	"vat_value" double precision DEFAULT 0 NOT NULL,
	"discount" double precision DEFAULT 0 NOT NULL,
	Constraint "ezproductcollection_item_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 40 (OID 671942)
--
-- Name: ezrole_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezrole_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 134 (OID 671944)
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

--
-- TOC Entry ID 42 (OID 671950)
--
-- Name: ezsearch_object_word_link_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_object_word_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 135 (OID 671952)
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
	"published" integer DEFAULT '0' NOT NULL,
	"section_id" integer DEFAULT '0' NOT NULL,
	Constraint "ezsearch_object_word_link_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 44 (OID 671955)
--
-- Name: ezsearch_return_count_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_return_count_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 136 (OID 671957)
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
-- TOC Entry ID 46 (OID 671960)
--
-- Name: ezsearch_search_phrase_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_search_phrase_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 137 (OID 671962)
--
-- Name: ezsearch_search_phrase Type: TABLE Owner: sp
--

CREATE TABLE "ezsearch_search_phrase" (
	"id" integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
	"phrase" character varying(250),
	Constraint "ezsearch_search_phrase_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 48 (OID 671965)
--
-- Name: ezsearch_word_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsearch_word_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 138 (OID 671967)
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
-- TOC Entry ID 50 (OID 671970)
--
-- Name: ezsection_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezsection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 139 (OID 671972)
--
-- Name: ezsection Type: TABLE Owner: sp
--

CREATE TABLE "ezsection" (
	"id" integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
	"name" character varying(255),
	"locale" character varying(255),
	"navigation_part_identifier" character varying(100) DEFAULT 'ezcontentnavigationpart',
	Constraint "ezsection_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 140 (OID 671975)
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
-- TOC Entry ID 52 (OID 671981)
--
-- Name: eztrigger_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "eztrigger_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 141 (OID 671983)
--
-- Name: eztrigger Type: TABLE Owner: sp
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
-- TOC Entry ID 142 (OID 671985)
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

--
-- TOC Entry ID 54 (OID 671987)
--
-- Name: ezuser_role_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezuser_role_s" start 26 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 143 (OID 671989)
--
-- Name: ezuser_role Type: TABLE Owner: sp
--

CREATE TABLE "ezuser_role" (
	"id" integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
	"role_id" integer,
	"contentobject_id" integer,
	Constraint "ezuser_role_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 144 (OID 671992)
--
-- Name: ezuser_setting Type: TABLE Owner: sp
--

CREATE TABLE "ezuser_setting" (
	"user_id" integer DEFAULT '0' NOT NULL,
	"is_enabled" smallint DEFAULT '0' NOT NULL,
	"max_login" integer,
	Constraint "ezuser_setting_pkey" Primary Key ("user_id")
);

--
-- TOC Entry ID 56 (OID 671995)
--
-- Name: ezwishlist_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezwishlist_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 145 (OID 671997)
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
-- TOC Entry ID 58 (OID 672000)
--
-- Name: ezworkflow_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 146 (OID 672002)
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

--
-- TOC Entry ID 60 (OID 672005)
--
-- Name: ezworkflow_assign_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_assign_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 147 (OID 672007)
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
-- TOC Entry ID 62 (OID 672010)
--
-- Name: ezworkflow_event_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_event_s" start 3 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 148 (OID 672012)
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

--
-- TOC Entry ID 64 (OID 672015)
--
-- Name: ezworkflow_group_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_group_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 149 (OID 672017)
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

--
-- TOC Entry ID 150 (OID 672020)
--
-- Name: ezworkflow_group_link Type: TABLE Owner: sp
--

CREATE TABLE "ezworkflow_group_link" (
	"workflow_id" integer DEFAULT '0' NOT NULL,
	"group_id" integer DEFAULT '0' NOT NULL,
	"workflow_version" integer DEFAULT '0' NOT NULL,
	"group_name" character varying,
	Constraint "ezworkflow_group_link_pkey" Primary Key ("workflow_id", "group_id", "workflow_version")
);

--
-- TOC Entry ID 66 (OID 672026)
--
-- Name: ezworkflow_process_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezworkflow_process_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 151 (OID 672028)
--
-- Name: ezworkflow_process Type: TABLE Owner: sp
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
-- TOC Entry ID 68 (OID 672034)
--
-- Name: ezoperation_memento_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezoperation_memento_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 152 (OID 672036)
--
-- Name: ezoperation_memento Type: TABLE Owner: sp
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
-- TOC Entry ID 70 (OID 672042)
--
-- Name: ezdiscountsubrule_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezdiscountsubrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 153 (OID 672044)
--
-- Name: ezdiscountsubrule Type: TABLE Owner: sp
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
-- TOC Entry ID 154 (OID 672047)
--
-- Name: ezdiscountsubrule_value Type: TABLE Owner: sp
--

CREATE TABLE "ezdiscountsubrule_value" (
	"discountsubrule_id" integer DEFAULT '0' NOT NULL,
	"value" integer DEFAULT '0' NOT NULL,
	"issection" integer DEFAULT '0' NOT NULL,
	Constraint "ezdiscountsubrule_value_pkey" Primary Key ("discountsubrule_id", "value", "issection")
);

--
-- TOC Entry ID 72 (OID 672050)
--
-- Name: ezinfocollection_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezinfocollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 155 (OID 672052)
--
-- Name: ezinfocollection Type: TABLE Owner: sp
--

CREATE TABLE "ezinfocollection" (
	"id" integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
	"contentobject_id" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	Constraint "ezinfocollection_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 74 (OID 672055)
--
-- Name: ezinfocollection_attribute_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezinfocollection_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 156 (OID 672057)
--
-- Name: ezinfocollection_attribute Type: TABLE Owner: sp
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
-- TOC Entry ID 76 (OID 672063)
--
-- Name: ezuser_discountrule_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezuser_discountrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 157 (OID 672065)
--
-- Name: ezuser_discountrule Type: TABLE Owner: sp
--

CREATE TABLE "ezuser_discountrule" (
	"id" integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
	"discountrule_id" integer,
	"contentobject_id" integer,
	"name" character varying(255) DEFAULT '' NOT NULL,
	Constraint "ezuser_discountrule_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 78 (OID 672068)
--
-- Name: ezvattype_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezvattype_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 158 (OID 672070)
--
-- Name: ezvattype Type: TABLE Owner: sp
--

CREATE TABLE "ezvattype" (
	"id" integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"percentage" double precision,
	Constraint "ezvattype_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 82 (OID 672081)
--
-- Name: ezdiscountrule_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezdiscountrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 161 (OID 672083)
--
-- Name: ezdiscountrule Type: TABLE Owner: sp
--

CREATE TABLE "ezdiscountrule" (
	"id" integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
	"name" character varying(255) NOT NULL,
	Constraint "ezdiscountrule_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 84 (OID 672086)
--
-- Name: ezorder_item_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezorder_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 162 (OID 672088)
--
-- Name: ezorder_item Type: TABLE Owner: sp
--

CREATE TABLE "ezorder_item" (
	"id" integer DEFAULT nextval('ezorder_item_s'::text) NOT NULL,
	"order_id" integer NOT NULL,
	"description" character varying(255),
	"price" double precision,
	"vat_value" integer DEFAULT '0' NOT NULL,
	Constraint "ezorder_item_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 163 (OID 672091)
--
-- Name: ezcontentobject_name Type: TABLE Owner: sp
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
-- TOC Entry ID 86 (OID 672094)
--
-- Name: ezwaituntildatevalue_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezwaituntildatevalue_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 164 (OID 672096)
--
-- Name: ezwaituntildatevalue Type: TABLE Owner: sp
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
-- TOC Entry ID 88 (OID 672099)
--
-- Name: ezcontent_translation_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcontent_translation_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 165 (OID 672101)
--
-- Name: ezcontent_translation Type: TABLE Owner: sp
--

CREATE TABLE "ezcontent_translation" (
	"id" integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"locale" character varying(255) NOT NULL,
	Constraint "ezcontent_translation_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 90 (OID 672104)
--
-- Name: ezcollab_item_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcollab_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 166 (OID 672106)
--
-- Name: ezcollab_item Type: TABLE Owner: sp
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
-- TOC Entry ID 92 (OID 672112)
--
-- Name: ezcollab_group_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcollab_group_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 167 (OID 672114)
--
-- Name: ezcollab_group Type: TABLE Owner: sp
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
-- TOC Entry ID 168 (OID 672117)
--
-- Name: ezcollab_item_group_link Type: TABLE Owner: sp
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
-- TOC Entry ID 169 (OID 672120)
--
-- Name: ezcollab_item_status Type: TABLE Owner: sp
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
-- TOC Entry ID 170 (OID 672123)
--
-- Name: ezcollab_item_participant_link Type: TABLE Owner: sp
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
-- TOC Entry ID 94 (OID 672126)
--
-- Name: ezcollab_item_message_link_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcollab_item_message_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 171 (OID 672128)
--
-- Name: ezcollab_item_message_link Type: TABLE Owner: sp
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
-- TOC Entry ID 96 (OID 672131)
--
-- Name: ezcollab_simple_message_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcollab_simple_message_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 172 (OID 672133)
--
-- Name: ezcollab_simple_message Type: TABLE Owner: sp
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
-- TOC Entry ID 98 (OID 672139)
--
-- Name: ezcollab_profile_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezcollab_profile_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 173 (OID 672141)
--
-- Name: ezcollab_profile Type: TABLE Owner: sp
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
-- TOC Entry ID 100 (OID 672147)
--
-- Name: ezapprove_items_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezapprove_items_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 174 (OID 672149)
--
-- Name: ezapprove_items Type: TABLE Owner: sp
--

CREATE TABLE "ezapprove_items" (
	"id" integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
	"workflow_process_id" integer DEFAULT '0' NOT NULL,
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	Constraint "ezapprove_items_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 102 (OID 672152)
--
-- Name: ezurl_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezurl_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 175 (OID 672154)
--
-- Name: ezurl Type: TABLE Owner: sp
--

CREATE TABLE "ezurl" (
	"id" integer DEFAULT nextval('ezurl_s'::text) NOT NULL,
	"url" character varying(255),
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	"is_valid" integer DEFAULT '1' NOT NULL,
	"last_checked" integer DEFAULT '0' NOT NULL,
	"original_url_md5" character varying(32) DEFAULT '' NOT NULL,
	Constraint "ezurl_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 104 (OID 672157)
--
-- Name: ezmessage_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezmessage_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 176 (OID 672159)
--
-- Name: ezmessage Type: TABLE Owner: sp
--

CREATE TABLE "ezmessage" (
	"id" integer DEFAULT nextval('ezmessage_s'::text) NOT NULL,
	"send_method" character varying(50) DEFAULT '' NOT NULL,
	"send_weekday" character varying(50) DEFAULT '' NOT NULL,
	"send_time" character varying(50) DEFAULT '' NOT NULL,
	"destination_address" character varying(50) DEFAULT '' NOT NULL,
	"title" character varying(50) DEFAULT '' NOT NULL,
	"body" character varying(50),
	"is_sent" smallint DEFAULT '0' NOT NULL,
	Constraint "ezmessage_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 106 (OID 672297)
--
-- Name: ezproductcollection_item_opt_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezproductcollection_item_opt_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 177 (OID 672299)
--
-- Name: ezproductcollection_item_opt Type: TABLE Owner: sp
--

CREATE TABLE "ezproductcollection_item_opt" (
	"id" integer DEFAULT nextval('ezproductcollection_item_opt_s'::text) NOT NULL,
	"item_id" integer NOT NULL,
	"option_item_id" integer NOT NULL,
	"object_attribute_id" integer NOT NULL,
	"name" character varying(255) NOT NULL,
	"value" character varying(255) NOT NULL,
	"price" double precision DEFAULT 0 NOT NULL,
	Constraint "ezproductcollection_item_opt_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 108 (OID 672302)
--
-- Name: ezforgot_password_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "ezforgot_password_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 178 (OID 672304)
--
-- Name: ezforgot_password Type: TABLE Owner: sp
--

CREATE TABLE "ezforgot_password" (
	"id" integer DEFAULT nextval('ezforgot_password_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"hash_key" character varying(32) NOT NULL,
	"time" integer NOT NULL,
	Constraint "ezforgot_password_pkey" Primary Key ("id")
);

--
-- Data for TOC Entry ID 203 (OID 671818)
--
-- Name: ezbasket Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 204 (OID 671821)
--
-- Name: ezbinaryfile Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 205 (OID 671826)
--
-- Name: ezcontentclass Type: TABLE DATA Owner: sp
--


INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1033922265);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1033922035);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1033922064);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1033922083);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (5,0,'Image','','<name>',8,14,1031484992,1033921948);
--
-- Data for TOC Entry ID 206 (OID 671831)
--
-- Name: ezcontentclass_attribute Type: TABLE DATA Owner: sp
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
-- Data for TOC Entry ID 207 (OID 671834)
--
-- Name: ezcontentclass_classgroup Type: TABLE DATA Owner: sp
--


INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (1,0,1,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (2,0,1,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (4,0,2,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (5,0,3,'Media');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (3,0,2,'');
--
-- Data for TOC Entry ID 208 (OID 671839)
--
-- Name: ezcontentclassgroup Type: TABLE DATA Owner: sp
--


INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (3,'Media',8,14,1032009743,1033922120);
--
-- Data for TOC Entry ID 209 (OID 671844)
--
-- Name: ezcontentobject Type: TABLE DATA Owner: sp
--


INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (1,0,1,1,'Frontpage',1,0,1033917596,1033917596,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (4,0,2,3,'Users',1,0,0,0,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (10,8,2,4,'Anonymous User',1,0,1033920665,1033920665,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (11,8,2,3,'Guest accounts',1,0,1033920746,1033920746,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (12,8,2,3,'Administrator users',1,0,1033920775,1033920775,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (13,8,2,3,'Editors',1,0,1033920794,1033920794,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (14,8,2,4,'Administrator User',1,0,1033920830,1033920830,1,'');
--
-- Data for TOC Entry ID 210 (OID 671849)
--
-- Name: ezcontentobject_attribute Type: TABLE DATA Owner: sp
--


INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (1,'eng-GB',1,1,4,'Frontpage',0,0);
INSERT INTO "ezcontentobject_attribute" ("id","language_code","version","contentobject_id","contentclassattribute_id","data_text","data_int","data_float") VALUES (2,'eng-GB',1,1,119,'<?xml version="1.0"><section><paragraph>This folder contains some information about...</paragraph></section>',0,0);
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
-- Data for TOC Entry ID 211 (OID 671857)
--
-- Name: ezcontentobject_link Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 212 (OID 671862)
--
-- Name: ezcontentobject_tree Type: TABLE DATA Owner: sp
--


INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (1,1,1,0,1,1,NULL,0,'/1/',NULL,1,1,0,NULL);
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (2,2,1,1,1,1,0,1,'/1/2/','',1,1,0,'d41d8cd98f00b204e9800998ecf8427e');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (5,5,1,4,1,0,-195235522,1,'/1/5/','__1',1,1,0,'08a9d0bbf3381652f7cca8738b5a8469');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (11,11,5,10,1,1,1015610524,2,'/1/5/11/','__1/anonymous_user',1,1,0,'a59d2313b486e0f43477433525edea9b');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (12,12,5,11,1,1,1857785444,2,'/1/5/12/','__1/guest_accounts',1,1,0,'c894997127008ea742913062f39adfc5');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (13,13,5,12,1,1,-1978139175,2,'/1/5/13/','__1/administrator_users',1,1,0,'caeccbc33185f04d92e2b6cb83b1c7e4');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (15,15,13,14,1,1,-852704961,3,'/1/5/13/15/','__1/administrator_users/administrator_user',1,1,0,'2c3f2814cfa91bcb17d7893ca6f8a0c4');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (14,14,5,13,1,1,2094553782,2,'/1/5/14/','__1/editors',1,1,0,'39f6f6f51c1e3a922600b2d415d7a46d');
--
-- Data for TOC Entry ID 213 (OID 671870)
--
-- Name: ezcontentobject_version Type: TABLE DATA Owner: sp
--


INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (436,1,8,1,1033919080,1033919080,1,1,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (4,4,8,1,1033919080,1033919080,1,1,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (438,10,8,1,1033920649,1033920665,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (439,11,8,1,1033920737,1033920746,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (440,12,8,1,1033920760,1033920775,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (441,13,8,1,1033920786,1033920794,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (442,14,8,1,1033920808,1033920830,0,0,0,NULL);
--
-- Data for TOC Entry ID 214 (OID 671873)
--
-- Name: ezenumobjectvalue Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 215 (OID 671878)
--
-- Name: ezenumvalue Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 216 (OID 671881)
--
-- Name: ezimage Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 217 (OID 671884)
--
-- Name: ezimagevariation Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 218 (OID 671887)
--
-- Name: ezmedia Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 219 (OID 671892)
--
-- Name: ezmodule_run Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 220 (OID 671900)
--
-- Name: eznode_assignment Type: TABLE DATA Owner: sp
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
-- Data for TOC Entry ID 221 (OID 671905)
--
-- Name: ezorder Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 222 (OID 671913)
--
-- Name: ezpolicy Type: TABLE DATA Owner: sp
--


INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (308,2,'*','*','*');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (315,1,'read','content',' ');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (316,1,'login','user','*');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (317,3,'*','content','*');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (318,3,'login','user','*');
--
-- Data for TOC Entry ID 223 (OID 671921)
--
-- Name: ezpolicy_limitation Type: TABLE DATA Owner: sp
--


INSERT INTO "ezpolicy_limitation" ("id","policy_id","identifier","role_id","function_name","module_name") VALUES (245,315,'Class',0,'read','content');
--
-- Data for TOC Entry ID 224 (OID 671929)
--
-- Name: ezpolicy_limitation_value Type: TABLE DATA Owner: sp
--


INSERT INTO "ezpolicy_limitation_value" ("id","limitation_id","value") VALUES (409,245,1);
INSERT INTO "ezpolicy_limitation_value" ("id","limitation_id","value") VALUES (410,245,7);
--
-- Data for TOC Entry ID 225 (OID 671934)
--
-- Name: ezproductcollection Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 226 (OID 671939)
--
-- Name: ezproductcollection_item Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 227 (OID 671944)
--
-- Name: ezrole Type: TABLE DATA Owner: sp
--


INSERT INTO "ezrole" ("id","version","name","value") VALUES (2,0,'Administrator','*');
INSERT INTO "ezrole" ("id","version","name","value") VALUES (1,0,'Anonymous',' ');
INSERT INTO "ezrole" ("id","version","name","value") VALUES (3,0,'Editor',' ');
--
-- Data for TOC Entry ID 228 (OID 671952)
--
-- Name: ezsearch_object_word_link Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 229 (OID 671957)
--
-- Name: ezsearch_return_count Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 230 (OID 671962)
--
-- Name: ezsearch_search_phrase Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 231 (OID 671967)
--
-- Name: ezsearch_word Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 232 (OID 671972)
--
-- Name: ezsection Type: TABLE DATA Owner: sp
--


INSERT INTO "ezsection" ("id","name","locale","navigation_part_identifier") VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO "ezsection" ("id","name","locale","navigation_part_identifier") VALUES (2,'Users section','','ezusernavigationpart');
--
-- Data for TOC Entry ID 233 (OID 671975)
--
-- Name: ezsession Type: TABLE DATA Owner: sp
--


INSERT INTO "ezsession" ("session_key","expiration_time","data") VALUES ('7a244467fd70e3ec35bb977be0b1dc6a',1048773857,'LastAccessesURI|s:20:"/workflow/grouplist/";eZUserInfoCache_Timestamp|i:1048514591;eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_Timestamp|i:1048514591;eZUserGroupsCache_10|a:1:{i:0;a:2:{i:0;s:1:"4";s:2:"id";s:1:"4";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1048514591;UserRoles|a:1:{i:0;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:13:"Administrator";s:4:"name";s:13:"Administrator";}}canInstantiateClassesCachedForUser|s:2:"14";classesCachedTimestamp|i:1048514624;canInstantiateClasses|i:1;eZUserLoggedInID|s:2:"14";eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_14|a:1:{i:0;a:2:{i:0;s:2:"12";s:2:"id";s:2:"12";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}BrowseFromPage|s:18:"/section/assign/2/";BrowseActionName|s:13:"AssignSection";BrowseReturnType|s:6:"NodeID";CustomActionButton|N;BrowseSelectionType|N;canInstantiateClassList|a:5:{i:0;a:4:{i:0;s:1:"1";s:2:"id";s:1:"1";i:1;s:6:"Folder";s:4:"name";s:6:"Folder";}i:1;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:7:"Article";s:4:"name";s:7:"Article";}i:2;a:4:{i:0;s:1:"3";s:2:"id";s:1:"3";i:1;s:10:"User group";s:4:"name";s:10:"User group";}i:3;a:4:{i:0;s:1:"4";s:2:"id";s:1:"4";i:1;s:4:"User";s:4:"name";s:4:"User";}i:4;a:4:{i:0;s:1:"5";s:2:"id";s:1:"5";i:1;s:5:"Image";s:4:"name";s:5:"Image";}}eZUserDiscountRulesTimestamp|i:1048514636;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:"id";s:1:"2";}');
--
-- Data for TOC Entry ID 234 (OID 671983)
--
-- Name: eztrigger Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 235 (OID 671985)
--
-- Name: ezuser Type: TABLE DATA Owner: sp
--


INSERT INTO "ezuser" ("contentobject_id","login","email","password_hash_type","password_hash") VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO "ezuser" ("contentobject_id","login","email","password_hash_type","password_hash") VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');
--
-- Data for TOC Entry ID 236 (OID 671989)
--
-- Name: ezuser_role Type: TABLE DATA Owner: sp
--


INSERT INTO "ezuser_role" ("id","role_id","contentobject_id") VALUES (24,1,4);
INSERT INTO "ezuser_role" ("id","role_id","contentobject_id") VALUES (25,2,12);
--
-- Data for TOC Entry ID 237 (OID 671992)
--
-- Name: ezuser_setting Type: TABLE DATA Owner: sp
--


INSERT INTO "ezuser_setting" ("user_id","is_enabled","max_login") VALUES (10,1,1000);
INSERT INTO "ezuser_setting" ("user_id","is_enabled","max_login") VALUES (14,1,10);
--
-- Data for TOC Entry ID 238 (OID 671997)
--
-- Name: ezwishlist Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 239 (OID 672002)
--
-- Name: ezworkflow Type: TABLE DATA Owner: sp
--


INSERT INTO "ezworkflow" ("id","version","workflow_type_string","name","creator_id","modifier_id","created","modified","is_enabled") VALUES (1,0,'group_ezserial','Sp''s forkflow',8,24,1031927869,1032856662,0);
--
-- Data for TOC Entry ID 240 (OID 672007)
--
-- Name: ezworkflow_assign Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 241 (OID 672012)
--
-- Name: ezworkflow_event Type: TABLE DATA Owner: sp
--


INSERT INTO "ezworkflow_event" ("id","version","workflow_id","workflow_type_string","description","data_int1","data_int2","data_int3","data_int4","data_text1","data_text2","data_text3","data_text4","placement") VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO "ezworkflow_event" ("id","version","workflow_id","workflow_type_string","description","data_int1","data_int2","data_int3","data_int4","data_text1","data_text2","data_text3","data_text4","placement") VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);
--
-- Data for TOC Entry ID 242 (OID 672017)
--
-- Name: ezworkflow_group Type: TABLE DATA Owner: sp
--


INSERT INTO "ezworkflow_group" ("id","name","creator_id","modifier_id","created","modified") VALUES (1,'Standard',-1,-1,1024392098,1024392098);
--
-- Data for TOC Entry ID 243 (OID 672020)
--
-- Name: ezworkflow_group_link Type: TABLE DATA Owner: sp
--


INSERT INTO "ezworkflow_group_link" ("workflow_id","group_id","workflow_version","group_name") VALUES (1,1,0,'Standard');
--
-- Data for TOC Entry ID 244 (OID 672028)
--
-- Name: ezworkflow_process Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 245 (OID 672036)
--
-- Name: ezoperation_memento Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 246 (OID 672044)
--
-- Name: ezdiscountsubrule Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 247 (OID 672047)
--
-- Name: ezdiscountsubrule_value Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 248 (OID 672052)
--
-- Name: ezinfocollection Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 249 (OID 672057)
--
-- Name: ezinfocollection_attribute Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 250 (OID 672065)
--
-- Name: ezuser_discountrule Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 251 (OID 672070)
--
-- Name: ezvattype Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 254 (OID 672083)
--
-- Name: ezdiscountrule Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 255 (OID 672088)
--
-- Name: ezorder_item Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 256 (OID 672091)
--
-- Name: ezcontentobject_name Type: TABLE DATA Owner: sp
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
-- Data for TOC Entry ID 257 (OID 672096)
--
-- Name: ezwaituntildatevalue Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 258 (OID 672101)
--
-- Name: ezcontent_translation Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 259 (OID 672106)
--
-- Name: ezcollab_item Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 260 (OID 672114)
--
-- Name: ezcollab_group Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 261 (OID 672117)
--
-- Name: ezcollab_item_group_link Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 262 (OID 672120)
--
-- Name: ezcollab_item_status Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 263 (OID 672123)
--
-- Name: ezcollab_item_participant_link Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 264 (OID 672128)
--
-- Name: ezcollab_item_message_link Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 265 (OID 672133)
--
-- Name: ezcollab_simple_message Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 266 (OID 672141)
--
-- Name: ezcollab_profile Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 267 (OID 672149)
--
-- Name: ezapprove_items Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 268 (OID 672154)
--
-- Name: ezurl Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 269 (OID 672159)
--
-- Name: ezmessage Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 270 (OID 672299)
--
-- Name: ezproductcollection_item_opt Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 271 (OID 672304)
--
-- Name: ezforgot_password Type: TABLE DATA Owner: sp
--


--
-- TOC Entry ID 179 (OID 672273)
--
-- Name: "ezcontentclass_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentclass_id ON ezcontentclass USING btree (id);

--
-- TOC Entry ID 180 (OID 672274)
--
-- Name: "ezcontentobject_id" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX ezcontentobject_id ON ezcontentobject USING btree (id);

--
-- TOC Entry ID 181 (OID 672275)
--
-- Name: "ezsearch_object_word_link_word" Type: INDEX Owner: sp
--

CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link USING btree (word_id);

--
-- TOC Entry ID 182 (OID 672276)
--
-- Name: "ezsearch_object_word_link_freq" Type: INDEX Owner: sp
--

CREATE INDEX ezsearch_object_word_link_freq ON ezsearch_object_word_link USING btree (frequency);

--
-- TOC Entry ID 183 (OID 672277)
--
-- Name: "ezsearch_word_i" Type: INDEX Owner: sp
--

CREATE INDEX ezsearch_word_i ON ezsearch_word USING btree (word);

--
-- TOC Entry ID 184 (OID 672278)
--
-- Name: "ezcontentobject_tree_path" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_path ON ezcontentobject_tree USING btree (path_string);

--
-- TOC Entry ID 185 (OID 672279)
--
-- Name: "ezcontentobject_tree_p_node_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_p_node_id ON ezcontentobject_tree USING btree (parent_node_id);

--
-- TOC Entry ID 186 (OID 672280)
--
-- Name: "ezcontentobject_tree_co_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_co_id ON ezcontentobject_tree USING btree (contentobject_id);

--
-- TOC Entry ID 187 (OID 672281)
--
-- Name: "ezcontentobject_tree_depth" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_depth ON ezcontentobject_tree USING btree (depth);

--
-- TOC Entry ID 188 (OID 672282)
--
-- Name: "eztrigger_id" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX eztrigger_id ON eztrigger USING btree (id);

--
-- TOC Entry ID 189 (OID 672283)
--
-- Name: "eztrigger_fetch" Type: INDEX Owner: sp
--

CREATE INDEX eztrigger_fetch ON eztrigger USING btree (module_name, function_name, connect_type);

--
-- TOC Entry ID 190 (OID 672284)
--
-- Name: "ezmodule_run_workflow_process_id_s" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s ON ezmodule_run USING btree (workflow_process_id);

--
-- TOC Entry ID 191 (OID 672285)
--
-- Name: "ezcontentobject_tree_crc32_path" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_tree_crc32_path ON ezcontentobject_tree USING btree (crc32_path);

--
-- TOC Entry ID 192 (OID 672286)
--
-- Name: "ezcontentobject_tree_md5_path" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX ezcontentobject_tree_md5_path ON ezcontentobject_tree USING btree (md5_path);

--
-- TOC Entry ID 193 (OID 672287)
--
-- Name: "ezuser_contentobject_id" Type: INDEX Owner: sp
--

CREATE UNIQUE INDEX ezuser_contentobject_id ON ezuser USING btree (contentobject_id);

--
-- TOC Entry ID 194 (OID 672288)
--
-- Name: "ezuser_role_contentobject_id" Type: INDEX Owner: sp
--

CREATE INDEX ezuser_role_contentobject_id ON ezuser_role USING btree (contentobject_id);

--
-- TOC Entry ID 195 (OID 672289)
--
-- Name: "ezcontentobject_attribute_contentobject_id" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_attribute_contentobject_id ON ezcontentobject_attribute USING btree (contentobject_id);

--
-- TOC Entry ID 196 (OID 672290)
--
-- Name: "ezcontentobject_attribute_language_code" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentobject_attribute_language_code ON ezcontentobject_attribute USING btree (language_code);

--
-- TOC Entry ID 197 (OID 672291)
--
-- Name: "ezcontentclass_version" Type: INDEX Owner: sp
--

CREATE INDEX ezcontentclass_version ON ezcontentclass USING btree ("version");

--
-- TOC Entry ID 198 (OID 672292)
--
-- Name: "ezenumvalue_co_cl_attr_id_co_class_att_ver" Type: INDEX Owner: sp
--

CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver ON ezenumvalue USING btree (contentclass_attribute_id, contentclass_attribute_version);

--
-- TOC Entry ID 199 (OID 672293)
--
-- Name: "ezenumobjectvalue_co_attr_id_co_attr_ver" Type: INDEX Owner: sp
--

CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver ON ezenumobjectvalue USING btree (contentobject_attribute_id, contentobject_attribute_version);

--
-- TOC Entry ID 200 (OID 672294)
--
-- Name: "ezwaituntildatevalue_wf_ev_id_wf_ver" Type: INDEX Owner: sp
--

CREATE INDEX ezwaituntildatevalue_wf_ev_id_wf_ver ON ezwaituntildatevalue USING btree (workflow_event_id, workflow_event_version);

--
-- TOC Entry ID 201 (OID 672295)
--
-- Name: "ezcollab_group_path" Type: INDEX Owner: sp
--

CREATE INDEX ezcollab_group_path ON ezcollab_group USING btree (path_string);

--
-- TOC Entry ID 202 (OID 672296)
--
-- Name: "ezcollab_group_dept" Type: INDEX Owner: sp
--

CREATE INDEX ezcollab_group_dept ON ezcollab_group USING btree (depth);

--
-- TOC Entry ID 3 (OID 671814)
--
-- Name: ezapprovetasks_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezapprovetasks_s"', 1, false);

--
-- TOC Entry ID 5 (OID 671816)
--
-- Name: ezbasket_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezbasket_s"', 1, false);

--
-- TOC Entry ID 7 (OID 671824)
--
-- Name: ezcontentclass_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentclass_s"', 6, true);

--
-- TOC Entry ID 9 (OID 671829)
--
-- Name: ezcontentclass_attribute_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentclass_attribute_s"', 124, true);

--
-- TOC Entry ID 11 (OID 671837)
--
-- Name: ezcontentclassgroup_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentclassgroup_s"', 4, true);

--
-- TOC Entry ID 13 (OID 671842)
--
-- Name: ezcontentobject_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentobject_s"', 15, true);

--
-- TOC Entry ID 15 (OID 671847)
--
-- Name: ezcontentobject_attribute_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentobject_attribute_s"', 371, true);

--
-- TOC Entry ID 17 (OID 671855)
--
-- Name: ezcontentobject_link_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentobject_link_s"', 1, false);

--
-- TOC Entry ID 19 (OID 671860)
--
-- Name: ezcontentobject_tree_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentobject_tree_s"', 16, true);

--
-- TOC Entry ID 21 (OID 671868)
--
-- Name: ezcontentobject_version_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontentobject_version_s"', 443, true);

--
-- TOC Entry ID 23 (OID 671876)
--
-- Name: ezenumvalue_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezenumvalue_s"', 1, false);

--
-- TOC Entry ID 25 (OID 671890)
--
-- Name: ezmodule_run_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezmodule_run_s"', 1, false);

--
-- TOC Entry ID 27 (OID 671898)
--
-- Name: eznode_assignment_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"eznode_assignment_s"', 154, true);

--
-- TOC Entry ID 29 (OID 671903)
--
-- Name: ezorder_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezorder_s"', 1, false);

--
-- TOC Entry ID 31 (OID 671911)
--
-- Name: ezpolicy_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezpolicy_s"', 318, true);

--
-- TOC Entry ID 33 (OID 671919)
--
-- Name: ezpolicy_limitation_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezpolicy_limitation_s"', 245, true);

--
-- TOC Entry ID 35 (OID 671927)
--
-- Name: ezpolicy_limitation_value_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezpolicy_limitation_value_s"', 410, true);

--
-- TOC Entry ID 37 (OID 671932)
--
-- Name: ezproductcollection_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezproductcollection_s"', 1, false);

--
-- TOC Entry ID 39 (OID 671937)
--
-- Name: ezproductcollection_item_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezproductcollection_item_s"', 1, false);

--
-- TOC Entry ID 41 (OID 671942)
--
-- Name: ezrole_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezrole_s"', 5, true);

--
-- TOC Entry ID 43 (OID 671950)
--
-- Name: ezsearch_object_word_link_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezsearch_object_word_link_s"', 1, false);

--
-- TOC Entry ID 45 (OID 671955)
--
-- Name: ezsearch_return_count_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezsearch_return_count_s"', 1, false);

--
-- TOC Entry ID 47 (OID 671960)
--
-- Name: ezsearch_search_phrase_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezsearch_search_phrase_s"', 1, false);

--
-- TOC Entry ID 49 (OID 671965)
--
-- Name: ezsearch_word_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezsearch_word_s"', 1, false);

--
-- TOC Entry ID 51 (OID 671970)
--
-- Name: ezsection_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezsection_s"', 2, true);

--
-- TOC Entry ID 53 (OID 671981)
--
-- Name: eztrigger_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"eztrigger_s"', 1, false);

--
-- TOC Entry ID 55 (OID 671987)
--
-- Name: ezuser_role_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezuser_role_s"', 26, false);

--
-- TOC Entry ID 57 (OID 671995)
--
-- Name: ezwishlist_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezwishlist_s"', 1, false);

--
-- TOC Entry ID 59 (OID 672000)
--
-- Name: ezworkflow_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezworkflow_s"', 2, false);

--
-- TOC Entry ID 61 (OID 672005)
--
-- Name: ezworkflow_assign_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezworkflow_assign_s"', 1, false);

--
-- TOC Entry ID 63 (OID 672010)
--
-- Name: ezworkflow_event_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezworkflow_event_s"', 3, false);

--
-- TOC Entry ID 65 (OID 672015)
--
-- Name: ezworkflow_group_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezworkflow_group_s"', 2, false);

--
-- TOC Entry ID 67 (OID 672026)
--
-- Name: ezworkflow_process_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezworkflow_process_s"', 1, false);

--
-- TOC Entry ID 69 (OID 672034)
--
-- Name: ezoperation_memento_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezoperation_memento_s"', 1, false);

--
-- TOC Entry ID 71 (OID 672042)
--
-- Name: ezdiscountsubrule_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezdiscountsubrule_s"', 1, false);

--
-- TOC Entry ID 73 (OID 672050)
--
-- Name: ezinfocollection_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezinfocollection_s"', 1, false);

--
-- TOC Entry ID 75 (OID 672055)
--
-- Name: ezinfocollection_attribute_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezinfocollection_attribute_s"', 1, false);

--
-- TOC Entry ID 77 (OID 672063)
--
-- Name: ezuser_discountrule_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezuser_discountrule_s"', 1, false);

--
-- TOC Entry ID 79 (OID 672068)
--
-- Name: ezvattype_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezvattype_s"', 1, false);

--
-- TOC Entry ID 83 (OID 672081)
--
-- Name: ezdiscountrule_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezdiscountrule_s"', 1, false);

--
-- TOC Entry ID 85 (OID 672086)
--
-- Name: ezorder_item_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezorder_item_s"', 1, false);

--
-- TOC Entry ID 87 (OID 672094)
--
-- Name: ezwaituntildatevalue_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezwaituntildatevalue_s"', 1, false);

--
-- TOC Entry ID 89 (OID 672099)
--
-- Name: ezcontent_translation_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcontent_translation_s"', 1, false);

--
-- TOC Entry ID 91 (OID 672104)
--
-- Name: ezcollab_item_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcollab_item_s"', 1, false);

--
-- TOC Entry ID 93 (OID 672112)
--
-- Name: ezcollab_group_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcollab_group_s"', 1, false);

--
-- TOC Entry ID 95 (OID 672126)
--
-- Name: ezcollab_item_message_link_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcollab_item_message_link_s"', 1, false);

--
-- TOC Entry ID 97 (OID 672131)
--
-- Name: ezcollab_simple_message_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcollab_simple_message_s"', 1, false);

--
-- TOC Entry ID 99 (OID 672139)
--
-- Name: ezcollab_profile_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezcollab_profile_s"', 1, false);

--
-- TOC Entry ID 101 (OID 672147)
--
-- Name: ezapprove_items_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezapprove_items_s"', 1, false);

--
-- TOC Entry ID 103 (OID 672152)
--
-- Name: ezurl_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezurl_s"', 1, false);

--
-- TOC Entry ID 105 (OID 672157)
--
-- Name: ezmessage_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezmessage_s"', 1, false);

--
-- TOC Entry ID 107 (OID 672297)
--
-- Name: ezproductcollection_item_opt_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezproductcollection_item_opt_s"', 1, false);

--
-- TOC Entry ID 109 (OID 672302)
--
-- Name: ezforgot_password_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"ezforgot_password_s"', 1, false);

