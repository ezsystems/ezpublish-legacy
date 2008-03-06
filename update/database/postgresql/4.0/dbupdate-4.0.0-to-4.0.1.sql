UPDATE ezsite_data SET value='4.0.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

CREATE INDEX ezcontent_language_name ON ezcontent_language (name);

