UPDATE ezsite_data SET value='3.5.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

alter table ezrole add column is_new integer not null default 0;

-- New name for ezsearch index, the old one crashed with the table name ezsearch_word
ALTER TABLE ezsearch_word DROP INDEX ezsearch_word;
ALTER TABLE ezsearch_word ADD INDEX ezsearch_word_word_i ( word );

 -- ezpdf_export
 -- Added support for versioning (class-type)

CREATE TABLE tmp_ezpdf_export (
    created int(11) default NULL,
    creator_id int(11) default NULL,
    export_classes varchar(255) default NULL,
    export_structure varchar(255) default NULL,
    id int(11) NOT NULL auto_increment,
    intro_text longtext,
    modified int(11) default NULL,
    modifier_id int(11) default NULL,
    pdf_filename varchar(255) default NULL,
    show_frontpage int(11) default NULL,
    site_access varchar(255) default NULL,
    source_node_id int(11) default NULL,
    status int(11) default NULL,
    sub_text longtext,
    title varchar(255) default NULL,
    version int(11) NOT NULL default '0',
    PRIMARY KEY (id,version)
) TYPE=MyISAM;

INSERT INTO tmp_ezpdf_export( created, creator_id, export_classes, export_structure, id, intro_text, modified, modifier_id,
    pdf_filename, show_frontpage, site_access, source_node_id, status, sub_text, title )
SELECT created, creator_id, export_classes, export_structure, id, intro_text, modified, modifier_id,
    pdf_filename, show_frontpage, site_access, source_node_id, status, sub_text, title
FROM ezpdf_export;

DROP TABLE ezpdf_export;

ALTER TABLE tmp_ezpdf_export RENAME TO ezpdf_export;

 -- ezrss_import
 -- Added support for versioning (class-type) by reusing status attribute

CREATE TABLE tmp_ezrss_import (
    active int(11) default NULL,
    class_description varchar(255) default NULL,
    class_id int(11) default NULL,
    class_title varchar(255) default NULL,
    class_url varchar(255) default NULL,
    created int(11) default NULL,
    creator_id int(11) default NULL,
    destination_node_id int(11) default NULL,
    id int(11) NOT NULL auto_increment,
    modified int(11) default NULL,
    modifier_id int(11) default NULL,
    name varchar(255) default NULL,
    object_owner_id int(11) default NULL,
    status int(11) NOT NULL default '0',
    url longtext,
    PRIMARY KEY (id,status)
) TYPE=MyISAM;

INSERT INTO tmp_ezrss_import( active, class_description, class_id, class_title, class_url, created, creator_id,
    destination_node_id, id, modified, modifier_id, name, object_owner_id, status, url )
SELECT active, class_description, class_id, class_title, class_url, created, creator_id,
    destination_node_id, id, modified, modifier_id, name, object_owner_id, status, url
FROM ezrss_import;

DROP TABLE ezrss_import;

ALTER TABLE tmp_ezrss_import RENAME TO ezrss_import;

 -- ezrss_export
 -- Added support for versioning (class-type) by reusing status attribute

CREATE TABLE tmp_ezrss_export (
    access_url varchar(255) default NULL,
    active int(11) default NULL,
    created int(11) default NULL,
    creator_id int(11) default NULL,
    description longtext,
    id int(11) NOT NULL auto_increment,
    image_id int(11) default NULL,
    modified int(11) default NULL,
    modifier_id int(11) default NULL,
    rss_version varchar(255) default NULL,
    site_access varchar(255) default NULL,
    status int(11) NOT NULL default '0',
    title varchar(255) default NULL,
    url varchar(255) default NULL,
    PRIMARY KEY (id,status)
) TYPE=MyISAM;

INSERT INTO tmp_ezrss_export( access_url, active, created, creator_id, description, id, image_id, modified,
    modifier_id, rss_version, site_access, status, title, url )
SELECT access_url, active, created, creator_id, description, id, image_id, modified,
    modifier_id, rss_version, site_access, status, title, url
FROM ezrss_export;

DROP TABLE ezrss_export;

ALTER TABLE tmp_ezrss_export RENAME TO ezrss_export;

 -- ezrss_export_item
 -- Added support for versioning (class-type) by introducing status attribute

CREATE TABLE tmp_ezrss_export_item (
    class_id int(11) default NULL,
    description varchar(255) default NULL,
    id int(11) NOT NULL auto_increment,
    rssexport_id int(11) default NULL,
    source_node_id int(11) default NULL,
    status int(11) NOT NULL default '0',
    title varchar(255) default NULL,
    PRIMARY KEY (id,status),
    KEY ezrss_export_rsseid (rssexport_id)
) TYPE=MyISAM;

INSERT INTO tmp_ezrss_export_item( class_id, description, id, rssexport_id, source_node_id, title )
SELECT class_id, description, id, rssexport_id, source_node_id, title
FROM ezrss_export_item;

UPDATE tmp_ezrss_export_item SET status='1';

DROP TABLE ezrss_export_item;

ALTER TABLE tmp_ezrss_export_item RENAME TO ezrss_export_item;

 -- ezproductcollection_item
 -- Added attribute name for storing a product name

ALTER TABLE ezproductcollection_item ADD COLUMN name VARCHAR(255) NOT NULL DEFAULT '';
UPDATE ezproductcollection_item SET name='Unknown product';
