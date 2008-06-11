UPDATE ezsite_data SET value='3.10.1rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';

CREATE TABLE ezurlwildcard (
  id int(11) NOT NULL auto_increment,
  source_url longtext NOT NULL,
  destination_url longtext NOT NULL,
  type int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

-- START: from 3.9.5
ALTER TABLE ezcontent_language ADD INDEX ezcontent_language_name(name);

ALTER TABLE ezcontentobject ADD INDEX ezcontentobject_owner(owner_id);

ALTER TABLE ezcontentobject ADD UNIQUE INDEX ezcontentobject_remote_id(remote_id);
-- END: from 3.9.5

ALTER TABLE ezurlalias_ml ADD COLUMN alias_redirects int(1) NOT NULL default 1;
