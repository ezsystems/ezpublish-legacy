UPDATE ezsite_data SET value='4.4.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontentobject DROP COLUMN is_published;

ALTER TABLE ezsection ADD COLUMN identifier character varying(255);

CREATE INDEX ezinfocollection_attr_cca_id ON ezinfocollection_attribute USING btree ( contentclass_attribute_id );
CREATE INDEX ezinfocollection_attr_coa_id ON ezinfocollection_attribute USING btree ( contentobject_attribute_id );
CREATE INDEX ezinfocollection_attr_ic_id ON ezinfocollection_attribute USING btree ( informationcollection_id );

ALTER TABLE ezpreferences ALTER COLUMN value TYPE text;
ALTER TABLE ezpolicy ADD COLUMN original_id INT NOT NULL DEFAULT 0;
CREATE INDEX ezpolicy_original_id ON ezpolicy USING btree ( original_id );
UPDATE ezcontentclass_attribute SET can_translate=0 WHERE data_type_string='ezuser';

