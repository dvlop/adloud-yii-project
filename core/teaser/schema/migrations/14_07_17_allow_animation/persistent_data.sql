-- Column: allow_animation

-- ALTER TABLE sites DROP COLUMN allow_animation;

ALTER TABLE sites ADD COLUMN allow_animation boolean;

-- Column: animation

-- ALTER TABLE ads DROP COLUMN animation;

ALTER TABLE ads ADD COLUMN animation boolean;

-- Column: sms

-- ALTER TABLE ads DROP COLUMN sms;

ALTER TABLE ads ADD COLUMN sms boolean;

-- Column: allow_sms

-- ALTER TABLE blocks DROP COLUMN allow_sms;

ALTER TABLE blocks ADD COLUMN allow_sms boolean;

-- Column: allow_animation

-- ALTER TABLE blocks DROP COLUMN allow_animation;

ALTER TABLE blocks ADD COLUMN allow_animation boolean;