UPDATE ezsite_data SET value='5.2.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- Start ezp-21465 : Cleanup extra lines in the ezurl_object_link table
DELETE
FROM ezurl_object_link AS T1
WHERE T1.url_id < ANY (SELECT url_id
      FROM ezurl_object_link T2
      WHERE T1.url_id <> T2.url_id
      AND T1.contentobject_attribute_id = T2.contentobject_attribute_id
      AND T1.contentobject_attribute_version = T2.contentobject_attribute_version);
-- End ezp-21465

