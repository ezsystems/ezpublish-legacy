UPDATE ezsite_data SET value='3.4.0alpha2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

---
--- Add session ID to user object
---

CREATE TABLE ezuser_session_link (
  user_id int(11) NOT NULL,
  session_key varchar(32) NOT NULL,
  PRIMARY KEY( user_id, session_key )
);
CREATE INDEX ezuser_session_link_user_idx on ezuser_session_link ( user_id );
CREATE INDEX ezuser_session_link_session_idx on ezuser_session_link ( session_key );


CREATE INDEX ezpreferences_user_id_idx on ezpreferences ( user_id, name );

ALTER  TABLE ezorder ADD COLUMN email varchar(150) NOT NULL default '';



CREATE TABLE ezsubtree_expiry (
  subtree varchar(255) NOT NULL default '',
  cache_file varchar(255) NOT NULL default '',
  KEY ezsubtree_expiry_subtree (subtree)
) DELAY_KEY_WRITE=1 TYPE=MyISAM;


CREATE TABLE ezpending_actions (
  action varchar(64) NOT NULL,
  param text,
  KEY ezpending_actions_action (action)
) TYPE=MyISAM;
