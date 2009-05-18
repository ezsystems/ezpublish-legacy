SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.2.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- START: from 4.1.1
ALTER TABLE ezworkflow_event
CHANGE data_text1 data_text1 VARCHAR( 255 ),
CHANGE data_text2 data_text2 VARCHAR( 255 ),
CHANGE data_text3 data_text3 VARCHAR( 255 ),
CHANGE data_text4 data_text4 VARCHAR( 255 );

ALTER TABLE ezsession ALTER COLUMN user_hash SET DEFAULT '';
-- END: from 4.1.1

-- START: from 4.1.0
-- END: from 4.1.0