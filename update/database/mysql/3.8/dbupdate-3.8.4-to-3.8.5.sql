UPDATE ezsite_data SET value='3.8.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='11' WHERE name='ezpublish-release';

-- ezcontentobject
CREATE INDEX ezcontentobject_pub ON ezcontentobject( published );
CREATE INDEX ezcontentobject_status ON ezcontentobject( status );
CREATE INDEX ezcontentobject_classid ON ezcontentobject( contentclass_id );
CREATE INDEX ezcontentobject_currentversion ON ezcontentobject( current_version );

-- ezcontentobject_name
CREATE INDEX ezcontentobject_name_lang_id ON ezcontentobject_name( language_id );
CREATE INDEX ezcontentobject_name_name ON ezcontentobject_name( name );
CREATE INDEX ezcontentobject_name_co_id ON ezcontentobject_name( contentobject_id );
CREATE INDEX ezcontentobject_name_cov_id ON ezcontentobject_name( content_version );

-- ezcontentobject_version
CREATE INDEX ezcobj_version_creator_id ON ezcontentobject_version( creator_id );
CREATE INDEX ezcobj_version_status ON ezcontentobject_version( status );

-- ezpolicy_limitation_value
CREATE INDEX ezpolicy_limitation_value_val ON ezpolicy_limitation_value( value );

-- ezinfocollection_attribute
CREATE INDEX ezinfocollection_attr_co_id ON ezinfocollection_attribute( contentobject_id );

-- ezurlalias
CREATE INDEX ezurlalias_forward_to_id ON ezurlalias( forward_to_id );

-- ezkeyword
CREATE INDEX ezkeyword_keyword ON ezkeyword( keyword );

-- ezurl
CREATE INDEX ezurl_url ON ezurl( url );

-- ezcontentobject_attribute
CREATE INDEX ezcontentobject_attr_id ON ezcontentobject_attribute( id );

-- ezcontentoclass_attribute
CREATE INDEX ezcontentclass_attr_ccid ON ezcontentclass_attribute( contentclass_id );

-- eznode_assignment
CREATE INDEX eznode_assignment_co_id ON eznode_assignment( contentobject_id );
CREATE INDEX eznode_assignment_co_version ON eznode_assignment( contentobject_version );

-- ezkeyword_attribute_link
CREATE INDEX ezkeyword_attr_link_keyword_id ON ezkeyword_attribute_link( keyword_id );
