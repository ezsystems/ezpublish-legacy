ALTER TABLE ezcontentobject_tree ADD COLUMN modified_subnode INT;
ALTER TABLE ezcontentobject_tree ALTER modified_subnode SET DEFAULT 0;

CREATE INDEX ezcontentobject_tree_mod_sub ON ezcontentobject_tree( modified_subnode );

UPDATE ezcontentobject_tree SET modified_subnode=(SELECT max( ezcontentobject.modified )
       FROM ezcontentobject, ezcontentobject_tree as tree
       WHERE ezcontentobject.id = tree.contentobject_id AND
             tree.path_string like ezcontentobject_tree.path_string||'%');

ALTER TABLE ezuser_role ADD COLUMN limit_identifier varchar(255) default '';
ALTER TABLE ezuser_role ADD COLUMN limit_value varchar(255) default '';

ALTER TABLE ezpolicy DROP COLUMN limitation;

ALTER TABLE ezpolicy_limitation DROP COLUMN role_id;
ALTER TABLE ezpolicy_limitation DROP COLUMN function_name;
ALTER TABLE ezpolicy_limitation DROP COLUMN module_name;
