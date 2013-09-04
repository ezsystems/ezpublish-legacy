UPDATE ezsite_data SET value='5.2.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontent_language
    ALTER COLUMN id TYPE BIGINT;

ALTER TABLE ezcontentclass
    ALTER COLUMN initial_language_id TYPE BIGINT,
    ALTER COLUMN language_mask TYPE BIGINT;

ALTER TABLE ezcontentclass_name
    ALTER COLUMN language_id TYPE BIGINT;

ALTER TABLE ezcontentobject
    ALTER COLUMN initial_language_id TYPE BIGINT,
    ALTER COLUMN language_mask TYPE BIGINT;

ALTER TABLE ezcontentobject_name
    ALTER COLUMN language_id TYPE BIGINT;

ALTER TABLE ezcontentobject_attribute
    ALTER COLUMN language_id TYPE BIGINT;

ALTER TABLE ezcontentobject_version
    ALTER COLUMN initial_language_id TYPE BIGINT,
    ALTER COLUMN language_mask TYPE BIGINT;

ALTER TABLE ezcobj_state
    ALTER COLUMN default_language_id TYPE BIGINT,
    ALTER COLUMN language_mask TYPE BIGINT;

ALTER TABLE ezcobj_state_group
    ALTER COLUMN default_language_id TYPE BIGINT,
    ALTER COLUMN language_mask TYPE BIGINT;

ALTER TABLE ezcobj_state_group_language
    ALTER COLUMN language_id TYPE BIGINT,
    ALTER COLUMN real_language_id TYPE BIGINT;

ALTER TABLE ezcobj_state_language
    ALTER COLUMN language_id TYPE BIGINT;

ALTER TABLE ezurlalias_ml
    ALTER COLUMN lang_mask TYPE BIGINT;
