CREATE TABLE ezapprove_items (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	workflow_process_id int(11) NOT NULL ,
	collaboration_id int(11) NOT NULL 
);


CREATE TABLE ezbasket (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	session_id varchar(255) NOT NULL DEFAULT '' ,
	productcollection_id int(11) NOT NULL 
);


CREATE TABLE ezbinaryfile (
	contentobject_attribute_id int(11) NOT NULL ,
	version int(11) NOT NULL ,
	filename varchar(255) NOT NULL DEFAULT '' ,
	original_filename varchar(255) NOT NULL DEFAULT '' ,
	mime_type varchar(50) NOT NULL DEFAULT '' 
);
ALTER TABLE ezbinaryfile ADD PRIMARY KEY ( contentobject_attribute_id, version );


CREATE TABLE ezcollab_group (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	parent_group_id int(11) NOT NULL ,
	depth int(11) NOT NULL ,
	path_string varchar(255) NOT NULL DEFAULT '' ,
	is_open int(11) NOT NULL DEFAULT '1' ,
	user_id int(11) NOT NULL ,
	title varchar(255) NOT NULL DEFAULT '' ,
	priority int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezcollab_item (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	type_identifier varchar(40) NOT NULL DEFAULT '' ,
	creator_id int(11) NOT NULL ,
	status int(11) NOT NULL DEFAULT '1' ,
	data_text1 text NOT NULL ,
	data_text2 text NOT NULL ,
	data_text3 text NOT NULL ,
	data_int1 int(11) NOT NULL ,
	data_int2 int(11) NOT NULL ,
	data_int3 int(11) NOT NULL ,
	data_float1 float NOT NULL DEFAULT '0' ,
	data_float2 float NOT NULL DEFAULT '0' ,
	data_float3 float NOT NULL DEFAULT '0' ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezcollab_item_group_link (
	collaboration_id int(11) NOT NULL ,
	group_id int(11) NOT NULL ,
	user_id int(11) NOT NULL ,
	is_read int(11) NOT NULL ,
	is_active int(11) NOT NULL DEFAULT '1' ,
	last_read int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);
ALTER TABLE ezcollab_item_group_link ADD PRIMARY KEY ( collaboration_id, group_id, user_id );


CREATE TABLE ezcollab_item_message_link (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	collaboration_id int(11) NOT NULL ,
	participant_id int(11) NOT NULL ,
	message_id int(11) NOT NULL ,
	message_type int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezcollab_item_participant_link (
	collaboration_id int(11) NOT NULL ,
	participant_id int(11) NOT NULL ,
	participant_type int(11) NOT NULL DEFAULT '1' ,
	participant_role int(11) NOT NULL DEFAULT '1' ,
	is_read int(11) NOT NULL ,
	is_active int(11) NOT NULL DEFAULT '1' ,
	last_read int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);
ALTER TABLE ezcollab_item_participant_link ADD PRIMARY KEY ( collaboration_id, participant_id );


CREATE TABLE ezcollab_item_status (
	collaboration_id int(11) NOT NULL ,
	user_id int(11) NOT NULL ,
	is_read int(11) NOT NULL ,
	is_active int(11) NOT NULL DEFAULT '1' ,
	last_read int(11) NOT NULL 
);
ALTER TABLE ezcollab_item_status ADD PRIMARY KEY ( collaboration_id, user_id );


CREATE TABLE ezcollab_notification_rule (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id varchar(255) NOT NULL DEFAULT '' ,
	collab_identifier varchar(255) NOT NULL DEFAULT '' 
);


CREATE TABLE ezcollab_profile (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	main_group int(11) NOT NULL ,
	data_text1 text NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezcollab_simple_message (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	message_type varchar(40) NOT NULL DEFAULT '' ,
	creator_id int(11) NOT NULL ,
	data_text1 text NOT NULL ,
	data_text2 text NOT NULL ,
	data_text3 text NOT NULL ,
	data_int1 int(11) NOT NULL ,
	data_int2 int(11) NOT NULL ,
	data_int3 int(11) NOT NULL ,
	data_float1 float NOT NULL DEFAULT '0' ,
	data_float2 float NOT NULL DEFAULT '0' ,
	data_float3 float NOT NULL DEFAULT '0' ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezcontent_translation (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) NOT NULL DEFAULT '' ,
	locale varchar(255) NOT NULL DEFAULT '' 
);


CREATE TABLE ezcontentbrowsebookmark (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	node_id int(11) NOT NULL ,
	name varchar(255) NOT NULL DEFAULT '' 
);


CREATE TABLE ezcontentbrowserecent (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	node_id int(11) NOT NULL ,
	created int(11) NOT NULL ,
	name varchar(255) NOT NULL DEFAULT '' 
);


CREATE TABLE ezcontentclass (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	version int(11) NOT NULL ,
	name varchar(255) DEFAULT '' ,
	identifier varchar(50) NOT NULL DEFAULT '' ,
	contentobject_name varchar(255) DEFAULT '' ,
	creator_id int(11) NOT NULL ,
	modifier_id int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezcontentclass_attribute (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	version int(11) NOT NULL ,
	contentclass_id int(11) NOT NULL ,
	identifier varchar(50) NOT NULL DEFAULT '' ,
	name varchar(255) NOT NULL DEFAULT '' ,
	data_type_string varchar(50) NOT NULL DEFAULT '' ,
	is_searchable int(1) NOT NULL ,
	is_required int(1) NOT NULL ,
	placement int(11) NOT NULL ,
	data_int1 int(11) ,
	data_int2 int(11) ,
	data_int3 int(11) ,
	data_int4 int(11) ,
	data_float1 float DEFAULT '0' ,
	data_float2 float DEFAULT '0' ,
	data_float3 float DEFAULT '0' ,
	data_float4 float DEFAULT '0' ,
	data_text1 varchar(50) DEFAULT '' ,
	data_text2 varchar(50) DEFAULT '' ,
	data_text3 varchar(50) DEFAULT '' ,
	data_text4 varchar(255) DEFAULT '' ,
	data_text5 text ,
	is_information_collector int(11) NOT NULL ,
	can_translate int(11) DEFAULT '1' 
);


CREATE TABLE ezcontentclass_classgroup (
	contentclass_id int(11) NOT NULL ,
	contentclass_version int(11) NOT NULL ,
	group_id int(11) NOT NULL ,
	group_name varchar(255) DEFAULT '' 
);
ALTER TABLE ezcontentclass_classgroup ADD PRIMARY KEY ( contentclass_id, contentclass_version, group_id );


CREATE TABLE ezcontentclassgroup (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) DEFAULT '' ,
	creator_id int(11) NOT NULL ,
	modifier_id int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezcontentobject (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	owner_id int(11) NOT NULL ,
	section_id int(11) NOT NULL ,
	contentclass_id int(11) NOT NULL ,
	name varchar(255) DEFAULT '' ,
	current_version int(11) ,
	is_published int(11) ,
	published int(11) NOT NULL ,
	modified int(11) NOT NULL ,
	status int(11) ,
	remote_id varchar(100) DEFAULT '' 
);


CREATE TABLE ezcontentobject_attribute (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	language_code varchar(20) NOT NULL DEFAULT '' ,
	version int(11) NOT NULL ,
	contentobject_id int(11) NOT NULL ,
	contentclassattribute_id int(11) NOT NULL ,
	data_text text ,
	data_int int(11) ,
	data_float float DEFAULT '0' ,
	attribute_original_id int(11) ,
	sort_key_int int(11) NOT NULL ,
	sort_key_string varchar(50) NOT NULL DEFAULT '' ,
	data_type_string varchar(50) NOT NULL DEFAULT '' 
);


CREATE TABLE ezcontentobject_link (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	from_contentobject_id int(11) NOT NULL ,
	from_contentobject_version int(11) NOT NULL ,
	to_contentobject_id int(11) NOT NULL 
);


CREATE TABLE ezcontentobject_name (
	contentobject_id int(11) NOT NULL ,
	name varchar(255) DEFAULT '' ,
	content_version int(11) NOT NULL ,
	content_translation varchar(20) NOT NULL DEFAULT '' ,
	real_translation varchar(20) DEFAULT '' 
);
ALTER TABLE ezcontentobject_name ADD PRIMARY KEY ( contentobject_id, content_version, content_translation );


CREATE TABLE ezcontentobject_tree (
	node_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	parent_node_id int(11) NOT NULL ,
	contentobject_id int(11) ,
	contentobject_version int(11) ,
	contentobject_is_published int(11) ,
	depth int(11) NOT NULL ,
	path_string varchar(255) NOT NULL DEFAULT '' ,
	sort_field int(11) DEFAULT '1' ,
	sort_order int(1) DEFAULT '1' ,
	priority int(11) NOT NULL ,
	path_identification_string text ,
	main_node_id int(11) 
);


CREATE TABLE ezcontentobject_version (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contentobject_id int(11) ,
	creator_id int(11) NOT NULL ,
	version int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL ,
	status int(11) NOT NULL ,
	workflow_event_pos int(11) NOT NULL ,
	user_id int(11) NOT NULL 
);


CREATE TABLE ezdiscountrule (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) NOT NULL DEFAULT '' 
);


CREATE TABLE ezdiscountsubrule (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) NOT NULL DEFAULT '' ,
	discountrule_id int(11) NOT NULL ,
	discount_percent float DEFAULT '0' ,
	limitation char(1) 
);


CREATE TABLE ezdiscountsubrule_value (
	discountsubrule_id int(11) NOT NULL ,
	value int(11) NOT NULL ,
	issection int(1) NOT NULL 
);
ALTER TABLE ezdiscountsubrule_value ADD PRIMARY KEY ( discountsubrule_id, value, issection );


CREATE TABLE ezenumobjectvalue (
	contentobject_attribute_id int(11) NOT NULL ,
	contentobject_attribute_version int(11) NOT NULL ,
	enumid int(11) NOT NULL ,
	enumelement varchar(255) NOT NULL DEFAULT '' ,
	enumvalue varchar(255) NOT NULL DEFAULT '' 
);
ALTER TABLE ezenumobjectvalue ADD PRIMARY KEY ( contentobject_attribute_id, contentobject_attribute_version, enumid );


CREATE TABLE ezenumvalue (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contentclass_attribute_id int(11) NOT NULL ,
	contentclass_attribute_version int(11) NOT NULL ,
	enumelement varchar(255) NOT NULL DEFAULT '' ,
	enumvalue varchar(255) NOT NULL DEFAULT '' ,
	placement int(11) NOT NULL 
);


CREATE TABLE ezforgot_password (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	hash_key varchar(32) NOT NULL DEFAULT '' ,
	time int(11) NOT NULL 
);


CREATE TABLE ezgeneral_digest_user_settings (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	address varchar(255) NOT NULL DEFAULT '' ,
	receive_digest int(11) NOT NULL ,
	digest_type int(11) NOT NULL ,
	day varchar(255) NOT NULL DEFAULT '' ,
	time varchar(255) NOT NULL DEFAULT '' 
);


CREATE TABLE ezimage (
	contentobject_attribute_id int(11) NOT NULL ,
	version int(11) NOT NULL ,
	filename varchar(255) NOT NULL DEFAULT '' ,
	original_filename varchar(255) NOT NULL DEFAULT '' ,
	mime_type varchar(50) NOT NULL DEFAULT '' ,
	alternative_text varchar(255) NOT NULL DEFAULT '' 
);
ALTER TABLE ezimage ADD PRIMARY KEY ( contentobject_attribute_id, version );


CREATE TABLE ezimagefile (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contentobject_attribute_id int(11) NOT NULL ,
	filepath text NOT NULL 
);


CREATE TABLE ezimagevariation (
	contentobject_attribute_id int(11) NOT NULL ,
	version int(11) NOT NULL ,
	filename varchar(255) NOT NULL DEFAULT '' ,
	additional_path varchar(255) DEFAULT '' ,
	requested_width int(11) NOT NULL ,
	requested_height int(11) NOT NULL ,
	width int(11) NOT NULL ,
	height int(11) NOT NULL 
);
ALTER TABLE ezimagevariation ADD PRIMARY KEY ( contentobject_attribute_id, version, requested_width, requested_height );


CREATE TABLE ezinfocollection (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contentobject_id int(11) NOT NULL ,
	created int(11) NOT NULL ,
	user_identifier varchar(34) DEFAULT '' ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezinfocollection_attribute (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	informationcollection_id int(11) NOT NULL ,
	data_text text ,
	data_int int(11) ,
	data_float float DEFAULT '0' ,
	contentclass_attribute_id int(11) NOT NULL ,
	contentobject_attribute_id int(11) ,
	contentobject_id int(11) 
);


CREATE TABLE ezkeyword (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	keyword varchar(255) DEFAULT '' ,
	class_id int(11) NOT NULL 
);


CREATE TABLE ezkeyword_attribute_link (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	keyword_id int(11) NOT NULL ,
	objectattribute_id int(11) NOT NULL 
);


CREATE TABLE ezmedia (
	contentobject_attribute_id int(11) NOT NULL ,
	version int(11) NOT NULL ,
	filename varchar(255) NOT NULL DEFAULT '' ,
	original_filename varchar(255) NOT NULL DEFAULT '' ,
	mime_type varchar(50) NOT NULL DEFAULT '' ,
	width int(11) ,
	height int(11) ,
	has_controller int(1) ,
	is_autoplay int(1) ,
	pluginspage varchar(255) DEFAULT '' ,
	quality varchar(50) DEFAULT '' ,
	controls varchar(50) DEFAULT '' ,
	is_loop int(1) 
);
ALTER TABLE ezmedia ADD PRIMARY KEY ( contentobject_attribute_id, version );


CREATE TABLE ezmessage (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	send_method varchar(50) NOT NULL DEFAULT '' ,
	send_weekday varchar(50) NOT NULL DEFAULT '' ,
	send_time varchar(50) NOT NULL DEFAULT '' ,
	destination_address varchar(50) NOT NULL DEFAULT '' ,
	title varchar(255) NOT NULL DEFAULT '' ,
	body text ,
	is_sent int(1) NOT NULL 
);


CREATE TABLE ezmodule_run (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	workflow_process_id int(11) ,
	module_name varchar(255) DEFAULT '' ,
	function_name varchar(255) DEFAULT '' ,
	module_data text 
);


CREATE TABLE eznode_assignment (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contentobject_id int(11) ,
	contentobject_version int(11) ,
	parent_node int(11) ,
	sort_field int(11) DEFAULT '1' ,
	sort_order int(1) DEFAULT '1' ,
	is_main int(11) NOT NULL ,
	from_node_id int(11) ,
	remote_id int(11) NOT NULL 
);


CREATE TABLE eznotificationcollection (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	event_id int(11) NOT NULL ,
	handler varchar(255) NOT NULL DEFAULT '' ,
	transport varchar(255) NOT NULL DEFAULT '' ,
	data_subject text NOT NULL ,
	data_text text NOT NULL 
);


CREATE TABLE eznotificationcollection_item (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	collection_id int(11) NOT NULL ,
	event_id int(11) NOT NULL ,
	address varchar(255) NOT NULL DEFAULT '' ,
	send_date int(11) NOT NULL 
);


CREATE TABLE eznotificationevent (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	status int(11) NOT NULL ,
	event_type_string varchar(255) NOT NULL DEFAULT '' ,
	data_int1 int(11) NOT NULL ,
	data_int2 int(11) NOT NULL ,
	data_int3 int(11) NOT NULL ,
	data_int4 int(11) NOT NULL ,
	data_text1 text NOT NULL ,
	data_text2 text NOT NULL ,
	data_text3 text NOT NULL ,
	data_text4 text NOT NULL 
);


CREATE TABLE ezoperation_memento (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	memento_key varchar(32) NOT NULL DEFAULT '' ,
	memento_data text NOT NULL ,
	main int(11) NOT NULL ,
	main_key varchar(32) NOT NULL DEFAULT '' 
);


CREATE TABLE ezorder (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	productcollection_id int(11) NOT NULL ,
	created int(11) NOT NULL ,
	is_temporary int(11) NOT NULL DEFAULT '1' ,
	order_nr int(11) NOT NULL ,
	data_text_2 text ,
	data_text_1 text ,
	account_identifier varchar(100) NOT NULL DEFAULT 'default' ,
	ignore_vat int(11) NOT NULL 
);


CREATE TABLE ezorder_item (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	order_id int(11) NOT NULL ,
	description varchar(255) DEFAULT '' ,
	price float DEFAULT '0' ,
	vat_value int(11) NOT NULL 
);


CREATE TABLE ezpdf_export (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title varchar(255) DEFAULT '' ,
	show_frontpage int(11) ,
	intro_text text ,
	sub_text text ,
	source_node_id int(11) ,
	export_structure varchar(255) DEFAULT '' ,
	export_classes varchar(255) DEFAULT '' ,
	site_access varchar(255) DEFAULT '' ,
	pdf_filename varchar(255) DEFAULT '' ,
	modifier_id int(11) ,
	modified int(11) ,
	created int(11) ,
	creator_id int(11) ,
	status int(11) 
);


CREATE TABLE ezpolicy (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	role_id int(11) ,
	function_name varchar(255) DEFAULT '' ,
	module_name varchar(255) DEFAULT '' ,
	limitation char(1) 
);


CREATE TABLE ezpolicy_limitation (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	policy_id int(11) ,
	identifier varchar(255) NOT NULL DEFAULT '' ,
	role_id int(11) ,
	function_name varchar(255) DEFAULT '' ,
	module_name varchar(255) DEFAULT '' 
);


CREATE TABLE ezpolicy_limitation_value (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	limitation_id int(11) ,
	value varchar(255) DEFAULT '' 
);


CREATE TABLE ezpreferences (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	name varchar(100) DEFAULT '' ,
	value varchar(100) DEFAULT '' 
);


CREATE TABLE ezproductcollection (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	created int(11) 
);


CREATE TABLE ezproductcollection_item (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	productcollection_id int(11) NOT NULL ,
	contentobject_id int(11) NOT NULL ,
	item_count int(11) NOT NULL ,
	price double ,
	is_vat_inc int(11) ,
	vat_value float DEFAULT '0' ,
	discount float DEFAULT '0' 
);


CREATE TABLE ezproductcollection_item_opt (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	item_id int(11) NOT NULL ,
	option_item_id int(11) NOT NULL ,
	name varchar(255) NOT NULL DEFAULT '' ,
	value varchar(255) NOT NULL DEFAULT '' ,
	price float NOT NULL DEFAULT '0' ,
	object_attribute_id int(11) 
);


CREATE TABLE ezrole (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	version int(11) ,
	name varchar(255) NOT NULL DEFAULT '' ,
	value char(1) 
);


CREATE TABLE ezrss_export (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title varchar(255) DEFAULT '' ,
	modifier_id int(11) ,
	modified int(11) ,
	url varchar(255) DEFAULT '' ,
	description text ,
	image_id int(11) ,
	active int(11) ,
	access_url varchar(255) DEFAULT '' ,
	created int(11) ,
	creator_id int(11) ,
	status int(11) ,
	site_access varchar(255) DEFAULT '' ,
	rss_version varchar(255) DEFAULT '' 
);


CREATE TABLE ezrss_export_item (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	rssexport_id int(11) ,
	source_node_id int(11) ,
	class_id int(11) ,
	title varchar(255) DEFAULT '' ,
	description varchar(255) DEFAULT '' 
);


CREATE TABLE ezrss_import (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) DEFAULT '' ,
	url text ,
	destination_node_id int(11) ,
	class_id int(11) ,
	class_title varchar(255) DEFAULT '' ,
	class_url varchar(255) DEFAULT '' ,
	class_description varchar(255) DEFAULT '' ,
	active int(11) ,
	creator_id int(11) ,
	created int(11) ,
	modifier_id int(11) ,
	modified int(11) ,
	status int(11) ,
	object_owner_id int(11) 
);


CREATE TABLE ezsearch_object_word_link (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	contentobject_id int(11) NOT NULL ,
	word_id int(11) NOT NULL ,
	frequency float NOT NULL DEFAULT '0' ,
	placement int(11) NOT NULL ,
	prev_word_id int(11) NOT NULL ,
	next_word_id int(11) NOT NULL ,
	contentclass_id int(11) NOT NULL ,
	published int(11) NOT NULL ,
	section_id int(11) NOT NULL ,
	contentclass_attribute_id int(11) NOT NULL ,
	identifier varchar(255) NOT NULL DEFAULT '' ,
	integer_value int(11) NOT NULL 
);


CREATE TABLE ezsearch_return_count (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	phrase_id int(11) NOT NULL ,
	time int(11) NOT NULL ,
	count int(11) NOT NULL 
);


CREATE TABLE ezsearch_search_phrase (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	phrase varchar(250) DEFAULT '' 
);


CREATE TABLE ezsearch_word (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	word varchar(150) DEFAULT '' ,
	object_count int(11) NOT NULL 
);


CREATE TABLE ezsection (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) DEFAULT '' ,
	locale varchar(255) DEFAULT '' ,
	navigation_part_identifier varchar(100) DEFAULT 'ezcontentnavigationpart' 
);


CREATE TABLE ezsession (
	session_key varchar(32) NOT NULL DEFAULT '' ,
	expiration_time int(11) NOT NULL ,
	data text NOT NULL 
);
ALTER TABLE ezsession ADD PRIMARY KEY ( session_key );


CREATE TABLE ezsite_data (
	name varchar(60) NOT NULL DEFAULT '' ,
	value text NOT NULL 
);
ALTER TABLE ezsite_data ADD PRIMARY KEY ( name );


CREATE TABLE ezsubtree_notification_rule (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	address varchar(255) NOT NULL DEFAULT '' ,
	use_digest int(11) NOT NULL ,
	node_id int(11) NOT NULL 
);


CREATE TABLE eztipafriend_counter (
	node_id int(11) NOT NULL ,
	count int(11) NOT NULL 
);
ALTER TABLE eztipafriend_counter ADD PRIMARY KEY ( node_id );


CREATE TABLE eztrigger (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) DEFAULT '' ,
	module_name varchar(200) NOT NULL DEFAULT '' ,
	function_name varchar(200) NOT NULL DEFAULT '' ,
	connect_type char(1) NOT NULL ,
	workflow_id int(11) 
);


CREATE TABLE ezurl (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	url varchar(255) DEFAULT '' ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL ,
	is_valid int(11) NOT NULL DEFAULT '1' ,
	last_checked int(11) NOT NULL ,
	original_url_md5 varchar(32) NOT NULL DEFAULT '' 
);


CREATE TABLE ezurl_object_link (
	url_id int(11) NOT NULL ,
	contentobject_attribute_id int(11) NOT NULL ,
	contentobject_attribute_version int(11) NOT NULL 
);
ALTER TABLE ezurl_object_link ADD PRIMARY KEY ( url_id, contentobject_attribute_id, contentobject_attribute_version );


CREATE TABLE ezurlalias (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	source_url text NOT NULL ,
	source_md5 varchar(32) DEFAULT '' ,
	destination_url text NOT NULL ,
	is_internal int(11) NOT NULL DEFAULT '1' ,
	forward_to_id int(11) NOT NULL ,
	is_wildcard int(11) NOT NULL 
);


CREATE TABLE ezuser (
	contentobject_id int(11) NOT NULL ,
	login varchar(150) NOT NULL DEFAULT '' ,
	email varchar(150) NOT NULL DEFAULT '' ,
	password_hash_type int(11) NOT NULL DEFAULT '1' ,
	password_hash varchar(50) DEFAULT '' 
);
ALTER TABLE ezuser ADD PRIMARY KEY ( contentobject_id );


CREATE TABLE ezuser_accountkey (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	hash_key varchar(32) NOT NULL DEFAULT '' ,
	time int(11) NOT NULL 
);


CREATE TABLE ezuser_discountrule (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	discountrule_id int(11) ,
	contentobject_id int(11) ,
	name varchar(255) NOT NULL DEFAULT '' 
);


CREATE TABLE ezuser_role (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	role_id int(11) ,
	contentobject_id int(11) 
);


CREATE TABLE ezuser_setting (
	user_id int(11) NOT NULL ,
	is_enabled int(1) NOT NULL ,
	max_login int(11) 
);
ALTER TABLE ezuser_setting ADD PRIMARY KEY ( user_id );


CREATE TABLE ezvattype (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) NOT NULL DEFAULT '' ,
	percentage float DEFAULT '0' 
);


