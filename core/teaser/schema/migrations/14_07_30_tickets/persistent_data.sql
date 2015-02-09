-- Sequence: ticket_id_seq

-- DROP SEQUENCE ticket_id_seq;

CREATE SEQUENCE ticket_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE ticket_id_seq
  OWNER TO web;


-- Sequence: ticket_category_id_seq

-- DROP SEQUENCE ticket_category_id_seq;

CREATE SEQUENCE ticket_category_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE ticket_category_id_seq
  OWNER TO web;


-- Sequence: message_id_seq

-- DROP SEQUENCE message_id_seq;

CREATE SEQUENCE message_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE message_id_seq
  OWNER TO web;


-- Table: ticket_category

-- DROP TABLE ticket_category;

CREATE TABLE ticket_category
(
  id bigint NOT NULL DEFAULT nextval('ticket_category_id_seq'::regclass),
  name character(240) NOT NULL,
  content character(1024),
  CONSTRAINT ticket_category_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ticket_category
  OWNER TO web;


-- Table: ticket

-- DROP TABLE ticket;

CREATE TABLE ticket
(
  id bigint NOT NULL DEFAULT nextval('ticket_id_seq'::regclass),
  name character(512) NOT NULL,
  date timestamp without time zone NOT NULL,
  category_id bigint NOT NULL,
  user_id bigint NOT NULL,
  status smallint NOT NULL DEFAULT 0,
  CONSTRAINT ticket_pk PRIMARY KEY (id),
  CONSTRAINT ticket_category_id FOREIGN KEY (category_id)
      REFERENCES ticket_category (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT ticket_user_id FOREIGN KEY (user_id)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE ticket
  OWNER TO web;

-- Index: fki_ticket_category_id

-- DROP INDEX fki_ticket_category_id;

CREATE INDEX fki_ticket_category_id
  ON ticket
  USING btree
  (category_id);

-- Index: fki_ticket_user_id

-- DROP INDEX fki_ticket_user_id;

CREATE INDEX fki_ticket_user_id
  ON ticket
  USING btree
  (user_id);

-- Table: message

-- DROP TABLE message;

CREATE TABLE message
(
  id bigint NOT NULL DEFAULT nextval('message_id_seq'::regclass),
  content text NOT NULL,
  date timestamp without time zone NOT NULL,
  ticket_id bigint NOT NULL,
  user_id bigint NOT NULL,
  status smallint NOT NULL DEFAULT 0,
  is_admin boolean,
  CONSTRAINT message_pk PRIMARY KEY (id),
  CONSTRAINT message_ticket_id FOREIGN KEY (ticket_id)
      REFERENCES ticket (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT message_user_id FOREIGN KEY (user_id)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE message
  OWNER TO web;

-- Index: fki_message_ticket_id

-- DROP INDEX fki_message_ticket_id;

CREATE INDEX fki_message_ticket_id
  ON message
  USING btree
  (ticket_id);

-- Index: fki_message_user_id

-- DROP INDEX fki_message_user_id;

CREATE INDEX fki_message_user_id
  ON message
  USING btree
  (user_id);


