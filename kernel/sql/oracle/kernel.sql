/*
ezworkflow table
*/
drop SEQUENCE ezworkflow_s;
CREATE SEQUENCE ezworkflow_s;

drop table   ezworkflow;
CREATE TABLE ezworkflow (
id int  not null ,
version int not null,
workflow_type_string varchar(50) not null,
name varchar( 255 ) not null,
creator_id int not null,
modifier_id int not null,
created int not null,
modified int not null,
primary key(id,version) );

create or replace trigger ezworkflow_tr
before insert on ezworkflow
for each row
begin
select ezworkflow_s.nextval into :new.id from dual;
end;
/

drop index ezworkflow_id;
create unique index ezworkflow_id on ezworkflow( id );
/*-------------*/
insert into ezworkflow ( version, workflow_type_string, name, creator_id, modifier_id, created, modified) values(  0, 'group_ezserial', 'Publish', -1, -1, 1024392098, 1024392098);
insert into ezworkflow ( version, workflow_type_string, name, creator_id, modifier_id, created, modified) values(  0, 'group_ezserial', 'Editor approval', -1, -1, 1024392098, 1024392098);
insert into ezworkflow ( version, workflow_type_string, name, creator_id, modifier_id, created, modified) values(  0, 'group_ezserial', 'Advanced approval', -1, -1, 1024392098, 1024392098);

/******************************************************************************************/

/*
ezworkflowevent table
*/

drop SEQUENCE ezworkflow_event_s ;
CREATE SEQUENCE ezworkflow_event_s ;

drop table  ezworkflow_event;
CREATE TABLE ezworkflow_event (
id int,
version int not null,
workflow_id int not null,
workflow_type_string varchar(50) not null,
description varchar(50) not null,
data_int1 int,
data_int2 int,
data_int3 int,
data_int4 int,
data_text1 int,
data_text2 int,
data_text3 int,
data_text4 int,
placement int not null,
primary key(id,version) );

drop index ezworkflow_event_id;
create unique index ezworkflow_event_id on ezworkflow_event( id );

create or replace trigger ezworkflow_event_tr
before insert on ezworkflow_event
for each row
begin
select ezworkflow_event_s.nextval into :new.id from dual;
end;
/


/* workflow events ------------------*/
insert into ezworkflow_event ( version, workflow_id, workflow_type_string, description, placement) values( 0, 1, 'event_ezpublish', 'Publish object', 1);
insert into ezworkflow_event ( version, workflow_id, workflow_type_string, description, placement) values( 0, 2, 'event_ezapprove', 'Approve by editor', 1);
insert into ezworkflow_event ( version, workflow_id, workflow_type_string, description, placement) values( 0, 2, 'event_ezmessage', 'Send message to editor', 2);



/******************************************************************************************/

/*
ezworkflowprocess table
*/

drop SEQUENCE ezworkflow_process_s ;
CREATE SEQUENCE ezworkflow_process_s;

