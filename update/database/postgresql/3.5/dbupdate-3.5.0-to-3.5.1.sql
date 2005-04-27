UPDATE ezsite_data SET value='3.5.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

-- Fix for tipafriend request functionality
CREATE TABLE eztipafriend_request (
        email_receiver varchar(100) not null,
        created int not null
);
create index eztipafriend_request_email_rec on eztipafriend_request(email_receiver);
create index eztipafriend_request_created on eztipafriend_request(created);
