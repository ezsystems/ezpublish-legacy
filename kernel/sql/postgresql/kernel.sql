drop SEQUENCE ezworkflow_group_s;
CREATE SEQUENCE ezworkflow_group_s;

DROP TABLE  ezworkflow_group;
CREATE TABLE ezworkflow_group (
    id int NOT NULL DEFAULT nextval('ezworkflow_group'),
    name varchar( 255 ) NOT NULL,
    creator_id int NOT NULL,
    modifier_id int NOT NULL,
    created int NOT NULL,
    modified int NOT NULL,
    PRIMARY KEY(id) );

INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES( 1, 'Standard', -1, -1, 1024392098, 1024392098);
INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES( 2, 'Custom', -1, -1, 1024392098, 1024392098);

DROP TABLE  ezworkflow_group_link;
CREATE TABLE ezworkflow_group_link (
    workflow_id int NOT NULL,
    group_id int NOT NULL,
    PRIMARY KEY(workflow_id, group_id) );

INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 1, 1 );
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 2, 1 );
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 3, 2 );
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 3, 1 );

/*
ezworkflow table
*/
drop SEQUENCE ezworkflow_s;
CREATE SEQUENCE ezworkflow_s;

drop table   ezworkflow;
CREATE TABLE ezworkflow (
    id int not null DEFAULT nextval('ezworkflow_s'),
    version int not null,
    workflow_type_string varchar(50) not null,
    name varchar( 255 ) not null,
    creator_id int not null,
    modifier_id int not null,
    created int not null,
    modified int not null,
    primary key(id,version) );

/*-------------*/
insert into ezworkflow (id, version, workflow_type_string, name, creator_id, modifier_id, created, modified) values( 1, 0, 'group_ezserial', 'Publish', -1, -1, 1024392098, 1024392098);
insert into ezworkflow (id, version, workflow_type_string, name, creator_id, modifier_id, created, modified) values( 2, 0, 'group_ezserial', 'Editor approval', -1, -1, 1024392098, 1024392098);
insert into ezworkflow (id, version, workflow_type_string, name, creator_id, modifier_id, created, modified) values( 3, 0, 'group_ezserial', 'Advanced approval', -1, -1, 1024392098, 1024392098);

select setval('ezworkflow_s', 3);
/******************************************************************************************/

drop SEQUENCE ezworkflow_assign_s;
CREATE SEQUENCE ezworkflow_assign_s;

drop table  ezworkflow_assign;
CREATE TABLE ezworkflow_assign (
    id int not null DEFAULT nextval('ezworkflow_assign_s'),
    workflow_id int not null,
    node_id int not null,
    access_type int not null,
    as_tree int not null,
    primary key(id) );


/*
ezworkflowevent table
*/

drop SEQUENCE ezworkflow_event_s ;
CREATE SEQUENCE ezworkflow_event_s;

drop table  ezworkflow_event;
CREATE TABLE ezworkflow_event (
    id int not null DEFAULT nextval('ezworkflow_event_s'),
    version int not null,
    workflow_id int not null,
    workflow_type_string varchar(50) not null,
    description varchar(50) not null,
    data_int1 int,
    data_int2 int,
    data_int3 int,
    data_int4 int,
    data_text1 varchar(50),
    data_text2 varchar(50),
    data_text3 varchar(50),
    data_text4 varchar(50),
    placement int not null,
    primary key(id,version) );

drop index ezworkflow_event_id;
create unique index ezworkflow_event_id on ezworkflow_event( id );


/* workflow events ------------------*/
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(1, 0, 1, 'event_ezpublish', 'Publish object', 1);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(2, 0, 2, 'event_ezapprove', 'Approve by editor', 1);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(3, 0, 2, 'event_ezmessage', 'Send message to editor', 2);

insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_text1) values(4, 0, 3, 'event_ezmessage', 'Send first message', 1, 'First test message from event');
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(5, 0, 3, 'event_ezapprove', 'Approve by editor', 2);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_int1) values(6, 0, 3, 'event_ezpublish', 'Unpublish', 3, 0);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_text1) values(7, 0, 3, 'event_ezmessage', 'Send second message', 4, 'Some text');
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_int1) values(8, 0, 3, 'event_ezpublish', 'Publish', 5, 1);

