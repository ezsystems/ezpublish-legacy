UPDATE ezsite_data SET value='3.5.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER TABLE ezrole ADD COLUMN is_new integer;
UPDATE ezrole SET is_new=0;
ALTER TABLE ezrole ALTER is_new SET NOT NULL;
ALTER TABLE ezrole ALTER is_new SET DEFAULT 0;

-- New name for ezsearch index, the old one crashed with the table name ezsearch_word
DROP INDEX ezsearch_word960;
CREATE INDEX ezsearch_word_word_i ON ezsearch_word USING btree (word);

