-- Column: targets

-- ALTER TABLE ads DROP COLUMN targets;

ALTER TABLE ads ADD COLUMN targets bigint[];

-- Column: targets

-- ALTER TABLE campaign DROP COLUMN targets;

ALTER TABLE campaign ADD COLUMN targets bigint[];