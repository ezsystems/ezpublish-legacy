UPDATE ezsite_data SET value='3.9.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';

-- START: from 3.8.1
CREATE INDEX ezkeyword_keyword_id ON ezkeyword ( keyword, id );
CREATE INDEX ezkeyword_attr_link_kid_oaid ON ezkeyword_attribute_link ( keyword_id, objectattribute_id );

CREATE INDEX ezurlalias_is_wildcard ON ezurlalias( is_wildcard );

CREATE INDEX eznode_assignment_coid_cov ON eznode_assignment( contentobject_id,contentobject_version );
CREATE INDEX eznode_assignment_is_main ON eznode_assignment( is_main );
CREATE INDEX eznode_assignment_parent_node ON eznode_assignment( parent_node );
-- END: from 3.8.1

ALTER TABLE ezuservisit ADD COLUMN failed_login_attempts int NOT NULL DEFAULT 0;

ALTER TABLE ezcontentobject_link ADD COLUMN relation_type int NOT NULL DEFAULT 1;
UPDATE ezcontentobject_link SET relation_type=8 WHERE contentclassattribute_id<>0;

-- START: 'default sorting' attribute for ezcontentclass
ALTER TABLE ezcontentclass ADD COLUMN sort_field int NOT NULL DEFAULT 1;
ALTER TABLE ezcontentclass ADD COLUMN sort_order int NOT NULL DEFAULT 1;
-- END: 'default sorting' attribute for ezcontentclass

-- START: new table for trash
CREATE TABLE ezcontentobject_trash (
  contentobject_id int(11) default NULL,
  contentobject_version int(11) default NULL,
  depth int(11) NOT NULL default '0',
  is_hidden int(11) NOT NULL default '0',
  is_invisible int(11) NOT NULL default '0',
  main_node_id int(11) default NULL,
  modified_subnode int(11) default '0',
  node_id int(11) NOT NULL,
  parent_node_id int(11) NOT NULL default '0',
  path_identification_string longtext,
  path_string varchar(255) NOT NULL default '',
  priority int(11) NOT NULL default '0',
  remote_id varchar(100) NOT NULL default '',
  sort_field int(11) default '1',
  sort_order int(11) default '1',
  PRIMARY KEY  (node_id)
);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_co_id (contentobject_id);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_depth (depth);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_p_node_id (parent_node_id);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_path (path_string);
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_path_ident (path_identification_string(50));
ALTER TABLE ezcontentobject_trash ADD INDEX ezcobj_trash_modified_subnode (modified_subnode);
-- END: new table for trash

-- START: ezcontentclass/ezcontentclass_attribute translations
ALTER TABLE ezcontentclass CHANGE name serialized_name_list varchar(255) default NULL;
ALTER TABLE ezcontentclass ADD COLUMN language_mask int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentclass ADD COLUMN initial_language_id int NOT NULL DEFAULT 0;
ALTER TABLE ezcontentclass_attribute CHANGE name serialized_name_list varchar(255) NOT NULL default '';

CREATE TABLE ezcontentclass_name
(
    contentclass_id int NOT NULL default '0',
    contentclass_version int NOT NULL default '0',
    language_locale varchar(20) NOT NULL default '',
    language_id int NOT NULL default '0',
    name varchar(255) NOT NULL default '',
    PRIMARY KEY (contentclass_id, contentclass_version, language_id)
);
-- END: ezcontentclass/ezcontentclass_attribute translations

-- START: eztipafriend_counter, new column and primary key (new fetch function for tipafriend_top_list)
ALTER TABLE eztipafriend_counter ADD COLUMN requested int NOT NULL DEFAULT 0;
ALTER TABLE eztipafriend_counter DROP PRIMARY KEY;
ALTER TABLE eztipafriend_counter ADD PRIMARY KEY (node_id, requested);
-- END: eztipafriend_counter, new column and primary key (new fetch function for tipafriend_top_list)

-- START: improvements in shop(better vat handling of order items, like shipping)
ALTER TABLE ezorder_item ADD COLUMN is_vat_inc int default '0' NOT NULL;
-- END: improvements in shop(better vat handling of order items, like shipping)

-- START: from 3.8.5
-- ezcontentobject
CREATE INDEX ezcontentobject_pub ON ezcontentobject( published );
CREATE INDEX ezcontentobject_status ON ezcontentobject( status );
CREATE INDEX ezcontentobject_classid ON ezcontentobject( contentclass_id );
CREATE INDEX ezcontentobject_currentversion ON ezcontentobject( current_version );

