UPDATE ezsite_data SET value='3.5.0beta2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER TABLE ezrole ADD COLUMN is_new integer;
UPDATE ezrole SET is_new=0;
ALTER TABLE ezrole ALTER is_new SET NOT NULL;
ALTER TABLE ezrole ALTER is_new SET DEFAULT 0;

