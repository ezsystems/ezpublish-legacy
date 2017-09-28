-- See https://jira.ez.no/browse/EZP-24744 - Increase password security
ALTER TABLE ezuser ALTER COLUMN password_hash TYPE character varying(255);

