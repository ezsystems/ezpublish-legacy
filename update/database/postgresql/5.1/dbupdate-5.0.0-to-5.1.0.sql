UPDATE ezsite_data SET value='5.1.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE INDEX ezcontentclass_identifier ON ezcontentclass USING btree (identifier, version);
CREATE INDEX ezcontentobject_tree_remote_id ON ezcontentobject_tree USING btree (remote_id);
CREATE INDEX ezcontobj_version_obj_status ON ezcontentobject_version USING btree (contentobject_id, status);
CREATE INDEX ezpolicy_role_id ON ezpolicy USING btree (role_id);
CREATE INDEX ezpolicy_limit_value_limit_id ON ezpolicy_limitation_value USING btree (limitation_id);
DROP INDEX ezcontentobject_attribute_contentobject_id;
DROP INDEX ezcontentobject_attr_id;
DROP INDEX ezcontentobject_name_co_id;
DROP INDEX ezenumobjectvalue_co_attr_id_co_attr_ver;
DROP INDEX ezkeyword_keyword_id;
DROP INDEX ezkeyword_attr_link_keyword_id;
DROP INDEX eznode_assignment_co_id;
DROP INDEX client_id;

DROP INDEX ezurlalias_ml_actt;
-- Combining "ezurlalias_ml_par_txt" and "ezurlalias_ml_par_lnk_txt" by moving "link" after "text(32)" in the latter:
DROP INDEX ezurlalias_ml_par_txt;
DROP INDEX ezurlalias_ml_par_lnk_txt;
CREATE INDEX ezurlalias_ml_par_lnk_txt ON ezurlalias_ml USING btree (parent, text, link);

-- Combining "ezurlalias_ml_action" and "ezurlalias_ml_par_act_id_lnk" by moving "parent" after "link" in the latter:
DROP INDEX ezurlalias_ml_action;
DROP INDEX ezurlalias_ml_par_act_id_lnk;
CREATE INDEX ezurlalias_ml_par_act_id_lnk ON ezurlalias_ml USING btree ("action", id, link, parent);

-- See https://jira.ez.no/browse/EZP-20239
DELETE FROM ezcontentobject_link WHERE op_code <> 0;
DELETE FROM ezcontentobject_link WHERE relation_type = 0;
ALTER TABLE ezcontentobject_link DROP COLUMN op_code;

-- See https://jira.ez.no/browse/EZP-20527
UPDATE ezcobj_state_group_language SET real_language_id = language_id & ~1;

-- See https://jira.ez.no/browse/EZP-20673
ALTER TABLE eznode_assignment ALTER COLUMN remote_id TYPE character varying(100);
