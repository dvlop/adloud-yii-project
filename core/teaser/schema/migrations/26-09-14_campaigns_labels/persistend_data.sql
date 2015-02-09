-- Sequence: label_id_seq

-- DROP SEQUENCE label_id_seq;

CREATE SEQUENCE label_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE label_id_seq
  OWNER TO web;



-- Table: label

-- DROP TABLE label;

CREATE TABLE label
(
  id bigint NOT NULL DEFAULT nextval('label_id_seq'::regclass),
  name character(240) NOT NULL,
  color character(520) NOT NULL,
  user_id bigint NOT NULL,
  CONSTRAINT label_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE label
  OWNER TO web;



-- Column: labels

-- ALTER TABLE campaign DROP COLUMN labels;

ALTER TABLE campaign ADD COLUMN labels_id bigint[];