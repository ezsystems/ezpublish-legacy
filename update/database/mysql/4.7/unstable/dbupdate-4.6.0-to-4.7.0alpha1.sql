SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.7.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';


ALTER TABLE ezpending_actions ADD COLUMN id int(11) AUTO_INCREMENT PRIMARY KEY;


-- Cleanup for #18886
-- when a user is manually enabled through the admin interface,
-- the corresponding ezuser_accountkey record is not removed
DELETE FROM ezuser_accountkey WHERE user_id IN ( SELECT user_id FROM ezuser_setting WHERE is_enabled = 1 );
