alter table ezsearch_object_word_link add column identifier varchar(255);
alter table ezsearch_object_word_link add column integer_value integer;

CREATE SEQUENCE ezcollab_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezcollab_notification_rule (
    id integer DEFAULT nextval('ezcollab_notification_rule_s'::text) NOT NULL,
    user_id character varying(255) DEFAULT '' NOT NULL,
    collab_identifier character varying(255) DEFAULT '' NOT NULL
);

ALTER TABLE ONLY ezcollab_notification_rule
    ADD CONSTRAINT ezcollab_notification_rule160_key PRIMARY KEY (id);


CREATE TABLE ezurl_object_link (
    url_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_version integer DEFAULT '0' NOT NULL
);

ALTER TABLE ONLY ezurl_object_link
    ADD CONSTRAINT ezurl_object_link1039_key PRIMARY KEY (url_id, contentobject_attribute_id, contentobject_attribute_version);

CREATE TABLE ezsite_data (
  name varchar(60) NOT NULL default '',
  value text NOT NULL default '',
  PRIMARY KEY (name)
);

INSERT INTO ezsite_data (name, value) VALUES('ezpublish-version', '3.2.0');
INSERT INTO ezsite_data (name, value) VALUES('ezpublish-release', '1');


drop index ezcontentobject_tree_depth on ezcontentobject_tree;
create index ezsearch_word_object_count on ezsearch_word(object_count);
create index ezcontentobject_status on ezcontentobject( status );
create index ezcontentobject_tree_path_depth on ezcontentobject_tree( path_string, depth );

alter table ezcontentclass_attribute add can_translate int default 1;
alter table ezcontentobject_attribute add attribute_original_id int default 0;

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
    forward_to_id integer DEFAULT '0' NOT NULL
);

CREATE INDEX ezurlalias_source_md51059 ON ezurlalias USING btree (source_md5);

ALTER TABLE ONLY ezurlalias
    ADD CONSTRAINT ezurlalias1051_key PRIMARY KEY (id);


create index ezurlalias_source_md5 on ezurlalias( source_md5 );

insert into ezurlalias ( source_url, source_md5, destination_url, is_internal ) select path_identification_string, md5( path_identification_string ), concat( 'content/view/full/', node_id ), 1 from ezcontentobject_tree where node_id <> 1;

# Drop unneeded columns
alter table ezcontentobject_tree drop md5_path;
alter table ezcontentobject_tree drop crc32_path;

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
    value character varying(100)
);

create index ezpreferences_name on ezpreferences using btree ( name );

ALTER TABLE ONLY ezpreferences
    ADD CONSTRAINT ezpreferences833_key PRIMARY KEY (id);

alter table ezcontentobject_attribute add sort_key_int int not null default 0;
alter table ezcontentobject_attribute add sort_key_string varchar(50) not null default '';

## Updates from sort_key to sort_key_int and sort_key_string
# Not needed if you don't upgrade from an svn version of 3.2
# update ezcontentobject_attribute set sort_key_int=sort_key;
# update ezcontentobject_attribute set sort_key_string=sort_key;
# alter table  ezcontentobject_attribute drop sort_key;


ALTER TABLE ezcontentobject_attribute ADD index ( sort_key_int );
ALTER TABLE ezcontentobject_attribute ADD index ( sort_key_string );
