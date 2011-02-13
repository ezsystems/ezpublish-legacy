UPDATE ezsite_data SET value='4.1.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezpending_actions ADD COLUMN created integer;

CREATE INDEX ezpending_actions_created ON ezpending_actions USING btree ( created );
