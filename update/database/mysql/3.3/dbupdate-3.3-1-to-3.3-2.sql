UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

-- If you're upgrading from an 3.3-1 installation ( not 3.2-4 -> 3.3-1 upgrade ) please uncomment and run the line below

-- ALTER TABLE ezsubtree_notification_rule add user_id INT NOT NULL;
-- 
-- CREATE TABLE tmp_notification_rule ( id INT PRIMARY KEY AUTO_INCREMENT,
--                                      use_digest INT DEFAULT 0,
--                                      node_id INT NOT NULL,
--                                      user_id INT NOT NULL );
-- 
-- INSERT INTO tmp_notification_rule ( use_digest, node_id, user_id ) SELECT rule.use_digest, rule.node_id, ezuser.contentobject_id AS user_id 
--       FROM ezsubtree_notification_rule AS rule, ezuser 
--       WHERE rule.address=ezuser.email;
-- 
-- DROP TABLE ezsubtree_notification_rule;
-- ALTER TABLE tmp_notification_rule rename ezsubtree_notification_rule;
-- 
-- CREATE INDEX ezsubtree_notification_rule_id ON ezsubtree_notification_rule(id);
-- CREATE INDEX ezsubtree_notification_rule_user_id ON ezsubtree_notification_rule(user_id);
