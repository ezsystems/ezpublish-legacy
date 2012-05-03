SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.7.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontentobject_attribute MODIFY COLUMN data_float double default NULL;
ALTER TABLE ezcontentclass_attribute MODIFY COLUMN data_float1 double default NULL;
ALTER TABLE ezcontentclass_attribute MODIFY COLUMN data_float2 double default NULL;
ALTER TABLE ezcontentclass_attribute MODIFY COLUMN data_float3 double default NULL;
ALTER TABLE ezcontentclass_attribute MODIFY COLUMN data_float4 double default NULL;
