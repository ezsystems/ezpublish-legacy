SELECT pg_catalog.setval ('ezcontentclass_s', (select max(id)+1 from ezcontentclass), false);
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';
