UPDATE ezsite_data SET value='3.3.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezinfocollection ADD COLUMN user_identifier varchar(34);
ALTER TABLE ezinfocollection ADD COLUMN modified int NOT NULL DEFAULT 0;
ALTER TABLE ezinfocollection_attribute ADD COLUMN contentobject_attribute_id int;
ALTER TABLE ezinfocollection_attribute ADD COLUMN contentobject_id int;

CREATE TABLE ezpdf_export ( 
  id integer NOT NULL auto_increment,
  title varchar(255) default NULL,
  show_frontpage int default NULL,
  intro_text text default NULL,
  sub_text text default NULL,
  source_node_id int default NULL,
  export_structure varchar(255) default NULL,
  export_classes varchar(255) default NULL,
  site_access varchar(255) default NULL,
  pdf_filename varchar(255) default NULL,
  modifier_id integer default NULL,
  modified integer default NULL,
  created integer default NULL,
  creator_id integer default NULL,
  status integer default NULL,
  PRIMARY KEY (id) 
) TYPE=MyISAM;

CREATE TABLE ezrss_export (
  id integer NOT NULL auto_increment,
  title varchar(255) default NULL,
  modifier_id integer default NULL,
  modified integer default NULL,
  url varchar(255) default NULL,
  description text,
  image_id integer default NULL,
  active integer default NULL,
  access_url varchar(255) default NULL,
  created integer default NULL,
  creator_id integer default NULL,
  status integer default NULL,
  site_access varchar(255) default NULL,
  rss_version varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE ezrss_export_item (
  id integer NOT NULL auto_increment,
  rssexport_id integer default NULL,
  source_node_id integer default NULL,
  class_id integer default NULL,
  title varchar(255) default NULL,
  description varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE INDEX ezrss_export_rsseid ON ezrss_export_item( rssexport_id );

CREATE TABLE ezrss_import (
  id integer NOT NULL auto_increment,
  name varchar(255) default NULL,
  url text,
  destination_node_id integer default NULL,
  class_id integer default NULL,
  class_title varchar(255) default NULL,
  class_url varchar(255) default NULL,
  class_description varchar(255) default NULL,
  active integer default NULL,
  creator_id integer default NULL,
  created integer default NULL,
  modifier_id integer default NULL,
  modified integer default NULL,
  status integer default NULL,
  object_owner_id integer default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;


--
-- 
create table ezcontent_attribute_tmp as select ezcontentobject_attribute.*, ezcontentclass_attribute.data_type_string from ezcontentobject_attribute, ezcontentclass_attribute where  ezcontentobject_attribute.contentclassattribute_id=ezcontentclass_attribute.id and ezcontentclass_attribute.version=0;

delete from ezcontentobject_attribute;

alter table ezcontentobject_attribute add data_type_string varchar(50) not null;

-- alter table ezcontentobject_version add workflow_event_pos int(11) not null default '0';

insert into ezcontentobject_attribute select * from ezcontent_attribute_tmp;
drop table ezcontent_attribute_tmp;


-- MySQL 4.1 upgrade
--
-- alter table ezcontentobject_attribute add data_type_string varchar(50) not null;
--
-- update ezcontentobject_attribute, ezcontentclass_attribute 
-- set ezcontentobject_attribute.data_type_string=ezcontentclass_attribute.data_type_string 
-- where ezcontentobject_attribute.contentclassattribute_id=ezcontentclass_attribute.id;


CREATE TABLE ezimagefile (
  id INTEGER AUTO_INCREMENT NOT NULL,
  contentobject_attribute_id INTEGER NOT NULL,
  filepath TEXT NOT NULL,
  PRIMARY KEY ( id )
) TYPE=MyISAM;

CREATE INDEX ezimagefile_coid ON ezimagefile( contentobject_attribute_id );
CREATE INDEX ezimagefile_file ON ezimagefile( filepath(200) );


CREATE TABLE ezview_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;

CREATE TABLE eztipafriend_counter (
  node_id int(11) NOT NULL default '0',
  count int(11) NOT NULL default '0',
  PRIMARY KEY  (node_id)
) TYPE=MyISAM;