select setval('ezworkflow_event_s', 8);


/******************************************************************************************/

/*
ezworkflowprocess table
*/

drop SEQUENCE ezworkflow_process_s ;
CREATE SEQUENCE ezworkflow_process_s;

drop table ezworkflow_process;
CREATE TABLE ezworkflow_process (
    id int not null DEFAULT nextval('ezworkflow_process_s'),
    workflow_id int not null,
    user_id int not null,
    content_id int not null,
    content_version int not null,
    content_parent_id int not null,
    event_id int not null,
    event_position int not null,
    last_event_id int not null,
    last_event_position int not null,
    last_event_status int not null,
    status int not null,
    created int not null,
    modified int not null,
    activation_date int,
    primary key(id) );

drop index ezworkflow_process_id;
create unique index ezworkflow_process_id on ezworkflow_process( id );


/*******************************************************************************/

/* class & attributes */

drop SEQUENCE ezcontentclass_s;
CREATE SEQUENCE ezcontentclass_s;


drop table   ezcontentclass;
CREATE TABLE ezcontentclass (
    id int NOT NULL DEFAULT nextval('ezcontentclass_s'),
    version int not null,
    name varchar( 255 ),
    identifier varchar(50) not null,
    contentobject_name varchar(255),
    creator_id int not null,
    modifier_id int not null,
    created int not null,
    modified int not null,
    primary key(id,version) );

CREATE INDEX ezcontentclass_id  ON ezcontentclass ( id );
/*--------------------------------*/


insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 1, 0, 'Folder', 'folder', -1, -1, 1024392098, 1024392098);
insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 2, 0, 'Article', 'article', -1, -1, 1024392098, 1024392098);

insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 3, 0, 'User group', 'user_group', -1, -1, 1024392098, 1024392098);
insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 4, 0, 'User', 'user', -1, -1, 1024392098, 1024392098);
SELECT setval('ezcontentclass_s', 4);
SELECT nextval('ezcontentclass_s');
SELECT setval('ezcontentclass_s', 4);
/************************************************************/



drop SEQUENCE ezcontentclass_attribute_s;
CREATE SEQUENCE ezcontentclass_attribute_s;

drop table   ezcontentclass_attribute;
CREATE TABLE ezcontentclass_attribute (
    id int not null DEFAULT nextval('ezcontentclass_attribute_s'),
    version int not null,
    contentclass_id int not null,
    identifier varchar(50) not null,
    name varchar( 255 ) not null,
    data_type_string varchar(50) not null,
    placement int not null,
    is_searchable smallint DEFAULT '0',
    is_required smallint DEFAULT '0',
    data_int1 int,
    data_int2 int,
    data_int3 int,
    data_int4 int,
    data_float1 float,
    data_float2 float,
    data_float3 float,
    data_float4 float,
    data_text1 varchar(50),
    data_text2 varchar(50),
    data_text3 varchar(50),
    data_text4 varchar(50),
    primary key(id,version)
    );

