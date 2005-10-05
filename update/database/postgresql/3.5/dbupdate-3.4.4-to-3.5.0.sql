UPDATE ezsite_data SET value='3.5.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';

-- 3.4.2 to 3.5.0alpha1

-- We allow users from the "Editors" group 
-- access only to "Root Folder" and "Media" trees.
-- If you want to fix this you need to figure out the ids of these roles and modify
-- the following SQLs
--
-- DELETE FROM ezuser_role WHERE id=30 AND role_id=3;
-- INSERT INTO ezuser_role
--        (role_id, contentobject_id, limit_identifier,limit_value)
--        VALUES (3,13,'Subtree','/1/2/');
-- INSERT INTO ezuser_role
--        (role_id, contentobject_id, limit_identifier,limit_value)
--        VALUES (3,13,'Subtree','/1/43/');

-- This is present in 3.4.2, uncomment and run this if
-- this is missing from your DB
-- CREATE SEQUENCE tmp_notification_rule_s
--     START 1
--     INCREMENT 1
--     MAXVALUE 9223372036854775807
--     MINVALUE 1
--     CACHE 1;

-- the support of redirect payment gateways
-- create table for eZPaymentObjects
CREATE SEQUENCE ezpaymentobject_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezpaymentobject(
    id integer DEFAULT nextval('ezpaymentobject_s'::text) NOT NULL PRIMARY KEY,
    workflowprocess_id integer DEFAULT 0 NOT NULL,
    order_id integer DEFAULT 0 NOT NULL,
    payment_string character varying(255) DEFAULT ''::character varying NOT NULL,
    status integer DEFAULT 0 NOT NULL
    );

ALTER TABLE ezbasket ADD COLUMN order_id integer;
UPDATE ezbasket SET order_id=0;
ALTER TABLE ezbasket ALTER order_id SET NOT NULL;
ALTER TABLE ezbasket ALTER order_id SET DEFAULT 0;

ALTER TABLE ezbinaryfile ADD COLUMN download_count integer;
UPDATE ezbinaryfile SET download_count='0';
ALTER TABLE ezbinaryfile ALTER download_count SET NOT NULL;
ALTER TABLE ezbinaryfile ALTER download_count SET DEFAULT 0;

ALTER TABLE ezcontentclass ADD is_container integer;
UPDATE ezcontentclass SET is_container=0;
ALTER TABLE ezcontentclass ALTER is_container SET NOT NULL;
ALTER TABLE ezcontentclass ALTER is_container SET DEFAULT 0;

-- New table for storing the users last visit

CREATE TABLE ezuservisit
(
    user_id                 INT NOT NULL PRIMARY KEY,
    current_visit_timestamp INT NOT NULL,
    last_visit_timestamp    INT NOT NULL
);

-- New columns for the hiding functionality
ALTER TABLE ezcontentobject_tree ADD   is_hidden INTEGER;
UPDATE      ezcontentobject_tree SET   is_hidden = 0;
ALTER TABLE ezcontentobject_tree ALTER is_hidden SET NOT NULL;
ALTER TABLE ezcontentobject_tree ALTER is_hidden SET DEFAULT 0;
ALTER TABLE ezcontentobject_tree ADD   is_invisible INTEGER;
UPDATE      ezcontentobject_tree SET   is_invisible = 0;
ALTER TABLE ezcontentobject_tree ALTER is_invisible SET NOT NULL;
ALTER TABLE ezcontentobject_tree ALTER is_invisible SET DEFAULT 0;


-- 3.5.0alpha1 to 3.5.0beta1

-- fix for section based conditional assignment also in 3.4.3
update  ezuser_role set limit_identifier='Section' where limit_identifier='section';

-- fixes incorrect name of group in ezcontentclass_classgroup 
update ezcontentclass_classgroup set group_name='Users' where group_id=2;

-- 3.5.0beta1 to 3.5.0rc1

ALTER TABLE ezrole ADD COLUMN is_new integer;
UPDATE ezrole SET is_new=0;
ALTER TABLE ezrole ALTER is_new SET NOT NULL;
ALTER TABLE ezrole ALTER is_new SET DEFAULT 0;

-- New name for ezsearch index, the old one crashed with the table name ezsearch_word
DROP INDEX ezsearch_word960;
CREATE INDEX ezsearch_word_word_i ON ezsearch_word USING btree (word);

