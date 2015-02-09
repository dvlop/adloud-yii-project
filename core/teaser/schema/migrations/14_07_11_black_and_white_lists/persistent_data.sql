-- Sequence: lists_id_seq

-- DROP SEQUENCE lists_id_seq;

CREATE SEQUENCE lists_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 20
  CACHE 1;
ALTER TABLE lists_id_seq
  OWNER TO web;


-- Table: lists

-- DROP TABLE lists;

CREATE TABLE lists
(
  id bigint NOT NULL DEFAULT nextval('lists_id_seq'::regclass),
  name character(240) NOT NULL,
  type smallint NOT NULL,
  user_id bigint NOT NULL,
  sites bigint[] NOT NULL,
  campaigns bigint[] NOT NULL,
  description character(512),
  CONSTRAINT lists_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE lists
  OWNER TO web;

-- Index: lists_type

-- DROP INDEX lists_type;

CREATE INDEX lists_type
  ON lists
  USING btree
  (type);

-- Index: lists_user_id

-- DROP INDEX lists_user_id;

CREATE INDEX lists_user_id
  ON lists
  USING btree
  (user_id);

-- Columns: black_list & white_list in campaigns

--Drop old column block_list

ALTER TABLE campaign DROP COLUMN block_list;

--ALTER TABLE campaign DROP COLUMN black_list;

ALTER TABLE campaign ADD COLUMN black_list bigint[];

--ALTER TABLE campaign DROP COLUMN white_list;

ALTER TABLE campaign ADD COLUMN white_list bigint[];


-- Columns: black_list & white_list in ads

ALTER TABLE ads DROP COLUMN black_list;

ALTER TABLE ads ADD COLUMN black_list bigint[];

-- ALTER TABLE ads DROP COLUMN white_list;

ALTER TABLE ads ADD COLUMN white_list bigint[];