/* article attributes */
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(1, 0, 2, 'title', 'Title', 'ezstring', 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(2, 0, 2, 'intro', 'Intro', 'ezstring', 2);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(3, 0, 2, 'integer', 'Integer text', 'ezinteger', 3);


/* folder attributes */
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(4, 0, 1, 'name', 'Name', 'ezstring', 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(5, 0, 1, 'name', 'Description', 'ezstring', 2);

/* user group attributes */
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(6, 0, 3, 'name', 'Name', 'ezstring', 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(7, 0, 3, 'description', 'Description', 'ezstring', 2);

/* user attributes */
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(8, 0, 4, 'first_name', 'First name', 'ezstring', 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, placement) values(9, 0, 4, 'last_name', 'Last name', 'ezstring', 2);
SELECT setval('ezcontentclass_attribute_s', 9);
SELECT nextval('ezcontentclass_attribute_s');
SELECT setval('ezcontentclass_attribute_s', 9);
/*******************************************************************************/
drop SEQUENCE ezcontentobject_s;
CREATE SEQUENCE ezcontentobject_s;


drop table   ezcontentobject;
CREATE TABLE ezcontentobject (
    id int not null primary key DEFAULT nextval('ezcontentobject_s'),
    parent_id int not null,
    owner_id int not null default '0',
    section_id int not null default '0',
    main_node_id int not null,
    contentclass_id int not null,
    name varchar( 255 ),
    current_version int,
    is_published int,
    permission_id int
    );
create unique index ezcontentobject_id on ezcontentobject(id);
create index ezcontentobject_parent_id on ezcontentobject(parent_id);

/*------------------------------------------*/
insert into ezcontentobject(parent_id,contentclass_id,name, current_version,permission_id, main_node_id ) values(0,1,'Top level folder',1,1,1);

insert into ezcontentobject(parent_id,contentclass_id,name, current_version,permission_id,main_node_id) values (1,2, 'An article', 1, 1,2);
insert into ezcontentobject(parent_id,contentclass_id,name, current_version,permission_id,main_node_id) values (1,2,'Another message',1,1,3);

/* user groups */
insert into ezcontentobject(parent_id,contentclass_id,name, current_version,permission_id,main_node_id) values (0,3, 'Main User Group',1,1,4);
insert into ezcontentobject(parent_id,contentclass_id,name, current_version,permission_id,main_node_id) values (4, 3,'Content editors',1,1,5);

/* users */
insert into ezcontentobject(parent_id,contentclass_id,name, current_version,permission_id,main_node_id) values (4,4,'Bård Farstad (root)', 1,1,6);
insert into ezcontentobject(parent_id,contentclass_id,name, current_version,permission_id,main_node_id) values (5,4,'Christoffer Elo',1,6,7);
/*************************************************************************/

drop SEQUENCE ezcontentobject_link_s;
CREATE SEQUENCE ezcontentobject_link_s;


drop table   ezcontentobject_link;
CREATE TABLE ezcontentobject_link (
    id int not null  primary key DEFAULT nextval('ezcontentobject_link_s'),
    from_contentobject_id int not null,
    from_contentobject_version int not null,
    to_contentobject_id int
    );



/*************************************************************************/

drop SEQUENCE ezcontentobject_version_s;
CREATE SEQUENCE ezcontentobject_version_s;

drop table   ezcontentobject_version;
CREATE TABLE ezcontentobject_version (
    id int not null primary key DEFAULT nextval('ezcontentobject_version_s'),
    contentobject_id int,
    creator_id int not null default '0',
    version int not null default '0',
    created int not null default '0',
    modified int not null default '0',
    status int not null default '0',
    workflow_event_pos int not null default '0',
    user_id int not null default '0'
    );
/*----------------------------------------------------*/
insert into ezcontentobject_version(contentobject_id, version, status, workflow_event_pos) values ( 1, 1, 1, 1 );
insert into ezcontentobject_version(contentobject_id, version, status, workflow_event_pos) values ( 2, 1, 1, 1 );
insert into ezcontentobject_version(contentobject_id, version, status, workflow_event_pos) values ( 3, 1, 1, 1 );
insert into ezcontentobject_version(contentobject_id, version, status, workflow_event_pos) values ( 4, 1, 1, 1 );
insert into ezcontentobject_version(contentobject_id, version, status, workflow_event_pos) values ( 5, 1, 1, 1 );
insert into ezcontentobject_version(contentobject_id, version, status, workflow_event_pos) values ( 6, 1, 1, 1 );
insert into ezcontentobject_version(contentobject_id, version, status, workflow_event_pos) values ( 7, 1, 1, 1 );
/*************************************************************************/
drop SEQUENCE ezcontentobject_attribute_s;
CREATE SEQUENCE ezcontentobject_attribute_s;


drop table   ezcontentobject_attribute;

CREATE TABLE ezcontentobject_attribute (
    id int DEFAULT nextval('ezcontentobject_attribute_s') not null,
    language_code varchar( 20 ) not null,
    version int not null,
    contentobject_id int not null,
    contentclassattribute_id int not null,
    data_text text,
    data_int int,
    data_float float,
    primary key( id, version ) );

/*-------------------------------------------------------------------------------------*/

insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values (1, 1, 'en_GB', 4, 'My folder' ) ;
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 1, 1, 'en_GB', 5, 'This folder contains some information about...');

insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 2, 1, 'en_GB', 1, 'This is an article' );
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 2, 1, 'en_GB', 2, 'Intro..' );
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 2, 1, 'en_GB', 3, 42 );

insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values (3, 1, 'en_GB', 4, 'This is the message' );

/* user group data */
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 4, 1, 'en_GB', 5, 'Main group' );
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 4, 1, 'en_GB', 6, 'This is the master users' );

/* user  data */
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 5, 1, 'en_GB', 7, 'Bård' );
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 5, 1, 'en_GB', 8, 'Farstad (root)' );

insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 6, 1, 'en_GB', 7, 'Christoffer' );
insert into ezcontentobject_attribute( contentobject_id, version, language_code, contentclassattribute_id, data_text )  values ( 6, 1, 'en_GB', 8, 'Elo' );





/*  Permissions */
drop SEQUENCE ezcontentobject_perm_set_s;
CREATE SEQUENCE ezcontentobject_perm_set_s;

drop table   ezcontentobject_perm_set;
CREATE TABLE ezcontentobject_perm_set (
    id int not null primary key DEFAULT nextval('ezcontentobject_perm_set_s'),
    name  varchar(255)
    );
insert into  ezcontentobject_perm_set( name ) values( 'test set' );
insert into  ezcontentobject_perm_set( name ) values( 'test set 2' );




/* Users */
drop table   ezuser;
CREATE TABLE ezuser (
    contentobject_id int not null,
    login varchar( 150 ) not null,
    email varchar( 150 ) not null,
    password_hash_type int not null default 1,
    password_hash varchar( 50 )
    );

insert into ezuser ( contentobject_id, login, email, password_hash_type, password_hash ) VALUES ( '1', 'anonymous', 'anon@anon.com', 1, '' );




/*
 Search
*/
drop SEQUENCE  ezsearch_object_word_link_s;
CREATE SEQUENCE  ezsearch_object_word_link_s;

DROP TABLE  ezsearch_object_word_link;
CREATE TABLE ezsearch_object_word_link
(
    id int not null primary key  DEFAULT nextval('ezsearch_object_word_link_s'),
    contentobject_id int not null,
    word_id int not null,
    frequency float not null,
    placement int not null,
    prev_word_id int not null,
    next_word_id int not null,
    contentclass_id int not null,
    contentclass_attribute_id int not null
    );

drop SEQUENCE ezsearch_word_s;
CREATE SEQUENCE ezsearch_word_s;

drop table ezsearch_word;
CREATE TABLE ezsearch_word
(
    id int not null  primary key DEFAULT nextval('ezsearch_word_s'),
    word varchar( 150 ),
    object_count int not null
    );

CREATE INDEX ezsearch_object_word_link_object ON ezsearch_object_word_link (object_id);
CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link (word_id);
CREATE INDEX ezsearch_object_word_link_frequency ON ezsearch_object_word_link (frequency);
CREATE INDEX ezsearch_word_i ON ezsearch_word (word);



/* search log */

drop SEQUENCE ezsearch_search_phrase_s ;
CREATE SEQUENCE ezsearch_search_phrase_s;

drop table  ezsearch_search_phrase;
CREATE TABLE ezsearch_search_phrase
(
    id int primary key  DEFAULT nextval('ezsearch_search_phrase_s'),
    phrase varchar( 250 )
    );

drop SEQUENCE ezsearch_return_count_s ;
CREATE SEQUENCE ezsearch_return_count_s;

drop table ezsearch_return_count;
CREATE TABLE ezsearch_return_count
(
    id int primary key  DEFAULT nextval('ezsearch_return_count_s'),
    phrase_id int not null,
    time int not null,
    count int not null
    );

