UPDATE ezsite_data SET value='3.8.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder ADD is_archived INT DEFAULT '0' NOT NULL;
ALTER TABLE ezorder ADD INDEX ezorder_is_archived (is_archived);


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
  code varchar(4) NOT NULL default '',
  symbol varchar(255) NOT NULL default '',
  locale varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '1',
  auto_rate_value numeric(10,5) NOT NULL default '0.00000',
  custom_rate_value numeric(10,5) NOT NULL default '0.00000',
  rate_factor numeric(10,5) NOT NULL default '1.00000',
  PRIMARY KEY (id)
);

ALTER TABLE ezcurrencydata ADD INDEX ezcurrencydata_code (code);


CREATE TABLE ezmultipricedata (
  id int(11) NOT NULL auto_increment,
  contentobject_attr_id int(11) NOT NULL default '0',
  contentobject_attr_version int(11) NOT NULL default '0',
  currency_code varchar(4) NOT NULL default '',
  value numeric(15,2) NOT NULL default '0.00',
  type int(11) NOT NULL default '0',
  PRIMARY KEY (id)
);

ALTER TABLE ezmultipricedata ADD INDEX ezmultipricedata_coa_id (contentobject_attr_id);
ALTER TABLE ezmultipricedata ADD INDEX ezmultipricedata_coa_version (contentobject_attr_version);
ALTER TABLE ezmultipricedata ADD INDEX ezmultipricedata_currency_code (currency_code);

ALTER TABLE ezproductcollection ADD COLUMN currency_code varchar(4) NOT NULL default '';
-- Multicurrency. -- END --

-- Improved packages system -- START --
CREATE TABLE `ezpackage` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `version` varchar(30) NOT NULL default '0',
  `install_date` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);
-- Improved packages system -- END --