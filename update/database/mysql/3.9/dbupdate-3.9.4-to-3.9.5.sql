UPDATE ezsite_data SET value='3.9.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='11' WHERE name='ezpublish-release';

ALTER TABLE ezcontent_language ADD INDEX ezcontent_language_name(name);

