UPDATE ezsite_data SET value='3.5.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- We allow users from the "Editors" group 
-- access only to "Root Folder" and "Media" trees.
DELETE FROM ezuser_role WHERE id=30 AND role_id=3;  
INSERT INTO ezuser_role
       (role_id, contentobject_id, limit_identifier,limit_value)
       VALUES (3,13,'Subtree','/1/2/');
INSERT INTO ezuser_role
       (role_id, contentobject_id, limit_identifier,limit_value)
       VALUES (3,13,'Subtree','/1/43/');
