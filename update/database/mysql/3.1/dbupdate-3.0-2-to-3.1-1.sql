
create table ezkeyword(
id int NOT NULL auto_increment,
keyword varchar(255),
class_id int not null,
PRIMARY KEY  (id)
);


create table ezkeyword_attribute_link(
id int NOT NULL auto_increment,
keyword_id int not null,
objectattribute_id  int not null,
PRIMARY KEY  (id)
);



CREATE TABLE ezcontentbrowsebookmark (
    id integer NOT NULL auto_increment,
    user_id integer NOT NULL,
    node_id integer NOT NULL,
    name  varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (id)
);

Create index ezcontentbrowsebookmark_user on ezcontentbrowsebookmark( user_id );


CREATE TABLE ezcontentbrowserecent (
    id integer NOT NULL auto_increment,
    user_id integer NOT NULL,
    node_id integer NOT NULL,
    created integer NOT NULL DEFAULT 0,
    name  varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (id)
);

Create index ezcontentbrowserecent_user on ezcontentbrowserecent( user_id );



CREATE TABLE eznotificationevent (
    id integer  auto_increment NOT NULL,
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



CREATE TABLE eznotificationcollection (
    id integer  auto_increment NOT NULL,
    event_id integer NOT NULL default 0,
    handler varchar(255) NOT NULL default '',
    transport varchar(255) NOT NULL default '',
    data_subject text NOT NULL default '',
    data_text text NOT NULL default '',
    primary key ( id )
);


CREATE TABLE eznotificationcollection_item (
    id integer  auto_increment NOT NULL,
    collection_id integer NOT NULL default 0,
    event_id integer NOT NULL default 0,
    address varchar(255) NOT NULL default '',
    send_date integer NOT NULL default 0,
    primary key ( id )
);


CREATE TABLE ezsubtree_notification_rule (
    id integer  auto_increment NOT NULL,
    address varchar(255) NOT NULL,
    use_digest integer not null default 0,
    node_id integer NOT NULL,
    primary key ( id )
);



CREATE TABLE ezgeneral_digest_user_settings (
    id integer  auto_increment NOT NULL,
    address varchar(255) NOT NULL,
    receive_digest integer not null default 0,
    digest_type integer not null default 0,
    day varchar(255) not null default '',
    time varchar(255) not null default '',
    primary key ( id )
);

alter table  ezpolicy_limitation_value MODIFY value varchar(255);
