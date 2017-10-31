SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='6.12.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezuser CHANGE password_hash password_hash VARCHAR(255) default NULL;