-- Renamed several indexes, now they have the exact same name as the ones in MySQL.
-- Changed names of all primary keys to be <tbl_name>_pkey
ALTER TABLE ezapprove_items DROP CONSTRAINT ezapprove_items12_key;
ALTER TABLE ONLY ezapprove_items ADD CONSTRAINT ezapprove_items_pkey PRIMARY KEY( "id" );
ALTER TABLE ezbasket DROP CONSTRAINT ezbasket24_key;
ALTER TABLE ONLY ezbasket ADD CONSTRAINT ezbasket_pkey PRIMARY KEY( "id" );
ALTER TABLE ezbinaryfile DROP CONSTRAINT ezbinaryfile36_key;
ALTER TABLE ONLY ezbinaryfile ADD CONSTRAINT ezbinaryfile_pkey PRIMARY KEY( "contentobject_attribute_id", "version" );
ALTER TABLE ezcollab_group DROP CONSTRAINT ezcollab_group50_key;
DROP INDEX ezcollab_group_depth63;
DROP INDEX ezcollab_group_path62;
ALTER TABLE ONLY ezcollab_group ADD CONSTRAINT ezcollab_group_pkey PRIMARY KEY( "id" );
CREATE INDEX ezcollab_group_depth ON ezcollab_group USING btree( "depth" );
CREATE INDEX ezcollab_group_path ON ezcollab_group USING btree( "path_string" );
ALTER TABLE ezcollab_item DROP CONSTRAINT ezcollab_item71_key;
ALTER TABLE ONLY ezcollab_item ADD CONSTRAINT ezcollab_item_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcollab_item_group_link DROP CONSTRAINT ezcollab_item_group_link95_key;
ALTER TABLE ONLY ezcollab_item_group_link ADD CONSTRAINT ezcollab_item_group_link_pkey PRIMARY KEY( "collaboration_id", "group_id", "user_id" );
ALTER TABLE ezcollab_item_message_link DROP CONSTRAINT ezcollab_item_message_link112_key;
ALTER TABLE ONLY ezcollab_item_message_link ADD CONSTRAINT ezcollab_item_message_link_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcollab_item_participant_link DROP CONSTRAINT ezcollab_item_participant_link128_key;
ALTER TABLE ONLY ezcollab_item_participant_link ADD CONSTRAINT ezcollab_item_participant_link_pkey PRIMARY KEY( "collaboration_id", "participant_id" );
ALTER TABLE ezcollab_item_status DROP CONSTRAINT ezcollab_item_status146_key;
ALTER TABLE ONLY ezcollab_item_status ADD CONSTRAINT ezcollab_item_status_pkey PRIMARY KEY( "collaboration_id", "user_id" );
ALTER TABLE ezcollab_notification_rule DROP CONSTRAINT ezcollab_notification_rule160_key;
ALTER TABLE ONLY ezcollab_notification_rule ADD CONSTRAINT ezcollab_notification_rule_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcollab_profile DROP CONSTRAINT ezcollab_profile172_key;
ALTER TABLE ONLY ezcollab_profile ADD CONSTRAINT ezcollab_profile_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcollab_simple_message DROP CONSTRAINT ezcollab_simple_message187_key;
ALTER TABLE ONLY ezcollab_simple_message ADD CONSTRAINT ezcollab_simple_message_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcontent_translation DROP CONSTRAINT ezcontent_translation210_key;
ALTER TABLE ONLY ezcontent_translation ADD CONSTRAINT ezcontent_translation_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcontentbrowsebookmark DROP CONSTRAINT ezcontentbrowsebookmark222_key;
DROP INDEX ezcontentbrowsebookmark_user228;
ALTER TABLE ONLY ezcontentbrowsebookmark ADD CONSTRAINT ezcontentbrowsebookmark_pkey PRIMARY KEY( "id" );
CREATE INDEX ezcontentbrowsebookmark_user ON ezcontentbrowsebookmark USING btree( "user_id" );
ALTER TABLE ezcontentbrowserecent DROP CONSTRAINT ezcontentbrowserecent236_key;
DROP INDEX ezcontentbrowserecent_user243;
ALTER TABLE ONLY ezcontentbrowserecent ADD CONSTRAINT ezcontentbrowserecent_pkey PRIMARY KEY( "id" );
CREATE INDEX ezcontentbrowserecent_user ON ezcontentbrowserecent USING btree( "user_id" );
ALTER TABLE ezcontentclass DROP CONSTRAINT ezcontentclass251_key;
DROP INDEX ezcontentclass_version262;
ALTER TABLE ONLY ezcontentclass ADD CONSTRAINT ezcontentclass_pkey PRIMARY KEY( "id", "version" );
CREATE INDEX ezcontentclass_version ON ezcontentclass USING btree( "version" );
ALTER TABLE ezcontentclass_attribute DROP CONSTRAINT ezcontentclass_attribute270_key;
ALTER TABLE ONLY ezcontentclass_attribute ADD CONSTRAINT ezcontentclass_attribute_pkey PRIMARY KEY( "id", "version" );
ALTER TABLE ezcontentclass_classgroup DROP CONSTRAINT ezcontentclass_classgroup303_key;
ALTER TABLE ONLY ezcontentclass_classgroup ADD CONSTRAINT ezcontentclass_classgroup_pkey PRIMARY KEY( "contentclass_id", "contentclass_version", "group_id" );
ALTER TABLE ezcontentclassgroup DROP CONSTRAINT ezcontentclassgroup316_key;
ALTER TABLE ONLY ezcontentclassgroup ADD CONSTRAINT ezcontentclassgroup_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcontentobject DROP CONSTRAINT ezcontentobject331_key;
ALTER TABLE ONLY ezcontentobject ADD CONSTRAINT ezcontentobject_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcontentobject_attribute DROP CONSTRAINT ezcontentobject_attribute351_key;
DROP INDEX ezcontentobject_attribute_contentobject_id364;
DROP INDEX ezcontentobject_attribute_language_code365;
DROP INDEX sort_key_int366;
DROP INDEX sort_key_string367;
ALTER TABLE ONLY ezcontentobject_attribute ADD CONSTRAINT ezcontentobject_attribute_pkey PRIMARY KEY( "id", "version" );
CREATE INDEX ezcontentobject_attribute_contentobject_id ON ezcontentobject_attribute USING btree( "contentobject_id" );
CREATE INDEX ezcontentobject_attribute_language_code ON ezcontentobject_attribute USING btree( "language_code" );
CREATE INDEX sort_key_int ON ezcontentobject_attribute USING btree( "sort_key_int" );
CREATE INDEX sort_key_string ON ezcontentobject_attribute USING btree( "sort_key_string" );
ALTER TABLE ezcontentobject_link DROP CONSTRAINT ezcontentobject_link375_key;
ALTER TABLE ONLY ezcontentobject_link ADD CONSTRAINT ezcontentobject_link_pkey PRIMARY KEY( "id" );
ALTER TABLE ezcontentobject_name DROP CONSTRAINT ezcontentobject_name388_key;
ALTER TABLE ONLY ezcontentobject_name ADD CONSTRAINT ezcontentobject_name_pkey PRIMARY KEY( "contentobject_id", "content_version", "content_translation" );
ALTER TABLE ezcontentobject_tree DROP CONSTRAINT ezcontentobject_tree402_key;
DROP INDEX ezcontentobject_tree_co_id418;
DROP INDEX ezcontentobject_tree_depth419;
DROP INDEX ezcontentobject_tree_mod_sub;
DROP INDEX ezcontentobject_tree_p_node_id417;
DROP INDEX ezcontentobject_tree_path416;
ALTER TABLE ONLY ezcontentobject_tree ADD CONSTRAINT ezcontentobject_tree_pkey PRIMARY KEY( "node_id" );
CREATE INDEX ezcontentobject_tree_co_id ON ezcontentobject_tree USING btree( "contentobject_id" );
CREATE INDEX ezcontentobject_tree_depth ON ezcontentobject_tree USING btree( "depth" );
CREATE INDEX ezcontentobject_tree_p_node_id ON ezcontentobject_tree USING btree( "parent_node_id" );
CREATE INDEX ezcontentobject_tree_path ON ezcontentobject_tree USING btree( "path_string" );
CREATE INDEX modified_subnode ON ezcontentobject_tree USING btree( "modified_subnode" );
ALTER TABLE ezcontentobject_version DROP CONSTRAINT ezcontentobject_version427_key;
ALTER TABLE ONLY ezcontentobject_version ADD CONSTRAINT ezcontentobject_version_pkey PRIMARY KEY( "id" );
ALTER TABLE ezdiscountrule DROP CONSTRAINT ezdiscountrule445_key;
ALTER TABLE ONLY ezdiscountrule ADD CONSTRAINT ezdiscountrule_pkey PRIMARY KEY( "id" );
ALTER TABLE ezdiscountsubrule DROP CONSTRAINT ezdiscountsubrule456_key;
ALTER TABLE ONLY ezdiscountsubrule ADD CONSTRAINT ezdiscountsubrule_pkey PRIMARY KEY( "id" );
ALTER TABLE ezdiscountsubrule_value DROP CONSTRAINT ezdiscountsubrule_value470_key;
ALTER TABLE ONLY ezdiscountsubrule_value ADD CONSTRAINT ezdiscountsubrule_value_pkey PRIMARY KEY( "discountsubrule_id", "value", "issection" );
ALTER TABLE ezenumobjectvalue DROP CONSTRAINT ezenumobjectvalue482_key;
DROP INDEX ezenumobjectvalue_co_attr_id_co_attr_ver489;
ALTER TABLE ONLY ezenumobjectvalue ADD CONSTRAINT ezenumobjectvalue_pkey PRIMARY KEY( "contentobject_attribute_id", "contentobject_attribute_version", "enumid" );
CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver ON ezenumobjectvalue USING btree( "contentobject_attribute_id", "contentobject_attribute_version" );
ALTER TABLE ezenumvalue DROP CONSTRAINT ezenumvalue497_key;
DROP INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver505;
ALTER TABLE ONLY ezenumvalue ADD CONSTRAINT ezenumvalue_pkey PRIMARY KEY( "id", "contentclass_attribute_id", "contentclass_attribute_version" );
CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver ON ezenumvalue USING btree( "contentclass_attribute_id", "contentclass_attribute_version" );
ALTER TABLE ezforgot_password DROP CONSTRAINT ezforgot_password513_key;
ALTER TABLE ONLY ezforgot_password ADD CONSTRAINT ezforgot_password_pkey PRIMARY KEY( "id" );
ALTER TABLE ezgeneral_digest_user_settings DROP CONSTRAINT ezgeneral_digest_user_settings526_key;
ALTER TABLE ONLY ezgeneral_digest_user_settings ADD CONSTRAINT ezgeneral_digest_user_settings_pkey PRIMARY KEY( "id" );
ALTER TABLE ezimage DROP CONSTRAINT ezimage541_key;
ALTER TABLE ONLY ezimage ADD CONSTRAINT ezimage_pkey PRIMARY KEY( "contentobject_attribute_id", "version" );
ALTER TABLE ezimagevariation DROP CONSTRAINT ezimagevariation556_key;
ALTER TABLE ONLY ezimagevariation ADD CONSTRAINT ezimagevariation_pkey PRIMARY KEY( "contentobject_attribute_id", "version", "requested_width", "requested_height" );
ALTER TABLE ezinfocollection DROP CONSTRAINT ezinfocollection573_key;
ALTER TABLE ONLY ezinfocollection ADD CONSTRAINT ezinfocollection_pkey PRIMARY KEY( "id" );
ALTER TABLE ezinfocollection_attribute DROP CONSTRAINT ezinfocollection_attribute585_key;
ALTER TABLE ONLY ezinfocollection_attribute ADD CONSTRAINT ezinfocollection_attribute_pkey PRIMARY KEY( "id" );
ALTER TABLE ezkeyword DROP CONSTRAINT ezkeyword600_key;
ALTER TABLE ONLY ezkeyword ADD CONSTRAINT ezkeyword_pkey PRIMARY KEY( "id" );
ALTER TABLE ezkeyword_attribute_link DROP CONSTRAINT ezkeyword_attribute_link612_key;
ALTER TABLE ONLY ezkeyword_attribute_link ADD CONSTRAINT ezkeyword_attribute_link_pkey PRIMARY KEY( "id" );
ALTER TABLE ezmedia DROP CONSTRAINT ezmedia624_key;
ALTER TABLE ONLY ezmedia ADD CONSTRAINT ezmedia_pkey PRIMARY KEY( "contentobject_attribute_id", "version" );
ALTER TABLE ezmessage DROP CONSTRAINT ezmessage646_key;
ALTER TABLE ONLY ezmessage ADD CONSTRAINT ezmessage_pkey PRIMARY KEY( "id" );
ALTER TABLE ezmodule_run DROP CONSTRAINT ezmodule_run663_key;
DROP INDEX ezmodule_run_workflow_process_id_s670;
ALTER TABLE ONLY ezmodule_run ADD CONSTRAINT ezmodule_run_pkey PRIMARY KEY( "id" );
CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s ON ezmodule_run USING btree( "workflow_process_id" );
ALTER TABLE eznode_assignment DROP CONSTRAINT eznode_assignment678_key;
ALTER TABLE ONLY eznode_assignment ADD CONSTRAINT eznode_assignment_pkey PRIMARY KEY( "id" );
ALTER TABLE eznotificationcollection DROP CONSTRAINT eznotificationcollection696_key;
ALTER TABLE ONLY eznotificationcollection ADD CONSTRAINT eznotificationcollection_pkey PRIMARY KEY( "id" );
ALTER TABLE eznotificationcollection_item DROP CONSTRAINT eznotificationcollection_item711_key;
ALTER TABLE ONLY eznotificationcollection_item ADD CONSTRAINT eznotificationcollection_item_pkey PRIMARY KEY( "id" );
ALTER TABLE eznotificationevent DROP CONSTRAINT eznotificationevent725_key;
ALTER TABLE ONLY eznotificationevent ADD CONSTRAINT eznotificationevent_pkey PRIMARY KEY( "id" );
ALTER TABLE ezoperation_memento DROP CONSTRAINT ezoperation_memento745_key;
ALTER TABLE ONLY ezoperation_memento ADD CONSTRAINT ezoperation_memento_pkey PRIMARY KEY( "id", "memento_key" );
ALTER TABLE ezorder DROP CONSTRAINT ezorder759_key;
ALTER TABLE ONLY ezorder ADD CONSTRAINT ezorder_pkey PRIMARY KEY( "id" );
ALTER TABLE ezorder_item DROP CONSTRAINT ezorder_item778_key;
ALTER TABLE ONLY ezorder_item ADD CONSTRAINT ezorder_item_pkey PRIMARY KEY( "id" );
ALTER TABLE ezpolicy DROP CONSTRAINT ezpolicy792_key;
ALTER TABLE ONLY ezpolicy ADD CONSTRAINT ezpolicy_pkey PRIMARY KEY( "id" );
ALTER TABLE ezpolicy_limitation DROP CONSTRAINT ezpolicy_limitation806_key;
ALTER TABLE ONLY ezpolicy_limitation ADD CONSTRAINT ezpolicy_limitation_pkey PRIMARY KEY( "id" );
ALTER TABLE ezpolicy_limitation_value DROP CONSTRAINT ezpolicy_limitation_value821_key;
ALTER TABLE ONLY ezpolicy_limitation_value ADD CONSTRAINT ezpolicy_limitation_value_pkey PRIMARY KEY( "id" );
ALTER TABLE ezpreferences DROP CONSTRAINT ezpreferences833_key;
DROP INDEX ezpreferences_name839;
ALTER TABLE ONLY ezpreferences ADD CONSTRAINT ezpreferences_pkey PRIMARY KEY( "id" );
CREATE INDEX ezpreferences_name ON ezpreferences USING btree( "name" );
ALTER TABLE ezproductcollection DROP CONSTRAINT ezproductcollection847_key;
ALTER TABLE ONLY ezproductcollection ADD CONSTRAINT ezproductcollection_pkey PRIMARY KEY( "id" );
ALTER TABLE ezproductcollection_item DROP CONSTRAINT ezproductcollection_item858_key;
ALTER TABLE ONLY ezproductcollection_item ADD CONSTRAINT ezproductcollection_item_pkey PRIMARY KEY( "id" );
ALTER TABLE ezproductcollection_item_opt DROP CONSTRAINT ezproductcollection_item_opt875_key;
ALTER TABLE ONLY ezproductcollection_item_opt ADD CONSTRAINT ezproductcollection_item_opt_pkey PRIMARY KEY( "id" );
ALTER TABLE ezrole DROP CONSTRAINT ezrole891_key;
ALTER TABLE ONLY ezrole ADD CONSTRAINT ezrole_pkey PRIMARY KEY( "id" );
ALTER TABLE ezsearch_object_word_link DROP CONSTRAINT ezsearch_object_word_link904_key;
DROP INDEX ezsearch_object_word_link_frequency921;
DROP INDEX ezsearch_object_word_link_identifier922;
DROP INDEX ezsearch_object_word_link_integer_value923;
DROP INDEX ezsearch_object_word_link_object919;
DROP INDEX ezsearch_object_word_link_word920;
ALTER TABLE ONLY ezsearch_object_word_link ADD CONSTRAINT ezsearch_object_word_link_pkey PRIMARY KEY( "id" );
CREATE INDEX ezsearch_object_word_link_frequency ON ezsearch_object_word_link USING btree( "frequency" );
CREATE INDEX ezsearch_object_word_link_identifier ON ezsearch_object_word_link USING btree( "identifier" );
CREATE INDEX ezsearch_object_word_link_integer_value ON ezsearch_object_word_link USING btree( "integer_value" );
CREATE INDEX ezsearch_object_word_link_object ON ezsearch_object_word_link USING btree( "contentobject_id" );
CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link USING btree( "word_id" );
ALTER TABLE ezsearch_return_count DROP CONSTRAINT ezsearch_return_count931_key;
ALTER TABLE ONLY ezsearch_return_count ADD CONSTRAINT ezsearch_return_count_pkey PRIMARY KEY( "id" );
ALTER TABLE ezsearch_search_phrase DROP CONSTRAINT ezsearch_search_phrase944_key;
ALTER TABLE ONLY ezsearch_search_phrase ADD CONSTRAINT ezsearch_search_phrase_pkey PRIMARY KEY( "id" );
ALTER TABLE ezsearch_word DROP CONSTRAINT ezsearch_word955_key;
ALTER TABLE ONLY ezsearch_word ADD CONSTRAINT ezsearch_word_pkey PRIMARY KEY( "id" );
ALTER TABLE ezsection DROP CONSTRAINT ezsection968_key;
ALTER TABLE ONLY ezsection ADD CONSTRAINT ezsection_pkey PRIMARY KEY( "id" );
DROP INDEX expiration_time986;
ALTER TABLE ezsession DROP CONSTRAINT ezsession981_key;
ALTER TABLE ONLY ezsession ADD CONSTRAINT ezsession_pkey PRIMARY KEY( "session_key" );
CREATE INDEX expiration_time ON ezsession USING btree( "expiration_time" );
ALTER TABLE ezsubtree_notification_rule DROP CONSTRAINT tmp_notification_rule_pkey;
ALTER TABLE ONLY ezsubtree_notification_rule ADD CONSTRAINT ezsubtree_notification_rule_pkey PRIMARY KEY( "id" );
ALTER TABLE eztrigger DROP CONSTRAINT eztrigger1007_key;
DROP INDEX eztrigger_def_id1015;
ALTER TABLE ONLY eztrigger ADD CONSTRAINT eztrigger_pkey PRIMARY KEY( "id" );
CREATE UNIQUE INDEX eztrigger_def_id ON eztrigger USING btree( "module_name", "function_name", "connect_type" );
ALTER TABLE ezurl DROP CONSTRAINT ezurl1023_key;
ALTER TABLE ONLY ezurl ADD CONSTRAINT ezurl_pkey PRIMARY KEY( "id" );
ALTER TABLE ezurlalias DROP CONSTRAINT ezurlalias1051_key;
DROP INDEX ezurlalias_source_md51059;
ALTER TABLE ONLY ezurlalias ADD CONSTRAINT ezurlalias_pkey PRIMARY KEY( "id" );
CREATE INDEX ezurlalias_source_md5 ON ezurlalias USING btree( "source_md5" );
ALTER TABLE ezuser DROP CONSTRAINT ezuser1067_key;
ALTER TABLE ONLY ezuser ADD CONSTRAINT ezuser_pkey PRIMARY KEY( "contentobject_id" );
ALTER TABLE ezuser_accountkey DROP CONSTRAINT ezuser_accountkey1081_key;
ALTER TABLE ONLY ezuser_accountkey ADD CONSTRAINT ezuser_accountkey_pkey PRIMARY KEY( "id" );
ALTER TABLE ezuser_discountrule DROP CONSTRAINT ezuser_discountrule1094_key;
ALTER TABLE ONLY ezuser_discountrule ADD CONSTRAINT ezuser_discountrule_pkey PRIMARY KEY( "id" );
ALTER TABLE ezuser_role DROP CONSTRAINT ezuser_role1107_key;
DROP INDEX ezuser_role_contentobject_id1112;
ALTER TABLE ONLY ezuser_role ADD CONSTRAINT ezuser_role_pkey PRIMARY KEY( "id" );
CREATE INDEX ezuser_role_contentobject_id ON ezuser_role USING btree( "contentobject_id" );
ALTER TABLE ezuser_setting DROP CONSTRAINT ezuser_setting1120_key;
ALTER TABLE ONLY ezuser_setting ADD CONSTRAINT ezuser_setting_pkey PRIMARY KEY( "user_id" );
ALTER TABLE ezvattype DROP CONSTRAINT ezvattype1132_key;
ALTER TABLE ONLY ezvattype ADD CONSTRAINT ezvattype_pkey PRIMARY KEY( "id" );
DROP INDEX ezwaituntildateevalue_wf_ev_id_wf_ver1151;
ALTER TABLE ezwaituntildatevalue DROP CONSTRAINT ezwaituntildatevalue1144_key;
ALTER TABLE ONLY ezwaituntildatevalue ADD CONSTRAINT ezwaituntildatevalue_pkey PRIMARY KEY( "id", "workflow_event_id", "workflow_event_version" );
CREATE INDEX ezwaituntildateevalue_wf_ev_id_wf_ver ON ezwaituntildatevalue USING btree( "workflow_event_id", "workflow_event_version" );
ALTER TABLE ezwishlist DROP CONSTRAINT ezwishlist1159_key;
ALTER TABLE ONLY ezwishlist ADD CONSTRAINT ezwishlist_pkey PRIMARY KEY( "id" );
ALTER TABLE ezworkflow DROP CONSTRAINT ezworkflow1171_key;
ALTER TABLE ONLY ezworkflow ADD CONSTRAINT ezworkflow_pkey PRIMARY KEY( "id", "version" );
ALTER TABLE ezworkflow_assign DROP CONSTRAINT ezworkflow_assign1189_key;
ALTER TABLE ONLY ezworkflow_assign ADD CONSTRAINT ezworkflow_assign_pkey PRIMARY KEY( "id" );
ALTER TABLE ezworkflow_event DROP CONSTRAINT ezworkflow_event1203_key;
ALTER TABLE ONLY ezworkflow_event ADD CONSTRAINT ezworkflow_event_pkey PRIMARY KEY( "id", "version" );
ALTER TABLE ezworkflow_group DROP CONSTRAINT ezworkflow_group1226_key;
ALTER TABLE ONLY ezworkflow_group ADD CONSTRAINT ezworkflow_group_pkey PRIMARY KEY( "id" );
ALTER TABLE ezworkflow_group_link DROP CONSTRAINT ezworkflow_group_link1241_key;
ALTER TABLE ONLY ezworkflow_group_link ADD CONSTRAINT ezworkflow_group_link_pkey PRIMARY KEY( "workflow_id", "group_id", "workflow_version" );
ALTER TABLE ezworkflow_process DROP CONSTRAINT ezworkflow_process1254_key;
ALTER TABLE ONLY ezworkflow_process ADD CONSTRAINT ezworkflow_process_pkey PRIMARY KEY( "id" );

