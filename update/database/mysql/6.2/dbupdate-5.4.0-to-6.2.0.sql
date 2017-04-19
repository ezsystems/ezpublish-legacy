SET default_storage_engine=InnoDB;
-- Set storage engine schema version number
UPDATE ezsite_data SET value='6.2.0' WHERE name='ezpublish-version';

--
-- EZP-19123: Make ezuser.login case in-sensitive across databases
--

ALTER TABLE ezuser ADD COLUMN login_normalized varchar(150) NOT NULL DEFAULT '' AFTER login;
UPDATE ezuser SET login_normalized=LOWER(login);

-- Note: As part of EZP-19123, it was decided to not allow duplicate login with different case, this should not have
-- been issue on mysql where code checking for existing use was implicit case-insensitive. However on postgres this was
-- not the case, meaning you'll need to manually handle conflicts if anyone occurs on the following line because of the
-- new "UNIQUE" constraint.
ALTER TABLE ezuser DROP KEY ezuser_login, ADD UNIQUE KEY ezuser_login (login_normalized);
