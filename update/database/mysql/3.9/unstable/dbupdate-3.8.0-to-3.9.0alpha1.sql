UPDATE ezsite_data SET value='3.9.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE INDEX ezkeyword_keyword_id ON ezkeyword ( keyword, id );
CREATE INDEX ezkeyword_attr_link_kid_oaid ON ezkeyword_attribute_link ( keyword_id, objectattribute_id );

CREATE INDEX ezurlalias_is_wildcard ON ezurlalias( is_wildcard );

CREATE INDEX eznode_assignment_coid_cov ON eznode_assignment( contentobject_id,contentobject_version );
CREATE INDEX eznode_assignment_is_main ON eznode_assignment( is_main );
CREATE INDEX eznode_assignment_parent_node ON eznode_assignment( parent_node );

ALTER TABLE ezuservisit ADD COLUMN failed_login_attempts int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_link ADD COLUMN relation_type int NOT NULL DEFAULT 1;


-- START: 'default sorting' attribute for ezcontentclass

ALTER TABLE ezcontentclass ADD COLUMN sort_field int NOT NULL DEFAULT 1;
ALTER TABLE ezcontentclass ADD COLUMN sort_order int NOT NULL DEFAULT 1;

-- END: 'default sorting' attribute for ezcontentclass

-- START: new table for trash
CREATE TABLE ezcontentobject_trash (
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  depth int(11) NOT NULL default '0',
  is_hidden int(11) NOT NULL default '0',
  is_invisible int(11) NOT NULL default '0',
  main_node_id int(11) default NULL,
  modified_subnode int(11) default '0',
  node_id int(11) NOT NULL,
  parent_node_id int(11) NOT NULL default '0',
  path_identification_string longtext,
  path_string varchar(255) NOT NULL default '',
  priority int(11) NOT NULL default '0',
  remote_id varchar(100) NOT NULL default '',
  sort_field int(11) default '1',
  sort_order int(11) default '1',
  PRIMARY KEY  (node_id)
);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcontentobject_trash_co_id (contentobject_id),
ALTER TABLE ezcontentobject_trash ADD INDEX ezcontentobject_trash_depth (depth);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcontentobject_trash_p_node_id (parent_node_id);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcontentobject_trash_path (path_string);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcontentobject_trash_path_ident (path_identification_string(50));
ALTER TABLE ezcontentobject_trash ADD INDEX ezcontentobject_trash_modified_subnode (modified_subnode);
-- END: new table for trash

