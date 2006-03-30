DROP TABLE   file;
CREATE TABLE file (
    id   SERIAL,
    name VARCHAR(255) NOT NULL UNIQUE,
    name_hash CHAR(32) NOT NULL UNIQUE
);
