DROP TABLE IF EXISTS ezworkflow_group;
CREATE TABLE ezworkflow_group (
id int AUTO_INCREMENT NOT NULL,
name varchar( 255 ) NOT NULL,
creator_id int NOT NULL,
modifier_id int NOT NULL,
created int NOT NULL,
modified int NOT NULL,
PRIMARY KEY(id) );

INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES( 1, 'Standard', -1, -1, 1024392098, 1024392098);
INSERT INTO ezworkflow_group (id, name, creator_id, modifier_id, created, modified) VALUES( 2, 'Custom', -1, -1, 1024392098, 1024392098);

DROP TABLE IF EXISTS ezworkflow_group_link;
CREATE TABLE ezworkflow_group_link (
workflow_id int NOT NULL,
group_id int NOT NULL,
PRIMARY KEY(workflow_id, group_id) );

INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 1, 1 );
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 2, 1 );
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 3, 2 );
INSERT INTO ezworkflow_group_link (workflow_id, group_id) VALUES( 3, 1 );

DROP TABLE IF EXISTS ezworkflow;
CREATE TABLE ezworkflow (
id int AUTO_INCREMENT NOT NULL,
version int NOT NULL,
is_enabled int(1) NOT NULL,
workflow_type_string varchar(50) NOT NULL,
name varchar( 255 ) NOT NULL,
creator_id int NOT NULL,
modifier_id int NOT NULL,
created int NOT NULL,
modified int NOT NULL,
PRIMARY KEY(id,version) );

INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES( 1, 0, 1, 'group_ezserial', 'Publish', -1, -1, 1024392098, 1024392098);
INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES( 2, 0, 1, 'group_ezserial', 'Editor approval', -1, -1, 1024392098, 1024392098);
INSERT INTO ezworkflow (id, version, is_enabled, workflow_type_string, name, creator_id, modifier_id, created, modified) VALUES( 3, 0, 1, 'group_ezserial', 'Advanced approval', -1, -1, 1024392098, 1024392098);

drop table if exists ezworkflow_assign;
CREATE TABLE ezworkflow_assign (
id int auto_increment not null,
workflow_id int not null,
node_id int not null,
access_type int not null,
as_tree int(1) not null,
primary key(id) );

drop table if exists ezworkflow_event;
CREATE TABLE ezworkflow_event (
id int auto_increment not null,
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

# workflow events
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(1, 0, 1, 'event_ezpublish', 'Publish object', 1);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(2, 0, 2, 'event_ezapprove', 'Approve by editor', 1);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(3, 0, 2, 'event_ezmessage', 'Send message to editor', 2);

insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_text1) values(4, 0, 3, 'event_ezmessage', 'Send first message', 1, 'First test message from event');
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement) values(5, 0, 3, 'event_ezapprove', 'Approve by editor', 2);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_int1) values(6, 0, 3, 'event_ezpublish', 'Unpublish', 3, 0);
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_text1) values(7, 0, 3, 'event_ezmessage', 'Send second message', 4, 'Some text');
insert into ezworkflow_event (id, version, workflow_id, workflow_type_string, description, placement, data_int1) values(8, 0, 3, 'event_ezpublish', 'Publish', 5, 1);

drop table if exists ezworkflow_process;
CREATE TABLE ezworkflow_process (
id int auto_increment not null,
workflow_id int not null,
user_id int not null,
content_id int not null,
content_version int not null,
node_id int not null,
event_id int not null,
event_position int not null,
last_event_id int not null,
last_event_position int not null,
last_event_status int not null,
event_status int not null,
created int not null,
modified int not null,
activation_date int,
primary key(id) );

# workflow processes
#insert into ezworkflow_process ( id, workflow_id, event_id, last_event_id, user_id, content_id, content_version, node_id, last_event_position, last_event_status, event_position, event_status, created,    modified )
#                         values( 1,  3,           0,        0,             -2,      2,          1,               1,                 0,                   0,                 0,              0,      1024392098, 1024392098 );

# class & attributes