drop table ezworkflow_process;
CREATE TABLE ezworkflow_process (
id int ,
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

create or replace trigger ezworkflow_process_tr
before insert on ezworkflow_process
for each row
begin
select ezworkflow_process_s.nextval into :new.id from dual;
end;
/


/*******************************************************************************/

/* class & attributes */

drop SEQUENCE ezcontentclass_s;
CREATE SEQUENCE ezcontentclass_s ;


drop table   ezcontentclass;
CREATE TABLE ezcontentclass (
id int , 
version int not null,
name varchar( 255 ),
identifier varchar(50) default '',
creator_id int not null,
modifier_id int not null,
created int not null,
modified int not null,
primary key(id,version) );

CREATE INDEX ezcontentclass_id  ON ezcontentclass ( id );
/*--------------------------------*/
create or replace trigger ezcontentclass_tr
before insert on ezcontentclass
for each row 
when (new.id is NULL)
begin
select ezcontentclass_s.nextval into :new.id from dual;
end;
/


insert into ezcontentclass ( version, name, identifier, creator_id, modifier_id, created, modified) values(  0, 'Folder', 'folder', -1, -1, 1024392098, 1024392098);
insert into ezcontentclass ( version, name, identifier, creator_id, modifier_id, created, modified) values(  0, 'Article', 'article', -1, -1, 1024392098, 1024392098);

insert into ezcontentclass ( version, name, identifier, creator_id, modifier_id, created, modified) values( 0, 'User group', 'user_group', -1, -1, 1024392098, 1024392098);
insert into ezcontentclass ( version, name, identifier, creator_id, modifier_id, created, modified) values( 0, 'User', 'user', -1, -1, 1024392098, 1024392098);


/************************************************************/



drop SEQUENCE ezcontentclass_attribute_s;
CREATE SEQUENCE ezcontentclass_attribute_s ;

drop table   ezcontentclass_attribute;
CREATE TABLE ezcontentclass_attribute (
id int ,
version int not null,
contentclass_id int not null,
identifier varchar(50) default '',
name varchar( 255 ) not null,
data_type_string varchar(50) not null,
placement int not null,
is_searchable smallint,
data_int1 int,
data_int2 int,
data_int3 int,
data_int4 int,
data_text1 varchar(50),
data_text2 varchar(50),
data_text3 varchar(50),
data_text4 varchar(50),
primary key(id,version) );


create or replace trigger ezcontentclass_attribute_tr
before insert on ezcontentclass_attribute
for each row
when (new.id is NULL)
begin
select ezcontentclass_attribute_s.nextval into :new.id from dual;
end;
/

/* article attributes */
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 2, 'title', 'Title', 'ezstring', 1);
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 2, 'intro', 'Intro', 'ezstring', 2);
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 2, 'integer', 'Integer text', 'ezinteger', 3);


/* folder attributes */
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 1, 'name', 'Name', 'ezstring', 1);
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 1, 'name', 'Description', 'ezstring', 2);

/* user group attributes */
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 3, 'name', 'Name', 'ezstring', 1);
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 3, 'description', 'Description', 'ezstring', 2);

/* user attributes */
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 4, 'first_name', 'First name', 'ezstring', 1);
insert into ezcontentclass_attribute ( version, contentclass_id, identifier, name, data_type_string, placement) values( 0, 4, 'last_name', 'Last name', 'ezstring', 2);




/*******************************************************************************/
drop SEQUENCE ezcontentobject_s;
CREATE SEQUENCE ezcontentobject_s;


drop table   ezcontentobject;
CREATE TABLE ezcontentobject ( 
id int primary key , 
parent_id int not null,
main_node_id int not null,
contentclass_id int not null,
name varchar( 255 ),
current_version int,
is_published int,
permission_id int
);
create unique index ezcontentobject_id on ezcontentobject(id);
create index ezcontentobject_parent_id on ezcontentobject(parent_id);

create or replace trigger ezcontentobject_tr
before insert on ezcontentobject
for each row
begin
select ezcontentobject_s.nextval into :new.id from dual;
end;
/
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



/*************************************************************************/

drop SEQUENCE ezcontentobject_link_s;
CREATE SEQUENCE ezcontentobject_link_s;

drop table   ezcontentobject_link;
CREATE TABLE ezcontentobject_link ( 
id int  primary key , 
from_contentobject_id int not null, 
from_contentobject_version int not null,
to_contentobject_id int
);
create or replace trigger ezcontentobject_link_tr
before insert on ezcontentobject_link
for each row
begin
select ezcontentobject_link_s.nextval into :new.id from dual;
end;
/

/*************************************************************************/

drop SEQUENCE ezcontentobject_version_s;
CREATE SEQUENCE ezcontentobject_version_s ;

