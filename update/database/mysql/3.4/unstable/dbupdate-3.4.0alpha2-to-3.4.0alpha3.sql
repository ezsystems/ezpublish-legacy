UPDATE ezsite_data SET value='3.4.0alpha3' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER TABLE ezcontentclass ADD COLUMN remote_id varchar(100) NOT NULL default '';
ALTER TABLE ezcontentobject_tree ADD COLUMN remote_id varchar(100) NOT NULL default '';

ALTER TABLE eznode_assignment ADD COLUMN parent_remote_id varchar(100) NOT NULL default '';

ALTER TABLE ezsession ADD COLUMN user_id integer NOT NULL default 0;
CREATE INDEX ezsession_user_id ON ezsession ( user_id );
UPDATE ezsession, ezuser_session_link SET ezsession.user_id=ezuser_session_link.user_id WHERE ezsession.session_key=ezuser_session_link.session_key;
DROP TABLE ezuser_session_link;
