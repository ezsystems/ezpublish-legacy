UPDATE ezsite_data SET value='3.3.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

alter table ezinfocollection add column user_identifier varchar(34);
alter table ezinfocollection add column modified int not null default 0;
alter table ezinfocollection_attribute add column contentobject_attribute_id int;
alter table ezinfocollection_attribute add column contentobject_id int;
