UPDATE ezsite_data SET value='3.5.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

-- fix for section based conditional assignment also in 3.4.3
update  ezuser_role set limit_identifier='Section' where limit_identifier='section';

-- fixes incorrect name of group in ezcontentclass_classgroup 
update ezcontentclass_classgroup set group_name='Users' where group_id=2;
