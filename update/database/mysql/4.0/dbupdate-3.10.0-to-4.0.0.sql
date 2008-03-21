UPDATE ezsite_data SET value='4.0.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';

DELETE FROM ezuser_setting where user_id not in (SELECT contentobject_id FROM ezuser);

DELETE FROM ezcontentclass_classgroup WHERE NOT EXISTS (SELECT * FROM ezcontentclass c WHERE c.id=contentclass_id AND c.version=contentclass_version);

-- START: from 3.10.1
CREATE TABLE ezurlwildcard (
  id int(11) NOT NULL auto_increment,
  source_url longtext NOT NULL,
  destination_url longtext NOT NULL,
  type int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
);
-- END: from 3.10.1

