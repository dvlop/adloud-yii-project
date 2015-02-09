-- Sequence: notification_id_seq

-- DROP SEQUENCE notification_id_seq;

CREATE SEQUENCE notification_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE notification_id_seq
  OWNER TO web;

-- Table: notification

-- DROP TABLE notification;

CREATE TABLE notification
(
  id bigint NOT NULL DEFAULT nextval('notification_id_seq'::regclass),
  user_id bigint NOT NULL,
  date timestamp without time zone NOT NULL,
  is_new boolean NOT NULL DEFAULT true,
  text text,
  type character varying(64),
  is_shown boolean NOT NULL DEFAULT false,
  CONSTRAINT notification_pkey PRIMARY KEY (id),
  CONSTRAINT notification_user_id FOREIGN KEY (user_id)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);
ALTER TABLE notification
  OWNER TO web;



