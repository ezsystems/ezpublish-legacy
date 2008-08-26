UPDATE ezsite_data SET value='4.1.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezworkflow_event ADD COLUMN data_text5 TEXT;

ALTER TABLE ezrss_export ADD COLUMN node_id INT NULL;
ALTER TABLE ezrss_export_item ADD COLUMN category VARCHAR( 255 ) NULL;

-- START: from 4.0.1
CREATE INDEX ezcontent_language_name ON ezcontent_language (name);

CREATE INDEX ezcontentobject_owner ON ezcontentobject (owner_id);

CREATE UNIQUE INDEX ezcontentobject_remote_id ON ezcontentobject (remote_id);
-- END: from 4.0.1

CREATE UNIQUE INDEX ezgeneral_digest_user_settings_address ON ezgeneral_digest_user_settings (address);
DELETE FROM ezgeneral_digest_user_settings WHERE address not in (SELECT email FROM ezuser);

-- START: from 3.10.1
ALTER TABLE ezurlalias_ml ADD COLUMN alias_redirects INT;
ALTER TABLE ezurlalias_ml ALTER COLUMN alias_redirects SET default 1;
ALTER TABLE ezurlalias_ml ALTER COLUMN alias_redirects SET NOT NULL;
-- END: from 3.10.1

ALTER TABLE ezbinaryfile ALTER COLUMN mime_type TYPE character varying(255);
