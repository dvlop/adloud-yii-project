--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: transaction_type; Type: TYPE; Schema: public; Owner: web
--

CREATE TYPE transaction_type AS ENUM (
  'webmaster',
  'system',
  'advertiser'
);


ALTER TYPE public.transaction_type OWNER TO web;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: ads; Type: TABLE; Schema: public; Owner: web; Tablespace: 
--

CREATE TABLE ads (
  image_location character(400),
  text character varying(255),
  click_price real,
  rating real,
  expenses real,
  max_clicks smallint,
  geo character varying(255),
  clicks integer,
  id bigint NOT NULL,
  user_id bigint,
  shows bigint,
  url character varying(400),
  categories smallint[]
);


ALTER TABLE public.ads OWNER TO web;

--
-- Name: transaction_tables; Type: TABLE; Schema: public; Owner: web; Tablespace: 
--

CREATE TABLE transaction_tables (
  date date,
  table_name character(23)
);


ALTER TABLE public.transaction_tables OWNER TO web;

--
-- Name: transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: web
--

CREATE SEQUENCE transactions_id_seq
START WITH 1
INCREMENT BY 1
NO MINVALUE
NO MAXVALUE
CACHE 1;


ALTER TABLE public.transactions_id_seq OWNER TO web;

--
-- Name: transactions_2014_03_26; Type: TABLE; Schema: public; Owner: web; Tablespace: 
--

CREATE TABLE transactions_2014_03_26 (
  amount real NOT NULL,
  description character varying(512),
  ip character varying(15),
  referer character varying(400),
  block_id bigint,
  ads_id bigint,
  id bigint DEFAULT nextval('transactions_id_seq'::regclass) NOT NULL,
  recipient_id bigint,
  sender_id bigint,
  "timestamp" timestamp without time zone,
  "from" transaction_type,
  "to" transaction_type,
  sender_balance double precision,
  recipient_balance double precision
);


ALTER TABLE public.transactions_2014_03_26 OWNER TO web;

--
-- Name: transactions_2014_03_27; Type: TABLE; Schema: public; Owner: web; Tablespace: 
--

CREATE TABLE transactions_2014_03_27 (
  amount real NOT NULL,
  description character varying(512),
  ip character varying(15),
  referer character varying(400),
  block_id bigint,
  ads_id bigint,
  id bigint DEFAULT nextval('transactions_id_seq'::regclass) NOT NULL,
  recipient_id bigint,
  sender_id bigint,
  "timestamp" timestamp without time zone,
  "from" transaction_type,
  "to" transaction_type,
  sender_balance double precision,
  recipient_balance double precision
);


ALTER TABLE public.transactions_2014_03_27 OWNER TO web;

--
-- Name: transactions_2014_03_28; Type: TABLE; Schema: public; Owner: web; Tablespace: 
--

CREATE TABLE transactions_2014_03_28 (
  amount real NOT NULL,
  description character varying(512),
  ip character varying(15),
  referer character varying(400),
  block_id bigint,
  ads_id bigint,
  id bigint DEFAULT nextval('transactions_id_seq'::regclass) NOT NULL,
  recipient_id bigint,
  sender_id bigint,
  "timestamp" timestamp without time zone,
  "from" transaction_type,
  "to" transaction_type,
  sender_balance double precision,
  recipient_balance double precision
);


ALTER TABLE public.transactions_2014_03_28 OWNER TO web;

--
-- Name: transactions_2014_03_29; Type: TABLE; Schema: public; Owner: web; Tablespace: 
--

CREATE TABLE transactions_2014_03_29 (
  amount real NOT NULL,
  description character varying(512),
  ip character varying(15),
  referer character varying(400),
  block_id bigint,
  ads_id bigint,
  id bigint DEFAULT nextval('transactions_id_seq'::regclass) NOT NULL,
  recipient_id bigint,
  sender_id bigint,
  "timestamp" timestamp without time zone,
  "from" transaction_type,
  "to" transaction_type,
  sender_balance double precision,
  recipient_balance double precision
);


ALTER TABLE public.transactions_2014_03_29 OWNER TO web;

