UPDATE ezsite_data SET value='3.10.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

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
