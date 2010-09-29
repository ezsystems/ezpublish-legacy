SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.4.0beta2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';
ALTER TABLE ezpreferences MODIFY COLUMN value longtext;
ALTER TABLE ezpolicy ADD original_id INT(11) NOT NULL DEFAULT '0';
ALTER TABLE ezpolicy ADD INDEX ezpolicy_original_id ( original_id );
UPDATE ezcontentclass_attribute SET can_translate=0 WHERE data_type_string='ezuser';
