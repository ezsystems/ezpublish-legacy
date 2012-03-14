SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.7.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';


ALTER TABLE ezpending_actions ADD COLUMN id int(11) AUTO_INCREMENT PRIMARY KEY;


-- Cleanup for #18886
-- when a user is manually enabled through the admin interface,
-- the corresponding ezuser_accountkey record is not removed
DELETE FROM ezuser_accountkey WHERE user_id IN ( SELECT user_id FROM ezuser_setting WHERE is_enabled = 1 );


CREATE TABLE `ezaudit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_login` varchar(150) NOT NULL DEFAULT '',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(255) NOT NULL DEFAULT '',
  `action` varchar(255) NOT NULL DEFAULT '',
  `details` longtext,
  PRIMARY KEY (`id`),
  KEY `ezaudit_user_id` (`user_id`),
  KEY `ezaudit_action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
