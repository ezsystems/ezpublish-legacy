-- 
-- Update ContentObjectTreeNode to store latest modified subnode value.
--

ALTER TABLE ezcontentobject_tree ADD COLUMN modified_subnode int NOT NULL default 0, ADD INDEX (modified_subnode);

CREATE TABLE tmp_ezcontentobject_tree (
  node_id int(11) NOT NULL auto_increment,
  parent_node_id int(11) NOT NULL default '0',
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  contentobject_is_published int(11) default NULL,
  depth int(11) NOT NULL default '0',
  path_string varchar(255) NOT NULL default '',
  sort_field int(11) default '1',
  sort_order int(1) default '1',
  priority int(11) NOT NULL default '0',
  path_identification_string text,
  main_node_id int(11) default NULL,
  modified_subnode int NOT NULL default '0',
  PRIMARY KEY  (node_id),
  KEY ezcontentobject_tree_path (path_string),
  KEY ezcontentobject_tree_p_node_id (parent_node_id),
  KEY ezcontentobject_tree_co_id (contentobject_id),
  KEY ezcontentobject_tree_depth (depth),
  KEY ezcontentobject_tree_mo_su (modified_subnode)
) TYPE=MyISAM;

INSERT INTO tmp_ezcontentobject_tree ( node_id, parent_node_id, contentobject_id, contentobject_version, contentobject_is_published, depth, path_string, sort_field, sort_order, priority, path_identification_string, main_node_id, modified_subnode )
       SELECT tree.node_id, tree.parent_node_id, tree.contentobject_id, tree.contentobject_version, tree.contentobject_is_published, tree.depth, tree.path_string, tree.sort_field, tree.sort_order, tree.priority, tree.path_identification_string, tree.main_node_id, max( obj.modified )
       FROM ezcontentobject_tree as subtree, ezcontentobject_tree as tree,  ezcontentobject as obj
       WHERE obj.id = subtree.contentobject_id AND
             subtree.path_string like concat( tree.path_string, '%')
       GROUP BY tree.node_id;

DROP TABLE ezcontentobject_tree;

RENAME TABLE tmp_ezcontentobject_tree TO ezcontentobject_tree;

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

