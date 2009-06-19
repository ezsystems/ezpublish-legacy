CREATE TABLE ezdfsfile (
  `name` text NOT NULL,
  name_trunk text NOT NULL,
  name_hash varchar(34) NOT NULL DEFAULT '',
  datatype varchar(60) NOT NULL DEFAULT 'application/octet-stream',
  scope varchar(20) NOT NULL DEFAULT '',
  size bigint(20) unsigned NOT NULL DEFAULT '0',
  mtime int(11) NOT NULL DEFAULT '0',
  expired tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (name_hash),
  KEY ezdfsfile_name (`name`(250)),
  KEY ezdfsfile_name_trunk (name_trunk(250)),
  KEY ezdfsfile_mtime (mtime),
  KEY ezdfsfile_expired_name (expired,`name`(250))
) ENGINE=InnoDB;
