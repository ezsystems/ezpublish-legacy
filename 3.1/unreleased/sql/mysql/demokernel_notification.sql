--
-- Table structure for table 'eznotification_rule'
--

CREATE TABLE eznotification_rule (
  id int(11) NOT NULL auto_increment,
  type varchar(250) NOT NULL default '',
  contentclass_name varchar(250) NOT NULL default '',
  path varchar(250) default NULL,
  keyword varchar(250) default NULL,
  has_constraint int(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'eznotification_rule'
--


--
-- Table structure for table 'eznotification_user_link'
--

CREATE TABLE eznotification_user_link (
  rule_id int(11) NOT NULL default '0',
  user_id int(11) NOT NULL default '0',
  send_method varchar(50) NOT NULL default '',
  send_weekday varchar(50) NOT NULL default '',
  send_time varchar(50) NOT NULL default '',
  destination_address varchar(50) NOT NULL default '',
  PRIMARY KEY  (rule_id,user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'eznotification_user_link'
--
