#Adding two field in table ezcontentobject_tree

alter table ezcontentobject_tree add sort_field int default 1 after path_identification_string;
alter table ezcontentobject_tree add sort_order int(1) default 1 after path_identification_string;


#Adding two field in table eznode_assignment
alter table eznode_assignment add sort_field int default 1 after main;
alter table eznode_assignment add sort_order int(1) default 1 after main;

# Change the length of enum fields
alter table ezenumvalue change enumelement enumelement varchar(255);
alter table ezenumvalue change enumvalue enumvalue varchar(255);

alter table ezenumobjectvalue change enumelement enumelement varchar(255);
alter table ezenumobjectvalue change enumvalue enumvalue varchar(255);

# Adding two field in table ezsearch_object_word_link
alter table ezsearch_object_word_link add published int not null after contentclass_attribute_id;
alter table ezsearch_object_word_link add section_id int not null after published;

# Sorting priority
alter table ezcontentobject_tree add priority int not null default '0';

# storing info about module and function of limitation
# you should run that query only for kernel_clean db
update ezpolicy_limitation set function_name='read', module_name='content', identifier='Class' where policy_id=306;

# add session_key for session based workflows
# note!!!: for PostgreSQL you are not able to set default value when add column. You need to run additional
# alter command: alter table ezworkflow_process alter  column session_key SET DEFAULT '0';
alter table ezworkflow_process add column session_key varchar(32) NOT NULL DEFAULT '0';
alter table ezworkflow_process add column process_key char(32) NOT NULL ;
alter table  ezworkflow_process add column parameters text;
alter table  ezworkflow_process add column memento_key char(32);

# Change data type for messages
alter table ezmessage change title title varchar(255);
alter table ezmessage change body body text;


#After beta 2:
# for mysql
alter table eznode_assignment add from_node_id int default 0;
# for Postgresql
# alter table eznode_assignment add from_node_id int;
# alter table eznode_assignment alter column from_node_id set default 0;

# After beta 3
DROP TABLE IF EXISTS ezoperation_memento;
CREATE TABLE ezoperation_memento (
id int NOT NULL auto_increment,
main_key int NOT NULL default 0,
memento_key varchar(32) NOT NULL,
memento_data text NOT NULL,
main int NOT NULL default 0,
PRIMARY KEY(id, memento_key) );

alter table ezcontentclass_attribute add is_information_collector int not null default 0;

alter table ezworkflow_group_link drop PRIMARY KEY;
alter table ezworkflow_group_link modify COLUMN workflow_version int not null default 0;
alter table ezworkflow_group_link add PRIMARY KEY ( workflow_id,group_id,workflow_version);


CREATE TABLE ezvattype (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL,
  percentage float default NULL,
  PRIMARY KEY  (id) );


alter table ezproductcollection_item drop price_is_inc_vat;


CREATE TABLE ezuser_discountrule (
  id int(11) NOT NULL auto_increment,
  discountrule_id int(11) default NULL,
  contentobject_id int(11) default NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY  (id) );


CREATE TABLE ezdiscountsubrule (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL,
  discountrule_id int(11) NOT NULL,
  discount_percent float default NULL,
  limitation char(1) default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE ezdiscountsubrule_value (
  discountsubrule_id int(11) NOT NULL,
  value int(11) NOT NULL,
  issection int(1) NOT NULL,
  PRIMARY KEY  (discountsubrule_id,value,issection)
);

create table ezinformationcollection (
  id int(11) NOT NULL auto_increment,
  contentobject_id int(11) not null default 0,
  created int(11) not null default 0,
  PRIMARY KEY  (id) 
);

create table ezinformationcollection_attribute (
  id int(11) NOT NULL auto_increment,
  informationcollection_id int(11) not null default 0,
  data_text text,
  data_int int(11) default NULL,
  data_float float default NULL,
  PRIMARY KEY  (id) 
);
