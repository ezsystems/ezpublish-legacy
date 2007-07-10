UPDATE ezsite_data SET value='3.9.3' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='9' WHERE name='ezpublish-release';
ALTER TABLE ezvatrule CHANGE country country_code varchar(255) DEFAULT '' NOT NULL;
