UPDATE ezsite_data SET value='3.4.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='7' WHERE name='ezpublish-release';

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