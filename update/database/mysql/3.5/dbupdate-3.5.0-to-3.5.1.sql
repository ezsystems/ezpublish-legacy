UPDATE ezsite_data SET value='3.5.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

-- Fix for tipafriend request functionality
CREATE TABLE eztipafriend_request (
  email_receiver varchar(100) NOT NULL default '',
  created integer NOT NULL default '0',
  KEY eztipafriend_request_created (created),
  KEY eztipafriend_request_email_rec (email_receiver)
) TYPE=MyISAM;

