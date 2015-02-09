ALTER TABLE referal_stats DROP COLUMN status;
ALTER TABLE referal_stats ADD COLUMN status smallint;
ALTER TABLE referal_stats ALTER COLUMN status SET DEFAULT 0;