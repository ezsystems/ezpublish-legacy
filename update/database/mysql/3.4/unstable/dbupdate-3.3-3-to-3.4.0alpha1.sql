UPDATE ezsite_data SET value='3.4.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- 
-- Update ContentObjectTreeNode to store latest modified subnode value.
--

-- 
-- Update ContentObjectTreeNode to store latest modified subnode value.
--

CREATE TABLE tmp_ezcontentobject_tree (
  node_id int(11) NOT NULL,
  modified_subnode int NOT NULL
);

CREATE INDEX idx_modified_obj on ezcontentobject( modified DESC );
CREATE INDEX idx_pat_string_obj on ezcontentobject_tree( path_string );

INSERT INTO tmp_ezcontentobject_tree ( node_id, modified_subnode )
       SELECT tree.node_id, max( obj.modified )
       FROM ezcontentobject_tree as subtree, ezcontentobject_tree as tree,  ezcontentobject as obj
       WHERE obj.id = subtree.contentobject_id AND
             subtree.path_string like concat( tree.path_string, '%')
       GROUP BY tree.node_id;

CREATE TABLE tmp2_ezcontentobject_tree AS
 SELECT ezcontentobject_tree.*, tmp_ezcontentobject_tree.modified_subnode
  FROM  ezcontentobject_tree, tmp_ezcontentobject_tree
  WHERE ezcontentobject_tree.node_id=tmp_ezcontentobject_tree.node_id;

DELETE FROM ezcontentobject_tree;

ALTER TABLE ezcontentobject_tree ADD COLUMN modified_subnode int NOT NULL default 0, ADD INDEX (modified_subnode);

INSERT INTO ezcontentobject_tree SELECT * FROM tmp2_ezcontentobject_tree;

DROP TABLE tmp2_ezcontentobject_tree;

DROP TABLE tmp_ezcontentobject_tree;

DROP INDEX idx_modified_obj on ezcontentobject;
DROP INDEX idx_pat_string_obj on ezcontentobject_tree;

--
--  Optimization and extending of role system.
--

ALTER TABLE ezuser_role ADD COLUMN limit_identifier varchar(255) default '';
ALTER TABLE ezuser_role ADD COLUMN limit_value varchar(255) default '';

ALTER TABLE ezpolicy DROP COLUMN limitation;

ALTER TABLE ezpolicy_limitation DROP COLUMN role_id;
ALTER TABLE ezpolicy_limitation DROP COLUMN function_name;
ALTER TABLE ezpolicy_limitation DROP COLUMN module_name;

CREATE INDEX ezuser_role_role_id ON ezuser_role ( role_id );