-- Some minor fixes to schema to make it 100% equal to MySQL
-- Difference in SQL commands from MySQL to PostgreSQL
ALTER TABLE ezcontentobject_attribute RENAME COLUMN data_type_string TO data_type_string_tmp;
ALTER TABLE ezcontentobject_attribute ADD COLUMN data_type_string character varying(50);
ALTER TABLE ezcontentobject_attribute ALTER data_type_string SET DEFAULT '' ;
UPDATE ezcontentobject_attribute SET data_type_string=data_type_string_tmp;
ALTER TABLE ezcontentobject_attribute DROP COLUMN data_type_string_tmp;

ALTER TABLE ezmedia RENAME COLUMN has_controller TO has_controller_tmp;
ALTER TABLE ezmedia ADD COLUMN has_controller integer;
ALTER TABLE ezmedia ALTER has_controller SET DEFAULT '0' ;
UPDATE ezmedia SET has_controller=has_controller_tmp;
ALTER TABLE ezmedia DROP COLUMN has_controller_tmp;

ALTER TABLE ezmedia RENAME COLUMN is_autoplay TO is_autoplay_tmp;
ALTER TABLE ezmedia ADD COLUMN is_autoplay integer;
ALTER TABLE ezmedia ALTER is_autoplay SET DEFAULT '0' ;
UPDATE ezmedia SET is_autoplay=is_autoplay_tmp;
ALTER TABLE ezmedia DROP COLUMN is_autoplay_tmp;

