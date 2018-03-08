# sql script for MySql

drop table if exists ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
contentobject_attribute_id int not null,
contentobject_attribute_version int not null,
enumid int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
primary key( contentobject_attribute_id, contentobject_attribute_version, enumid ) );
