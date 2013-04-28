SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.1.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontentclass ADD INDEX ezcontentclass_identifier (identifier, version);
ALTER TABLE ezcontentobject_tree ADD INDEX ezcontentobject_tree_remote_id (remote_id);
ALTER TABLE ezcontentobject_version ADD INDEX ezcontobj_version_obj_status (contentobject_id, status);
ALTER TABLE ezpolicy ADD INDEX ezpolicy_role_id (role_id);
ALTER TABLE ezpolicy_limitation_value ADD INDEX ezpolicy_limit_value_limit_id (limitation_id);
ALTER TABLE ezcontentobject_attribute
    DROP INDEX ezcontentobject_attribute_contentobject_id,
    DROP INDEX ezcontentobject_attr_id;
ALTER TABLE ezcontentobject_name DROP INDEX ezcontentobject_name_co_id;
ALTER TABLE ezenumobjectvalue DROP INDEX ezenumobjectvalue_co_attr_id_co_attr_ver;
ALTER TABLE ezkeyword DROP INDEX ezkeyword_keyword_id;
ALTER TABLE ezkeyword_attribute_link DROP INDEX ezkeyword_attr_link_keyword_id;
ALTER TABLE eznode_assignment DROP INDEX eznode_assignment_co_id;
ALTER TABLE ezprest_clients DROP INDEX client_id;

ALTER TABLE ezurlalias_ml
    DROP INDEX ezurlalias_ml_actt,
-- Combining "ezurlalias_ml_par_txt" and "ezurlalias_ml_par_lnk_txt" by moving "link" after "text(32)" in the latter:
    DROP INDEX ezurlalias_ml_par_txt,
    DROP INDEX ezurlalias_ml_par_lnk_txt,
    ADD INDEX ezurlalias_ml_par_lnk_txt (parent, text(32), link),

-- Combining "ezurlalias_ml_action" and "ezurlalias_ml_par_act_id_lnk" by moving "parent" after "link" in the latter:
    DROP INDEX ezurlalias_ml_action,
    DROP INDEX ezurlalias_ml_par_act_id_lnk,
    ADD INDEX ezurlalias_ml_par_act_id_lnk (action(32), id, link, parent);

-- See https://jira.ez.no/browse/EZP-20239
DELETE FROM ezcontentobject_link WHERE op_code <> 0;
DELETE FROM ezcontentobject_link WHERE relation_type = 0;
ALTER TABLE ezcontentobject_link DROP COLUMN op_code;

-- See https://jira.ez.no/browse/EZP-20527
UPDATE ezcobj_state_group_language SET real_language_id = language_id & ~1;

-- See https://jira.ez.no/browse/EZP-20673
ALTER TABLE eznode_assignment CHANGE COLUMN remote_id remote_id varchar(100) NOT NULL DEFAULT '0';
