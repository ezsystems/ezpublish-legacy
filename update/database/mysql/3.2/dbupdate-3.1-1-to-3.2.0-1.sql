alter table ezcontentclass_attribute add column data_text5 text;
alter table ezsearch_object_word_link add column identifier varchar(255) not null default '';
alter table ezsearch_object_word_link add column integer_value integer not null default '0';

CREATE TABLE ezurl_object_link (
  url_id int(11) NOT NULL default '0',
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  PRIMARY KEY (url_id,contentobject_attribute_id,contentobject_attribute_version)
);