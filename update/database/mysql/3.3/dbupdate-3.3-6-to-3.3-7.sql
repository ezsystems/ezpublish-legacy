UPDATE ezsite_data SET value='7' WHERE name='ezpublish-release';

CREATE TABLE eztipafriend_request (
  email_receiver varchar(100) NOT NULL default '',
  created int(11) NOT NULL default '0',
  KEY created (created),
  KEY email_receiver (email_receiver)
) TYPE=MyISAM;


ALTER TABLE ezsearch_word DROP KEY ezsearch_word;
ALTER TABLE ezsearch_word CHANGE word word blob;
ALTER TABLE ezsearch_word ADD KEY ezsearch_word (word(50));

