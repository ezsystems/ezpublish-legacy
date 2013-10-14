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

-- Start ezp-21465 : Cleanup extra lines in the ezurl_object_link table
DELETE
FROM ezurl_object_link AS T1
WHERE  T1.url_id < ANY (
  SELECT DISTINCT url_id
  FROM  ezurl_object_link T2 JOIN ezcontentobject_attribute ON T2.contentobject_attribute_id = ezcontentobject_attribute.id
  WHERE T1.url_id < T2.url_id
  AND T1.contentobject_attribute_id = T2.contentobject_attribute_id
  AND T1.contentobject_attribute_version = T2.contentobject_attribute_version
  AND ezcontentobject_attribute.data_type_string = 'ezurl');
-- End ezp-21465

-- Start EZP-21469
-- While using the public API, ezcontentobject.language_mask was not updated correctly,
-- the UPDATE statement below fixes that based on the language_mask of the current version.
UPDATE
    ezcontentobject AS o
SET
    language_mask = (o.language_mask & 1) | (v.language_mask & ~1)
FROM
    ezcontentobject_version AS v
WHERE
    o.id = v.contentobject_id AND o.current_version = v.version;
-- End EZP-21469

-- Start EZP-21648:
-- Adding 'priority' and 'is_hidden' columns to the 'eznode_assignment' table
ALTER TABLE eznode_assignment ADD priority integer DEFAULT 0 NOT NULL;
ALTER TABLE eznode_assignment ADD is_hidden integer DEFAULT 0 NOT NULL;
-- End EZP-21648
