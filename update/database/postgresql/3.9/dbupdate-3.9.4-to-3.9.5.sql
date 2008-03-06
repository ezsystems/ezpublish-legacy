UPDATE ezsite_data SET value='3.9.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='11' WHERE name='ezpublish-release';

CREATE INDEX ezcontent_language_name ON ezcontent_language (name);

