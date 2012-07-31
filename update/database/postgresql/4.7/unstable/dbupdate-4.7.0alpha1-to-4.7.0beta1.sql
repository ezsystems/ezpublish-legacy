UPDATE ezsite_data SET value='4.7.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcontentobject_attribute ALTER COLUMN data_float TYPE double precision;
ALTER TABLE ezcontentclass_attribute
    ALTER COLUMN data_float1 TYPE double precision,
    ALTER COLUMN data_float2 TYPE double precision,
    ALTER COLUMN data_float3 TYPE double precision,
    ALTER COLUMN data_float4 TYPE double precision;
