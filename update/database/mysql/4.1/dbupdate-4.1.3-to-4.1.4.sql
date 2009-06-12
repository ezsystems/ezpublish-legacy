SET storage_engine=InnoDB;
UPDATE ezsite_data SET value='4.1.4' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

CREATE INDEX ezkeyword_attr_link_oaid ON ezkeyword_attribute_link( objectattribute_id );
