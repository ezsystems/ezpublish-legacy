SELECT pg_catalog.setval ('ezcontentclass_s', (select max(id)+1 from ezcontentclass), false);
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

-- If you're upgrading from an 3.3-1 installation ( not 3.2-4 -> 3.3-1 upgrade ) please uncomment and run the line below

-- ALTER TABLE ezsubtree_notification_rule ADD user_id INTEGER;
-- ALTER TABLE ezsubtree_notification_rule ALTER user_id SET NOT NULL;
-- 
-- CREATE SEQUENCE tmp_notification_rule_s
--     START 1
--     INCREMENT 1
--     MAXVALUE 9223372036854775807
--     MINVALUE 1
--     CACHE 1;
-- 
-- CREATE TABLE tmp_notification_rule ( id INTEGER DEFAULT nextval('tmp_notification_rule_s'::text) NOT NULL,
--                                      use_digest INTEGER DEFAULT 0,
--                                      node_id INTEGER NOT NULL,
--                                      user_id INTEGER NOT NULL,
--                                      primary key( id ) );
-- 
-- INSERT INTO tmp_notification_rule ( use_digest, node_id, user_id ) SELECT rule.use_digest, rule.node_id, ezuser.contentobject_id AS user_id 
--       FROM ezsubtree_notification_rule AS rule, ezuser 
--       WHERE rule.address=ezuser.email;
-- 
-- DROP TABLE ezsubtree_notification_rule;
-- DROP SEQUENCE tmp_notification_rule_s;
-- ALTER TABLE tmp_notification_rule rename TO ezsubtree_notification_rule;
-- 
-- CREATE INDEX ezsubtree_notification_rule_id ON ezsubtree_notification_rule(id);
-- CREATE INDEX ezsubtree_notification_rule_user_id ON ezsubtree_notification_rule(user_id);
