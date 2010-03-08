UPDATE ezsite_data SET value='4.3.0beta2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE SEQUENCE ezscheduled_script_s
  START 1
  INCREMENT 1
  MAXVALUE 9223372036854775807
  MINVALUE 1
  CACHE 1;
CREATE TABLE ezscheduled_script (
  command character varying(255) DEFAULT ''::character varying NOT NULL,
  id integer DEFAULT nextval('ezscheduled_script_s'::text) NOT NULL,
  last_report_timestamp integer DEFAULT 0 NOT NULL,
  name character varying(50) DEFAULT ''::character varying NOT NULL,
  process_id integer DEFAULT 0 NOT NULL,
  progress integer DEFAULT 0,
  user_id integer DEFAULT 0 NOT NULL
);
CREATE INDEX ezscheduled_script_timestamp ON ezscheduled_script USING btree ( last_report_timestamp );

ALTER TABLE ONLY ezscheduled_script ADD CONSTRAINT ezscheduled_script_pkey PRIMARY KEY ( id );
