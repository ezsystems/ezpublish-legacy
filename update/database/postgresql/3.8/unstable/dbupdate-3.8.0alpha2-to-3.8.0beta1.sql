UPDATE ezsite_data SET value='3.8.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

-- Multilanguage fixes

CREATE TABLE ezcontent_language
(
    id int NOT NULL default '0',
    disabled int NOT NULL default '0',
    locale varchar(20) NOT NULL default '',
    name varchar(255) NOT NULL default '',
    PRIMARY KEY (id)
);
                    
DROP TABLE ezcontent_translation;
                 
ALTER TABLE ezcontentobject ADD COLUMN language_mask int; 
ALTER TABLE ezcontentobject ALTER COLUMN language_mask SET DEFAULT 0;
ALTER TABLE ezcontentobject ALTER COLUMN language_mask SET NOT NULL;

ALTER TABLE ezcontentobject ADD COLUMN initial_language_id int;
ALTER TABLE ezcontentobject ALTER COLUMN initial_language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject ALTER COLUMN initial_language_id SET NOT NULL;

ALTER TABLE ezcontentobject_name ADD COLUMN language_id int;
ALTER TABLE ezcontentobject_name ALTER COLUMN language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_name ALTER COLUMN language_id SET NOT NULL;

ALTER TABLE ezcontentobject_attribute ADD COLUMN language_id int;
ALTER TABLE ezcontentobject_attribute ALTER COLUMN language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_attribute ALTER COLUMN language_id SET NOT NULL;

ALTER TABLE ezcontentobject_version ADD COLUMN language_mask int;
ALTER TABLE ezcontentobject_version ALTER COLUMN language_mask SET DEFAULT 0;
ALTER TABLE ezcontentobject_version ALTER COLUMN language_mask SET NOT NULL;

ALTER TABLE ezcontentobject_version ADD COLUMN initial_language_id int;
ALTER TABLE ezcontentobject_version ALTER COLUMN initial_language_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_version ALTER COLUMN initial_language_id SET NOT NULL;

ALTER TABLE ezcontentclass ADD COLUMN always_available int;
ALTER TABLE ezcontentclass ALTER COLUMN always_available SET DEFAULT 0;
ALTER TABLE ezcontentclass ALTER COLUMN always_available SET NOT NULL;

ALTER TABLE ezcontentobject_link ADD COLUMN op_code int;
ALTER TABLE ezcontentobject_link ALTER COLUMN op_code SET DEFAULT 0;
ALTER TABLE ezcontentobject_link ALTER COLUMN op_code SET NOT NULL;

ALTER TABLE eznode_assignment ADD COLUMN op_code int;
ALTER TABLE eznode_assignment ALTER COLUMN op_code SET DEFAULT 0;
ALTER TABLE eznode_assignment ALTER COLUMN op_code SET NOT NULL;

-- updates
-- set correct op_code
-- mark as being moved
update eznode_assignment set op_code=4 where from_node_id > 0 and op_code=0;
-- mark as being created
update eznode_assignment set op_code=2 where from_node_id <= 0 and op_code=0;
-- mark as being set
update eznode_assignment set op_code=2 where remote_id != 0 and op_code=0;

CREATE INDEX ezcontentobject_lmask ON ezcontentobject USING btree ( language_mask );

-- Now remember to run ./update/common/scripts/updatemultilingual.php before using the site

-- Information collection improvments
ALTER TABLE ezinfocollection ADD creator_id INT;
ALTER TABLE ezinfocollection ALTER COLUMN creator_id SET DEFAULT 0;
ALTER TABLE ezinfocollection ALTER COLUMN creator_id SET NOT NULL;
