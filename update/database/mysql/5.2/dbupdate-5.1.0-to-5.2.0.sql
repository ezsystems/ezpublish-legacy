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

-- Start ezp-21465 : Cleanup extra lines in the ezurl_object_link table
DROP TEMPORARY TABLE IF EXISTS ezurl_object_link_temp ;

CREATE TEMPORARY TABLE ezurl_object_link_temp (
   contentobject_attribute_id int(11) NOT NULL DEFAULT '0',
   contentobject_attribute_version int(11) NOT NULL DEFAULT '0',
   url_id int(11) NOT NULL DEFAULT '0',
   KEY ezurl_ol_coa_id (contentobject_attribute_id),
   KEY ezurl_ol_coa_version (contentobject_attribute_version),
   KEY ezurl_ol_url_id (url_id),
   UNIQUE KEY unique_key (contentobject_attribute_id, contentobject_attribute_version)
) IGNORE SELECT * FROM ezurl_object_link;

TRUNCATE TABLE ezurl_object_link;

INSERT INTO ezurl_object_link SELECT * FROM ezurl_object_link_temp;
-- End ezp-21465

-- Start EZP-21469
-- While using the public API, ezcontentobject.language_mask was not updated correctly,
-- the UPDATE statement below fixes that based on the language_mask of the current version.
UPDATE
    ezcontentobject AS o
INNER JOIN
    ezcontentobject_version AS v ON o.id = v.contentobject_id AND o.current_version = v.version
SET
    o.language_mask = (o.language_mask & 1) | (v.language_mask & ~1);
-- End EZP-21469
