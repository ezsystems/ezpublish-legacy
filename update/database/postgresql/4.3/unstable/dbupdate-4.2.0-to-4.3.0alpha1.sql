UPDATE ezsite_data SET value='4.3.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezrss_export_item ADD COLUMN enclosure character varying(255);
ALTER TABLE ezrss_export_item ALTER enclosure SET DEFAULT NULL;

ALTER TABLE ezcontentclass ADD COLUMN serialized_description_list character;
ALTER TABLE ezcontentclass ALTER serialized_description_list SET DEFAULT NULL;

ALTER TABLE ezcontentclass_attribute ADD COLUMN serialized_data_text character;
ALTER TABLE ezcontentclass_attribute ALTER serialized_data_text SET DEFAULT NULL;
ALTER TABLE ezcontentclass_attribute ADD COLUMN serialized_description_list character;
ALTER TABLE ezcontentclass_attribute ALTER serialized_description_list SET DEFAULT NULL;
ALTER TABLE ezcontentclass_attribute ADD COLUMN category character varying(25);
