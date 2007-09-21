UPDATE ezsite_data SET value='3.10.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

CREATE INDEX  ezsearch_word_obj_count ON ezsearch_word(object_count);

DROP INDEX ezurl_url;
ALTER TABLE ezurl ALTER url TYPE text;
CREATE INDEX ezurl_url ON ezurl(url);
