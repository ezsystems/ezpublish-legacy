CREATE SEQUENCE ezapprove_items_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezapprove_items (
	id integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
	workflow_process_id integer(11) NOT NULL ,
	collaboration_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezapprove_items ADD CONSTRAINT ezapprove_items12_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezbasket_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezbasket (
	id integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
	session_id character varying(255) DEFAULT '' NOT NULL ,
	productcollection_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezbasket ADD CONSTRAINT ezbasket24_key PRIMARY KEY ( "id" );
CREATE INDEX ezbasket_session_id ON ezbasket USING btree ( "session_id" );


CREATE SEQUENCE ezbinaryfile_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezbinaryfile (
	contentobject_attribute_id integer(11) NOT NULL ,
	version integer(11) NOT NULL ,
	filename character varying(255) DEFAULT '' NOT NULL ,
	original_filename character varying(255) DEFAULT '' NOT NULL ,
	mime_type character varying(50) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezbinaryfile ADD CONSTRAINT ezbinaryfile36_key PRIMARY KEY ( "contentobject_attribute_id", "version" );


CREATE SEQUENCE ezcollab_group_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_group (
	id integer DEFAULT nextval('ezcollab_group_s'::text) NOT NULL,
	parent_group_id integer(11) NOT NULL ,
	depth integer(11) NOT NULL ,
	path_string character varying(255) DEFAULT '' NOT NULL ,
	is_open integer(11) DEFAULT '1' NOT NULL ,
	user_id integer(11) NOT NULL ,
	title character varying(255) DEFAULT '' NOT NULL ,
	priority integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_group ADD CONSTRAINT ezcollab_group50_key PRIMARY KEY ( "id" );
CREATE INDEX ezcollab_group_depth63 ON ezcollab_group USING btree ( "depth" );
CREATE INDEX ezcollab_group_path62 ON ezcollab_group USING btree ( "path_string" );


CREATE SEQUENCE ezcollab_item_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_item (
	id integer DEFAULT nextval('ezcollab_item_s'::text) NOT NULL,
	type_identifier character varying(40) DEFAULT '' NOT NULL ,
	creator_id integer(11) NOT NULL ,
	status integer(11) DEFAULT '1' NOT NULL ,
	data_text1 text NOT NULL ,
	data_text2 text NOT NULL ,
	data_text3 text NOT NULL ,
	data_int1 integer(11) NOT NULL ,
	data_int2 integer(11) NOT NULL ,
	data_int3 integer(11) NOT NULL ,
	data_float1 double precision DEFAULT '0' NOT NULL ,
	data_float2 double precision DEFAULT '0' NOT NULL ,
	data_float3 double precision DEFAULT '0' NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_item ADD CONSTRAINT ezcollab_item71_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcollab_item_group_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_item_group_link (
	collaboration_id integer(11) NOT NULL ,
	group_id integer(11) NOT NULL ,
	user_id integer(11) NOT NULL ,
	is_read integer(11) NOT NULL ,
	is_active integer(11) DEFAULT '1' NOT NULL ,
	last_read integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_item_group_link ADD CONSTRAINT ezcollab_item_group_link95_key PRIMARY KEY ( "collaboration_id", "group_id", "user_id" );


CREATE SEQUENCE ezcollab_item_message_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_item_message_link (
	id integer DEFAULT nextval('ezcollab_item_message_link_s'::text) NOT NULL,
	collaboration_id integer(11) NOT NULL ,
	participant_id integer(11) NOT NULL ,
	message_id integer(11) NOT NULL ,
	message_type integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_item_message_link ADD CONSTRAINT ezcollab_item_message_link112_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcollab_item_participant_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_item_participant_link (
	collaboration_id integer(11) NOT NULL ,
	participant_id integer(11) NOT NULL ,
	participant_type integer(11) DEFAULT '1' NOT NULL ,
	participant_role integer(11) DEFAULT '1' NOT NULL ,
	is_read integer(11) NOT NULL ,
	is_active integer(11) DEFAULT '1' NOT NULL ,
	last_read integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_item_participant_link ADD CONSTRAINT ezcollab_item_participant_link128_key PRIMARY KEY ( "collaboration_id", "participant_id" );


CREATE SEQUENCE ezcollab_item_status_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_item_status (
	collaboration_id integer(11) NOT NULL ,
	user_id integer(11) NOT NULL ,
	is_read integer(11) NOT NULL ,
	is_active integer(11) DEFAULT '1' NOT NULL ,
	last_read integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_item_status ADD CONSTRAINT ezcollab_item_status146_key PRIMARY KEY ( "collaboration_id", "user_id" );


CREATE SEQUENCE ezcollab_notification_rule_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_notification_rule (
	id integer DEFAULT nextval('ezcollab_notification_rule_s'::text) NOT NULL,
	user_id character varying(255) DEFAULT '' NOT NULL ,
	collab_identifier character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezcollab_notification_rule ADD CONSTRAINT ezcollab_notification_rule160_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcollab_profile_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_profile (
	id integer DEFAULT nextval('ezcollab_profile_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	main_group integer(11) NOT NULL ,
	data_text1 text NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_profile ADD CONSTRAINT ezcollab_profile172_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcollab_simple_message_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcollab_simple_message (
	id integer DEFAULT nextval('ezcollab_simple_message_s'::text) NOT NULL,
	message_type character varying(40) DEFAULT '' NOT NULL ,
	creator_id integer(11) NOT NULL ,
	data_text1 text NOT NULL ,
	data_text2 text NOT NULL ,
	data_text3 text NOT NULL ,
	data_int1 integer(11) NOT NULL ,
	data_int2 integer(11) NOT NULL ,
	data_int3 integer(11) NOT NULL ,
	data_float1 double precision DEFAULT '0' NOT NULL ,
	data_float2 double precision DEFAULT '0' NOT NULL ,
	data_float3 double precision DEFAULT '0' NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcollab_simple_message ADD CONSTRAINT ezcollab_simple_message187_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcontent_translation_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontent_translation (
	id integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
	name character varying(255) DEFAULT '' NOT NULL ,
	locale character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezcontent_translation ADD CONSTRAINT ezcontent_translation210_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcontentbrowsebookmark_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentbrowsebookmark (
	id integer DEFAULT nextval('ezcontentbrowsebookmark_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	node_id integer(11) NOT NULL ,
	name character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezcontentbrowsebookmark ADD CONSTRAINT ezcontentbrowsebookmark222_key PRIMARY KEY ( "id" );
CREATE INDEX ezcontentbrowsebookmark_user228 ON ezcontentbrowsebookmark USING btree ( "user_id" );


CREATE SEQUENCE ezcontentbrowserecent_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentbrowserecent (
	id integer DEFAULT nextval('ezcontentbrowserecent_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	node_id integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	name character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezcontentbrowserecent ADD CONSTRAINT ezcontentbrowserecent236_key PRIMARY KEY ( "id" );
CREATE INDEX ezcontentbrowserecent_user243 ON ezcontentbrowserecent USING btree ( "user_id" );


CREATE SEQUENCE ezcontentclass_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentclass (
	id integer DEFAULT nextval('ezcontentclass_s'::text) NOT NULL,
	version integer(11) NOT NULL ,
	name character varying(255) ,
	identifier character varying(50) DEFAULT '' NOT NULL ,
	contentobject_name character varying(255) ,
	creator_id integer(11) NOT NULL ,
	modifier_id integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcontentclass ADD CONSTRAINT ezcontentclass251_key PRIMARY KEY ( "id", "version" );
CREATE INDEX ezcontentclass_version262 ON ezcontentclass USING btree ( "version" );


CREATE SEQUENCE ezcontentclass_attribute_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentclass_attribute (
	id integer DEFAULT nextval('ezcontentclass_attribute_s'::text) NOT NULL,
	version integer(11) NOT NULL ,
	contentclass_id integer(11) NOT NULL ,
	identifier character varying(50) DEFAULT '' NOT NULL ,
	name character varying(255) DEFAULT '' NOT NULL ,
	data_type_string character varying(50) DEFAULT '' NOT NULL ,
	is_searchable integer(11) NOT NULL ,
	is_required integer(11) NOT NULL ,
	placement integer(11) NOT NULL ,
	data_int1 integer(11) ,
	data_int2 integer(11) ,
	data_int3 integer(11) ,
	data_int4 integer(11) ,
	data_float1 double precision DEFAULT '0' ,
	data_float2 double precision DEFAULT '0' ,
	data_float3 double precision DEFAULT '0' ,
	data_float4 double precision DEFAULT '0' ,
	data_text1 character varying(50) ,
	data_text2 character varying(50) ,
	data_text3 character varying(50) ,
	data_text4 character varying(255) ,
	data_text5 text ,
	is_information_collector integer(11) NOT NULL ,
	can_translate integer(11) DEFAULT '1' 
);
ALTER TABLE ONLY ezcontentclass_attribute ADD CONSTRAINT ezcontentclass_attribute270_key PRIMARY KEY ( "id", "version" );


CREATE SEQUENCE ezcontentclass_classgroup_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentclass_classgroup (
	contentclass_id integer(11) NOT NULL ,
	contentclass_version integer(11) NOT NULL ,
	group_id integer(11) NOT NULL ,
	group_name character varying(255) 
);
ALTER TABLE ONLY ezcontentclass_classgroup ADD CONSTRAINT ezcontentclass_classgroup303_key PRIMARY KEY ( "contentclass_id", "contentclass_version", "group_id" );


CREATE SEQUENCE ezcontentclassgroup_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentclassgroup (
	id integer DEFAULT nextval('ezcontentclassgroup_s'::text) NOT NULL,
	name character varying(255) ,
	creator_id integer(11) NOT NULL ,
	modifier_id integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcontentclassgroup ADD CONSTRAINT ezcontentclassgroup316_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcontentobject_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentobject (
	id integer DEFAULT nextval('ezcontentobject_s'::text) NOT NULL,
	owner_id integer(11) NOT NULL ,
	section_id integer(11) NOT NULL ,
	contentclass_id integer(11) NOT NULL ,
	name character varying(255) ,
	current_version integer(11) ,
	is_published integer(11) ,
	published integer(11) NOT NULL ,
	modified integer(11) NOT NULL ,
	status integer(11) ,
	remote_id character varying(100) 
);
ALTER TABLE ONLY ezcontentobject ADD CONSTRAINT ezcontentobject331_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcontentobject_attribute_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentobject_attribute (
	id integer DEFAULT nextval('ezcontentobject_attribute_s'::text) NOT NULL,
	language_code character varying(20) DEFAULT '' NOT NULL ,
	version integer(11) NOT NULL ,
	contentobject_id integer(11) NOT NULL ,
	contentclassattribute_id integer(11) NOT NULL ,
	data_text text ,
	data_int integer(11) ,
	data_float double precision DEFAULT '0' ,
	attribute_original_id integer(11) ,
	sort_key_int integer(11) NOT NULL ,
	sort_key_string character varying(50) DEFAULT '' ,
	data_type_string character varying(50) 
);
CREATE INDEX ezcontentobject_attribute_contentobject_id364 ON ezcontentobject_attribute USING btree ( "contentobject_id" );
CREATE INDEX ezcontentobject_attribute_language_code365 ON ezcontentobject_attribute USING btree ( "language_code" );
CREATE INDEX sort_key_int366 ON ezcontentobject_attribute USING btree ( "sort_key_int" );
CREATE INDEX sort_key_string367 ON ezcontentobject_attribute USING btree ( "sort_key_string" );
CREATE INDEX ezcontentobject_attribute_co_id_ver_lang_code ON ezcontentobject_attribute USING btree ( "contentobject_id", "version", "language_code" );
ALTER TABLE ONLY ezcontentobject_attribute ADD CONSTRAINT ezcontentobject_attribute351_key PRIMARY KEY ( "id", "version" );


CREATE SEQUENCE ezcontentobject_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentobject_link (
	id integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
	from_contentobject_id integer(11) NOT NULL ,
	from_contentobject_version integer(11) NOT NULL ,
	to_contentobject_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezcontentobject_link ADD CONSTRAINT ezcontentobject_link375_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezcontentobject_name_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentobject_name (
	contentobject_id integer(11) NOT NULL ,
	name character varying(255) ,
	content_version integer(11) NOT NULL ,
	content_translation character varying(20) DEFAULT '' NOT NULL ,
	real_translation character varying(20) 
);
ALTER TABLE ONLY ezcontentobject_name ADD CONSTRAINT ezcontentobject_name388_key PRIMARY KEY ( "contentobject_id", "content_version", "content_translation" );


CREATE SEQUENCE ezcontentobject_tree_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentobject_tree (
	node_id integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
	parent_node_id integer(11) NOT NULL ,
	contentobject_id integer(11) ,
	contentobject_version integer(11) ,
	contentobject_is_published integer(11) ,
	depth integer(11) NOT NULL ,
	path_string character varying(255) DEFAULT '' NOT NULL ,
	sort_field integer(11) DEFAULT '1' ,
	sort_order integer(11) DEFAULT '1' ,
	priority integer(11) NOT NULL ,
	path_identification_string text ,
	main_node_id integer(11) 
);
CREATE INDEX ezcontentobject_tree_path416 ON ezcontentobject_tree USING btree ( "path_string" );
CREATE INDEX ezcontentobject_tree_p_node_id417 ON ezcontentobject_tree USING btree ( "parent_node_id" );
CREATE INDEX ezcontentobject_tree_co_id418 ON ezcontentobject_tree USING btree ( "contentobject_id" );
CREATE INDEX ezcontentobject_tree_depth419 ON ezcontentobject_tree USING btree ( "depth" );
ALTER TABLE ONLY ezcontentobject_tree ADD CONSTRAINT ezcontentobject_tree402_key PRIMARY KEY ( "node_id" );


CREATE SEQUENCE ezcontentobject_version_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezcontentobject_version (
	id integer DEFAULT nextval('ezcontentobject_version_s'::text) NOT NULL,
	contentobject_id integer(11) ,
	creator_id integer(11) NOT NULL ,
	version integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL ,
	status integer(11) NOT NULL ,
	user_id integer(11) NOT NULL ,
	workflow_event_pos integer(11) 
);
ALTER TABLE ONLY ezcontentobject_version ADD CONSTRAINT ezcontentobject_version427_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezdiscountrule_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezdiscountrule (
	id integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
	name character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezdiscountrule ADD CONSTRAINT ezdiscountrule445_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezdiscountsubrule_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezdiscountsubrule (
	id integer DEFAULT nextval('ezdiscountsubrule_s'::text) NOT NULL,
	name character varying(255) DEFAULT '' NOT NULL ,
	discountrule_id integer(11) NOT NULL ,
	discount_percent double precision DEFAULT '0' ,
	limitation character(1) 
);
ALTER TABLE ONLY ezdiscountsubrule ADD CONSTRAINT ezdiscountsubrule456_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezdiscountsubrule_value_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezdiscountsubrule_value (
	discountsubrule_id integer(11) NOT NULL ,
	value integer(11) NOT NULL ,
	issection integer(11) NOT NULL 
);
ALTER TABLE ONLY ezdiscountsubrule_value ADD CONSTRAINT ezdiscountsubrule_value470_key PRIMARY KEY ( "discountsubrule_id", "value", "issection" );


CREATE SEQUENCE ezenumobjectvalue_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezenumobjectvalue (
	contentobject_attribute_id integer(11) NOT NULL ,
	contentobject_attribute_version integer(11) NOT NULL ,
	enumid integer(11) NOT NULL ,
	enumelement character varying(255) DEFAULT '' NOT NULL ,
	enumvalue character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezenumobjectvalue ADD CONSTRAINT ezenumobjectvalue482_key PRIMARY KEY ( "contentobject_attribute_id", "contentobject_attribute_version", "enumid" );
CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver489 ON ezenumobjectvalue USING btree ( "contentobject_attribute_id", "contentobject_attribute_version" );


CREATE SEQUENCE ezenumvalue_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezenumvalue (
	id integer DEFAULT nextval('ezenumvalue_s'::text) NOT NULL,
	contentclass_attribute_id integer(11) NOT NULL ,
	contentclass_attribute_version integer(11) NOT NULL ,
	enumelement character varying(255) DEFAULT '' NOT NULL ,
	enumvalue character varying(255) DEFAULT '' NOT NULL ,
	placement integer(11) NOT NULL 
);
ALTER TABLE ONLY ezenumvalue ADD CONSTRAINT ezenumvalue497_key PRIMARY KEY ( "id", "contentclass_attribute_id", "contentclass_attribute_version" );
CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver505 ON ezenumvalue USING btree ( "contentclass_attribute_id", "contentclass_attribute_version" );


CREATE SEQUENCE ezforgot_password_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezforgot_password (
	id integer DEFAULT nextval('ezforgot_password_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	hash_key character varying(32) DEFAULT '' NOT NULL ,
	time integer(11) NOT NULL 
);
ALTER TABLE ONLY ezforgot_password ADD CONSTRAINT ezforgot_password513_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezgeneral_digest_user_settings_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezgeneral_digest_user_settings (
	id integer DEFAULT nextval('ezgeneral_digest_user_settings_s'::text) NOT NULL,
	address character varying(255) DEFAULT '' NOT NULL ,
	receive_digest integer(11) NOT NULL ,
	digest_type integer(11) NOT NULL ,
	day character varying(255) DEFAULT '' NOT NULL ,
	time character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezgeneral_digest_user_settings ADD CONSTRAINT ezgeneral_digest_user_settings526_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezimage_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezimage (
	contentobject_attribute_id integer(11) NOT NULL ,
	version integer(11) NOT NULL ,
	filename character varying(255) DEFAULT '' NOT NULL ,
	original_filename character varying(255) DEFAULT '' NOT NULL ,
	mime_type character varying(50) DEFAULT '' NOT NULL ,
	alternative_text character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezimage ADD CONSTRAINT ezimage541_key PRIMARY KEY ( "contentobject_attribute_id", "version" );


CREATE SEQUENCE ezimagefile_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezimagefile (
	id integer DEFAULT nextval('ezimagefile_s'::text) NOT NULL,
	contentobject_attribute_id integer(11) NOT NULL ,
	filepath text NOT NULL 
);
ALTER TABLE ONLY ezimagefile ADD CONSTRAINT ezimagefile_pkey PRIMARY KEY ( "id" );
CREATE INDEX ezimagefile_file ON ezimagefile USING btree ( "filepath" );
CREATE INDEX ezimagefile_coid ON ezimagefile USING btree ( "contentobject_attribute_id" );


CREATE SEQUENCE ezimagevariation_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezimagevariation (
	contentobject_attribute_id integer(11) NOT NULL ,
	version integer(11) NOT NULL ,
	filename character varying(255) DEFAULT '' NOT NULL ,
	additional_path character varying(255) ,
	requested_width integer(11) NOT NULL ,
	requested_height integer(11) NOT NULL ,
	width integer(11) NOT NULL ,
	height integer(11) NOT NULL 
);
ALTER TABLE ONLY ezimagevariation ADD CONSTRAINT ezimagevariation556_key PRIMARY KEY ( "contentobject_attribute_id", "version", "requested_width", "requested_height" );


CREATE SEQUENCE ezinfocollection_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezinfocollection (
	id integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
	contentobject_id integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	user_identifier character varying(34) ,
	modified integer(11) 
);
ALTER TABLE ONLY ezinfocollection ADD CONSTRAINT ezinfocollection573_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezinfocollection_attribute_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezinfocollection_attribute (
	id integer DEFAULT nextval('ezinfocollection_attribute_s'::text) NOT NULL,
	informationcollection_id integer(11) NOT NULL ,
	data_text text ,
	data_int integer(11) ,
	data_float double precision DEFAULT '0' ,
	contentclass_attribute_id integer(11) NOT NULL ,
	contentobject_attribute_id integer(11) ,
	contentobject_id integer(11) 
);
ALTER TABLE ONLY ezinfocollection_attribute ADD CONSTRAINT ezinfocollection_attribute585_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezkeyword_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezkeyword (
	id integer DEFAULT nextval('ezkeyword_s'::text) NOT NULL,
	keyword character varying(255) ,
	class_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezkeyword ADD CONSTRAINT ezkeyword600_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezkeyword_attribute_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezkeyword_attribute_link (
	id integer DEFAULT nextval('ezkeyword_attribute_link_s'::text) NOT NULL,
	keyword_id integer(11) NOT NULL ,
	objectattribute_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezkeyword_attribute_link ADD CONSTRAINT ezkeyword_attribute_link612_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezmedia_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezmedia (
	contentobject_attribute_id integer(11) NOT NULL ,
	version integer(11) NOT NULL ,
	filename character varying(255) DEFAULT '' NOT NULL ,
	original_filename character varying(255) DEFAULT '' NOT NULL ,
	mime_type character varying(50) DEFAULT '' NOT NULL ,
	width integer(11) ,
	height integer(11) ,
	has_controller integer(11) ,
	is_autoplay integer(11) ,
	pluginspage character varying(255) ,
	quality character varying(50) ,
	controls character varying(50) ,
	is_loop integer(11) 
);
ALTER TABLE ONLY ezmedia ADD CONSTRAINT ezmedia624_key PRIMARY KEY ( "contentobject_attribute_id", "version" );


CREATE SEQUENCE ezmessage_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezmessage (
	id integer DEFAULT nextval('ezmessage_s'::text) NOT NULL,
	send_method character varying(50) DEFAULT '' NOT NULL ,
	send_weekday character varying(50) DEFAULT '' NOT NULL ,
	send_time character varying(50) DEFAULT '' NOT NULL ,
	destination_address character varying(50) DEFAULT '' NOT NULL ,
	title character varying(255) DEFAULT '' NOT NULL ,
	body text ,
	is_sent integer(11) NOT NULL 
);
ALTER TABLE ONLY ezmessage ADD CONSTRAINT ezmessage646_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezmodule_run_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezmodule_run (
	id integer DEFAULT nextval('ezmodule_run_s'::text) NOT NULL,
	workflow_process_id integer(11) ,
	module_name character varying(255) ,
	function_name character varying(255) ,
	module_data text 
);
ALTER TABLE ONLY ezmodule_run ADD CONSTRAINT ezmodule_run663_key PRIMARY KEY ( "id" );
CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s670 ON ezmodule_run USING btree ( "workflow_process_id" );


CREATE SEQUENCE eznode_assignment_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE eznode_assignment (
	id integer DEFAULT nextval('eznode_assignment_s'::text) NOT NULL,
	contentobject_id integer(11) ,
	contentobject_version integer(11) ,
	parent_node integer(11) ,
	sort_field integer(11) DEFAULT '1' ,
	sort_order integer(11) DEFAULT '1' ,
	is_main integer(11) NOT NULL ,
	from_node_id integer(11) ,
	remote_id integer(11) NOT NULL 
);
ALTER TABLE ONLY eznode_assignment ADD CONSTRAINT eznode_assignment678_key PRIMARY KEY ( "id" );


CREATE SEQUENCE eznotificationcollection_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE eznotificationcollection (
	id integer DEFAULT nextval('eznotificationcollection_s'::text) NOT NULL,
	event_id integer(11) NOT NULL ,
	handler character varying(255) DEFAULT '' NOT NULL ,
	transport character varying(255) DEFAULT '' NOT NULL ,
	data_subject text NOT NULL ,
	data_text text NOT NULL 
);
ALTER TABLE ONLY eznotificationcollection ADD CONSTRAINT eznotificationcollection696_key PRIMARY KEY ( "id" );


CREATE SEQUENCE eznotificationcollection_item_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE eznotificationcollection_item (
	id integer DEFAULT nextval('eznotificationcollection_item_s'::text) NOT NULL,
	collection_id integer(11) NOT NULL ,
	event_id integer(11) NOT NULL ,
	address character varying(255) DEFAULT '' NOT NULL ,
	send_date integer(11) NOT NULL 
);
ALTER TABLE ONLY eznotificationcollection_item ADD CONSTRAINT eznotificationcollection_item711_key PRIMARY KEY ( "id" );


CREATE SEQUENCE eznotificationevent_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE eznotificationevent (
	id integer DEFAULT nextval('eznotificationevent_s'::text) NOT NULL,
	status integer(11) NOT NULL ,
	event_type_string character varying(255) DEFAULT '' NOT NULL ,
	data_int1 integer(11) NOT NULL ,
	data_int2 integer(11) NOT NULL ,
	data_int3 integer(11) NOT NULL ,
	data_int4 integer(11) NOT NULL ,
	data_text1 text NOT NULL ,
	data_text2 text NOT NULL ,
	data_text3 text NOT NULL ,
	data_text4 text NOT NULL 
);
ALTER TABLE ONLY eznotificationevent ADD CONSTRAINT eznotificationevent725_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezoperation_memento_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezoperation_memento (
	id integer DEFAULT nextval('ezoperation_memento_s'::text) NOT NULL,
	memento_key character varying(32) DEFAULT '' NOT NULL ,
	memento_data text NOT NULL ,
	main integer(11) NOT NULL ,
	main_key character varying(32) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezoperation_memento ADD CONSTRAINT ezoperation_memento745_key PRIMARY KEY ( "id", "memento_key" );
CREATE INDEX ezoperation_memento_memento_key_main ON ezoperation_memento USING btree ( "memento_key", "main" );


CREATE SEQUENCE ezorder_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezorder (
	id integer DEFAULT nextval('ezorder_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	productcollection_id integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	is_temporary integer(11) DEFAULT '1' NOT NULL ,
	order_nr integer(11) NOT NULL ,
	data_text_2 text ,
	data_text_1 text ,
	account_identifier character varying(100) DEFAULT 'default' NOT NULL ,
	ignore_vat integer(11) NOT NULL 
);
ALTER TABLE ONLY ezorder ADD CONSTRAINT ezorder759_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezorder_item_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezorder_item (
	id integer DEFAULT nextval('ezorder_item_s'::text) NOT NULL,
	order_id integer(11) NOT NULL ,
	description character varying(255) ,
	price double precision DEFAULT '0' ,
	vat_value integer(11) NOT NULL 
);
ALTER TABLE ONLY ezorder_item ADD CONSTRAINT ezorder_item778_key PRIMARY KEY ( "id" );
CREATE INDEX ezorder_item_order_id ON ezorder_item USING btree ( "order_id" );


CREATE SEQUENCE ezpdf_export_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezpdf_export (
	id integer DEFAULT nextval('ezpdf_export_s'::text) NOT NULL,
	title character varying(255) ,
	show_frontpage integer(11) ,
	intro_text text ,
	sub_text text ,
	source_node_id integer(11) ,
	export_structure character varying(255) ,
	export_classes character varying(255) ,
	site_access character varying(255) ,
	pdf_filename character varying(255) ,
	modifier_id integer(11) ,
	modified integer(11) ,
	created integer(11) ,
	creator_id integer(11) ,
	status integer(11) 
);
ALTER TABLE ONLY ezpdf_export ADD CONSTRAINT ezpdf_export_pkey PRIMARY KEY ( "id" );


CREATE SEQUENCE ezpolicy_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezpolicy (
	id integer DEFAULT nextval('ezpolicy_s'::text) NOT NULL,
	role_id integer(11) ,
	function_name character varying(255) ,
	module_name character varying(255) ,
	limitation character(1) 
);
ALTER TABLE ONLY ezpolicy ADD CONSTRAINT ezpolicy792_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezpolicy_limitation_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezpolicy_limitation (
	id integer DEFAULT nextval('ezpolicy_limitation_s'::text) NOT NULL,
	policy_id integer(11) ,
	identifier character varying(255) DEFAULT '' NOT NULL ,
	role_id integer(11) ,
	function_name character varying(255) ,
	module_name character varying(255) 
);
ALTER TABLE ONLY ezpolicy_limitation ADD CONSTRAINT ezpolicy_limitation806_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezpolicy_limitation_value_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezpolicy_limitation_value (
	id integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
	limitation_id integer(11) ,
	value character varying(255) 
);
ALTER TABLE ONLY ezpolicy_limitation_value ADD CONSTRAINT ezpolicy_limitation_value821_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezpreferences_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezpreferences (
	id integer DEFAULT nextval('ezpreferences_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	name character varying(100) ,
	value character varying(100) 
);
ALTER TABLE ONLY ezpreferences ADD CONSTRAINT ezpreferences833_key PRIMARY KEY ( "id" );
CREATE INDEX ezpreferences_name839 ON ezpreferences USING btree ( "name" );


CREATE SEQUENCE ezproductcollection_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezproductcollection (
	id integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
	created integer(11) 
);
ALTER TABLE ONLY ezproductcollection ADD CONSTRAINT ezproductcollection847_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezproductcollection_item_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezproductcollection_item (
	id integer DEFAULT nextval('ezproductcollection_item_s'::text) NOT NULL,
	productcollection_id integer(11) NOT NULL ,
	contentobject_id integer(11) NOT NULL ,
	item_count integer(11) NOT NULL ,
	price double precision DEFAULT '0' ,
	is_vat_inc integer(11) ,
	vat_value double precision DEFAULT '0' ,
	discount double precision DEFAULT '0' 
);
ALTER TABLE ONLY ezproductcollection_item ADD CONSTRAINT ezproductcollection_item858_key PRIMARY KEY ( "id" );
CREATE INDEX ezproductcollection_item_contentobject_id ON ezproductcollection_item USING btree ( "productcollection_id" );
CREATE INDEX ezproductcollection_item_productcollection_id ON ezproductcollection_item USING btree ( "productcollection_id" );


CREATE SEQUENCE ezproductcollection_item_opt_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezproductcollection_item_opt (
	id integer DEFAULT nextval('ezproductcollection_item_opt_s'::text) NOT NULL,
	item_id integer(11) NOT NULL ,
	option_item_id integer(11) NOT NULL ,
	name character varying(255) DEFAULT '' NOT NULL ,
	value character varying(255) DEFAULT '' NOT NULL ,
	price double precision DEFAULT '0' NOT NULL ,
	object_attribute_id integer(11) 
);
ALTER TABLE ONLY ezproductcollection_item_opt ADD CONSTRAINT ezproductcollection_item_opt875_key PRIMARY KEY ( "id" );
CREATE INDEX ezproductcollection_item_opt_item_id ON ezproductcollection_item_opt USING btree ( "item_id" );


CREATE SEQUENCE ezrole_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezrole (
	id integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
	version integer(11) ,
	name character varying(255) DEFAULT '' NOT NULL ,
	value character(1) 
);
ALTER TABLE ONLY ezrole ADD CONSTRAINT ezrole891_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezrss_export_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezrss_export (
	id integer DEFAULT nextval('ezrss_export_s'::text) NOT NULL,
	title character varying(255) ,
	modifier_id integer(11) ,
	modified integer(11) ,
	url character varying(255) ,
	description text ,
	image_id integer(11) ,
	active integer(11) ,
	access_url character varying(255) ,
	created integer(11) ,
	creator_id integer(11) ,
	status integer(11) ,
	rss_version character varying(255) ,
	site_access character varying(255) 
);
ALTER TABLE ONLY ezrss_export ADD CONSTRAINT ezrss_export_pkey PRIMARY KEY ( "id" );


CREATE SEQUENCE ezrss_export_item_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezrss_export_item (
	id integer DEFAULT nextval('ezrss_export_item_s'::text) NOT NULL,
	rssexport_id integer(11) ,
	source_node_id integer(11) ,
	class_id integer(11) ,
	title character varying(255) ,
	description character varying(255) 
);
ALTER TABLE ONLY ezrss_export_item ADD CONSTRAINT ezrss_export_item_pkey PRIMARY KEY ( "id" );
CREATE INDEX ezrss_export_rsseid ON ezrss_export_item USING btree ( "rssexport_id" );


CREATE SEQUENCE ezrss_import_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezrss_import (
	id integer DEFAULT nextval('ezrss_import_s'::text) NOT NULL,
	name character varying(255) ,
	url text ,
	destination_node_id integer(11) ,
	class_id integer(11) ,
	class_title character varying(255) ,
	class_url character varying(255) ,
	class_description character varying(255) ,
	active integer(11) ,
	creator_id integer(11) ,
	created integer(11) ,
	modifier_id integer(11) ,
	modified integer(11) ,
	status integer(11) ,
	object_owner_id integer(11) 
);
ALTER TABLE ONLY ezrss_import ADD CONSTRAINT ezrss_import_pkey PRIMARY KEY ( "id" );


CREATE SEQUENCE ezsearch_object_word_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsearch_object_word_link (
	id integer DEFAULT nextval('ezsearch_object_word_link_s'::text) NOT NULL,
	contentobject_id integer(11) NOT NULL ,
	word_id integer(11) NOT NULL ,
	frequency double precision DEFAULT '0' NOT NULL ,
	placement integer(11) NOT NULL ,
	prev_word_id integer(11) NOT NULL ,
	next_word_id integer(11) NOT NULL ,
	contentclass_id integer(11) NOT NULL ,
	published integer(11) NOT NULL ,
	section_id integer(11) NOT NULL ,
	contentclass_attribute_id integer(11) NOT NULL ,
	identifier character varying(255) DEFAULT '' NOT NULL ,
	integer_value integer(11) NOT NULL 
);
CREATE INDEX ezsearch_object_word_link_object919 ON ezsearch_object_word_link USING btree ( "contentobject_id" );
CREATE INDEX ezsearch_object_word_link_word920 ON ezsearch_object_word_link USING btree ( "word_id" );
CREATE INDEX ezsearch_object_word_link_frequency921 ON ezsearch_object_word_link USING btree ( "frequency" );
CREATE INDEX ezsearch_object_word_link_identifier922 ON ezsearch_object_word_link USING btree ( "identifier" );
CREATE INDEX ezsearch_object_word_link_integer_value923 ON ezsearch_object_word_link USING btree ( "integer_value" );
ALTER TABLE ONLY ezsearch_object_word_link ADD CONSTRAINT ezsearch_object_word_link904_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezsearch_return_count_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsearch_return_count (
	id integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
	phrase_id integer(11) NOT NULL ,
	time integer(11) NOT NULL ,
	count integer(11) NOT NULL 
);
ALTER TABLE ONLY ezsearch_return_count ADD CONSTRAINT ezsearch_return_count931_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezsearch_search_phrase_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsearch_search_phrase (
	id integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
	phrase character varying(250) 
);
ALTER TABLE ONLY ezsearch_search_phrase ADD CONSTRAINT ezsearch_search_phrase944_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezsearch_word_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsearch_word (
	id integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
	word character varying(150) ,
	object_count integer(11) NOT NULL 
);
ALTER TABLE ONLY ezsearch_word ADD CONSTRAINT ezsearch_word955_key PRIMARY KEY ( "id" );
CREATE INDEX ezsearch_word960 ON ezsearch_word USING btree ( "word" );


CREATE SEQUENCE ezsection_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsection (
	id integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
	name character varying(255) ,
	locale character varying(255) ,
	navigation_part_identifier character varying(100) DEFAULT 'ezcontentnavigationpart' 
);
ALTER TABLE ONLY ezsection ADD CONSTRAINT ezsection968_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezsession_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsession (
	session_key character varying(32) DEFAULT '' NOT NULL ,
	expiration_time integer DEFAULT '0::bigint' NOT NULL ,
	data text NOT NULL 
);
ALTER TABLE ONLY ezsession ADD CONSTRAINT ezsession981_key PRIMARY KEY ( "session_key" );
CREATE INDEX expiration_time986 ON ezsession USING btree ( "expiration_time" );


CREATE SEQUENCE ezsite_data_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsite_data (
	name character varying(60) DEFAULT '' NOT NULL ,
	value text DEFAULT '''::text' NOT NULL 
);
ALTER TABLE ONLY ezsite_data ADD CONSTRAINT ezsite_data_pkey PRIMARY KEY ( "name" );


CREATE SEQUENCE ezsubtree_notification_rule_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezsubtree_notification_rule (
	id integer DEFAULT nextval('ezsubtree_notification_rule_s'::text) NOT NULL,
	address character varying(255) DEFAULT '' NOT NULL ,
	use_digest integer(11) NOT NULL ,
	node_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezsubtree_notification_rule ADD CONSTRAINT ezsubtree_notification_rule994_key PRIMARY KEY ( "id" );


CREATE SEQUENCE eztipafriend_counter_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE eztipafriend_counter (
	node_id integer(11) NOT NULL ,
	count integer(11) NOT NULL 
);
ALTER TABLE ONLY eztipafriend_counter ADD CONSTRAINT eztipafriend_counter_pkey PRIMARY KEY ( "node_id" );


CREATE SEQUENCE eztrigger_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE eztrigger (
	id integer DEFAULT nextval('eztrigger_s'::text) NOT NULL,
	name character varying(255) ,
	module_name character varying(200) DEFAULT '' NOT NULL ,
	function_name character varying(200) DEFAULT '' NOT NULL ,
	connect_type character(1) DEFAULT '''::bpchar' NOT NULL ,
	workflow_id integer(11) 
);
ALTER TABLE ONLY eztrigger ADD CONSTRAINT eztrigger1007_key PRIMARY KEY ( "id" );
CREATE INDEX eztrigger_fetch ON eztrigger USING btree ( "name", "module_name", "function_name" );
CREATE UNIQUE INDEX eztrigger_def_id1015 ON eztrigger USING btree ( "module_name", "function_name", "connect_type" );


CREATE SEQUENCE ezurl_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezurl (
	id integer DEFAULT nextval('ezurl_s'::text) NOT NULL,
	url character varying(255) ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL ,
	is_valid integer(11) DEFAULT '1' NOT NULL ,
	last_checked integer(11) NOT NULL ,
	original_url_md5 character varying(32) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezurl ADD CONSTRAINT ezurl1023_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezurl_object_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezurl_object_link (
	url_id integer(11) NOT NULL ,
	contentobject_attribute_id integer(11) NOT NULL ,
	contentobject_attribute_version integer(11) NOT NULL 
);
ALTER TABLE ONLY ezurl_object_link ADD CONSTRAINT ezurl_object_link1039_key PRIMARY KEY ( "url_id", "contentobject_attribute_id", "contentobject_attribute_version" );


CREATE SEQUENCE ezurlalias_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezurlalias (
	id integer DEFAULT nextval('ezurlalias_s'::text) NOT NULL,
	source_url text NOT NULL ,
	source_md5 character varying(32) ,
	destination_url text NOT NULL ,
	is_internal integer(11) DEFAULT '1' NOT NULL ,
	forward_to_id integer(11) NOT NULL ,
	is_wildcard integer(11) NOT NULL 
);
CREATE INDEX ezurlalias_source_md51059 ON ezurlalias USING btree ( "source_md5" );
CREATE INDEX ezurlalias_source_url ON ezurlalias USING btree ( "source_url" );
CREATE INDEX ezurlalias_desturl ON ezurlalias USING btree ( "destination_url" );
ALTER TABLE ONLY ezurlalias ADD CONSTRAINT ezurlalias1051_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezuser_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezuser (
	contentobject_id integer(11) NOT NULL ,
	login character varying(150) DEFAULT '' NOT NULL ,
	email character varying(150) DEFAULT '' NOT NULL ,
	password_hash_type integer(11) DEFAULT '1' NOT NULL ,
	password_hash character varying(50) 
);
ALTER TABLE ONLY ezuser ADD CONSTRAINT ezuser1067_key PRIMARY KEY ( "contentobject_id" );


CREATE SEQUENCE ezuser_accountkey_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezuser_accountkey (
	id integer DEFAULT nextval('ezuser_accountkey_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	hash_key character varying(32) DEFAULT '' NOT NULL ,
	time integer(11) NOT NULL 
);
ALTER TABLE ONLY ezuser_accountkey ADD CONSTRAINT ezuser_accountkey1081_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezuser_discountrule_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezuser_discountrule (
	id integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
	discountrule_id integer(11) ,
	contentobject_id integer(11) ,
	name character varying(255) DEFAULT '' NOT NULL 
);
ALTER TABLE ONLY ezuser_discountrule ADD CONSTRAINT ezuser_discountrule1094_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezuser_role_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezuser_role (
	id integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
	role_id integer(11) ,
	contentobject_id integer(11) 
);
ALTER TABLE ONLY ezuser_role ADD CONSTRAINT ezuser_role1107_key PRIMARY KEY ( "id" );
CREATE INDEX ezuser_role_contentobject_id1112 ON ezuser_role USING btree ( "contentobject_id" );


CREATE SEQUENCE ezuser_setting_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezuser_setting (
	user_id integer(11) NOT NULL ,
	is_enabled integer(11) NOT NULL ,
	max_login integer(11) 
);
ALTER TABLE ONLY ezuser_setting ADD CONSTRAINT ezuser_setting1120_key PRIMARY KEY ( "user_id" );


CREATE SEQUENCE ezvattype_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezvattype (
	id integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
	name character varying(255) DEFAULT '' NOT NULL ,
	percentage double precision DEFAULT '0' 
);
ALTER TABLE ONLY ezvattype ADD CONSTRAINT ezvattype1132_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezview_counter_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezview_counter (
	node_id integer(11) NOT NULL ,
	count integer(11) NOT NULL 
);
ALTER TABLE ONLY ezview_counter ADD CONSTRAINT ezview_counter_pkey PRIMARY KEY ( "node_id" );


CREATE SEQUENCE ezwaituntildatevalue_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezwaituntildatevalue (
	id integer DEFAULT nextval('ezwaituntildatevalue_s'::text) NOT NULL,
	workflow_event_id integer(11) NOT NULL ,
	workflow_event_version integer(11) NOT NULL ,
	contentclass_id integer(11) NOT NULL ,
	contentclass_attribute_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezwaituntildatevalue ADD CONSTRAINT ezwaituntildatevalue1144_key PRIMARY KEY ( "id", "workflow_event_id", "workflow_event_version" );
CREATE INDEX ezwaituntildateevalue_wf_ev_id_wf_ver1151 ON ezwaituntildatevalue USING btree ( "workflow_event_id", "workflow_event_version" );


CREATE SEQUENCE ezwishlist_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezwishlist (
	id integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
	user_id integer(11) NOT NULL ,
	productcollection_id integer(11) NOT NULL 
);
ALTER TABLE ONLY ezwishlist ADD CONSTRAINT ezwishlist1159_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezworkflow_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezworkflow (
	id integer DEFAULT nextval('ezworkflow_s'::text) NOT NULL,
	version integer(11) NOT NULL ,
	is_enabled integer(11) NOT NULL ,
	workflow_type_string character varying(50) DEFAULT '' NOT NULL ,
	name character varying(255) DEFAULT '' NOT NULL ,
	creator_id integer(11) NOT NULL ,
	modifier_id integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezworkflow ADD CONSTRAINT ezworkflow1171_key PRIMARY KEY ( "id", "version" );


CREATE SEQUENCE ezworkflow_assign_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezworkflow_assign (
	id integer DEFAULT nextval('ezworkflow_assign_s'::text) NOT NULL,
	workflow_id integer(11) NOT NULL ,
	node_id integer(11) NOT NULL ,
	access_type integer(11) NOT NULL ,
	as_tree integer(11) NOT NULL 
);
ALTER TABLE ONLY ezworkflow_assign ADD CONSTRAINT ezworkflow_assign1189_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezworkflow_event_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezworkflow_event (
	id integer DEFAULT nextval('ezworkflow_event_s'::text) NOT NULL,
	version integer(11) NOT NULL ,
	workflow_id integer(11) NOT NULL ,
	workflow_type_string character varying(50) DEFAULT '' NOT NULL ,
	description character varying(50) DEFAULT '' NOT NULL ,
	data_int1 integer(11) ,
	data_int2 integer(11) ,
	data_int3 integer(11) ,
	data_int4 integer(11) ,
	data_text1 character varying(50) ,
	data_text2 character varying(50) ,
	data_text3 character varying(50) ,
	data_text4 character varying(50) ,
	placement integer(11) NOT NULL 
);
ALTER TABLE ONLY ezworkflow_event ADD CONSTRAINT ezworkflow_event1203_key PRIMARY KEY ( "id", "version" );


CREATE SEQUENCE ezworkflow_group_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezworkflow_group (
	id integer DEFAULT nextval('ezworkflow_group_s'::text) NOT NULL,
	name character varying(255) DEFAULT '' NOT NULL ,
	creator_id integer(11) NOT NULL ,
	modifier_id integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL 
);
ALTER TABLE ONLY ezworkflow_group ADD CONSTRAINT ezworkflow_group1226_key PRIMARY KEY ( "id" );


CREATE SEQUENCE ezworkflow_group_link_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezworkflow_group_link (
	workflow_id integer(11) NOT NULL ,
	group_id integer(11) NOT NULL ,
	workflow_version integer(11) NOT NULL ,
	group_name character varying(255) 
);
ALTER TABLE ONLY ezworkflow_group_link ADD CONSTRAINT ezworkflow_group_link1241_key PRIMARY KEY ( "workflow_id", "group_id", "workflow_version" );


CREATE SEQUENCE ezworkflow_process_s
	START 1
	INCREMENT 1
	MAXVALUE 9223372036854775807
	MINVALUE 1
	CACHE 1;

CREATE TABLE ezworkflow_process (
	id integer DEFAULT nextval('ezworkflow_process_s'::text) NOT NULL,
	process_key character varying(32) DEFAULT '' NOT NULL ,
	workflow_id integer(11) NOT NULL ,
	user_id integer(11) NOT NULL ,
	content_id integer(11) NOT NULL ,
	content_version integer(11) NOT NULL ,
	node_id integer(11) NOT NULL ,
	session_key character varying(32) DEFAULT '0' NOT NULL ,
	event_id integer(11) NOT NULL ,
	event_position integer(11) NOT NULL ,
	last_event_id integer(11) NOT NULL ,
	last_event_position integer(11) NOT NULL ,
	last_event_status integer(11) NOT NULL ,
	event_status integer(11) NOT NULL ,
	created integer(11) NOT NULL ,
	modified integer(11) NOT NULL ,
	activation_date integer(11) ,
	event_state integer(11) ,
	status integer(11) ,
	parameters text ,
	memento_key character varying(32) 
);
ALTER TABLE ONLY ezworkflow_process ADD CONSTRAINT ezworkflow_process1254_key PRIMARY KEY ( "id" );
CREATE INDEX ezworkflow_process_process_key ON ezworkflow_process USING btree ( "process_key" );


