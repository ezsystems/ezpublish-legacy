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
alter table eztrigger add column name varchar(255) not null;
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

# After beta 4

# For mysql
alter table ezcontentobject_tree  drop md5_path;
alter table ezcontentobject_tree  drop left_margin;
alter table ezcontentobject_tree  drop right_margin;

# For postgresql
#create table temp_tree as select * from ezcontentobject_tree;
#drop table ezcontentobject_tree;
#CREATE TABLE "ezcontentobject_tree" (
#	"node_id" integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
#	"parent_node_id" integer NOT NULL,
#	"contentobject_id" integer,
#	"contentobject_version" integer,
#	"contentobject_is_published" integer,
#	"crc32_path" integer,
#	"depth" integer NOT NULL,
#	"path_string" character varying(255) NOT NULL,
#	"path_identification_string" text,
#   "sort_field" integer default 1,
#   "sort_order" smallint default 1,
#   "priority" integer  default 0,
#	Constraint "ezcontentobject_tree_pkey" Primary Key ("node_id")
#);
#insert into ezcontentobject_tree select node_id, parent_node_id, contentobject_id, contentobject_version,  contentobject_is_published,crc32_path, depth, path_string, path_identification_string,sort_field,sort_order,priority from temp_tree;
#drop table temp_tree;

alter table eznode_assignment change main is_main int not null;
# PostgreSQL
# alter table eznode_assignment rename column main to is_main;

#alter table ezcontentobject drop main_node_id;
alter table ezcontentobject drop permission_id;
alter table ezcontentobject_tree add column main_node_id integer ;

create table ezcontentobject_name(
    contentobject_id int not null,
    name varchar(255),
    content_version int not null,
    content_translation varchar(20) not null,
    real_translation varchar(20),
    primary key (contentobject_id,content_version, content_translation )
    );
#you need to do it for each language you have
insert into ezcontentobject_name select id,name,current_version,  'eng-GB', 'eng-GB' from ezcontentobject, ezcontentobject_tree where ezcontentobject.id = ezcontentobject_tree.contentobject_id;
insert into ezcontentobject_name select id,name,current_version,  'nor-NO', 'eng-GB' from ezcontentobject, ezcontentobject_tree where ezcontentobject.id = ezcontentobject_tree.contentobject_id;

CREATE TABLE ezdiscountrule (
    id int(11) NOT NULL auto_increment,
    name varchar(255) NOT NULL,
    PRIMARY KEY  (id)
 );

alter table ezorder add is_temporary int not null default 1;

alter table ezinformationcollection_attribute add contentclass_attribute_id int not null;

create table ezorder_item(
    id int primary key NOT NULL auto_increment,
    order_id int not null,
    description varchar(255),
    price float,
    vat_is_included int,
    vat_type_id int
    );

alter table ezorder add order_nr int not null default 0;



# After RC1
create table ezwaituntildatevalue(
    id int NOT NULL auto_increment,
    workflow_event_id int NOT NULL default '0',
    workflow_event_version int NOT NULL default '0',
    contentclass_id int NOT NULL default '0',
    contentclass_attribute_id int NOT NULL default '0',
    PRIMARY KEY  (id,workflow_event_id,workflow_event_version),
    KEY ezwaituntildatevalue_wf_ev_id_wf_ver (workflow_event_id,workflow_event_version)
    );


alter table eznode_assignment add remote_id int(11) NOT NULL default '0';
alter table ezsession add cache_mask_1 int default 0 not null;
alter table ezcontentobject_tree add column md5_path varchar(32);
# remember to run update/common/scripts/updateniceurls.php to update path_identification strings
update ezcontentobject_tree set md5_path = md5( path_identification_string );
alter table ezcontentobject drop column parent_id;

create table ezcollab_item(
    id int NOT NULL auto_increment,
    type_identifier varchar(40) NOT NULL default '',
    creator_id int NOT NULL default '0',
    status int NOT NULL default '1',
    data_text1 text NOT NULL default '',
    data_text2 text NOT NULL default '',
    data_text3 text NOT NULL default '',
    data_int1 int NOT NULL default '0',
    data_int2 int NOT NULL default '0',
    data_int3 int NOT NULL default '0',
    data_float1 float NOT NULL default '0',
    data_float2 float NOT NULL default '0',
    data_float3 float NOT NULL default '0',
    created int NOT NULL default '0',
    modified int NOT NULL default '0',
    PRIMARY KEY  (id)
    );

create table ezcollab_group(
    id int NOT NULL auto_increment,
    parent_group_id int NOT NULL default '0',
    depth int(11) NOT NULL default '0',
    path_string varchar(255) NOT NULL default '',
    is_open int NOT NULL default '1',
    user_id int NOT NULL default '0',
    title varchar(255) NOT NULL default '',
    priority int NOT NULL default '0',
    created int NOT NULL default '0',
    modified int NOT NULL default '0',
    PRIMARY KEY  (id),
    KEY ezcollab_group_path (path_string),
    KEY ezcollab_group_depth (depth)
    );

