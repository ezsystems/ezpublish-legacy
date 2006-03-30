dropdb   trunk_clustering
createdb trunk_clustering

time -p pg_restore -Ft -d trunk_clustering trunk_clustering.tdmp
#time -p pg_restore -Fc -d trunk_clustering trunk_clustering.cdmp
