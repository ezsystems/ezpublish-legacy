

CREATE SEQUENCE ezkeyword_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

create table ezkeyword(
id int NOT NULL DEFAULT nextval('ezkeyword_s'::text),
keyword varchar(255),
class_id int not null,
PRIMARY KEY  (id)
);

CREATE SEQUENCE ezkeyword_attribute_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

create table ezkeyword_attribute_link(
id int NOT NULL nextval('ezkeyword_attribute_link_s'::text),
keyword_id int not null,
objectattribute_id  int not null,
PRIMARY KEY  (id)
);


CREATE SEQUENCE ezcontentbrowsebookmark_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE ezcontentbrowsebookmark (
    id integer DEFAULT nextval('ezcontentbrowsebookmark_s'::text) NOT NULL,
    user_id integer NOT NULL,
    node_id integer NOT NULL,
    name character varying NOT NULL DEFAULT ''
);

ALTER TABLE ONLY ezcontentbrowsebookmark
    ADD CONSTRAINT ezcontentbrowsebookmark_pkey PRIMARY KEY (id);
Create index ezcontentbrowsebookmark_user on ezcontentbrowsebookmark( user_id );


CREATE SEQUENCE ezcontentbrowserecent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE ezcontentbrowserecent (
    id integer DEFAULT nextval('ezcontentbrowserecent_s'::text) NOT NULL,
    user_id integer NOT NULL,
    node_id integer NOT NULL,
    created integer NOT NULL DEFAULT 0,
    name character varying NOT NULL DEFAULT ''
);

ALTER TABLE ONLY ezcontentbrowserecent
    ADD CONSTRAINT ezcontentbrowserecent_pkey PRIMARY KEY (id);
Create index ezcontentbrowserecent_user on ezcontentbrowserecent( user_id );


CREATE SEQUENCE eznotificationevent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE eznotificationevent (
    id integer DEFAULT nextval('eznotificationevent_s'::text) NOT NULL,
    status integer NOT NULL DEFAULT 0,
    event_type_string varchar(255) NOT NULL,
    data_int1 integer NOT NULL default 0,
    data_int2 integer NOT NULL default 0,
    data_int3 integer NOT NULL default 0,
    data_int4 integer NOT NULL default 0,
    data_text1 text NOT NULL default '',
    data_text2 text NOT NULL default '',
    data_text3 text NOT NULL default '',
    data_text4 text NOT NULL default '',
    primary key ( id )
);


CREATE SEQUENCE eznotificationcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE eznotificationcollection (
    id integer DEFAULT nextval('eznotificationcollection_s'::text) NOT NULL,
    event_id integer NOT NULL default 0,
    handler varchar(255) NOT NULL default '',
    transport varchar(255) NOT NULL default '',
    data_subject text NOT NULL default '',
    data_text text NOT NULL default '',
    primary key ( id )
);

CREATE SEQUENCE eznotificationcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE eznotificationcollection_item (
    id integer DEFAULT nextval('eznotificationcollection_item_s'::text) NOT NULL,
    collection_id integer NOT NULL default 0,
    event_id integer NOT NULL default 0,
    address varchar(255) NOT NULL default '',
    send_date integer NOT NULL default 0,
    primary key ( id )
);


CREATE SEQUENCE ezsubtree_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezsubtree_notification_rule (
    id integer DEFAULT nextval('ezsubtree_notification_rule_s'::text) NOT NULL,
    address varchar(255) NOT NULL,
    use_digest integer not null default 0,
    node_id integer NOT NULL,
    primary key ( id )
);


CREATE SEQUENCE ezgeneral_digest_user_settings_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezgeneral_digest_user_settings (
    id integer DEFAULT nextval('ezgeneral_digest_user_settings_s'::text) NOT NULL,
    address varchar(255) NOT NULL,
    receive_digest integer not null default 0,
    digest_type integer not null default 0,
    day varchar(255) not null default '',
    time varchar(255) not null default '',
    primary key ( id )
);


create temporary table ezpolicy_limitation_value_temp as select * from ezpolicy_limitation_value;
drop table ezpolicy_limitation_value;
CREATE TABLE ezpolicy_limitation_value (
    id integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
    limitation_id integer,
    limitation_id integer,
 );
insert into ezpolicy_limitation_value(id, limitation_id,value) select id, limitation_id,value::char from ezpolicy_limitation_value_temp;
alter table ezcontentclass_attribute ADD data_text5 text;
