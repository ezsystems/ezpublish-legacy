SET table_type=InnoDB;
UPDATE ezsite_data SET value='4.1.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezworkflow_event ADD COLUMN data_text5 LONGTEXT;

ALTER TABLE ezrss_export ADD COLUMN node_id INT NULL;
ALTER TABLE ezrss_export_item ADD COLUMN category VARCHAR( 255 ) NULL;

-- START: from 4.0.1
ALTER TABLE ezcontent_language ADD INDEX ezcontent_language_name(name);

ALTER TABLE ezcontentobject ADD INDEX ezcontentobject_owner(owner_id);

ALTER TABLE ezcontentobject ADD UNIQUE INDEX ezcontentobject_remote_id(remote_id);
-- END: from 4.0.1

ALTER TABLE ezgeneral_digest_user_settings ADD UNIQUE INDEX ezgeneral_digest_user_settings_address(address);
DELETE FROM ezgeneral_digest_user_settings WHERE address not in (SELECT email FROM ezuser);

-- START: from 3.10.1
ALTER TABLE ezurlalias_ml ADD alias_redirects int(11) NOT NULL default 1;
-- END: from 3.10.1

ALTER TABLE ezbinaryfile MODIFY COLUMN mime_type VARCHAR(255) NOT NULL;

CREATE TABLE ezcontentobject_state_group (
  default_language_id int(10) NOT NULL default '0',
  id int(11) NOT NULL auto_increment,
  identifier varchar(45) NOT NULL default '',
  language_mask int(10) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY ezcontentobject_state_group_identifier (identifier)
);

CREATE TABLE ezcontentobject_state_group_language (
  contentobject_state_group_id int(10) NOT NULL default '0',
  description longtext NOT NULL,
  language_id int(10) NOT NULL default '0',
  name varchar(45) NOT NULL default '',
  PRIMARY KEY  (language_id,contentobject_state_group_id)
);

CREATE TABLE ezcontentobject_state_language (
  contentobject_state_id int(10) NOT NULL default '0',
  default int(10) default NULL,
  description longtext NOT NULL,
  language_id int(10) NOT NULL default '0',
  name varchar(45) NOT NULL default '',
  PRIMARY KEY  (contentobject_state_id,language_id)
);

CREATE TABLE ezcontentobject_state_link (
  contentobject_id int(10) NOT NULL default '0',
  contentobject_state_id int(10) NOT NULL default '0',
  PRIMARY KEY  (contentobject_id,contentobject_state_id)
);