-- Columns: black_list & white_list in ads

-- ALTER TABLE ads DROP COLUMN black_list;

ALTER TABLE ads ADD COLUMN black_list bigint[];

-- ALTER TABLE ads DROP COLUMN white_list;

ALTER TABLE ads ADD COLUMN white_list bigint[];