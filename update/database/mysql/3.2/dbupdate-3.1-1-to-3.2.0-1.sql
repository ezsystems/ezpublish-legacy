alter table ezcontentclass_attribute add column data_text5 text;
alter table ezsearch_object_word_link add column identifier varchar(255) not null default '';
alter table ezsearch_object_word_link add column integer_value integer not null default '0';

CREATE TABLE ezcollab_notification_rule (
    id integer  auto_increment NOT NULL,
    user_id varchar(255) NOT NULL,
    collab_identifier varchar(255) not null,
    primary key ( id )
);

CREATE TABLE ezurl_object_link (
  url_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  PRIMARY KEY (url_id,contentobject_attribute_id,contentobject_attribute_version)
);

ALTER TABLE ezcontentobject_attribute ADD sort_key varchar(255);
ALTER TABLE ezcontentobject_attribute ADD index ( sort_key );

CREATE TABLE ezsite_data (
  id int(11) auto_increment NOT NULL,
  name varchar(60) NOT NULL default '',
  value text NOT NULL default '',
  PRIMARY KEY (id)
);

INSERT INTO ezsite_data (name, value) VALUES('ezpublish-version', '3.2.0');
INSERT INTO ezsite_data (name, value) VALUES('ezpublish-release', '1');


create index ezsearch_word_object_count on ezsearch_word(object_count);
create index ezcontentobject_status on ezcontentobject( status );
create index ezcontentobject_tree_path_depth on ezcontentobject_tree( path_string, depth );
