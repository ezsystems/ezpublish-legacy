UPDATE ezsite_data SET value='3.4.0alpha2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

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
