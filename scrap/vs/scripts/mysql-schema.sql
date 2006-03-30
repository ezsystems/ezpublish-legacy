DROP TABLE IF EXISTS ezdbfile_data;
DROP TABLE IF EXISTS ezdbfile;

CREATE TABLE ezdbfile (
  id        MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
  datatype  VARCHAR(60)  NOT NULL DEFAULT 'application/octet-stream',
  name      VARCHAR(255) NOT NULL DEFAULT '',
  name_hash VARCHAR(34)  NOT NULL DEFAULT '',
  scope     VARCHAR(20)  NOT NULL DEFAULT '',
  size      BIGINT(20)   UNSIGNED NOT NULL,
  mtime     INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE INDEX ezdbfile_name (name),
  UNIQUE INDEX ezdbfile_name_hash (name_hash)
) ENGINE=InnoDB;


CREATE TABLE ezdbfile_data (
  id       MEDIUMINT(8) unsigned NOT NULL auto_increment,
  masterid MEDIUMINT(8) unsigned NOT NULL default '0',
  filedata BLOB NOT NULL,
  PRIMARY KEY (id),
  KEY master_idx (masterid)
) ENGINE=InnoDB;
