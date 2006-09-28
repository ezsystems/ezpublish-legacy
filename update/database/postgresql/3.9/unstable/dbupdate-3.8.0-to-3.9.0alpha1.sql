UPDATE ezsite_data SET value='3.9.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE INDEX ezkeyword_keyword_id ON ezkeyword USING btree ( keyword, id );
CREATE INDEX ezkeyword_attr_link_kid_oaid ON ezkeyword_attribute_link USING btree ( keyword_id, objectattribute_id );

CREATE INDEX ezurlalias_is_wildcard ON ezurlalias USING btree ( is_wildcard );

CREATE INDEX eznode_assignment_coid_cov ON eznode_assignment USING btree ( contentobject_id,contentobject_version );
CREATE INDEX eznode_assignment_is_main ON eznode_assignment USING btree ( is_main );
CREATE INDEX eznode_assignment_parent_node ON eznode_assignment USING btree ( parent_node );

ALTER TABLE ezuservisit ADD COLUMN failed_login_attempts int;
ALTER TABLE ezuservisit ALTER COLUMN failed_login_attempts SET DEFAULT 0;
ALTER TABLE ezuservisit ALTER COLUMN failed_login_attempts SET NOT NULL;

ALTER TABLE ezcontentobject_link ADD COLUMN relation_type int;
ALTER TABLE ezcontentobject_link ALTER COLUMN relation_type SET DEFAULT 1;
ALTER TABLE ezcontentobject_link ALTER COLUMN relation_type SET NOT NULL;
