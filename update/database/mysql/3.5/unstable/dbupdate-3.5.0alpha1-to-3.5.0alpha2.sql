UPDATE ezsite_data SET value='3.5.0alpha2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

-- fix for section based conditional assignment also in 3.4.3
update  ezuser_role set limit_identifier='Section' where limit_identifier='section';