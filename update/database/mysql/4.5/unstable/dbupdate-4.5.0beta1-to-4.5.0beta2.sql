SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.5.0beta2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezsection MODIFY COLUMN identifier VARCHAR(255);
UPDATE ezsection SET identifier=NULL WHERE identifier='';