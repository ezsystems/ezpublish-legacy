SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.5.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE ezpublishingqueueprocesses (
  created int(11) default NULL,
  ezcontentobject_version_id int(11) NOT NULL default '0',
  finished int(11) default NULL,
  pid int(8) default NULL,
  started int(11) default NULL,
  status int(2) default NULL,
  PRIMARY KEY  (ezcontentobject_version_id)
) ENGINE=InnoDB;

ALTER TABLE ezsection MODIFY COLUMN identifier VARCHAR(255) DEFAULT '';
UPDATE ezsection SET identifier='' WHERE identifier IS NULL;