UPDATE ezsite_data SET value='3.8.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder ADD is_archived INT DEFAULT '0' NOT NULL ;
ALTER TABLE ezorder ADD INDEX ( is_archived ) ;


-- Improved Approval Workflow -- START --
UPDATE ezworkflow_event set data_text3=data_int1;
-- Improved Approval Workflow --  END  --

UPDATE ezpolicy SET function_name='administrate' WHERE module_name='shop' AND function_name='adminstrate';

-- Improved RSS import. -- START --
ALTER TABLE ezrss_import ADD COLUMN import_description longtext NOT NULL DEFAULT '';
-- Improved RSS import. -- END --

-- Multicurrency. -- START --
CREATE TABLE ezcurrencydata (
  id int(11) NOT NULL auto_increment,
  code char(3) NOT NULL default '',
  symbol varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '1',
  auto_rate_value numeric(10,5) NOT NULL default '0.00000',
  custom_rate_value numeric(10,5) NOT NULL default '0.00000',
  rate_factor numeric(10,5) NOT NULL default '1.00000',
  PRIMARY KEY (id),
  KEY (code)
);

CREATE TABLE ezmultipricedata (
  id int(11) NOT NULL auto_increment,
  contentobject_attribute_id int(11) NOT NULL default '0',
  contentobject_attribute_version int(11) NOT NULL default '0',
  currency_code char(3) NOT NULL default '',
  value numeric(15,2) NOT NULL default '0.00',
  type int(11) NOT NULL default '0',
  PRIMARY KEY (id),
  KEY (contentobject_attribute_id),
  KEY (contentobject_attribute_version),
  KEY (currency_code)
);
-- Multicurrency. -- END --
