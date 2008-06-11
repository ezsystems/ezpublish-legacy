UPDATE ezsite_data SET value='4.0.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

-- START: from 3.10.1
CREATE INDEX ezcontent_language_name ON ezcontent_language (name);

CREATE INDEX ezcontentobject_owner ON ezcontentobject (owner_id);

CREATE UNIQUE INDEX ezcontentobject_remote_id ON ezcontentobject (remote_id);
-- END: from 3.10.1

ALTER TABLE ezurlalias_ml ADD COLUMN alias_redirects INT;
ALTER TABLE ezurlalias_ml ALTER COLUMN alias_redirects SET default 1;
ALTER TABLE ezurlalias_ml ALTER COLUMN alias_redirects SET NOT NULL;
