SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.2.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- START: from 4.2.0 using cluster setup
UPDATE ezdbfile set scope='classattridentifiers' WHERE scope='classattributeidenti';
UPDATE ezdbfile set scope='classattridentifiers' WHERE scope='classattributeidenfiers';
-- END: from 4.2.0 using cluster setup
