UPDATE ezsite_data SET value='3.5.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

alter table ezrole add column is_new integer not null default 0;

-- New name for ezsearch index, the old one crashed with the table name ezsearch_word
ALTER TABLE ezsearch_word DROP INDEX ezsearch_word;
ALTER TABLE ezsearch_word ADD INDEX ezsearch_word_word_i ( word );

