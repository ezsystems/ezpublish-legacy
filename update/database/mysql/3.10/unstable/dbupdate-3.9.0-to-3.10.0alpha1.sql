UPDATE ezsite_data SET value='3.10.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- extend length of 'serialized_name_list'
ALTER TABLE ezcontentclass CHANGE COLUMN serialized_name_list serialized_name_list longtext default NULL;
ALTER TABLE ezcontentclass_attribute CHANGE COLUMN serialized_name_list serialized_name_list longtext NOT NULL;


-- Enhanced ISBN datatype.
CREATE TABLE ezisbn_group (
  id int(11) NOT NULL auto_increment,
  description varchar(255) NOT NULL default '',
  group_number int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

CREATE TABLE ezisbn_group_range (
  id int(11) NOT NULL auto_increment,
  from_number int(11) NOT NULL default '0',
  to_number int(11) NOT NULL default '0',
  group_from varchar(32) NOT NULL default '',
  group_to varchar(32) NOT NULL default '',
  group_length int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

CREATE TABLE ezisbn_registrant_range (
  id int(11) NOT NULL auto_increment,
  from_number int(11) NOT NULL default '0',
  to_number int(11) NOT NULL default '0',
  registrant_from varchar(32) NOT NULL default '',
  registrant_to varchar(32) NOT NULL default '',
  registrant_length int(11) NOT NULL default '0',
  isbn_group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);
