SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.1.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezworkflow_event
CHANGE data_text1 data_text1 VARCHAR(255),
CHANGE data_text2 data_text2 VARCHAR(255),
CHANGE data_text3 data_text3 VARCHAR(255),
CHANGE data_text4 data_text4 VARCHAR(255);