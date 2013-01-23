SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.1.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontentclass ADD INDEX ezcontentclass_identifier (identifier, version);
ALTER TABLE ezcontentobject_tree ADD INDEX ezcontentobject_tree_remote_id (remote_id);
ALTER TABLE ezcontentobject_version ADD INDEX ezcontobj_version_obj_status (contentobject_id, status);
ALTER TABLE ezpolicy ADD INDEX ezpolicy_role_id (role_id);
ALTER TABLE ezpolicy_limitation_value ADD INDEX ezpolicy_limit_value_limit_id (limitation_id);
