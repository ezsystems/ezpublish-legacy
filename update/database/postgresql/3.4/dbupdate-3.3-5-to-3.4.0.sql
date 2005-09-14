UPDATE ezsite_data SET value='3.4.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='7' WHERE name='ezpublish-release';

-- NOTE: This file will be generated from all the development releases, do not add entries

-- 3.3.3 to 3.4.0alpha1

ALTER TABLE ezcontentobject_tree ADD COLUMN modified_subnode INT;
ALTER TABLE ezcontentobject_tree ALTER modified_subnode SET DEFAULT 0;

CREATE INDEX ezcontentobject_tree_mod_sub ON ezcontentobject_tree( modified_subnode );

UPDATE ezcontentobject_tree SET modified_subnode=(SELECT max( ezcontentobject.modified )
       FROM ezcontentobject, ezcontentobject_tree as tree
       WHERE ezcontentobject.id = tree.contentobject_id AND
             tree.path_string like ezcontentobject_tree.path_string||'%');

ALTER TABLE ezuser_role ADD COLUMN limit_identifier varchar(255);
ALTER TABLE ezuser_role ALTER limit_identifier SET DEFAULT '';
ALTER TABLE ezuser_role ADD COLUMN limit_value varchar(255);
ALTER TABLE ezuser_role ALTER limit_value SET DEFAULT '';

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
  user_id INTEGER NOT NULL,
  session_key CHARACTER VARYING(32),
  PRIMARY KEY( user_id, session_key )
);
CREATE INDEX ezuser_session_link_user_idx on ezuser_session_link ( user_id );
CREATE INDEX ezuser_session_link_session_idx on ezuser_session_link ( session_key );

CREATE INDEX ezpreferences_user_id_idx on ezpreferences ( user_id, name );

ALTER TABLE ezorder ADD COLUMN email CHARACTER VARYING(150);
ALTER TABLE ezorder ALTER email SET DEFAULT '';
--- 
--- Run script update/common/scripts/addorderemail.php after this change to
--- insert correct email for existing orders.
---


CREATE TABLE ezsubtree_expiry (
    subtree character varying(255) NOT NULL,
    cache_file character varying(255) NOT NULL
);

CREATE INDEX ezsubtree_expiry_subtree ON ezsubtree_expiry USING btree (subtree);


CREATE TABLE ezpending_actions (
  action character varying(64) NOT NULL,
  param text
);

CREATE INDEX ezpending_actions_action ON ezpending_actions USING btree (action);


-- Make sure ezrss_export_item (description) has '' as default like the MySQL schema
ALTER TABLE ezrss_export_item RENAME COLUMN description TO description_tmp;
ALTER TABLE ezrss_export_item ADD COLUMN description character varying(255) ;
ALTER TABLE ezrss_export_item ALTER description SET DEFAULT '' ;
UPDATE ezrss_export_item SET description=description_tmp;
ALTER TABLE ezrss_export_item DROP COLUMN description_tmp;

-- Make sure ezsession (expiration_time) is normal integer and not bigint
ALTER TABLE ezsession RENAME COLUMN expiration_time TO expiration_time_tmp;
ALTER TABLE ezsession ADD COLUMN expiration_time integer ;
ALTER TABLE ezsession ALTER expiration_time SET DEFAULT 0 ;
UPDATE ezsession SET expiration_time=0;
ALTER TABLE ezsession ALTER expiration_time SET NOT NULL ;
UPDATE ezsession SET expiration_time=expiration_time_tmp;
ALTER TABLE ezsession DROP COLUMN expiration_time_tmp;
CREATE INDEX expiration_time986 ON ezsession USING btree (expiration_time);

-- Run these four if you installed kernel_schema.sql from 3.4.0alpha1, they were missing
-- ALTER TABLE ezuser_role ADD COLUMN limit_identifier varchar(255);
-- ALTER TABLE ezuser_role ALTER limit_identifier SET DEFAULT '';
-- ALTER TABLE ezuser_role ADD COLUMN limit_value varchar(255);
-- ALTER TABLE ezuser_role ALTER limit_value SET DEFAULT '';

-- 3.4.0alpha2 to 3.4.0alpha3

ALTER TABLE ezcontentclass ADD COLUMN remote_id varchar(100);
UPDATE ezcontentclass SET remote_id='';
ALTER TABLE ezcontentclass ALTER remote_id SET NOT NULL;
ALTER TABLE ezcontentclass ALTER remote_id SET default '';

