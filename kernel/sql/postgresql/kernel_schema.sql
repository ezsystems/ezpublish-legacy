--
-- PostgreSQL database dump
--
--
-- TOC entry 2 (OID 18803)
-- Name: ezbasket_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezbasket_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 54 (OID 18805)
-- Name: ezbasket; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezbasket (
    id integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
    session_id character varying(255) NOT NULL,
    productcollection_id integer NOT NULL
);


--
-- TOC entry 55 (OID 18810)
-- Name: ezbinaryfile; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezbinaryfile (
    contentobject_attribute_id integer NOT NULL,
    "version" integer NOT NULL,
    filename character varying(255) NOT NULL,
    original_filename character varying(255) NOT NULL,
    mime_type character varying(50) NOT NULL
);


--
-- TOC entry 3 (OID 18814)
-- Name: ezcontentclass_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentclass_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 56 (OID 18816)
-- Name: ezcontentclass; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 4 (OID 18821)
-- Name: ezcontentclass_attribute_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentclass_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 57 (OID 18823)
-- Name: ezcontentclass_attribute; Type: TABLE; Schema: public; Owner: sp
--

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
    data_text4 character varying(50),
    is_information_collector integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 58 (OID 18831)
-- Name: ezcontentclass_classgroup; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcontentclass_classgroup (
    contentclass_id integer NOT NULL,
    contentclass_version integer NOT NULL,
    group_id integer NOT NULL,
    group_name character varying(255)
);


--
-- TOC entry 5 (OID 18835)
-- Name: ezcontentclassgroup_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentclassgroup_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 59 (OID 18837)
-- Name: ezcontentclassgroup; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcontentclassgroup (
    id integer DEFAULT nextval('ezcontentclassgroup_s'::text) NOT NULL,
    name character varying(255),
    creator_id integer NOT NULL,
    modifier_id integer NOT NULL,
    created integer NOT NULL,
    modified integer NOT NULL
);


--
-- TOC entry 6 (OID 18842)
-- Name: ezcontentobject_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentobject_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 60 (OID 18844)
-- Name: ezcontentobject; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 7 (OID 18853)
-- Name: ezcontentobject_attribute_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentobject_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 61 (OID 18855)
-- Name: ezcontentobject_attribute; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 8 (OID 18863)
-- Name: ezcontentobject_link_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentobject_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 62 (OID 18865)
-- Name: ezcontentobject_link; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcontentobject_link (
    id integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
    from_contentobject_id integer NOT NULL,
    from_contentobject_version integer NOT NULL,
    to_contentobject_id integer
);


--
-- TOC entry 9 (OID 18870)
-- Name: ezcontentobject_tree_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentobject_tree_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 63 (OID 18872)
-- Name: ezcontentobject_tree; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 10 (OID 18883)
-- Name: ezcontentobject_version_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontentobject_version_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 64 (OID 18885)
-- Name: ezcontentobject_version; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 65 (OID 18897)
-- Name: ezenumobjectvalue; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezenumobjectvalue (
    contentobject_attribute_id integer NOT NULL,
    contentobject_attribute_version integer NOT NULL,
    enumid integer NOT NULL,
    enumelement character varying(255) NOT NULL,
    enumvalue character varying(255) NOT NULL
);


--
-- TOC entry 11 (OID 18901)
-- Name: ezenumvalue_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezenumvalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 66 (OID 18903)
-- Name: ezenumvalue; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezenumvalue (
    id integer DEFAULT nextval('ezenumvalue_s'::text) NOT NULL,
    contentclass_attribute_id integer NOT NULL,
    contentclass_attribute_version integer NOT NULL,
    enumelement character varying(255) NOT NULL,
    enumvalue character varying(255) NOT NULL,
    placement integer NOT NULL
);


