UPDATE ezsite_data SET value='3.8.10' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='16' WHERE name='ezpublish-release';

ALTER table ezsearch_word ADD KEY ezsearch_word_obj_count(object_count);

DROP INDEX ezurl_url ON ezurl;
ALTER TABLE ezurl MODIFY url longtext;
ALTER table ezurl ADD KEY ezurl_url( url(255) );
