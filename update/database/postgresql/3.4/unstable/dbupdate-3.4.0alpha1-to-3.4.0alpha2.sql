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
--- 
--- Run script update/common/scripts/addorderemail.php after this change to
--- insert correct email for existing orders.
---


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


-- Make sure ezrss_export_item (description) has '' as default like the MySQL schema
ALTER TABLE ezrss_export_item RENAME COLUMN description TO description_tmp;
ALTER TABLE ezrss_export_item ADD COLUMN description character varying(255) ;
ALTER TABLE ezrss_export_item ALTER description SET DEFAULT '' ;
UPDATE ezrss_export_item SET description=description_tmp;
ALTER TABLE ezrss_export_item DROP COLUMN description_tmp;

-- Make sure ezsession (expiration_time) is normal integer and not bigint
ALTER TABLE ezsession RENAME COLUMN expiration_time TO expiration_time_tmp;
ALTER TABLE ezsession ADD COLUMN expiration_time integer ;
ALTER TABLE ezsession ALTER expiration_time SET DEFAULT 0 ;
UPDATE ezsession SET expiration_time=0;
ALTER TABLE ezsession ALTER expiration_time SET NOT NULL ;
UPDATE ezsession SET expiration_time=expiration_time_tmp;
ALTER TABLE ezsession DROP COLUMN expiration_time_tmp;
CREATE INDEX expiration_time986 ON ezsession USING btree (expiration_time);

-- Run these four if you installed kernel_schema.sql from 3.4.0alpha1, they were missing
-- ALTER TABLE ezuser_role ADD COLUMN limit_identifier varchar(255);
-- ALTER TABLE ezuser_role ALTER limit_identifier SET DEFAULT '';
-- ALTER TABLE ezuser_role ADD COLUMN limit_value varchar(255);
-- ALTER TABLE ezuser_role ALTER limit_value SET DEFAULT '';

