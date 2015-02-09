-- Table: user_agent

-- DROP TABLE user_agent;

CREATE TABLE user_agent
(
  id bigserial NOT NULL,
  value character varying(255),
  name character varying(255) NOT NULL,
  type character varying(64) NOT NULL,
  is_checked boolean,
  CONSTRAINT user_agent_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE user_agent
  OWNER TO web;
