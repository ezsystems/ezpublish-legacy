UPDATE ezsite_data SET value='4.2.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- START: from 4.1.4
CREATE INDEX ezkeyword_attr_link_oaid ON ezkeyword_attribute_link USING btree (objectattribute_id);
CREATE INDEX ezinfocollection_co_id_created ON ezinfocollection USING btree (contentobject_id, created);
-- END: from 4.1.4

-- START: from 4.1.2
ALTER TABLE ezsession ALTER COLUMN user_hash SET DEFAULT '';
-- END: from 4.1.2

-- START: from 4.1.1
ALTER TABLE ezworkflow_event RENAME COLUMN data_text1 TO data_text1_tmp;
ALTER TABLE ezworkflow_event ADD COLUMN data_text1 character varying(255);
ALTER TABLE ezworkflow_event ALTER data_text1 SET DEFAULT NULL ;
UPDATE ezworkflow_event SET data_text1=data_text1_tmp;
ALTER TABLE ezworkflow_event DROP COLUMN data_text1_tmp;

ALTER TABLE ezworkflow_event RENAME COLUMN data_text2 TO data_text2_tmp;
ALTER TABLE ezworkflow_event ADD COLUMN data_text2 character varying(255);
ALTER TABLE ezworkflow_event ALTER data_text2 SET DEFAULT NULL ;
UPDATE ezworkflow_event SET data_text2=data_text2_tmp;
ALTER TABLE ezworkflow_event DROP COLUMN data_text2_tmp;

ALTER TABLE ezworkflow_event RENAME COLUMN data_text3 TO data_text3_tmp;
ALTER TABLE ezworkflow_event ADD COLUMN data_text3 character varying(255);
ALTER TABLE ezworkflow_event ALTER data_text3 SET DEFAULT NULL ;
UPDATE ezworkflow_event SET data_text3=data_text3_tmp;
ALTER TABLE ezworkflow_event DROP COLUMN data_text3_tmp;

ALTER TABLE ezworkflow_event RENAME COLUMN data_text4 TO data_text4_tmp;
ALTER TABLE ezworkflow_event ADD COLUMN data_text4 character varying(255);
ALTER TABLE ezworkflow_event ALTER data_text4 SET DEFAULT NULL ;
UPDATE ezworkflow_event SET data_text4=data_text4_tmp;
ALTER TABLE ezworkflow_event DROP COLUMN data_text4_tmp;
-- END: from 4.1.1

-- START: from 4.1.0
CREATE INDEX policy_id ON ezpolicy_limitation USING btree ( policy_id );
CREATE INDEX hash_key ON ezuser_accountkey USING btree ( hash_key );
CREATE INDEX wid_version_placement ON ezworkflow_event USING btree ( workflow_id, "version", placement );
-- END: from 4.1.0
