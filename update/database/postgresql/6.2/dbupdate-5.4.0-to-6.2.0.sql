-- Set storage engine schema version number
UPDATE ezsite_data SET value='6.2.0' WHERE name='ezpublish-version';

--
-- EZP-19123: Make ezuser.login case in-sensitive across databases
--

ALTER TABLE ezuser ADD login_normalized character varying(150) DEFAULT ''::character varying NOT NULL;
UPDATE ezuser SET login_normalized=lower(login);

-- Note: As part of EZP-19123, it was decided to not allow duplicate login with different case, this should not have
-- been issue on mysql where code checking for existing use was implicit case-insensitive. However on postgres this was
-- not the case, meaning you'll need to manually handle conflicts if anyone occurs on the following line because of the
-- new "UNIQUE" constraint.
ALTER TABLE ezuser DROP CONSTRAINT ezuser_login, ADD CONSTRAINT ezuser_login UNIQUE KEY (login_normalized);
