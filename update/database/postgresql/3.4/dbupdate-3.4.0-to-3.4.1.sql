UPDATE ezsite_data SET value='3.4.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='8' WHERE name='ezpublish-release';

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

CREATE SEQUENCE tmp_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

-- Missing index
CREATE INDEX idx_object_version_objver ON ezcontentobject_version ( contentobject_id, version );
