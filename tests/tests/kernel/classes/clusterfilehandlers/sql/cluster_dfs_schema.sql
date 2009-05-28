CREATE TABLE ezdfsfile (
  datatype      VARCHAR(60)   NOT NULL DEFAULT 'application/octet-stream',
  name          TEXT          NOT NULL,
  name_trunk    TEXT          NOT NULL,
  name_hash     VARCHAR(34)   NOT NULL DEFAULT '',
  scope         VARCHAR(20)   NOT NULL DEFAULT '',
  size          BIGINT(20)    UNSIGNED NOT NULL,
  mtime         INT(11)       NOT NULL DEFAULT '0',
  expired       BOOL          NOT NULL DEFAULT '0',
  status        TINYINT(1)    NOT NULL DEFAULT '0',
  PRIMARY KEY (name_hash),
  INDEX ezdfsfile_name (name(250)),
  INDEX ezdfsfile_name_trunk (name_trunk(250)),
  INDEX ezdfsfile_mtime (mtime),
  INDEX ezdfsfile_expired_name (expired, name(250))
) ENGINE=InnoDB;

