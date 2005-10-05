UPDATE ezsite_data SET value='3.5.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- We allow users from the "Editors" group 
-- access only to "Root Folder" and "Media" trees.
-- If you want to fix this you need to figure out the ids of these roles and modify
-- the following SQLs
--
-- DELETE FROM ezuser_role WHERE id=30 AND role_id=3;
-- INSERT INTO ezuser_role
--        (role_id, contentobject_id, limit_identifier,limit_value)
--        VALUES (3,13,'Subtree','/1/2/');
-- INSERT INTO ezuser_role
--        (role_id, contentobject_id, limit_identifier,limit_value)
--        VALUES (3,13,'Subtree','/1/43/');

-- This is present in 3.4.2, uncomment and run this if
-- this is missing from your DB
-- CREATE SEQUENCE tmp_notification_rule_s
--     START 1
--     INCREMENT 1
--     MAXVALUE 9223372036854775807
--     MINVALUE 1
--     CACHE 1;

-- the support of redirect payment gateways
-- create table for eZPaymentObjects
CREATE SEQUENCE ezpaymentobject_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezpaymentobject(
    id integer DEFAULT nextval('ezpaymentobject_s'::text) NOT NULL PRIMARY KEY,
    workflowprocess_id integer DEFAULT 0 NOT NULL,
    order_id integer DEFAULT 0 NOT NULL,
    payment_string character varying(255) DEFAULT ''::character varying NOT NULL,
    status integer DEFAULT 0 NOT NULL
    );

ALTER TABLE ezbasket ADD COLUMN order_id integer;
UPDATE ezbasket SET order_id=0;
ALTER TABLE ezbasket ALTER order_id SET NOT NULL;
ALTER TABLE ezbasket ALTER order_id SET DEFAULT 0;

ALTER TABLE ezbinaryfile ADD COLUMN download_count integer;
UPDATE ezbinaryfile SET download_count='0';
ALTER TABLE ezbinaryfile ALTER download_count SET NOT NULL;
ALTER TABLE ezbinaryfile ALTER download_count SET DEFAULT 0;

ALTER TABLE ezcontentclass ADD is_container integer;
UPDATE ezcontentclass SET is_container=0;
ALTER TABLE ezcontentclass ALTER is_container SET NOT NULL;
ALTER TABLE ezcontentclass ALTER is_container SET DEFAULT 0;

-- New table for storing the users last visit

CREATE TABLE ezuservisit
(
    user_id                 INT NOT NULL PRIMARY KEY,
    current_visit_timestamp INT NOT NULL,
    last_visit_timestamp    INT NOT NULL
);

-- New columns for the hiding functionality
ALTER TABLE ezcontentobject_tree ADD   is_hidden INTEGER;
UPDATE      ezcontentobject_tree SET   is_hidden = 0;
ALTER TABLE ezcontentobject_tree ALTER is_hidden SET NOT NULL;
ALTER TABLE ezcontentobject_tree ALTER is_hidden SET DEFAULT 0;
ALTER TABLE ezcontentobject_tree ADD   is_invisible INTEGER;
UPDATE      ezcontentobject_tree SET   is_invisible = 0;
ALTER TABLE ezcontentobject_tree ALTER is_invisible SET NOT NULL;
ALTER TABLE ezcontentobject_tree ALTER is_invisible SET DEFAULT 0;

