SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.2.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- Start ezp-21465 : Cleanup extra lines in the ezurl_object_link table
DROP TEMPORARY TABLE IF EXISTS ezurl_object_link_temp ;

CREATE TEMPORARY TABLE ezurl_object_link_temp (
   contentobject_attribute_id int(11) NOT NULL DEFAULT '0',
   contentobject_attribute_version int(11) NOT NULL DEFAULT '0',
   url_id int(11) NOT NULL DEFAULT '0',
   KEY ezurl_ol_coa_id (contentobject_attribute_id),
   KEY ezurl_ol_coa_version (contentobject_attribute_version),
   KEY ezurl_ol_url_id (url_id),
   UNIQUE KEY unique_key (contentobject_attribute_id, contentobject_attribute_version)
) IGNORE SELECT * FROM ezurl_object_link;

TRUNCATE TABLE ezurl_object_link;

INSERT INTO ezurl_object_link SELECT * FROM ezurl_object_link_temp;
-- End ezp-21465
