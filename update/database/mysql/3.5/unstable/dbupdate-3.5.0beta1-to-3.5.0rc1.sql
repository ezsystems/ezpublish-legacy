UPDATE ezsite_data SET value='3.5.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';


alter table ezrole add column is_new integer not null default 0;
