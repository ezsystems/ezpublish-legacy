UPDATE ezsite_data SET value='3.10.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='4' WHERE name='ezpublish-release';

-- Enhanced ISBN datatype.
CREATE TABLE ezisbn_group (
  id int(11) NOT NULL auto_increment,
  description varchar(255) NOT NULL default '',
  group_number int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

CREATE TABLE ezisbn_group_range (
  id int(11) NOT NULL auto_increment,
  from_number int(11) NOT NULL default '0',
  to_number int(11) NOT NULL default '0',
  group_from varchar(32) NOT NULL default '',
  group_to varchar(32) NOT NULL default '',
  group_length int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

CREATE TABLE ezisbn_registrant_range (
  id int(11) NOT NULL auto_increment,
  from_number int(11) NOT NULL default '0',
  to_number int(11) NOT NULL default '0',
  registrant_from varchar(32) NOT NULL default '',
  registrant_to varchar(32) NOT NULL default '',
  registrant_length int(11) NOT NULL default '0',
  isbn_group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);

-- URL alias name pattern
ALTER TABLE ezcontentclass ADD COLUMN url_alias_name VARCHAR(255) AFTER contentobject_name;

-- URL alias
CREATE TABLE ezurlalias_ml (
  id        integer NOT NULL DEFAULT 0,
  link      integer NOT NULL DEFAULT 0,
  parent    integer NOT NULL DEFAULT 0,
  lang_mask integer NOT NULL DEFAULT 0,
  text      longtext NOT NULL,
  text_md5  varchar(32) NOT NULL DEFAULT '',
  action    longtext NOT NULL,
  action_type varchar(32) NOT NULL DEFAULT '',
  is_original integer NOT NULL DEFAULT 0,
  is_alias    integer NOT NULL DEFAULT 0,
  PRIMARY KEY(parent, text_md5),
  KEY ezurlalias_ml_text_lang (text(32), lang_mask, parent),
  KEY ezurlalias_ml_text (text(32), id, link),
  KEY ezurlalias_ml_action (action(32), id, link),
  KEY ezurlalias_ml_par_txt (parent, text(32)),
  KEY ezurlalias_ml_par_lnk_txt (parent, link, text(32)),
  KEY ezurlalias_ml_par_act_id_lnk (parent, action(32), id, link),
  KEY ezurlalias_ml_id (id),
  KEY ezurlalias_ml_act_org (action(32),is_original),
  KEY ezurlalias_ml_actt_org_al (action_type, is_original, is_alias),
  KEY ezurlalias_ml_actt (action_type)
  );

-- Update old urlalias table for the import
ALTER TABLE ezurlalias ADD COLUMN is_imported integer NOT NULL DEFAULT 0;
ALTER TABLE ezurlalias ADD KEY ezurlalias_imp_wcard_fwd (is_imported, is_wildcard, forward_to_id);
ALTER TABLE ezurlalias ADD KEY ezurlalias_wcard_fwd (is_wildcard, forward_to_id);
ALTER TABLE ezurlalias DROP KEY ezurlalias_is_wildcard;

-- START: from 3.9.1
-- extend length of 'serialized_name_list'
ALTER TABLE ezcontentclass CHANGE COLUMN serialized_name_list serialized_name_list longtext default NULL;
ALTER TABLE ezcontentclass_attribute CHANGE COLUMN serialized_name_list serialized_name_list longtext NOT NULL;
-- END: from 3.9.1

-- START: from 3.9.3
ALTER TABLE ezvatrule CHANGE country country_code varchar(255) DEFAULT '' NOT NULL;
-- END: from 3.9.3

-- START: from 3.9.4
ALTER table ezsearch_word ADD KEY ezsearch_word_obj_count(object_count);

DROP INDEX ezurl_url ON ezurl;
ALTER TABLE ezurl MODIFY url longtext;
ALTER table ezurl ADD KEY ezurl_url( url(255) );
-- END: from 3.9.4

