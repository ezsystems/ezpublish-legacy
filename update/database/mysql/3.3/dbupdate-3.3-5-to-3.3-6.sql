UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

-- incrementing size of 'sort_key_string' to 255 characters
ALTER TABLE ezcontentobject_attribute MODIFY sort_key_string VARCHAR(255) NOT NULL default '';

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

-- fixes for schemas in packages, they did not have 100% similarity with the kernel_schema
ALTER TABLE ezcontentobject_attribute MODIFY data_type_string VARCHAR(50) default '';
ALTER TABLE ezproductcollection_item DROP INDEX ezproductcollection_item_contentobject_id;
ALTER TABLE ezproductcollection_item ADD INDEX ezproductcollection_item_contentobject_id ( contentobject_id );
ALTER TABLE ezsubtree_notification_rule DROP INDEX ezsubtree_notification_rule_id;
ALTER TABLE ezurlalias CHANGE COLUMN is_wildcard is_wildcard int(11) NOT NULL DEFAULT '0' ;
