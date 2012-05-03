CREATE TABLE ezdbfile (
  datatype      VARCHAR(255)   NOT NULL DEFAULT 'application/octet-stream',
  name          TEXT          NOT NULL,
  name_trunk    TEXT          NOT NULL,
  name_hash     VARCHAR(34)   NOT NULL DEFAULT '',
  scope         VARCHAR(20)   NOT NULL DEFAULT '',
  size          BIGINT(20)    UNSIGNED NOT NULL DEFAULT '0',
  mtime         INT(11)       NOT NULL DEFAULT '0',
  expired       BOOL          NOT NULL DEFAULT '0',
  PRIMARY KEY (name_hash),
  INDEX ezdbfile_name (name(250)),
  INDEX ezdbfile_name_trunk (name_trunk(250)),
  INDEX ezdbfile_mtime (mtime),
  INDEX ezdbfile_expired_name (expired, name(250))
) ENGINE=InnoDB;


CREATE TABLE ezdbfile_data (
  name_hash VARCHAR(34)   NOT NULL DEFAULT '',
  offset    INT(11) UNSIGNED NOT NULL,
  filedata  BLOB          NOT NULL,
  PRIMARY KEY (name_hash, offset),
  CONSTRAINT ezdbfile_fk1 FOREIGN KEY (name_hash) REFERENCES ezdbfile (name_hash) ON DELETE CASCADE
) ENGINE=InnoDB;