drop table   ezcontentobject_version;
CREATE TABLE ezcontentobject_version (
id int primary key ,
contentobject_id int,
version int default 0 ,
created int default 0 ,
modified int default 0 ,
status int default 0 ,
workflow_event_pos int default 0,
user_id int default 0
);
create or replace trigger ezcontentobject_version_tr
before insert on ezcontentobject_version
for each row
begin
select ezcontentobject_version_s.nextval into :new.id from dual;
end;
/

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

<<<<<<< .mine
=======
drop table ezcontentobject_attribute;
>>>>>>> .r682
CREATE TABLE ezcontentobject_attribute (
id int not null,
language_code varchar( 20 ) not null,
version int not null,
contentobject_id int not null,
contentclassattribute_id int not null,
data_text clob,
data_int int,
data_float float,
primary key( id, version ) );

create or replace trigger ezcontentobject_attribute_tr
before insert on ezcontentobject_attribute
for each row
begin
select ezcontentobject_attribute_s.nextval into :new.id from dual;
end;
/

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
id int  primary key, 
name  varchar(255)
);

create or replace trigger ezcontentobject_perm_set_tr
before insert on ezcontentobject_perm_set
for each row
begin
select ezcontentobject_perm_set_s.nextval into :new.id from dual;
end;
/

insert into  ezcontentobject_perm_set( name ) values( 'test set' );
insert into  ezcontentobject_perm_set( name ) values( 'test set 2' );



drop SEQUENCE ezcontentobject_permission_s;
CREATE SEQUENCE ezcontentobject_permission_s;

drop table   ezcontentobject_permission;
CREATE TABLE ezcontentobject_permission ( 
id int primary key, 
permission_id int not null,
user_group_id int not null,
read_permission int not null,
create_permission int not null,
edit_permission int not null,
remove_permission int not null
);

create index ezco_perm_permission_id on ezcontentobject_permission(permission_id);
create index ezco_perm_user_group_id on ezcontentobject_permission(user_group_id);

create or replace trigger ezcontentobject_permission_tr
before insert on ezcontentobject_permission
for each row
begin
select ezcontentobject_permission_s.nextval into :new.id from dual;
end;
/

/*  */
insert into ezcontentobject_permission( permission_id,user_group_id,read_permission,create_permission,edit_permission,remove_permission) values ( 1, 2, 0, 1, 1, 0 );
insert into ezcontentobject_permission( permission_id,user_group_id,read_permission,create_permission,edit_permission,remove_permission) values ( 1, 3, 0, 1, 1, 1 );
insert into ezcontentobject_permission ( permission_id,user_group_id,read_permission,create_permission,edit_permission,remove_permission) values(1,4,1,1,1,1);



/*# check permissions for anonymous 
*/
select * from ezcontentobject_permission where permission_id='1' AND ( user_group_id='-1' );

/*# check permissions for anonymous and group 2
*/
select max( read_permission ),  max( create_permission ), max( edit_permission ), max( remove_permission )
from ezcontentobject_permission where permission_id='1' AND ( user_group_id='2' or user_group_id='-1' );

/*# check permissions for anonymous,  group 2 and group 3
*/
select max( read_permission ),  max( create_permission ), max( edit_permission ), max( remove_permission )
from ezcontentobject_permission where permission_id='1' AND ( user_group_id='2' or user_group_id='3' or user_group_id='-1' );


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



select a.id,a.name, max(b.read_permission),max(b.edit_permission)  from ezcontentobject a, ezcontentobject_permission  b where a.permission_id=b.permission_id and b.user_group_id in (1)  group by a.id,a.name;

SELECT
ezcontentobject.*,
ezcontentclass.name as class_name,
max( ezcontentobject_permission.read_permission ) as can_read,
max( ezcontentobject_permission.create_permission ) as can_create,
max( ezcontentobject_permission.edit_permission ) as can_edit,
max( ezcontentobject_permission.remove_permission ) as can_remove
FROM
ezcontentobject,
ezcontentobject_permission,
ezcontentclass
WHERE
ezcontentobject.contentclass_id=ezcontentclass.id
AND
ezcontentobject.permission_id=ezcontentobject_permission.permission_id
AND
ezcontentobject.parent_id='37'
AND
 ezcontentobject_permission.user_group_id IN ( 8 )
