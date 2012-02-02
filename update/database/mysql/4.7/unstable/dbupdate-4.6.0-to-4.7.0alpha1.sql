SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.7.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';


ALTER TABLE ezpending_actions ADD COLUMN id int(11) AUTO_INCREMENT PRIMARY KEY;
