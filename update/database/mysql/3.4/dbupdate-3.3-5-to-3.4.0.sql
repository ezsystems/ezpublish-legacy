UPDATE ezsite_data SET value='3.4.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='7' WHERE name='ezpublish-release';

-- NOTE: This file will be generated from all the development releases, do not add entries

-- 3.3.3 to 3.4.0alpha1

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

-- 3.4.0alpha1 to 3.4.0alpha2


---
--- Add session ID to user object
---

CREATE TABLE ezuser_session_link (
  user_id int(11) NOT NULL,
  session_key varchar(32) NOT NULL,
  PRIMARY KEY( user_id, session_key )
);
CREATE INDEX ezuser_session_link_user_idx on ezuser_session_link ( user_id );
CREATE INDEX ezuser_session_link_session_idx on ezuser_session_link ( session_key );


CREATE INDEX ezpreferences_user_id_idx on ezpreferences ( user_id, name );


ALTER  TABLE ezorder ADD COLUMN email varchar(150) NOT NULL default '';
--- 
--- Run script update/common/scripts/addorderemail.php after this change to
--- insert correct email for existing orders.
---


CREATE TABLE ezsubtree_expiry (
  subtree varchar(255) NOT NULL default '',
  cache_file varchar(255) NOT NULL default '',
  KEY ezsubtree_expiry_subtree (subtree)
) DELAY_KEY_WRITE=1 TYPE=MyISAM;


CREATE TABLE ezpending_actions (
  action varchar(64) NOT NULL,
  param text,
  KEY ezpending_actions_action (action)
) TYPE=MyISAM;


-- Change text fields to longtext
ALTER TABLE ezcollab_item CHANGE COLUMN data_text1 data_text1 longtext NOT NULL ;
ALTER TABLE ezcollab_item CHANGE COLUMN data_text2 data_text2 longtext NOT NULL ;
ALTER TABLE ezcollab_item CHANGE COLUMN data_text3 data_text3 longtext NOT NULL ;
ALTER TABLE ezcollab_profile CHANGE COLUMN data_text1 data_text1 longtext NOT NULL ;
ALTER TABLE ezcollab_simple_message CHANGE COLUMN data_text1 data_text1 longtext NOT NULL ;
ALTER TABLE ezcollab_simple_message CHANGE COLUMN data_text2 data_text2 longtext NOT NULL ;
ALTER TABLE ezcollab_simple_message CHANGE COLUMN data_text3 data_text3 longtext NOT NULL ;
ALTER TABLE ezcontentclass_attribute CHANGE COLUMN data_text5 data_text5 longtext ;
ALTER TABLE ezcontentobject_attribute CHANGE COLUMN data_text data_text longtext ;
ALTER TABLE ezcontentobject_tree CHANGE COLUMN path_identification_string path_identification_string longtext ;
ALTER TABLE ezinfocollection_attribute CHANGE COLUMN data_text data_text longtext ;
ALTER TABLE ezmessage CHANGE COLUMN body body longtext ;
ALTER TABLE ezmodule_run CHANGE COLUMN module_data module_data longtext ;
ALTER TABLE eznotificationcollection CHANGE COLUMN data_subject data_subject longtext NOT NULL ;
ALTER TABLE eznotificationcollection CHANGE COLUMN data_text data_text longtext NOT NULL ;
ALTER TABLE eznotificationevent CHANGE COLUMN data_text1 data_text1 longtext NOT NULL ;
ALTER TABLE eznotificationevent CHANGE COLUMN data_text2 data_text2 longtext NOT NULL ;
ALTER TABLE eznotificationevent CHANGE COLUMN data_text3 data_text3 longtext NOT NULL ;
ALTER TABLE eznotificationevent CHANGE COLUMN data_text4 data_text4 longtext NOT NULL ;
ALTER TABLE ezoperation_memento CHANGE COLUMN memento_data memento_data longtext NOT NULL ;
ALTER TABLE ezorder CHANGE COLUMN data_text_2 data_text_2 longtext ;
ALTER TABLE ezorder CHANGE COLUMN data_text_1 data_text_1 longtext ;
ALTER TABLE ezpdf_export CHANGE COLUMN intro_text intro_text longtext ;
ALTER TABLE ezpdf_export CHANGE COLUMN sub_text sub_text longtext ;
ALTER TABLE ezpending_actions CHANGE COLUMN param param longtext ;
ALTER TABLE ezrss_export CHANGE COLUMN description description longtext ;
ALTER TABLE ezrss_import CHANGE COLUMN url url longtext ;
ALTER TABLE ezsession CHANGE COLUMN data data longtext NOT NULL ;
ALTER TABLE ezsite_data CHANGE COLUMN value value longtext NOT NULL ;
ALTER TABLE ezurlalias CHANGE COLUMN source_url source_url longtext NOT NULL ;
ALTER TABLE ezurlalias CHANGE COLUMN destination_url destination_url longtext NOT NULL ;
ALTER TABLE ezworkflow_process CHANGE COLUMN parameters parameters longtext ;
ALTER TABLE ezimagefile CHANGE COLUMN filepath filepath longtext NOT NULL ;

