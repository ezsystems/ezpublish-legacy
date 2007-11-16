UPDATE ezsite_data SET value='4.0.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

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
