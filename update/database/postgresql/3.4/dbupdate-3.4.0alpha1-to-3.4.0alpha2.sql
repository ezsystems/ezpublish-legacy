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
