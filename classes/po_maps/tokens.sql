CREATE TABLE ezprest_token (
    -- with sha1 40 would presumably be enough
    id character varying(200) NOT NULL,
    expirytime integer DEFAULT 0 NOT NULL,
    client_id character varying(200) NOT NULL,
    -- datatype?
    scope character varying(200) DEFAULT NULL
);

ALTER TABLE ONLY ezprest_token ADD CONSTRAINT ezprest_token_pkey PRIMARY KEY ( id );
-- Unsure this is need as of yet.
CREATE INDEX token_client_id ON ezprest_token USING btree ( client_id );
