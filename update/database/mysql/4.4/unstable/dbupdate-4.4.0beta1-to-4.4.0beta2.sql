SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.4.0beta2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';
ALTER TABLE ezpreferences MODIFY COLUMN value longtext;
ALTER TABLE ezpolicy ADD original_id INT(11);
ALTER TABLE ezinfocollection_attribute ADD INDEX ezoriginal_policy_id ( original_id );
