UPDATE ezsite_data SET value='3.6.0alpha1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE TABLE eztipafriend_request (
        email_receiver varchar(100) not null,
        created int not null
);
create index email_receiver on eztipafriend_request(email_receiver);
create index created on eztipafriend_request(created);

ALTER TABLE ezrss_export_item ADD COLUMN subnodes integer;
ALTER TABLE ezrss_export_item ALTER subnodes SET NOT NULL;
ALTER TABLE ezrss_export_item ALTER subnodes SET DEFAULT 0;

ALTER TABLE ezrss_export ADD COLUMN number_of_objects integer;
ALTER TABLE ezrss_export ALTER number_of_objects SET NOT NULL;
ALTER TABLE ezrss_export ALTER number_of_objects SET DEFAULT 0;
-- Old behaviour of RSS was that it fed 5 items
UPDATE ezrss_export SET number_of_objects='5';

ALTER TABLE ezrss_export ADD COLUMN main_node_only integer;
ALTER TABLE ezrss_export ALTER main_node_only SET NOT NULL;
ALTER TABLE ezrss_export ALTER main_node_only SET DEFAULT 1;
-- Old behaviour of RSS was that all nodes have been shown,
-- i.e. including those besides the main node
UPDATE ezrss_export SET main_node_only='1';

ALTER TABLE ezcontentobject_link ADD contentclassattribute_id INT;
ALTER TABLE ezcontentobject_link ALTER COLUMN contentclassattribute_id SET DEFAULT 0;
ALTER TABLE ezcontentobject_link ALTER COLUMN contentclassattribute_id SET NOT NULL;
CREATE INDEX ezco_link_to_co_id ON ezcontentobject_link ( to_contentobject_id );
CREATE INDEX ezco_link_from     ON ezcontentobject_link ( from_contentobject_id,
                                                          from_contentobject_version,
                                                          contentclassattribute_id );

