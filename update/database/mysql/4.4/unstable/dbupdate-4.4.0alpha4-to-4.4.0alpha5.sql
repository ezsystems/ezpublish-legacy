SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.4.0alpha5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';
ALTER TABLE ezsection ADD section_identifier VARCHAR(255);
