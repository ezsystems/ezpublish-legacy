SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='5.4.0alpha1' WHERE name='ezpublish-version';

DROP TABLE ezsearch_return_count;

UPDATE ezcontentobject_attribute
    SET data_int = NULL
WHERE
    data_int = 0
    AND data_type_string IN ( 'ezdate', 'ezdatetime' );

UPDATE ezinfocollection_attribute, ezcontentclass_attribute
    SET ezinfocollection_attribute.data_int = NULL
WHERE
    ezcontentclass_attribute.id = ezinfocollection_attribute.contentclass_attribute_id
    AND ezinfocollection_attribute.data_int = 0
    AND ezcontentclass_attribute.data_type_string IN ( 'ezdate', 'ezdatetime' );