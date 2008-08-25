UPDATE ezsite_data SET value='4.0.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- START: from 3.10.1
ALTER TABLE ezcontent_language ADD INDEX ezcontent_language_name(name);

ALTER TABLE ezcontentobject ADD INDEX ezcontentobject_owner(owner_id);

ALTER TABLE ezcontentobject ADD UNIQUE INDEX ezcontentobject_remote_id(remote_id);

ALTER TABLE ezurlalias_ml ADD alias_redirects int(11) NOT NULL default 1;
-- END: from 3.10.1

