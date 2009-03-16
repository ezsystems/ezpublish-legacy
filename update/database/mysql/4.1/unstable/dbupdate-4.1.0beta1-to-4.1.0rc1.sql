UPDATE ezsite_data SET value='4.1.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezpending_actions ADD COLUMN created int(11) DEFAULT NULL;

ALTER TABLE ezpending_actions ADD INDEX ezpending_actions_created ( created );
