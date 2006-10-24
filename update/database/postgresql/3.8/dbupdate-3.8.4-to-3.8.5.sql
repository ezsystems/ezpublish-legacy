UPDATE ezsite_data SET value='3.8.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='11' WHERE name='ezpublish-release';

-- ezcontentobject
CREATE INDEX ezcontentobject_pub ON ezcontentobject USING btree ( published );
CREATE INDEX ezcontentobject_status ON ezcontentobject USING btree ( status );
CREATE INDEX ezcontentobject_classid ON ezcontentobject USING btree ( contentclass_id );
CREATE INDEX ezcontentobject_currentversion ON ezcontentobject USING btree ( current_version );

-- ezcontentobject_name
CREATE INDEX ezcontentobject_name_lang_id ON ezcontentobject_name USING btree ( language_id );
CREATE INDEX ezcontentobject_name_name ON ezcontentobject_name USING btree ( name );
CREATE INDEX ezcontentobject_name_co_id ON ezcontentobject_name USING btree ( contentobject_id );
CREATE INDEX ezcontentobject_name_cov_id ON ezcontentobject_name USING btree ( content_version );

-- ezcontentobject_version
CREATE INDEX ezcobj_version_creator_id ON ezcontentobject_version USING btree ( creator_id );
CREATE INDEX ezcobj_version_status ON ezcontentobject_version USING btree ( status );

-- ezpolicy_limitation_value
CREATE INDEX ezpolicy_limitation_value_val ON ezpolicy_limitation_value USING btree ( value );

-- ezinfocollection_attribute
CREATE INDEX ezinfocollection_attr_co_id ON ezinfocollection_attribute USING btree ( contentobject_id );

-- ezurlalias
CREATE INDEX ezurlalias_forward_to_id ON ezurlalias USING btree ( forward_to_id );

-- ezkeyword
CREATE INDEX ezkeyword_keyword ON ezkeyword USING btree ( keyword );

-- ezurl
CREATE INDEX ezurl_url ON ezurl USING btree ( url );

-- ezcontentobject_attribute
CREATE INDEX ezcontentobject_attr_id ON ezcontentobject_attribute USING btree ( id );

-- ezcontentoclass_attribute
CREATE INDEX ezcontentclass_attr_ccid ON ezcontentclass_attribute USING btree ( contentclass_id );

-- eznode_assignment
CREATE INDEX eznode_assignment_co_id ON eznode_assignment USING btree ( contentobject_id );
CREATE INDEX eznode_assignment_co_version ON eznode_assignment USING btree ( contentobject_version );

-- ezkeyword_attribute_link
CREATE INDEX ezkeyword_attr_link_keyword_id ON ezkeyword_attribute_link USING btree ( keyword_id );
