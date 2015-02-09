-- Table: referal_stats

-- DROP TABLE referal_stats;

CREATE TABLE referal_stats
(
  id bigserial NOT NULL,
  referer_id bigint NOT NULL,
  referal_id bigint NOT NULL,
  date date NOT NULL,
  sum double precision NOT NULL,
  status boolean NOT NULL DEFAULT false,
  CONSTRAINT referal_stats_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE referal_stats
  OWNER TO web;

-- Column: referer

-- ALTER TABLE users DROP COLUMN referer;

ALTER TABLE users ADD COLUMN referer bigint;
