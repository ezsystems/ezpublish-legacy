UPDATE ezsite_data SET value='3.4.0alpha2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

---
--- Add session ID to user object
---

CREATE TABLE ezuser_session_link (
  user_id INTEGER NOT NULL,
  session_key CHARACTER VARYING(32),
  PRIMARY KEY( user_id, session_key )
);
CREATE INDEX ezuser_session_link_user_idx on ezuser_session_link ( user_id );
CREATE INDEX ezuser_session_link_session_idx on ezuser_session_link ( session_key );

CREATE INDEX ezpreferences_user_id_idx on ezpreferences ( user_id, name );

ALTER TABLE ezorder ADD COLUMN email CHARACTER VARYING(150);
ALTER TABLE ezorder ALTER email SET DEFAULT '';


CREATE TABLE ezsubtree_expiry (
    subtree character varying(255) NOT NULL,
    cache_file character varying(255) NOT NULL
);

CREATE INDEX ezsubtree_expiry_subtree ON ezsubtree_expiry USING btree (subtree);


CREATE TABLE ezpending_actions (
  action character varying(64) NOT NULL,
  param text
);

CREATE INDEX ezpending_actions_action ON ezpending_actions USING btree (action);
