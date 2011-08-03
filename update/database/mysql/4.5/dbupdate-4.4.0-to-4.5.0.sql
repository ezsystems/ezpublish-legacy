SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.5.0' WHERE name='ezpublish-version';
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

CREATE TABLE `ezprest_token` (
  `id` varchar(200) NOT NULL,
  `refresh_token` varchar(200) NOT NULL,
  `expirytime` bigint(20) NOT NULL DEFAULT '0',
  `client_id` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `scope` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token_client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ezprest_authcode` (
  `id` varchar(200) NOT NULL,
  `expirytime` bigint(20) NOT NULL DEFAULT '0',
  `client_id` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `scope` varchar(200) DEFAULT NULL,

  PRIMARY KEY (`id`),
  KEY `authcode_client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ezprest_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` longtext,
  `client_id` varchar(200) DEFAULT NULL,
  `client_secret` varchar(200) DEFAULT NULL,
  `endpoint_uri` varchar(200) DEFAULT NULL,
  `owner_id` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  `version` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id_unique` (`client_id`,`version`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB;

CREATE TABLE `ezprest_authorized_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rest_client_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_user` (`rest_client_id`,`user_id`)
) ENGINE=InnoDB;
