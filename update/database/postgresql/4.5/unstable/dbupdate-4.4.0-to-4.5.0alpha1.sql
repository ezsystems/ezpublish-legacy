UPDATE ezsite_data SET value='4.5.0alpha1' WHERE name='ezpublish-version';
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