-- ezcontentobject_name
CREATE INDEX ezcontentobject_name_lang_id ON ezcontentobject_name( language_id );
CREATE INDEX ezcontentobject_name_name ON ezcontentobject_name( name );
CREATE INDEX ezcontentobject_name_co_id ON ezcontentobject_name( contentobject_id );
CREATE INDEX ezcontentobject_name_cov_id ON ezcontentobject_name( content_version );

-- ezcontentobject_version
CREATE INDEX ezcobj_version_creator_id ON ezcontentobject_version( creator_id );
CREATE INDEX ezcobj_version_status ON ezcontentobject_version( status );

-- ezpolicy_limitation_value
CREATE INDEX ezpolicy_limitation_value_val ON ezpolicy_limitation_value( value );

-- ezinfocollection_attribute
CREATE INDEX ezinfocollection_attr_co_id ON ezinfocollection_attribute( contentobject_id );

-- ezurlalias
CREATE INDEX ezurlalias_forward_to_id ON ezurlalias( forward_to_id );

-- ezkeyword
CREATE INDEX ezkeyword_keyword ON ezkeyword( keyword );

-- ezurl
CREATE INDEX ezurl_url ON ezurl( url );

-- ezcontentobject_attribute
CREATE INDEX ezcontentobject_attr_id ON ezcontentobject_attribute( id );

-- ezcontentoclass_attribute
CREATE INDEX ezcontentclass_attr_ccid ON ezcontentclass_attribute( contentclass_id );

-- eznode_assignment
CREATE INDEX eznode_assignment_co_id ON eznode_assignment( contentobject_id );
CREATE INDEX eznode_assignment_co_version ON eznode_assignment( contentobject_version );

-- ezkeyword_attribute_link
CREATE INDEX ezkeyword_attr_link_keyword_id ON ezkeyword_attribute_link( keyword_id );
-- END: from 3.8.5


-- alter table ezsearch_return_count add key ( phrase_id, count );
-- alter table ezsearch_search_phrase add key ( phrase );
CREATE INDEX  ezsearch_return_cnt_new_ph_id_count  ON   ezsearch_return_count ( phrase_id, count );
CREATE INDEX ezsearch_search_phrase_phr ON ezsearch_search_phrase ( phrase );

CREATE TABLE `ezsearch_search_phrase_new` (
  `id` int(11) NOT NULL auto_increment PRIMARY KEY ,
  `phrase` varchar(250) default NULL,
  `phrase_count` int(11) default '0',
  `result_count` int(11) default '0'
);
CREATE UNIQUE INDEX ezsearch_search_phrase_phrase ON ezsearch_search_phrase_new ( phrase );
CREATE INDEX ezsearch_search_phrase_count ON ezsearch_search_phrase_new ( phrase_count );


INSERT INTO ezsearch_search_phrase_new ( phrase, phrase_count, result_count )
SELECT   lower( phrase ), count(*), sum( ezsearch_return_count.count )
FROM     ezsearch_search_phrase,
         ezsearch_return_count
WHERE    ezsearch_search_phrase.id = ezsearch_return_count.phrase_id
GROUP BY lower( ezsearch_search_phrase.phrase );

-- ezsearch_return_count is of no (additional) use in a normal eZ Publish installation
-- but perhaps someone built something for himself, then it is not BC
-- to not break BC apply the CREATE and INSERT statements

CREATE TABLE `ezsearch_return_count_new` (
  `id` int(11) NOT NULL auto_increment   PRIMARY KEY,
  `phrase_id` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0'
);
CREATE INDEX  ezsearch_return_cnt_ph_id_cnt  ON   ezsearch_return_count_new ( phrase_id, count );

INSERT INTO `ezsearch_return_count_new` ( phrase_id, time, `count` )
SELECT    ezsearch_search_phrase_new.id, time, `count`
FROM      ezsearch_search_phrase,
          ezsearch_search_phrase_new,
          ezsearch_return_count
WHERE     ezsearch_search_phrase_new.phrase = LOWER( ezsearch_search_phrase.phrase ) AND
          ezsearch_search_phrase.id = ezsearch_return_count.phrase_id;

-- final tasks with and without BC
DROP TABLE ezsearch_search_phrase;
-- ALTER TABLE ezsearch_search_phrase RENAME TO ezsearch_search_phrase_old;
ALTER TABLE ezsearch_search_phrase_new RENAME TO ezsearch_search_phrase;

DROP TABLE `ezsearch_return_count`;
-- ALTER TABLE ezsearch_return_count RENAME TO ezsearch_return_count_old;
-- of course the next statement is only valid if you created `ezsearch_return_count_new`
ALTER TABLE ezsearch_return_count_new RENAME TO ezsearch_return_count;


