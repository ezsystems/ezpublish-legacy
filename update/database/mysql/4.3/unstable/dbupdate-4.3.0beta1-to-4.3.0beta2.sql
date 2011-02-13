SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.3.0beta2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE ezscheduled_script (
  id int(11) NOT NULL auto_increment,
  process_id int(11) NOT NULL default '0',
  name varchar(50) NOT NULL default '',
  command varchar(255) NOT NULL default '',
  last_report_timestamp int(11) NOT NULL default '0',
  progress int(3) default '0',
  user_id int(11) NOT NULL default '0',
  PRIMARY KEY (id),
  KEY ezscheduled_script_timestamp (last_report_timestamp)
);
