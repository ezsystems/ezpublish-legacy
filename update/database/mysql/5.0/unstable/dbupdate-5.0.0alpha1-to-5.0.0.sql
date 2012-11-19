SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.0.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE eznode_assignment CHANGE remote_id remote_id VARCHAR(100);
