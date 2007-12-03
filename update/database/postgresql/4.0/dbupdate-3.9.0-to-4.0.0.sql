UPDATE ezsite_data SET value='4.0.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';


-- Add new tables for the isbn datatype. -- START --

CREATE SEQUENCE ezisbn_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezisbn_group (
  id integer DEFAULT nextval('ezisbn_group_s'::text) NOT NULL,
  description character varying(255) default ''::character varying NOT NULL,
  group_number integer DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY ezisbn_group
    ADD CONSTRAINT ezisbn_group_pkey PRIMARY KEY (id);

CREATE SEQUENCE ezisbn_group_range_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezisbn_group_range (
  id integer DEFAULT nextval('ezisbn_group_range_s'::text) NOT NULL,
  from_number  integer DEFAULT 0 NOT NULL,
  to_number  integer DEFAULT 0 NOT NULL,
  group_from  character varying(32) default ''::character varying NOT NULL,
  group_to character varying(32) default ''::character varying NOT NULL,
  group_length integer DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY ezisbn_group_range
    ADD CONSTRAINT ezisbn_group_range_pkey PRIMARY KEY (id);

CREATE SEQUENCE ezisbn_registrant_range_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezisbn_registrant_range (
  id integer DEFAULT nextval('ezisbn_registrant_range_s'::text) NOT NULL,
  from_number integer DEFAULT 0 NOT NULL,
  to_number integer DEFAULT 0 NOT NULL,
  registrant_from character varying(32) default ''::character varying NOT NULL,
  registrant_to character varying(32) default ''::character varying NOT NULL,
  registrant_length integer DEFAULT 0 NOT NULL,
  isbn_group_id integer DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY ezisbn_registrant_range
    ADD CONSTRAINT ezisbn_registrant_range_pkey PRIMARY KEY (id);

-- Add new tables for the isbn datatype. -- END --

-- URL alias name pattern
ALTER TABLE ezcontentclass ADD COLUMN url_alias_name VARCHAR(255);

-- URL alias
CREATE TABLE ezurlalias_ml (
  id        integer NOT NULL DEFAULT 0,
  link      integer NOT NULL DEFAULT 0,
  parent    integer NOT NULL DEFAULT 0,
  lang_mask integer NOT NULL DEFAULT 0,
  text      text NOT NULL DEFAULT '',
  text_md5  varchar(32) NOT NULL DEFAULT '',
  action    text NOT NULL DEFAULT '',
  action_type varchar(32) NOT NULL DEFAULT '',
  is_original integer NOT NULL DEFAULT 0,
  is_alias    integer NOT NULL DEFAULT 0
  );

ALTER TABLE ONLY ezurlalias_ml
    ADD CONSTRAINT ezurlalias_ml_pkey PRIMARY KEY (parent, text_md5);
CREATE INDEX ezurlalias_ml_text_lang ON ezurlalias_ml USING btree (text, lang_mask, parent);
CREATE INDEX ezurlalias_ml_text ON ezurlalias_ml USING btree (text, id, link);
CREATE INDEX ezurlalias_ml_action ON ezurlalias_ml USING btree (action, id, link);
CREATE INDEX ezurlalias_ml_par_txt ON ezurlalias_ml USING btree (parent, text);
CREATE INDEX ezurlalias_ml_par_lnk_txt ON ezurlalias_ml USING btree (parent, link, text);
CREATE INDEX ezurlalias_ml_par_act_id_lnk ON ezurlalias_ml USING btree (parent, action, id, link);
CREATE INDEX ezurlalias_ml_id ON ezurlalias_ml USING btree (id);
CREATE INDEX ezurlalias_ml_act_org ON ezurlalias_ml USING btree (action,is_original);
CREATE INDEX ezurlalias_ml_actt_org_al ON ezurlalias_ml USING btree (action_type, is_original, is_alias);
CREATE INDEX ezurlalias_ml_actt ON ezurlalias_ml USING btree (action_type);

-- Update old urlalias table for the import
ALTER TABLE ezurlalias ADD COLUMN is_imported integer;
ALTER TABLE ezurlalias ALTER COLUMN is_imported SET DEFAULT 0;
ALTER TABLE ezurlalias ALTER COLUMN is_imported SET NOT NULL;
CREATE INDEX ezurlalias_imp_wcard_fwd ON ezurlalias USING btree (is_imported, is_wildcard, forward_to_id);
CREATE INDEX ezurlalias_wcard_fwd ON ezurlalias USING btree (is_wildcard, forward_to_id);
DROP INDEX ezurlalias_is_wildcard;

DELETE FROM ezuser_setting where user_id not in (SELECT contentobject_id FROM ezuser);

DELETE FROM ezcontentclass_classgroup WHERE NOT EXISTS (SELECT * FROM ezcontentclass c WHERE c.id=contentclass_id AND c.version=contentclass_version);

CREATE SEQUENCE ezurlwildcard_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezurlwildcard (
  id integer DEFAULT nextval('ezurlwildcard_s'::text) NOT NULL,
  source_url text NOT NULL,
  destination_url text NOT NULL,
  type integer DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY ezurlwildcard
    ADD CONSTRAINT ezurlwildcard_pkey PRIMARY KEY (id);

-- START: from 3.9.1
-- extend length of 'serialized_name_list'
ALTER TABLE ezcontentclass RENAME COLUMN serialized_name_list TO name_tmp;
ALTER TABLE ezcontentclass ADD COLUMN serialized_name_list text;
UPDATE ezcontentclass SET serialized_name_list = name_tmp;
ALTER TABLE ezcontentclass DROP COLUMN name_tmp;

ALTER TABLE ezcontentclass_attribute RENAME COLUMN serialized_name_list TO name_tmp;
ALTER TABLE ezcontentclass_attribute ADD COLUMN serialized_name_list text;
UPDATE ezcontentclass_attribute SET serialized_name_list = name_tmp;
ALTER TABLE ezcontentclass_attribute ALTER serialized_name_list SET NOT NULL;
ALTER TABLE ezcontentclass_attribute DROP COLUMN name_tmp;
-- END: from 3.9.1

-- START: from 3.9.3
ALTER TABLE ezvatrule RENAME country TO country_code;
-- END: from 3.9.3

-- START: from 3.9.4
CREATE INDEX  ezsearch_word_obj_count ON ezsearch_word(object_count);

DROP INDEX ezurl_url;
ALTER TABLE ezurl ALTER url TYPE text;
CREATE INDEX ezurl_url ON ezurl(url);
-- END: from 3.9.4
