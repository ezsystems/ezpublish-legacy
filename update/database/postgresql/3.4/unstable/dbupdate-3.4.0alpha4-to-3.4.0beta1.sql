UPDATE ezsite_data SET value='3.4.0beta1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='5' WHERE name='ezpublish-release';

ALTER TABLE eznode_assignment RENAME COLUMN parent_remote_id TO parent_remote_id_tmp;
ALTER TABLE eznode_assignment ADD COLUMN parent_remote_id character varying(100) ;
ALTER TABLE eznode_assignment ALTER parent_remote_id SET DEFAULT '' ;
UPDATE eznode_assignment SET parent_remote_id='';
ALTER TABLE eznode_assignment ALTER parent_remote_id SET NOT NULL ;
UPDATE eznode_assignment SET parent_remote_id=parent_remote_id_tmp;
ALTER TABLE eznode_assignment DROP COLUMN parent_remote_id_tmp;