-- Change NULL and size for some fields
ALTER TABLE ezcontentobject_attribute CHANGE COLUMN sort_key_string sort_key_string varchar(50) DEFAULT '' ;
ALTER TABLE ezcontentobject_attribute CHANGE COLUMN data_type_string data_type_string varchar(50) DEFAULT '' ;
ALTER TABLE ezorder CHANGE COLUMN email email varchar(150) DEFAULT '' ;
ALTER TABLE ezproductcollection_item CHANGE COLUMN price price float DEFAULT '0' ;

ALTER TABLE ezcontentclass_attribute CHANGE COLUMN is_searchable is_searchable int(11) NOT NULL DEFAULT '0' ;
ALTER TABLE ezcontentclass_attribute CHANGE COLUMN is_required is_required int(11) NOT NULL DEFAULT '0' ;
ALTER TABLE ezcontentobject_tree CHANGE COLUMN sort_order sort_order int(11) DEFAULT '1' ;
ALTER TABLE ezcontentobject_tree CHANGE COLUMN modified_subnode modified_subnode int(11) DEFAULT '0' ;
ALTER TABLE ezcontentobject_version CHANGE COLUMN workflow_event_pos workflow_event_pos int(11) DEFAULT '0' ;
ALTER TABLE ezdiscountsubrule_value CHANGE COLUMN issection issection int(11) NOT NULL DEFAULT '0' ;
ALTER TABLE ezinfocollection CHANGE COLUMN modified modified int(11) DEFAULT '0' ;
ALTER TABLE ezmedia CHANGE COLUMN has_controller has_controller int(11) DEFAULT '0' ;
ALTER TABLE ezmedia CHANGE COLUMN is_autoplay is_autoplay int(11) DEFAULT '0' ;
ALTER TABLE ezmedia CHANGE COLUMN is_loop is_loop int(11) DEFAULT '0' ;
ALTER TABLE ezmessage CHANGE COLUMN is_sent is_sent int(11) NOT NULL DEFAULT '0' ;
ALTER TABLE eznode_assignment CHANGE COLUMN sort_order sort_order int(11) DEFAULT '1' ;
ALTER TABLE ezuser_setting CHANGE COLUMN is_enabled is_enabled int(11) NOT NULL DEFAULT '0' ;
ALTER TABLE ezworkflow CHANGE COLUMN is_enabled is_enabled int(11) NOT NULL DEFAULT '0' ;
ALTER TABLE ezworkflow_assign CHANGE COLUMN as_tree as_tree int(11) NOT NULL DEFAULT '0' ;


-- 3.4.0alpha2 to 3.4.0alpha3


ALTER TABLE ezcontentclass ADD COLUMN remote_id varchar(100) NOT NULL default '';
ALTER TABLE ezcontentobject_tree ADD COLUMN remote_id varchar(100) NOT NULL default '';

ALTER TABLE eznode_assignment ADD COLUMN parent_remote_id varchar(100) NOT NULL default '';

CREATE TABLE tmp_ezsession AS
SELECT ezsession.*, ezuser_session_link.user_id FROM ezsession, ezuser_session_link
 WHERE ezsession.session_key=ezuser_session_link.session_key;

DELETE FROM ezsession;

ALTER TABLE ezsession ADD COLUMN user_id integer NOT NULL default 0;

INSERT INTO ezsession SELECT * FROM tmp_ezsession;

CREATE INDEX ezsession_user_id ON ezsession ( user_id );

DROP TABLE tmp_ezsession;

DROP TABLE ezuser_session_link;


-- 3.4.0alpha3 to 3.4.0alpha4

-- 3.4.0alpha4 to 3.4.0beta1

-- 3.4.0beta1 to 3.4.0beta2

-- 3.4.0beta2 to 3.4.0

-- incrementing size of 'sort_key_string' to 255 characters
ALTER TABLE ezcontentobject_attribute MODIFY sort_key_string VARCHAR(255) NOT NULL default '';
-- cleans up ezcontentbrowsebookmark and ezcontentbrowserecent tables from corrupted node_id's

CREATE TABLE ezcontentbrowsebookmark_temp AS
  SELECT ezcontentbrowsebookmark.* FROM ezcontentbrowsebookmark,ezcontentobject_tree 
    WHERE ezcontentbrowsebookmark.node_id = ezcontentobject_tree.node_id;
DELETE FROM ezcontentbrowsebookmark;
INSERT INTO ezcontentbrowsebookmark SELECT * FROM ezcontentbrowsebookmark_temp;
DROP TABLE ezcontentbrowsebookmark_temp;

CREATE TABLE ezcontentbrowserecent_temp AS
  SELECT ezcontentbrowserecent.* from ezcontentbrowserecent,ezcontentobject_tree 
    WHERE ezcontentbrowserecent.node_id = ezcontentobject_tree.node_id;
DELETE FROM ezcontentbrowserecent;
INSERT INTO ezcontentbrowserecent SELECT * FROM ezcontentbrowserecent_temp;
DROP TABLE ezcontentbrowserecent_temp;

