UPDATE ezsite_data SET value='3.10.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER table ezsearch_word ADD KEY ezsearch_word_obj_count(object_count);