--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: ads; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE ads (
  "text" varchar(255) NOT NULL,
  "url" varchar(400) NOT NULL,
  "shows" int8 DEFAULT 0,
  "clicks" int4 DEFAULT 0,
  "rating" float4,
  "click_price" float4 NOT NULL,
  "image_address" json NOT NULL,
  "max_clicks" int2,
  "id" int8 DEFAULT nextval('ads_id_seq'::regclass) NOT NULL,
  "campaign_id" int8 NOT NULL,
  "geo" int4[],
  "categories" int4[],
  "start_date" timestamp(6),
  "stop_date" timestamp(6),
  "status" int2,
  "moderated" int2 DEFAULT 0
);



--
-- Name: ads_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE ads_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: ads_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE ads_id_seq OWNED BY ads.id;


--
-- Name: blocks; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE blocks (
  css character varying(4000),
  shows bigint,
  clicks integer,
  id bigint NOT NULL,
  site_id bigint NOT NULL,
  categories smallint[],
  description character varying(255),
  ads_number smallint
);



--
-- Name: blocks_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE blocks_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: blocks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE blocks_id_seq OWNED BY blocks.id;


--
-- Name: campaign; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE campaign (
  description character varying(255),
  user_id bigint,
  id bigint NOT NULL,
  click_price real,
  categories smallint[],
  geo smallint[],
  start_date timestamp without time zone,
  stop_date timestamp without time zone,
  black_list bigint[]
);



--
-- Name: campaign_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE campaign_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: campaign_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE campaign_id_seq OWNED BY campaign.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE categories (
  description character varying(128),
  active smallint DEFAULT 0,
  min_click_price real,
  id smallint NOT NULL
);



--
-- Name: categories_id_seq1; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE categories_id_seq1
START WITH 28
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: categories_id_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE categories_id_seq1 OWNED BY categories.id;


--
-- Name: geo_data; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE geo_data (
  range_start character varying,
  range_end character varying,
  type smallint,
  description character varying(128)
);



--
-- Name: sites; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE sites (
  "user_id" int8 NOT NULL,
  "url" varchar(400) NOT NULL,
  "description" varchar(255),
  "categories" int2[],
  "id" int8 DEFAULT nextval('sites_id_seq'::regclass) NOT NULL,
  "moderated" int2 DEFAULT 0
)
WITH (OIDS=FALSE);



--
-- Name: sites_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE sites_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: sites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE sites_id_seq OWNED BY sites.id;


--
-- Name: user_balance_stats; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE "user_balance_stats" (
  "id" int8 DEFAULT nextval('user_balance_stats_id_seq'::regclass) NOT NULL,
  "income" float8,
  "outcome" float8,
  "date" date NOT NULL,
  "user_id" int8 NOT NULL,
  "balance" float8,
  "ctr" float8,
  "blocked_ips_count" int4,
  "geo_stats" json,
  "referer_stats" json,
  "click_time" float4
);



--
-- Name: user_balance_stats_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE user_balance_stats_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: user_balance_stats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE user_balance_stats_id_seq OWNED BY user_balance_stats.id;


--
-- Name: user_payout_request; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE user_payout_request (
  id bigint NOT NULL,
  user_id bigint,
  amount double precision,
  date_time timestamp without time zone,
  status smallint DEFAULT 0,
  actual_output double precision,
  comment character varying(255)
);



--
-- Name: user_payout_request_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE user_payout_request_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: user_payout_request_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE user_payout_request_id_seq OWNED BY user_payout_request.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: web; Tablespace:
--

CREATE TABLE users (
  id bigint NOT NULL,
  email character varying(100) NOT NULL,
  password character(102) NOT NULL,
  full_name character varying(255),
  register_date date NOT NULL,
  last_login timestamp(6) without time zone,
  access_level smallint DEFAULT 0,
  help_desc_password character varying(32)
);



--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE users_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;



--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: web
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY ads ALTER COLUMN id SET DEFAULT nextval('ads_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY blocks ALTER COLUMN id SET DEFAULT nextval('blocks_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY campaign ALTER COLUMN id SET DEFAULT nextval('campaign_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY categories ALTER COLUMN id SET DEFAULT nextval('categories_id_seq1'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY sites ALTER COLUMN id SET DEFAULT nextval('sites_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY user_balance_stats ALTER COLUMN id SET DEFAULT nextval('user_balance_stats_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY user_payout_request ALTER COLUMN id SET DEFAULT nextval('user_payout_request_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: web
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: blocks_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY blocks
ADD CONSTRAINT blocks_pkey PRIMARY KEY (id);


--
-- Name: campaign_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY campaign
ADD CONSTRAINT campaign_pkey PRIMARY KEY (id);


--
-- Name: categories_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY categories
ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: sites_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY sites
ADD CONSTRAINT sites_pkey PRIMARY KEY (id);


--
-- Name: user_balance_stats_date_user_id_key; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY user_balance_stats
ADD CONSTRAINT user_balance_stats_date_user_id_key UNIQUE (date, user_id);


--
-- Name: user_balance_stats_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY user_balance_stats
ADD CONSTRAINT user_balance_stats_pkey PRIMARY KEY (date, user_id);


--
-- Name: user_payout_request_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY user_payout_request
ADD CONSTRAINT user_payout_request_pkey PRIMARY KEY (id);


--
-- Name: users_email_key; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY users
ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace:
--

ALTER TABLE ONLY users
ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: camp_index; Type: INDEX; Schema: public; Owner: web; Tablespace:
--

CREATE INDEX camp_index ON ads USING btree (campaign_id);


--
-- Name: users_email_idx; Type: INDEX; Schema: public; Owner: web; Tablespace:
--

CREATE UNIQUE INDEX users_email_idx ON users USING btree (email);


--
-- Name: ads_campaign_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: web
--

ALTER TABLE ONLY ads
ADD CONSTRAINT ads_campaign_id_fkey FOREIGN KEY (campaign_id) REFERENCES campaign(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: blocks_site_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: web
--

ALTER TABLE ONLY blocks
ADD CONSTRAINT blocks_site_id_fkey FOREIGN KEY (site_id) REFERENCES sites(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: campaign_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: web
--

ALTER TABLE ONLY campaign
ADD CONSTRAINT campaign_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: sites_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: web
--

ALTER TABLE ONLY sites
ADD CONSTRAINT sites_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: user_balance_stats_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: web
--

ALTER TABLE ONLY user_balance_stats
ADD CONSTRAINT user_balance_stats_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

