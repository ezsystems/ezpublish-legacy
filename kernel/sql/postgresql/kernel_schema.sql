







CREATE SEQUENCE ezbasket_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezbasket (
    id integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
    session_id character varying(255) NOT NULL,
    productcollection_id integer NOT NULL
);







CREATE TABLE ezbinaryfile (
    contentobject_attribute_id integer NOT NULL,
    "version" integer NOT NULL,
    filename character varying(255) NOT NULL,
    original_filename character varying(255) NOT NULL,
    mime_type character varying(50) NOT NULL
);







CREATE SEQUENCE ezcontentclass_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentclass (
    id integer DEFAULT nextval('ezcontentclass_s'::text) NOT NULL,
    "version" integer NOT NULL,
    name character varying(255),
    identifier character varying(50) NOT NULL,
    contentobject_name character varying(255),
    creator_id integer NOT NULL,
    modifier_id integer NOT NULL,
    created integer NOT NULL,
    modified integer NOT NULL
);







CREATE SEQUENCE ezcontentclass_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentclass_attribute (
    id integer DEFAULT nextval('ezcontentclass_attribute_s'::text) NOT NULL,
    "version" integer NOT NULL,
    contentclass_id integer NOT NULL,
    identifier character varying(50) NOT NULL,
    name character varying(255) NOT NULL,
    data_type_string character varying(50) NOT NULL,
    placement integer NOT NULL,
    is_searchable smallint DEFAULT '0',
    is_required smallint DEFAULT '0',
    data_int1 integer,
    data_int2 integer,
    data_int3 integer,
    data_int4 integer,
    data_float1 double precision,
    data_float2 double precision,
    data_float3 double precision,
    data_float4 double precision,
    data_text1 character varying(50),
    data_text2 character varying(50),
    data_text3 character varying(50),
    data_text4 text,
    data_text5 text,
    is_information_collector integer DEFAULT '0' NOT NULL
);







CREATE TABLE ezcontentclass_classgroup (
    contentclass_id integer NOT NULL,
    contentclass_version integer NOT NULL,
    group_id integer NOT NULL,
    group_name character varying(255)
);







CREATE SEQUENCE ezcontentclassgroup_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentclassgroup (
    id integer DEFAULT nextval('ezcontentclassgroup_s'::text) NOT NULL,
    name character varying(255),
    creator_id integer NOT NULL,
    modifier_id integer NOT NULL,
    created integer NOT NULL,
    modified integer NOT NULL
);







CREATE SEQUENCE ezcontentobject_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentobject (
    id integer DEFAULT nextval('ezcontentobject_s'::text) NOT NULL,
    owner_id integer DEFAULT '0' NOT NULL,
    section_id integer DEFAULT '0' NOT NULL,
    contentclass_id integer NOT NULL,
    name character varying(255),
    current_version integer,
    is_published integer,
    published integer,
    modified integer,
    status smallint DEFAULT 0,
    remote_id character varying(100) DEFAULT '' NOT NULL
);







CREATE SEQUENCE ezcontentobject_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentobject_attribute (
    id integer DEFAULT nextval('ezcontentobject_attribute_s'::text) NOT NULL,
    language_code character varying(20) NOT NULL,
    "version" integer NOT NULL,
    contentobject_id integer NOT NULL,
    contentclassattribute_id integer NOT NULL,
    data_text text,
    data_int integer,
    data_float double precision
);







CREATE SEQUENCE ezcontentobject_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentobject_link (
    id integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
    from_contentobject_id integer NOT NULL,
    from_contentobject_version integer NOT NULL,
    to_contentobject_id integer
);







CREATE SEQUENCE ezcontentobject_tree_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentobject_tree (
    node_id integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
    main_node_id integer,
    parent_node_id integer NOT NULL,
    contentobject_id integer,
    contentobject_version integer,
    contentobject_is_published integer,
    crc32_path integer,
    depth integer NOT NULL,
    path_string character varying(255) NOT NULL,
    path_identification_string text,
    sort_field integer DEFAULT 1,
    sort_order smallint DEFAULT 1,
    priority integer DEFAULT 0,
    md5_path character varying(32)
);







CREATE SEQUENCE ezcontentobject_version_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentobject_version (
    id integer DEFAULT nextval('ezcontentobject_version_s'::text) NOT NULL,
    contentobject_id integer,
    creator_id integer DEFAULT '0' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL,
    status integer DEFAULT '0' NOT NULL,
    workflow_event_pos integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    main_node_id integer
);







