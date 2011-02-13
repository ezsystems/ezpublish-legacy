UPDATE ezsite_data SET value='4.1.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezsession ADD COLUMN user_hash VARCHAR( 32 );
ALTER TABLE ezsession ALTER COLUMN user_hash SET NOT NULL;

