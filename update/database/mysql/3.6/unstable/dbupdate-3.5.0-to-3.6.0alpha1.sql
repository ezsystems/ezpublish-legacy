UPDATE ezsite_data SET value='3.6.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE eztipafriend_request (
  email_receiver varchar(100) NOT NULL default '',
  created integer NOT NULL default '0',
  KEY created (created),
  KEY email_receiver (email_receiver)
) TYPE=MyISAM;

ALTER TABLE ezrss_export_item ADD subnodes INT UNSIGNED DEFAULT '0' NOT NULL;

ALTER TABLE ezrss_export ADD number_of_objects INT UNSIGNED DEFAULT '0' NOT NULL;
# Old behaviour of RSS was that it fed 5 items
UPDATE ezrss_export SET number_of_objects='5';
