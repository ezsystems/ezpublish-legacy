# sql script for MySql

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


#####################################################################################
# sql script for PostgreSql

drop SEQUENCE ezenumvalue_s ;
CREATE SEQUENCE ezenumvalue_s;

drop table ezenumvalue;
CREATE TABLE ezenumvalue (
id int DEFAULT nextval('ezenumvalue_s') not null,
contentclass_attribute_id int not null,
contentclass_attribute_version int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
placement int not null,
primary key( id, contentclass_attribute_id, contentclass_attribute_version ) );



# EnumObjectValue:
drop table ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
contentobject_attribute_id int not null,
contentobject_attribute_version int not null,
enumid int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
primary key( contentobject_attribute_id,contentobject_attribute_version,enumid ) );


#######################################################################################
# sql script for Oracle

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

# EnumObjectValue:
drop table ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
contentobject_attribute_id int not null,
contentobject_attribute_version int not null,
enumid int not null,
enumelement varchar(50) not null,
enumvalue varchar(50) not null,
primary key( contentobject_attribute_id,contentobject_attribute_version,enumid ) );
