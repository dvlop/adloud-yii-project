-- Table: transaction_stats

-- DROP TABLE transaction_stats;

CREATE TABLE transaction_stats
(
  id bigserial NOT NULL,
  user_id bigint,
  date date,
  comment character varying(64),
  amount double precision,
  CONSTRAINT transaction_stats_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE transaction_stats
  OWNER TO web;
