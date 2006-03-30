OPTS='-b'
pg_dump $OPTS -Fp -f trunk_clustering.pdmp trunk_clustering
pg_dump $OPTS -Fc -f trunk_clustering.cdmp trunk_clustering
pg_dump $OPTS -Ft -f trunk_clustering.tdmp trunk_clustering