--
-- Data for Name: ads; Type: TABLE DATA; Schema: public; Owner: web
--

COPY ads (image_location, text, click_price, rating, expenses, max_clicks, geo, clicks, id, user_id, shows, url, categories) FROM stdin;
http://localhost/teasernetwork/images/files/2/12/19/4d763b15f903a8065947a205ea3e1077.jpeg                                                                                                                                                                                                                                                                                                                       	sdfsdf	0.200000003	0.200000003	\N	222	{}	\N	129045	65	\N	http://google.com	{4,5,6,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,24,25,26,27,28,1,2}
http://localhost/teasernetwork/images/files/14/16/43/d26f7356345f5c0158fd7e1a2ee3b0f3.jpeg                                                                                                                                                                                                                                                                                                                      	test	1	1	\N	200	{}	\N	129043	65	\N	http://google.com	{4,5}
http://localhost/teasernetwork/images/files/45/35/6/54c073d7c8e7853627eec59c7daa75c3.jpeg                                                                                                                                                                                                                                                                                                                       	ршол	0.0700000003	0.0700000003	\N	9999	{}	\N	129044	65	\N	http://google.com	{4,5,6,7,8,9,10,11,12,13,14,16,17,18,19,20,21,22,23,24,25,26,27,28,1,2}
\.


--
-- Data for Name: transaction_tables; Type: TABLE DATA; Schema: public; Owner: web
--

COPY transaction_tables (date, table_name) FROM stdin;
2014-03-27	transactions_2014_03_27
2014-03-28	transactions_2014_03_28
2014-03-29	transactions_2014_03_29
2014-03-26	transactions_2014_03_26
\.


--
-- Data for Name: transactions_2014_03_26; Type: TABLE DATA; Schema: public; Owner: web
--

COPY transactions_2014_03_26 (amount, description, ip, referer, block_id, ads_id, id, recipient_id, sender_id, "timestamp", "from", "to", sender_balance, recipient_balance) FROM stdin;
100	85525	\N	\N	\N	\N	1	65	\N	2014-03-27 00:51:27.021	system	advertiser	-100	100
200	49624	\N	\N	\N	\N	2	65	\N	2014-03-27 00:51:30.254	system	advertiser	-300	300
222	83806	\N	\N	\N	\N	3	65	\N	2014-03-27 00:51:34.292	system	advertiser	-522	522
\.


--
-- Data for Name: transactions_2014_03_27; Type: TABLE DATA; Schema: public; Owner: web
--

COPY transactions_2014_03_27 (amount, description, ip, referer, block_id, ads_id, id, recipient_id, sender_id, "timestamp", "from", "to", sender_balance, recipient_balance) FROM stdin;
\.


--
-- Data for Name: transactions_2014_03_28; Type: TABLE DATA; Schema: public; Owner: web
--

COPY transactions_2014_03_28 (amount, description, ip, referer, block_id, ads_id, id, recipient_id, sender_id, "timestamp", "from", "to", sender_balance, recipient_balance) FROM stdin;
\.


--
-- Data for Name: transactions_2014_03_29; Type: TABLE DATA; Schema: public; Owner: web
--

COPY transactions_2014_03_29 (amount, description, ip, referer, block_id, ads_id, id, recipient_id, sender_id, "timestamp", "from", "to", sender_balance, recipient_balance) FROM stdin;
\.


--
-- Name: transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: web
--

SELECT pg_catalog.setval('transactions_id_seq', 6, true);


--
-- Name: ads_pkey; Type: CONSTRAINT; Schema: public; Owner: web; Tablespace: 
--

ALTER TABLE ONLY ads
ADD CONSTRAINT ads_pkey PRIMARY KEY (id);


--
-- Name: rating; Type: INDEX; Schema: public; Owner: web; Tablespace: 
--

CREATE INDEX rating ON ads USING btree (rating);


--
-- Name: rating_cats; Type: INDEX; Schema: public; Owner: web; Tablespace: 
--

CREATE INDEX rating_cats ON ads USING btree (rating, categories);


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