drop table if exists ezcontentclass;
CREATE TABLE ezcontentclass (
id int auto_increment not null,
version int not null,
name varchar( 255 ),
identifier varchar(50) not null,
contentobject_name varchar(255),
creator_id int not null,
modifier_id int not null,
created int not null,
modified int not null,
primary key(id,version) );

insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 1, 0, 'Folder', 'folder', -1, -1, 1024392098, 1024392098);
insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 2, 0, 'Article', 'article', -1, -1, 1024392098, 1024392098);

insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 3, 0, 'User group', 'user_group', -1, -1, 1024392098, 1024392098);
insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 4, 0, 'User', 'user', -1, -1, 1024392098, 1024392098);

insert into ezcontentclass (id, version, name, identifier, creator_id, modifier_id, created, modified) values( 5, 0, 'Låve', 'laave', -1, -1, 1024392098, 1024392098);


drop table if exists ezcontentclass_attribute;
CREATE TABLE ezcontentclass_attribute (
id int auto_increment not null,
version int not null,
contentclass_id int not null,
identifier varchar(50) not null,
name varchar( 255 ) not null,
data_type_string varchar(50) not null,
is_searchable int(1) not null,
placement int not null,
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
primary key(id,version) );

# article attributes
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(1, 0, 2, 'title', 'Title', 'ezstring', 1, 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(2, 0, 2, 'intro', 'Intro', 'ezstring', 1, 2);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(3, 0, 2, 'integer', 'Integer text', 'ezinteger', 0, 3);


# folder attributes
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(4, 0, 1, 'name', 'Name', 'ezstring', 1, 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(5, 0, 1, 'name', 'Description', 'ezstring', 1, 2);

# user group attributes
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(6, 0, 3, 'name', 'Name', 'ezstring', 1, 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(7, 0, 3, 'description', 'Description', 'ezstring', 1, 2);

# user attributes
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(8, 0, 4, 'first_name', 'First name', 'ezstring', 1, 1);
insert into ezcontentclass_attribute (id, version, contentclass_id, identifier, name, data_type_string, is_searchable, placement) values(9, 0, 4, 'last_name', 'Last name', 'ezstring', 1, 2);


drop table if exists ezcontentobject;
CREATE TABLE ezcontentobject (
id int primary key auto_increment,
owner_id int not null,
parent_id int not null,
main_node_id int not null,
section_id int not null,
contentclass_id int not null,
name varchar( 255 ),
current_version int,
is_published int,
permission_id int,
published int not null,
modified int not null
);

insert into ezcontentobject set main_node_id='1', parent_id='0', contentclass_id='1', name='Top level folder', current_version='1', permission_id='1';

insert into ezcontentobject set main_node_id='2', parent_id='1', contentclass_id='2', name='An article', current_version='1', permission_id='1';
insert into ezcontentobject set main_node_id='3', parent_id='1', contentclass_id='2', name='Another message', current_version='1', permission_id='1';

# user groups
insert into ezcontentobject set main_node_id='4', parent_id='0', contentclass_id='3', name='Main User Group', current_version='1', permission_id='1';
insert into ezcontentobject set main_node_id='5', parent_id='4', contentclass_id='3', name='Content editors', current_version='1', permission_id='1';

# users
insert into ezcontentobject set main_node_id='6', parent_id='4', contentclass_id='4', name='Bård Farstad (root)', current_version='1', permission_id='1';
insert into ezcontentobject set main_node_id='7', parent_id='5', contentclass_id='4', name='Christoffer Elo', current_version='1', permission_id='1';

drop table if exists ezcontentobject_link;
CREATE TABLE ezcontentobject_link (
id int primary key auto_increment,
from_contentobject_id int not null,
from_contentobject_version int not null,
to_contentobject_id int not null
);


drop table if exists ezcontentobject_version;
CREATE TABLE ezcontentobject_version (
id int primary key auto_increment,
contentobject_id int,
creator_id int not null default '0',
version int not null default '0',
created int not null default '0',
modified int not null default '0',
status int not null default '0',
workflow_event_pos int not null default '0',
user_id int not null default '0'
);

insert into ezcontentobject_version set contentobject_id='1', version='1', status='1', workflow_event_pos='1';
insert into ezcontentobject_version set contentobject_id='2', version='1', status='1', workflow_event_pos='1';
insert into ezcontentobject_version set contentobject_id='3', version='1', status='1', workflow_event_pos='1';
insert into ezcontentobject_version set contentobject_id='4', version='1', status='1', workflow_event_pos='1';
insert into ezcontentobject_version set contentobject_id='5', version='1', status='1', workflow_event_pos='1';
insert into ezcontentobject_version set contentobject_id='6', version='1', status='1', workflow_event_pos='1';
insert into ezcontentobject_version set contentobject_id='7', version='1', status='1', workflow_event_pos='1';

drop table if exists ezcontentobject_attribute;
CREATE TABLE ezcontentobject_attribute (
id int auto_increment not null,
language_code varchar( 20 ) not null,
version int not null,
contentobject_id int not null,
contentclassattribute_id int not null,
data_text text,
data_int int,
data_float float,
primary key(id, version) );

insert into ezcontentobject_attribute set contentobject_id='1', version='1', language_code='en_GB', contentclassattribute_id='4', data_text='My folder';
insert into ezcontentobject_attribute set contentobject_id='1', version='1', language_code='en_GB', contentclassattribute_id='5', data_text='This folder contains some information about...';

insert into ezcontentobject_attribute set contentobject_id='2', version='1', language_code='en_GB', contentclassattribute_id='1', data_text='This is an article';
insert into ezcontentobject_attribute set contentobject_id='2', version='1', language_code='en_GB', contentclassattribute_id='2', data_text='Intro..';
insert into ezcontentobject_attribute set contentobject_id='2', version='1', language_code='en_GB', contentclassattribute_id='3', data_int='42';

insert into ezcontentobject_attribute set contentobject_id='3', version='1', language_code='en_GB', contentclassattribute_id='4', data_text='This is the message';

# user group data
insert into ezcontentobject_attribute set contentobject_id='4', version='1', language_code='en_GB', contentclassattribute_id='5', data_text='Main group';
insert into ezcontentobject_attribute set contentobject_id='4', version='1', language_code='en_GB', contentclassattribute_id='6', data_text='This is the master users';

# user  data
insert into ezcontentobject_attribute set contentobject_id='5', version='1', language_code='en_GB', contentclassattribute_id='7', data_text='Bård';
insert into ezcontentobject_attribute set contentobject_id='5', version='1', language_code='en_GB', contentclassattribute_id='8', data_text='Farstad (root)';

insert into ezcontentobject_attribute set contentobject_id='6', version='1', language_code='en_GB', contentclassattribute_id='7', data_text='Christoffer';
insert into ezcontentobject_attribute set contentobject_id='6', version='1', language_code='en_GB', contentclassattribute_id='8', data_text='Elo';


#
# Permissions
#

drop table if exists   ezcontentobject_perm_set;
CREATE TABLE ezcontentobject_perm_set (
id int not null primary key auto_increment,
name  varchar(255)
);
insert into  ezcontentobject_perm_set( name ) values( 'test set' );
insert into  ezcontentobject_perm_set( name ) values( 'test set 2' );




drop table if exists ezcontentobject_permission;
CREATE TABLE ezcontentobject_permission (
id int primary key auto_increment,
permission_id int not null,
user_group_id int not null,
read_permission int not null,
create_permission int not null,
edit_permission int not null,
remove_permission int not null
);


##insert into ezcontentobject_permission set permission_id='1', user_group_id='-1', read_permission='1', create_permission='0', edit_permission='0', remove_permission='0';
insert into ezcontentobject_permission set permission_id='1', user_group_id='2', read_permission='0', create_permission='1', edit_permission='1', remove_permission='0';
insert into ezcontentobject_permission set permission_id='1', user_group_id='3', read_permission='0', create_permission='1', edit_permission='1', remove_permission='1';


# check permissions for anonymous
select * from ezcontentobject_permission where permission_id='1' AND ( user_group_id='-1' );

# check permissions for anonymous and group 2
select max( read_permission ),  max( create_permission ), max( edit_permission ), max( remove_permission )
from ezcontentobject_permission where permission_id='1' AND ( user_group_id='2' or user_group_id='-1' );

# check permissions for anonymous,  group 2 and group 3
select max( read_permission ),  max( create_permission ), max( edit_permission ), max( remove_permission )
from ezcontentobject_permission where permission_id='1' AND ( user_group_id='2' or user_group_id='3' or user_group_id='-1' );


# Users

drop table if exists ezuser;
CREATE TABLE ezuser (
contentobject_id int not null,
login varchar( 150 ) not null,
email varchar( 150 ) not null,
password_hash_type int not null default 1,
password_hash varchar( 50 )
);

insert into ezuser ( contentobject_id, login, email, password_hash_type, password_hash ) VALUES ( '1', 'anonymous', 'anon@anon.com', 1, '' ); 


# Search

DROP TABLE  if exists ezsearch_object_word_link;
CREATE TABLE ezsearch_object_word_link
(
id int primary key auto_increment,
contentobject_id int not null,
word_id int not null,
frequency float not null,
placement int not null,
prev_word_id int not null,
next_word_id int not null,
contentclass_id int not null,
contentclass_attribute_id int not null
);


drop table if exists ezsearch_word;
CREATE TABLE ezsearch_word
(
id int primary key auto_increment,
word varchar( 150 ),
object_count int not null
);

CREATE INDEX ezsearch_object_word_link_object ON ezsearch_object_word_link (contentobject_id);
CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link (word_id);
CREATE INDEX ezsearch_object_word_link_frequency ON ezsearch_object_word_link (frequency);
CREATE INDEX ezsearch_word ON ezsearch_word (word);

# search log

drop table if exists ezsearch_search_phrase;
CREATE TABLE ezsearch_search_phrase
(
id int primary key auto_increment,
phrase varchar( 250 )
);

drop table if exists ezsearch_return_count;
CREATE TABLE ezsearch_return_count
(
id int primary key auto_increment,
phrase_id int not null,
time int not null,
count int not null
);

# Image :

drop table if exists ezimage;
CREATE TABLE ezimage (
contentobject_attribute_id int not null,
version int not null,
filename varchar(255) not null,
original_filename varchar(255) not null,
mime_type varchar(50) not null,
primary key(contentobject_attribute_id, version) );

drop table if exists ezimagevariation;
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

# Binary file:

drop table if exists ezbinaryfile;
CREATE TABLE ezbinaryfile (
contentobject_attribute_id int not null,
version int not null,
filename varchar(255) not null,
original_filename varchar(255) not null,
mime_type varchar(50) not null,
primary key( contentobject_attribute_id, version ) );


# EnumValue:

drop table if exists ezenumvalue;
CREATE TABLE ezenumvalue (
id int auto_increment not null,
contentclass_attribute_id int not null,
contentclass_attribute_version int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
placement int not null,
primary key( id, contentclass_attribute_id, contentclass_attribute_version ) );

# EnumObjectValue:
drop table if exists ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
contentobject_attribute_id int not null,
contentobject_attribute_version int not null,
enumid int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
primary key( contentobject_attribute_id,contentobject_attribute_version,enumid ) );


# Contentclassgroup:
drop table if exists ezcontentclassgroup;
CREATE TABLE ezcontentclassgroup (
id int auto_increment not null,
name varchar( 255 ),
creator_id int not null,
modifier_id int not null,
created int not null,
modified int not null,
primary key( id ) );


# Contentclass_classgroup
drop table if exists ezcontentclass_classgroup;
CREATE TABLE ezcontentclass_classgroup (
contentclass_id int not null,
contentclass_version int not null,
group_id int not null,
group_name  varchar( 255 ),
primary key( contentclass_id, contentclass_version, group_id ) );


## Product collection SQL

drop table if exists ezproductcollection;
CREATE TABLE ezproductcollection (
id int not null auto_increment,
primary key(id) );

drop table if exists ezproductcollection_item;
CREATE TABLE ezproductcollection_item (
id int not null auto_increment,
productcollection_id int not null,
contentobject_id int not null,
item_count int not null,
price_is_inc_vat int not null,
price int not null,
primary key(id) );

drop table if exists ezbasket;
CREATE TABLE ezbasket (
id int not null auto_increment,
session_id varchar(255) not null,
productcollection_id int not null,
primary key(id) );

drop table if exists ezorder;
CREATE TABLE ezorder (
id int not null auto_increment,
user_id int not null,
productcollection_id int not null,
created int not null,
primary key(id) );

drop table if exists ezwishlist;
CREATE TABLE ezwishlist (
id int not null auto_increment,
user_id int not null,
productcollection_id int not null,
primary key(id) );

# Session
drop table if exists ezsession;
create table ezsession
(
 session_key char(32) not null,
 expiration_time int(11) unsigned not null,
 data text not null,
 primary key (session_key), 
 key (expiration_time) 
);

# Section

drop table if exists ezsection;
create table ezsection
(
 id int not null auto_increment,
 name varchar(255),
 locale varchar(255),
 primary key (id)
);



# ezcontentobjecttree

drop table if exists ezcontentobject_tree;
create table ezcontentobject_tree(
node_id int primary key auto_increment,
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

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, left_margin, right_margin) values ( 0, 0, 0, 1, 1, 0, '/0/', 1, 16 );
update ezcontentobject_tree set node_id=0 ;
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, depth, path_string, left_margin, right_margin) values ( 1, 0, 1, 1, 1, '/0/1/', 2,  7);
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, depth, path_string, left_margin, right_margin) values ( 2, 1, 2, 1, 2, '/0/1/2/', 3, 4);
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, depth, path_string, left_margin, right_margin) values ( 3, 1, 3, 1, 2, '/0/1/3/', 5, 6);

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, depth, path_string, left_margin, right_margin) values ( 4, 0, 4, 1, 1, '/0/4/', 8,  15);

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, depth, path_string, left_margin, right_margin) values ( 5, 4, 5, 1, 2, '/0/4/5/', 9, 12);

insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, depth, path_string, left_margin, right_margin) values ( 6, 4, 6, 1, 2, '/0/4/6/', 13, 14);
insert into ezcontentobject_tree  ( node_id, parent_node_id, contentobject_id, contentobject_version, depth, path_string, left_margin, right_margin) values ( 7, 5, 7, 1, 3, '/0/4/5/7/', 10, 11);



DROP TABLE IF EXISTS eztask;
CREATE TABLE eztask (
id int AUTO_INCREMENT NOT NULL,
task_type int NOT NULL,
status int NOT NULL,
connection_type int NOT NULL,
session_hash varchar(80) NOT NULL,
creator_id int NOT NULL,
receiver_id int NOT NULL,
parent_task_type int NOT NULL,
parent_task_id int NOT NULL,
access_type int NOT NULL,
object_type int NOT NULL,
object_id int NOT NULL,
created int NOT NULL,
modified int NOT NULL,
PRIMARY KEY(id) );

-- INSERT INTO eztask (id, task_type, status, connection_type, creator_id, receiver_id, created,    modified)
--              VALUES(1,  1,         2,      1,               92,         92,          1031214781, 1031214781);
-- INSERT INTO eztask (id, task_type, status, connection_type, creator_id, receiver_id, created,    modified)
--              VALUES(2,  1,         1,      1,               92,         8,         1031215781, 1031215781);
-- INSERT INTO eztask (id, task_type, status, connection_type, creator_id, receiver_id, created,    modified)
--              VALUES(3,  1,         2,      1,               92,         155,         1031216781, 1031216781);
-- INSERT INTO eztask (id, task_type, status, connection_type, creator_id, receiver_id, created,    modified)
--              VALUES(4,  1,         2,      1,               11,         92,         1031217781, 1031217781);

DROP TABLE IF EXISTS eztask_message;
CREATE TABLE eztask_message (
id int AUTO_INCREMENT NOT NULL,
task_id int NOT NULL,
contentobject_id int NOT NULL,
created int NOT NULL,
creator_type int NOT NULL,
PRIMARY KEY(id) );

-- INSERT INTO eztask_message (id, task_id, contentobject_id, created,    creator_type)
--                      VALUES(1,  16,      24,               1031214781, 1);
