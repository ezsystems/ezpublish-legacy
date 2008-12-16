UPDATE ezsite_data SET value='4.0.2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder_item ALTER COLUMN vat_value TYPE double precision;
ALTER TABLE ezorder_item ALTER COLUMN vat_value SET DEFAULT 0;
ALTER TABLE ezorder_item ALTER COLUMN vat_value SET NOT NULL;

CREATE SEQUENCE ezurlalias_ml_incr_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;
    
CREATE TABLE ezurlalias_ml_incr (
    id integer DEFAULT nextval('ezurlalias_ml_incr_s'::text) NOT NULL
);

ALTER TABLE ONLY ezurlalias_ml_incr
    ADD CONSTRAINT ezurlalias_ml_incr_pkey PRIMARY KEY (id);

