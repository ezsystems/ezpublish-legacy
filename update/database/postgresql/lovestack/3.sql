--
-- EZP-28881: Add a field to support "date object was trashed"
--

ALTER TABLE ezcontentobject_trash add  trashed integer DEFAULT 0 NOT NULL;