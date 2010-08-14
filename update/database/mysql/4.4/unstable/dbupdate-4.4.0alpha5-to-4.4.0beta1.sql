SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.4.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';
ALTER TABLE ezinfocollection_attribute ADD INDEX ezinfocollection_attr_cca_id ( contentclass_attribute_id );
ALTER TABLE ezinfocollection_attribute ADD INDEX ezinfocollection_attr_coa_id ( contentobject_attribute_id );
ALTER TABLE ezinfocollection_attribute ADD INDEX ezinfocollection_attr_ic_id ( informationcollection_id );
ALTER TABLE ezsection CHANGE section_identifier identifier VARCHAR(255);
