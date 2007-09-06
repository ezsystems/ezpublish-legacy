UPDATE ezsite_data SET value='3.9.4' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='10' WHERE name='ezpublish-release';

ALTER table ezsearch_word ADD KEY ezsearch_word_obj_count(object_count);