DROP TABLE ezdbfile;
CREATE TABLE ezdbfile (
  id        SERIAL       PRIMARY KEY,
  datatype  VARCHAR(60)  NOT NULL DEFAULT 'application/octet-stream',
  name      VARCHAR(255) NOT NULL UNIQUE,
  name_hash CHAR(32)     NOT NULL UNIQUE,
  scope     VARCHAR(20)  NOT NULL,
  size      BIGINT       NOT NULL,
  mtime     INT          NOT NULL,
  lob_id    OID
);
