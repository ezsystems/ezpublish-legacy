UPDATE ezsite_data SET value='3.8.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder ADD is_archived INT;
UPDATE ezorder SET is_archived='0';
ALTER TABLE ezorder ALTER COLUMN is_archived SET DEFAULT 0;
ALTER TABLE ezorder ALTER COLUMN is_archived SET NOT NULL;

CREATE INDEX ezorder_is_archived ON ezorder USING btree (is_archived);


-- Improved Approval Workflow -- START --

UPDATE ezworkflow_event set data_text3=data_int1;

-- Improved Approval Workflow --  END  --

UPDATE ezpolicy SET function_name='administrate' WHERE module_name='shop' AND function_name='adminstrate';

