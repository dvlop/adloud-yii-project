-- Column: status

-- ALTER TABLE target_list DROP COLUMN status;

ALTER TABLE target_list ADD COLUMN status smallint;
ALTER TABLE target_list ALTER COLUMN status SET NOT NULL;
ALTER TABLE target_list ALTER COLUMN status SET DEFAULT 1;

-- Column: users

-- ALTER TABLE target_list DROP COLUMN users;

ALTER TABLE target_list ADD COLUMN users bigint;
