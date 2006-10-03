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
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_co_id (contentobject_id);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_depth (depth);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_p_node_id (parent_node_id);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_path (path_string);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_path_ident (path_identification_string(50));
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_modified_subnode (modified_subnode);
-- END: new table for trash

-- START: ezcontentclass/ezcontentclass_attribute translations
ALTER TABLE ezcontentclass CHANGE name serialized_name_list varchar(255) default NULL;
ALTER TABLE ezcontentclass ADD COLUMN language_mask int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentclass ADD COLUMN initial_language_id int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentclass_attribute CHANGE name serialized_name_list varchar(255) NOT NULL default '';

CREATE TABLE ezcontentclass_name
(
    id int NOT NULL auto_increment,
    contentclass_id int NOT NULL default '0',
    contentclass_version int NOT NULL default '0',
    language_locale varchar(20) NOT NULL default '',
    language_id int NOT NULL default '0',
    name varchar(255) NOT NULL default '',
    PRIMARY KEY (id)
);
-- END: ezcontentclass/ezcontentclass_attribute translations

-- START: eztipafriend_counter, new column and primary key (new fetch function for tipafriend_top_list)
ALTER TABLE eztipafriend_counter ADD COLUMN requested int NOT NULL DEFAULT 0;
ALTER TABLE eztipafriend_counter DROP PRIMARY KEY;
ALTER TABLE eztipafriend_counter ADD PRIMARY KEY (node_id, requested);
-- END: eztipafriend_counter, new column and primary key (new fetch function for tipafriend_top_list)


