UPDATE ezsite_data SET value='3.7.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder ADD is_archived INT;
ALTER TABLE ezorder ALTER COLUMN is_archived SET DEFAULT 0;
ALTER TABLE ezorder ALTER COLUMN is_archived SET NOT NULL;

CREATE INDEX ezorder_is_archived ON ezorder USING btree (is_archived);

