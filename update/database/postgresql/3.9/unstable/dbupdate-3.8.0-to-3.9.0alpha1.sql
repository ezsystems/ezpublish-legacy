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

-- START: 'default sorting' attribute for ezcontentclass

ALTER TABLE ezcontentclass ADD COLUMN sort_field int;
ALTER TABLE ezcontentclass ALTER COLUMN sort_field SET DEFAULT 1;
ALTER TABLE ezcontentclass ALTER COLUMN sort_field SET NOT NULL;

ALTER TABLE ezcontentclass ADD COLUMN sort_order int;
ALTER TABLE ezcontentclass ALTER COLUMN sort_order SET DEFAULT 1;
ALTER TABLE ezcontentclass ALTER COLUMN sort_order SET NOT NULL;

-- END: 'default sorting' attribute for ezcontentclass

-- START: new table for trash
CREATE TABLE ezcontentobject_trash (
    contentobject_id integer,
    contentobject_version integer,
    depth integer DEFAULT 0 NOT NULL,
    is_hidden integer DEFAULT 0 NOT NULL,
    is_invisible integer DEFAULT 0 NOT NULL,
    main_node_id integer,
    modified_subnode integer DEFAULT 0,
    node_id integer DEFAULT 0 NOT NULL,
    parent_node_id integer DEFAULT 0 NOT NULL,
    path_identification_string text,
    path_string character varying(255) DEFAULT ''::character varying NOT NULL,
    priority integer DEFAULT 0 NOT NULL,
    remote_id character varying(100) DEFAULT ''::character varying NOT NULL,
    sort_field integer DEFAULT 1,
    sort_order integer DEFAULT 1
);


CREATE INDEX ezcontentobject_trash_co_id ON ezcontentobject_trash USING btree (contentobject_id);
CREATE INDEX ezcontentobject_trash_depth ON ezcontentobject_trash USING btree (depth);
CREATE INDEX ezcontentobject_trash_p_node_id ON ezcontentobject_trash USING btree (parent_node_id);
CREATE INDEX ezcontentobject_trash_path ON ezcontentobject_trash USING btree (path_string);
CREATE INDEX ezcontentobject_trash_path_ident ON ezcontentobject_trash USING btree (path_identification_string);
CREATE INDEX ezcontentobject_trash_modified_subnode ON ezcontentobject_trash USING btree (modified_subnode);
ALTER TABLE ONLY ezcontentobject_trash ADD CONSTRAINT ezcontentobject_trash_pkey PRIMARY KEY (node_id);
-- END: new table for trash
