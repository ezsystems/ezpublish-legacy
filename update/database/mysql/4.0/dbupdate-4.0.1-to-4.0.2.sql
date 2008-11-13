UPDATE ezsite_data SET value='4.0.2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder_item CHANGE vat_value vat_value FLOAT NOT NULL default 0;

