UPDATE ezsite_data SET value='4.7.0rc1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='1' WHERE name='ezpublish-release';

ALTER TABLE ONLY ezpending_actions
    ADD CONSTRAINT ezpending_actions_pkey PRIMARY KEY (id);

UPDATE eztrigger SET name = 'pre_updatemainassignment', function_name = 'updatemainassignment'
  WHERE name = 'pre_UpdateMainAssignment' AND function_name = 'UpdateMainAssignment';