ALTER TABLE ezmedia RENAME COLUMN is_loop TO is_loop_tmp;
ALTER TABLE ezmedia ADD COLUMN is_loop integer;
ALTER TABLE ezmedia ALTER is_loop SET DEFAULT '0' ;
UPDATE ezmedia SET is_loop=is_loop_tmp;
ALTER TABLE ezmedia DROP COLUMN is_loop_tmp;

ALTER TABLE ezproductcollection_item RENAME COLUMN price TO price_tmp;
ALTER TABLE ezproductcollection_item ADD COLUMN price double precision;
ALTER TABLE ezproductcollection_item ALTER price SET DEFAULT '0' ;
UPDATE ezproductcollection_item SET price=price_tmp;
ALTER TABLE ezproductcollection_item DROP COLUMN price_tmp;

ALTER TABLE ezrss_export_item RENAME COLUMN description TO description_tmp;
ALTER TABLE ezrss_export_item ADD COLUMN description character varying(255);
ALTER TABLE ezrss_export_item ALTER description SET DEFAULT NULL ;
UPDATE ezrss_export_item SET description=description_tmp;
ALTER TABLE ezrss_export_item DROP COLUMN description_tmp;

 -- ezpdf_export
 -- Added support for versioning (class-type)

ALTER TABLE ezpdf_export
    DROP CONSTRAINT ezpdf_export_pkey;

