{let arr=array( 1, 2, 3 )}
$arr|insert( 3, 4, 5, 6 )|implode( ',' )='{$arr|insert( 3, 4, 5, 6 )|implode( ',' )}'
$arr|insert( 0, -2, -1, 0 )|implode( ',' )='{$arr|insert( 0, -2, -1, 0 )|implode( ',' )}'
$arr|remove( 2 )|implode( ',' )='{$arr|remove( 2 )|implode( ',' )}'
$arr|remove( 0, 2 )|implode( ',' )='{$arr|remove( 0, 2 )|implode( ',' )}'
$arr|replace( 2, 1, 4, 5, 6 )|implode( ',' )='{$arr|replace( 2, 1, 4, 5, 6 )|implode( ',' )}'
$arr|replace( 0, 2, -1, -2 )|implode( ',' )='{$arr|replace( 0, 2, -1, -2 )|implode( ',' )}'
{/let}



{let string="123"}
$string|insert( 3, "456" )='{$string|insert( 3, "456" )}'
$string|insert( 0, "-2-10" )='{$string|insert( 0, "-2-10" )}'
$string|remove( 2 )='{$string|remove( 2 )}'
$string|remove( 0, 2 )='{$string|remove( 0, 2 )}'
$string|replace( 2, 1, "456" )='{$string|replace( 2, 1, "456" )}'
$string|replace( 0, 2, "-1-2" )='{$string|replace( 0, 2, "-1-2" )}'
{/let}
