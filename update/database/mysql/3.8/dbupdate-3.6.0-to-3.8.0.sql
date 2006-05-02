UPDATE ezsite_data SET value='3.8.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

ALTER TABLE ezorder ADD is_archived INT DEFAULT '0' NOT NULL;
ALTER TABLE ezorder ADD INDEX ezorder_is_archived (is_archived);
ALTER TABLE ezorder_item ADD type VARCHAR(30);
ALTER TABLE ezorder_item ADD INDEX ezorder_item_type ( type ) ;


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

-- VAT charging rules -- START --
CREATE TABLE ezproductcategory (
  id int(11) NOT NULL auto_increment,
  name varchar(255) NOT NULL default '',
  PRIMARY KEY (id)
);

CREATE TABLE ezvatrule (
  id int(11) NOT NULL auto_increment,
  country varchar(255) NOT NULL default '',
  vat_type int(11) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ezvatrule_product_category (
  vatrule_id int(11) NOT NULL,
  product_category_id int(11) NOT NULL,
  PRIMARY KEY (vatrule_id, product_category_id)
);
-- VAT charging rules -- END --

-- Multilanguage fixes

CREATE TABLE ezcontent_language
(
    id int NOT NULL default '0',
    disabled int NOT NULL default '0',
    locale varchar(20) NOT NULL default '',
    name varchar(255) NOT NULL default '',
    PRIMARY KEY (id)
);
                    
DROP TABLE ezcontent_translation;
                 
ALTER TABLE ezcontentobject ADD COLUMN language_mask int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentobject ADD COLUMN initial_language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_name ADD COLUMN language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_attribute ADD COLUMN language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_version ADD COLUMN language_mask int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentobject_version ADD COLUMN initial_language_id int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentclass ADD COLUMN always_available int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_link ADD COLUMN op_code int NOT NULL DEFAULT 0;

ALTER TABLE eznode_assignment ADD COLUMN op_code int NOT NULL DEFAULT 0;

-- updates
-- set correct op_code
-- mark as being moved
update eznode_assignment set op_code=4 where from_node_id > 0 and op_code=0;
-- mark as being created
update eznode_assignment set op_code=2 where from_node_id <= 0 and op_code=0;
-- mark as being set
update eznode_assignment set op_code=2 where remote_id != 0 and op_code=0;

ALTER TABLE ezcontentobject ADD INDEX ezcontentobject_lmask ( language_mask ) ;

-- Now remember to run ./update/common/scripts/updatemultilingual.php before using the site

-- Information collection improvments
ALTER TABLE ezinfocollection ADD COLUMN creator_id int NOT NULL DEFAULT 0;