CREATE TABLE ezview_counter (
	node_id int(11) NOT NULL ,
	count int(11) NOT NULL 
);
ALTER TABLE ezview_counter ADD PRIMARY KEY ( node_id );


CREATE TABLE ezwaituntildatevalue (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	workflow_event_id int(11) NOT NULL ,
	workflow_event_version int(11) NOT NULL ,
	contentclass_id int(11) NOT NULL ,
	contentclass_attribute_id int(11) NOT NULL 
);


CREATE TABLE ezwishlist (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id int(11) NOT NULL ,
	productcollection_id int(11) NOT NULL 
);


CREATE TABLE ezworkflow (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	version int(11) NOT NULL ,
	is_enabled int(1) NOT NULL ,
	workflow_type_string varchar(50) NOT NULL DEFAULT '' ,
	name varchar(255) NOT NULL DEFAULT '' ,
	creator_id int(11) NOT NULL ,
	modifier_id int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezworkflow_assign (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	workflow_id int(11) NOT NULL ,
	node_id int(11) NOT NULL ,
	access_type int(11) NOT NULL ,
	as_tree int(1) NOT NULL 
);


CREATE TABLE ezworkflow_event (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	version int(11) NOT NULL ,
	workflow_id int(11) NOT NULL ,
	workflow_type_string varchar(50) NOT NULL DEFAULT '' ,
	description varchar(50) NOT NULL DEFAULT '' ,
	data_int1 int(11) ,
	data_int2 int(11) ,
	data_int3 int(11) ,
	data_int4 int(11) ,
	data_text1 varchar(50) DEFAULT '' ,
	data_text2 varchar(50) DEFAULT '' ,
	data_text3 varchar(50) DEFAULT '' ,
	data_text4 varchar(50) DEFAULT '' ,
	placement int(11) NOT NULL 
);


CREATE TABLE ezworkflow_group (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) NOT NULL DEFAULT '' ,
	creator_id int(11) NOT NULL ,
	modifier_id int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL 
);


CREATE TABLE ezworkflow_group_link (
	workflow_id int(11) NOT NULL ,
	group_id int(11) NOT NULL ,
	workflow_version int(11) NOT NULL ,
	group_name varchar(255) DEFAULT '' 
);
ALTER TABLE ezworkflow_group_link ADD PRIMARY KEY ( workflow_id, group_id, workflow_version );


CREATE TABLE ezworkflow_process (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	process_key varchar(32) NOT NULL DEFAULT '' ,
	workflow_id int(11) NOT NULL ,
	user_id int(11) NOT NULL ,
	content_id int(11) NOT NULL ,
	content_version int(11) NOT NULL ,
	node_id int(11) NOT NULL ,
	session_key varchar(32) NOT NULL DEFAULT '0' ,
	event_id int(11) NOT NULL ,
	event_position int(11) NOT NULL ,
	last_event_id int(11) NOT NULL ,
	last_event_position int(11) NOT NULL ,
	last_event_status int(11) NOT NULL ,
	event_status int(11) NOT NULL ,
	created int(11) NOT NULL ,
	modified int(11) NOT NULL ,
	activation_date int(11) ,
	event_state int(11) ,
	status int(11) ,
	parameters text ,
	memento_key varchar(32) DEFAULT '' 
);


