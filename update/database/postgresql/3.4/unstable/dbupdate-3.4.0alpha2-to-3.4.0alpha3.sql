UPDATE ezsite_data SET value='3.4.0alpha3' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER TABLE ezcontentclass ADD COLUMN remote_id varchar(100);
UPDATE ezcontentclass SET remote_id='';
ALTER TABLE ezcontentclass ALTER remote_id SET NOT NULL;
ALTER TABLE ezcontentclass ALTER remote_id SET default '';

ALTER TABLE ezcontentobject_tree ADD COLUMN remote_id varchar(100);
UPDATE ezcontentobject_tree SET remote_id='';
ALTER TABLE ezcontentobject_tree ALTER remote_id SET NOT NULL;
ALTER TABLE ezcontentobject_tree ALTER remote_id SET default '';

ALTER TABLE eznode_assignment ADD COLUMN parent_remote_id varchar(100);
UPDATE eznode_assignment SET parent_remote_id='';
ALTER TABLE eznode_assignment ALTER parent_remote_id SET NOT NULL;
ALTER TABLE eznode_assignment ALTER parent_remote_id SET default '';

ALTER TABLE ezsession ADD COLUMN user_id integer;
UPDATE ezsession SET user_id=0;
ALTER TABLE ezsession ALTER user_id SET NOT NULL;
ALTER TABLE ezsession ALTER user_id SET DEFAULT 0;
CREATE INDEX ezsession_user_id ON ezsession ( user_id );
ALTER TABLE ezsession ALTER user_id DROP NOT NULL;
UPDATE ezsession SET user_id=(SELECT ezuser_session_link.user_id FROM ezuser_session_link, ezsession WHERE ezsession.session_key=ezuser_session_link.session_key);
UPDATE ezsession SET user_id=0 WHERE user_id IS NULL;
ALTER TABLE ezsession ALTER user_id SET NOT NULL;
DROP TABLE ezuser_session_link;
