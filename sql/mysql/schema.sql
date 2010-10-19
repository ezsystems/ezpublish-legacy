CREATE TABLE `ezprest_token` (
  `id` varchar(200) NOT NULL,
  `expirytime` bigint(20) NOT NULL DEFAULT '0',
  `client_id` varchar(200) NOT NULL,
  `scope` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token_client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