GROUP BY ezcontentobject.id, ezcontentobject.name,ezcontentobject.parent_id,ezcontentobject.contentclass_id,ezcontentobject.current_version,ezcontentobject.is_published,ezcontentobject.permission_id,ezcontentclass.name;

/*# Search*/
drop SEQUENCE  ezsearch_object_word_link_s;
CREATE SEQUENCE  ezsearch_object_word_link_s;



DROP TABLE  ezsearch_object_word_link;
CREATE TABLE ezsearch_object_word_link
(
id int primary key,
contentobject_id int not null,
word_id int not null,
frequency float not null,
placement int not null,
prev_word_id int not null,
next_word_id int not null,
contentclass_id int not null,
contentclass_attribute_id int not null
);

create or replace trigger ezsearch_object_word_link_tr
before insert on ezsearch_object_word_link
for each row
begin
select ezsearch_object_word_link_s.nextval into :new.id from dual;
end;
/


drop SEQUENCE ezsearch_word_s;
CREATE SEQUENCE ezsearch_word_s;

drop table ezsearch_word;
CREATE TABLE ezsearch_word
(
id int primary key,
word varchar( 150 ),
object_count int not null
);

CREATE INDEX ezsearch_obj_word_link_obj ON ezsearch_object_word_link (object_id);
CREATE INDEX ezsearch_obj_word_link_word ON ezsearch_object_word_link (word_id);
CREATE INDEX ezsearch_obj_word_link_freq ON ezsearch_object_word_link (frequency);
CREATE INDEX ezsearch_word_i ON ezsearch_word (word);

create or replace trigger ezsearch_word_tr
before insert on ezsearch_word
for each row
begin
select ezsearch_word_s.nextval into :new.id from dual;
end;
/

/*
# EnumValue
*/
drop SEQUENCE ezenumvalue_s ;
CREATE SEQUENCE ezenumvalue_s;

drop table ezenumvalue;
CREATE TABLE ezenumvalue (
id int not null,
contentclass_attribute_id int not null,
contentclass_attribute_version int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
placement int not null,
primary key( id, contentclass_attribute_id, contentclass_attribute_version ) );

create or replace trigger ezenumvalue_tr
before insert on ezenumvalue
for each row
begin
select ezenumvalue_s.nextval into :new.id from dual;
end;

/*
# EnumObjectValue:
*/
drop table ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
contentobject_attribute_id int not null,
contentobject_attribute_version int not null,
enumid int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
primary key( contentobject_attribute_id,contentobject_attribute_version,enumid ) );


/*
# ContentClassGroup
*/
drop SEQUENCE ezcontentclassgroup_s ;
CREATE SEQUENCE ezcontentclassgroup_s;

drop table ezcontentclassgroup;
CREATE TABLE ezcontentclassgroup (
id int not null,
name varchar( 255 ),
creator_id int not null,
modifier_id int not null,
created int not null,
modified int not null,
primary key( id ) );

create or replace trigger ezcontentclassgroup_tr
before insert on ezcontentclassgroup
for each row
begin
select ezcontentclassgroup_s.nextval into :new.id from dual;
end;

/*
# ContentClass_ClassGroup
*/
drop SEQUENCE ezcontentclass_classgroup_s ;
CREATE SEQUENCE ezcontentclass_classgroup_s;

drop table ezcontentclass_classgroup;
CREATE TABLE ezcontentclass_classgroup (
contentclass_id int not null,
contentclass_version int not null,
group_id int not null,
group_name  varchar( 255 ),
primary key( contentclass_id, contentclass_version, group_id ) );


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
# Image
*/
drop table ezimage;
CREATE TABLE ezimage (
contentobject_attribute_id int not null,
version int not null,
filename varchar(255) not null,
original_filename varchar(255) not null,
mime_type varchar(50) not null,
primary key(contentobject_attribute_id,version) );

