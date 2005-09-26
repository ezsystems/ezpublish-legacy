UPDATE ezsite_data SET value='3.5.6' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='12' WHERE name='ezpublish-release';

ALTER TABLE ezcontentobject_link ADD COLUMN  contentclassattribute_id int(11) NOT NULL default '0';

CREATE INDEX ezco_link_from on ezcontentobject_link( from_contentobject_id,from_contentobject_version,contentclassattribute_id );
CREATE INDEX ezco_link_to_co_id on ezcontentobject_link( to_contentobject_id );
