ALTER TABLE ezpending_actions ADD COLUMN created int(11) DEFAULT NULL;

ALTER TABLE ezpending_actions ADD INDEX ezpending_actions_created ( created );