/*
# ImageVariation
*/
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
node_id int primary key,
parent_node_id int not null,
contentobject_id int,
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

create or replace trigger ezcontentobject_tree_tr
before insert on ezcontentobject_tree
for each row
begin
select ezcontentobject_tree_s.nextval into :new.node_id from dual;
end;
/

insert into ezcontentobject_tree  (  parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values (  0, 1, 1, '/0/1/', 2,  7);
insert into ezcontentobject_tree  (  parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values (  1, 2, 2, '/0/1/2/', 3, 4);
insert into ezcontentobject_tree  (  parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values (  1, 3, 2, '/0/1/3/', 5, 6);

insert into ezcontentobject_tree  (  parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values (  0, 4, 1, '/0/4/', 8,  15);

insert into ezcontentobject_tree  (  parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values (  4, 5, 2, '/0/4/5/', 9, 12);

insert into ezcontentobject_tree  (  parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values (  4, 6, 2, '/0/4/6/', 13, 14);
insert into ezcontentobject_tree  (  parent_node_id, contentobject_id, depth, path_string, left_margin, right_margin) values (  5, 7, 3, '/0/4/5/7/', 10, 11);

DROP TABLE permission;
CREATE GLOBAL TEMPORARY TABLE permission(
permission_id int primary key, 
can_read int, 
can_create int, 
can_edit int,  
can_remove int
) ON COMMIT PRESERVE ROWS;


/*
## Product collection SQL
*/
drop SEQUENCE ezproductcollection_s;
CREATE SEQUENCE ezproductcollection_s;
drop table ezproductcollection;
CREATE TABLE ezproductcollection( 
id int,
primary key(id) );
create or replace trigger ezproductcollection_tr
before insert on ezproductcollection
for each row 
when (new.id is NULL)
begin
select ezproductcollection_s.nextval into :new.id from dual;
end;
/
drop SEQUENCE ezproductcollection_item_s;
CREATE SEQUENCE ezproductcollection_item_s;
drop table ezproductcollection_item;
CREATE TABLE ezproductcollection_item (
id int, 
productcollection_id int not null,
contentobject_id int not null,
item_count int not null,
price_is_inc_vat int not null,
price int not null,
primary key(id) );
create or replace trigger ezproductcollection_item_tr
before insert on ezproductcollection_item
for each row 
when (new.id is NULL)
begin
select ezproductcollection_item_s.nextval into :new.id from dual;
end;
/
drop SEQUENCE ezcart_s;
CREATE SEQUENCE ezcart_s;
drop table  ezcart;
CREATE TABLE ezcart (
id int,
session_id varchar(255) not null,
productcollection_id int not null,
primary key(id) );
create or replace trigger ezcart_tr
before insert on ezcart
for each row 
when (new.id is NULL)
begin
select ezcart_s.nextval into :new.id from dual;
end;
/
drop SEQUENCE ezorder_s;
CREATE SEQUENCE ezorder_s;
drop table  ezorder;
CREATE TABLE ezorder (
id int,
user_id int not null,
productcollection_id int not null,
created int not null,
primary key(id) );

create or replace trigger ezorder_tr
before insert on ezorder
for each row 
when (new.id is NULL)
begin
select ezorder_s.nextval into :new.id from dual;
end;
/


drop SEQUENCE ezwishlist_s;
CREATE SEQUENCE ezwishlist_s;

drop table if exists ezwishlist;
CREATE TABLE ezwishlist (
id int,
user_id int not null,
productcollection_id int not null,
primary key(id) );

create or replace trigger ezwishlist_tr
before insert on ezwishlist
for each row 
when (new.id is NULL)
begin
select ezwishlist_s.nextval into :new.id from dual;
end;
/

drop table permission
create global temporary table permission(
permission_id int primary key,
can_read int,
can_create int,
can_edit int,
can_remove int 
);
