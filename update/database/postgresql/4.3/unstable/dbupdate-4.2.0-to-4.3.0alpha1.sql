UPDATE ezsite_data SET value='4.3.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezrss_export_item ADD COLUMN enclosure VARCHAR( 255 ) NULL;
ALTER TABLE ezcontentclass ADD COLUMN serialized_description_list text NULL;
ALTER TABLE ezcontentclass_attribute ADD COLUMN serialized_data_text text NULL;
ALTER TABLE ezcontentclass_attribute ADD COLUMN serialized_description_list text NULL;
ALTER TABLE ezcontentclass_attribute ADD COLUMN category VARCHAR( 25 ) NOT NULL;
