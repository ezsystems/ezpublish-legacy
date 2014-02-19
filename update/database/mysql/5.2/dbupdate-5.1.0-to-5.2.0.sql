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

-- create a temporary table containing stale links
CREATE TEMPORARY TABLE ezurl_object_link_temp
SELECT DISTINCT contentobject_attribute_id, contentobject_attribute_version, url_id
FROM ezurl_object_link AS T1 JOIN ezcontentobject_attribute ON T1.contentobject_attribute_id = ezcontentobject_attribute.id
WHERE ezcontentobject_attribute.data_type_string = "ezurl"
AND T1.url_id < ANY
  (SELECT DISTINCT T2.url_id
  FROM ezurl_object_link T2
  WHERE T1.url_id < T2.url_id
  AND T1.contentobject_attribute_id = T2.contentobject_attribute_id
  AND T1.contentobject_attribute_version = T2.contentobject_attribute_version);

SET @OLD_SQL_SAFE_UPDATES=@@SQL_SAFE_UPDATES;
SET SQL_SAFE_UPDATES=0;

DELETE ezurl_object_link.*
FROM ezurl_object_link JOIN ezurl_object_link_temp ON ezurl_object_link.url_id = ezurl_object_link_temp.url_id
AND ezurl_object_link.contentobject_attribute_id = ezurl_object_link_temp.contentobject_attribute_id
AND ezurl_object_link.contentobject_attribute_version = ezurl_object_link_temp.contentobject_attribute_version;

SET SQL_SAFE_UPDATES=@OLD_SQL_SAFE_UPDATES;
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

-- Start EZP-21648:
-- Adding 'priority' and 'is_hidden' columns to the 'eznode_assignment' table
ALTER TABLE eznode_assignment ADD COLUMN priority int(11) NOT NULL DEFAULT '0';
ALTER TABLE eznode_assignment ADD COLUMN is_hidden int(11) NOT NULL DEFAULT '0';
-- End EZP-21648
