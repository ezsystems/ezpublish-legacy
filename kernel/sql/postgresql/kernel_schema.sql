--
-- PostgreSQL database dump
--

\connect - postgres

SET search_path = public, pg_catalog;

--
-- TOC entry 2 (OID 33190)
-- Name: ezapprove_items_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezapprove_items_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 3 (OID 33192)
-- Name: ezbasket_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezbasket_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 4 (OID 33194)
-- Name: ezcollab_group_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 5 (OID 33196)
-- Name: ezcollab_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 6 (OID 33198)
-- Name: ezcollab_item_message_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_item_message_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 7 (OID 33200)
-- Name: ezcollab_notification_rule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 8 (OID 33202)
-- Name: ezcollab_profile_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_profile_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 9 (OID 33204)
-- Name: ezcollab_simple_message_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcollab_simple_message_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 10 (OID 33206)
-- Name: ezcontent_translation_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontent_translation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 11 (OID 33208)
-- Name: ezcontentbrowsebookmark_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentbrowsebookmark_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 12 (OID 33210)
-- Name: ezcontentbrowserecent_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentbrowserecent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 13 (OID 33212)
-- Name: ezcontentclass_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentclass_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 14 (OID 33214)
-- Name: ezcontentclass_attribute_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentclass_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 15 (OID 33216)
-- Name: ezcontentclassgroup_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentclassgroup_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 16 (OID 33218)
-- Name: ezcontentobject_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 17 (OID 33220)
-- Name: ezcontentobject_attribute_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 18 (OID 33222)
-- Name: ezcontentobject_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 19 (OID 33224)
-- Name: ezcontentobject_tree_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_tree_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 20 (OID 33226)
-- Name: ezcontentobject_version_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezcontentobject_version_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 21 (OID 33228)
-- Name: ezdiscountrule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezdiscountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 22 (OID 33230)
-- Name: ezdiscountsubrule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezdiscountsubrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 23 (OID 33232)
-- Name: ezenumvalue_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezenumvalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 24 (OID 33234)
-- Name: ezforgot_password_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezforgot_password_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 25 (OID 33236)
-- Name: ezgeneral_digest_user_settings_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezgeneral_digest_user_settings_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 26 (OID 33238)
-- Name: ezinfocollection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezinfocollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 27 (OID 33240)
-- Name: ezinfocollection_attribute_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezinfocollection_attribute_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 28 (OID 33242)
-- Name: ezkeyword_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezkeyword_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 29 (OID 33244)
-- Name: ezkeyword_attribute_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezkeyword_attribute_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 30 (OID 33246)
-- Name: ezmessage_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezmessage_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 31 (OID 33248)
-- Name: ezmodule_run_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezmodule_run_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 32 (OID 33250)
-- Name: eznode_assignment_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznode_assignment_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 33 (OID 33252)
-- Name: eznotificationcollection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznotificationcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 34 (OID 33254)
-- Name: eznotificationcollection_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznotificationcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 35 (OID 33256)
-- Name: eznotificationevent_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eznotificationevent_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 36 (OID 33258)
-- Name: ezoperation_memento_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezoperation_memento_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 37 (OID 33260)
-- Name: ezorder_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezorder_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 38 (OID 33262)
-- Name: ezorder_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezorder_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 39 (OID 33264)
-- Name: ezpolicy_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpolicy_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 40 (OID 33266)
-- Name: ezpolicy_limitation_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpolicy_limitation_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 41 (OID 33268)
-- Name: ezpolicy_limitation_value_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpolicy_limitation_value_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 42 (OID 33270)
-- Name: ezpreferences_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezpreferences_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 43 (OID 33272)
-- Name: ezproductcollection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezproductcollection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 44 (OID 33274)
-- Name: ezproductcollection_item_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezproductcollection_item_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 45 (OID 33276)
-- Name: ezproductcollection_item_opt_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezproductcollection_item_opt_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 46 (OID 33278)
-- Name: ezrole_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezrole_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 47 (OID 33280)
-- Name: ezsearch_object_word_link_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_object_word_link_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 48 (OID 33282)
-- Name: ezsearch_return_count_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_return_count_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 49 (OID 33284)
-- Name: ezsearch_search_phrase_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_search_phrase_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 50 (OID 33286)
-- Name: ezsearch_word_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsearch_word_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 51 (OID 33288)
-- Name: ezsection_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsection_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 52 (OID 33290)
-- Name: ezsubtree_notification_rule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezsubtree_notification_rule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 53 (OID 33292)
-- Name: eztrigger_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE eztrigger_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 54 (OID 33294)
-- Name: ezurl_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezurl_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 55 (OID 33296)
-- Name: ezurlalias_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezurlalias_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 56 (OID 33298)
-- Name: ezuser_accountkey_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezuser_accountkey_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 57 (OID 33300)
-- Name: ezuser_discountrule_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezuser_discountrule_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 58 (OID 33302)
-- Name: ezuser_role_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezuser_role_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 59 (OID 33304)
-- Name: ezvattype_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezvattype_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 60 (OID 33306)
-- Name: ezwaituntildatevalue_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezwaituntildatevalue_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 61 (OID 33308)
-- Name: ezwishlist_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezwishlist_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 62 (OID 33310)
-- Name: ezworkflow_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 63 (OID 33312)
-- Name: ezworkflow_assign_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_assign_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 64 (OID 33314)
-- Name: ezworkflow_event_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_event_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 65 (OID 33316)
-- Name: ezworkflow_group_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_group_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 66 (OID 33318)
-- Name: ezworkflow_process_s; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE ezworkflow_process_s
    START 1
    INCREMENT 1
    MAXVALUE 9223372036854775807
    MINVALUE 1
    CACHE 1;


