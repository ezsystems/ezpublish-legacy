UPDATE ezsite_data SET value='3.6.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

ALTER TABLE ezrss_export_item ADD subnodes INT(11) UNSIGNED DEFAULT '0' NOT NULL;

ALTER TABLE ezrss_export ADD number_of_objects INT(11) UNSIGNED DEFAULT '0' NOT NULL;
-- Old behaviour of RSS was that it fed 5 items
UPDATE ezrss_export SET number_of_objects='5';

ALTER TABLE ezrss_export ADD main_node_only INT(11) UNSIGNED DEFAULT '1' NOT NULL;
-- Old behaviour of RSS was that all nodes have been shown,
-- i.e. including those besides the main node
UPDATE ezrss_export SET main_node_only='0';

ALTER TABLE ezcontentobject_link ADD contentclassattribute_id INT(11) UNSIGNED DEFAULT '0' NOT NULL;
CREATE INDEX ezco_link_to_co_id ON ezcontentobject_link ( to_contentobject_id );
CREATE INDEX ezco_link_from     ON ezcontentobject_link ( from_contentobject_id,
                                                          from_contentobject_version,
                                                          contentclassattribute_id );



-- Add missing index for orders
ALTER TABLE ezorder ADD INDEX ezorder_is_tmp (is_temporary);

-- New feature: Status on orders

ALTER TABLE ezorder ADD status_id int DEFAULT 0;
ALTER TABLE ezorder ADD status_modified int DEFAULT 0;
ALTER TABLE ezorder ADD status_modifier_id int DEFAULT 0;

CREATE TABLE ezorder_status (
    id int(11) NOT NULL auto_increment,
    status_id int(11) NOT NULL DEFAULT 0,
    name varchar(255) NOT NULL DEFAULT '',
    is_active int(11) NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
);

ALTER TABLE ezorder_status ADD INDEX ezorder_status_sid (status_id);
ALTER TABLE ezorder_status ADD INDEX ezorder_status_name (name);
ALTER TABLE ezorder_status ADD INDEX ezorder_status_active (is_active);

INSERT INTO ezorder_status (status_id, name, is_active)
VALUES( 1, 'Pending', 1 );
INSERT INTO ezorder_status (status_id, name, is_active)
VALUES( 2, 'Processing', 1 );
INSERT INTO ezorder_status (status_id, name, is_active)
VALUES( 3, 'Delivered', 1 );

CREATE TABLE ezorder_status_history (
    id int(11) NOT NULL auto_increment,
    order_id int(11) NOT NULL DEFAULT 0,
    status_id int(11) NOT NULL DEFAULT 0,
    modifier_id int(11) NOT NULL DEFAULT 0,
    modified int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
);

ALTER TABLE ezorder_status_history ADD INDEX ezorder_status_history_oid (order_id);
ALTER TABLE ezorder_status_history ADD INDEX ezorder_status_history_sid (status_id);
ALTER TABLE ezorder_status_history ADD INDEX ezorder_status_history_mod (modified);


-- Make sure each order has a history element with Pending status
INSERT INTO ezorder_status_history (order_id, status_id, modifier_id, modified)
SELECT order_nr AS order_id, 1 AS status_id, user_id AS modifier_id, created AS modified FROM ezorder WHERE status_id = 0;
-- Update status of all orders to Pending
UPDATE ezorder SET status_id = 1, status_modifier_id = user_id, status_modified = created WHERE status_id = 0;
