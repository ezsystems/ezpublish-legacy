Input: static, offset=static, length=static
{array( 1, 2, 3, 4, 5, 6 )|extract( 2 )|implode( ',' )}
{array( 1, 2, 3, 4, 5, 6 )|extract( 2, 2 )|implode( ',' )}
{array( 1, 2, 3, 4, 5, 6 )|extract( 4, 4 )|implode( ',' )}
{array( 1, 2, 3, 4, 5, 6 )|extract_left( 3 )|implode( ',' )}
{array( 1, 2, 3, 4, 5, 6 )|extract_right( 3 )|implode( ',' )}
===============================================
Input: non-static, offset=static, length=static

{let arr=array( 1, 2, 3, 4, 5, 6 )}
{$arr|extract( 2 )|implode( ',' )}
{$arr|extract( 2, 2 )|implode( ',' )}
{$arr|extract( 4, 4 )|implode( ',' )}
{$arr|extract_left( 3 )|implode( ',' )}
{$arr|extract_right( 3 )|implode( ',' )}

{/let}
===============================================
Input: non-static, offset=non-static, length=static

{let arr=array( 1, 2, 3, 4, 5, 6 ) offs='2'}
{$arr|extract( $offs )|implode( ',' )}
{$arr|extract( $offs, 2 )|implode( ',' )}
{$arr|extract( sum( $offs, 2 ), 4 )|implode( ',' )}
{$arr|extract_left( $offs|inc )|implode( ',' )}
{$arr|extract_right( $offs|inc )|implode( ',' )}

{/let}
===============================================
Input: non-static, offset=non-static, length=non-static

{let arr=array( 1, 2, 3, 4, 5, 6 ) offs='2' len='2'}
{$arr|extract( $offs )|implode( ',' )}
{$arr|extract( $offs, $len )|implode( ',' )}
{$arr|extract( sum( $offs, 2 ), sum( $len, 2 ) )|implode( ',' )}
{$arr|extract_left( $offs|inc )|implode( ',' )}
{$arr|extract_right( $offs|inc )|implode( ',' )}

{/let}
===============================================
