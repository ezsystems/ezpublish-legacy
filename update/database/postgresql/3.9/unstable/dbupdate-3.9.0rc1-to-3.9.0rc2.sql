UPDATE ezsite_data SET value='3.9.0rc2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='4' WHERE name='ezpublish-release';

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


