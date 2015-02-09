CREATE TABLE "public"."user_add_money_request" (
"id" int8 DEFAULT nextval('user_add_money_request_id_seq'::regclass) NOT NULL,
"amount" float8,
"user_id" int8
)
WITH (OIDS=FALSE)
;
