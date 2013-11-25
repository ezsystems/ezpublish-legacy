SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.3.0alpha1' WHERE name='ezpublish-version';

ALTER TABLE ezcontentobject_attribute
    ADD KEY ezcontentobject_classattr_id (contentclassattribute_id);