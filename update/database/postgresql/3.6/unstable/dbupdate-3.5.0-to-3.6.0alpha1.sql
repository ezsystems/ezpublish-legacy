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
# Old behaviour of RSS was that it fed 5 items
UPDATE ezrss_export SET number_of_objects='5';
