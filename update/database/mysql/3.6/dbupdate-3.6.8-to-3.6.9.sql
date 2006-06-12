UPDATE ezsite_data SET value='3.6.9' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='13' WHERE name='ezpublish-release';

CREATE INDEX ezkeyword_keyword_id ON ezkeyword ( keyword, id );
CREATE INDEX ezkeyword_attr_link_kid_oaid ON ezkeyword_attribute_link ( keyword_id, objectattribute_id );

CREATE INDEX ezurlalias_is_wildcard ON ezurlalias( is_wildcard );

CREATE INDEX eznode_assignment_coid_cov ON eznode_assignment( contentobject_id,contentobject_version );
CREATE INDEX eznode_assignment_is_main ON eznode_assignment( is_main );
CREATE INDEX eznode_assignment_parent_node ON eznode_assignment( parent_node );
