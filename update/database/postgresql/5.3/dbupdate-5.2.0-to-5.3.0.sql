UPDATE ezsite_data SET value='5.3.0alpha1' WHERE name='ezpublish-version';

CREATE INDEX ezcontentobject_classattr_id ON ezcontentobject_attribute USING btree (contentclassattribute_id);
