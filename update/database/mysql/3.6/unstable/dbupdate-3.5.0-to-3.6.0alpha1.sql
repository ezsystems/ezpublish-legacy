UPDATE ezsite_data SET value='3.6.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE eztipafriend_request (
  email_receiver varchar(100) NOT NULL default '',
  created integer NOT NULL default '0',
  KEY created (created),
  KEY email_receiver (email_receiver)
) TYPE=MyISAM;

ALTER TABLE ezrss_export_item ADD subnodes INT UNSIGNED DEFAULT '0' NOT NULL;
