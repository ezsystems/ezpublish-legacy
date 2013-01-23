UPDATE ezsite_data SET value='5.1.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE INDEX ezcontentclass_identifier ON ezcontentclass USING btree (identifier, version);
CREATE INDEX ezcontentobject_tree_remote_id ON ezcontentobject_tree USING btree (remote_id);
CREATE INDEX ezcontobj_version_obj_status ON ezcontentobject_version USING btree (contentobject_id, status);
CREATE INDEX ezpolicy_role_id ON ezpolicy USING btree (role_id);
CREATE INDEX ezpolicy_limit_value_limit_id ON ezpolicy_limitation_value USING btree (limitation_id);
