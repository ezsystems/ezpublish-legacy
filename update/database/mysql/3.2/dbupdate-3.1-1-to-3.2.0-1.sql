alter table ezcontentclass_attribute add column data_text5 text;
alter table ezsearch_object_word_link add column identifier varchar(255) not null default '';
alter table ezsearch_object_word_link add column integer_value integer not null default '0';
