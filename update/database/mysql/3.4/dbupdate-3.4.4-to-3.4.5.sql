UPDATE ezsite_data SET value='3.4.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='12' WHERE name='ezpublish-release';

CREATE TABLE eztipafriend_request (
  email_receiver varchar(100) NOT NULL default '',
  created integer NOT NULL default '0',
  KEY created (created),
  KEY email_receiver (email_receiver)
) TYPE=MyISAM;

