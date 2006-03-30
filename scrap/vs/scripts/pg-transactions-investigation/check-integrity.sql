-- both queries should return no rows.

SELECT f.lob_id AS only_in_ezdbfile FROM ezdbfile f LEFT JOIN pg_largeobject lo ON f.lob_id=lo.loid WHERE lo.loid IS NULL;
SELECT lo.loid AS only_in_pg_largeobject FROM pg_largeobject lo LEFT JOIN ezdbfile f ON lo.loid=f.lob_id WHERE f.lob_id IS NULL;
