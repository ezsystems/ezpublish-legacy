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
