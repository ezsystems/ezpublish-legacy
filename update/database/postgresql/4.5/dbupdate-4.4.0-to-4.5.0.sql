UPDATE ezsite_data SET value='4.5.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE ezpublishingqueueprocesses (
    created integer,
    ezcontentobject_version_id integer DEFAULT 0 NOT NULL,
    finished integer,
    pid integer,
    started integer,
    status integer
);

ALTER TABLE ONLY ezpublishingqueueprocesses
    ADD CONSTRAINT ezpublishingqueueprocesses_pkey PRIMARY KEY (ezcontentobject_version_id);

CREATE TABLE ezprest_token (
    -- with sha1 40 would presumably be enough
    id character varying(200) NOT NULL,
    refresh_token character varying(200) NOT NULL,
    expirytime bigint DEFAULT 0 NOT NULL,
    client_id character varying(200) NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    -- datatype?
    scope character varying(200) DEFAULT NULL
);

ALTER TABLE ONLY ezprest_token ADD CONSTRAINT ezprest_token_pkey PRIMARY KEY ( id );
CREATE INDEX token_client_id ON ezprest_token USING btree ( client_id );

CREATE TABLE ezprest_authcode (
    -- with sha1 40 would presumably be enough
    id character varying(200) NOT NULL,
    expirytime bigint DEFAULT 0 NOT NULL,
    client_id character varying(200) NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    -- datatype?
    scope character varying(200) DEFAULT NULL
);

ALTER TABLE ONLY ezprest_authcode ADD CONSTRAINT ezprest_authcode_pkey PRIMARY KEY ( id );
CREATE INDEX authcode_client_id ON ezprest_authcode USING btree ( client_id );

CREATE SEQUENCE ezprest_clients_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezprest_clients (
    id integer DEFAULT nextval('ezprest_clients_s'::text) NOT NULL,
    name character varying(100) DEFAULT NULL,
    description text,
    client_id character varying(200) DEFAULT NULL,
    client_secret character varying(200) DEFAULT NULL,
    endpoint_uri character varying(200) DEFAULT NULL,
    owner_id integer DEFAULT 0 NOT NULL,
    created integer DEFAULT 0 NOT NULL,
    updated integer DEFAULT 0 NOT NULL,
    "version" integer DEFAULT 0 NOT NULL
);

ALTER TABLE ONLY ezprest_clients ADD CONSTRAINT ezprest_clients_pkey PRIMARY KEY ( id );
CREATE INDEX client_id ON ezprest_clients USING btree ( client_id );
CREATE UNIQUE INDEX client_id_UNIQUE ON ezprest_clients USING btree ( client_id, "version" );

CREATE SEQUENCE ezprest_authorized_clients_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezprest_authorized_clients (
    created integer,
    id integer DEFAULT nextval('ezprest_authorized_clients_s'::text) NOT NULL,
    rest_client_id integer,
    user_id integer
);

CREATE INDEX client_user ON ezprest_authorized_clients USING btree (rest_client_id, user_id);
ALTER TABLE ONLY ezprest_authorized_clients ADD CONSTRAINT ezprest_authorized_clients_pkey PRIMARY KEY (id);
