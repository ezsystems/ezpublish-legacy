UPDATE ezsite_data SET value='4.1.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- START: using cluster setup
UPDATE ezdbfile set scope='classattridentifiers' WHERE scope='classattributeidenti';
UPDATE ezdbfile set scope='classattridentifiers' WHERE scope='classattributeidenfiers';
-- END: using cluster setup
