UPDATE ezsite_data SET value='3.4.3' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='10' WHERE name='ezpublish-release';

update  ezuser_role set limit_identifier='Section' where limit_identifier='section';