--
-- TOC entry 67 (OID 18908)
-- Name: ezimage; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezimage (
    contentobject_attribute_id integer NOT NULL,
    "version" integer NOT NULL,
    filename character varying(255) NOT NULL,
    original_filename character varying(255) NOT NULL,
    mime_type character varying(50) NOT NULL,
    alternative_text character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 68 (OID 18913)
-- Name: ezimagevariation; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 69 (OID 18917)
-- Name: ezmedia; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 12 (OID 18921)
-- Name: ezmodule_run_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezmodule_run_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 70 (OID 18923)
-- Name: ezmodule_run; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezmodule_run (
    id integer DEFAULT nextval('ezmodule_run_s'::text) NOT NULL,
    workflow_process_id integer,
    module_name character varying(255),
    function_name character varying(255),
    module_data text
);


--
-- TOC entry 13 (OID 18931)
-- Name: eznode_assignment_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE eznode_assignment_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 71 (OID 18933)
-- Name: eznode_assignment; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 14 (OID 18941)
-- Name: ezorder_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezorder_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 72 (OID 18943)
-- Name: ezorder; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 15 (OID 18955)
-- Name: ezpolicy_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezpolicy_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 73 (OID 18957)
-- Name: ezpolicy; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezpolicy (
    id integer DEFAULT nextval('ezpolicy_s'::text) NOT NULL,
    role_id integer,
    function_name character varying,
    module_name character varying,
    limitation character(1)
);


--
-- TOC entry 16 (OID 18965)
-- Name: ezpolicy_limitation_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezpolicy_limitation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 74 (OID 18967)
-- Name: ezpolicy_limitation; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezpolicy_limitation (
    id integer DEFAULT nextval('ezpolicy_limitation_s'::text) NOT NULL,
    policy_id integer,
    identifier character varying NOT NULL,
    role_id integer,
    function_name character varying,
    module_name character varying
);


--
-- TOC entry 17 (OID 18975)
-- Name: ezpolicy_limitation_value_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezpolicy_limitation_value_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 75 (OID 18977)
-- Name: ezpolicy_limitation_value; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezpolicy_limitation_value (
    id integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
    limitation_id integer,
    value varchar(255)
);


--
-- TOC entry 18 (OID 18982)
-- Name: ezproductcollection_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezproductcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 76 (OID 18984)
-- Name: ezproductcollection; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezproductcollection (
    id integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
    created integer
);


--
-- TOC entry 19 (OID 18989)
-- Name: ezproductcollection_item_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezproductcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 77 (OID 18991)
-- Name: ezproductcollection_item; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 20 (OID 18998)
-- Name: ezrole_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezrole_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 78 (OID 19000)
-- Name: ezrole; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezrole (
    id integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
    "version" integer DEFAULT '0',
    name character varying NOT NULL,
    value character(1)
);


--
-- TOC entry 21 (OID 19009)
-- Name: ezsearch_object_word_link_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezsearch_object_word_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 79 (OID 19011)
-- Name: ezsearch_object_word_link; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 22 (OID 19018)
-- Name: ezsearch_return_count_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezsearch_return_count_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 80 (OID 19020)
-- Name: ezsearch_return_count; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezsearch_return_count (
    id integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
    phrase_id integer NOT NULL,
    "time" integer NOT NULL,
    count integer NOT NULL
);


--
-- TOC entry 23 (OID 19025)
-- Name: ezsearch_search_phrase_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezsearch_search_phrase_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 81 (OID 19027)
-- Name: ezsearch_search_phrase; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezsearch_search_phrase (
    id integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
    phrase character varying(250)
);


--
-- TOC entry 24 (OID 19032)
-- Name: ezsearch_word_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezsearch_word_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 82 (OID 19034)
-- Name: ezsearch_word; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezsearch_word (
    id integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
    word character varying(150),
    object_count integer NOT NULL
);


--
-- TOC entry 25 (OID 19039)
-- Name: ezsection_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezsection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 83 (OID 19041)
-- Name: ezsection; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezsection (
    id integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
    name character varying(255),
    locale character varying(255),
    navigation_part_identifier character varying(100) DEFAULT 'ezcontentnavigationpart'
);


--
-- TOC entry 84 (OID 19047)
-- Name: ezsession; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezsession (
    session_key character(32) NOT NULL,
    expiration_time integer NOT NULL,
    data text NOT NULL
);


--
-- TOC entry 26 (OID 19054)
-- Name: eztrigger_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE eztrigger_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 85 (OID 19056)
-- Name: eztrigger; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE eztrigger (
    id integer DEFAULT nextval('eztrigger_s'::text),
    name character varying(255),
    module_name character varying(255) NOT NULL,
    function_name character varying(255) NOT NULL,
    connect_type character(1) NOT NULL,
    workflow_id integer
);


--
-- TOC entry 86 (OID 19059)
-- Name: ezuser; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezuser (
    contentobject_id integer NOT NULL,
    login character varying(150) NOT NULL,
    email character varying(150) NOT NULL,
    password_hash_type integer DEFAULT 1 NOT NULL,
    password_hash character varying(50)
);


--
-- TOC entry 27 (OID 19062)
-- Name: ezuser_role_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezuser_role_s
    START 26
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 87 (OID 19064)
-- Name: ezuser_role; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezuser_role (
    id integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
    role_id integer,
    contentobject_id integer
);


--
-- TOC entry 88 (OID 19069)
-- Name: ezuser_setting; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezuser_setting (
    user_id integer DEFAULT '0' NOT NULL,
    is_enabled smallint DEFAULT '0' NOT NULL,
    max_login integer
);


--
-- TOC entry 28 (OID 19075)
-- Name: ezwishlist_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezwishlist_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 89 (OID 19077)
-- Name: ezwishlist; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezwishlist (
    id integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
    user_id integer NOT NULL,
    productcollection_id integer NOT NULL
);


--
-- TOC entry 29 (OID 19082)
-- Name: ezworkflow_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezworkflow_s
    START 2
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 90 (OID 19084)
-- Name: ezworkflow; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 30 (OID 19089)
-- Name: ezworkflow_assign_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezworkflow_assign_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 91 (OID 19091)
-- Name: ezworkflow_assign; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezworkflow_assign (
    id integer DEFAULT nextval('ezworkflow_assign_s'::text) NOT NULL,
    workflow_id integer NOT NULL,
    node_id integer NOT NULL,
    access_type integer NOT NULL,
    as_tree integer NOT NULL
);


--
-- TOC entry 31 (OID 19096)
-- Name: ezworkflow_event_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezworkflow_event_s
    START 3
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 92 (OID 19098)
-- Name: ezworkflow_event; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 32 (OID 19103)
-- Name: ezworkflow_group_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezworkflow_group_s
    START 2
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 93 (OID 19105)
-- Name: ezworkflow_group; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezworkflow_group (
    id integer DEFAULT nextval('ezworkflow_group_s'::text) NOT NULL,
    name character varying(255) NOT NULL,
    creator_id integer NOT NULL,
    modifier_id integer NOT NULL,
    created integer NOT NULL,
    modified integer NOT NULL
);


--
-- TOC entry 94 (OID 19110)
-- Name: ezworkflow_group_link; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezworkflow_group_link (
    workflow_id integer DEFAULT '0' NOT NULL,
    group_id integer DEFAULT '0' NOT NULL,
    workflow_version integer DEFAULT '0' NOT NULL,
    group_name character varying
);


--
-- TOC entry 33 (OID 19120)
-- Name: ezworkflow_process_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezworkflow_process_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 95 (OID 19122)
-- Name: ezworkflow_process; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 34 (OID 19136)
-- Name: ezoperation_memento_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezoperation_memento_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 96 (OID 19138)
-- Name: ezoperation_memento; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezoperation_memento (
    id integer DEFAULT nextval('ezoperation_memento_s'::text) NOT NULL,
    main integer DEFAULT 0 NOT NULL,
    memento_key character(32) NOT NULL,
    main_key character(32) NOT NULL,
    memento_data text NOT NULL
);


--
-- TOC entry 35 (OID 19147)
-- Name: ezdiscountsubrule_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezdiscountsubrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 97 (OID 19149)
-- Name: ezdiscountsubrule; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezdiscountsubrule (
    id integer DEFAULT nextval('ezdiscountsubrule_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    discountrule_id integer DEFAULT '0' NOT NULL,
    discount_percent double precision,
    limitation character(1)
);


--
-- TOC entry 98 (OID 19156)
-- Name: ezdiscountsubrule_value; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezdiscountsubrule_value (
    discountsubrule_id integer DEFAULT '0' NOT NULL,
    value integer DEFAULT '0' NOT NULL,
    issection integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 36 (OID 19163)
-- Name: ezinfocollection_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezinfocollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 99 (OID 19165)
-- Name: ezinfocollection; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezinfocollection (
    id integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
    contentobject_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 37 (OID 19172)
-- Name: ezinfocollection_attribute_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezinfocollection_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 100 (OID 19174)
-- Name: ezinfocollection_attribute; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezinfocollection_attribute (
    id integer DEFAULT nextval('ezinfocollection_attribute_s'::text) NOT NULL,
    informationcollection_id integer DEFAULT 0 NOT NULL,
    data_text text,
    data_int integer,
    data_float double precision,
    contentclass_attribute_id integer
);


--
-- TOC entry 38 (OID 19183)
-- Name: ezuser_discountrule_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezuser_discountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 101 (OID 19185)
-- Name: ezuser_discountrule; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezuser_discountrule (
    id integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
    discountrule_id integer,
    contentobject_id integer,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 39 (OID 19191)
-- Name: ezvattype_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezvattype_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 102 (OID 19193)
-- Name: ezvattype; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezvattype (
    id integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    percentage double precision
);


--
-- TOC entry 40 (OID 19199)
-- Name: ezdiscountrule_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezdiscountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 103 (OID 19201)
-- Name: ezdiscountrule; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezdiscountrule (
    id integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
    name character varying(255) NOT NULL
);


--
-- TOC entry 41 (OID 19206)
-- Name: ezorder_item_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezorder_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 104 (OID 19208)
-- Name: ezorder_item; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezorder_item (
    id integer DEFAULT nextval('ezorder_item_s'::text) NOT NULL,
    order_id integer NOT NULL,
    description character varying(255),
    price double precision,
    vat_value integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 105 (OID 19214)
-- Name: ezcontentobject_name; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcontentobject_name (
    contentobject_id integer NOT NULL,
    name character varying(255),
    content_version integer NOT NULL,
    content_translation character varying(20) NOT NULL,
    real_translation character varying(20)
);


--
-- TOC entry 42 (OID 19218)
-- Name: ezwaituntildatevalue_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezwaituntildatevalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 106 (OID 19220)
-- Name: ezwaituntildatevalue; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezwaituntildatevalue (
    id integer DEFAULT nextval('ezwaituntildatevalue_s'::text) NOT NULL,
    workflow_event_id integer DEFAULT '0' NOT NULL,
    workflow_event_version integer DEFAULT '0' NOT NULL,
    contentclass_id integer DEFAULT '0' NOT NULL,
    contentclass_attribute_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 43 (OID 19229)
-- Name: ezcontent_translation_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcontent_translation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 107 (OID 19231)
-- Name: ezcontent_translation; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcontent_translation (
    id integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    locale character varying(255) NOT NULL
);


--
-- TOC entry 44 (OID 19237)
-- Name: ezcollab_item_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcollab_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 108 (OID 19239)
-- Name: ezcollab_item; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 45 (OID 19261)
-- Name: ezcollab_group_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcollab_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 109 (OID 19263)
-- Name: ezcollab_group; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 110 (OID 19277)
-- Name: ezcollab_item_group_link; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 111 (OID 19289)
-- Name: ezcollab_item_status; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcollab_item_status (
    collaboration_id integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    is_read integer DEFAULT '0' NOT NULL,
    is_active integer DEFAULT '1' NOT NULL,
    last_read integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 112 (OID 19298)
-- Name: ezcollab_item_participant_link; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcollab_item_participant_link (
    collaboration_id integer DEFAULT '0' NOT NULL,
    participant_id integer DEFAULT '0' NOT NULL,
    participant_type integer DEFAULT '1' NOT NULL,
    participant_role integer DEFAULT '1' NOT NULL,
    last_read integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 46 (OID 19309)
-- Name: ezcollab_item_message_link_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcollab_item_message_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 113 (OID 19311)
-- Name: ezcollab_item_message_link; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcollab_item_message_link (
    id integer DEFAULT nextval('ezcollab_item_message_link_s'::text) NOT NULL,
    collaboration_id integer DEFAULT '0' NOT NULL,
    participant_id integer DEFAULT '0' NOT NULL,
    message_id integer DEFAULT '0' NOT NULL,
    message_type integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 47 (OID 19322)
-- Name: ezcollab_simple_message_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcollab_simple_message_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 114 (OID 19324)
-- Name: ezcollab_simple_message; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 48 (OID 19345)
-- Name: ezcollab_profile_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezcollab_profile_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 115 (OID 19347)
-- Name: ezcollab_profile; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezcollab_profile (
    id integer DEFAULT nextval('ezcollab_profile_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    main_group integer DEFAULT '0' NOT NULL,
    data_text1 text DEFAULT '' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 49 (OID 19360)
-- Name: ezapprove_items_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezapprove_items_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 116 (OID 19362)
-- Name: ezapprove_items; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezapprove_items (
    id integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
    workflow_process_id integer DEFAULT '0' NOT NULL,
    collaboration_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 50 (OID 19369)
-- Name: ezurl_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezurl_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 117 (OID 19371)
-- Name: ezurl; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezurl (
    id integer DEFAULT nextval('ezurl_s'::text) NOT NULL,
    url character varying(255),
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL,
    is_valid integer DEFAULT '1' NOT NULL,
    last_checked integer DEFAULT '0' NOT NULL,
    original_url_md5 character varying(32) DEFAULT '' NOT NULL
);


--
-- TOC entry 51 (OID 19381)
-- Name: ezmessage_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezmessage_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 118 (OID 19383)
-- Name: ezmessage; Type: TABLE; Schema: public; Owner: sp
--

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


--
-- TOC entry 52 (OID 19394)
-- Name: ezproductcollection_item_opt_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezproductcollection_item_opt_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 119 (OID 19396)
-- Name: ezproductcollection_item_opt; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezproductcollection_item_opt (
    id integer DEFAULT nextval('ezproductcollection_item_opt_s'::text) NOT NULL,
    item_id integer NOT NULL,
    option_item_id integer NOT NULL,
    object_attribute_id integer NOT NULL,
    name character varying(255) NOT NULL,
    value character varying(255) NOT NULL,
    price double precision DEFAULT 0 NOT NULL
);


--
-- TOC entry 53 (OID 19402)
-- Name: ezforgot_password_s; Type: SEQUENCE; Schema: public; Owner: sp
--

CREATE SEQUENCE ezforgot_password_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 120 (OID 19404)
-- Name: ezforgot_password; Type: TABLE; Schema: public; Owner: sp
--

CREATE TABLE ezforgot_password (
    id integer DEFAULT nextval('ezforgot_password_s'::text) NOT NULL,
    user_id integer NOT NULL,
    hash_key character varying(32) NOT NULL,
    "time" integer NOT NULL
);


--
-- TOC entry 123 (OID 19523)
-- Name: ezcontentclass_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentclass_id ON ezcontentclass USING btree (id);


--
-- TOC entry 129 (OID 19524)
-- Name: ezcontentobject_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE UNIQUE INDEX ezcontentobject_id ON ezcontentobject USING btree (id);


--
-- TOC entry 162 (OID 19525)
-- Name: ezsearch_object_word_link_word; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezsearch_object_word_link_word ON ezsearch_object_word_link USING btree (word_id);


--
-- TOC entry 160 (OID 19526)
-- Name: ezsearch_object_word_link_freq; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezsearch_object_word_link_freq ON ezsearch_object_word_link USING btree (frequency);


--
-- TOC entry 165 (OID 19527)
-- Name: ezsearch_word_i; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezsearch_word_i ON ezsearch_word USING btree (word);


--
-- TOC entry 140 (OID 19528)
-- Name: ezcontentobject_tree_path; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentobject_tree_path ON ezcontentobject_tree USING btree (path_string);


--
-- TOC entry 139 (OID 19529)
-- Name: ezcontentobject_tree_p_node_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentobject_tree_p_node_id ON ezcontentobject_tree USING btree (parent_node_id);


--
-- TOC entry 135 (OID 19530)
-- Name: ezcontentobject_tree_co_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentobject_tree_co_id ON ezcontentobject_tree USING btree (contentobject_id);


--
-- TOC entry 137 (OID 19531)
-- Name: ezcontentobject_tree_depth; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentobject_tree_depth ON ezcontentobject_tree USING btree (depth);


--
-- TOC entry 170 (OID 19532)
-- Name: eztrigger_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE UNIQUE INDEX eztrigger_id ON eztrigger USING btree (id);


--
-- TOC entry 169 (OID 19533)
-- Name: eztrigger_fetch; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX eztrigger_fetch ON eztrigger USING btree (module_name, function_name, connect_type);


--
-- TOC entry 151 (OID 19534)
-- Name: ezmodule_run_workflow_process_id_s; Type: INDEX; Schema: public; Owner: sp
--

CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s ON ezmodule_run USING btree (workflow_process_id);


--
-- TOC entry 136 (OID 19535)
-- Name: ezcontentobject_tree_crc32_path; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentobject_tree_crc32_path ON ezcontentobject_tree USING btree (crc32_path);


--
-- TOC entry 138 (OID 19536)
-- Name: ezcontentobject_tree_md5_path; Type: INDEX; Schema: public; Owner: sp
--

CREATE UNIQUE INDEX ezcontentobject_tree_md5_path ON ezcontentobject_tree USING btree (md5_path);


--
-- TOC entry 171 (OID 19537)
-- Name: ezuser_contentobject_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE UNIQUE INDEX ezuser_contentobject_id ON ezuser USING btree (contentobject_id);


--
-- TOC entry 172 (OID 19538)
-- Name: ezuser_role_contentobject_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezuser_role_contentobject_id ON ezuser_role USING btree (contentobject_id);


--
-- TOC entry 131 (OID 19539)
-- Name: ezcontentobject_attribute_contentobject_id; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentobject_attribute_contentobject_id ON ezcontentobject_attribute USING btree (contentobject_id);


--
-- TOC entry 132 (OID 19540)
-- Name: ezcontentobject_attribute_language_code; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentobject_attribute_language_code ON ezcontentobject_attribute USING btree (language_code);


--
-- TOC entry 125 (OID 19541)
-- Name: ezcontentclass_version; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcontentclass_version ON ezcontentclass USING btree ("version");


--
-- TOC entry 145 (OID 19542)
-- Name: ezenumvalue_co_cl_attr_id_co_class_att_ver; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver ON ezenumvalue USING btree (contentclass_attribute_id, contentclass_attribute_version);


--
-- TOC entry 143 (OID 19543)
-- Name: ezenumobjectvalue_co_attr_id_co_attr_ver; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver ON ezenumobjectvalue USING btree (contentobject_attribute_id, contentobject_attribute_version);


--
-- TOC entry 193 (OID 19544)
-- Name: ezwaituntildatevalue_wf_ev_id_wf_ver; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezwaituntildatevalue_wf_ev_id_wf_ver ON ezwaituntildatevalue USING btree (workflow_event_id, workflow_event_version);


--
-- TOC entry 197 (OID 19545)
-- Name: ezcollab_group_path; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcollab_group_path ON ezcollab_group USING btree (path_string);


--
-- TOC entry 196 (OID 19546)
-- Name: ezcollab_group_dept; Type: INDEX; Schema: public; Owner: sp
--

CREATE INDEX ezcollab_group_dept ON ezcollab_group USING btree (depth);


--
-- TOC entry 121 (OID 18808)
-- Name: ezbasket_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezbasket
    ADD CONSTRAINT ezbasket_pkey PRIMARY KEY (id);


--
-- TOC entry 122 (OID 18812)
-- Name: ezbinaryfile_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezbinaryfile
    ADD CONSTRAINT ezbinaryfile_pkey PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 124 (OID 18819)
-- Name: ezcontentclass_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentclass
    ADD CONSTRAINT ezcontentclass_pkey PRIMARY KEY (id, "version");


--
-- TOC entry 126 (OID 18829)
-- Name: ezcontentclass_attribute_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentclass_attribute
    ADD CONSTRAINT ezcontentclass_attribute_pkey PRIMARY KEY (id, "version");


--
-- TOC entry 127 (OID 18833)
-- Name: ezcontentclass_classgroup_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentclass_classgroup
    ADD CONSTRAINT ezcontentclass_classgroup_pkey PRIMARY KEY (contentclass_id, contentclass_version, group_id);


--
-- TOC entry 128 (OID 18840)
-- Name: ezcontentclassgroup_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentclassgroup
    ADD CONSTRAINT ezcontentclassgroup_pkey PRIMARY KEY (id);


--
-- TOC entry 130 (OID 18851)
-- Name: ezcontentobject_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentobject
    ADD CONSTRAINT ezcontentobject_pkey PRIMARY KEY (id);


--
-- TOC entry 133 (OID 18861)
-- Name: ezcontentobject_attribute_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentobject_attribute
    ADD CONSTRAINT ezcontentobject_attribute_pkey PRIMARY KEY (id, "version");


--
-- TOC entry 134 (OID 18868)
-- Name: ezcontentobject_link_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentobject_link
    ADD CONSTRAINT ezcontentobject_link_pkey PRIMARY KEY (id);


--
-- TOC entry 141 (OID 18881)
-- Name: ezcontentobject_tree_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentobject_tree
    ADD CONSTRAINT ezcontentobject_tree_pkey PRIMARY KEY (node_id);


--
-- TOC entry 142 (OID 18895)
-- Name: ezcontentobject_version_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentobject_version
    ADD CONSTRAINT ezcontentobject_version_pkey PRIMARY KEY (id);


--
-- TOC entry 144 (OID 18899)
-- Name: ezenumobjectvalue_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezenumobjectvalue
    ADD CONSTRAINT ezenumobjectvalue_pkey PRIMARY KEY (contentobject_attribute_id, contentobject_attribute_version, enumid);


--
-- TOC entry 146 (OID 18906)
-- Name: ezenumvalue_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezenumvalue
    ADD CONSTRAINT ezenumvalue_pkey PRIMARY KEY (id, contentclass_attribute_id, contentclass_attribute_version);


--
-- TOC entry 147 (OID 18911)
-- Name: ezimage_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezimage
    ADD CONSTRAINT ezimage_pkey PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 148 (OID 18915)
-- Name: ezimagevariation_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezimagevariation
    ADD CONSTRAINT ezimagevariation_pkey PRIMARY KEY (contentobject_attribute_id, "version", requested_width, requested_height);


--
-- TOC entry 149 (OID 18919)
-- Name: ezmedia_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezmedia
    ADD CONSTRAINT ezmedia_pkey PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 150 (OID 18929)
-- Name: ezmodule_run_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezmodule_run
    ADD CONSTRAINT ezmodule_run_pkey PRIMARY KEY (id);


--
-- TOC entry 152 (OID 18939)
-- Name: eznode_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY eznode_assignment
    ADD CONSTRAINT eznode_assignment_pkey PRIMARY KEY (id);


--
-- TOC entry 153 (OID 18953)
-- Name: ezorder_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezorder
    ADD CONSTRAINT ezorder_pkey PRIMARY KEY (id);


--
-- TOC entry 154 (OID 18963)
-- Name: ezpolicy_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezpolicy
    ADD CONSTRAINT ezpolicy_pkey PRIMARY KEY (id);


--
-- TOC entry 155 (OID 18973)
-- Name: ezpolicy_limitation_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezpolicy_limitation
    ADD CONSTRAINT ezpolicy_limitation_pkey PRIMARY KEY (id);


--
-- TOC entry 156 (OID 18980)
-- Name: ezpolicy_limitation_value_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezpolicy_limitation_value
    ADD CONSTRAINT ezpolicy_limitation_value_pkey PRIMARY KEY (id);


--
-- TOC entry 157 (OID 18987)
-- Name: ezproductcollection_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezproductcollection
    ADD CONSTRAINT ezproductcollection_pkey PRIMARY KEY (id);


--
-- TOC entry 158 (OID 18996)
-- Name: ezproductcollection_item_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezproductcollection_item
    ADD CONSTRAINT ezproductcollection_item_pkey PRIMARY KEY (id);


--
-- TOC entry 159 (OID 19007)
-- Name: ezrole_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezrole
    ADD CONSTRAINT ezrole_pkey PRIMARY KEY (id);


--
-- TOC entry 161 (OID 19016)
-- Name: ezsearch_object_word_link_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezsearch_object_word_link
    ADD CONSTRAINT ezsearch_object_word_link_pkey PRIMARY KEY (id);


--
-- TOC entry 163 (OID 19023)
-- Name: ezsearch_return_count_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezsearch_return_count
    ADD CONSTRAINT ezsearch_return_count_pkey PRIMARY KEY (id);


--
-- TOC entry 164 (OID 19030)
-- Name: ezsearch_search_phrase_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezsearch_search_phrase
    ADD CONSTRAINT ezsearch_search_phrase_pkey PRIMARY KEY (id);


--
-- TOC entry 166 (OID 19037)
-- Name: ezsearch_word_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezsearch_word
    ADD CONSTRAINT ezsearch_word_pkey PRIMARY KEY (id);


--
-- TOC entry 167 (OID 19045)
-- Name: ezsection_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezsection
    ADD CONSTRAINT ezsection_pkey PRIMARY KEY (id);


--
-- TOC entry 168 (OID 19052)
-- Name: ezsession_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezsession
    ADD CONSTRAINT ezsession_pkey PRIMARY KEY (session_key);


--
-- TOC entry 173 (OID 19067)
-- Name: ezuser_role_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezuser_role
    ADD CONSTRAINT ezuser_role_pkey PRIMARY KEY (id);


--
-- TOC entry 174 (OID 19073)
-- Name: ezuser_setting_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezuser_setting
    ADD CONSTRAINT ezuser_setting_pkey PRIMARY KEY (user_id);


--
-- TOC entry 175 (OID 19080)
-- Name: ezwishlist_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezwishlist
    ADD CONSTRAINT ezwishlist_pkey PRIMARY KEY (id);


--
-- TOC entry 176 (OID 19087)
-- Name: ezworkflow_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezworkflow
    ADD CONSTRAINT ezworkflow_pkey PRIMARY KEY (id, "version");


--
-- TOC entry 177 (OID 19094)
-- Name: ezworkflow_assign_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezworkflow_assign
    ADD CONSTRAINT ezworkflow_assign_pkey PRIMARY KEY (id);


--
-- TOC entry 178 (OID 19101)
-- Name: ezworkflow_event_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezworkflow_event
    ADD CONSTRAINT ezworkflow_event_pkey PRIMARY KEY (id, "version");


--
-- TOC entry 179 (OID 19108)
-- Name: ezworkflow_group_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezworkflow_group
    ADD CONSTRAINT ezworkflow_group_pkey PRIMARY KEY (id);


--
-- TOC entry 180 (OID 19118)
-- Name: ezworkflow_group_link_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezworkflow_group_link
    ADD CONSTRAINT ezworkflow_group_link_pkey PRIMARY KEY (workflow_id, group_id, workflow_version);


--
-- TOC entry 181 (OID 19134)
-- Name: ezworkflow_process_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezworkflow_process
    ADD CONSTRAINT ezworkflow_process_pkey PRIMARY KEY (id);


--
-- TOC entry 182 (OID 19145)
-- Name: ezoperation_memento_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezoperation_memento
    ADD CONSTRAINT ezoperation_memento_pkey PRIMARY KEY (id, memento_key);


--
-- TOC entry 183 (OID 19154)
-- Name: ezdiscountsubrule_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezdiscountsubrule
    ADD CONSTRAINT ezdiscountsubrule_pkey PRIMARY KEY (id);


--
-- TOC entry 184 (OID 19161)
-- Name: ezdiscountsubrule_value_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezdiscountsubrule_value
    ADD CONSTRAINT ezdiscountsubrule_value_pkey PRIMARY KEY (discountsubrule_id, value, issection);


--
-- TOC entry 185 (OID 19170)
-- Name: ezinfocollection_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezinfocollection
    ADD CONSTRAINT ezinfocollection_pkey PRIMARY KEY (id);


--
-- TOC entry 186 (OID 19181)
-- Name: ezinfocollection_attribute_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezinfocollection_attribute
    ADD CONSTRAINT ezinfocollection_attribute_pkey PRIMARY KEY (id);


--
-- TOC entry 187 (OID 19189)
-- Name: ezuser_discountrule_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezuser_discountrule
    ADD CONSTRAINT ezuser_discountrule_pkey PRIMARY KEY (id);


--
-- TOC entry 188 (OID 19197)
-- Name: ezvattype_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezvattype
    ADD CONSTRAINT ezvattype_pkey PRIMARY KEY (id);


--
-- TOC entry 189 (OID 19204)
-- Name: ezdiscountrule_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezdiscountrule
    ADD CONSTRAINT ezdiscountrule_pkey PRIMARY KEY (id);


--
-- TOC entry 190 (OID 19212)
-- Name: ezorder_item_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezorder_item
    ADD CONSTRAINT ezorder_item_pkey PRIMARY KEY (id);


--
-- TOC entry 191 (OID 19216)
-- Name: ezcontentobject_name_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontentobject_name
    ADD CONSTRAINT ezcontentobject_name_pkey PRIMARY KEY (contentobject_id, content_version, content_translation);


--
-- TOC entry 192 (OID 19227)
-- Name: ezwaituntildatevalue_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezwaituntildatevalue
    ADD CONSTRAINT ezwaituntildatevalue_pkey PRIMARY KEY (id, workflow_event_id, workflow_event_version);


--
-- TOC entry 194 (OID 19235)
-- Name: ezcontent_translation_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcontent_translation
    ADD CONSTRAINT ezcontent_translation_pkey PRIMARY KEY (id);


--
-- TOC entry 195 (OID 19259)
-- Name: ezcollab_item_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_item
    ADD CONSTRAINT ezcollab_item_pkey PRIMARY KEY (id);


--
-- TOC entry 198 (OID 19275)
-- Name: ezcollab_group_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_group
    ADD CONSTRAINT ezcollab_group_pkey PRIMARY KEY (id);


--
-- TOC entry 199 (OID 19287)
-- Name: ezcollab_item_group_link_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_item_group_link
    ADD CONSTRAINT ezcollab_item_group_link_pkey PRIMARY KEY (collaboration_id, group_id, user_id);


--
-- TOC entry 200 (OID 19296)
-- Name: ezcollab_item_status_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_item_status
    ADD CONSTRAINT ezcollab_item_status_pkey PRIMARY KEY (collaboration_id, user_id);


--
-- TOC entry 201 (OID 19307)
-- Name: ezcollab_item_participant_link_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_item_participant_link
    ADD CONSTRAINT ezcollab_item_participant_link_pkey PRIMARY KEY (collaboration_id, participant_id);


--
-- TOC entry 202 (OID 19320)
-- Name: ezcollab_item_message_link_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_item_message_link
    ADD CONSTRAINT ezcollab_item_message_link_pkey PRIMARY KEY (id);


--
-- TOC entry 203 (OID 19343)
-- Name: ezcollab_simple_message_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_simple_message
    ADD CONSTRAINT ezcollab_simple_message_pkey PRIMARY KEY (id);


--
-- TOC entry 204 (OID 19358)
-- Name: ezcollab_profile_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezcollab_profile
    ADD CONSTRAINT ezcollab_profile_pkey PRIMARY KEY (id);


--
-- TOC entry 205 (OID 19367)
-- Name: ezapprove_items_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezapprove_items
    ADD CONSTRAINT ezapprove_items_pkey PRIMARY KEY (id);


--
-- TOC entry 206 (OID 19379)
-- Name: ezurl_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezurl
    ADD CONSTRAINT ezurl_pkey PRIMARY KEY (id);


--
-- TOC entry 207 (OID 19392)
-- Name: ezmessage_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezmessage
    ADD CONSTRAINT ezmessage_pkey PRIMARY KEY (id);


--
-- TOC entry 208 (OID 19400)
-- Name: ezproductcollection_item_opt_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezproductcollection_item_opt
    ADD CONSTRAINT ezproductcollection_item_opt_pkey PRIMARY KEY (id);


--
-- TOC entry 209 (OID 19407)
-- Name: ezforgot_password_pkey; Type: CONSTRAINT; Schema: public; Owner: sp
--

ALTER TABLE ONLY ezforgot_password
    ADD CONSTRAINT ezforgot_password_pkey PRIMARY KEY (id);


CREATE SEQUENCE ezcontentbrowsebookmark_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 78 (OID 19000)
-- Name: ezrole; Type: TABLE; Schema: public; Owner: sp
--

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
id int NOT NULL nextval('ezkeyword_attribute_link_s'::text),
keyword_id int not null,
objectattribute_id  int not null,
PRIMARY KEY  (id)
);

