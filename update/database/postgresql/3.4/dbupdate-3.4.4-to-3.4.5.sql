UPDATE ezsite_data SET value='3.4.5' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='12' WHERE name='ezpublish-release';

CREATE TABLE eztipafriend_request (
        email_receiver varchar(100) not null,
        created int not null
);
create index eztipafriend_request_email_receiver on eztipafriend_request(email_receiver);
create index eztipafriend_request_created on eztipafriend_request(created);
