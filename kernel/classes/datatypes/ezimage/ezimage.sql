###################################################################################
sql for mysql

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

###################################################################################
sql for postgresql
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

###################################################################################
sql for oracle

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
