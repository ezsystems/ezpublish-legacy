UPDATE ezsite_data SET value='4.6.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE SEQUENCE ezorder_nr_incr_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezorder_nr_incr (
    id integer DEFAULT nextval('ezorder_nr_incr_s'::text) NOT NULL
);

ALTER TABLE ONLY ezorder_nr_incr
    ADD CONSTRAINT ezorder_nr_incr_pkey PRIMARY KEY (id);

