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

alter table ezcontentobject_attribute add sort_key varchar(255);
alter table ezcontentobject_attribute add index ( sort_key );
