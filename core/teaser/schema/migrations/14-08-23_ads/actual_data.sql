-- Column: ua_device

-- ALTER TABLE ads DROP COLUMN ua_device;

ALTER TABLE ads ADD COLUMN ua_device character varying(32)[];

-- Column: ua_browser

-- ALTER TABLE ads DROP COLUMN ua_browser;

ALTER TABLE ads ADD COLUMN ua_browser character varying(32)[];

-- Column: ua_os

-- ALTER TABLE ads DROP COLUMN ua_os;

ALTER TABLE ads ADD COLUMN ua_os character varying(32)[];

-- Column: ua_browser

-- ALTER TABLE ads DROP COLUMN ua_browser;

ALTER TABLE ads ADD COLUMN ua_device_model bigint[];

-- Column: ua_os

-- ALTER TABLE ads DROP COLUMN ua_os;

ALTER TABLE ads ADD COLUMN ua_os_ver bigint[];
