UPDATE ezsite_data SET value='3.4.0alpha2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

---
--- Add session ID to user object
---

ALTER TABLE ezuser ADD COLUMN session_key varchar(32) default '' NOT NULL;

CREATE INDEX ezpreferences_user_id_idx on ezpreferences ( user_id, name );
