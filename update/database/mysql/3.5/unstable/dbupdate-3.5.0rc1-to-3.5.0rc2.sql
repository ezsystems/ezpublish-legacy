UPDATE ezsite_data SET value='3.5.0rc2' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='4' WHERE name='ezpublish-release';

-- Reduce the total size of the index eztrigger_def_id to make it work with utf-8
ALTER TABLE eztrigger DROP INDEX eztrigger_def_id;
ALTER TABLE eztrigger ADD UNIQUE INDEX eztrigger_def_id (module_name( 50 ),function_name( 50 ),connect_type);