create table ezcollab_item_group_link(
    collaboration_id int NOT NULL DEFAULT '0',
    group_id  int NOT NULL default '0',
    user_id int NOT NULL default '0',
    is_read int NOT NULL default '0',
    is_active int NOT NULL default '1',
    last_read int NOT NULL default '0',
    created int NOT NULL default '0',
    modified int NOT NULL default '0',
    PRIMARY KEY  (collaboration_id, group_id, user_id)
    );

create table ezcollab_item_status(
    collaboration_id int NOT NULL DEFAULT '0',
    user_id int NOT NULL default '0',
    is_read int NOT NULL default '0',
    is_active int NOT NULL default '1',
    last_read int NOT NULL default '0',
    PRIMARY KEY  (collaboration_id, user_id)
    );

create table ezcollab_item_participant_link(
    collaboration_id int NOT NULL DEFAULT '0',
    participant_id  int NOT NULL default '0',
    participant_type  int NOT NULL default '1',
    participant_role  int NOT NULL default '1',
    is_read int NOT NULL default '0',
    is_active int NOT NULL default '1',
    last_read int NOT NULL default '0',
    created int NOT NULL default '0',
    modified int NOT NULL default '0',
    PRIMARY KEY  (collaboration_id, participant_id)
    );

create table ezcollab_item_message_link(
    id int NOT NULL auto_increment,
    collaboration_id int NOT NULL DEFAULT '0',
    participant_id  int NOT NULL default '0',
    message_id  int NOT NULL default '0',
    message_type  int NOT NULL default '0',
    created int NOT NULL default '0',
    modified int NOT NULL default '0',
    PRIMARY KEY  (id)
    );

create table ezcollab_simple_message(
    id int NOT NULL auto_increment,
    message_type varchar(40) NOT NULL default '',
    creator_id int NOT NULL default '0',
    data_text1 text NOT NULL default '',
    data_text2 text NOT NULL default '',
    data_text3 text NOT NULL default '',
    data_int1 int NOT NULL default '0',
    data_int2 int NOT NULL default '0',
    data_int3 int NOT NULL default '0',
    data_float1 float NOT NULL default '0',
    data_float2 float NOT NULL default '0',
    data_float3 float NOT NULL default '0',
    created int NOT NULL default '0',
    modified int NOT NULL default '0',
    PRIMARY KEY  (id)
    );

create table ezcollab_profile(
    id int NOT NULL auto_increment,
    user_id int NOT NULL default '0',
    main_group int NOT NULL default '0',
    data_text1 text NOT NULL default '',
    created int NOT NULL default '0',
    modified int NOT NULL default '0',
    PRIMARY KEY  (id)
    );

create table ezcontent_translation(
    id int NOT NULL auto_increment,
    name varchar(255) NOT NULL DEFAULT '',
    locale varchar(255) NOT NULL,
    PRIMARY KEY  (id)
    );

# adding status to ezcontentobject and making correct update of it
alter table ezcontentobject add column status int default 0;
create temporary table ezcontentobject_temp as select distinct ezcontentobject.* from ezcontentobject, ezcontentobject_tree  where ezcontentobject_tree.contentobject_id = ezcontentobject.id;
update ezcontentobject_temp set status = 1;
insert into ezcontentobject_temp  select ezcontentobject.* from ezcontentobject left join ezcontentobject_tree on ezcontentobject.id = ezcontentobject_tree.contentobject_id where ezcontentobject_tree.contentobject_id is null;
delete from ezcontentobject;
insert into ezcontentobject select * from ezcontentobject_temp;


# Change workflow approve tables to reflect the new collaboration system
create table ezapprove_items(
    id int NOT NULL auto_increment,
    workflow_process_id int NOT NULL DEFAULT '0',
    collaboration_id int NOT NULL DEFAULT '0',
    PRIMARY KEY  (id)
    );

insert into ezapprove_items (workflow_process_id, collaboration_id) select workflow_process_id, task_id from ezapprovetasks;

drop table ezapprovetasks;

# Fixes a bug with workflows and mementos
alter table ezoperation_memento drop main_key;
alter table ezoperation_memento add main_key varchar(32) NOT NULL;

# Fixed a bug with a lacking field in ezmedia
alter table ezmedia add controls varchar(50);

# Add some extra info the url table
alter table ezurl add created int NOT NULL DEFAULT '0';
alter table ezurl add modified int NOT NULL DEFAULT '0';
alter table ezurl add is_valid int NOT NULL DEFAULT '1';
alter table ezurl add last_checked int NOT NULL DEFAULT '0';
alter table ezurl add original_url_md5 varchar(32) NOT NULL DEFAULT '';

# run update/common/scripts/updatexmltext.php to fix XML fields with bad links.

alter table ezinformationcollection rename as ezinfocollection;
alter table ezinformationcollection_attribute rename as ezinfocollection_attribute;
