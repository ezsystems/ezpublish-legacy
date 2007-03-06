UPDATE ezsite_data SET value='3.10.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- extend length of 'serialized_name_list'
ALTER TABLE ezcontentclass RENAME COLUMN serialized_name_list TO name_tmp;
ALTER TABLE ezcontentclass ADD COLUMN serialized_name_list text;
UPDATE ezcontentclass SET serialized_name_list = name_tmp;
ALTER TABLE ezcontentclass DROP COLUMN name_tmp;

ALTER TABLE ezcontentclass_attribute RENAME COLUMN serialized_name_list TO name_tmp;
ALTER TABLE ezcontentclass_attribute ADD COLUMN serialized_name_list text;
UPDATE ezcontentclass_attribute SET serialized_name_list = name_tmp;
ALTER TABLE ezcontentclass_attribute ALTER serialized_name_list SET NOT NULL;
ALTER TABLE ezcontentclass_attribute DROP COLUMN name_tmp;
