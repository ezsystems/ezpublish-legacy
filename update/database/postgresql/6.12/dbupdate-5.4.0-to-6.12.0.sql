UPDATE ezsite_data SET value='6.12.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezuser ALTER COLUMN password_hash TYPE VARCHAR(255);
