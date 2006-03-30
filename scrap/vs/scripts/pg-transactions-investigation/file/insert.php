#!/usr/bin/env php
<?
echo "BEGIN;\n";
echo "TRUNCATE TABLE file;\n";
for( $i=0; $i<3; $i++ )
{
    $numstring = sprintf( "%09d", $i );
    $name = "var/shop_site/cache/template-block/7/5/7/$numstring.cache";
    $name_hash = md5( $name );
    echo "INSERT INTO file (name,name_hash) VALUES ('$name', '$name_hash');\n";
}
echo "COMMIT;\n";
?>