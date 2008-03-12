UPDATE ezsite_data SET value='3.9.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='11' WHERE name='ezpublish-release';

ALTER TABLE ezcontent_language ADD INDEX ezcontent_language_name(name);

ALTER TABLE ezcontentobject ADD INDEX ezcontentobject_owner(owner_id);

ALTER TABLE ezcontentobject ADD UNIQUE INDEX ezcontentobject_remote_id(remote_id);

