UPDATE ezsite_data SET value='4.7.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ezcollab_item
    ALTER COLUMN data_float1 TYPE real,
    ALTER COLUMN data_float2 TYPE real,
    ALTER COLUMN data_float3 TYPE real,
    ALTER COLUMN data_float1 SET DEFAULT (0)::real,
    ALTER COLUMN data_float2 SET DEFAULT (0)::real,
    ALTER COLUMN data_float3 SET DEFAULT (0)::real;

ALTER TABLE ezcollab_simple_message
    ALTER COLUMN data_float1 TYPE real,
    ALTER COLUMN data_float2 TYPE real,
    ALTER COLUMN data_float3 TYPE real,
    ALTER COLUMN data_float1 SET DEFAULT (0)::real,
    ALTER COLUMN data_float2 SET DEFAULT (0)::real,
    ALTER COLUMN data_float3 SET DEFAULT (0)::real;

ALTER TABLE ezcontentclass_attribute
    ALTER COLUMN data_float1 TYPE real,
    ALTER COLUMN data_float2 TYPE real,
    ALTER COLUMN data_float3 TYPE real,
    ALTER COLUMN data_float4 TYPE real;

ALTER TABLE ezcontentobject_attribute ALTER COLUMN data_float TYPE real;

ALTER TABLE ezdiscountsubrule ALTER COLUMN discount_percent TYPE real;

ALTER TABLE ezinfocollection_attribute ALTER COLUMN data_float TYPE real;

ALTER TABLE ezorder_item
    ALTER COLUMN price TYPE real,
    ALTER COLUMN vat_value TYPE real,
    ALTER COLUMN vat_value SET DEFAULT (0)::real;

ALTER TABLE ezproductcollection_item
    ALTER COLUMN discount TYPE real,
    ALTER COLUMN price TYPE real,
    ALTER COLUMN vat_value TYPE real,
    ALTER COLUMN price SET DEFAULT (0)::real;

ALTER TABLE ezproductcollection_item_opt
    ALTER COLUMN price TYPE real,
    ALTER COLUMN price SET DEFAULT (0)::real;

ALTER TABLE ezsearch_object_word_link
    ALTER COLUMN frequency TYPE real,
    ALTER COLUMN frequency SET DEFAULT (0)::real;

ALTER TABLE ezvattype ALTER COLUMN percentage TYPE real;


CREATE SEQUENCE ezpending_actions_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

ALTER TABLE ezpending_actions ADD id integer DEFAULT nextval('ezpending_actions_s'::text) NOT NULL;
UPDATE ezpending_actions SET id=nextval('ezpending_actions_s'::text);

-- Cleanup for #18886
-- when a user is manually enabled through the admin interface,
-- the corresponding ezuser_accountkey record is not removed
DELETE FROM ezuser_accountkey WHERE user_id IN ( SELECT user_id FROM ezuser_setting WHERE is_enabled = 1 );
