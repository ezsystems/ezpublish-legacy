# sql script for MySql

drop table if exists ezenumvalue;
CREATE TABLE ezenumvalue (
  contentclass_attribute_id int(11) NOT NULL default '0',
  contentclass_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumvalue varchar(255) NOT NULL default '',
  id int(11) NOT NULL auto_increment,
  placement int(11) NOT NULL default '0',
  PRIMARY KEY  (id,contentclass_attribute_id,contentclass_attribute_version),
  KEY ezenumvalue_co_cl_attr_id_co_class_att_ver (contentclass_attribute_id,contentclass_attribute_version)
);

drop table if exists ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  enumelement varchar(255) NOT NULL default '',
  enumid int(11) NOT NULL default '0',
  enumvalue varchar(255) NOT NULL default '',
  PRIMARY KEY  (contentobject_attribute_id,contentobject_attribute_version,enumid),
  KEY ezenumobjectvalue_co_attr_id_co_attr_ver (contentobject_attribute_id,contentobject_attribute_version)
);

#####################################################################################
# sql script for PostgreSql

drop SEQUENCE ezenumvalue_s ;
CREATE SEQUENCE ezenumvalue_s;

drop table ezenumvalue;
CREATE TABLE ezenumvalue (
    contentclass_attribute_id integer DEFAULT 0 NOT NULL,
    contentclass_attribute_version integer DEFAULT 0 NOT NULL,
    enumelement character varying(255) DEFAULT ''::character varying NOT NULL,
    enumvalue character varying(255) DEFAULT ''::character varying NOT NULL,
    id integer DEFAULT nextval('ezenumvalue_s'::text) NOT NULL,
    placement integer DEFAULT 0 NOT NULL
);

drop table ezenumobjectvalue;
CREATE TABLE ezenumobjectvalue (
    contentobject_attribute_id integer DEFAULT 0 NOT NULL,
    contentobject_attribute_version integer DEFAULT 0 NOT NULL,
    enumelement character varying(255) DEFAULT ''::character varying NOT NULL,
    enumid integer DEFAULT 0 NOT NULL,
    enumvalue character varying(255) DEFAULT ''::character varying NOT NULL
);

#######################################################################################
# sql script for Oracle

drop sequence s_enumvalue;
create sequence s_enumvalue;

drop table ezenumvalue;
create table ezenumvalue (
id int not null,
contentclass_attribute_id integer default 0 not null,
contentclass_attribute_version integer default 0 not null,
enumelement varchar2(255) not null,
enumvalue varchar2(255) not null,
placement integer default 0 not null,
primary key( id, contentclass_attribute_id, contentclass_attribute_version ) );

create index ezenv_coc_attr_id_coc_attr_ver on ezenumvalue
(contentclass_attribute_id, contentclass_attribute_version);

create or replace trigger ezenumvalue_id_tr
before insert on ezenumvalue for each row
when (
new.id is null
      )
begin
  select s_enumvalue.nextval into :new.id from dual;
end;
/

drop table ezenumobjectvalue;
create table ezenumobjectvalue (
contentobject_attribute_id integer default 0 not null,
contentobject_attribute_version integer default 0 not null,
enumid integer default 0 not null,
enumelement varchar2(255) not null,
enumvalue varchar2(255) not null,
primary key( contentobject_attribute_id,contentobject_attribute_version,enumid ) );

create index ezenov_co_attr_id_co_attr_ver on ezenumobjectvalue
(contentobject_attribute_id, contentobject_attr_version);
