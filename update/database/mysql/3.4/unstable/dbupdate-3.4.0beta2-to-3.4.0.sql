UPDATE ezsite_data SET value='3.4.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='7' WHERE name='ezpublish-release';

-- incrementing size of 'sort_key_string' to 255 characters
ALTER TABLE ezcontentobject_attribute MODIFY sort_key_string VARCHAR(255) NOT NULL default '';
