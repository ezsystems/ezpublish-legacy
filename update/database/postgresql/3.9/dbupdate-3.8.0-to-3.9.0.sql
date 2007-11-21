UPDATE ezsite_data SET value='3.9.0' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';


-- START: from 3.8.1
CREATE INDEX ezkeyword_keyword_id ON ezkeyword USING btree ( keyword, id );
CREATE INDEX ezkeyword_attr_link_kid_oaid ON ezkeyword_attribute_link USING btree ( keyword_id, objectattribute_id );

CREATE INDEX ezurlalias_is_wildcard ON ezurlalias USING btree ( is_wildcard );

CREATE INDEX eznode_assignment_coid_cov ON eznode_assignment USING btree ( contentobject_id,contentobject_version );
CREATE INDEX eznode_assignment_is_main ON eznode_assignment USING btree ( is_main );
CREATE INDEX eznode_assignment_parent_node ON eznode_assignment USING btree ( parent_node );
-- END: from 3.8.1

ALTER TABLE ezuservisit ADD COLUMN failed_login_attempts int;
ALTER TABLE ezuservisit ALTER COLUMN failed_login_attempts SET DEFAULT 0;
ALTER TABLE ezuservisit ALTER COLUMN failed_login_attempts SET NOT NULL;

ALTER TABLE ezcontentobject_link ADD COLUMN relation_type int;
ALTER TABLE ezcontentobject_link ALTER COLUMN relation_type SET DEFAULT 1;
ALTER TABLE ezcontentobject_link ALTER COLUMN relation_type SET NOT NULL;
UPDATE ezcontentobject_link SET relation_type=8 WHERE contentclassattribute_id<>0;


-- START: 'default sorting' attribute for ezcontentclass

ALTER TABLE ezcontentclass ADD COLUMN sort_field int;
ALTER TABLE ezcontentclass ALTER COLUMN sort_field SET DEFAULT 1;
ALTER TABLE ezcontentclass ALTER COLUMN sort_field SET NOT NULL;

ALTER TABLE ezcontentclass ADD COLUMN sort_order int;
ALTER TABLE ezcontentclass ALTER COLUMN sort_order SET DEFAULT 1;
ALTER TABLE ezcontentclass ALTER COLUMN sort_order SET NOT NULL;

-- END: 'default sorting' attribute for ezcontentclass

-- START: new table for trash
CREATE TABLE ezcontentobject_trash (
    contentobject_id integer,
    contentobject_version integer,
    depth integer DEFAULT 0 NOT NULL,
    is_hidden integer DEFAULT 0 NOT NULL,
    is_invisible integer DEFAULT 0 NOT NULL,
    main_node_id integer,
    modified_subnode integer DEFAULT 0,
    node_id integer DEFAULT 0 NOT NULL,
    parent_node_id integer DEFAULT 0 NOT NULL,
    path_identification_string text,
    path_string character varying(255) DEFAULT ''::character varying NOT NULL,
    priority integer DEFAULT 0 NOT NULL,
    remote_id character varying(100) DEFAULT ''::character varying NOT NULL,
    sort_field integer DEFAULT 1,
    sort_order integer DEFAULT 1
);


CREATE INDEX ezcobj_trash_co_id ON ezcontentobject_trash USING btree (contentobject_id);
CREATE INDEX ezcobj_trash_depth ON ezcontentobject_trash USING btree (depth);
CREATE INDEX ezcobj_trash_p_node_id ON ezcontentobject_trash USING btree (parent_node_id);
CREATE INDEX ezcobj_trash_path ON ezcontentobject_trash USING btree (path_string);
CREATE INDEX ezcobj_trash_path_ident ON ezcontentobject_trash USING btree (path_identification_string);
CREATE INDEX ezcobj_trash_modified_subnode ON ezcontentobject_trash USING btree (modified_subnode);
ALTER TABLE ONLY ezcontentobject_trash ADD CONSTRAINT ezcontentobject_trash_pkey PRIMARY KEY (node_id);
-- END: new table for trash

