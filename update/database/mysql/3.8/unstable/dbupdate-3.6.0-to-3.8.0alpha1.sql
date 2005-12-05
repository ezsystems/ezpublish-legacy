UPDATE ezsite_data SET value='3.8.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder ADD is_archived INT DEFAULT '0' NOT NULL ;
ALTER TABLE ezorder ADD INDEX ( is_archived ) ;


-- Improved Approval Workflow -- START --
UPDATE ezworkflow_event set data_text3=data_int1;
-- Improved Approval Workflow --  END  --

UPDATE ezpolicy SET function_name='administrate' WHERE module_name='shop' AND function_name='adminstrate';

-- Improved RSS import. -- START --
ALTER TABLE ezrss_import ADD COLUMN import_description longtext NOT NULL DEFAULT '';
-- Improved RSS import. -- END --
