UPDATE ezsite_data SET value='3.2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezsite_data DROP COLUMN id;
ALTER TABLE ezsite_data ADD PRIMARY KEY ( name );
