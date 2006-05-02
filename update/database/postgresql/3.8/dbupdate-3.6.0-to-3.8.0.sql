UPDATE ezsite_data SET value='3.8.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

ALTER TABLE ezorder ADD is_archived INT;
UPDATE ezorder SET is_archived='0';
ALTER TABLE ezorder ALTER COLUMN is_archived SET DEFAULT 0;
ALTER TABLE ezorder ALTER COLUMN is_archived SET NOT NULL;
ALTER TABLE ezorder_item ADD type VARCHAR(30);

CREATE INDEX ezorder_is_archived ON ezorder USING btree (is_archived);
CREATE INDEX ezorder_item_type ON ezorder_item USING btree (type);


-- Improved Approval Workflow -- START --
UPDATE ezworkflow_event set data_text3=data_int1;
-- Improved Approval Workflow --  END  --

UPDATE ezpolicy SET function_name='administrate' WHERE module_name='shop' AND function_name='adminstrate';


-- Improved RSS import. -- START --
ALTER TABLE ezrss_import ADD COLUMN import_description text;
ALTER TABLE ezrss_import ALTER import_description SET NOT NULL;
ALTER TABLE ezrss_import ALTER import_description SET DEFAULT '';
-- Improved RSS import. -- END --

-- Multicurrency. -- START --
CREATE SEQUENCE ezcurrencydata_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


CREATE TABLE ezcurrencydata (
  id integer NOT NULL DEFAULT nextval('ezcurrencydata_s'::text),
  code varchar(4) NOT NULL default '',
  symbol varchar(255) NOT NULL default '',
  locale varchar(255) NOT NULL default '',
  status integer NOT NULL default 1,
  auto_rate_value numeric(10,5) NOT NULL default '0.00000',
  custom_rate_value numeric(10,5) NOT NULL default '0.00000',
  rate_factor numeric(10,5) NOT NULL default '1.00000'
);

ALTER TABLE ONLY ezcurrencydata
    ADD CONSTRAINT ezcurrencydata_pkey PRIMARY KEY (id);

CREATE INDEX ezcurrencydata_code ON ezcurrencydata USING btree (code);


CREATE SEQUENCE ezmultipricedata_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezmultipricedata (
  id integer NOT NULL DEFAULT nextval('ezmultipricedata_s'::text),
  contentobject_attr_id integer NOT NULL default 0,
  contentobject_attr_version integer NOT NULL default 0,
  currency_code varchar(4) NOT NULL default '',
  value numeric(15,2) NOT NULL default '0.00',
  type integer NOT NULL default 0
);

ALTER TABLE ONLY ezmultipricedata
    ADD CONSTRAINT ezmultipricedata_pkey PRIMARY KEY (id);

CREATE INDEX ezmultipricedata_coa_id ON ezmultipricedata USING btree (contentobject_attr_id);
CREATE INDEX ezmultipricedata_coa_version ON ezmultipricedata USING btree (contentobject_attr_version);
CREATE INDEX ezmultipricedata_currency_code ON ezmultipricedata USING btree (currency_code);


ALTER TABLE ezproductcollection ADD currency_code varchar(4);
ALTER TABLE ezproductcollection ALTER currency_code SET NOT NULL;
ALTER TABLE ezproductcollection ALTER currency_code SET DEFAULT '';
-- Multicurrency. -- END --

-- Improved packages system -- START --
CREATE SEQUENCE ezpackage_s
       START 1
       INCREMENT 1
       MAXVALUE 9223372036854775807
       MINVALUE 1
       CACHE 1;

CREATE TABLE ezpackage (
  id integer NOT NULL DEFAULT nextval('ezpackage_s'::text),
  name varchar(100) NOT NULL default '',
  version varchar(30) NOT NULL default '0',
  install_date integer NOT NULL,
  PRIMARY KEY  (id)
);
-- Improved packages system -- END --

