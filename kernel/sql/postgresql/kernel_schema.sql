--
-- PostgreSQL database dump
--

\connect - postgres

SET search_path = public, pg_catalog;

--
-- TOC entry 2 (OID 38938)
-- Name: ezapprove_items_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezapprove_items_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 3 (OID 38940)
-- Name: ezbasket_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezbasket_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 4 (OID 38942)
-- Name: ezcollab_group_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 5 (OID 38944)
-- Name: ezcollab_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 6 (OID 38946)
-- Name: ezcollab_item_message_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_item_message_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 7 (OID 38948)
-- Name: ezcollab_notification_rule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 8 (OID 38950)
-- Name: ezcollab_profile_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_profile_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 9 (OID 38952)
-- Name: ezcollab_simple_message_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_simple_message_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 38954)
-- Name: ezcontent_translation_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontent_translation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 11 (OID 38956)
-- Name: ezcontentbrowsebookmark_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentbrowsebookmark_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 38958)
-- Name: ezcontentbrowserecent_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentbrowserecent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 13 (OID 38960)
-- Name: ezcontentclass_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentclass_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 14 (OID 38962)
-- Name: ezcontentclass_attribute_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentclass_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 15 (OID 38964)
-- Name: ezcontentclassgroup_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentclassgroup_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 16 (OID 38966)
-- Name: ezcontentobject_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 17 (OID 38968)
-- Name: ezcontentobject_attribute_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 38970)
-- Name: ezcontentobject_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 19 (OID 38972)
-- Name: ezcontentobject_tree_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_tree_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 20 (OID 38974)
-- Name: ezcontentobject_version_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_version_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 21 (OID 38976)
-- Name: ezdiscountrule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezdiscountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 22 (OID 38978)
-- Name: ezdiscountsubrule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezdiscountsubrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 23 (OID 38980)
-- Name: ezenumvalue_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezenumvalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 24 (OID 38982)
-- Name: ezforgot_password_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezforgot_password_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 25 (OID 38984)
-- Name: ezgeneral_digest_user_settings_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezgeneral_digest_user_settings_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 26 (OID 38986)
-- Name: ezinfocollection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezinfocollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 27 (OID 38988)
-- Name: ezinfocollection_attribute_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezinfocollection_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 28 (OID 38990)
-- Name: ezkeyword_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezkeyword_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 29 (OID 38992)
-- Name: ezkeyword_attribute_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezkeyword_attribute_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 30 (OID 38994)
-- Name: ezmessage_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezmessage_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 31 (OID 38996)
-- Name: ezmodule_run_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezmodule_run_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 32 (OID 38998)
-- Name: eznode_assignment_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznode_assignment_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 33 (OID 39000)
-- Name: eznotificationcollection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznotificationcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 39002)
-- Name: eznotificationcollection_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznotificationcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 35 (OID 39004)
-- Name: eznotificationevent_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznotificationevent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 36 (OID 39006)
-- Name: ezoperation_memento_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezoperation_memento_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 37 (OID 39008)
-- Name: ezorder_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezorder_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 39010)
-- Name: ezorder_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezorder_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 39 (OID 39012)
-- Name: ezpolicy_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpolicy_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 40 (OID 39014)
-- Name: ezpolicy_limitation_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpolicy_limitation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 41 (OID 39016)
-- Name: ezpolicy_limitation_value_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpolicy_limitation_value_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 42 (OID 39018)
-- Name: ezpreferences_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpreferences_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 43 (OID 39020)
-- Name: ezproductcollection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezproductcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 44 (OID 39022)
-- Name: ezproductcollection_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezproductcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 45 (OID 39024)
-- Name: ezproductcollection_item_opt_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezproductcollection_item_opt_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 46 (OID 39026)
-- Name: ezrole_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezrole_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 47 (OID 39028)
-- Name: ezsearch_object_word_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_object_word_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 39030)
-- Name: ezsearch_return_count_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_return_count_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 49 (OID 39032)
-- Name: ezsearch_search_phrase_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_search_phrase_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 50 (OID 39034)
-- Name: ezsearch_word_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_word_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 51 (OID 39036)
-- Name: ezsection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 52 (OID 39038)
-- Name: ezsubtree_notification_rule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsubtree_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 53 (OID 39040)
-- Name: eztrigger_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eztrigger_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 54 (OID 39042)
-- Name: ezurl_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezurl_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 55 (OID 39044)
-- Name: ezurlalias_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezurlalias_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 56 (OID 39046)
-- Name: ezuser_accountkey_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezuser_accountkey_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 57 (OID 39048)
-- Name: ezuser_discountrule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezuser_discountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 58 (OID 39050)
-- Name: ezuser_role_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezuser_role_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 59 (OID 39052)
-- Name: ezvattype_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezvattype_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 60 (OID 39054)
-- Name: ezwaituntildatevalue_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezwaituntildatevalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 61 (OID 39056)
-- Name: ezwishlist_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezwishlist_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 62 (OID 39058)
-- Name: ezworkflow_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 63 (OID 39060)
-- Name: ezworkflow_assign_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_assign_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 64 (OID 39062)
-- Name: ezworkflow_event_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_event_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 65 (OID 39064)
-- Name: ezworkflow_group_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 66 (OID 39066)
-- Name: ezworkflow_process_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_process_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 67 (OID 39068)
-- Name: ezapprove_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezapprove_items (
    id integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
    workflow_process_id integer DEFAULT '0' NOT NULL,
    collaboration_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 68 (OID 39073)
-- Name: ezbasket; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezbasket (
    id integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
    session_id character varying(255) DEFAULT '' NOT NULL,
    productcollection_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 69 (OID 39078)
-- Name: ezbinaryfile; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezbinaryfile (
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    filename character varying(255) DEFAULT '' NOT NULL,
    original_filename character varying(255) DEFAULT '' NOT NULL,
    mime_type character varying(50) DEFAULT '' NOT NULL
);


--
-- TOC entry 70 (OID 39085)
-- Name: ezcollab_group; Type: TABLE; Schema: public; Owner: postgres
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
-- TOC entry 71 (OID 39097)
-- Name: ezcollab_item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcollab_item (
    id integer DEFAULT nextval('ezcollab_item_s'::text) NOT NULL,
    type_identifier character varying(40) DEFAULT '' NOT NULL,
    creator_id integer DEFAULT '0' NOT NULL,
    status integer DEFAULT '1' NOT NULL,
    data_text1 text NOT NULL,
    data_text2 text NOT NULL,
    data_text3 text NOT NULL,
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
-- TOC entry 72 (OID 39114)
-- Name: ezcollab_item_group_link; Type: TABLE; Schema: public; Owner: postgres
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
-- TOC entry 73 (OID 39124)
-- Name: ezcollab_item_message_link; Type: TABLE; Schema: public; Owner: postgres
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
-- TOC entry 74 (OID 39133)
-- Name: ezcollab_item_participant_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcollab_item_participant_link (
    collaboration_id integer DEFAULT '0' NOT NULL,
    participant_id integer DEFAULT '0' NOT NULL,
    participant_type integer DEFAULT '1' NOT NULL,
    participant_role integer DEFAULT '1' NOT NULL,
    is_read integer DEFAULT '0' NOT NULL,
    is_active integer DEFAULT '1' NOT NULL,
    last_read integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 75 (OID 39144)
-- Name: ezcollab_item_status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcollab_item_status (
    collaboration_id integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    is_read integer DEFAULT '0' NOT NULL,
    is_active integer DEFAULT '1' NOT NULL,
    last_read integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 76 (OID 39151)
-- Name: ezcollab_notification_rule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcollab_notification_rule (
    id integer DEFAULT nextval('ezcollab_notification_rule_s'::text) NOT NULL,
    user_id character varying(255) DEFAULT '' NOT NULL,
    collab_identifier character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 77 (OID 39156)
-- Name: ezcollab_profile; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcollab_profile (
    id integer DEFAULT nextval('ezcollab_profile_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    main_group integer DEFAULT '0' NOT NULL,
    data_text1 text NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 78 (OID 39166)
-- Name: ezcollab_simple_message; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcollab_simple_message (
    id integer DEFAULT nextval('ezcollab_simple_message_s'::text) NOT NULL,
    message_type character varying(40) DEFAULT '' NOT NULL,
    creator_id integer DEFAULT '0' NOT NULL,
    data_text1 text NOT NULL,
    data_text2 text NOT NULL,
    data_text3 text NOT NULL,
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
-- TOC entry 79 (OID 39182)
-- Name: ezcontent_translation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontent_translation (
    id integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    locale character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 80 (OID 39187)
-- Name: ezcontentbrowsebookmark; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentbrowsebookmark (
    id integer DEFAULT nextval('ezcontentbrowsebookmark_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 81 (OID 39193)
-- Name: ezcontentbrowserecent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentbrowserecent (
    id integer DEFAULT nextval('ezcontentbrowserecent_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 82 (OID 39200)
-- Name: ezcontentclass; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentclass (
    id integer DEFAULT nextval('ezcontentclass_s'::text) NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    name character varying(255),
    identifier character varying(50) DEFAULT '' NOT NULL,
    contentobject_name character varying(255),
    creator_id integer DEFAULT '0' NOT NULL,
    modifier_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 83 (OID 39209)
-- Name: ezcontentclass_attribute; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentclass_attribute (
    id integer DEFAULT nextval('ezcontentclass_attribute_s'::text) NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    contentclass_id integer DEFAULT '0' NOT NULL,
    identifier character varying(50) DEFAULT '' NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    data_type_string character varying(50) DEFAULT '' NOT NULL,
    is_searchable integer DEFAULT '0' NOT NULL,
    is_required integer DEFAULT '0' NOT NULL,
    placement integer DEFAULT '0' NOT NULL,
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
    data_text4 character varying(255),
    data_text5 text,
    is_information_collector integer DEFAULT '0' NOT NULL,
    can_translate integer DEFAULT '1'
);


--
-- TOC entry 84 (OID 39225)
-- Name: ezcontentclass_classgroup; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentclass_classgroup (
    contentclass_id integer DEFAULT '0' NOT NULL,
    contentclass_version integer DEFAULT '0' NOT NULL,
    group_id integer DEFAULT '0' NOT NULL,
    group_name character varying(255)
);


--
-- TOC entry 85 (OID 39230)
-- Name: ezcontentclassgroup; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentclassgroup (
    id integer DEFAULT nextval('ezcontentclassgroup_s'::text) NOT NULL,
    name character varying(255),
    creator_id integer DEFAULT '0' NOT NULL,
    modifier_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 86 (OID 39237)
-- Name: ezcontentobject; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentobject (
    id integer DEFAULT nextval('ezcontentobject_s'::text) NOT NULL,
    owner_id integer DEFAULT '0' NOT NULL,
    section_id integer DEFAULT '0' NOT NULL,
    contentclass_id integer DEFAULT '0' NOT NULL,
    name character varying(255),
    current_version integer,
    is_published integer,
    published integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL,
    status integer DEFAULT '0',
    remote_id character varying(100)
);


--
-- TOC entry 87 (OID 39246)
-- Name: ezcontentobject_attribute; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentobject_attribute (
    id integer DEFAULT nextval('ezcontentobject_attribute_s'::text) NOT NULL,
    language_code character varying(20) DEFAULT '' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    contentobject_id integer DEFAULT '0' NOT NULL,
    contentclassattribute_id integer DEFAULT '0' NOT NULL,
    data_text text,
    data_int integer,
    data_float double precision,
    attribute_original_id integer DEFAULT '0',
    sort_key_int integer DEFAULT '0' NOT NULL,
    sort_key_string character varying(50) DEFAULT '' NOT NULL
);


--
-- TOC entry 88 (OID 39259)
-- Name: ezcontentobject_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentobject_link (
    id integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
    from_contentobject_id integer DEFAULT '0' NOT NULL,
    from_contentobject_version integer DEFAULT '0' NOT NULL,
    to_contentobject_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 89 (OID 39265)
-- Name: ezcontentobject_name; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentobject_name (
    contentobject_id integer DEFAULT '0' NOT NULL,
    name character varying(255),
    content_version integer DEFAULT '0' NOT NULL,
    content_translation character varying(20) DEFAULT '' NOT NULL,
    real_translation character varying(20)
);


--
-- TOC entry 90 (OID 39270)
-- Name: ezcontentobject_tree; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentobject_tree (
    node_id integer DEFAULT nextval('ezcontentobject_tree_s'::text) NOT NULL,
    parent_node_id integer DEFAULT '0' NOT NULL,
    contentobject_id integer,
    contentobject_version integer,
    contentobject_is_published integer,
    depth integer DEFAULT '0' NOT NULL,
    path_string character varying(255) DEFAULT '' NOT NULL,
    sort_field integer DEFAULT '1',
    sort_order integer DEFAULT '1',
    priority integer DEFAULT '0' NOT NULL,
    path_identification_string text,
    main_node_id integer
);


--
-- TOC entry 91 (OID 39282)
-- Name: ezcontentobject_version; Type: TABLE; Schema: public; Owner: postgres
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
    user_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 92 (OID 39292)
-- Name: ezdiscountrule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezdiscountrule (
    id integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 93 (OID 39296)
-- Name: ezdiscountsubrule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezdiscountsubrule (
    id integer DEFAULT nextval('ezdiscountsubrule_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    discountrule_id integer DEFAULT '0' NOT NULL,
    discount_percent double precision,
    limitation character(1)
);


--
-- TOC entry 94 (OID 39301)
-- Name: ezdiscountsubrule_value; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezdiscountsubrule_value (
    discountsubrule_id integer DEFAULT '0' NOT NULL,
    value integer DEFAULT '0' NOT NULL,
    issection integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 95 (OID 39306)
-- Name: ezenumobjectvalue; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezenumobjectvalue (
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_version integer DEFAULT '0' NOT NULL,
    enumid integer DEFAULT '0' NOT NULL,
    enumelement character varying(255) DEFAULT '' NOT NULL,
    enumvalue character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 96 (OID 39313)
-- Name: ezenumvalue; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezenumvalue (
    id integer DEFAULT nextval('ezenumvalue_s'::text) NOT NULL,
    contentclass_attribute_id integer DEFAULT '0' NOT NULL,
    contentclass_attribute_version integer DEFAULT '0' NOT NULL,
    enumelement character varying(255) DEFAULT '' NOT NULL,
    enumvalue character varying(255) DEFAULT '' NOT NULL,
    placement integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 97 (OID 39321)
-- Name: ezforgot_password; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezforgot_password (
    id integer DEFAULT nextval('ezforgot_password_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    hash_key character varying(32) DEFAULT '' NOT NULL,
    "time" integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 98 (OID 39327)
-- Name: ezgeneral_digest_user_settings; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezgeneral_digest_user_settings (
    id integer DEFAULT nextval('ezgeneral_digest_user_settings_s'::text) NOT NULL,
    address character varying(255) DEFAULT '' NOT NULL,
    receive_digest integer DEFAULT '0' NOT NULL,
    digest_type integer DEFAULT '0' NOT NULL,
    "day" character varying(255) DEFAULT '' NOT NULL,
    "time" character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 99 (OID 39335)
-- Name: ezimage; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezimage (
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    filename character varying(255) DEFAULT '' NOT NULL,
    original_filename character varying(255) DEFAULT '' NOT NULL,
    mime_type character varying(50) DEFAULT '' NOT NULL,
    alternative_text character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 100 (OID 39343)
-- Name: ezimagevariation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezimagevariation (
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    filename character varying(255) DEFAULT '' NOT NULL,
    additional_path character varying(255),
    requested_width integer DEFAULT '0' NOT NULL,
    requested_height integer DEFAULT '0' NOT NULL,
    width integer DEFAULT '0' NOT NULL,
    height integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 101 (OID 39352)
-- Name: ezinfocollection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezinfocollection (
    id integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
    contentobject_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 102 (OID 39357)
-- Name: ezinfocollection_attribute; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezinfocollection_attribute (
    id integer DEFAULT nextval('ezinfocollection_attribute_s'::text) NOT NULL,
    informationcollection_id integer DEFAULT '0' NOT NULL,
    data_text text,
    data_int integer,
    data_float double precision,
    contentclass_attribute_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 103 (OID 39365)
-- Name: ezkeyword; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezkeyword (
    id integer DEFAULT nextval('ezkeyword_s'::text) NOT NULL,
    keyword character varying(255),
    class_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 104 (OID 39369)
-- Name: ezkeyword_attribute_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezkeyword_attribute_link (
    id integer DEFAULT nextval('ezkeyword_attribute_link_s'::text) NOT NULL,
    keyword_id integer DEFAULT '0' NOT NULL,
    objectattribute_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 105 (OID 39374)
-- Name: ezmedia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezmedia (
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    filename character varying(255) DEFAULT '' NOT NULL,
    original_filename character varying(255) DEFAULT '' NOT NULL,
    mime_type character varying(50) DEFAULT '' NOT NULL,
    width integer,
    height integer,
    has_controller integer,
    is_autoplay integer,
    pluginspage character varying(255),
    quality character varying(50),
    controls character varying(50),
    is_loop integer
);


--
-- TOC entry 106 (OID 39381)
-- Name: ezmessage; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezmessage (
    id integer DEFAULT nextval('ezmessage_s'::text) NOT NULL,
    send_method character varying(50) DEFAULT '' NOT NULL,
    send_weekday character varying(50) DEFAULT '' NOT NULL,
    send_time character varying(50) DEFAULT '' NOT NULL,
    destination_address character varying(50) DEFAULT '' NOT NULL,
    title character varying(255) DEFAULT '' NOT NULL,
    body text,
    is_sent integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 107 (OID 39393)
-- Name: ezmodule_run; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezmodule_run (
    id integer DEFAULT nextval('ezmodule_run_s'::text) NOT NULL,
    workflow_process_id integer,
    module_name character varying(255),
    function_name character varying(255),
    module_data text
);


--
-- TOC entry 108 (OID 39399)
-- Name: eznode_assignment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE eznode_assignment (
    id integer DEFAULT nextval('eznode_assignment_s'::text) NOT NULL,
    contentobject_id integer,
    contentobject_version integer,
    parent_node integer,
    sort_field integer DEFAULT '1',
    sort_order integer DEFAULT '1',
    is_main integer DEFAULT '0' NOT NULL,
    from_node_id integer DEFAULT '0',
    remote_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 109 (OID 39407)
-- Name: eznotificationcollection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE eznotificationcollection (
    id integer DEFAULT nextval('eznotificationcollection_s'::text) NOT NULL,
    event_id integer DEFAULT '0' NOT NULL,
    "handler" character varying(255) DEFAULT '' NOT NULL,
    transport character varying(255) DEFAULT '' NOT NULL,
    data_subject text NOT NULL,
    data_text text NOT NULL
);


--
-- TOC entry 110 (OID 39416)
-- Name: eznotificationcollection_item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE eznotificationcollection_item (
    id integer DEFAULT nextval('eznotificationcollection_item_s'::text) NOT NULL,
    collection_id integer DEFAULT '0' NOT NULL,
    event_id integer DEFAULT '0' NOT NULL,
    address character varying(255) DEFAULT '' NOT NULL,
    send_date integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 111 (OID 39423)
-- Name: eznotificationevent; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE eznotificationevent (
    id integer DEFAULT nextval('eznotificationevent_s'::text) NOT NULL,
    status integer DEFAULT '0' NOT NULL,
    event_type_string character varying(255) DEFAULT '' NOT NULL,
    data_int1 integer DEFAULT '0' NOT NULL,
    data_int2 integer DEFAULT '0' NOT NULL,
    data_int3 integer DEFAULT '0' NOT NULL,
    data_int4 integer DEFAULT '0' NOT NULL,
    data_text1 text NOT NULL,
    data_text2 text NOT NULL,
    data_text3 text NOT NULL,
    data_text4 text NOT NULL
);


--
-- TOC entry 112 (OID 39435)
-- Name: ezoperation_memento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezoperation_memento (
    id integer DEFAULT nextval('ezoperation_memento_s'::text) NOT NULL,
    memento_key character varying(32) DEFAULT '' NOT NULL,
    memento_data text NOT NULL,
    main integer DEFAULT '0' NOT NULL,
    main_key character varying(32) DEFAULT '' NOT NULL
);


--
-- TOC entry 113 (OID 39444)
-- Name: ezorder; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezorder (
    id integer DEFAULT nextval('ezorder_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    productcollection_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    is_temporary integer DEFAULT '1' NOT NULL,
    order_nr integer DEFAULT '0' NOT NULL,
    data_text_2 text,
    data_text_1 text,
    account_identifier character varying(100) DEFAULT 'default' NOT NULL,
    ignore_vat integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 114 (OID 39457)
-- Name: ezorder_item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezorder_item (
    id integer DEFAULT nextval('ezorder_item_s'::text) NOT NULL,
    order_id integer DEFAULT '0' NOT NULL,
    description character varying(255),
    price double precision,
    vat_value integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 115 (OID 39462)
-- Name: ezpolicy; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezpolicy (
    id integer DEFAULT nextval('ezpolicy_s'::text) NOT NULL,
    role_id integer,
    function_name character varying(255),
    module_name character varying(255),
    limitation character(1)
);


--
-- TOC entry 116 (OID 39465)
-- Name: ezpolicy_limitation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezpolicy_limitation (
    id integer DEFAULT nextval('ezpolicy_limitation_s'::text) NOT NULL,
    policy_id integer,
    identifier character varying(255) DEFAULT '' NOT NULL,
    role_id integer,
    function_name character varying(255),
    module_name character varying(255)
);


--
-- TOC entry 117 (OID 39469)
-- Name: ezpolicy_limitation_value; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezpolicy_limitation_value (
    id integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
    limitation_id integer,
    value character varying(255)
);


--
-- TOC entry 118 (OID 39472)
-- Name: ezpreferences; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezpreferences (
    id integer DEFAULT nextval('ezpreferences_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    name character varying(100),
    value character varying(100)
);


--
-- TOC entry 119 (OID 39476)
-- Name: ezproductcollection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezproductcollection (
    id integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
    created integer
);


--
-- TOC entry 120 (OID 39479)
-- Name: ezproductcollection_item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezproductcollection_item (
    id integer DEFAULT nextval('ezproductcollection_item_s'::text) NOT NULL,
    productcollection_id integer DEFAULT '0' NOT NULL,
    contentobject_id integer DEFAULT '0' NOT NULL,
    item_count integer DEFAULT '0' NOT NULL,
    price double precision,
    is_vat_inc integer,
    vat_value double precision,
    discount double precision
);


--
-- TOC entry 121 (OID 39485)
-- Name: ezproductcollection_item_opt; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezproductcollection_item_opt (
    id integer DEFAULT nextval('ezproductcollection_item_opt_s'::text) NOT NULL,
    item_id integer DEFAULT '0' NOT NULL,
    option_item_id integer DEFAULT '0' NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    value character varying(255) DEFAULT '' NOT NULL,
    price double precision DEFAULT '0' NOT NULL,
    object_attribute_id integer
);


--
-- TOC entry 122 (OID 39493)
-- Name: ezrole; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezrole (
    id integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
    "version" integer DEFAULT '0',
    name character varying(255) DEFAULT '' NOT NULL,
    value character(1)
);


--
-- TOC entry 123 (OID 39498)
-- Name: ezsearch_object_word_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsearch_object_word_link (
    id integer DEFAULT nextval('ezsearch_object_word_link_s'::text) NOT NULL,
    contentobject_id integer DEFAULT '0' NOT NULL,
    word_id integer DEFAULT '0' NOT NULL,
    frequency double precision DEFAULT '0' NOT NULL,
    placement integer DEFAULT '0' NOT NULL,
    prev_word_id integer DEFAULT '0' NOT NULL,
    next_word_id integer DEFAULT '0' NOT NULL,
    contentclass_id integer DEFAULT '0' NOT NULL,
    published integer DEFAULT '0' NOT NULL,
    section_id integer DEFAULT '0' NOT NULL,
    contentclass_attribute_id integer DEFAULT '0' NOT NULL,
    identifier character varying(255) DEFAULT '' NOT NULL,
    integer_value integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 124 (OID 39513)
-- Name: ezsearch_return_count; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsearch_return_count (
    id integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
    phrase_id integer DEFAULT '0' NOT NULL,
    "time" integer DEFAULT '0' NOT NULL,
    count integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 125 (OID 39519)
-- Name: ezsearch_search_phrase; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsearch_search_phrase (
    id integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
    phrase character varying(250)
);


--
-- TOC entry 126 (OID 39522)
-- Name: ezsearch_word; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsearch_word (
    id integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
    word character varying(150),
    object_count integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 127 (OID 39526)
-- Name: ezsection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsection (
    id integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
    name character varying(255),
    locale character varying(255),
    navigation_part_identifier character varying(100) DEFAULT 'ezcontentnavigationpart'
);


--
-- TOC entry 128 (OID 39530)
-- Name: ezsession; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsession (
    session_key character varying(32) DEFAULT '' NOT NULL,
    expiration_time bigint DEFAULT '0' NOT NULL,
    data text NOT NULL,
    CONSTRAINT ezsession_expiration_time CHECK ((expiration_time >= 0))
);


--
-- TOC entry 129 (OID 39538)
-- Name: ezsubtree_notification_rule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsubtree_notification_rule (
    id integer DEFAULT nextval('ezsubtree_notification_rule_s'::text) NOT NULL,
    address character varying(255) DEFAULT '' NOT NULL,
    use_digest integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 130 (OID 39544)
-- Name: eztrigger; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE eztrigger (
    id integer DEFAULT nextval('eztrigger_s'::text) NOT NULL,
    name character varying(255),
    module_name character varying(200) DEFAULT '' NOT NULL,
    function_name character varying(200) DEFAULT '' NOT NULL,
    connect_type character(1) DEFAULT '' NOT NULL,
    workflow_id integer
);


--
-- TOC entry 131 (OID 39550)
-- Name: ezurl; Type: TABLE; Schema: public; Owner: postgres
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
-- TOC entry 132 (OID 39558)
-- Name: ezurl_object_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezurl_object_link (
    url_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_version integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 133 (OID 39563)
-- Name: ezurlalias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezurlalias (
    id integer DEFAULT nextval('ezurlalias_s'::text) NOT NULL,
    source_url text NOT NULL,
    source_md5 character varying(32),
    destination_url text NOT NULL,
    is_internal integer DEFAULT '1' NOT NULL,
    forward_to_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 134 (OID 39571)
-- Name: ezuser; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser (
    contentobject_id integer DEFAULT '0' NOT NULL,
    login character varying(150) DEFAULT '' NOT NULL,
    email character varying(150) DEFAULT '' NOT NULL,
    password_hash_type integer DEFAULT '1' NOT NULL,
    password_hash character varying(50)
);


--
-- TOC entry 135 (OID 39577)
-- Name: ezuser_accountkey; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_accountkey (
    id integer DEFAULT nextval('ezuser_accountkey_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    hash_key character varying(32) DEFAULT '' NOT NULL,
    "time" integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 136 (OID 39583)
-- Name: ezuser_discountrule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_discountrule (
    id integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
    discountrule_id integer,
    contentobject_id integer,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 137 (OID 39587)
-- Name: ezuser_role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_role (
    id integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
    role_id integer,
    contentobject_id integer
);


--
-- TOC entry 138 (OID 39590)
-- Name: ezuser_setting; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_setting (
    user_id integer DEFAULT '0' NOT NULL,
    is_enabled integer DEFAULT '0' NOT NULL,
    max_login integer
);


--
-- TOC entry 139 (OID 39594)
-- Name: ezvattype; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezvattype (
    id integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    percentage double precision
);


--
-- TOC entry 140 (OID 39598)
-- Name: ezwaituntildatevalue; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezwaituntildatevalue (
    id integer DEFAULT nextval('ezwaituntildatevalue_s'::text) NOT NULL,
    workflow_event_id integer DEFAULT '0' NOT NULL,
    workflow_event_version integer DEFAULT '0' NOT NULL,
    contentclass_id integer DEFAULT '0' NOT NULL,
    contentclass_attribute_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 141 (OID 39605)
-- Name: ezwishlist; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezwishlist (
    id integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    productcollection_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 142 (OID 39610)
-- Name: ezworkflow; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezworkflow (
    id integer DEFAULT nextval('ezworkflow_s'::text) NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    is_enabled integer DEFAULT '0' NOT NULL,
    workflow_type_string character varying(50) DEFAULT '' NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    creator_id integer DEFAULT '0' NOT NULL,
    modifier_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 143 (OID 39621)
-- Name: ezworkflow_assign; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezworkflow_assign (
    id integer DEFAULT nextval('ezworkflow_assign_s'::text) NOT NULL,
    workflow_id integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL,
    access_type integer DEFAULT '0' NOT NULL,
    as_tree integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 144 (OID 39628)
-- Name: ezworkflow_event; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezworkflow_event (
    id integer DEFAULT nextval('ezworkflow_event_s'::text) NOT NULL,
    "version" integer DEFAULT '0' NOT NULL,
    workflow_id integer DEFAULT '0' NOT NULL,
    workflow_type_string character varying(50) DEFAULT '' NOT NULL,
    description character varying(50) DEFAULT '' NOT NULL,
    data_int1 integer,
    data_int2 integer,
    data_int3 integer,
    data_int4 integer,
    data_text1 character varying(50),
    data_text2 character varying(50),
    data_text3 character varying(50),
    data_text4 character varying(50),
    placement integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 145 (OID 39636)
-- Name: ezworkflow_group; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezworkflow_group (
    id integer DEFAULT nextval('ezworkflow_group_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    creator_id integer DEFAULT '0' NOT NULL,
    modifier_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 146 (OID 39644)
-- Name: ezworkflow_group_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezworkflow_group_link (
    workflow_id integer DEFAULT '0' NOT NULL,
    group_id integer DEFAULT '0' NOT NULL,
    workflow_version integer DEFAULT '0' NOT NULL,
    group_name character varying(255)
);


--
-- TOC entry 147 (OID 39649)
-- Name: ezworkflow_process; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezworkflow_process (
    id integer DEFAULT nextval('ezworkflow_process_s'::text) NOT NULL,
    process_key character varying(32) DEFAULT '' NOT NULL,
    workflow_id integer DEFAULT '0' NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    content_id integer DEFAULT '0' NOT NULL,
    content_version integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL,
    session_key character varying(32) DEFAULT '0' NOT NULL,
    event_id integer DEFAULT '0' NOT NULL,
    event_position integer DEFAULT '0' NOT NULL,
    last_event_id integer DEFAULT '0' NOT NULL,
    last_event_position integer DEFAULT '0' NOT NULL,
    last_event_status integer DEFAULT '0' NOT NULL,
    event_status integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL,
    modified integer DEFAULT '0' NOT NULL,
    activation_date integer,
    event_state integer,
    status integer,
    parameters text,
    memento_key character varying(32)
);


--
-- TOC entry 153 (OID 39670)
-- Name: ezcollab_group_path62; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcollab_group_path62 ON ezcollab_group USING btree (path_string);


--
-- TOC entry 152 (OID 39671)
-- Name: ezcollab_group_depth63; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcollab_group_depth63 ON ezcollab_group USING btree (depth);


--
-- TOC entry 164 (OID 39672)
-- Name: ezcontentbrowsebookmark_user228; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentbrowsebookmark_user228 ON ezcontentbrowsebookmark USING btree (user_id);


--
-- TOC entry 166 (OID 39673)
-- Name: ezcontentbrowserecent_user243; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentbrowserecent_user243 ON ezcontentbrowserecent USING btree (user_id);


--
-- TOC entry 168 (OID 39674)
-- Name: ezcontentclass_version262; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentclass_version262 ON ezcontentclass USING btree ("version");


--
-- TOC entry 174 (OID 39675)
-- Name: ezcontentobject_attribute_contentobject_id364; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_attribute_contentobject_id364 ON ezcontentobject_attribute USING btree (contentobject_id);


--
-- TOC entry 175 (OID 39676)
-- Name: ezcontentobject_attribute_language_code365; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_attribute_language_code365 ON ezcontentobject_attribute USING btree (language_code);


--
-- TOC entry 176 (OID 39677)
-- Name: sort_key_int366; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sort_key_int366 ON ezcontentobject_attribute USING btree (sort_key_int);


--
-- TOC entry 177 (OID 39678)
-- Name: sort_key_string367; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sort_key_string367 ON ezcontentobject_attribute USING btree (sort_key_string);


--
-- TOC entry 184 (OID 39679)
-- Name: ezcontentobject_tree_path416; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_path416 ON ezcontentobject_tree USING btree (path_string);


--
-- TOC entry 183 (OID 39680)
-- Name: ezcontentobject_tree_p_node_id417; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_p_node_id417 ON ezcontentobject_tree USING btree (parent_node_id);


--
-- TOC entry 181 (OID 39681)
-- Name: ezcontentobject_tree_co_id418; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_co_id418 ON ezcontentobject_tree USING btree (contentobject_id);


--
-- TOC entry 182 (OID 39682)
-- Name: ezcontentobject_tree_depth419; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_depth419 ON ezcontentobject_tree USING btree (depth);


--
-- TOC entry 190 (OID 39683)
-- Name: ezenumobjectvalue_co_attr_id_co_attr_ver489; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver489 ON ezenumobjectvalue USING btree (contentobject_attribute_id, contentobject_attribute_version);


--
-- TOC entry 192 (OID 39684)
-- Name: ezenumvalue_co_cl_attr_id_co_class_att_ver505; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver505 ON ezenumvalue USING btree (contentclass_attribute_id, contentclass_attribute_version);


--
-- TOC entry 204 (OID 39685)
-- Name: ezmodule_run_workflow_process_id_s670; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s670 ON ezmodule_run USING btree (workflow_process_id);


--
-- TOC entry 216 (OID 39686)
-- Name: ezpreferences_name839; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezpreferences_name839 ON ezpreferences USING btree (name);


--
-- TOC entry 225 (OID 39687)
-- Name: ezsearch_object_word_link_object919; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_object919 ON ezsearch_object_word_link USING btree (contentobject_id);


--
-- TOC entry 226 (OID 39688)
-- Name: ezsearch_object_word_link_word920; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_word920 ON ezsearch_object_word_link USING btree (word_id);


--
-- TOC entry 222 (OID 39689)
-- Name: ezsearch_object_word_link_frequency921; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_frequency921 ON ezsearch_object_word_link USING btree (frequency);


--
-- TOC entry 223 (OID 39690)
-- Name: ezsearch_object_word_link_identifier922; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_identifier922 ON ezsearch_object_word_link USING btree (identifier);


--
-- TOC entry 224 (OID 39691)
-- Name: ezsearch_object_word_link_integer_value923; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_integer_value923 ON ezsearch_object_word_link USING btree (integer_value);


--
-- TOC entry 230 (OID 39692)
-- Name: ezsearch_word960; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_word960 ON ezsearch_word USING btree (word);


--
-- TOC entry 232 (OID 39693)
-- Name: expiration_time986; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX expiration_time986 ON ezsession USING btree (expiration_time);


--
-- TOC entry 236 (OID 39694)
-- Name: eztrigger_def_id1015; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX eztrigger_def_id1015 ON eztrigger USING btree (module_name, function_name, connect_type);


--
-- TOC entry 240 (OID 39695)
-- Name: ezurlalias_source_md51059; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezurlalias_source_md51059 ON ezurlalias USING btree (source_md5);


--
-- TOC entry 245 (OID 39696)
-- Name: ezuser_role_contentobject_id1112; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezuser_role_contentobject_id1112 ON ezuser_role USING btree (contentobject_id);


--
-- TOC entry 248 (OID 39697)
-- Name: ezwaituntildateevalue_wf_ev_id_wf_ver1151; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezwaituntildateevalue_wf_ev_id_wf_ver1151 ON ezwaituntildatevalue USING btree (workflow_event_id, workflow_event_version);


--
-- TOC entry 148 (OID 39698)
-- Name: ezapprove_items12_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezapprove_items
    ADD CONSTRAINT ezapprove_items12_key PRIMARY KEY (id);


--
-- TOC entry 149 (OID 39700)
-- Name: ezbasket24_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezbasket
    ADD CONSTRAINT ezbasket24_key PRIMARY KEY (id);


--
-- TOC entry 150 (OID 39702)
-- Name: ezbinaryfile36_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezbinaryfile
    ADD CONSTRAINT ezbinaryfile36_key PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 151 (OID 39704)
-- Name: ezcollab_group50_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_group
    ADD CONSTRAINT ezcollab_group50_key PRIMARY KEY (id);


--
-- TOC entry 154 (OID 39706)
-- Name: ezcollab_item71_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item
    ADD CONSTRAINT ezcollab_item71_key PRIMARY KEY (id);


--
-- TOC entry 155 (OID 39708)
-- Name: ezcollab_item_group_link95_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_group_link
    ADD CONSTRAINT ezcollab_item_group_link95_key PRIMARY KEY (collaboration_id, group_id, user_id);


--
-- TOC entry 156 (OID 39710)
-- Name: ezcollab_item_message_link112_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_message_link
    ADD CONSTRAINT ezcollab_item_message_link112_key PRIMARY KEY (id);


--
-- TOC entry 157 (OID 39712)
-- Name: ezcollab_item_participant_link128_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_participant_link
    ADD CONSTRAINT ezcollab_item_participant_link128_key PRIMARY KEY (collaboration_id, participant_id);


--
-- TOC entry 158 (OID 39714)
-- Name: ezcollab_item_status146_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_status
    ADD CONSTRAINT ezcollab_item_status146_key PRIMARY KEY (collaboration_id, user_id);


--
-- TOC entry 159 (OID 39716)
-- Name: ezcollab_notification_rule160_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_notification_rule
    ADD CONSTRAINT ezcollab_notification_rule160_key PRIMARY KEY (id);


--
-- TOC entry 160 (OID 39718)
-- Name: ezcollab_profile172_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_profile
    ADD CONSTRAINT ezcollab_profile172_key PRIMARY KEY (id);


--
-- TOC entry 161 (OID 39720)
-- Name: ezcollab_simple_message187_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_simple_message
    ADD CONSTRAINT ezcollab_simple_message187_key PRIMARY KEY (id);


--
-- TOC entry 162 (OID 39722)
-- Name: ezcontent_translation210_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontent_translation
    ADD CONSTRAINT ezcontent_translation210_key PRIMARY KEY (id);


--
-- TOC entry 163 (OID 39724)
-- Name: ezcontentbrowsebookmark222_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentbrowsebookmark
    ADD CONSTRAINT ezcontentbrowsebookmark222_key PRIMARY KEY (id);


--
-- TOC entry 165 (OID 39726)
-- Name: ezcontentbrowserecent236_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentbrowserecent
    ADD CONSTRAINT ezcontentbrowserecent236_key PRIMARY KEY (id);


--
-- TOC entry 167 (OID 39728)
-- Name: ezcontentclass251_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclass
    ADD CONSTRAINT ezcontentclass251_key PRIMARY KEY (id, "version");


--
-- TOC entry 169 (OID 39730)
-- Name: ezcontentclass_attribute270_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclass_attribute
    ADD CONSTRAINT ezcontentclass_attribute270_key PRIMARY KEY (id, "version");


--
-- TOC entry 170 (OID 39732)
-- Name: ezcontentclass_classgroup303_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclass_classgroup
    ADD CONSTRAINT ezcontentclass_classgroup303_key PRIMARY KEY (contentclass_id, contentclass_version, group_id);


--
-- TOC entry 171 (OID 39734)
-- Name: ezcontentclassgroup316_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclassgroup
    ADD CONSTRAINT ezcontentclassgroup316_key PRIMARY KEY (id);


--
-- TOC entry 172 (OID 39736)
-- Name: ezcontentobject331_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject
    ADD CONSTRAINT ezcontentobject331_key PRIMARY KEY (id);


--
-- TOC entry 173 (OID 39738)
-- Name: ezcontentobject_attribute351_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_attribute
    ADD CONSTRAINT ezcontentobject_attribute351_key PRIMARY KEY (id, "version");


--
-- TOC entry 178 (OID 39740)
-- Name: ezcontentobject_link375_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_link
    ADD CONSTRAINT ezcontentobject_link375_key PRIMARY KEY (id);


--
-- TOC entry 179 (OID 39742)
-- Name: ezcontentobject_name388_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_name
    ADD CONSTRAINT ezcontentobject_name388_key PRIMARY KEY (contentobject_id, content_version, content_translation);


--
-- TOC entry 180 (OID 39744)
-- Name: ezcontentobject_tree402_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_tree
    ADD CONSTRAINT ezcontentobject_tree402_key PRIMARY KEY (node_id);


--
-- TOC entry 185 (OID 39746)
-- Name: ezcontentobject_version427_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_version
    ADD CONSTRAINT ezcontentobject_version427_key PRIMARY KEY (id);


--
-- TOC entry 186 (OID 39748)
-- Name: ezdiscountrule445_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezdiscountrule
    ADD CONSTRAINT ezdiscountrule445_key PRIMARY KEY (id);


--
-- TOC entry 187 (OID 39750)
-- Name: ezdiscountsubrule456_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezdiscountsubrule
    ADD CONSTRAINT ezdiscountsubrule456_key PRIMARY KEY (id);


--
-- TOC entry 188 (OID 39752)
-- Name: ezdiscountsubrule_value470_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezdiscountsubrule_value
    ADD CONSTRAINT ezdiscountsubrule_value470_key PRIMARY KEY (discountsubrule_id, value, issection);


--
-- TOC entry 189 (OID 39754)
-- Name: ezenumobjectvalue482_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezenumobjectvalue
    ADD CONSTRAINT ezenumobjectvalue482_key PRIMARY KEY (contentobject_attribute_id, contentobject_attribute_version, enumid);


--
-- TOC entry 191 (OID 39756)
-- Name: ezenumvalue497_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezenumvalue
    ADD CONSTRAINT ezenumvalue497_key PRIMARY KEY (id, contentclass_attribute_id, contentclass_attribute_version);


--
-- TOC entry 193 (OID 39758)
-- Name: ezforgot_password513_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezforgot_password
    ADD CONSTRAINT ezforgot_password513_key PRIMARY KEY (id);


--
-- TOC entry 194 (OID 39760)
-- Name: ezgeneral_digest_user_settings526_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezgeneral_digest_user_settings
    ADD CONSTRAINT ezgeneral_digest_user_settings526_key PRIMARY KEY (id);


--
-- TOC entry 195 (OID 39762)
-- Name: ezimage541_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezimage
    ADD CONSTRAINT ezimage541_key PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 196 (OID 39764)
-- Name: ezimagevariation556_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezimagevariation
    ADD CONSTRAINT ezimagevariation556_key PRIMARY KEY (contentobject_attribute_id, "version", requested_width, requested_height);


--
-- TOC entry 197 (OID 39766)
-- Name: ezinfocollection573_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezinfocollection
    ADD CONSTRAINT ezinfocollection573_key PRIMARY KEY (id);


--
-- TOC entry 198 (OID 39768)
-- Name: ezinfocollection_attribute585_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezinfocollection_attribute
    ADD CONSTRAINT ezinfocollection_attribute585_key PRIMARY KEY (id);


--
-- TOC entry 199 (OID 39770)
-- Name: ezkeyword600_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezkeyword
    ADD CONSTRAINT ezkeyword600_key PRIMARY KEY (id);


--
-- TOC entry 200 (OID 39772)
-- Name: ezkeyword_attribute_link612_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezkeyword_attribute_link
    ADD CONSTRAINT ezkeyword_attribute_link612_key PRIMARY KEY (id);


--
-- TOC entry 201 (OID 39774)
-- Name: ezmedia624_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezmedia
    ADD CONSTRAINT ezmedia624_key PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 202 (OID 39776)
-- Name: ezmessage646_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezmessage
    ADD CONSTRAINT ezmessage646_key PRIMARY KEY (id);


--
-- TOC entry 203 (OID 39778)
-- Name: ezmodule_run663_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezmodule_run
    ADD CONSTRAINT ezmodule_run663_key PRIMARY KEY (id);


--
-- TOC entry 205 (OID 39780)
-- Name: eznode_assignment678_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznode_assignment
    ADD CONSTRAINT eznode_assignment678_key PRIMARY KEY (id);


--
-- TOC entry 206 (OID 39782)
-- Name: eznotificationcollection696_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznotificationcollection
    ADD CONSTRAINT eznotificationcollection696_key PRIMARY KEY (id);


--
-- TOC entry 207 (OID 39784)
-- Name: eznotificationcollection_item711_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznotificationcollection_item
    ADD CONSTRAINT eznotificationcollection_item711_key PRIMARY KEY (id);


--
-- TOC entry 208 (OID 39786)
-- Name: eznotificationevent725_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznotificationevent
    ADD CONSTRAINT eznotificationevent725_key PRIMARY KEY (id);


--
-- TOC entry 209 (OID 39788)
-- Name: ezoperation_memento745_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezoperation_memento
    ADD CONSTRAINT ezoperation_memento745_key PRIMARY KEY (id, memento_key);


--
-- TOC entry 210 (OID 39790)
-- Name: ezorder759_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezorder
    ADD CONSTRAINT ezorder759_key PRIMARY KEY (id);


--
-- TOC entry 211 (OID 39792)
-- Name: ezorder_item778_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezorder_item
    ADD CONSTRAINT ezorder_item778_key PRIMARY KEY (id);


--
-- TOC entry 212 (OID 39794)
-- Name: ezpolicy792_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpolicy
    ADD CONSTRAINT ezpolicy792_key PRIMARY KEY (id);


--
-- TOC entry 213 (OID 39796)
-- Name: ezpolicy_limitation806_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpolicy_limitation
    ADD CONSTRAINT ezpolicy_limitation806_key PRIMARY KEY (id);


--
-- TOC entry 214 (OID 39798)
-- Name: ezpolicy_limitation_value821_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpolicy_limitation_value
    ADD CONSTRAINT ezpolicy_limitation_value821_key PRIMARY KEY (id);


--
-- TOC entry 215 (OID 39800)
-- Name: ezpreferences833_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpreferences
    ADD CONSTRAINT ezpreferences833_key PRIMARY KEY (id);


--
-- TOC entry 217 (OID 39802)
-- Name: ezproductcollection847_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezproductcollection
    ADD CONSTRAINT ezproductcollection847_key PRIMARY KEY (id);


--
-- TOC entry 218 (OID 39804)
-- Name: ezproductcollection_item858_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezproductcollection_item
    ADD CONSTRAINT ezproductcollection_item858_key PRIMARY KEY (id);


--
-- TOC entry 219 (OID 39806)
-- Name: ezproductcollection_item_opt875_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezproductcollection_item_opt
    ADD CONSTRAINT ezproductcollection_item_opt875_key PRIMARY KEY (id);


--
-- TOC entry 220 (OID 39808)
-- Name: ezrole891_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezrole
    ADD CONSTRAINT ezrole891_key PRIMARY KEY (id);


--
-- TOC entry 221 (OID 39810)
-- Name: ezsearch_object_word_link904_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_object_word_link
    ADD CONSTRAINT ezsearch_object_word_link904_key PRIMARY KEY (id);


--
-- TOC entry 227 (OID 39812)
-- Name: ezsearch_return_count931_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_return_count
    ADD CONSTRAINT ezsearch_return_count931_key PRIMARY KEY (id);


--
-- TOC entry 228 (OID 39814)
-- Name: ezsearch_search_phrase944_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_search_phrase
    ADD CONSTRAINT ezsearch_search_phrase944_key PRIMARY KEY (id);


--
-- TOC entry 229 (OID 39816)
-- Name: ezsearch_word955_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_word
    ADD CONSTRAINT ezsearch_word955_key PRIMARY KEY (id);


--
-- TOC entry 231 (OID 39818)
-- Name: ezsection968_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsection
    ADD CONSTRAINT ezsection968_key PRIMARY KEY (id);


--
-- TOC entry 233 (OID 39820)
-- Name: ezsession981_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsession
    ADD CONSTRAINT ezsession981_key PRIMARY KEY (session_key);


--
-- TOC entry 234 (OID 39822)
-- Name: ezsubtree_notification_rule994_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsubtree_notification_rule
    ADD CONSTRAINT ezsubtree_notification_rule994_key PRIMARY KEY (id);


--
-- TOC entry 235 (OID 39824)
-- Name: eztrigger1007_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eztrigger
    ADD CONSTRAINT eztrigger1007_key PRIMARY KEY (id);


--
-- TOC entry 237 (OID 39826)
-- Name: ezurl1023_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezurl
    ADD CONSTRAINT ezurl1023_key PRIMARY KEY (id);


--
-- TOC entry 238 (OID 39828)
-- Name: ezurl_object_link1039_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezurl_object_link
    ADD CONSTRAINT ezurl_object_link1039_key PRIMARY KEY (url_id, contentobject_attribute_id, contentobject_attribute_version);


--
-- TOC entry 239 (OID 39830)
-- Name: ezurlalias1051_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezurlalias
    ADD CONSTRAINT ezurlalias1051_key PRIMARY KEY (id);


--
-- TOC entry 241 (OID 39832)
-- Name: ezuser1067_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser
    ADD CONSTRAINT ezuser1067_key PRIMARY KEY (contentobject_id);


--
-- TOC entry 242 (OID 39834)
-- Name: ezuser_accountkey1081_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_accountkey
    ADD CONSTRAINT ezuser_accountkey1081_key PRIMARY KEY (id);


--
-- TOC entry 243 (OID 39836)
-- Name: ezuser_discountrule1094_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_discountrule
    ADD CONSTRAINT ezuser_discountrule1094_key PRIMARY KEY (id);


--
-- TOC entry 244 (OID 39838)
-- Name: ezuser_role1107_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_role
    ADD CONSTRAINT ezuser_role1107_key PRIMARY KEY (id);


--
-- TOC entry 246 (OID 39840)
-- Name: ezuser_setting1120_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_setting
    ADD CONSTRAINT ezuser_setting1120_key PRIMARY KEY (user_id);


--
-- TOC entry 247 (OID 39842)
-- Name: ezvattype1132_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezvattype
    ADD CONSTRAINT ezvattype1132_key PRIMARY KEY (id);


--
-- TOC entry 249 (OID 39844)
-- Name: ezwaituntildatevalue1144_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezwaituntildatevalue
    ADD CONSTRAINT ezwaituntildatevalue1144_key PRIMARY KEY (id, workflow_event_id, workflow_event_version);


--
-- TOC entry 250 (OID 39846)
-- Name: ezwishlist1159_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezwishlist
    ADD CONSTRAINT ezwishlist1159_key PRIMARY KEY (id);


--
-- TOC entry 251 (OID 39848)
-- Name: ezworkflow1171_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow
    ADD CONSTRAINT ezworkflow1171_key PRIMARY KEY (id, "version");


--
-- TOC entry 252 (OID 39850)
-- Name: ezworkflow_assign1189_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_assign
    ADD CONSTRAINT ezworkflow_assign1189_key PRIMARY KEY (id);


--
-- TOC entry 253 (OID 39852)
-- Name: ezworkflow_event1203_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_event
    ADD CONSTRAINT ezworkflow_event1203_key PRIMARY KEY (id, "version");


--
-- TOC entry 254 (OID 39854)
-- Name: ezworkflow_group1226_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_group
    ADD CONSTRAINT ezworkflow_group1226_key PRIMARY KEY (id);


--
-- TOC entry 255 (OID 39856)
-- Name: ezworkflow_group_link1241_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_group_link
    ADD CONSTRAINT ezworkflow_group_link1241_key PRIMARY KEY (workflow_id, group_id, workflow_version);


--
-- TOC entry 256 (OID 39858)
-- Name: ezworkflow_process1254_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_process
    ADD CONSTRAINT ezworkflow_process1254_key PRIMARY KEY (id);


