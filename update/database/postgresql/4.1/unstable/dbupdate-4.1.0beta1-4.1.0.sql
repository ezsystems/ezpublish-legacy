ALTER TABLE ezpending_actions ADD COLUMN created integer;

CREATE INDEX ezpending_actions_created ON ezpending_actions USING btree ( created );
