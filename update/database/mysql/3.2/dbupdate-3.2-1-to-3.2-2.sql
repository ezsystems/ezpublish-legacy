UPDATE ezsite_data SET value='2' WHERE name='ezpublish-release';

CREATE INDEX ezproductcollection_item_opt_item_id ON  ezproductcollection_item_opt(item_id);
CREATE INDEX ezproductcollection_item_contentobject_id ON ezproductcollection_item(productcollection_id);
CREATE INDEX ezbasket_session_id ON ezbasket(session_id);
CREATE INDEX ezoperation_memento_memento_key_main ON ezoperation_memento(memento_key,main);
CREATE INDEX eztrigger_fetch ON eztrigger(name(25),module_name(50),function_name(50));
CREATE INDEX ezworkflow_process_process_key ON ezworkflow_process(process_key);
CREATE INDEX ezurlalias_desturl ON ezurlalias( destination_url(200) );

ALTER TABLE ezurlalias ADD COLUMN is_wildcard integer NOT NULL DEFAULT 0;
