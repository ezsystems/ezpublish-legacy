UPDATE ezsite_data SET value='4.0.2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezorder_item ALTER COLUMN vat_value TYPE double precision;
ALTER TABLE ezorder_item ALTER COLUMN vat_value SET DEFAULT 0;
ALTER TABLE ezorder_item ALTER COLUMN vat_value SET NOT NULL;