/*
Image
*/
drop table ezimage;
CREATE TABLE ezimage (
    contentobject_attribute_id int not null,
    version int not null,
    filename varchar(255) not null,
    original_filename varchar(255) not null,
    mime_type varchar(50) not null,
    primary key(contentobject_attribute_id,version) );

drop table ezimagevariation;
create table ezimagevariation (
    contentobject_attribute_id int not null,
    version int not null,
    filename varchar( 255 ) not null,
    additional_path varchar( 255 ),
    requested_width int not null,
    requested_height int not null,
    width int not null,
    height int not null,
    primary key( contentobject_attribute_id,version,requested_width,requested_height) );


/*
Binary File
*/

drop table ezbinaryfile;
CREATE TABLE ezbinaryfile (
    contentobject_attribute_id int not null,
    version int not null,
    filename varchar(255) not null,
    original_filename varchar(255) not null,
    mime_type varchar(50) not null,
    primary key( contentobject_attribute_id, version ) );



/*
 EnumValue
*/

drop SEQUENCE ezenumvalue_s ;
CREATE SEQUENCE ezenumvalue_s;

drop table  ezenumvalue;
CREATE TABLE ezenumvalue (
    id int not null  DEFAULT nextval('ezenumvalue_s'),
    contentclass_attribute_id int not null,
    contentclass_attribute_version int not null,
    enumelement varchar(50) not null,
    enumvalue varchar(50) not null,
    placement int not null,
    primary key( id, contentclass_attribute_id, contentclass_attribute_version ) );

/*
 EnumObjectValue
*/
drop table  ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
    contentobject_attribute_id int not null,
    contentobject_attribute_version int not null,
    enumid int not null,
    enumelement varchar(50) not null,
    enumvalue varchar(50) not null,
    primary key( contentobject_attribute_id, contentobject_attribute_version, enumid ) );


/*
 ContentClassGroup
*/
drop SEQUENCE ezcontentclassgroup_s;
CREATE SEQUENCE ezcontentclassgroup_s;

drop table ezcontentclassgroup;
CREATE TABLE ezcontentclassgroup (
    id int DEFAULT nextval('ezcontentclassgroup_s') not null,
    name varchar( 255 ),
    creator_id int not null,
    modifier_id int not null,
    created int not null,
    modified int not null,
    primary key( id ) );

/*
 ContentClass_ClassGroup
*/
drop table ezcontentclass_classgroup;
CREATE TABLE ezcontentclass_classgroup (
    contentclass_id int not null,
    contentclass_version int not null,
    group_id int not null,
    group_name  varchar( 255 ),
    primary key( contentclass_id, contentclass_version, group_id ) );



/*
  Product collection SQL
*/
drop SEQUENCE ezproductcollection_s;
CREATE SEQUENCE ezproductcollection_s;

drop table  ezproductcollection;
CREATE TABLE ezproductcollection (
    id int not null DEFAULT nextval('ezproductcollection_s'),
    primary key(id) );

drop SEQUENCE ezproductcollection_item_s;
CREATE SEQUENCE ezproductcollection_item_s;

drop table  ezproductcollection_item;
CREATE TABLE ezproductcollection_item (
    id int not null DEFAULT nextval('ezproductcollection_item_s'),
    productcollection_id int not null,
    contentobject_id int not null,
    item_count int not null,
    price_is_inc_vat int not null,
    price int not null,
    primary key(id) );

drop SEQUENCE ezcart_s;
CREATE SEQUENCE ezcart_s;

drop table  ezcart;
CREATE TABLE ezcart (
    id int not null DEFAULT nextval('ezcart_s'),
    session_id varchar(255) not null,
    productcollection_id int not null,
    primary key(id) );

drop SEQUENCE ezorder_s;
CREATE SEQUENCE ezorder_s;

drop table  ezorder;
CREATE TABLE ezorder (
    id int not null DEFAULT nextval('ezorder_s'),
    user_id int not null,
    productcollection_id int not null,
    created int not null,
    primary key(id) );

drop SEQUENCE ezwishlist_s;
CREATE SEQUENCE ezwishlist_s;

