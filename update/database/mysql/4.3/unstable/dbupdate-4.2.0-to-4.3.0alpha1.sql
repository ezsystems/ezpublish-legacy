SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.3.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezrss_export_item ADD COLUMN enclosure VARCHAR( 255 ) NULL;
