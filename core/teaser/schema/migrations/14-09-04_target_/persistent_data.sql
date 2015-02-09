-- Sequence: target_category_id_seq

-- DROP SEQUENCE target_category_id_seq;

CREATE SEQUENCE target_category_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE target_category_id_seq
  OWNER TO web;

-- Sequence: target_list_id_seq

-- DROP SEQUENCE target_list_id_seq;

CREATE SEQUENCE target_list_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1000
  CACHE 1;
ALTER TABLE target_list_id_seq
  OWNER TO web;

-- Table: target_category

-- DROP TABLE target_category;

CREATE TABLE target_category
(
  id bigint NOT NULL DEFAULT nextval('target_category_id_seq'::regclass),
  name character varying(64) NOT NULL,
  CONSTRAINT target_category_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE target_category
  OWNER TO web;

-- Table: target_list

-- DROP TABLE target_list;

CREATE TABLE target_list
(
  id bigint NOT NULL DEFAULT nextval('target_list_id_seq'::regclass),
  user_id bigint NOT NULL,
  name character varying(255) NOT NULL,
  category_id bigint NOT NULL,
  CONSTRAINT target_list_pk PRIMARY KEY (id),
  CONSTRAINT target_list_category_id FOREIGN KEY (category_id)
      REFERENCES target_category (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT target_list_user_id FOREIGN KEY (user_id)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE target_list
  OWNER TO web;
