UPDATE ezsite_data SET value='4.4.0beta2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';
ALTER TABLE ezpreferences ALTER COLUMN value TYPE text;
ALTER TABLE ezpolicy ADD COLUMN original_id INT NOT NULL DEFAULT 0;
CREATE INDEX ezpolicy_original_id ON ezpolicy USING btree ( original_id );
UPDATE ezcontentclass_attribute SET can_translate=0 WHERE data_type_string='ezuser';
