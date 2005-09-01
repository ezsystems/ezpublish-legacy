alter table ezsearch_object_word_link add column identifier varchar(255);
alter table ezsearch_object_word_link alter column identifier set not null;
alter table ezsearch_object_word_link alter column identifier set default '';
alter table ezsearch_object_word_link add column integer_value integer;
alter table ezsearch_object_word_link alter column integer_value set not null;
alter table ezsearch_object_word_link alter column integer_value set default '0';

CREATE SEQUENCE ezcollab_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezcollab_notification_rule (
    id integer DEFAULT nextval('ezcollab_notification_rule_s'::text) NOT NULL,
    user_id character varying(255) DEFAULT '' NOT NULL,
    collab_identifier character varying(255) DEFAULT '' NOT NULL,
    PRIMARY KEY ( id )
);


CREATE TABLE ezurl_object_link (
    url_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_version integer DEFAULT '0' NOT NULL,
    PRIMARY KEY ( url_id, contentobject_attribute_id, contentobject_attribute_version )
);

CREATE TABLE ezsite_data (
  name varchar(60) NOT NULL default '',
  value text NOT NULL default '',
  PRIMARY KEY (name)
);

drop index ezcontentobject_tree_depth;
create index ezsearch_word_object_count on ezsearch_word(object_count);
create index ezcontentobject_status on ezcontentobject( status );
create index ezcontentobject_tree_path_depth on ezcontentobject_tree( path_string, depth );

alter table ezcontentclass_attribute add can_translate int;
alter table ezcontentclass_attribute alter can_translate set default 1;
alter table ezcontentobject_attribute add attribute_original_id int;
alter table ezcontentobject_attribute alter attribute_original_id set default 0;

CREATE SEQUENCE ezurlalias_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezurlalias (
    id integer DEFAULT nextval('ezurlalias_s'::text) NOT NULL,
    source_url text NOT NULL,
    source_md5 character varying(32),
    destination_url text NOT NULL,
    is_internal integer DEFAULT '1' NOT NULL,
    forward_to_id integer DEFAULT '0' NOT NULL,
    PRIMARY KEY (id)
);

create index ezurlalias_source_md5 on ezurlalias( source_md5 );

insert into ezurlalias ( source_url, source_md5, destination_url, is_internal ) select path_identification_string, encode( digest( path_identification_string, 'md5' ), 'hex' ), 'content/view/full/' || node_id, 1 from ezcontentobject_tree where node_id <> 1;

-- Drop unneeded columns
-- alter table ezcontentobject_tree drop md5_path;
-- alter table ezcontentobject_tree drop crc32_path;

CREATE TABLE ezcontentobject_tree_tmp (
    node_id integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
    parent_node_id integer DEFAULT '0' NOT NULL,
    contentobject_id integer,
    contentobject_version integer,
    contentobject_is_published integer,
    depth integer DEFAULT '0' NOT NULL,
    path_string character varying(255) DEFAULT '' NOT NULL,
    sort_field integer DEFAULT '1',
    sort_order integer DEFAULT '1',
    priority integer DEFAULT '0' NOT NULL,
    path_identification_string text,
    main_node_id integer
);

INSERT INTO ezcontentobject_tree_tmp (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id )
  SELECT node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id
    FROM ezcontentobject_tree;

DROP TABLE ezcontentobject_tree;

CREATE TABLE ezcontentobject_tree (
    node_id integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
    parent_node_id integer DEFAULT '0' NOT NULL,
    contentobject_id integer,
    contentobject_version integer,
    contentobject_is_published integer,
    depth integer DEFAULT '0' NOT NULL,
    path_string character varying(255) DEFAULT '' NOT NULL,
    sort_field integer DEFAULT '1',
    sort_order integer DEFAULT '1',
    priority integer DEFAULT '0' NOT NULL,
    path_identification_string text,
    main_node_id integer
);

INSERT INTO ezcontentobject_tree (node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id )
  SELECT node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id
    FROM ezcontentobject_tree_tmp;

DROP TABLE ezcontentobject_tree_tmp;

CREATE SEQUENCE ezpreferences_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezpreferences (
    id integer DEFAULT nextval('ezpreferences_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    name character varying(100),
    value character varying(100),
    PRIMARY KEY (id)
);

create index ezpreferences_name on ezpreferences( name );

alter table ezcontentobject_attribute add sort_key_int int;
alter table ezcontentobject_attribute alter sort_key_int set default 0;
alter table ezcontentobject_attribute alter sort_key_int set not null;
alter table ezcontentobject_attribute add sort_key_string varchar(50);
alter table ezcontentobject_attribute alter sort_key_string set not null;
alter table ezcontentobject_attribute alter sort_key_string set default '';


-- Remove workflow_event_pos

CREATE TABLE ezcontentobject_version_tmp (
    id integer DEFAULT nextval('ezcontentobject_version_s'::text) NOT NULL,
    contentobject_id integer,
    creator_id integer DEFAULT '0' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL,
    status integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL
);

INSERT INTO ezcontentobject_version_tmp (id, contentobject_id, creator_id, version, created, modified, status, user_id)
  SELECT id, contentobject_id, creator_id, version, created, modified, status, user_id
    FROM ezcontentobject_version;

DROP TABLE ezcontentobject_version;

CREATE TABLE ezcontentobject_version (
    id integer DEFAULT nextval('ezcontentobject_version_s'::text) NOT NULL,
    contentobject_id integer,
    creator_id integer DEFAULT '0' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL,
    status integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL
);

INSERT INTO ezcontentobject_version (id, contentobject_id, creator_id, version, created, modified, status, user_id)
  SELECT id, contentobject_id, creator_id, version, created, modified, status, user_id
    FROM ezcontentobject_version_tmp;

DROP TABLE ezcontentobject_version_tmp;

 --- Updates from sort_key to sort_key_int and sort_key_string
-- Not needed if you don't upgrade from an svn version of 3.2
-- update ezcontentobject_attribute set sort_key_int=sort_key;
-- update ezcontentobject_attribute set sort_key_string=sort_key;
-- alter table  ezcontentobject_attribute drop sort_key;


CREATE INDEX ezcontentobject_attribute_ski ON ezcontentobject_attribute( sort_key_int );
CREATE INDEX ezcontentobject_attribute_sks ON ezcontentobject_attribute( sort_key_string );


CREATE INDEX ezorder_item_order_id ON ezorder_item( order_id );
CREATE INDEX ezproductcollection_item_productcollection_id ON ezproductcollection_item( productcollection_id );
CREATE INDEX ezurlalias_source_url ON ezurlalias(source_url);
CREATE INDEX ezcontentobject_attribute_co_id_ver_lang_code ON ezcontentobject_attribute( contentobject_id, version, language_code);


INSERT INTO ezsite_data (name, value) VALUES('ezpublish-version', '3.2');
INSERT INTO ezsite_data (name, value) VALUES('ezpublish-release', '1');
