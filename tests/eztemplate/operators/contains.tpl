{array( 1, array( 2, 3 ), 4)|contains( array( 2, 3 ) )}

{let arr=array(1, 2, 3, 4)}
{$arr|contains( 3 )}
{array( 1, $arr, 3, 4)|contains(2)}
{/let}