drop table  ezwishlist;
CREATE TABLE ezwishlist (
    id int not null DEFAULT nextval('ezwishlist_s'),
    user_id int not null,
    productcollection_id int not null,
    primary key(id) );
/*
# Session
*/
drop table ezsession;
create table ezsession(
    session_key char(32) not null,
    expiration_time int not null,
    data text not null,
    primary key (session_key)
    );

/*
# Section
*/
drop SEQUENCE ezsection_s;
CREATE SEQUENCE ezsection_s;
drop table ezsection;
create table ezsection
(
    id int not null DEFAULT nextval('ezsection_s'),
    name varchar(255),
    locale varchar(255),
    primary key (id)
    );



/*
###### Tree test ###########3
#                    0
#                    |
#                   Root
#                 /      \
#             Sports       News
#            /           /      \
#          Motor        Domestic Fn1 ( same as the other Fn1 node )
#         /  \           /     \
#        F1  F3000     News 1  News 2
#      /   \
#     Fn1  Fn2
#
*/
drop SEQUENCE ezcontentobject_tree_s;
CREATE SEQUENCE ezcontentobject_tree_s;

drop table ezcontentobject_tree;
create table ezcontentobject_tree(
    node_id int not null primary key DEFAULT nextval('ezcontentobject_tree_s'),
    parent_node_id int not null,
    contentobject_id int,
    contentobject_version int,
    contentobject_is_published int,
    crc32_path int,
    depth int not null,
    path_string varchar(255) not null,
    md5_path varchar(15),
    left_margin int not null,
    right_margin int not null
    );

create index ezcontentobject_tree_path on ezcontentobject_tree( path_string );
create index ezcontentobject_tree_p_node_id on ezcontentobject_tree( parent_node_id );
create index ezcontentobject_tree_co_id  on ezcontentobject_tree( contentobject_id );
create index ezcontentobject_tree_depth  on ezcontentobject_tree( depth );

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 0, 0, 0, 0, '/0/', 1, 16 );
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 1, 0, 1, 1, '/0/1/', 2,  7);
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 2, 1, 2, 2, '/0/1/2/', 3, 4);
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 3, 1, 3, 2, '/0/1/3/', 5, 6);

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 4, 0, 4, 1, '/0/4/', 8,  15);

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 5, 4, 5, 2, '/0/4/5/', 9, 12);

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 6, 4, 6, 2, '/0/4/6/', 13, 14);
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values ( 7, 5, 7, 3, '/0/4/5/7/', 10, 11);
select nextval('ezcontentobject_tree_s');
select nextval('ezcontentobject_tree_s');
select nextval('ezcontentobject_tree_s');
select nextval('ezcontentobject_tree_s');
select nextval('ezcontentobject_tree_s');
select nextval('ezcontentobject_tree_s');
select nextval('ezcontentobject_tree_s');





/*
Role system
 */

drop SEQUENCE ezrole_s;
CREATE SEQUENCE ezrole_s;
drop table ezrole;
create table ezrole(
id int not null primary key DEFAULT nextval('ezrole_s'),
version int DEFAULT '0',
name varchar not null,
value char(1)
);
insert into ezrole(name, value) values('Anonimous', '');  
insert into ezrole(name, value) values('Admin', '*');
insert into ezrole(name, value) values('editor', '');
insert into ezrole(name, value) values('advanced editor','');
  

drop SEQUENCE ezuser_role_s;
CREATE SEQUENCE ezuser_role_s;
drop table ezuser_role;
create table ezuser_role(
id int not null primary key DEFAULT nextval('ezuser_role_s'),
role_id int,
contentobject_id int
);
insert into ezuser_role( role_id, contentobject_id ) values(1,49);
insert into ezuser_role( role_id, contentobject_id ) values(2,50);
insert into ezuser_role( role_id, contentobject_id ) values(3,51);
insert into ezuser_role( role_id, contentobject_id ) values(3,53);
insert into ezuser_role( role_id, contentobject_id ) values(4,53);
insert into ezuser_role( role_id, contentobject_id ) values(1,8);
insert into ezuser_role( role_id, contentobject_id ) values(1,4);
insert into ezuser_role( role_id, contentobject_id ) values(3,8);


