UPDATE ezsite_data SET value='3.4.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='7' WHERE name='ezpublish-release';

-- incrementing size of 'sort_key_string' to 255 characters
CREATE LOCAL TEMPORARY TABLE temp_sort_key_string
(
    id               integer NOT NULL,
    sort_key_string  character varying(50) NOT NULL DEFAULT ''::character varying
);

INSERT INTO temp_sort_key_string SELECT id, sort_key_string FROM ezcontentobject_attribute;
ALTER TABLE ezcontentobject_attribute DROP COLUMN sort_key_string;
ALTER TABLE ezcontentobject_attribute ADD COLUMN sort_key_string character varying(255);
ALTER TABLE ezcontentobject_attribute ALTER COLUMN sort_key_string SET NOT NULL;
ALTER TABLE ezcontentobject_attribute ALTER COLUMN sort_key_string SET DEFAULT ''::character varying;
UPDATE ezcontentobject_attribute SET sort_key_string=temp_sort_key_string.sort_key_string WHERE temp_sort_key_string.id=ezcontentobject_attribute.id;
CREATE INDEX sort_key_string367 ON ezcontentobject_attribute USING btree (sort_key_string);
DROP TABLE temp_sort_key_string;

