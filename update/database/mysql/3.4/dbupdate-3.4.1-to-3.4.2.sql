UPDATE ezsite_data SET value='3.4.2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='9' WHERE name='ezpublish-release';

-- Fix typos in the indexes creation queries.
DROP   INDEX ezproductcollection_item_contentobject_id ON
             ezproductcollection_item;                 
CREATE INDEX ezproductcollection_item_contentobject_id ON
             ezproductcollection_item (contentobject_id);

DROP INDEX ezsubtree_notification_rule_id ON ezsubtree_notification_rule;
