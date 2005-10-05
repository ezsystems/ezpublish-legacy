UPDATE ezsite_data SET value='3.6.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER TABLE ezrss_export_item ADD COLUMN subnodes integer;
UPDATE ezrss_export_item SET subnodes='0';
ALTER TABLE ezrss_export_item ALTER subnodes SET NOT NULL;
ALTER TABLE ezrss_export_item ALTER subnodes SET DEFAULT 0;

ALTER TABLE ezrss_export ADD COLUMN number_of_objects integer;
-- Old behaviour of RSS was that it fed 5 items
UPDATE ezrss_export SET number_of_objects='5';
ALTER TABLE ezrss_export ALTER number_of_objects SET NOT NULL;
ALTER TABLE ezrss_export ALTER number_of_objects SET DEFAULT 0;

ALTER TABLE ezrss_export ADD COLUMN main_node_only integer;
-- Old behaviour of RSS was that all nodes have been shown,
-- i.e. including those besides the main node
UPDATE ezrss_export SET main_node_only='1';
ALTER TABLE ezrss_export ALTER main_node_only SET NOT NULL;
ALTER TABLE ezrss_export ALTER main_node_only SET DEFAULT 1;

ALTER TABLE ezcontentobject_link ADD COLUMN contentclassattribute_id INT;
UPDATE ezcontentobject_link SET contentclassattribute_id='0';
ALTER TABLE ezcontentobject_link ALTER COLUMN contentclassattribute_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_link ALTER COLUMN contentclassattribute_id SET NOT NULL;
CREATE INDEX ezco_link_to_co_id ON ezcontentobject_link ( to_contentobject_id );
CREATE INDEX ezco_link_from     ON ezcontentobject_link ( from_contentobject_id,
                                                          from_contentobject_version,
                                                          contentclassattribute_id );



-- Add missing index for orders
CREATE INDEX ezorder_is_tmp ON ezorder USING btree (is_temporary);

ALTER TABLE ezorder ADD status_id integer;
ALTER TABLE ezorder ALTER status_id SET DEFAULT 0;

ALTER TABLE ezorder ADD status_modified integer;
ALTER TABLE ezorder ALTER status_modified SET DEFAULT 0;

ALTER TABLE ezorder ADD status_modifier_id integer;
ALTER TABLE ezorder ALTER status_modifier_id SET DEFAULT 0;


CREATE SEQUENCE ezorder_status_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezorder_status (
    id integer NOT NULL DEFAULT nextval('ezorder_status_s'::text),
    status_id integer NOT NULL DEFAULT 0,
    name varchar(255) NOT NULL DEFAULT '',
    is_active integer NOT NULL DEFAULT 1
);

ALTER TABLE ONLY ezorder_status
    ADD CONSTRAINT ezorder_status_pkey PRIMARY KEY (id);

CREATE INDEX ezorder_status_sid ON ezorder_status USING btree  (status_id);
CREATE INDEX ezorder_status_name ON ezorder_status USING btree (name);
CREATE INDEX ezorder_status_active ON ezorder_status USING btree  (is_active);

INSERT INTO ezorder_status (status_id, name, is_active)
VALUES( 1, 'Pending', 1 );
INSERT INTO ezorder_status (status_id, name, is_active)
VALUES( 2, 'Processing', 1 );
INSERT INTO ezorder_status (status_id, name, is_active)
VALUES( 3, 'Delivered', 1 );

CREATE SEQUENCE ezorder_status_history_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezorder_status_history (
    id integer NOT NULL DEFAULT nextval('ezorder_status_history_s'::text),
    order_id integer NOT NULL DEFAULT 0,
    status_id integer NOT NULL DEFAULT 0,
    modifier_id integer NOT NULL DEFAULT 0,
    modified integer NOT NULL DEFAULT 0
);

ALTER TABLE ONLY ezorder_status_history
    ADD CONSTRAINT ezorder_status_history_pkey PRIMARY KEY (id);

CREATE INDEX ezorder_status_history_oid ON ezorder_status_history USING btree (order_id);
CREATE INDEX ezorder_status_history_sid ON ezorder_status_history USING btree  (status_id);
CREATE INDEX ezorder_status_history_mod ON ezorder_status_history USING btree (modified);


-- Make sure each order has a history element with Pending status
INSERT INTO ezorder_status_history (order_id, status_id, modifier_id, modified)
SELECT order_nr AS order_id, 1 AS status_id, user_id AS modifier_id, created AS modified FROM ezorder WHERE status_id = 0;

-- Update status of all orders to Pending
UPDATE ezorder SET status_id = 1, status_modifier_id = user_id, status_modified = created WHERE status_id = 0;
