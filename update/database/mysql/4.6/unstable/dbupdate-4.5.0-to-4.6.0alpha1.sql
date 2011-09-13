SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.6.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE ezorder_nr_incr (
id int(11) NOT NULL AUTO_INCREMENT,
PRIMARY KEY ( id )
);