-- VAT charging rules -- START --
CREATE SEQUENCE ezproductcategory_s;
CREATE TABLE ezproductcategory (
  id INTEGER NOT NULL DEFAULT nextval('ezproductcategory_s'),
  name VARCHAR(255) NOT NULL default '',
  PRIMARY KEY (id)
);

CREATE SEQUENCE ezvatrule_s;
CREATE TABLE ezvatrule (
  id INTEGER NOT NULL DEFAULT nextval('ezvatrule_s'),
  country VARCHAR(255) NOT NULL default '',
  vat_type INTEGER NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ezvatrule_product_category (
  vatrule_id INTEGER NOT NULL,
  product_category_id INTEGER NOT NULL
);

ALTER TABLE ONLY ezvatrule_product_category
    ADD CONSTRAINT ezvatrule_product_category_pkey PRIMARY KEY (vatrule_id, product_category_id);
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
                 
ALTER TABLE ezcontentobject ADD COLUMN language_mask int; 
ALTER TABLE ezcontentobject ALTER COLUMN language_mask SET DEFAULT 0;
ALTER TABLE ezcontentobject ALTER COLUMN language_mask SET NOT NULL;

ALTER TABLE ezcontentobject ADD COLUMN initial_language_id int;
ALTER TABLE ezcontentobject ALTER COLUMN initial_language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject ALTER COLUMN initial_language_id SET NOT NULL;

ALTER TABLE ezcontentobject_name ADD COLUMN language_id int;
ALTER TABLE ezcontentobject_name ALTER COLUMN language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_name ALTER COLUMN language_id SET NOT NULL;

ALTER TABLE ezcontentobject_attribute ADD COLUMN language_id int;
ALTER TABLE ezcontentobject_attribute ALTER COLUMN language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_attribute ALTER COLUMN language_id SET NOT NULL;

ALTER TABLE ezcontentobject_version ADD COLUMN language_mask int;
ALTER TABLE ezcontentobject_version ALTER COLUMN language_mask SET DEFAULT 0;
ALTER TABLE ezcontentobject_version ALTER COLUMN language_mask SET NOT NULL;

ALTER TABLE ezcontentobject_version ADD COLUMN initial_language_id int;
ALTER TABLE ezcontentobject_version ALTER COLUMN initial_language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_version ALTER COLUMN initial_language_id SET NOT NULL;

ALTER TABLE ezcontentclass ADD COLUMN always_available int;
ALTER TABLE ezcontentclass ALTER COLUMN always_available SET DEFAULT 0;
ALTER TABLE ezcontentclass ALTER COLUMN always_available SET NOT NULL;

ALTER TABLE ezcontentobject_link ADD COLUMN op_code int;
ALTER TABLE ezcontentobject_link ALTER COLUMN op_code SET DEFAULT 0;
ALTER TABLE ezcontentobject_link ALTER COLUMN op_code SET NOT NULL;

ALTER TABLE eznode_assignment ADD COLUMN op_code int;
ALTER TABLE eznode_assignment ALTER COLUMN op_code SET DEFAULT 0;
ALTER TABLE eznode_assignment ALTER COLUMN op_code SET NOT NULL;

-- updates
-- set correct op_code
-- mark as being moved
update eznode_assignment set op_code=4 where from_node_id > 0 and op_code=0;
-- mark as being created
update eznode_assignment set op_code=2 where from_node_id <= 0 and op_code=0;
-- mark as being set
update eznode_assignment set op_code=2 where remote_id != 0 and op_code=0;

CREATE INDEX ezcontentobject_lmask ON ezcontentobject USING btree ( language_mask );

-- Now remember to run ./update/common/scripts/updatemultilingual.php before using the site

-- Information collection improvments
ALTER TABLE ezinfocollection ADD creator_id INT;
ALTER TABLE ezinfocollection ALTER COLUMN creator_id SET DEFAULT 0;
ALTER TABLE ezinfocollection ALTER COLUMN creator_id SET NOT NULL;
