UPDATE ezsite_data SET value='3.4.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='7' WHERE name='ezpublish-release';

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