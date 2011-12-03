SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.6.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE ezorder_nr_incr (
id int(11) NOT NULL AUTO_INCREMENT,
PRIMARY KEY ( id )
);

-- #18514 store affected class ids in data_text5 instead of data_text3 for multiplexer workflow event type
UPDATE ezworkflow_event SET data_text5 = data_text3, data_text3 = '' WHERE workflow_type_string = 'event_ezmultiplexer';
