UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER TABLE ezcontentobject_attribute CHANGE COLUMN data_text data_text MEDIUMTEXT;
