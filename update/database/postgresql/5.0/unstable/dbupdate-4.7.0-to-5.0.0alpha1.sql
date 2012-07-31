UPDATE ezsite_data SET value='5.0.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

-- See http://issues.ez.no/19169
ALTER TABLE ezcobj_state_group_language ADD real_language_id integer DEFAULT 0 NOT NULL;
UPDATE ezcobj_state_group_language SET real_language_id = language_id & ~1

-- dropping the primary key (contentobject_state_group_id, language_id) and creating
-- the new one (contentobject_state_group_id, real_language_id) are done in the
-- PHP script after removing the duplicated entries
