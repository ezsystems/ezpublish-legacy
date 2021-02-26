--
-- EZP-28881: Add a field to support "date object was trashed"
--

ALTER TABLE ezcontentobject_trash add trashed int(11) NOT NULL DEFAULT '0';