UPDATE ezsite_data SET value='3.4.2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='9' WHERE name='ezpublish-release';


-- Fix faulty index on ezurl_object_link
ALTER TABLE DROP CONSTRAINT ezurl_object_link1039_key;
CREATE INDEX ezurl_ol_url_id ON ezurl_object_link (url_id);
CREATE INDEX ezurl_ol_coa_id ON ezurl_object_link (contentobject_attribute_id);
CREATE INDEX ezurl_ol_coa_version ON ezurl_object_link (contentobject_attribute_version);
