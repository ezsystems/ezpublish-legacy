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


CREATE INDEX ezcobj_trash_co_id ON ezcontentobject_trash USING btree (contentobject_id);
CREATE INDEX ezcobj_trash_depth ON ezcontentobject_trash USING btree (depth);
CREATE INDEX ezcobj_trash_p_node_id ON ezcontentobject_trash USING btree (parent_node_id);
CREATE INDEX ezcobj_trash_path ON ezcontentobject_trash USING btree (path_string);
CREATE INDEX ezcobj_trash_path_ident ON ezcontentobject_trash USING btree (path_identification_string);
CREATE INDEX ezcobj_trash_modified_subnode ON ezcontentobject_trash USING btree (modified_subnode);
ALTER TABLE ONLY ezcontentobject_trash ADD CONSTRAINT ezcontentobject_trash_pkey PRIMARY KEY (node_id);
-- END: new table for trash

-- START: ezcontentclass/ezcontentclass_attribute translations
ALTER TABLE ezcontentclass RENAME COLUMN name TO serialized_name_list;
ALTER TABLE ezcontentclass ADD COLUMN language_mask integer;
ALTER TABLE ezcontentclass ALTER language_mask SET NOT NULL;
ALTER TABLE ezcontentclass ALTER language_mask SET DEFAULT 0;
ALTER TABLE ezcontentclass ADD COLUMN initial_language_id integer;
ALTER TABLE ezcontentclass ALTER initial_language_id SET NOT NULL;
ALTER TABLE ezcontentclass ALTER initial_language_id SET DEFAULT 0;
ALTER TABLE ezcontentclass_attribute RENAME COLUMN name TO serialized_name_list;

CREATE SEQUENCE ezcontentclass_name_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


CREATE TABLE ezcontentclass_name
(
    id integer NOT NULL DEFAULT nextval('ezcontentclass_name_s'::text),
    contentclass_id integer NOT NULL default 0,
    contentclass_version integer NOT NULL default 0,
    language_locale varchar(20) NOT NULL default '',
    language_id integer NOT NULL default 0,
    name varchar(255) NOT NULL default ''
);

ALTER TABLE ONLY ezcontentclass_name
    ADD CONSTRAINT ezcontentclass_name_pkey PRIMARY KEY (id);
-- END: ezcontentclass/ezcontentclass_attribute translations