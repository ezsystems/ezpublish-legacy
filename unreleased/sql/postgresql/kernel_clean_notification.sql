--
-- TOC Entry ID 80 (OID 672073)
--
-- Name: eznotification_rule_s Type: SEQUENCE Owner: sp
--

CREATE SEQUENCE "eznotification_rule_s" start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

--
-- TOC Entry ID 159 (OID 672075)
--
-- Name: eznotification_rule Type: TABLE Owner: sp
--

CREATE TABLE "eznotification_rule" (
	"id" integer DEFAULT nextval('eznotification_rule_s'::text) NOT NULL,
	"type" character varying(250) DEFAULT '' NOT NULL,
	"contentclass_name" character varying(250) DEFAULT '' NOT NULL,
	"path" character varying(250),
	"keyword" character varying(250),
	"has_constraint" smallint DEFAULT '0' NOT NULL,
	Constraint "eznotification_rule_pkey" Primary Key ("id")
);

--
-- TOC Entry ID 160 (OID 672078)
--
-- Name: eznotification_user_link Type: TABLE Owner: sp
--

CREATE TABLE "eznotification_user_link" (
	"rule_id" integer DEFAULT '0' NOT NULL,
	"user_id" integer DEFAULT '0' NOT NULL,
	"send_method" character varying(50) DEFAULT '' NOT NULL,
	"send_weekday" character varying(50) DEFAULT '' NOT NULL,
	"send_time" character varying(50) DEFAULT '' NOT NULL,
	"destination_address" character varying(50) DEFAULT '' NOT NULL,
	Constraint "eznotification_user_link_pkey" Primary Key ("rule_id", "user_id")
);

--
-- Data for TOC Entry ID 252 (OID 672075)
--
-- Name: eznotification_rule Type: TABLE DATA Owner: sp
--


--
-- Data for TOC Entry ID 253 (OID 672078)
--
-- Name: eznotification_user_link Type: TABLE DATA Owner: sp
--

--
-- TOC Entry ID 81 (OID 672073)
--
-- Name: eznotification_rule_s Type: SEQUENCE SET Owner: sp
--

SELECT setval ('"eznotification_rule_s"', 1, false);
