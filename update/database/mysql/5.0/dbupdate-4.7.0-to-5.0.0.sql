SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.0.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- See http://issues.ez.no/19169
ALTER TABLE ezcobj_state_group_language ADD COLUMN real_language_id int(11) NOT NULL DEFAULT '0';
UPDATE ezcobj_state_group_language SET real_language_id = language_id & ~1;

-- dropping the primary key (contentobject_state_group_id, language_id) and creating
-- the new one (contentobject_state_group_id, real_language_id) are done in the
-- PHP script after removing the duplicated entries

-- See http://issues.ez.no/19397
-- Normalize database so that updates to email address automatically affects digest settings
ALTER TABLE ezgeneral_digest_user_settings ADD COLUMN user_id int(11) NOT NULL default '0';
DELETE FROM ezgeneral_digest_user_settings WHERE address NOT IN (SELECT email FROM ezuser);
UPDATE ezgeneral_digest_user_settings SET user_id = (SELECT ezuser.contentobject_id
           FROM ezuser WHERE ezuser.email = ezgeneral_digest_user_settings.address);
ALTER TABLE ezgeneral_digest_user_settings ADD UNIQUE INDEX ezgeneral_digest_user_id (user_id);
ALTER TABLE ezgeneral_digest_user_settings DROP COLUMN address;
ALTER TABLE ezuser ADD INDEX ezuser_login (login);