ALTER TABLE ezpdf_export
    ADD COLUMN version integer;
UPDATE ezpdf_export SET version='0';
ALTER TABLE ezpdf_export ALTER version SET DEFAULT 0;
ALTER TABLE ezpdf_export ALTER version SET NOT NULL;

ALTER TABLE ezpdf_export
    ADD CONSTRAINT ezpdf_export_pkey PRIMARY KEY (id,version);

 -- ezrss_import
 -- Added support for versioning (class-type) by reusing status attribute

ALTER TABLE ezrss_import
    DROP CONSTRAINT ezrss_import_pkey;

UPDATE ezrss_import SET status=1 WHERE status=NULL;

ALTER TABLE ezrss_import ALTER status SET DEFAULT 0;
ALTER TABLE ezrss_import ALTER status SET NOT NULL;

ALTER TABLE ezrss_import
    ADD CONSTRAINT ezrss_import_pkey PRIMARY KEY (id,status);

 -- ezrss_export
 -- Added support for versioning (class-type) by reusing status attribute

ALTER TABLE ezrss_export
    DROP CONSTRAINT ezrss_export_pkey;

UPDATE ezrss_export SET status=1 WHERE status=NULL;

ALTER TABLE ezrss_export ALTER status SET DEFAULT 0;
ALTER TABLE ezrss_export ALTER status SET NOT NULL;

ALTER TABLE ezrss_export
    ADD CONSTRAINT ezrss_export_pkey PRIMARY KEY (id,status);

 -- ezrss_export_item
 -- Added support for versioning (class-type) by introducing status attribute

ALTER TABLE ezrss_export_item
    DROP CONSTRAINT ezrss_export_item_pkey;

ALTER TABLE ezrss_export_item
    ADD COLUMN status integer;

UPDATE ezrss_export_item SET status=1;

ALTER TABLE ezrss_export_item ALTER status SET DEFAULT 0;
ALTER TABLE ezrss_export_item ALTER status SET NOT NULL;

ALTER TABLE ezrss_export_item
    ADD CONSTRAINT ezrss_export_item_pkey PRIMARY KEY (id,status);

 -- ezproductcollection_item
 -- Added attribute name for storing a product name

ALTER TABLE ezproductcollection_item ADD COLUMN name varchar(255);
UPDATE ezproductcollection_item SET name='Unknown product';
ALTER TABLE ezproductcollection_item ALTER name SET NOT NULL;
ALTER TABLE ezproductcollection_item ALTER name SET DEFAULT '';

-- 3.5.0rc1 to 3.5.0rc2


-- 3.5.0rc2 to 3.5.0
