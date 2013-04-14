-- Fixes issue #014670: Embed relations are not removed when embed tag is
-- Affects all users that have used ezoe  (image / file upload) from its early betas, rc's and 5.0.0
DELETE FROM ezcontentobject_link WHERE relation_type = 8 AND contentclassattribute_id = 0;
UPDATE ezcontentobject_link SET relation_type = 2 WHERE relation_type = 10 AND contentclassattribute_id = 0;