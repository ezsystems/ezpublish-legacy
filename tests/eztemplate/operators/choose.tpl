3|choose( 'a', 'b', 'c', 'd', 'e' )='{3|choose( 'a', 'b', 'c', 'd', 'e' )}'


{let select=4}
$select|choose( 'a', 'b', 'c', 'd', 'e' )='{$select|choose( 'a', 'b', 'c', 'd', 'e' )}'
{/let}



{let select=1 first='a' second='b'}
$select|choose( $first, $second, 'c', 'd', 'e' )='{$select|choose( $first, $second, 'c', 'd', 'e' )}'
{/let}
