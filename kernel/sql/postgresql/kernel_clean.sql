








CREATE SEQUENCE "ezapprovetasks_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE SEQUENCE "ezbasket_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezbasket" (
	"id" integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
	"session_id" character varying(255) NOT NULL,
	"productcollection_id" integer NOT NULL,
	Constraint "ezbasket_pkey" Primary Key ("id")
);







CREATE TABLE "ezbinaryfile" (
	"contentobject_attribute_id" integer NOT NULL,
	"version" integer NOT NULL,
	"filename" character varying(255) NOT NULL,
	"original_filename" character varying(255) NOT NULL,
	"mime_type" character varying(50) NOT NULL,
	Constraint "ezbinaryfile_pkey" Primary Key ("contentobject_attribute_id", "version")
);







CREATE SEQUENCE "ezcontentclass_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezcontentclass_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE TABLE "ezcontentclass_classgroup" (
	"contentclass_id" integer NOT NULL,
	"contentclass_version" integer NOT NULL,
	"group_id" integer NOT NULL,
	"group_name" character varying(255),
	Constraint "ezcontentclass_classgroup_pkey" Primary Key ("contentclass_id", "contentclass_version", "group_id")
);







CREATE SEQUENCE "ezcontentclassgroup_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezcontentclassgroup" (
	"id" integer DEFAULT nextval('ezcontentclassgroup_s'::text) NOT NULL,
	"name" character varying(255),
	"creator_id" integer NOT NULL,
	"modifier_id" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	Constraint "ezcontentclassgroup_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezcontentobject_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezcontentobject_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezcontentobject_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezcontentobject_link" (
	"id" integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
	"from_contentobject_id" integer NOT NULL,
	"from_contentobject_version" integer NOT NULL,
	"to_contentobject_id" integer,
	Constraint "ezcontentobject_link_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezcontentobject_tree_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezcontentobject_version_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE TABLE "ezenumobjectvalue" (
	"contentobject_attribute_id" integer NOT NULL,
	"contentobject_attribute_version" integer NOT NULL,
	"enumid" integer NOT NULL,
	"enumelement" character varying(255) NOT NULL,
	"enumvalue" character varying(255) NOT NULL,
	Constraint "ezenumobjectvalue_pkey" Primary Key ("contentobject_attribute_id", "contentobject_attribute_version", "enumid")
);







CREATE SEQUENCE "ezenumvalue_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezenumvalue" (
	"id" integer DEFAULT nextval('ezenumvalue_s'::text) NOT NULL,
	"contentclass_attribute_id" integer NOT NULL,
	"contentclass_attribute_version" integer NOT NULL,
	"enumelement" character varying(255) NOT NULL,
	"enumvalue" character varying(255) NOT NULL,
	"placement" integer NOT NULL,
	Constraint "ezenumvalue_pkey" Primary Key ("id", "contentclass_attribute_id", "contentclass_attribute_version")
);







CREATE TABLE "ezimage" (
	"contentobject_attribute_id" integer NOT NULL,
	"version" integer NOT NULL,
	"filename" character varying(255) NOT NULL,
	"original_filename" character varying(255) NOT NULL,
	"mime_type" character varying(50) NOT NULL,
	"alternative_text" character varying(255) DEFAULT '' NOT NULL,
	Constraint "ezimage_pkey" Primary Key ("contentobject_attribute_id", "version")
);







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







CREATE SEQUENCE "ezmodule_run_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezmodule_run" (
	"id" integer DEFAULT nextval('ezmodule_run_s'::text) NOT NULL,
	"workflow_process_id" integer,
	"module_name" character varying(255),
	"function_name" character varying(255),
	"module_data" text,
	Constraint "ezmodule_run_pkey" Primary Key ("id")
);







CREATE SEQUENCE "eznode_assignment_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezorder_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezpolicy_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezpolicy" (
	"id" integer DEFAULT nextval('ezpolicy_s'::text) NOT NULL,
	"role_id" integer,
	"function_name" character varying,
	"module_name" character varying,
	"limitation" character(1),
	Constraint "ezpolicy_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezpolicy_limitation_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezpolicy_limitation" (
	"id" integer DEFAULT nextval('ezpolicy_limitation_s'::text) NOT NULL,
	"policy_id" integer,
	"identifier" character varying NOT NULL,
	"role_id" integer,
	"function_name" character varying,
	"module_name" character varying,
	Constraint "ezpolicy_limitation_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezpolicy_limitation_value_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezpolicy_limitation_value" (
	"id" integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
	"limitation_id" integer,
	"value" integer,
	Constraint "ezpolicy_limitation_value_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezproductcollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezproductcollection" (
	"id" integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
	"created" integer,
	Constraint "ezproductcollection_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezproductcollection_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezrole_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezrole" (
	"id" integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
	"version" integer DEFAULT '0',
	"name" character varying NOT NULL,
	"value" character(1),
	Constraint "ezrole_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezsearch_object_word_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezsearch_return_count_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezsearch_return_count" (
	"id" integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
	"phrase_id" integer NOT NULL,
	"time" integer NOT NULL,
	"count" integer NOT NULL,
	Constraint "ezsearch_return_count_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezsearch_search_phrase_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezsearch_search_phrase" (
	"id" integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
	"phrase" character varying(250),
	Constraint "ezsearch_search_phrase_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezsearch_word_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezsearch_word" (
	"id" integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
	"word" character varying(150),
	"object_count" integer NOT NULL,
	Constraint "ezsearch_word_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezsection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezsection" (
	"id" integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
	"name" character varying(255),
	"locale" character varying(255),
	"navigation_part_identifier" character varying(100) DEFAULT 'ezcontentnavigationpart',
	Constraint "ezsection_pkey" Primary Key ("id")
);







CREATE TABLE "ezsession" (
	"session_key" character(32) NOT NULL,
	"expiration_time" integer NOT NULL,
	"data" text NOT NULL,
	Constraint "ezsession_pkey" Primary Key ("session_key")
);







CREATE SEQUENCE "eztrigger_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "eztrigger" (
	"id" integer DEFAULT nextval('eztrigger_s'::text),
	"name" character varying(255),
	"module_name" character varying(255) NOT NULL,
	"function_name" character varying(255) NOT NULL,
	"connect_type" character(1) NOT NULL,
	"workflow_id" integer
);







CREATE TABLE "ezuser" (
	"contentobject_id" integer NOT NULL,
	"login" character varying(150) NOT NULL,
	"email" character varying(150) NOT NULL,
	"password_hash_type" integer DEFAULT 1 NOT NULL,
	"password_hash" character varying(50)
);







CREATE SEQUENCE "ezuser_role_s" start 26 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezuser_role" (
	"id" integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
	"role_id" integer,
	"contentobject_id" integer,
	Constraint "ezuser_role_pkey" Primary Key ("id")
);







CREATE TABLE "ezuser_setting" (
	"user_id" integer DEFAULT '0' NOT NULL,
	"is_enabled" smallint DEFAULT '0' NOT NULL,
	"max_login" integer,
	Constraint "ezuser_setting_pkey" Primary Key ("user_id")
);







CREATE SEQUENCE "ezwishlist_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezwishlist" (
	"id" integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"productcollection_id" integer NOT NULL,
	Constraint "ezwishlist_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezworkflow_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezworkflow_assign_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezworkflow_assign" (
	"id" integer DEFAULT nextval('ezworkflow_assign_s'::text) NOT NULL,
	"workflow_id" integer NOT NULL,
	"node_id" integer NOT NULL,
	"access_type" integer NOT NULL,
	"as_tree" integer NOT NULL,
	Constraint "ezworkflow_assign_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezworkflow_event_s" start 3 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezworkflow_group_s" start 2 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezworkflow_group" (
	"id" integer DEFAULT nextval('ezworkflow_group_s'::text) NOT NULL,
	"name" character varying(255) NOT NULL,
	"creator_id" integer NOT NULL,
	"modifier_id" integer NOT NULL,
	"created" integer NOT NULL,
	"modified" integer NOT NULL,
	Constraint "ezworkflow_group_pkey" Primary Key ("id")
);







CREATE TABLE "ezworkflow_group_link" (
	"workflow_id" integer DEFAULT '0' NOT NULL,
	"group_id" integer DEFAULT '0' NOT NULL,
	"workflow_version" integer DEFAULT '0' NOT NULL,
	"group_name" character varying,
	Constraint "ezworkflow_group_link_pkey" Primary Key ("workflow_id", "group_id", "workflow_version")
);







CREATE SEQUENCE "ezworkflow_process_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezoperation_memento_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezoperation_memento" (
	"id" integer DEFAULT nextval('ezoperation_memento_s'::text) NOT NULL,
	"main" integer DEFAULT 0 NOT NULL,
	"memento_key" character(32) NOT NULL,
	"main_key" character(32) NOT NULL,
	"memento_data" text NOT NULL,
	Constraint "ezoperation_memento_pkey" Primary Key ("id", "memento_key")
);







CREATE SEQUENCE "ezdiscountsubrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezdiscountsubrule" (
	"id" integer DEFAULT nextval('ezdiscountsubrule_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"discountrule_id" integer DEFAULT '0' NOT NULL,
	"discount_percent" double precision,
	"limitation" character(1),
	Constraint "ezdiscountsubrule_pkey" Primary Key ("id")
);







CREATE TABLE "ezdiscountsubrule_value" (
	"discountsubrule_id" integer DEFAULT '0' NOT NULL,
	"value" integer DEFAULT '0' NOT NULL,
	"issection" integer DEFAULT '0' NOT NULL,
	Constraint "ezdiscountsubrule_value_pkey" Primary Key ("discountsubrule_id", "value", "issection")
);







CREATE SEQUENCE "ezinfocollection_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezinfocollection" (
	"id" integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
	"contentobject_id" integer DEFAULT '0' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	Constraint "ezinfocollection_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezinfocollection_attribute_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezinfocollection_attribute" (
	"id" integer DEFAULT nextval('ezinfocollection_attribute_s'::text) NOT NULL,
	"informationcollection_id" integer DEFAULT 0 NOT NULL,
	"data_text" text,
	"data_int" integer,
	"data_float" double precision,
	"contentclass_attribute_id" integer,
	Constraint "ezinfocollection_attribute_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezuser_discountrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezuser_discountrule" (
	"id" integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
	"discountrule_id" integer,
	"contentobject_id" integer,
	"name" character varying(255) DEFAULT '' NOT NULL,
	Constraint "ezuser_discountrule_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezvattype_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezvattype" (
	"id" integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"percentage" double precision,
	Constraint "ezvattype_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezdiscountrule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezdiscountrule" (
	"id" integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
	"name" character varying(255) NOT NULL,
	Constraint "ezdiscountrule_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezorder_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezorder_item" (
	"id" integer DEFAULT nextval('ezorder_item_s'::text) NOT NULL,
	"order_id" integer NOT NULL,
	"description" character varying(255),
	"price" double precision,
	"vat_value" integer DEFAULT '0' NOT NULL,
	Constraint "ezorder_item_pkey" Primary Key ("id")
);







CREATE TABLE "ezcontentobject_name" (
	"contentobject_id" integer NOT NULL,
	"name" character varying(255),
	"content_version" integer NOT NULL,
	"content_translation" character varying(20) NOT NULL,
	"real_translation" character varying(20),
	Constraint "ezcontentobject_name_pkey" Primary Key ("contentobject_id", "content_version", "content_translation")
);







CREATE SEQUENCE "ezwaituntildatevalue_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezwaituntildatevalue" (
	"id" integer DEFAULT nextval('ezwaituntildatevalue_s'::text) NOT NULL,
	"workflow_event_id" integer DEFAULT '0' NOT NULL,
	"workflow_event_version" integer DEFAULT '0' NOT NULL,
	"contentclass_id" integer DEFAULT '0' NOT NULL,
	"contentclass_attribute_id" integer DEFAULT '0' NOT NULL,
	Constraint "ezwaituntildatevalue_pkey" Primary Key ("id", "workflow_event_id", "workflow_event_version")
);







CREATE SEQUENCE "ezcontent_translation_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezcontent_translation" (
	"id" integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
	"name" character varying(255) DEFAULT '' NOT NULL,
	"locale" character varying(255) NOT NULL,
	Constraint "ezcontent_translation_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezcollab_item_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezcollab_group_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE TABLE "ezcollab_item_status" (
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"is_read" integer DEFAULT '0' NOT NULL,
	"is_active" integer DEFAULT '1' NOT NULL,
	"last_read" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_item_status_pkey" Primary Key ("collaboration_id", "user_id")
);







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







CREATE SEQUENCE "ezcollab_item_message_link_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezcollab_simple_message_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezcollab_profile_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezcollab_profile" (
	"id" integer DEFAULT nextval('ezcollab_profile_s'::text) NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"main_group" integer DEFAULT '0' NOT NULL,
	"data_text1" text DEFAULT '' NOT NULL,
	"created" integer DEFAULT '0' NOT NULL,
	"modified" integer DEFAULT '0' NOT NULL,
	Constraint "ezcollab_profile_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezapprove_items_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezapprove_items" (
	"id" integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
	"workflow_process_id" integer DEFAULT '0' NOT NULL,
	"collaboration_id" integer DEFAULT '0' NOT NULL,
	Constraint "ezapprove_items_pkey" Primary Key ("id")
);







CREATE SEQUENCE "ezurl_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezmessage_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezproductcollection_item_opt_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







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







CREATE SEQUENCE "ezforgot_password_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;







CREATE TABLE "ezforgot_password" (
	"id" integer DEFAULT nextval('ezforgot_password_s'::text) NOT NULL,
	"user_id" integer NOT NULL,
	"hash_key" character varying(32) NOT NULL,
	"time" integer NOT NULL,
	Constraint "ezforgot_password_pkey" Primary Key ("id")
);






















INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (3,0,'User group','user_group','<name>',-1,14,1024392098,1033922064);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (4,0,'User','user','<first_name> <last_name>',-1,14,1024392098,1033922083);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (1,0,'Folder','folder','<name>',-1,14,1024392098,1048520596);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (2,0,'Article','article','<title>',-1,14,1024392098,1048520632);
INSERT INTO "ezcontentclass" ("id","version","name","identifier","contentobject_name","creator_id","modifier_id","created","modified") VALUES (5,0,'Image','image','<name>',8,14,1031484992,1048520714);







INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (7,0,3,'description','Description','ezstring',2,1,0,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (6,0,3,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (9,0,4,'last_name','Last name','ezstring',2,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (8,0,4,'first_name','First name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (12,0,4,'user_account','User account','ezuser',3,1,1,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (4,0,1,'name','Name','ezstring',1,1,1,255,0,0,0,0,0,0,0,'Folder','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (119,0,1,'description','Description','ezxmltext',2,1,0,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (1,0,2,'title','Title','ezstring',1,1,1,255,0,0,0,0,0,0,0,'New article','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (120,0,2,'intro','Intro','ezxmltext',2,1,1,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (121,0,2,'body','Body','ezxmltext',3,1,0,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (122,0,2,'thumbnail','Thumbnail','ezimage',4,0,0,2,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (123,0,2,'enable_comments','Enable comments','ezboolean',5,0,0,0,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (116,0,5,'name','Name','ezstring',1,1,1,150,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (117,0,5,'caption','Caption','ezxmltext',2,1,0,10,0,0,0,0,0,0,0,'','','','',0);
INSERT INTO "ezcontentclass_attribute" ("id","version","contentclass_id","identifier","name","data_type_string","placement","is_searchable","is_required","data_int1","data_int2","data_int3","data_int4","data_float1","data_float2","data_float3","data_float4","data_text1","data_text2","data_text3","data_text4","is_information_collector") VALUES (118,0,5,'image','Image','ezimage',3,0,0,2,0,0,0,0,0,0,0,'','','','',0);







INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (4,0,2,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (3,0,2,'');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (1,0,1,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (2,0,1,'Content');
INSERT INTO "ezcontentclass_classgroup" ("contentclass_id","contentclass_version","group_id","group_name") VALUES (5,0,3,'Media');







INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (1,'Content',1,14,1031216928,1033922106);
INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (2,'Users',1,14,1031216941,1033922113);
INSERT INTO "ezcontentclassgroup" ("id","name","creator_id","modifier_id","created","modified") VALUES (3,'Media',8,14,1032009743,1033922120);







INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (1,0,1,1,'Frontpage',1,0,1033917596,1033917596,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (4,0,2,3,'Users',1,0,0,0,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (10,8,2,4,'Anonymous User',1,0,1033920665,1033920665,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (11,8,2,3,'Guest accounts',1,0,1033920746,1033920746,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (12,8,2,3,'Administrator users',1,0,1033920775,1033920775,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (13,8,2,3,'Editors',1,0,1033920794,1033920794,1,'');
INSERT INTO "ezcontentobject" ("id","owner_id","section_id","contentclass_id","name","current_version","is_published","published","modified","status","remote_id") VALUES (14,8,2,4,'Administrator User',1,0,1033920830,1033920830,1,'');







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














INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (1,1,1,0,1,1,NULL,0,'/1/',NULL,1,1,0,NULL);
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (2,2,1,1,1,1,0,1,'/1/2/','',1,1,0,'d41d8cd98f00b204e9800998ecf8427e');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (5,5,1,4,1,0,-195235522,1,'/1/5/','__1',1,1,0,'08a9d0bbf3381652f7cca8738b5a8469');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (11,11,5,10,1,1,1015610524,2,'/1/5/11/','__1/anonymous_user',1,1,0,'a59d2313b486e0f43477433525edea9b');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (12,12,5,11,1,1,1857785444,2,'/1/5/12/','__1/guest_accounts',1,1,0,'c894997127008ea742913062f39adfc5');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (13,13,5,12,1,1,-1978139175,2,'/1/5/13/','__1/administrator_users',1,1,0,'caeccbc33185f04d92e2b6cb83b1c7e4');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (15,15,13,14,1,1,-852704961,3,'/1/5/13/15/','__1/administrator_users/administrator_user',1,1,0,'2c3f2814cfa91bcb17d7893ca6f8a0c4');
INSERT INTO "ezcontentobject_tree" ("node_id","main_node_id","parent_node_id","contentobject_id","contentobject_version","contentobject_is_published","crc32_path","depth","path_string","path_identification_string","sort_field","sort_order","priority","md5_path") VALUES (14,14,5,13,1,1,2094553782,2,'/1/5/14/','__1/editors',1,1,0,'39f6f6f51c1e3a922600b2d415d7a46d');







INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (436,1,8,1,1033919080,1033919080,1,1,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (4,4,8,1,1033919080,1033919080,1,1,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (438,10,8,1,1033920649,1033920665,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (439,11,8,1,1033920737,1033920746,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (440,12,8,1,1033920760,1033920775,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (441,13,8,1,1033920786,1033920794,0,0,0,NULL);
INSERT INTO "ezcontentobject_version" ("id","contentobject_id","creator_id","version","created","modified","status","workflow_event_pos","user_id","main_node_id") VALUES (442,14,8,1,1033920808,1033920830,0,0,0,NULL);

















































INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (2,1,1,1,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (3,4,1,1,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (148,9,1,2,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (149,10,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (150,11,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (151,12,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (152,13,1,5,1,1,1,0,NULL);
INSERT INTO "eznode_assignment" ("id","contentobject_id","contentobject_version","parent_node","is_main","sort_field","sort_order","from_node_id","remote_id") VALUES (153,14,1,13,1,1,1,0,NULL);














INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (308,2,'*','*','*');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (315,1,'read','content',' ');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (316,1,'login','user','*');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (317,3,'*','content','*');
INSERT INTO "ezpolicy" ("id","role_id","function_name","module_name","limitation") VALUES (318,3,'login','user','*');







INSERT INTO "ezpolicy_limitation" ("id","policy_id","identifier","role_id","function_name","module_name") VALUES (245,315,'Class',0,'read','content');







INSERT INTO "ezpolicy_limitation_value" ("id","limitation_id","value") VALUES (409,245,1);
INSERT INTO "ezpolicy_limitation_value" ("id","limitation_id","value") VALUES (410,245,7);





















INSERT INTO "ezrole" ("id","version","name","value") VALUES (2,0,'Administrator','*');
INSERT INTO "ezrole" ("id","version","name","value") VALUES (1,0,'Anonymous',' ');
INSERT INTO "ezrole" ("id","version","name","value") VALUES (3,0,'Editor',' ');



































INSERT INTO "ezsection" ("id","name","locale","navigation_part_identifier") VALUES (1,'Standard section','nor-NO','ezcontentnavigationpart');
INSERT INTO "ezsection" ("id","name","locale","navigation_part_identifier") VALUES (2,'Users section','','ezusernavigationpart');







INSERT INTO "ezsession" ("session_key","expiration_time","data") VALUES ('7a244467fd70e3ec35bb977be0b1dc6a',1048773857,'LastAccessesURI|s:20:"/workflow/grouplist/";eZUserInfoCache_Timestamp|i:1048514591;eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_Timestamp|i:1048514591;eZUserGroupsCache_10|a:1:{i:0;a:2:{i:0;s:1:"4";s:2:"id";s:1:"4";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1048514591;UserRoles|a:1:{i:0;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:13:"Administrator";s:4:"name";s:13:"Administrator";}}canInstantiateClassesCachedForUser|s:2:"14";classesCachedTimestamp|i:1048514624;canInstantiateClasses|i:1;eZUserLoggedInID|s:2:"14";eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_14|a:1:{i:0;a:2:{i:0;s:2:"12";s:2:"id";s:2:"12";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}BrowseFromPage|s:18:"/section/assign/2/";BrowseActionName|s:13:"AssignSection";BrowseReturnType|s:6:"NodeID";CustomActionButton|N;BrowseSelectionType|N;canInstantiateClassList|a:5:{i:0;a:4:{i:0;s:1:"1";s:2:"id";s:1:"1";i:1;s:6:"Folder";s:4:"name";s:6:"Folder";}i:1;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:7:"Article";s:4:"name";s:7:"Article";}i:2;a:4:{i:0;s:1:"3";s:2:"id";s:1:"3";i:1;s:10:"User group";s:4:"name";s:10:"User group";}i:3;a:4:{i:0;s:1:"4";s:2:"id";s:1:"4";i:1;s:4:"User";s:4:"name";s:4:"User";}i:4;a:4:{i:0;s:1:"5";s:2:"id";s:1:"5";i:1;s:5:"Image";s:4:"name";s:5:"Image";}}eZUserDiscountRulesTimestamp|i:1048514636;eZUserDiscountRules14|a:0:{}eZGlobalSection|a:1:{s:2:"id";s:1:"2";}');
INSERT INTO "ezsession" ("session_key","expiration_time","data") VALUES ('74791676e2ee2281d335a9aac6d8c752',1048779918,'LastAccessesURI|s:19:"/class/classlist/3/";eZUserInfoCache_10|a:5:{s:16:"contentobject_id";s:2:"10";s:5:"login";s:9:"anonymous";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"4e6f6184135228ccd45f8233d72a0363";s:18:"password_hash_type";s:1:"2";}eZUserInfoCache_Timestamp|i:1048520517;!eZUserGroupsCache_10|eZUserGroupsCache_Timestamp|i:1048520517;UserRoles|a:1:{i:0;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:13:"Administrator";s:4:"name";s:13:"Administrator";}}PermissionCachedForUserID|s:2:"14";PermissionCachedForUserIDTimestamp|i:1048520517;!eZUserDiscountRules10|eZUserDiscountRulesTimestamp|i:1048520517;eZGlobalSection|a:1:{s:2:"id";s:1:"1";}canInstantiateClassesCachedForUser|s:2:"14";canInstantiateClasses|i:1;eZUserLoggedInID|s:2:"14";eZUserInfoCache_14|a:5:{s:16:"contentobject_id";s:2:"14";s:5:"login";s:5:"admin";s:5:"email";s:12:"nospam@ez.no";s:13:"password_hash";s:32:"c78e3b0f3d9244ed8c6d1c29464bdff9";s:18:"password_hash_type";s:1:"2";}eZUserGroupsCache_14|a:1:{i:0;a:2:{i:0;s:2:"12";s:2:"id";s:2:"12";}}eZUserDiscountRules14|a:0:{}classesCachedForUser|s:2:"14";classesCachedTimestamp|i:1048520517;canInstantiateClassList|a:5:{i:0;a:4:{i:0;s:1:"1";s:2:"id";s:1:"1";i:1;s:6:"Folder";s:4:"name";s:6:"Folder";}i:1;a:4:{i:0;s:1:"2";s:2:"id";s:1:"2";i:1;s:7:"Article";s:4:"name";s:7:"Article";}i:2;a:4:{i:0;s:1:"3";s:2:"id";s:1:"3";i:1;s:10:"User group";s:4:"name";s:10:"User group";}i:3;a:4:{i:0;s:1:"4";s:2:"id";s:1:"4";i:1;s:4:"User";s:4:"name";s:4:"User";}i:4;a:4:{i:0;s:1:"5";s:2:"id";s:1:"5";i:1;s:5:"Image";s:4:"name";s:5:"Image";}}UserPolicies|a:1:{i:2;a:1:{i:0;a:5:{s:2:"id";s:3:"308";s:7:"role_id";s:1:"2";s:11:"module_name";s:1:"*";s:13:"function_name";s:1:"*";s:10:"limitation";s:1:"*";}}}FromGroupID|s:1:"3";');














INSERT INTO "ezuser" ("contentobject_id","login","email","password_hash_type","password_hash") VALUES (10,'anonymous','nospam@ez.no',2,'4e6f6184135228ccd45f8233d72a0363');
INSERT INTO "ezuser" ("contentobject_id","login","email","password_hash_type","password_hash") VALUES (14,'admin','nospam@ez.no',2,'c78e3b0f3d9244ed8c6d1c29464bdff9');







INSERT INTO "ezuser_role" ("id","role_id","contentobject_id") VALUES (24,1,4);
INSERT INTO "ezuser_role" ("id","role_id","contentobject_id") VALUES (25,2,12);







INSERT INTO "ezuser_setting" ("user_id","is_enabled","max_login") VALUES (10,1,1000);
INSERT INTO "ezuser_setting" ("user_id","is_enabled","max_login") VALUES (14,1,10);














INSERT INTO "ezworkflow" ("id","version","workflow_type_string","name","creator_id","modifier_id","created","modified","is_enabled") VALUES (1,0,'group_ezserial','Sp''s forkflow',8,24,1031927869,1032856662,0);














INSERT INTO "ezworkflow_event" ("id","version","workflow_id","workflow_type_string","description","data_int1","data_int2","data_int3","data_int4","data_text1","data_text2","data_text3","data_text4","placement") VALUES (18,0,1,'event_ezapprove','3333333333',0,0,0,0,'','','','',1);
INSERT INTO "ezworkflow_event" ("id","version","workflow_id","workflow_type_string","description","data_int1","data_int2","data_int3","data_int4","data_text1","data_text2","data_text3","data_text4","placement") VALUES (20,0,1,'event_ezmessage','foooooo',0,0,0,0,'eeeeeeeeeeeeeeeeee','','','',2);







INSERT INTO "ezworkflow_group" ("id","name","creator_id","modifier_id","created","modified") VALUES (1,'Standard',-1,-1,1024392098,1024392098);







INSERT INTO "ezworkflow_group_link" ("workflow_id","group_id","workflow_version","group_name") VALUES (1,1,0,'Standard');












































































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















































































































CREATE INDEX ezcontentclass_id ON ezcontentclass USING btree (id);







CREATE UNIQUE INDEX ezcontentobject_id ON ezcontentobject USING btree (id);







CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link USING btree (word_id);







CREATE INDEX ezsearch_object_word_link_freq ON ezsearch_object_word_link USING btree (frequency);







CREATE INDEX ezsearch_word_i ON ezsearch_word USING btree (word);







CREATE INDEX ezcontentobject_tree_path ON ezcontentobject_tree USING btree (path_string);







CREATE INDEX ezcontentobject_tree_p_node_id ON ezcontentobject_tree USING btree (parent_node_id);







CREATE INDEX ezcontentobject_tree_co_id ON ezcontentobject_tree USING btree (contentobject_id);







CREATE INDEX ezcontentobject_tree_depth ON ezcontentobject_tree USING btree (depth);







CREATE UNIQUE INDEX eztrigger_id ON eztrigger USING btree (id);







CREATE INDEX eztrigger_fetch ON eztrigger USING btree (module_name, function_name, connect_type);







CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s ON ezmodule_run USING btree (workflow_process_id);







CREATE INDEX ezcontentobject_tree_crc32_path ON ezcontentobject_tree USING btree (crc32_path);







CREATE UNIQUE INDEX ezcontentobject_tree_md5_path ON ezcontentobject_tree USING btree (md5_path);







CREATE UNIQUE INDEX ezuser_contentobject_id ON ezuser USING btree (contentobject_id);







CREATE INDEX ezuser_role_contentobject_id ON ezuser_role USING btree (contentobject_id);







CREATE INDEX ezcontentobject_attribute_contentobject_id ON ezcontentobject_attribute USING btree (contentobject_id);







CREATE INDEX ezcontentobject_attribute_language_code ON ezcontentobject_attribute USING btree (language_code);







CREATE INDEX ezcontentclass_version ON ezcontentclass USING btree ("version");







CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver ON ezenumvalue USING btree (contentclass_attribute_id, contentclass_attribute_version);







CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver ON ezenumobjectvalue USING btree (contentobject_attribute_id, contentobject_attribute_version);







CREATE INDEX ezwaituntildatevalue_wf_ev_id_wf_ver ON ezwaituntildatevalue USING btree (workflow_event_id, workflow_event_version);







CREATE INDEX ezcollab_group_path ON ezcollab_group USING btree (path_string);







CREATE INDEX ezcollab_group_dept ON ezcollab_group USING btree (depth);







SELECT setval ('"ezapprovetasks_s"', 1, false);







SELECT setval ('"ezbasket_s"', 1, false);







SELECT setval ('"ezcontentclass_s"', 6, true);







SELECT setval ('"ezcontentclass_attribute_s"', 124, true);







SELECT setval ('"ezcontentclassgroup_s"', 4, true);







SELECT setval ('"ezcontentobject_s"', 15, true);







SELECT setval ('"ezcontentobject_attribute_s"', 371, true);







SELECT setval ('"ezcontentobject_link_s"', 1, false);







SELECT setval ('"ezcontentobject_tree_s"', 16, true);







SELECT setval ('"ezcontentobject_version_s"', 443, true);







SELECT setval ('"ezenumvalue_s"', 1, false);







SELECT setval ('"ezmodule_run_s"', 1, false);







SELECT setval ('"eznode_assignment_s"', 154, true);







SELECT setval ('"ezorder_s"', 1, false);







SELECT setval ('"ezpolicy_s"', 318, true);







SELECT setval ('"ezpolicy_limitation_s"', 245, true);







SELECT setval ('"ezpolicy_limitation_value_s"', 410, true);







SELECT setval ('"ezproductcollection_s"', 1, false);







SELECT setval ('"ezproductcollection_item_s"', 1, false);







SELECT setval ('"ezrole_s"', 5, true);







SELECT setval ('"ezsearch_object_word_link_s"', 1, false);







SELECT setval ('"ezsearch_return_count_s"', 1, false);







SELECT setval ('"ezsearch_search_phrase_s"', 1, false);







SELECT setval ('"ezsearch_word_s"', 1, false);







SELECT setval ('"ezsection_s"', 2, true);







SELECT setval ('"eztrigger_s"', 1, false);







SELECT setval ('"ezuser_role_s"', 26, false);







SELECT setval ('"ezwishlist_s"', 1, false);







SELECT setval ('"ezworkflow_s"', 2, false);







SELECT setval ('"ezworkflow_assign_s"', 1, false);







SELECT setval ('"ezworkflow_event_s"', 3, false);







SELECT setval ('"ezworkflow_group_s"', 2, false);







SELECT setval ('"ezworkflow_process_s"', 1, false);







SELECT setval ('"ezoperation_memento_s"', 1, false);







SELECT setval ('"ezdiscountsubrule_s"', 1, false);







SELECT setval ('"ezinfocollection_s"', 1, false);







SELECT setval ('"ezinfocollection_attribute_s"', 1, false);







SELECT setval ('"ezuser_discountrule_s"', 1, false);







SELECT setval ('"ezvattype_s"', 1, false);







SELECT setval ('"ezdiscountrule_s"', 1, false);







SELECT setval ('"ezorder_item_s"', 1, false);







SELECT setval ('"ezwaituntildatevalue_s"', 1, false);







SELECT setval ('"ezcontent_translation_s"', 1, false);







SELECT setval ('"ezcollab_item_s"', 1, false);







SELECT setval ('"ezcollab_group_s"', 1, false);







SELECT setval ('"ezcollab_item_message_link_s"', 1, false);







SELECT setval ('"ezcollab_simple_message_s"', 1, false);







SELECT setval ('"ezcollab_profile_s"', 1, false);







SELECT setval ('"ezapprove_items_s"', 1, false);







SELECT setval ('"ezurl_s"', 1, false);







SELECT setval ('"ezmessage_s"', 1, false);







SELECT setval ('"ezproductcollection_item_opt_s"', 1, false);







SELECT setval ('"ezforgot_password_s"', 1, false);

