UPDATE ezsite_data SET value='4.4.0alpha5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';
ALTER TABLE ezsection ADD COLUMN section_identifier character varying(255);