--
-- TOC entry 67 (OID 33320)
-- Name: ezapprove_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezapprove_items (
    id integer DEFAULT nextval('ezapprove_items_s'::text) NOT NULL,
    workflow_process_id integer DEFAULT '0' NOT NULL,
    collaboration_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 68 (OID 33327)
-- Name: ezbasket; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezbasket (
    id integer DEFAULT nextval('ezbasket_s'::text) NOT NULL,
    session_id character varying(255) DEFAULT '' NOT NULL,
    productcollection_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 69 (OID 33334)
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
-- TOC entry 70 (OID 33343)
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
-- TOC entry 71 (OID 33359)
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
-- TOC entry 72 (OID 33378)
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
-- TOC entry 73 (OID 33390)
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
-- TOC entry 74 (OID 33401)
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
-- TOC entry 75 (OID 33414)
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
-- TOC entry 76 (OID 33423)
-- Name: ezcollab_notification_rule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcollab_notification_rule (
    id integer DEFAULT nextval('ezcollab_notification_rule_s'::text) NOT NULL,
    user_id character varying(255) DEFAULT '' NOT NULL,
    collab_identifier character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 77 (OID 33430)
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
-- TOC entry 78 (OID 33442)
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
-- TOC entry 79 (OID 33460)
-- Name: ezcontent_translation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontent_translation (
    id integer DEFAULT nextval('ezcontent_translation_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    locale character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 80 (OID 33467)
-- Name: ezcontentbrowsebookmark; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentbrowsebookmark (
    id integer DEFAULT nextval('ezcontentbrowsebookmark_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 81 (OID 33476)
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
-- TOC entry 82 (OID 33486)
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
-- TOC entry 83 (OID 33498)
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
-- TOC entry 84 (OID 33516)
-- Name: ezcontentclass_classgroup; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentclass_classgroup (
    contentclass_id integer DEFAULT '0' NOT NULL,
    contentclass_version integer DEFAULT '0' NOT NULL,
    group_id integer DEFAULT '0' NOT NULL,
    group_name character varying(255)
);


--
-- TOC entry 85 (OID 33523)
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
-- TOC entry 86 (OID 33532)
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
-- TOC entry 87 (OID 33543)
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
-- TOC entry 88 (OID 33562)
-- Name: ezcontentobject_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezcontentobject_link (
    id integer DEFAULT nextval('ezcontentobject_link_s'::text) NOT NULL,
    from_contentobject_id integer DEFAULT '0' NOT NULL,
    from_contentobject_version integer DEFAULT '0' NOT NULL,
    to_contentobject_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 89 (OID 33570)
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
-- TOC entry 90 (OID 33577)
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
-- TOC entry 91 (OID 33595)
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
-- TOC entry 92 (OID 33607)
-- Name: ezdiscountrule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezdiscountrule (
    id integer DEFAULT nextval('ezdiscountrule_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 93 (OID 33613)
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
-- TOC entry 94 (OID 33620)
-- Name: ezdiscountsubrule_value; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezdiscountsubrule_value (
    discountsubrule_id integer DEFAULT '0' NOT NULL,
    value integer DEFAULT '0' NOT NULL,
    issection integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 95 (OID 33627)
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
-- TOC entry 96 (OID 33637)
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
-- TOC entry 97 (OID 33648)
-- Name: ezforgot_password; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezforgot_password (
    id integer DEFAULT nextval('ezforgot_password_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    hash_key character varying(32) DEFAULT '' NOT NULL,
    "time" integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 98 (OID 33656)
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
-- TOC entry 99 (OID 33666)
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
-- TOC entry 100 (OID 33676)
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
-- TOC entry 101 (OID 33687)
-- Name: ezinfocollection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezinfocollection (
    id integer DEFAULT nextval('ezinfocollection_s'::text) NOT NULL,
    contentobject_id integer DEFAULT '0' NOT NULL,
    created integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 102 (OID 33694)
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
-- TOC entry 103 (OID 33704)
-- Name: ezkeyword; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezkeyword (
    id integer DEFAULT nextval('ezkeyword_s'::text) NOT NULL,
    keyword character varying(255),
    class_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 104 (OID 33710)
-- Name: ezkeyword_attribute_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezkeyword_attribute_link (
    id integer DEFAULT nextval('ezkeyword_attribute_link_s'::text) NOT NULL,
    keyword_id integer DEFAULT '0' NOT NULL,
    objectattribute_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 105 (OID 33717)
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
-- TOC entry 106 (OID 33726)
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
-- TOC entry 107 (OID 33740)
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
-- TOC entry 108 (OID 33749)
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
-- TOC entry 109 (OID 33759)
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
-- TOC entry 110 (OID 33770)
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
-- TOC entry 111 (OID 33779)
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
-- TOC entry 112 (OID 33793)
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
-- TOC entry 113 (OID 33804)
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
-- TOC entry 114 (OID 33819)
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
-- TOC entry 115 (OID 33826)
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
-- TOC entry 116 (OID 33831)
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
-- TOC entry 117 (OID 33837)
-- Name: ezpolicy_limitation_value; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezpolicy_limitation_value (
    id integer DEFAULT nextval('ezpolicy_limitation_value_s'::text) NOT NULL,
    limitation_id integer,
    value character varying(255)
);


--
-- TOC entry 118 (OID 33842)
-- Name: ezpreferences; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezpreferences (
    id integer DEFAULT nextval('ezpreferences_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    name character varying(100),
    value character varying(100)
);


--
-- TOC entry 119 (OID 33849)
-- Name: ezproductcollection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezproductcollection (
    id integer DEFAULT nextval('ezproductcollection_s'::text) NOT NULL,
    created integer
);


--
-- TOC entry 120 (OID 33854)
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
-- TOC entry 121 (OID 33862)
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
-- TOC entry 122 (OID 33872)
-- Name: ezrole; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezrole (
    id integer DEFAULT nextval('ezrole_s'::text) NOT NULL,
    "version" integer DEFAULT '0',
    name character varying(255) DEFAULT '' NOT NULL,
    value character(1)
);


--
-- TOC entry 123 (OID 33879)
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
-- TOC entry 124 (OID 33901)
-- Name: ezsearch_return_count; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsearch_return_count (
    id integer DEFAULT nextval('ezsearch_return_count_s'::text) NOT NULL,
    phrase_id integer DEFAULT '0' NOT NULL,
    "time" integer DEFAULT '0' NOT NULL,
    count integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 125 (OID 33909)
-- Name: ezsearch_search_phrase; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsearch_search_phrase (
    id integer DEFAULT nextval('ezsearch_search_phrase_s'::text) NOT NULL,
    phrase character varying(250)
);


--
-- TOC entry 126 (OID 33914)
-- Name: ezsearch_word; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsearch_word (
    id integer DEFAULT nextval('ezsearch_word_s'::text) NOT NULL,
    word character varying(150),
    object_count integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 127 (OID 33921)
-- Name: ezsection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsection (
    id integer DEFAULT nextval('ezsection_s'::text) NOT NULL,
    name character varying(255),
    locale character varying(255),
    navigation_part_identifier character varying(100) DEFAULT 'ezcontentnavigationpart'
);


--
-- TOC entry 128 (OID 33927)
-- Name: ezsession; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsession (
    session_key character varying(32) DEFAULT '' NOT NULL,
    expiration_time bigint DEFAULT '0' NOT NULL,
    data text NOT NULL,
    CONSTRAINT ezsession_expiration_time CHECK ((expiration_time >= 0))
);


--
-- TOC entry 129 (OID 33938)
-- Name: ezsubtree_notification_rule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezsubtree_notification_rule (
    id integer DEFAULT nextval('ezsubtree_notification_rule_s'::text) NOT NULL,
    address character varying(255) DEFAULT '' NOT NULL,
    use_digest integer DEFAULT '0' NOT NULL,
    node_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 130 (OID 33946)
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
-- TOC entry 131 (OID 33955)
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
-- TOC entry 132 (OID 33965)
-- Name: ezurl_object_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezurl_object_link (
    url_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_id integer DEFAULT '0' NOT NULL,
    contentobject_attribute_version integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 133 (OID 33972)
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
-- TOC entry 134 (OID 33983)
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
-- TOC entry 135 (OID 33991)
-- Name: ezuser_accountkey; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_accountkey (
    id integer DEFAULT nextval('ezuser_accountkey_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    hash_key character varying(32) DEFAULT '' NOT NULL,
    "time" integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 136 (OID 33999)
-- Name: ezuser_discountrule; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_discountrule (
    id integer DEFAULT nextval('ezuser_discountrule_s'::text) NOT NULL,
    discountrule_id integer,
    contentobject_id integer,
    name character varying(255) DEFAULT '' NOT NULL
);


--
-- TOC entry 137 (OID 34005)
-- Name: ezuser_role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_role (
    id integer DEFAULT nextval('ezuser_role_s'::text) NOT NULL,
    role_id integer,
    contentobject_id integer
);


--
-- TOC entry 138 (OID 34011)
-- Name: ezuser_setting; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezuser_setting (
    user_id integer DEFAULT '0' NOT NULL,
    is_enabled integer DEFAULT '0' NOT NULL,
    max_login integer
);


--
-- TOC entry 139 (OID 34017)
-- Name: ezvattype; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezvattype (
    id integer DEFAULT nextval('ezvattype_s'::text) NOT NULL,
    name character varying(255) DEFAULT '' NOT NULL,
    percentage double precision
);


--
-- TOC entry 140 (OID 34023)
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
-- TOC entry 141 (OID 34033)
-- Name: ezwishlist; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezwishlist (
    id integer DEFAULT nextval('ezwishlist_s'::text) NOT NULL,
    user_id integer DEFAULT '0' NOT NULL,
    productcollection_id integer DEFAULT '0' NOT NULL
);


--
-- TOC entry 142 (OID 34040)
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
-- TOC entry 143 (OID 34053)
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
-- TOC entry 144 (OID 34062)
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
-- TOC entry 145 (OID 34072)
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
-- TOC entry 146 (OID 34082)
-- Name: ezworkflow_group_link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE ezworkflow_group_link (
    workflow_id integer DEFAULT '0' NOT NULL,
    group_id integer DEFAULT '0' NOT NULL,
    workflow_version integer DEFAULT '0' NOT NULL,
    group_name character varying(255)
);


--
-- TOC entry 147 (OID 34089)
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
-- TOC entry 153 (OID 33357)
-- Name: ezcollab_group_path62; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcollab_group_path62 ON ezcollab_group USING btree (path_string);


--
-- TOC entry 152 (OID 33358)
-- Name: ezcollab_group_depth63; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcollab_group_depth63 ON ezcollab_group USING btree (depth);


--
-- TOC entry 164 (OID 33475)
-- Name: ezcontentbrowsebookmark_user228; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentbrowsebookmark_user228 ON ezcontentbrowsebookmark USING btree (user_id);


--
-- TOC entry 166 (OID 33485)
-- Name: ezcontentbrowserecent_user243; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentbrowserecent_user243 ON ezcontentbrowserecent USING btree (user_id);


--
-- TOC entry 168 (OID 33497)
-- Name: ezcontentclass_version262; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentclass_version262 ON ezcontentclass USING btree ("version");


--
-- TOC entry 174 (OID 33558)
-- Name: ezcontentobject_attribute_contentobject_id364; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_attribute_contentobject_id364 ON ezcontentobject_attribute USING btree (contentobject_id);


--
-- TOC entry 175 (OID 33559)
-- Name: ezcontentobject_attribute_language_code365; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_attribute_language_code365 ON ezcontentobject_attribute USING btree (language_code);


--
-- TOC entry 176 (OID 33560)
-- Name: sort_key_int366; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sort_key_int366 ON ezcontentobject_attribute USING btree (sort_key_int);


--
-- TOC entry 177 (OID 33561)
-- Name: sort_key_string367; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sort_key_string367 ON ezcontentobject_attribute USING btree (sort_key_string);


--
-- TOC entry 184 (OID 33591)
-- Name: ezcontentobject_tree_path416; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_path416 ON ezcontentobject_tree USING btree (path_string);


--
-- TOC entry 183 (OID 33592)
-- Name: ezcontentobject_tree_p_node_id417; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_p_node_id417 ON ezcontentobject_tree USING btree (parent_node_id);


--
-- TOC entry 181 (OID 33593)
-- Name: ezcontentobject_tree_co_id418; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_co_id418 ON ezcontentobject_tree USING btree (contentobject_id);


--
-- TOC entry 182 (OID 33594)
-- Name: ezcontentobject_tree_depth419; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezcontentobject_tree_depth419 ON ezcontentobject_tree USING btree (depth);


--
-- TOC entry 190 (OID 33636)
-- Name: ezenumobjectvalue_co_attr_id_co_attr_ver489; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezenumobjectvalue_co_attr_id_co_attr_ver489 ON ezenumobjectvalue USING btree (contentobject_attribute_id, contentobject_attribute_version);


--
-- TOC entry 192 (OID 33647)
-- Name: ezenumvalue_co_cl_attr_id_co_class_att_ver505; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezenumvalue_co_cl_attr_id_co_class_att_ver505 ON ezenumvalue USING btree (contentclass_attribute_id, contentclass_attribute_version);


--
-- TOC entry 204 (OID 33748)
-- Name: ezmodule_run_workflow_process_id_s670; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX ezmodule_run_workflow_process_id_s670 ON ezmodule_run USING btree (workflow_process_id);


--
-- TOC entry 216 (OID 33848)
-- Name: ezpreferences_name839; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezpreferences_name839 ON ezpreferences USING btree (name);


--
-- TOC entry 225 (OID 33896)
-- Name: ezsearch_object_word_link_object919; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_object919 ON ezsearch_object_word_link USING btree (contentobject_id);


--
-- TOC entry 226 (OID 33897)
-- Name: ezsearch_object_word_link_word920; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_word920 ON ezsearch_object_word_link USING btree (word_id);


--
-- TOC entry 222 (OID 33898)
-- Name: ezsearch_object_word_link_frequency921; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_frequency921 ON ezsearch_object_word_link USING btree (frequency);


--
-- TOC entry 223 (OID 33899)
-- Name: ezsearch_object_word_link_identifier922; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_identifier922 ON ezsearch_object_word_link USING btree (identifier);


--
-- TOC entry 224 (OID 33900)
-- Name: ezsearch_object_word_link_integer_value923; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_object_word_link_integer_value923 ON ezsearch_object_word_link USING btree (integer_value);


--
-- TOC entry 230 (OID 33920)
-- Name: ezsearch_word960; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezsearch_word960 ON ezsearch_word USING btree (word);


--
-- TOC entry 232 (OID 33937)
-- Name: expiration_time986; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX expiration_time986 ON ezsession USING btree (expiration_time);


--
-- TOC entry 236 (OID 33954)
-- Name: eztrigger_def_id1015; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX eztrigger_def_id1015 ON eztrigger USING btree (module_name, function_name, connect_type);


--
-- TOC entry 240 (OID 33982)
-- Name: ezurlalias_source_md51059; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezurlalias_source_md51059 ON ezurlalias USING btree (source_md5);


--
-- TOC entry 245 (OID 34010)
-- Name: ezuser_role_contentobject_id1112; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezuser_role_contentobject_id1112 ON ezuser_role USING btree (contentobject_id);


--
-- TOC entry 248 (OID 34032)
-- Name: ezwaituntildateevalue_wf_ev_id_wf_ver1151; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX ezwaituntildateevalue_wf_ev_id_wf_ver1151 ON ezwaituntildatevalue USING btree (workflow_event_id, workflow_event_version);


--
-- TOC entry 148 (OID 33325)
-- Name: ezapprove_items12_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezapprove_items
    ADD CONSTRAINT ezapprove_items12_key PRIMARY KEY (id);


--
-- TOC entry 149 (OID 33332)
-- Name: ezbasket24_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezbasket
    ADD CONSTRAINT ezbasket24_key PRIMARY KEY (id);


--
-- TOC entry 150 (OID 33341)
-- Name: ezbinaryfile36_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezbinaryfile
    ADD CONSTRAINT ezbinaryfile36_key PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 151 (OID 33355)
-- Name: ezcollab_group50_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_group
    ADD CONSTRAINT ezcollab_group50_key PRIMARY KEY (id);


--
-- TOC entry 154 (OID 33376)
-- Name: ezcollab_item71_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item
    ADD CONSTRAINT ezcollab_item71_key PRIMARY KEY (id);


--
-- TOC entry 155 (OID 33388)
-- Name: ezcollab_item_group_link95_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_group_link
    ADD CONSTRAINT ezcollab_item_group_link95_key PRIMARY KEY (collaboration_id, group_id, user_id);


--
-- TOC entry 156 (OID 33399)
-- Name: ezcollab_item_message_link112_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_message_link
    ADD CONSTRAINT ezcollab_item_message_link112_key PRIMARY KEY (id);


--
-- TOC entry 157 (OID 33412)
-- Name: ezcollab_item_participant_link128_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_participant_link
    ADD CONSTRAINT ezcollab_item_participant_link128_key PRIMARY KEY (collaboration_id, participant_id);


--
-- TOC entry 158 (OID 33421)
-- Name: ezcollab_item_status146_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_item_status
    ADD CONSTRAINT ezcollab_item_status146_key PRIMARY KEY (collaboration_id, user_id);


--
-- TOC entry 159 (OID 33428)
-- Name: ezcollab_notification_rule160_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_notification_rule
    ADD CONSTRAINT ezcollab_notification_rule160_key PRIMARY KEY (id);


--
-- TOC entry 160 (OID 33440)
-- Name: ezcollab_profile172_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_profile
    ADD CONSTRAINT ezcollab_profile172_key PRIMARY KEY (id);


--
-- TOC entry 161 (OID 33458)
-- Name: ezcollab_simple_message187_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcollab_simple_message
    ADD CONSTRAINT ezcollab_simple_message187_key PRIMARY KEY (id);


--
-- TOC entry 162 (OID 33465)
-- Name: ezcontent_translation210_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontent_translation
    ADD CONSTRAINT ezcontent_translation210_key PRIMARY KEY (id);


--
-- TOC entry 163 (OID 33473)
-- Name: ezcontentbrowsebookmark222_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentbrowsebookmark
    ADD CONSTRAINT ezcontentbrowsebookmark222_key PRIMARY KEY (id);


--
-- TOC entry 165 (OID 33483)
-- Name: ezcontentbrowserecent236_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentbrowserecent
    ADD CONSTRAINT ezcontentbrowserecent236_key PRIMARY KEY (id);


--
-- TOC entry 167 (OID 33495)
-- Name: ezcontentclass251_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclass
    ADD CONSTRAINT ezcontentclass251_key PRIMARY KEY (id, "version");


--
-- TOC entry 169 (OID 33514)
-- Name: ezcontentclass_attribute270_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclass_attribute
    ADD CONSTRAINT ezcontentclass_attribute270_key PRIMARY KEY (id, "version");


--
-- TOC entry 170 (OID 33521)
-- Name: ezcontentclass_classgroup303_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclass_classgroup
    ADD CONSTRAINT ezcontentclass_classgroup303_key PRIMARY KEY (contentclass_id, contentclass_version, group_id);


--
-- TOC entry 171 (OID 33530)
-- Name: ezcontentclassgroup316_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentclassgroup
    ADD CONSTRAINT ezcontentclassgroup316_key PRIMARY KEY (id);


--
-- TOC entry 172 (OID 33541)
-- Name: ezcontentobject331_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject
    ADD CONSTRAINT ezcontentobject331_key PRIMARY KEY (id);


--
-- TOC entry 173 (OID 33556)
-- Name: ezcontentobject_attribute351_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_attribute
    ADD CONSTRAINT ezcontentobject_attribute351_key PRIMARY KEY (id, "version");


--
-- TOC entry 178 (OID 33568)
-- Name: ezcontentobject_link375_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_link
    ADD CONSTRAINT ezcontentobject_link375_key PRIMARY KEY (id);


--
-- TOC entry 179 (OID 33575)
-- Name: ezcontentobject_name388_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_name
    ADD CONSTRAINT ezcontentobject_name388_key PRIMARY KEY (contentobject_id, content_version, content_translation);


--
-- TOC entry 180 (OID 33589)
-- Name: ezcontentobject_tree402_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_tree
    ADD CONSTRAINT ezcontentobject_tree402_key PRIMARY KEY (node_id);


--
-- TOC entry 185 (OID 33605)
-- Name: ezcontentobject_version427_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezcontentobject_version
    ADD CONSTRAINT ezcontentobject_version427_key PRIMARY KEY (id);


--
-- TOC entry 186 (OID 33611)
-- Name: ezdiscountrule445_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezdiscountrule
    ADD CONSTRAINT ezdiscountrule445_key PRIMARY KEY (id);


--
-- TOC entry 187 (OID 33618)
-- Name: ezdiscountsubrule456_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezdiscountsubrule
    ADD CONSTRAINT ezdiscountsubrule456_key PRIMARY KEY (id);


--
-- TOC entry 188 (OID 33625)
-- Name: ezdiscountsubrule_value470_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezdiscountsubrule_value
    ADD CONSTRAINT ezdiscountsubrule_value470_key PRIMARY KEY (discountsubrule_id, value, issection);


--
-- TOC entry 189 (OID 33634)
-- Name: ezenumobjectvalue482_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezenumobjectvalue
    ADD CONSTRAINT ezenumobjectvalue482_key PRIMARY KEY (contentobject_attribute_id, contentobject_attribute_version, enumid);


--
-- TOC entry 191 (OID 33645)
-- Name: ezenumvalue497_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezenumvalue
    ADD CONSTRAINT ezenumvalue497_key PRIMARY KEY (id, contentclass_attribute_id, contentclass_attribute_version);


--
-- TOC entry 193 (OID 33654)
-- Name: ezforgot_password513_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezforgot_password
    ADD CONSTRAINT ezforgot_password513_key PRIMARY KEY (id);


--
-- TOC entry 194 (OID 33664)
-- Name: ezgeneral_digest_user_settings526_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezgeneral_digest_user_settings
    ADD CONSTRAINT ezgeneral_digest_user_settings526_key PRIMARY KEY (id);


--
-- TOC entry 195 (OID 33674)
-- Name: ezimage541_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezimage
    ADD CONSTRAINT ezimage541_key PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 196 (OID 33685)
-- Name: ezimagevariation556_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezimagevariation
    ADD CONSTRAINT ezimagevariation556_key PRIMARY KEY (contentobject_attribute_id, "version", requested_width, requested_height);


--
-- TOC entry 197 (OID 33692)
-- Name: ezinfocollection573_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezinfocollection
    ADD CONSTRAINT ezinfocollection573_key PRIMARY KEY (id);


--
-- TOC entry 198 (OID 33702)
-- Name: ezinfocollection_attribute585_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezinfocollection_attribute
    ADD CONSTRAINT ezinfocollection_attribute585_key PRIMARY KEY (id);


--
-- TOC entry 199 (OID 33708)
-- Name: ezkeyword600_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezkeyword
    ADD CONSTRAINT ezkeyword600_key PRIMARY KEY (id);


--
-- TOC entry 200 (OID 33715)
-- Name: ezkeyword_attribute_link612_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezkeyword_attribute_link
    ADD CONSTRAINT ezkeyword_attribute_link612_key PRIMARY KEY (id);


--
-- TOC entry 201 (OID 33724)
-- Name: ezmedia624_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezmedia
    ADD CONSTRAINT ezmedia624_key PRIMARY KEY (contentobject_attribute_id, "version");


--
-- TOC entry 202 (OID 33738)
-- Name: ezmessage646_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezmessage
    ADD CONSTRAINT ezmessage646_key PRIMARY KEY (id);


--
-- TOC entry 203 (OID 33746)
-- Name: ezmodule_run663_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezmodule_run
    ADD CONSTRAINT ezmodule_run663_key PRIMARY KEY (id);


--
-- TOC entry 205 (OID 33757)
-- Name: eznode_assignment678_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznode_assignment
    ADD CONSTRAINT eznode_assignment678_key PRIMARY KEY (id);


--
-- TOC entry 206 (OID 33768)
-- Name: eznotificationcollection696_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznotificationcollection
    ADD CONSTRAINT eznotificationcollection696_key PRIMARY KEY (id);


--
-- TOC entry 207 (OID 33777)
-- Name: eznotificationcollection_item711_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznotificationcollection_item
    ADD CONSTRAINT eznotificationcollection_item711_key PRIMARY KEY (id);


--
-- TOC entry 208 (OID 33791)
-- Name: eznotificationevent725_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eznotificationevent
    ADD CONSTRAINT eznotificationevent725_key PRIMARY KEY (id);


--
-- TOC entry 209 (OID 33802)
-- Name: ezoperation_memento745_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezoperation_memento
    ADD CONSTRAINT ezoperation_memento745_key PRIMARY KEY (id, memento_key);


--
-- TOC entry 210 (OID 33817)
-- Name: ezorder759_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezorder
    ADD CONSTRAINT ezorder759_key PRIMARY KEY (id);


--
-- TOC entry 211 (OID 33824)
-- Name: ezorder_item778_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezorder_item
    ADD CONSTRAINT ezorder_item778_key PRIMARY KEY (id);


--
-- TOC entry 212 (OID 33829)
-- Name: ezpolicy792_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpolicy
    ADD CONSTRAINT ezpolicy792_key PRIMARY KEY (id);


--
-- TOC entry 213 (OID 33835)
-- Name: ezpolicy_limitation806_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpolicy_limitation
    ADD CONSTRAINT ezpolicy_limitation806_key PRIMARY KEY (id);


--
-- TOC entry 214 (OID 33840)
-- Name: ezpolicy_limitation_value821_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpolicy_limitation_value
    ADD CONSTRAINT ezpolicy_limitation_value821_key PRIMARY KEY (id);


--
-- TOC entry 215 (OID 33846)
-- Name: ezpreferences833_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezpreferences
    ADD CONSTRAINT ezpreferences833_key PRIMARY KEY (id);


--
-- TOC entry 217 (OID 33852)
-- Name: ezproductcollection847_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezproductcollection
    ADD CONSTRAINT ezproductcollection847_key PRIMARY KEY (id);


--
-- TOC entry 218 (OID 33860)
-- Name: ezproductcollection_item858_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezproductcollection_item
    ADD CONSTRAINT ezproductcollection_item858_key PRIMARY KEY (id);


--
-- TOC entry 219 (OID 33870)
-- Name: ezproductcollection_item_opt875_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezproductcollection_item_opt
    ADD CONSTRAINT ezproductcollection_item_opt875_key PRIMARY KEY (id);


--
-- TOC entry 220 (OID 33877)
-- Name: ezrole891_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezrole
    ADD CONSTRAINT ezrole891_key PRIMARY KEY (id);


--
-- TOC entry 221 (OID 33894)
-- Name: ezsearch_object_word_link904_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_object_word_link
    ADD CONSTRAINT ezsearch_object_word_link904_key PRIMARY KEY (id);


--
-- TOC entry 227 (OID 33907)
-- Name: ezsearch_return_count931_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_return_count
    ADD CONSTRAINT ezsearch_return_count931_key PRIMARY KEY (id);


--
-- TOC entry 228 (OID 33912)
-- Name: ezsearch_search_phrase944_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_search_phrase
    ADD CONSTRAINT ezsearch_search_phrase944_key PRIMARY KEY (id);


--
-- TOC entry 229 (OID 33918)
-- Name: ezsearch_word955_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsearch_word
    ADD CONSTRAINT ezsearch_word955_key PRIMARY KEY (id);


--
-- TOC entry 231 (OID 33925)
-- Name: ezsection968_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsection
    ADD CONSTRAINT ezsection968_key PRIMARY KEY (id);


--
-- TOC entry 233 (OID 33935)
-- Name: ezsession981_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsession
    ADD CONSTRAINT ezsession981_key PRIMARY KEY (session_key);


--
-- TOC entry 234 (OID 33944)
-- Name: ezsubtree_notification_rule994_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezsubtree_notification_rule
    ADD CONSTRAINT ezsubtree_notification_rule994_key PRIMARY KEY (id);


--
-- TOC entry 235 (OID 33952)
-- Name: eztrigger1007_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY eztrigger
    ADD CONSTRAINT eztrigger1007_key PRIMARY KEY (id);


--
-- TOC entry 237 (OID 33963)
-- Name: ezurl1023_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezurl
    ADD CONSTRAINT ezurl1023_key PRIMARY KEY (id);


--
-- TOC entry 238 (OID 33970)
-- Name: ezurl_object_link1039_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezurl_object_link
    ADD CONSTRAINT ezurl_object_link1039_key PRIMARY KEY (url_id, contentobject_attribute_id, contentobject_attribute_version);


--
-- TOC entry 239 (OID 33980)
-- Name: ezurlalias1051_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezurlalias
    ADD CONSTRAINT ezurlalias1051_key PRIMARY KEY (id);


--
-- TOC entry 241 (OID 33989)
-- Name: ezuser1067_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser
    ADD CONSTRAINT ezuser1067_key PRIMARY KEY (contentobject_id);


--
-- TOC entry 242 (OID 33997)
-- Name: ezuser_accountkey1081_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_accountkey
    ADD CONSTRAINT ezuser_accountkey1081_key PRIMARY KEY (id);


--
-- TOC entry 243 (OID 34003)
-- Name: ezuser_discountrule1094_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_discountrule
    ADD CONSTRAINT ezuser_discountrule1094_key PRIMARY KEY (id);


--
-- TOC entry 244 (OID 34008)
-- Name: ezuser_role1107_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_role
    ADD CONSTRAINT ezuser_role1107_key PRIMARY KEY (id);


--
-- TOC entry 246 (OID 34015)
-- Name: ezuser_setting1120_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezuser_setting
    ADD CONSTRAINT ezuser_setting1120_key PRIMARY KEY (user_id);


--
-- TOC entry 247 (OID 34021)
-- Name: ezvattype1132_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezvattype
    ADD CONSTRAINT ezvattype1132_key PRIMARY KEY (id);


--
-- TOC entry 249 (OID 34030)
-- Name: ezwaituntildatevalue1144_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezwaituntildatevalue
    ADD CONSTRAINT ezwaituntildatevalue1144_key PRIMARY KEY (id, workflow_event_id, workflow_event_version);


--
-- TOC entry 250 (OID 34038)
-- Name: ezwishlist1159_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezwishlist
    ADD CONSTRAINT ezwishlist1159_key PRIMARY KEY (id);


--
-- TOC entry 251 (OID 34051)
-- Name: ezworkflow1171_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow
    ADD CONSTRAINT ezworkflow1171_key PRIMARY KEY (id, "version");


--
-- TOC entry 252 (OID 34060)
-- Name: ezworkflow_assign1189_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_assign
    ADD CONSTRAINT ezworkflow_assign1189_key PRIMARY KEY (id);


--
-- TOC entry 253 (OID 34070)
-- Name: ezworkflow_event1203_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_event
    ADD CONSTRAINT ezworkflow_event1203_key PRIMARY KEY (id, "version");


--
-- TOC entry 254 (OID 34080)
-- Name: ezworkflow_group1226_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_group
    ADD CONSTRAINT ezworkflow_group1226_key PRIMARY KEY (id);


--
-- TOC entry 255 (OID 34087)
-- Name: ezworkflow_group_link1241_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_group_link
    ADD CONSTRAINT ezworkflow_group_link1241_key PRIMARY KEY (workflow_id, group_id, workflow_version);


--
-- TOC entry 256 (OID 34110)
-- Name: ezworkflow_process1254_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ezworkflow_process
    ADD CONSTRAINT ezworkflow_process1254_key PRIMARY KEY (id);


