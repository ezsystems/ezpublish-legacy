UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE INDEX ezorder_item_order_id ON ezorder_item( order_id );
CREATE INDEX ezproductcollection_item_productcollection_id ON ezproductcollection_item( productcollection_id );