CREATE TABLE ezenumobjectvalue (
    contentobject_attribute_id integer NOT NULL,
    contentobject_attribute_version integer NOT NULL,
    enumid integer NOT NULL,
    enumelement character varying(255) NOT NULL,
    enumvalue character varying(255) NOT NULL
);







CREATE SEQUENCE ezenumvalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezenumvalue (
    id integer DEFAULT nextval('ezenumvalue_s'::text) NOT NULL,
    contentclass_attribute_id integer NOT NULL,
    contentclass_attribute_version integer NOT NULL,
    enumelement character varying(255) NOT NULL,
    enumvalue character varying(255) NOT NULL,
    placement integer NOT NULL
);







CREATE TABLE ezimage (
    contentobject_attribute_id integer NOT NULL,
    "version" integer NOT NULL,
    filename character varying(255) NOT NULL,
    original_filename character varying(255) NOT NULL,
    mime_type character varying(50) NOT NULL,
    alternative_text character varying(255) DEFAULT '' NOT NULL
);







CREATE TABLE ezimagevariation (
    contentobject_attribute_id integer NOT NULL,
    "version" integer NOT NULL,
    filename character varying(255) NOT NULL,
    additional_path character varying(255),
    requested_width integer NOT NULL,
    requested_height integer NOT NULL,
    width integer NOT NULL,
    height integer NOT NULL
);







CREATE TABLE ezmedia (
    contentobject_attribute_id integer NOT NULL,
    "version" integer NOT NULL,
    filename character varying(255) NOT NULL,
    original_filename character varying(255) NOT NULL,
    mime_type character varying(50) NOT NULL,
    width integer,
    height integer,
    has_controller smallint,
    controls character varying(50),
    is_autoplay smallint,
    pluginspage character varying(255),
    quality character varying(50),
    is_loop smallint
);







CREATE SEQUENCE ezmodule_run_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezmodule_run (
    id integer DEFAULT nextval('ezmodule_run_s'::text) NOT NULL,
    workflow_process_id integer,
    module_name character varying(255),
    function_name character varying(255),
    module_data text
);







CREATE SEQUENCE eznode_assignment_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE eznode_assignment (
    id integer DEFAULT nextval('eznode_assignment_s'::text) NOT NULL,
    contentobject_id integer,
    contentobject_version integer,
    parent_node integer,
    is_main integer,
    sort_field integer DEFAULT 1,
    sort_order smallint DEFAULT 1,
    from_node_id integer DEFAULT '0',
    remote_id integer
);







CREATE SEQUENCE ezorder_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezorder (
    id integer DEFAULT nextval('ezorder_s'::text) NOT NULL,
    user_id integer NOT NULL,
    productcollection_id integer NOT NULL,
    created integer NOT NULL,
    is_temporary integer DEFAULT '1' NOT NULL,
    order_nr integer DEFAULT '0' NOT NULL,
    account_identifier character varying(100) DEFAULT 'default' NOT NULL,
    ignore_vat integer DEFAULT '0' NOT NULL,
    data_text_1 text,
    data_text_2 text
);







CREATE SEQUENCE ezpolicy_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezpolicy (
    id integer DEFAULT nextval('ezpolicy_s'::text) NOT NULL,
    role_id integer,
    function_name character varying,
    module_name character varying,
    limitation character(1)
);







CREATE SEQUENCE ezpolicy_limitation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezpolicy_limitation (
    id integer DEFAULT nextval('ezpolicy_limitation_s'::text) NOT NULL,
    policy_id integer,
    identifier character varying NOT NULL,
    role_id integer,
    function_name character varying,
    module_name character varying
);







CREATE SEQUENCE ezpolicy_limitation_value_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezpolicy_limitation_value (
    id integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
    limitation_id integer,
    value varchar(255)
);







CREATE SEQUENCE ezproductcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezproductcollection (
    id integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
    created integer
);







CREATE SEQUENCE ezproductcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezproductcollection_item (
    id integer DEFAULT nextval('ezproductcollection_item_s'::text) NOT NULL,
    productcollection_id integer NOT NULL,
    contentobject_id integer NOT NULL,
    item_count integer NOT NULL,
    price double precision NOT NULL,
    is_vat_inc integer NOT NULL,
    vat_value double precision DEFAULT 0 NOT NULL,
    discount double precision DEFAULT 0 NOT NULL
);







CREATE SEQUENCE ezrole_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezrole (
    id integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
    "version" integer DEFAULT '0',
    name character varying NOT NULL,
    value character(1)
);







