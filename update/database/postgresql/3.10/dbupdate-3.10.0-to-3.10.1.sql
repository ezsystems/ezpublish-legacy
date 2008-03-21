UPDATE ezsite_data SET value='3.10.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';

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

-- START: from 3.9.5
CREATE INDEX ezcontent_language_name ON ezcontent_language (name);

CREATE INDEX ezcontentobject_owner ON ezcontentobject (owner_id);

CREATE UNIQUE INDEX ezcontentobject_remote_id ON ezcontentobject (remote_id);
-- END: from 3.9.5