drop SEQUENCE ezpolicy_s;
CREATE SEQUENCE ezpolicy_s;
drop table ezpolicy;
create table ezpolicy(
id int not null primary key DEFAULT nextval('ezpolicy_s'),
role_id int,
function_name varchar,
module_name varchar,
limitation char(1)
);
insert into ezpolicy(role_id,module_name,function_name,limitation) values(1, 'content', 'sitemap', '');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(1, 'search' , 'search' , '');

insert into ezpolicy(role_id,module_name,function_name,limitation) values(2, '*'      , '*'      , '*' );
insert into ezpolicy(role_id,module_name,function_name,limitation) values(2, 'class'  ,  '*'     , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(2, 'content', '*'      , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(2, 'search' , '*'      , '*');

insert into ezpolicy(role_id,module_name,function_name,limitation) values(3, 'class'  , 'list'   , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(3, 'class'  , 'edit'   , '' );
insert into ezpolicy(role_id,module_name,function_name,limitation) values(3, 'content', 'sitemap', '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(3, 'content', 'delete' , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(3, 'content', 'edit'   , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(3, 'content', 'view'   , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(3, 'search' , '*'      , '*');

insert into ezpolicy(role_id,module_name,function_name,limitation) values(4, 'content', 'sitemap', '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(4, 'class'  , 'edit'   , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(4, 'search' , 'search' , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(4, 'content', '*'      , '*');
insert into ezpolicy(role_id,module_name,function_name,limitation) values(4, 'search' , '*'      , '*');

drop SEQUENCE ezpolicy_limitation_s;
CREATE SEQUENCE ezpolicy_limitation_s;

drop table ezpolicy_limitation;
create table ezpolicy_limitation(
id int not null primary key DEFAULT nextval('ezpolicy_limitation_s'),
policy_id int,
identifier varchar not null,
role_id int,
function_name varchar,
module_name varchar
);

insert into ezpolicy_limitation(policy_id,identifier,role_id,function_name,module_name) values(1,'ClassID', 1,  'content', 'sitemap');
insert into ezpolicy_limitation(policy_id,identifier,role_id,function_name,module_name) values(1,'ObjectID', 1,  'content', 'sitemap');
insert into ezpolicy_limitation(policy_id,identifier,role_id,function_name,module_name) values(2,'ClassID', 1, 'search', 'search');
insert into ezpolicy_limitation(policy_id,identifier,role_id,function_name,module_name) values(2,'ObjectID',1, 'search', 'search');
insert into ezpolicy_limitation(policy_id,identifier,role_id,function_name,module_name) values(8,'ClassID', 3, 'edit', 'class');


drop SEQUENCE ezpolicy_limitation_value_s;
CREATE SEQUENCE ezpolicy_limitation_value_s;
drop table ezpolicy_limitation_value;
create table ezpolicy_limitation_value(
id int not null primary key DEFAULT nextval('ezpolicy_limitation_value_s'),
limitation_id int,
value int
);

insert into ezpolicy_limitation_value(limitation_id,value) values(1,'2');
insert into ezpolicy_limitation_value(limitation_id,value) values(2,'1');
insert into ezpolicy_limitation_value(limitation_id,value) values(2,'17');
insert into ezpolicy_limitation_value(limitation_id,value) values(2,'45');
insert into ezpolicy_limitation_value(limitation_id,value) values(2,'3');
insert into ezpolicy_limitation_value(limitation_id,value) values(2,'46');
insert into ezpolicy_limitation_value(limitation_id,value) values(3,'2');

insert into ezpolicy_limitation_value(limitation_id,value) values(4,'1');
insert into ezpolicy_limitation_value(limitation_id,value) values(4,'17');
insert into ezpolicy_limitation_value(limitation_id,value) values(4,'45');
insert into ezpolicy_limitation_value(limitation_id,value) values(4,'3');
insert into ezpolicy_limitation_value(limitation_id,value) values(4,'46');
insert into ezpolicy_limitation_value(limitation_id,value) values(5,'2'); 


/*
END
*/