CREATE SEQUENCE ezsearch_object_word_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezsearch_object_word_link (
    id integer DEFAULT nextval('ezsearch_object_word_link_s'::text) NOT NULL,
    contentobject_id integer NOT NULL,
    word_id integer NOT NULL,
    frequency double precision NOT NULL,
    placement integer NOT NULL,
    prev_word_id integer NOT NULL,
    next_word_id integer NOT NULL,
    contentclass_id integer NOT NULL,
    contentclass_attribute_id integer NOT NULL,
    published integer DEFAULT '0' NOT NULL,
    section_id integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezsearch_return_count_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezsearch_return_count (
    id integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
    phrase_id integer NOT NULL,
    "time" integer NOT NULL,
    count integer NOT NULL
);







CREATE SEQUENCE ezsearch_search_phrase_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezsearch_search_phrase (
    id integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
    phrase character varying(250)
);







CREATE SEQUENCE ezsearch_word_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezsearch_word (
    id integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
    word character varying(150),
    object_count integer NOT NULL
);







CREATE SEQUENCE ezsection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezsection (
    id integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
    name character varying(255),
    locale character varying(255),
    navigation_part_identifier character varying(100) DEFAULT 'ezcontentnavigationpart'
);







CREATE TABLE ezsession (
    session_key character(32) NOT NULL,
    expiration_time integer NOT NULL,
    data text NOT NULL
);







CREATE SEQUENCE eztrigger_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE eztrigger (
    id integer DEFAULT nextval('eztrigger_s'::text),
    name character varying(255),
    module_name character varying(255) NOT NULL,
    function_name character varying(255) NOT NULL,
    connect_type character(1) NOT NULL,
    workflow_id integer
);







CREATE TABLE ezuser (
    contentobject_id integer NOT NULL,
    login character varying(150) NOT NULL,
    email character varying(150) NOT NULL,
    password_hash_type integer DEFAULT 1 NOT NULL,
    password_hash character varying(50)
);







CREATE SEQUENCE ezuser_role_s
    START 26
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezuser_role (
    id integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
    role_id integer,
    contentobject_id integer
);







CREATE TABLE ezuser_setting (
    user_id integer DEFAULT '0' NOT NULL,
    is_enabled smallint DEFAULT '0' NOT NULL,
    max_login integer
);







CREATE SEQUENCE ezwishlist_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezwishlist (
    id integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
    user_id integer NOT NULL,
    productcollection_id integer NOT NULL
);







CREATE SEQUENCE ezworkflow_s
    START 2
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezworkflow (
    id integer DEFAULT nextval('ezworkflow_s'::text) NOT NULL,
    "version" integer NOT NULL,
    workflow_type_string character varying(50) NOT NULL,
    name character varying(255) NOT NULL,
    creator_id integer NOT NULL,
    modifier_id integer NOT NULL,
    created integer NOT NULL,
    modified integer NOT NULL,
    is_enabled integer
);







CREATE SEQUENCE ezworkflow_assign_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezworkflow_assign (
    id integer DEFAULT nextval('ezworkflow_assign_s'::text) NOT NULL,
    workflow_id integer NOT NULL,
    node_id integer NOT NULL,
    access_type integer NOT NULL,
    as_tree integer NOT NULL
);







CREATE SEQUENCE ezworkflow_event_s
    START 3
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezworkflow_event (
    id integer DEFAULT nextval('ezworkflow_event_s'::text) NOT NULL,
    "version" integer NOT NULL,
    workflow_id integer NOT NULL,
    workflow_type_string character varying(50) NOT NULL,
    description character varying(50) NOT NULL,
    data_int1 integer,
    data_int2 integer,
    data_int3 integer,
    data_int4 integer,
    data_text1 character varying(50),
    data_text2 character varying(50),
    data_text3 character varying(50),
    data_text4 character varying(50),
    placement integer NOT NULL
);







CREATE SEQUENCE ezworkflow_group_s
    START 2
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezworkflow_group (
    id integer DEFAULT nextval('ezworkflow_group_s'::text) NOT NULL,
    name character varying(255) NOT NULL,
    creator_id integer NOT NULL,
    modifier_id integer NOT NULL,
    created integer NOT NULL,
    modified integer NOT NULL
);







CREATE TABLE ezworkflow_group_link (
    workflow_id integer DEFAULT '0' NOT NULL,
    group_id integer DEFAULT '0' NOT NULL,
    workflow_version integer DEFAULT '0' NOT NULL,
    group_name character varying
);







CREATE SEQUENCE ezworkflow_process_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezworkflow_process (
    id integer DEFAULT nextval('ezworkflow_process_s'::text) NOT NULL,
    process_key character(32) NOT NULL,
    workflow_id integer NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    content_id integer DEFAULT '0' NOT NULL,
    content_version integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL,
    session_key character varying(32) DEFAULT '0',
    event_id integer NOT NULL,
    event_position integer NOT NULL,
    last_event_id integer NOT NULL,
    last_event_position integer NOT NULL,
    last_event_status integer NOT NULL,
    event_status integer NOT NULL,
    created integer NOT NULL,
    modified integer NOT NULL,
    activation_date integer,
    event_state integer DEFAULT 0,
    status integer,
    parameters text,
    memento_key character(32)
);







CREATE SEQUENCE ezoperation_memento_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezoperation_memento (
    id integer DEFAULT nextval('ezoperation_memento_s'::text) NOT NULL,
    main integer DEFAULT 0 NOT NULL,
    memento_key character(32) NOT NULL,
    main_key character(32) NOT NULL,
    memento_data text NOT NULL
);







CREATE SEQUENCE ezdiscountsubrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezdiscountsubrule (
    id integer DEFAULT nextval('ezdiscountsubrule_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    discountrule_id integer DEFAULT '0' NOT NULL,
    discount_percent double precision,
    limitation character(1)
);







CREATE TABLE ezdiscountsubrule_value (
    discountsubrule_id integer DEFAULT '0' NOT NULL,
    value integer DEFAULT '0' NOT NULL,
    issection integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezinfocollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezinfocollection (
    id integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
    contentobject_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezinfocollection_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezinfocollection_attribute (
    id integer DEFAULT nextval('ezinfocollection_attribute_s'::text) NOT NULL,
    informationcollection_id integer DEFAULT 0 NOT NULL,
    data_text text,
    data_int integer,
    data_float double precision,
    contentclass_attribute_id integer
);







CREATE SEQUENCE ezuser_discountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezuser_discountrule (
    id integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
    discountrule_id integer,
    contentobject_id integer,
    name character varying(255) DEFAULT '' NOT NULL
);







CREATE SEQUENCE ezvattype_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezvattype (
    id integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    percentage double precision
);







CREATE SEQUENCE ezdiscountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezdiscountrule (
    id integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
    name character varying(255) NOT NULL
);







CREATE SEQUENCE ezorder_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezorder_item (
    id integer DEFAULT nextval('ezorder_item_s'::text) NOT NULL,
    order_id integer NOT NULL,
    description character varying(255),
    price double precision,
    vat_value integer DEFAULT '0' NOT NULL
);







CREATE TABLE ezcontentobject_name (
    contentobject_id integer NOT NULL,
    name character varying(255),
    content_version integer NOT NULL,
    content_translation character varying(20) NOT NULL,
    real_translation character varying(20)
);







CREATE SEQUENCE ezwaituntildatevalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezwaituntildatevalue (
    id integer DEFAULT nextval('ezwaituntildatevalue_s'::text) NOT NULL,
    workflow_event_id integer DEFAULT '0' NOT NULL,
    workflow_event_version integer DEFAULT '0' NOT NULL,
    contentclass_id integer DEFAULT '0' NOT NULL,
    contentclass_attribute_id integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezcontent_translation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontent_translation (
    id integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    locale character varying(255) NOT NULL
);







CREATE SEQUENCE ezcollab_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcollab_item (
    id integer DEFAULT nextval('ezcollab_item_s'::text) NOT NULL,
    type_identifier character varying(40) DEFAULT '' NOT NULL,
    creator_id integer DEFAULT '0' NOT NULL,
    status integer DEFAULT '1' NOT NULL,
    data_text1 text DEFAULT '' NOT NULL,
    data_text2 text DEFAULT '' NOT NULL,
    data_text3 text DEFAULT '' NOT NULL,
    data_int1 integer DEFAULT '0' NOT NULL,
    data_int2 integer DEFAULT '0' NOT NULL,
    data_int3 integer DEFAULT '0' NOT NULL,
    data_float1 double precision DEFAULT '0' NOT NULL,
    data_float2 double precision DEFAULT '0' NOT NULL,
    data_float3 double precision DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezcollab_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcollab_group (
    id integer DEFAULT nextval('ezcollab_group_s'::text) NOT NULL,
    parent_group_id integer DEFAULT '0' NOT NULL,
    depth integer DEFAULT '0' NOT NULL,
    path_string character varying(255) DEFAULT '' NOT NULL,
    is_open integer DEFAULT '1' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    title character varying(255) DEFAULT '' NOT NULL,
    priority integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);







CREATE TABLE ezcollab_item_group_link (
    collaboration_id integer DEFAULT '0' NOT NULL,
    group_id integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    is_read integer DEFAULT '0' NOT NULL,
    is_active integer DEFAULT '1' NOT NULL,
    last_read integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);







CREATE TABLE ezcollab_item_status (
    collaboration_id integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    is_read integer DEFAULT '0' NOT NULL,
    is_active integer DEFAULT '1' NOT NULL,
    last_read integer DEFAULT '0' NOT NULL
);







CREATE TABLE ezcollab_item_participant_link (
    collaboration_id integer DEFAULT '0' NOT NULL,
    participant_id integer DEFAULT '0' NOT NULL,
    participant_type integer DEFAULT '1' NOT NULL,
    participant_role integer DEFAULT '1' NOT NULL,
    last_read integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezcollab_item_message_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcollab_item_message_link (
    id integer DEFAULT nextval('ezcollab_item_message_link_s'::text) NOT NULL,
    collaboration_id integer DEFAULT '0' NOT NULL,
    participant_id integer DEFAULT '0' NOT NULL,
    message_id integer DEFAULT '0' NOT NULL,
    message_type integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezcollab_simple_message_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcollab_simple_message (
    id integer DEFAULT nextval('ezcollab_simple_message_s'::text) NOT NULL,
    message_type character varying(40) DEFAULT '' NOT NULL,
    creator_id integer DEFAULT '0' NOT NULL,
    data_text1 text DEFAULT '' NOT NULL,
    data_text2 text DEFAULT '' NOT NULL,
    data_text3 text DEFAULT '' NOT NULL,
    data_int1 integer DEFAULT '0' NOT NULL,
    data_int2 integer DEFAULT '0' NOT NULL,
    data_int3 integer DEFAULT '0' NOT NULL,
    data_float1 double precision DEFAULT '0' NOT NULL,
    data_float2 double precision DEFAULT '0' NOT NULL,
    data_float3 double precision DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezcollab_profile_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcollab_profile (
    id integer DEFAULT nextval('ezcollab_profile_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    main_group integer DEFAULT '0' NOT NULL,
    data_text1 text DEFAULT '' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezapprove_items_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezapprove_items (
    id integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
    workflow_process_id integer DEFAULT '0' NOT NULL,
    collaboration_id integer DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezurl_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezurl (
    id integer DEFAULT nextval('ezurl_s'::text) NOT NULL,
    url character varying(255),
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL,
    is_valid integer DEFAULT '1' NOT NULL,
    last_checked integer DEFAULT '0' NOT NULL,
    original_url_md5 character varying(32) DEFAULT '' NOT NULL
);







CREATE SEQUENCE ezmessage_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezmessage (
    id integer DEFAULT nextval('ezmessage_s'::text) NOT NULL,
    send_method character varying(50) DEFAULT '' NOT NULL,
    send_weekday character varying(50) DEFAULT '' NOT NULL,
    send_time character varying(50) DEFAULT '' NOT NULL,
    destination_address character varying(50) DEFAULT '' NOT NULL,
    title character varying(50) DEFAULT '' NOT NULL,
    body character varying(50),
    is_sent smallint DEFAULT '0' NOT NULL
);







CREATE SEQUENCE ezproductcollection_item_opt_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezproductcollection_item_opt (
    id integer DEFAULT nextval('ezproductcollection_item_opt_s'::text) NOT NULL,
    item_id integer NOT NULL,
    option_item_id integer NOT NULL,
    object_attribute_id integer NOT NULL,
    name character varying(255) NOT NULL,
    value character varying(255) NOT NULL,
    price double precision DEFAULT 0 NOT NULL
);







CREATE SEQUENCE ezforgot_password_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezforgot_password (
    id integer DEFAULT nextval('ezforgot_password_s'::text) NOT NULL,
    user_id integer NOT NULL,
    hash_key character varying(32) NOT NULL,
    "time" integer NOT NULL
);







CREATE INDEX ezcontentclass_id ON ezcontentclass USING btree (id);







CREATE UNIQUE INDEX ezcontentobject_id ON ezcontentobject USING btree (id);







CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link USING btree (word_id);







CREATE INDEX ezsearch_object_word_link_freq ON ezsearch_object_word_link USING btree (frequency);







CREATE INDEX ezsearch_word_i ON ezsearch_word USING btree (word);







CREATE INDEX ezcontentobject_tree_path ON ezcontentobject_tree USING btree (path_string);







CREATE INDEX ezcontentobject_tree_p_node_id ON ezcontentobject_tree USING btree (parent_node_id);







CREATE INDEX ezcontentobject_tree_co_id ON ezcontentobject_tree USING btree (contentobject_id);







CREATE INDEX ezcontentobject_tree_depth ON ezcontentobject_tree USING btree (depth);







CREATE UNIQUE INDEX eztrigger_id ON eztrigger USING btree (id);







CREATE INDEX eztrigger_fetch ON eztrigger USING btree (module_name, function_name, connect_type);







CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s ON ezmodule_run USING btree (workflow_process_id);







CREATE INDEX ezcontentobject_tree_crc32_path ON ezcontentobject_tree USING btree (crc32_path);







CREATE UNIQUE INDEX ezcontentobject_tree_md5_path ON ezcontentobject_tree USING btree (md5_path);







CREATE UNIQUE INDEX ezuser_contentobject_id ON ezuser USING btree (contentobject_id);







CREATE INDEX ezuser_role_contentobject_id ON ezuser_role USING btree (contentobject_id);







CREATE INDEX ezcontentobject_attribute_contentobject_id ON ezcontentobject_attribute USING btree (contentobject_id);







CREATE INDEX ezcontentobject_attribute_language_code ON ezcontentobject_attribute USING btree (language_code);







CREATE INDEX ezcontentclass_version ON ezcontentclass USING btree ("version");







CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver ON ezenumvalue USING btree (contentclass_attribute_id, contentclass_attribute_version);







CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver ON ezenumobjectvalue USING btree (contentobject_attribute_id, contentobject_attribute_version);







CREATE INDEX ezwaituntildatevalue_wf_ev_id_wf_ver ON ezwaituntildatevalue USING btree (workflow_event_id, workflow_event_version);







CREATE INDEX ezcollab_group_path ON ezcollab_group USING btree (path_string);







CREATE INDEX ezcollab_group_dept ON ezcollab_group USING btree (depth);







ALTER TABLE ONLY ezbasket
    ADD CONSTRAINT ezbasket_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezbinaryfile
    ADD CONSTRAINT ezbinaryfile_pkey PRIMARY KEY (contentobject_attribute_id, "version");







ALTER TABLE ONLY ezcontentclass
    ADD CONSTRAINT ezcontentclass_pkey PRIMARY KEY (id, "version");







ALTER TABLE ONLY ezcontentclass_attribute
    ADD CONSTRAINT ezcontentclass_attribute_pkey PRIMARY KEY (id, "version");







ALTER TABLE ONLY ezcontentclass_classgroup
    ADD CONSTRAINT ezcontentclass_classgroup_pkey PRIMARY KEY (contentclass_id, contentclass_version, group_id);







ALTER TABLE ONLY ezcontentclassgroup
    ADD CONSTRAINT ezcontentclassgroup_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcontentobject
    ADD CONSTRAINT ezcontentobject_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcontentobject_attribute
    ADD CONSTRAINT ezcontentobject_attribute_pkey PRIMARY KEY (id, "version");







ALTER TABLE ONLY ezcontentobject_link
    ADD CONSTRAINT ezcontentobject_link_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcontentobject_tree
    ADD CONSTRAINT ezcontentobject_tree_pkey PRIMARY KEY (node_id);







ALTER TABLE ONLY ezcontentobject_version
    ADD CONSTRAINT ezcontentobject_version_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezenumobjectvalue
    ADD CONSTRAINT ezenumobjectvalue_pkey PRIMARY KEY (contentobject_attribute_id, contentobject_attribute_version, enumid);







ALTER TABLE ONLY ezenumvalue
    ADD CONSTRAINT ezenumvalue_pkey PRIMARY KEY (id, contentclass_attribute_id, contentclass_attribute_version);







ALTER TABLE ONLY ezimage
    ADD CONSTRAINT ezimage_pkey PRIMARY KEY (contentobject_attribute_id, "version");







ALTER TABLE ONLY ezimagevariation
    ADD CONSTRAINT ezimagevariation_pkey PRIMARY KEY (contentobject_attribute_id, "version", requested_width, requested_height);







ALTER TABLE ONLY ezmedia
    ADD CONSTRAINT ezmedia_pkey PRIMARY KEY (contentobject_attribute_id, "version");







ALTER TABLE ONLY ezmodule_run
    ADD CONSTRAINT ezmodule_run_pkey PRIMARY KEY (id);







ALTER TABLE ONLY eznode_assignment
    ADD CONSTRAINT eznode_assignment_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezorder
    ADD CONSTRAINT ezorder_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezpolicy
    ADD CONSTRAINT ezpolicy_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezpolicy_limitation
    ADD CONSTRAINT ezpolicy_limitation_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezpolicy_limitation_value
    ADD CONSTRAINT ezpolicy_limitation_value_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezproductcollection
    ADD CONSTRAINT ezproductcollection_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezproductcollection_item
    ADD CONSTRAINT ezproductcollection_item_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezrole
    ADD CONSTRAINT ezrole_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezsearch_object_word_link
    ADD CONSTRAINT ezsearch_object_word_link_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezsearch_return_count
    ADD CONSTRAINT ezsearch_return_count_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezsearch_search_phrase
    ADD CONSTRAINT ezsearch_search_phrase_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezsearch_word
    ADD CONSTRAINT ezsearch_word_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezsection
    ADD CONSTRAINT ezsection_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezsession
    ADD CONSTRAINT ezsession_pkey PRIMARY KEY (session_key);







ALTER TABLE ONLY ezuser_role
    ADD CONSTRAINT ezuser_role_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezuser_setting
    ADD CONSTRAINT ezuser_setting_pkey PRIMARY KEY (user_id);







ALTER TABLE ONLY ezwishlist
    ADD CONSTRAINT ezwishlist_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezworkflow
    ADD CONSTRAINT ezworkflow_pkey PRIMARY KEY (id, "version");







ALTER TABLE ONLY ezworkflow_assign
    ADD CONSTRAINT ezworkflow_assign_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezworkflow_event
    ADD CONSTRAINT ezworkflow_event_pkey PRIMARY KEY (id, "version");







ALTER TABLE ONLY ezworkflow_group
    ADD CONSTRAINT ezworkflow_group_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezworkflow_group_link
    ADD CONSTRAINT ezworkflow_group_link_pkey PRIMARY KEY (workflow_id, group_id, workflow_version);







ALTER TABLE ONLY ezworkflow_process
    ADD CONSTRAINT ezworkflow_process_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezoperation_memento
    ADD CONSTRAINT ezoperation_memento_pkey PRIMARY KEY (id, memento_key);







ALTER TABLE ONLY ezdiscountsubrule
    ADD CONSTRAINT ezdiscountsubrule_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezdiscountsubrule_value
    ADD CONSTRAINT ezdiscountsubrule_value_pkey PRIMARY KEY (discountsubrule_id, value, issection);







ALTER TABLE ONLY ezinfocollection
    ADD CONSTRAINT ezinfocollection_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezinfocollection_attribute
    ADD CONSTRAINT ezinfocollection_attribute_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezuser_discountrule
    ADD CONSTRAINT ezuser_discountrule_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezvattype
    ADD CONSTRAINT ezvattype_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezdiscountrule
    ADD CONSTRAINT ezdiscountrule_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezorder_item
    ADD CONSTRAINT ezorder_item_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcontentobject_name
    ADD CONSTRAINT ezcontentobject_name_pkey PRIMARY KEY (contentobject_id, content_version, content_translation);







ALTER TABLE ONLY ezwaituntildatevalue
    ADD CONSTRAINT ezwaituntildatevalue_pkey PRIMARY KEY (id, workflow_event_id, workflow_event_version);







ALTER TABLE ONLY ezcontent_translation
    ADD CONSTRAINT ezcontent_translation_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcollab_item
    ADD CONSTRAINT ezcollab_item_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcollab_group
    ADD CONSTRAINT ezcollab_group_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcollab_item_group_link
    ADD CONSTRAINT ezcollab_item_group_link_pkey PRIMARY KEY (collaboration_id, group_id, user_id);







ALTER TABLE ONLY ezcollab_item_status
    ADD CONSTRAINT ezcollab_item_status_pkey PRIMARY KEY (collaboration_id, user_id);







ALTER TABLE ONLY ezcollab_item_participant_link
    ADD CONSTRAINT ezcollab_item_participant_link_pkey PRIMARY KEY (collaboration_id, participant_id);







ALTER TABLE ONLY ezcollab_item_message_link
    ADD CONSTRAINT ezcollab_item_message_link_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcollab_simple_message
    ADD CONSTRAINT ezcollab_simple_message_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezcollab_profile
    ADD CONSTRAINT ezcollab_profile_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezapprove_items
    ADD CONSTRAINT ezapprove_items_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezurl
    ADD CONSTRAINT ezurl_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezmessage
    ADD CONSTRAINT ezmessage_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezproductcollection_item_opt
    ADD CONSTRAINT ezproductcollection_item_opt_pkey PRIMARY KEY (id);







ALTER TABLE ONLY ezforgot_password
    ADD CONSTRAINT ezforgot_password_pkey PRIMARY KEY (id);


CREATE SEQUENCE ezcontentbrowsebookmark_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;







CREATE TABLE ezcontentbrowsebookmark (
    id integer DEFAULT nextval('ezcontentbrowsebookmark_s'::text) NOT NULL,
    user_id integer NOT NULL,
    node_id integer NOT NULL,
    name character varying NOT NULL DEFAULT ''
);

ALTER TABLE ONLY ezcontentbrowsebookmark
    ADD CONSTRAINT ezcontentbrowsebookmark_pkey PRIMARY KEY (id);
Create index ezcontentbrowsebookmark_user on ezcontentbrowsebookmark( user_id );


CREATE SEQUENCE ezcontentbrowserecent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE ezcontentbrowserecent (
    id integer DEFAULT nextval('ezcontentbrowserecent_s'::text) NOT NULL,
    user_id integer NOT NULL,
    node_id integer NOT NULL,
    created integer NOT NULL DEFAULT 0,
    name character varying NOT NULL DEFAULT ''
);

ALTER TABLE ONLY ezcontentbrowserecent
    ADD CONSTRAINT ezcontentbrowserecent_pkey PRIMARY KEY (id);
Create index ezcontentbrowserecent_user on ezcontentbrowserecent( user_id );


CREATE SEQUENCE eznotificationevent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE eznotificationevent (
    id integer DEFAULT nextval('eznotificationevent_s'::text) NOT NULL,
    status integer NOT NULL DEFAULT 0,
    event_type_string varchar(255) NOT NULL,
    data_int1 integer NOT NULL default 0,
    data_int2 integer NOT NULL default 0,
    data_int3 integer NOT NULL default 0,
    data_int4 integer NOT NULL default 0,
    data_text1 text NOT NULL default '',
    data_text2 text NOT NULL default '',
    data_text3 text NOT NULL default '',
    data_text4 text NOT NULL default '',
    primary key ( id )
);


CREATE SEQUENCE eznotificationcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE eznotificationcollection (
    id integer DEFAULT nextval('eznotificationcollection_s'::text) NOT NULL,
    event_id integer NOT NULL default 0,
    handler varchar(255) NOT NULL default '',
    transport varchar(255) NOT NULL default '',
    data_subject text NOT NULL default '',
    data_text text NOT NULL default '',
    primary key ( id )
);

CREATE SEQUENCE eznotificationcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;



CREATE TABLE eznotificationcollection_item (
    id integer DEFAULT nextval('eznotificationcollection_item_s'::text) NOT NULL,
    collection_id integer NOT NULL default 0,
    event_id integer NOT NULL default 0,
    address varchar(255) NOT NULL default '',
    send_date integer NOT NULL default 0,
    primary key ( id )
);


CREATE SEQUENCE ezsubtree_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezsubtree_notification_rule (
    id integer DEFAULT nextval('ezsubtree_notification_rule_s'::text) NOT NULL,
    address varchar(255) NOT NULL,
    use_digest integer not null default 0,
    node_id integer NOT NULL,
    primary key ( id )
);


CREATE SEQUENCE ezgeneral_digest_user_settings_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

CREATE TABLE ezgeneral_digest_user_settings (
    id integer DEFAULT nextval('ezgeneral_digest_user_settings_s'::text) NOT NULL,
    address varchar(255) NOT NULL,
    receive_digest integer not null default 0,
    digest_type integer not null default 0,
    day varchar(255) not null default '',
    time varchar(255) not null default '',
    primary key ( id )
);


CREATE SEQUENCE ezkeyword_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

create table ezkeyword(
id int NOT NULL DEFAULT nextval('ezkeyword_s'::text),
keyword varchar(255),
class_id int not null,
PRIMARY KEY  (id)
);

CREATE SEQUENCE ezkeyword_attribute_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;

create table ezkeyword_attribute_link(
    id int NOT NULL DEFAULT nextval('ezkeyword_attribute_link_s'::text),
    keyword_id int not null,
    objectattribute_id  int not null,
    PRIMARY KEY  (id)
);