ALTER TABLE ezcontentobject_tree ADD COLUMN remote_id varchar(100);
UPDATE ezcontentobject_tree SET remote_id='';
ALTER TABLE ezcontentobject_tree ALTER remote_id SET NOT NULL;
ALTER TABLE ezcontentobject_tree ALTER remote_id SET default '';

ALTER TABLE eznode_assignment ADD COLUMN parent_remote_id varchar(100);
UPDATE eznode_assignment SET parent_remote_id='';
ALTER TABLE eznode_assignment ALTER parent_remote_id SET NOT NULL;
ALTER TABLE eznode_assignment ALTER parent_remote_id SET default '';

ALTER TABLE ezsession ADD COLUMN user_id integer;
UPDATE ezsession SET user_id=0;
ALTER TABLE ezsession ALTER user_id SET NOT NULL;
ALTER TABLE ezsession ALTER user_id SET DEFAULT 0;
CREATE INDEX ezsession_user_id ON ezsession ( user_id );
ALTER TABLE ezsession ALTER user_id DROP NOT NULL;
UPDATE ezsession SET user_id=(SELECT ezuser_session_link.user_id FROM ezuser_session_link, ezsession WHERE ezsession.session_key=ezuser_session_link.session_key);
UPDATE ezsession SET user_id=0 WHERE user_id IS NULL;
ALTER TABLE ezsession ALTER user_id SET NOT NULL;
DROP TABLE ezuser_session_link;

-- 3.4.0alpha3 to 3.4.0alpha4

-- 3.4.0alpha4 to 3.4.0beta1

ALTER TABLE eznode_assignment RENAME COLUMN parent_remote_id TO parent_remote_id_tmp;
ALTER TABLE eznode_assignment ADD COLUMN parent_remote_id character varying(100) ;
ALTER TABLE eznode_assignment ALTER parent_remote_id SET DEFAULT '' ;
UPDATE eznode_assignment SET parent_remote_id='';
ALTER TABLE eznode_assignment ALTER parent_remote_id SET NOT NULL ;
UPDATE eznode_assignment SET parent_remote_id=parent_remote_id_tmp;
ALTER TABLE eznode_assignment DROP COLUMN parent_remote_id_tmp;

-- 3.4.0beta1 to 3.4.0beta2

-- 3.4.0beta2 to 3.4.0

-- incrementing size of 'sort_key_string' to 255 characters
CREATE LOCAL TEMPORARY TABLE temp_sort_key_string
(
    id               integer NOT NULL,
    sort_key_string  character varying(50) NOT NULL DEFAULT ''::character varying
);

INSERT INTO temp_sort_key_string SELECT id, sort_key_string FROM ezcontentobject_attribute;
ALTER TABLE ezcontentobject_attribute DROP COLUMN sort_key_string;
ALTER TABLE ezcontentobject_attribute ADD COLUMN sort_key_string character varying(255);
UPDATE ezcontentobject_attribute SET sort_key_string='';
ALTER TABLE ezcontentobject_attribute ALTER COLUMN sort_key_string SET NOT NULL;
ALTER TABLE ezcontentobject_attribute ALTER COLUMN sort_key_string SET DEFAULT ''::character varying;
UPDATE ezcontentobject_attribute SET sort_key_string=temp_sort_key_string.sort_key_string WHERE temp_sort_key_string.id=ezcontentobject_attribute.id;
CREATE INDEX sort_key_string367 ON ezcontentobject_attribute USING btree (sort_key_string);
DROP TABLE temp_sort_key_string;

-- cleans up ezcontentbrowsebookmark and ezcontentbrowserecent tables from corrupted node_id's

create temporary  table ezcontentbrowsebookmark_temp as 
      select  ezcontentbrowsebookmark.* from ezcontentbrowsebookmark,ezcontentobject_tree 
      where  ezcontentbrowsebookmark.node_id = ezcontentobject_tree.node_id;
delete from ezcontentbrowsebookmark;
insert into ezcontentbrowsebookmark select * from ezcontentbrowsebookmark_temp;

create temporary  table ezcontentbrowserecent_temp as 
      select  ezcontentbrowserecent.* from ezcontentbrowserecent,ezcontentobject_tree 
      where  ezcontentbrowserecent.node_id = ezcontentobject_tree.node_id;
delete from ezcontentbrowserecent;
insert into ezcontentbrowserecent select * from ezcontentbrowserecent_temp;