UPDATE ezsite_data SET value='3.7.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

UPDATE ezpolicy SET function_name='administrate' WHERE module_name='shop' AND function_name='adminstrate';