-- START: ezcontentclass/ezcontentclass_attribute translations
ALTER TABLE ezcontentclass RENAME COLUMN name TO serialized_name_list;
ALTER TABLE ezcontentclass ADD COLUMN language_mask integer;
ALTER TABLE ezcontentclass ALTER language_mask SET NOT NULL;
ALTER TABLE ezcontentclass ALTER language_mask SET DEFAULT 0;
ALTER TABLE ezcontentclass ADD COLUMN initial_language_id integer;
ALTER TABLE ezcontentclass ALTER initial_language_id SET NOT NULL;
ALTER TABLE ezcontentclass ALTER initial_language_id SET DEFAULT 0;
ALTER TABLE ezcontentclass_attribute RENAME COLUMN name TO serialized_name_list;

CREATE TABLE ezcontentclass_name
(
    contentclass_id integer NOT NULL default 0,
    contentclass_version integer NOT NULL default 0,
    language_locale varchar(20) NOT NULL default '',
    language_id integer NOT NULL default 0,
    name varchar(255) NOT NULL default ''
);

ALTER TABLE ONLY ezcontentclass_name
    ADD CONSTRAINT ezcontentclass_name_pkey PRIMARY KEY (contentclass_id, contentclass_version, language_id);
-- END: ezcontentclass/ezcontentclass_attribute translations

-- START: eztipafriend_counter, new column and primary key (new fetch function for tipafriend_top_list)
ALTER TABLE eztipafriend_counter ADD COLUMN requested integer;
ALTER TABLE eztipafriend_counter ALTER requested SET NOT NULL;
ALTER TABLE eztipafriend_counter ALTER requested SET DEFAULT 0;

ALTER TABLE eztipafriend_counter DROP CONSTRAINT eztipafriend_counter_pkey;
ALTER TABLE ONLY eztipafriend_counter ADD CONSTRAINT eztipafriend_counter_pkey PRIMARY KEY( node_id, requested );
-- END: eztipafriend_counter, new column and primary key (new fetch function for tipafriend_top_list)

-- START: improvements in shop(better vat handling of order items, like shipping)
ALTER TABLE ezorder_item ADD COLUMN is_vat_inc integer;
ALTER TABLE ezorder_item ALTER is_vat_inc SET NOT NULL;
ALTER TABLE ezorder_item ALTER is_vat_inc SET default 0;
-- END: improvements in shop(better vat handling of order items, like shipping)



-- START: from 3.8.5
-- ezcontentobject
CREATE INDEX ezcontentobject_pub ON ezcontentobject USING btree ( published );
CREATE INDEX ezcontentobject_status ON ezcontentobject USING btree ( status );
CREATE INDEX ezcontentobject_classid ON ezcontentobject USING btree ( contentclass_id );
CREATE INDEX ezcontentobject_currentversion ON ezcontentobject USING btree ( current_version );

-- ezcontentobject_name
CREATE INDEX ezcontentobject_name_lang_id ON ezcontentobject_name USING btree ( language_id );
CREATE INDEX ezcontentobject_name_name ON ezcontentobject_name USING btree ( name );
CREATE INDEX ezcontentobject_name_co_id ON ezcontentobject_name USING btree ( contentobject_id );
CREATE INDEX ezcontentobject_name_cov_id ON ezcontentobject_name USING btree ( content_version );

-- ezcontentobject_version
CREATE INDEX ezcobj_version_creator_id ON ezcontentobject_version USING btree ( creator_id );
CREATE INDEX ezcobj_version_status ON ezcontentobject_version USING btree ( status );

-- ezpolicy_limitation_value
CREATE INDEX ezpolicy_limitation_value_val ON ezpolicy_limitation_value USING btree ( value );

-- ezinfocollection_attribute
CREATE INDEX ezinfocollection_attr_co_id ON ezinfocollection_attribute USING btree ( contentobject_id );

