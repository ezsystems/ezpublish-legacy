UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

CREATE INDEX ezorder_item_order_id ON ezorder_item( order_id );
CREATE INDEX ezproductcollection_item_productcollection_id ON ezproductcollection_item( productcollection_id );
CREATE INDEX ezurlalias_source_url ON ezurlalias(source_url(255));
CREATE INDEX ezcontentobject_attribute_co_id_ver_lang_code ON ezcontentobject_attribute( contentobject_id, version, language_code);
