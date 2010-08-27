SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.3.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';
UPDATE ezcontentclass_attribute SET can_translate=0 WHERE data_type_string='ezuser';
