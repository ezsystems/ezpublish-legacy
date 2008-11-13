UPDATE ezsite_data SET value='4.1.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezworkflow_event ADD COLUMN data_text5 TEXT;

ALTER TABLE ezrss_export ADD COLUMN node_id INT NULL;
ALTER TABLE ezrss_export_item ADD COLUMN category VARCHAR( 255 ) NULL;

-- START: from 4.0.1
CREATE INDEX ezcontent_language_name ON ezcontent_language (name);

CREATE INDEX ezcontentobject_owner ON ezcontentobject (owner_id);

CREATE UNIQUE INDEX ezcontentobject_remote_id ON ezcontentobject (remote_id);
-- END: from 4.0.1

CREATE UNIQUE INDEX ezgeneral_digest_user_settings_address ON ezgeneral_digest_user_settings (address);
DELETE FROM ezgeneral_digest_user_settings WHERE address not in (SELECT email FROM ezuser);

-- START: from 3.10.1
ALTER TABLE ezurlalias_ml ADD COLUMN alias_redirects INT;
ALTER TABLE ezurlalias_ml ALTER COLUMN alias_redirects SET default 1;
ALTER TABLE ezurlalias_ml ALTER COLUMN alias_redirects SET NOT NULL;
-- END: from 3.10.1

ALTER TABLE ezbinaryfile ALTER COLUMN mime_type TYPE character varying(255);

CREATE SEQUENCE ezcontentobject_state_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE SEQUENCE ezcontentobject_state_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezcontentobject_state (
    default_language_id integer DEFAULT 0 NOT NULL,
    group_id integer DEFAULT 0 NOT NULL,
    id integer DEFAULT nextval('ezcontentobject_state_s'::text) NOT NULL,
    identifier character varying(45) DEFAULT ''::character varying NOT NULL,
    language_mask integer DEFAULT 0 NOT NULL,
    priority integer DEFAULT 0 NOT NULL
);

CREATE TABLE ezcontentobject_state_group (
    default_language_id integer DEFAULT 0 NOT NULL,
    id integer DEFAULT nextval('ezcontentobject_state_group_s'::text) NOT NULL,
    identifier character varying(45) DEFAULT ''::character varying NOT NULL,
    language_mask integer DEFAULT 0 NOT NULL
);

CREATE TABLE ezcontentobject_state_group_language (
    contentobject_state_group_id integer DEFAULT 0 NOT NULL,
    description text NOT NULL,
    language_id integer DEFAULT 0 NOT NULL,
    name character varying(45) DEFAULT ''::character varying NOT NULL
);

CREATE TABLE ezcontentobject_state_language (
    contentobject_state_id integer DEFAULT 0 NOT NULL,
    description text NOT NULL,
    language_id integer DEFAULT 0 NOT NULL,
    name character varying(45) DEFAULT ''::character varying NOT NULL
);

CREATE TABLE ezcontentobject_state_link (
    contentobject_id integer DEFAULT 0 NOT NULL,
    contentobject_state_id integer DEFAULT 0 NOT NULL
);

CREATE UNIQUE INDEX ezcontentobject_state_identifier ON ezcontentobject_state USING btree (group_id, identifier);

CREATE UNIQUE INDEX ezcontentobject_state_group_identifier ON ezcontentobject_state_group USING btree (identifier);

ALTER TABLE ONLY ezcontentobject_state
    ADD CONSTRAINT ezcontentobject_state_pkey PRIMARY KEY (id);

ALTER TABLE ONLY ezcontentobject_state_group
    ADD CONSTRAINT ezcontentobject_state_group_pkey PRIMARY KEY (id);

ALTER TABLE ONLY ezcontentobject_state_group_language
    ADD CONSTRAINT ezcontentobject_state_group_language_pkey PRIMARY KEY (language_id, contentobject_state_group_id);

ALTER TABLE ONLY ezcontentobject_state_language
    ADD CONSTRAINT ezcontentobject_state_language_pkey PRIMARY KEY (contentobject_state_id, language_id);

ALTER TABLE ONLY ezcontentobject_state_link
    ADD CONSTRAINT ezcontentobject_state_link_pkey PRIMARY KEY (contentobject_id, contentobject_state_id);
    
CREATE INDEX ezforgot_password_user ON ezforgot_password USING btree (user_id);

