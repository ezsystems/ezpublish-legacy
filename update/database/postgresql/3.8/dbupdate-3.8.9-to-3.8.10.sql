UPDATE ezsite_data SET value='3.8.10' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='16' WHERE name='ezpublish-release';

CREATE INDEX ezsearch_word_obj_count on ezsearch_word(object_count);