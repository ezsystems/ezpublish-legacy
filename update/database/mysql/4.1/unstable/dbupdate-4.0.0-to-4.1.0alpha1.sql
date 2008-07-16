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