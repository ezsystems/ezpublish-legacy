UPDATE ezsite_data SET value='3.5.1' WHERE name='ezpublish-version';
UPDATE ezsite_data SET value='6' WHERE name='ezpublish-release';

--
-- Update 'Design->Look and feel'
--
-- create 'Design' section
INSERT INTO ezsection (id, locale, name, navigation_part_identifier) 
       VALUES (5,'','Design','ezvisualnavigationpart');

-- create 'Design' folder under '1' node in 'Design' section
INSERT INTO ezcontentobject (contentclass_id, current_version, id, is_published, modified, name, owner_id, published, remote_id, section_id, status)
       VALUES (1,1,56,0,1103023132,'Design',14,1103023132,'08799e609893f7aba22f10cb466d9cc8',5,1);

INSERT INTO ezcontentobject_attribute (attribute_original_id, contentclassattribute_id, contentobject_id, data_float, data_int, data_text, data_type_string, id, language_code, sort_key_int, sort_key_string, version)
       VALUES (0,4,56,0,NULL,'Design','ezstring',181,'eng-GB',0,'design',1);
INSERT INTO ezcontentobject_attribute (attribute_original_id, contentclassattribute_id, contentobject_id, data_float, data_int, data_text, data_type_string, id, language_code, sort_key_int, sort_key_string, version)
       VALUES (0,155,56,0,NULL,'','ezstring',182,'eng-GB',0,'',1);
INSERT INTO ezcontentobject_attribute (attribute_original_id, contentclassattribute_id, contentobject_id, data_float, data_int, data_text, data_type_string, id, language_code, sort_key_int, sort_key_string, version)
       VALUES (0,119,56,0,1045487555,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />','ezxmltext',183,'eng-GB',0,'',1);
INSERT INTO ezcontentobject_attribute (attribute_original_id, contentclassattribute_id, contentobject_id, data_float, data_int, data_text, data_type_string, id, language_code, sort_key_int, sort_key_string, version)
       VALUES (0,156,56,0,1045487555,'<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<section xmlns:image=\"http://ez.no/namespaces/ezpublish3/image/\"\n         xmlns:xhtml=\"http://ez.no/namespaces/ezpublish3/xhtml/\"\n         xmlns:custom=\"http://ez.no/namespaces/ezpublish3/custom/\" />','ezxmltext',184,'eng-GB',0,'',1);
INSERT INTO ezcontentobject_attribute (attribute_original_id, contentclassattribute_id, contentobject_id, data_float, data_int, data_text, data_type_string, id, language_code, sort_key_int, sort_key_string, version)
       VALUES (0,158,56,0,1,'','ezboolean',185,'eng-GB',1,'',1);
 
INSERT INTO ezcontentobject_name (content_translation, content_version, contentobject_id, name, real_translation)
       VALUES ('eng-GB',1,56,'Design','eng-GB');
 
INSERT INTO ezcontentobject_tree (contentobject_id, contentobject_is_published, contentobject_version, depth, is_hidden, is_invisible, main_node_id, modified_subnode, node_id, parent_node_id, path_identification_string, path_string, priority, remote_id, sort_field, sort_order)
       VALUES (56,1,1,1,0,0,58,1103023133,58,1,'design','/1/58/',0,'79f2d67372ab56f59b5d65bb9e0ca3b9',2,0);
 
INSERT INTO ezcontentobject_version (contentobject_id, created, creator_id, id, modified, status, user_id, version, workflow_event_pos) 
       VALUES (56,1103023120,14,495,1103023120,1,0,1,0);
 
INSERT INTO eznode_assignment (contentobject_id, contentobject_version, from_node_id, id, is_main, parent_node, parent_remote_id, remote_id, sort_field, sort_order) 
       VALUES (56,1,0,34,1,1,'',0,2,0);
 
INSERT INTO ezurlalias (destination_url, forward_to_id, id, is_internal, is_wildcard, source_md5, source_url)
       VALUES ('content/view/full/58',0,33,1,0,'31c13f47ad87dd7baa2d558a91e0fbb9','design');

-- move 'eZ Publish' object from 'Setup' to 'Design'
UPDATE ezcontentobject_tree SET parent_node_id=58 where node_id=56;
UPDATE ezcontentobject_tree SET path_identification_string='design/ez_publish' where node_id=56;
UPDATE ezcontentobject_tree SET path_string='/1/58/56' where node_id=56;

UPDATE eznode_assignment SET parent_node=58 where contentobject_id=54;

UPDATE ezurlalias SET source_url='design/ez_publish' where id=32;
UPDATE ezurlalias SET source_md5='2dd3db5dc7122ea5f3ee539bb18fe97d' where id=32;

UPDATE ezcontentobject SET section_id=5 where id=54;

-- Fix for tipafriend request functionality
CREATE TABLE eztipafriend_request (
        email_receiver varchar(100) not null,
        created int not null
);
create index eztipafriend_request_email_receiver on eztipafriend_request(email_receiver);
create index eztipafriend_request_created on eztipafriend_request(created);
