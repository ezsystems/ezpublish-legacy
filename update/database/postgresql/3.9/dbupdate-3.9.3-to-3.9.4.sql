UPDATE ezsite_data SET value='3.9.4' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='10' WHERE name='ezpublish-release';

CREATE INDEX ezsearch_word_obj_count ON ezsearch_word(object_count);
