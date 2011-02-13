SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.4.0alpha3' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontentobject DROP COLUMN is_published;
