UPDATE ezsite_data SET value='3.4.0alpha3' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='3' WHERE name='ezpublish-release';

---
---  Add remote_id to ezcontent class
---

ALTER TABLE ezcontentclass ADD COLUMN remote_id varchar(100) NOT NULL default '';

---
--- Add remote_id to ezcontentobject_tree
---

ALTER TABLE ezcontentobject_tree ADD COLUMN remote_id varchar(100) NOT NULL default '';

---
--- Add node_remote_id to eznode_assigment
---

ALTER TABLE eznode_assignment ADD COLUMN node_remote_id varchar(100) NOT NULL default '';