-- ezurlalias
CREATE INDEX ezurlalias_forward_to_id ON ezurlalias USING btree ( forward_to_id );

-- ezkeyword
CREATE INDEX ezkeyword_keyword ON ezkeyword USING btree ( keyword );

-- ezurl
CREATE INDEX ezurl_url ON ezurl USING btree ( url );

-- ezcontentobject_attribute
CREATE INDEX ezcontentobject_attr_id ON ezcontentobject_attribute USING btree ( id );

-- ezcontentoclass_attribute
CREATE INDEX ezcontentclass_attr_ccid ON ezcontentclass_attribute USING btree ( contentclass_id );

-- eznode_assignment
CREATE INDEX eznode_assignment_co_id ON eznode_assignment USING btree ( contentobject_id );
CREATE INDEX eznode_assignment_co_version ON eznode_assignment USING btree ( contentobject_version );

-- ezkeyword_attribute_link
CREATE INDEX ezkeyword_attr_link_keyword_id ON ezkeyword_attribute_link USING btree ( keyword_id );
-- END: from 3.8.5


CREATE INDEX  ezsearch_return_cnt_ph_id_count  ON   ezsearch_return_count ( phrase_id, count );
-- alter table ezsearch_return_count add key ( phrase_id, count );
CREATE INDEX ezsearch_search_phrase_phr ON ezsearch_search_phrase ( phrase );
-- alter table ezsearch_search_phrase add key ( phrase );
CREATE SEQUENCE ezsearch_search_phrase_new_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezsearch_search_phrase_new (
  id int DEFAULT nextval('ezsearch_search_phrase_new_s'::text) PRIMARY KEY,
  phrase varchar(250) default NULL,
  phrase_count int default 0,
  result_count int default 0
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
CREATE SEQUENCE ezsearch_return_count_new_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezsearch_return_count_new (
  id int NOT NULL DEFAULT nextval('ezsearch_search_phrase_new_s'::text) PRIMARY KEY,
  phrase_id int NOT NULL default 0,
  time int NOT NULL default 0,
  count int NOT NULL default 0
);
CREATE INDEX  ezsearch_return_cnt_new_ph_id_cnt  ON  ezsearch_return_count_new ( phrase_id, count );

INSERT INTO ezsearch_return_count_new ( phrase_id, time, count )
SELECT    ezsearch_search_phrase_new.id, time, count
FROM      ezsearch_search_phrase,
          ezsearch_search_phrase_new,
          ezsearch_return_count
WHERE     ezsearch_search_phrase_new.phrase = LOWER( ezsearch_search_phrase.phrase ) AND
          ezsearch_search_phrase.id = ezsearch_return_count.phrase_id;

-- final tasks with and without BC
DROP TABLE ezsearch_search_phrase;
--ALTER TABLE ezsearch_search_phrase RENAME TO ezsearch_search_phrase_old;
ALTER TABLE ezsearch_search_phrase_new RENAME TO ezsearch_search_phrase;

DROP TABLE ezsearch_return_count;
-- ALTER TABLE ezsearch_return_count RENAME TO ezsearch_return_count_old;
-- of course the next statement is only valid if you created `ezsearch_return_count_new`
ALTER TABLE ezsearch_return_count_new RENAME TO ezsearch_return_count;
ALTER TABLE  ezsearch_return_count drop  constraint ezsearch_return_count_new_pkey;
ALTER TABLE ezsearch_return_count ADD PRIMARY KEY(id);
ALTER TABLE ezsearch_search_phrase  drop  constraint ezsearch_search_phrase_new_pkey;
ALTER TABLE ezsearch_search_phrase  ADD PRIMARY KEY(id);
DROP  INDEX ezsearch_return_cnt_new_ph_id_cnt;
CREATE INDEX  ezsearch_return_cnt_ph_id_cnt  ON   ezsearch_return_count ( phrase_id, count );


