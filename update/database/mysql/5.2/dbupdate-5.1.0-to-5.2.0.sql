SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.2.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontent_language
    MODIFY id BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcontentclass
    MODIFY initial_language_id BIGINT NOT NULL DEFAULT '0',
    MODIFY language_mask BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcontentclass_name
    MODIFY language_id BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcontentobject
    MODIFY initial_language_id BIGINT NOT NULL DEFAULT '0',
    MODIFY language_mask BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcontentobject_name
    MODIFY language_id BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcontentobject_attribute
    MODIFY language_id BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcontentobject_version
    MODIFY initial_language_id BIGINT NOT NULL DEFAULT '0',
    MODIFY language_mask BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcobj_state
    MODIFY default_language_id BIGINT NOT NULL DEFAULT '0',
    MODIFY language_mask BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcobj_state_group
    MODIFY default_language_id BIGINT NOT NULL DEFAULT '0',
    MODIFY language_mask BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcobj_state_group_language
    MODIFY language_id BIGINT NOT NULL DEFAULT '0',
    MODIFY real_language_id BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezcobj_state_language
    MODIFY language_id BIGINT NOT NULL DEFAULT '0';

ALTER TABLE ezurlalias_ml
    MODIFY lang_mask BIGINT NOT NULL DEFAULT '0';
