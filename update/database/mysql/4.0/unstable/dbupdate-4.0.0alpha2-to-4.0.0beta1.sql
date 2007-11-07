UPDATE ezsite_data SET value='4.0.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

DELETE FROM ezuser_setting where user_id not in (SELECT contentobject_id FROM ezuser);