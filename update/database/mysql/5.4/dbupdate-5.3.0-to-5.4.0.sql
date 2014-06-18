SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.4.0alpha1' WHERE name='ezpublish-version';

DROP TABLE ezsearch_return_count;
