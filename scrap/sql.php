<?php

include_once( "lib/ezdb/classes/ezdb.php" );


$db =& eZDB::instance();

function getmicrotime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

$start_t = getmicrotime();

// $db->query( "select * from my_node" );
// $db->query( "
// select node.id
// from
//   my_node as node,
//   my_permission_r as perm_r
// where
//       perm_r.user_group_id=1
//   AND node.permission_id=perm_r.permission_id
// " );
$db->query( "
select node.id
from
  my_node as node,
  my_permission as perm
where
      perm.user_group_id=1
  AND perm.can_read=1
  AND node.permission_id=perm.permission_id
" );

$end_t = getmicrotime();

$time = $end_t - $start_t;
$time *= 1000.0;

print( $time . "\n" );

?>
