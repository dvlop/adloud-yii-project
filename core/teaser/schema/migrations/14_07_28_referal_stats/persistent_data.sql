-- Column: moderation

-- ALTER TABLE referal_stats DROP COLUMN moderation;

ALTER TABLE referal_stats ADD COLUMN moderation character varying(64);

-- Column: start_date

-- ALTER TABLE referal_stats DROP COLUMN start_date;

ALTER TABLE referal_stats ADD COLUMN start_date date;
ALTER TABLE referal_stats ALTER COLUMN start_date SET NOT NULL;
